<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function __construct(private GoogleCalendarService $google) {}

    /** Página de ajustes del calendario (admin). */
    public function index()
    {
        return view('pipeline.calendar', [
            'configured' => $this->google->configured(),
            'connected'  => Auth::user()->hasGoogleCalendar(),
            'email'      => Auth::user()->google_calendar_email,
            'redirect'   => config('services.google_calendar.redirect'),
            'pageTitle'  => 'Mi calendario',
        ]);
    }

    /** Redirige al consentimiento de Google. */
    public function connect()
    {
        if (! $this->google->configured()) {
            return back()->with('error', 'Faltan las credenciales de Google en el .env (GOOGLE_CALENDAR_CLIENT_ID / SECRET).');
        }

        return redirect()->away($this->google->authUrl());
    }

    /** Callback de Google: guarda los tokens. */
    public function callback(Request $request)
    {
        if ($request->filled('error')) {
            return redirect()->route('pipeline.calendar')->with('error', 'Conexión cancelada: ' . $request->input('error'));
        }

        try {
            $this->google->connect(Auth::user(), $request->input('code', ''));
        } catch (\Throwable $e) {
            return redirect()->route('pipeline.calendar')->with('error', $e->getMessage());
        }

        return redirect()->route('pipeline.calendar')->with('success', 'Google Calendar conectado correctamente.');
    }

    public function disconnect()
    {
        $this->google->disconnect(Auth::user());

        return back()->with('success', 'Google Calendar desconectado.');
    }
}
