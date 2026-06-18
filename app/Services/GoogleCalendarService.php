<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleCalendarService
{
    private const AUTH_URL  = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const API       = 'https://www.googleapis.com/calendar/v3';

    private const SCOPES = [
        'openid',
        'email',
        'https://www.googleapis.com/auth/calendar.events',
        'https://www.googleapis.com/auth/calendar.readonly',
    ];

    public function configured(): bool
    {
        return ! empty(config('services.google_calendar.client_id'))
            && ! empty(config('services.google_calendar.client_secret'));
    }

    /** URL de consentimiento de Google. */
    public function authUrl(): string
    {
        $params = [
            'client_id'              => config('services.google_calendar.client_id'),
            'redirect_uri'           => config('services.google_calendar.redirect'),
            'response_type'          => 'code',
            'scope'                  => implode(' ', self::SCOPES),
            'access_type'            => 'offline',
            'prompt'                 => 'consent',
            'include_granted_scopes' => 'true',
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /** Intercambia el code por tokens y los guarda en el usuario. */
    public function connect(User $user, string $code): void
    {
        $res = Http::asForm()->post(self::TOKEN_URL, [
            'code'          => $code,
            'client_id'     => config('services.google_calendar.client_id'),
            'client_secret' => config('services.google_calendar.client_secret'),
            'redirect_uri'  => config('services.google_calendar.redirect'),
            'grant_type'    => 'authorization_code',
        ]);

        if ($res->failed()) {
            throw new RuntimeException('No se pudo conectar con Google: ' . $res->body());
        }

        $data  = $res->json();
        $email = $this->fetchEmail($data['access_token'] ?? '');

        $user->forceFill([
            'google_calendar_token'         => $data['access_token'] ?? null,
            'google_calendar_refresh_token' => $data['refresh_token'] ?? $user->google_calendar_refresh_token,
            'google_calendar_expires_at'    => now()->addSeconds(($data['expires_in'] ?? 3600) - 30),
            'google_calendar_email'         => $email ?: $user->google_calendar_email,
        ])->save();
    }

    public function disconnect(User $user): void
    {
        $user->forceFill([
            'google_calendar_token'         => null,
            'google_calendar_refresh_token' => null,
            'google_calendar_expires_at'    => null,
            'google_calendar_email'         => null,
        ])->save();
    }

    /** Devuelve un access token válido, refrescándolo si expiró. */
    public function validToken(User $user): string
    {
        if ($user->google_calendar_token
            && $user->google_calendar_expires_at
            && $user->google_calendar_expires_at->isFuture()) {
            return $user->google_calendar_token;
        }

        if (! $user->google_calendar_refresh_token) {
            throw new RuntimeException('El calendario no está conectado.');
        }

        $res = Http::asForm()->post(self::TOKEN_URL, [
            'client_id'     => config('services.google_calendar.client_id'),
            'client_secret' => config('services.google_calendar.client_secret'),
            'refresh_token' => $user->google_calendar_refresh_token,
            'grant_type'    => 'refresh_token',
        ]);

        if ($res->failed()) {
            throw new RuntimeException('No se pudo refrescar el token de Google: ' . $res->body());
        }

        $data = $res->json();
        $user->forceFill([
            'google_calendar_token'      => $data['access_token'],
            'google_calendar_expires_at' => now()->addSeconds(($data['expires_in'] ?? 3600) - 30),
        ])->save();

        return $data['access_token'];
    }

    /** Intervalos ocupados del calendario principal entre dos fechas. */
    public function busy(User $user, Carbon $from, Carbon $to): array
    {
        $res = Http::withToken($this->validToken($user))
            ->post(self::API . '/freeBusy', [
                'timeMin' => $from->toRfc3339String(),
                'timeMax' => $to->toRfc3339String(),
                'items'   => [['id' => 'primary']],
            ]);

        if ($res->failed()) {
            throw new RuntimeException('No se pudo leer la disponibilidad: ' . $res->body());
        }

        return collect($res->json('calendars.primary.busy', []))
            ->map(fn ($b) => [
                'start' => Carbon::parse($b['start']),
                'end'   => Carbon::parse($b['end']),
            ])->all();
    }

    /**
     * Crea un evento con Google Meet e invitados.
     *
     * @param  array{summary:string,description?:string,start:Carbon,end:Carbon,attendees?:array<string>}  $data
     * @return array{id:string,meet:?string,htmlLink:?string}
     */
    public function createEvent(User $user, array $data): array
    {
        $attendees = collect($data['attendees'] ?? [])
            ->filter()->map(fn ($e) => ['email' => $e])->values()->all();

        $body = [
            'summary'     => $data['summary'],
            'description' => $data['description'] ?? '',
            'start'       => ['dateTime' => $data['start']->toRfc3339String(), 'timeZone' => config('app.timezone')],
            'end'         => ['dateTime' => $data['end']->toRfc3339String(), 'timeZone' => config('app.timezone')],
            'attendees'   => $attendees,
            'conferenceData' => [
                'createRequest' => [
                    'requestId'             => 'mt-' . uniqid(),
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                ],
            ],
            'reminders' => ['useDefault' => true],
        ];

        $res = Http::withToken($this->validToken($user))
            ->post(self::API . '/calendars/primary/events?conferenceDataVersion=1&sendUpdates=all', $body);

        if ($res->failed()) {
            throw new RuntimeException('No se pudo crear el evento: ' . $res->body());
        }

        return [
            'id'       => $res->json('id'),
            'meet'     => $res->json('hangoutLink'),
            'htmlLink' => $res->json('htmlLink'),
        ];
    }

    public function deleteEvent(User $user, string $eventId): void
    {
        Http::withToken($this->validToken($user))
            ->delete(self::API . "/calendars/primary/events/{$eventId}?sendUpdates=all");
    }

    /**
     * Calcula los horarios libres del anfitrión combinando su free/busy de Google
     * con las reuniones ya agendadas en la app. Solo días hábiles, horario laboral.
     *
     * @return array<int, array{date:string,label:string,slots:array<int,array{start:string,label:string}>}>
     */
    public function availableSlots(User $host, int $days = 7, int $stepMinutes = 15, int $durationMinutes = 30, int $startHour = 9, int $endHour = 18): array
    {
        $tz   = config('app.timezone');
        $now  = Carbon::now($tz);
        $from = $now->copy();
        $to   = $now->copy()->addDays($days)->endOfDay();

        $busy = $this->busy($host, $from, $to);

        // Bloquear también las reuniones ya agendadas en la app que ocupan el tiempo
        // del anfitrión: las de cierre (host_user_id) y CUALQUIERA creada por él (user_id).
        $meetings = \App\Models\Meeting::query()
            ->where('estado', 'agendada')
            ->whereBetween('scheduled_at', [$from, $to])
            ->where(function ($q) use ($host) {
                $q->where('host_user_id', $host->id)->orWhere('user_id', $host->id);
            })->get()
            ->map(fn ($m) => [
                'start' => $m->scheduled_at->copy(),
                'end'   => $m->scheduled_at->copy()->addMinutes($durationMinutes),
            ])->all();
        $busy = array_merge($busy, $meetings);

        $result = [];

        for ($d = 0; $d < $days; $d++) {
            $day = $now->copy()->addDays($d)->startOfDay();
            if ($day->isWeekend()) {
                continue;
            }

            $slots  = [];
            $cursor = $day->copy()->setTime($startHour, 0);
            $dayEnd = $day->copy()->setTime($endHour, 0);

            // Genera TODOS los slots (cada $stepMinutes) marcando libre/ocupado,
            // así la comercial ve también los bloques ocupados (sin detalle).
            while ($cursor->copy()->addMinutes($durationMinutes)->lte($dayEnd)) {
                $slotStart = $cursor->copy();
                $slotEnd   = $cursor->copy()->addMinutes($durationMinutes);

                if ($slotStart->gt($now)) {
                    $libre = true;
                    foreach ($busy as $b) {
                        if ($slotStart->lt($b['end']) && $slotEnd->gt($b['start'])) {
                            $libre = false;
                            break;
                        }
                    }
                    $slots[] = [
                        'start' => $slotStart->toIso8601String(),
                        'label' => $slotStart->format('H:i'),
                        'free'  => $libre,
                    ];
                }
                $cursor->addMinutes($stepMinutes);
            }

            if ($slots) {
                $result[] = [
                    'date'  => $day->toDateString(),
                    'label' => ucfirst($day->locale('es')->translatedFormat('D d M')),
                    'slots' => $slots,
                ];
            }
        }

        return $result;
    }

    private function fetchEmail(string $accessToken): ?string
    {
        if (! $accessToken) {
            return null;
        }
        $res = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');

        return $res->ok() ? $res->json('email') : null;
    }
}
