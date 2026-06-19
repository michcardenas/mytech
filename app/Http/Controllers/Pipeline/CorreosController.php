<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Services\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Webklex\IMAP\Facades\Client;

class CorreosController extends Controller
{
    private function decode(?string $s): string
    {
        return $s ? trim(mb_decode_mimeheader($s)) : '(sin asunto)';
    }

    /** Bandeja de entrada (lee de la BD, sincronizada en segundo plano). */
    public function bandeja()
    {
        $mensajes = \App\Models\CorreoRecibido::orderByDesc('fecha')->limit(50)->get();

        return view('pipeline.correos.bandeja', [
            'mensajes' => $mensajes,
            'sinLeer' => \App\Models\CorreoRecibido::where('visto', false)->count(),
            'ultimaSync' => \App\Models\CorreoRecibido::max('synced_at'),
            'remitente' => config('mail.from.address'),
            'pageTitle' => 'Bandeja de entrada',
        ]);
    }

    /** Sincroniza la bandeja ahora (manual). */
    public function sincronizar()
    {
        @set_time_limit(120);
        $code = \Illuminate\Support\Facades\Artisan::call('emails:sync-inbox');

        return redirect()->route('pipeline.correos.bandeja')
            ->with($code === 0 ? 'success' : 'error',
                $code === 0 ? 'Bandeja actualizada.' : 'No se pudo sincronizar (revisa la conexión IMAP).');
    }

    /** Leer un correo + formulario de respuesta. */
    public function leer(int $uid)
    {
        try {
            $client = Client::account('default');
            $client->connect();
            $inbox = $client->getFolder('INBOX');
            $m = $inbox->query()->getMessageByUid($uid);
            if (! $m) {
                return redirect()->route('pipeline.correos.bandeja')->with('error', 'No se encontró el correo.');
            }
            $m->setFlag('Seen');
            \App\Models\CorreoRecibido::where('uid', $uid)->update(['visto' => true]);
            $from = $m->getFrom();
            $correo = (object) [
                'uid' => $uid,
                'de' => $from[0]->mail ?? '',
                'nombre' => $this->decode($from[0]->personal ?? '') ?: ($from[0]->mail ?? ''),
                'asunto' => $this->decode($m->getSubject()),
                'fecha' => (function () use ($m) {
                    try {
                        return $m->getDate()->toDate();
                    } catch (\Throwable $e) {
                        return null;
                    }
                })(),
                'html' => $m->getHTMLBody() ?: nl2br(e($m->getTextBody() ?: '')),
                'adjuntos' => $m->getAttachments()->map(fn ($a) => $a->getName())->all(),
            ];
        } catch (\Throwable $e) {
            return redirect()->route('pipeline.correos.bandeja')->with('error', 'No se pudo abrir el correo: '.$e->getMessage());
        }

        return view('pipeline.correos.leer', [
            'correo' => $correo,
            'pageTitle' => 'Correo',
        ]);
    }

    /** Responder un correo (envío inmediato). */
    public function responder(Request $request, EmailSender $sender)
    {
        $data = $request->validate([
            'para' => 'required|email',
            'asunto' => 'required|string|max:255',
            'cuerpo' => 'required|string',
        ]);

        $email = Email::create([
            'user_id' => Auth::id(),
            'para' => $data['para'],
            'asunto' => Str::startsWith(Str::lower($data['asunto']), 're:') ? $data['asunto'] : 'Re: '.$data['asunto'],
            'cuerpo' => nl2br(e($data['cuerpo'])),
            'estado' => 'pendiente',
        ]);
        $sender->sendOne($email);

        return redirect()->route('pipeline.correos.bandeja')
            ->with($email->fresh()->estado === 'enviado' ? 'success' : 'error',
                $email->fresh()->estado === 'enviado' ? 'Respuesta enviada a '.$data['para'] : 'No se pudo enviar: '.$email->fresh()->error);
    }

