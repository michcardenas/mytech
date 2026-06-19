<?php

namespace App\Http\Controllers\Pipeline;

use App\Exports\PlantillaClientesExport;
use App\Http\Controllers\Controller;
use App\Imports\ClientesImport;
use App\Models\ClienteImportado;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClientesImportController extends Controller
{
    /** Pantalla de importación + reparto. */
    public function index()
    {
        $comerciales = User::role('comercial')->orderBy('name')
            ->withCount(['leads as abiertos_count' => fn ($q) => $q->where('estado', 'abierto')])
            ->get();

        return view('pipeline.clientes.importar', [
            'comerciales' => $comerciales,
            'pendientes' => ClienteImportado::latest('id')->get(),
            'totalImportados' => Lead::where('fuente', 'importado')->count(),
            'pageTitle' => 'Importar clientes',
        ]);
    }

    /** Descarga la plantilla .xlsx vacía. */
    public function plantilla(): BinaryFileResponse
    {
        return Excel::download(new PlantillaClientesExport, 'plantilla-clientes.xlsx');
    }

    /** PASO 1: carga el archivo a la bolsa de clientes "sin repartir". */
    public function importar(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ], [
            'archivo.required' => 'Selecciona el archivo Excel a importar.',
            'archivo.mimes' => 'El archivo debe ser Excel (.xlsx, .xls) o CSV.',
        ]);

        $import = new ClientesImport;
        Excel::import($import, $request->file('archivo'));

        $clientes = $import->filas->slice(1)->map(fn ($f) => [
            'identificacion' => trim((string) ($f[0] ?? '')),
            'nombre' => trim((string) ($f[1] ?? '')),
            'empresa' => trim((string) ($f[2] ?? '')),
            'pais' => trim((string) ($f[3] ?? '')),
            'email' => trim((string) ($f[4] ?? '')),
            'telefono' => trim((string) ($f[5] ?? '')),
            'telefono2' => trim((string) ($f[6] ?? '')),
            'descripcion' => trim((string) ($f[7] ?? '')),
        ])->filter(fn ($c) => $c['nombre'] !== '')->values();

        if ($clientes->isEmpty()) {
            return back()->with('error', 'El archivo no tiene clientes válidos. La columna "Nombre" es obligatoria.');
        }

        $lote = (string) Str::uuid();
        $now = now();
        $filas = $clientes->map(fn ($c) => [
            'identificacion' => $c['identificacion'] ?: null,
            'nombre' => $c['nombre'],
            'empresa' => $c['empresa'] ?: null,
            'pais' => $c['pais'] ?: null,
            'email' => $c['email'] ?: null,
            'telefono' => $c['telefono'] ?: null,
            'telefono2' => $c['telefono2'] ?: null,
            'descripcion' => $c['descripcion'] ?: null,
            'lote_importacion' => $lote,
            'importado_por' => $request->user()->id,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        ClienteImportado::insert($filas);

        return redirect()->route('pipeline.clientes.importar')
            ->with('success', "Se cargaron {$clientes->count()} clientes a la bolsa. Ahora pulsa «Repartir» para asignarlos.");
    }

    /** PASO 2: reparte la bolsa de clientes aleatoriamente entre los comerciales. */
    public function repartir(Request $request): RedirectResponse
    {
        $request->validate([
            'comerciales' => 'nullable|array',
            'comerciales.*' => 'integer',
        ]);

        $ids = User::role('comercial')
            ->when($request->filled('comerciales'), fn ($q) => $q->whereIn('id', $request->input('comerciales')))
            ->pluck('id');

        if ($ids->isEmpty()) {
            return back()->with('error', 'Selecciona al menos un comercial para repartir.');
        }

        $pendientes = ClienteImportado::get();

        if ($pendientes->isEmpty()) {
            return back()->with('error', 'No hay clientes en la bolsa. Importa un archivo primero.');
        }

        $idsShuffled = $ids->shuffle()->values();
        $reparto = [];

        DB::transaction(function () use ($pendientes, $idsShuffled, &$reparto): void {
            foreach ($pendientes->shuffle()->values() as $i => $cli) {
                $uid = $idsShuffled[$i % $idsShuffled->count()];

                Lead::create([
                    'user_id' => $uid,
                    'nombre' => $cli->nombre,
                    'identificacion' => $cli->identificacion,
                    'empresa' => $cli->empresa,
                    'pais' => $cli->pais,
                    'email' => $cli->email,
                    'telefono' => $cli->telefono,
                    'telefono2' => $cli->telefono2,
                    'descripcion' => $cli->descripcion,
                    'fuente' => 'importado',
                    'etapa' => 'prospecto',
                    'estado' => Lead::ESTADO_ABIERTO,
                    'orden' => 0,
                    'lote_importacion' => $cli->lote_importacion,
                ]);

                $reparto[$uid] = ($reparto[$uid] ?? 0) + 1;
            }

            ClienteImportado::whereIn('id', $pendientes->pluck('id'))->delete();
        });

        $nombres = User::whereIn('id', array_keys($reparto))->pluck('name', 'id');
        $resumen = collect($reparto)
            ->map(fn ($n, $uid) => ['name' => $nombres[$uid] ?? 'Comercial', 'n' => $n])
            ->sortByDesc('n')->values()->all();

        return redirect()->route('pipeline.clientes.importar')
            ->with('success', "Se repartieron {$pendientes->count()} clientes entre {$idsShuffled->count()} comercial(es).")
            ->with('reparto', $resumen);
    }
}
