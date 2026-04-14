<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\ProjectPayment;
use App\Models\DeveloperPayment;
use App\Models\ProjectExpense;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternalProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalProject::withCount('payments', 'files')
            ->withSum('payments', 'monto');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fuente')) {
            $query->where('fuente', $request->fuente);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('cliente_nombre', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $stats = [
            'total' => InternalProject::count(),
            'en_progreso' => InternalProject::where('estado', 'en_progreso')->count(),
            'completados' => InternalProject::where('estado', 'completado')->count(),
            'cotizados' => InternalProject::where('estado', 'cotizado')->count(),
        ];

        return view('admin.internal-projects.index', compact('projects', 'stats'));
    }

    public function create()
    {
        return view('admin.internal-projects.form', [
            'project' => new InternalProject(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_contacto' => 'nullable|string|max:255',
            'cliente_email' => 'nullable|email|max:255',
            'fuente' => 'required|in:directo,workana',
            'fuente_url' => 'nullable|url|max:500',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD',
            'estado' => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_entrega' => 'nullable|date|required_without:es_recurrente',
            'es_recurrente' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
            'notas' => 'nullable|string',
            'desarrollador_nombre' => 'nullable|string|max:255',
            'desarrollador_email' => 'nullable|email|max:255',
            'desarrollador_pago' => 'nullable|numeric|min:0',
            'desarrollador_moneda' => 'required|in:COP,USD',
        ]);

        $validated['es_recurrente'] = $request->boolean('es_recurrente');
        if ($validated['es_recurrente']) {
            $validated['fecha_entrega'] = null;
        }

        $project = InternalProject::create($validated);

        return redirect()->route('admin.internal-projects.show', $project)
            ->with('success', 'Proyecto creado exitosamente.');
    }

    public function show(InternalProject $internal_project)
    {
        $internal_project->load([
            'payments' => fn($q) => $q->orderBy('fecha', 'desc'),
            'developerPayments' => fn($q) => $q->orderBy('fecha', 'desc'),
            'expenses' => fn($q) => $q->orderBy('fecha', 'desc'),
            'files',
        ]);

        return view('admin.internal-projects.show', [
            'project' => $internal_project,
        ]);
    }

    public function edit(InternalProject $internal_project)
    {
        return view('admin.internal-projects.form', [
            'project' => $internal_project,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_contacto' => 'nullable|string|max:255',
            'cliente_email' => 'nullable|email|max:255',
            'fuente' => 'required|in:directo,workana',
            'fuente_url' => 'nullable|url|max:500',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD',
            'estado' => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_entrega' => 'nullable|date|required_without:es_recurrente',
            'es_recurrente' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
            'notas' => 'nullable|string',
            'desarrollador_nombre' => 'nullable|string|max:255',
            'desarrollador_email' => 'nullable|email|max:255',
            'desarrollador_pago' => 'nullable|numeric|min:0',
            'desarrollador_moneda' => 'required|in:COP,USD',
        ]);

        $validated['es_recurrente'] = $request->boolean('es_recurrente');
        if ($validated['es_recurrente']) {
            $validated['fecha_entrega'] = null;
        }

        $internal_project->update($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function destroy(InternalProject $internal_project)
    {
        // Delete associated files from storage
        foreach ($internal_project->files as $file) {
            Storage::disk('public')->delete($file->archivo);
        }

        $internal_project->delete();

        return redirect()->route('admin.internal-projects.index')
            ->with('success', 'Proyecto eliminado.');
    }

    // --- Payments ---

    public function storePayment(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'monto_recibido_cop' => 'nullable|numeric|min:0',
            'fecha' => 'required|date',
            'metodo' => 'nullable|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
        ]);

        $internal_project->payments()->create($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function destroyPayment(InternalProject $internal_project, ProjectPayment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago eliminado.');
    }

    // --- Developer Payments ---

    public function storeDeveloperPayment(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD',
            'fecha' => 'required|date',
            'metodo' => 'nullable|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
        ]);

        $internal_project->developerPayments()->create($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago al desarrollador registrado.');
    }

    public function destroyDeveloperPayment(InternalProject $internal_project, DeveloperPayment $developerPayment)
    {
        $developerPayment->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago al desarrollador eliminado.');
    }

    // --- Otros Gastos ---

    public function storeExpense(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD',
            'fecha' => 'required|date',
            'categoria' => 'nullable|string|max:100',
        ]);

        $internal_project->expenses()->create($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Gasto registrado.');
    }

    public function destroyExpense(InternalProject $internal_project, ProjectExpense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Gasto eliminado.');
    }

    // --- Files ---

    public function storeFile(Request $request, InternalProject $internal_project)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'archivo' => 'required|file|max:20480', // 20MB max
        ]);

        $file = $request->file('archivo');
        $path = $file->store('internal-projects/' . $internal_project->id, 'public');

        $internal_project->files()->create([
            'nombre' => $request->nombre,
            'archivo' => $path,
            'tipo' => $file->getMimeType(),
            'tamano' => $file->getSize(),
        ]);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Archivo subido exitosamente.');
    }

    public function destroyFile(InternalProject $internal_project, ProjectFile $file)
    {
        Storage::disk('public')->delete($file->archivo);
        $file->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Archivo eliminado.');
    }
}