    /** Reporte de correos enviados por comercial (solo admin). */
    public function reporte(Request $request)
    {
        $comercialId = $request->integer('comercial') ?: null;
        $dias = max(7, min(90, $request->integer('dias') ?: 30));
        $desde = now()->subDays($dias - 1)->startOfDay();

        $hoyStr = now()->toDateString();
        $semanaStr = now()->startOfWeek()->toDateTimeString();
        $mesStr = now()->startOfMonth()->toDateTimeString();

        $scoped = fn () => Email::query()->when($comercialId, fn ($q) => $q->where('user_id', $comercialId));

        $kpis = [
            'hoy' => $scoped()->where('estado', 'enviado')->whereDate('sent_at', $hoyStr)->count(),
            'semana' => $scoped()->where('estado', 'enviado')->where('sent_at', '>=', $semanaStr)->count(),
            'mes' => $scoped()->where('estado', 'enviado')->where('sent_at', '>=', $mesStr)->count(),
            'enviados' => $scoped()->where('estado', 'enviado')->count(),
            'fallidos' => $scoped()->where('estado', 'fallido')->count(),
            'pendientes' => $scoped()->where('estado', 'pendiente')->count(),
        ];

        $agg = Email::query()
            ->selectRaw(
                'user_id,
                COUNT(*) as total,
                SUM(estado = ?) as enviados,
                SUM(estado = ?) as fallidos,
                SUM(estado = ? AND DATE(sent_at) = ?) as hoy,
                SUM(estado = ? AND sent_at >= ?) as semana,
                SUM(estado = ? AND sent_at >= ?) as mes,
                MAX(sent_at) as ultimo',
                ['enviado', 'fallido', 'enviado', $hoyStr, 'enviado', $semanaStr, 'enviado', $mesStr]
            )
            ->groupBy('user_id')->get()->keyBy('user_id');

        $usuarios = \App\Models\User::role(['admin', 'comercial'])->orderBy('name')->get()
            ->map(function ($u) use ($agg) {
                $a = $agg->get($u->id);

                return (object) [
                    'name' => $u->name,
                    'email' => $u->email,
                    'rol' => $u->getRoleNames()->first() ?? '—',
                    'hoy' => (int) ($a->hoy ?? 0),
                    'semana' => (int) ($a->semana ?? 0),
                    'mes' => (int) ($a->mes ?? 0),
                    'enviados' => (int) ($a->enviados ?? 0),
                    'fallidos' => (int) ($a->fallidos ?? 0),
                    'total' => (int) ($a->total ?? 0),
                    'ultimo' => $a->ultimo ?? null,
                ];
            })->sortByDesc('enviados')->values();

        $porDiaRaw = $scoped()->where('estado', 'enviado')->where('sent_at', '>=', $desde)
            ->selectRaw('DATE(sent_at) as dia, COUNT(*) as n')->groupBy('dia')->pluck('n', 'dia');

        $porDia = collect();
        for ($i = $dias - 1; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $porDia->push((object) ['dia' => $d, 'n' => (int) ($porDiaRaw[$d] ?? 0)]);
        }

        $detalle = $scoped()->with('user')->latest('id')->limit(150)->get();

        return view('pipeline.correos.reporte', [
            'kpis' => $kpis,
            'usuarios' => $usuarios,
            'porDia' => $porDia,
            'detalle' => $detalle,
            'comerciales' => \App\Models\User::role(['admin', 'comercial'])->orderBy('name')->get(['id', 'name']),
            'comercialId' => $comercialId,
            'dias' => $dias,
            'pageTitle' => 'Reporte de correos',
        ]);
    }

    /** Redactar + registro de enviados. */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        $enviados = Email::query()
            ->when(! $isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->with('user')->latest()->limit(80)->get();

        $base = Email::query()->when(! $isAdmin, fn ($q) => $q->where('user_id', $user->id));

        return view('pipeline.correos.index', [
            'enviados' => $enviados,
            'remitente' => config('mail.from.address'),
            'modoLog' => config('mail.default') === 'log',
            'isAdmin' => $isAdmin,
            'stats' => [
                'enviado' => (clone $base)->where('estado', 'enviado')->count(),
                'pendiente' => (clone $base)->where('estado', 'pendiente')->count(),
                'fallido' => (clone $base)->where('estado', 'fallido')->count(),
            ],
            'pageTitle' => 'Correos',
        ]);
    }

    /** Encolar y enviar (primera tanda inmediata; el resto por el programador). */
    public function send(Request $request, EmailSender $sender)
    {
        $data = $request->validate([
            'destinatarios' => 'required|string',
            'asunto' => 'required|string|max:255',
            'cuerpo' => 'required|string',
            'adjuntos' => 'nullable|array',
            'adjuntos.*' => 'file|max:10240', // 10 MB c/u
        ], [], ['destinatarios' => 'destinatarios', 'cuerpo' => 'mensaje']);

        // Parsear destinatarios (separados por coma, punto y coma o salto de línea)
        $emails = collect(preg_split('/[\s,;]+/', $data['destinatarios']))
            ->map(fn ($e) => trim($e))->filter()->unique()
            ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))->values();

        if ($emails->isEmpty()) {
            return back()->withInput()->with('error', 'No hay destinatarios válidos. Revisa los correos.');
        }

        // Adjuntos
        $rutas = [];
        foreach ((array) $request->file('adjuntos', []) as $file) {
            $rutas[] = $file->store('email-adjuntos/'.date('Y-m'));
        }

        $batch = (string) Str::uuid();
        foreach ($emails as $to) {
            Email::create([
                'user_id' => Auth::id(),
                'batch_id' => $batch,
                'para' => $to,
                'asunto' => $data['asunto'],
                'cuerpo' => $data['cuerpo'],
                'adjuntos' => $rutas,
                'estado' => 'pendiente',
            ]);
        }

        // Primera tanda inmediata (hasta 10); el resto sale solo cada minuto.
        $sender->sendBatch(10);

        return redirect()->route('pipeline.correos.index')
            ->with('success', $emails->count().' correo(s) en cola. Se envían en tandas de 10 para no caer en spam.');
    }
}
