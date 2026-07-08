<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClienteImportado;
use App\Models\Email;
use App\Models\InternalProject;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Meeting;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Roles que se pueden asignar desde este CRUD. */
    private const ROLES = ['admin', 'comercial'];

    /** Listado del equipo (admins y comerciales). */
    public function index()
    {
        $users = User::role(self::ROLES)->with('roles')->orderBy('name')->get();

        // Métricas de leads por comercial (para el modal de eliminación).
        $leadStats = Lead::query()
            ->selectRaw('user_id, COUNT(*) as total, SUM(CASE WHEN estado = ? THEN 1 ELSE 0 END) as abiertos, SUM(CASE WHEN fuente = ? THEN 1 ELSE 0 END) as importados', [Lead::ESTADO_ABIERTO, 'importado'])
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $comerciales = $users->filter(fn (User $u) => $u->hasRole('comercial'))->values();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => self::ROLES,
            'leadStats' => $leadStats,
            'comerciales' => $comerciales,
            'pageTitle' => 'Usuarios',
        ]);
    }

    public function create()
    {
        return view('admin.users.form', [
            'user' => new User,
            'roles' => self::ROLES,
            'pageTitle' => 'Nuevo usuario',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', "Usuario «{$user->name}» creado.");
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => self::ROLES,
            'pageTitle' => 'Editar usuario',
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        if ($user->hasRole('admin') && $data['role'] !== 'admin' && $this->adminCount() <= 1) {
            return back()->withInput()->with('error', 'No puedes cambiar el rol del único administrador.');
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', "Usuario «{$user->name}» actualizado.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        if ($user->hasRole('admin') && $this->adminCount() <= 1) {
            return back()->with('error', 'No puedes eliminar al único administrador.');
        }

        $esComercial = $user->hasRole('comercial');
        $tieneLeads = $esComercial && Lead::where('user_id', $user->id)->exists();

        if ($esComercial && $tieneLeads) {
            $data = $request->validate([
                'reassign_mode' => ['required', Rule::in(['to_user', 'random'])],
                'reassign_to' => ['required_if:reassign_mode,to_user', 'nullable', 'integer', 'different:'.$user->id],
            ]);

            $destinos = User::role('comercial')
                ->where('id', '!=', $user->id)
                ->pluck('id')
                ->all();

            if (empty($destinos)) {
                return back()->with('error', 'No hay otros comerciales para recibir los leads. Crea uno antes de eliminar.');
            }

            if ($data['reassign_mode'] === 'to_user') {
                $destino = (int) $data['reassign_to'];
                if (! in_array($destino, $destinos, true)) {
                    return back()->with('error', 'El comercial destino no es válido.');
                }
                $this->reasignarATodo($user->id, $destino);
            } else {
                $this->reasignarAlAzar($user->id, $destinos);
            }
        } elseif ($esComercial) {
            $this->limpiarReferencias($user->id);
        }

        $nombre = $user->name;
        $user->delete();

        $msg = $esComercial && $tieneLeads
            ? "Usuario «{$nombre}» eliminado. Leads y procesos reasignados."
            : "Usuario «{$nombre}» eliminado.";

        return redirect()->route('admin.users.index')->with('success', $msg);
    }

    private function adminCount(): int
    {
        return User::role('admin')->count();
    }

    /** Reasigna todos los datos operativos del comercial saliente a un único destino. */
    private function reasignarATodo(int $fromId, int $toId): void
    {
        DB::transaction(function () use ($fromId, $toId) {
            Lead::where('user_id', $fromId)->update(['user_id' => $toId]);
            LeadActivity::where('user_id', $fromId)->update(['user_id' => $toId]);
            Proposal::where('user_id', $fromId)->update(['user_id' => $toId]);
            Meeting::where('user_id', $fromId)->update(['user_id' => $toId]);
            Email::where('user_id', $fromId)->update(['user_id' => $toId]);
            ClienteImportado::where('importado_por', $fromId)->update(['importado_por' => $toId]);
            InternalProject::where('comercial_user_id', $fromId)->update(['comercial_user_id' => $toId]);
        });
    }

    /**
     * Distribuye los leads del comercial saliente al azar entre los destinos disponibles.
     * Cada lead va a un comercial (round-robin sobre una lista barajada), y sus registros
     * dependientes (actividades, propuestas, reuniones) viajan con él.
     *
     * @param  array<int>  $destinos
     */
    private function reasignarAlAzar(int $fromId, array $destinos): void
    {
        DB::transaction(function () use ($fromId, $destinos) {
            shuffle($destinos);
            $total = count($destinos);

            $leadIds = Lead::where('user_id', $fromId)->orderBy('id')->pluck('id')->all();
            foreach ($leadIds as $i => $leadId) {
                $nuevoDueno = $destinos[$i % $total];
                Lead::whereKey($leadId)->update(['user_id' => $nuevoDueno]);
                LeadActivity::where('lead_id', $leadId)->update(['user_id' => $nuevoDueno]);
                Proposal::where('lead_id', $leadId)->update(['user_id' => $nuevoDueno]);
                Meeting::where('lead_id', $leadId)->update(['user_id' => $nuevoDueno]);
            }

            // Registros sin vínculo a un lead: se reparten al azar por registro.
            foreach (Email::where('user_id', $fromId)->pluck('id')->all() as $id) {
                Email::whereKey($id)->update(['user_id' => $destinos[random_int(0, $total - 1)]]);
            }
            foreach (ClienteImportado::where('importado_por', $fromId)->pluck('id')->all() as $id) {
                ClienteImportado::whereKey($id)->update(['importado_por' => $destinos[random_int(0, $total - 1)]]);
            }
            foreach (InternalProject::where('comercial_user_id', $fromId)->pluck('id')->all() as $id) {
                InternalProject::whereKey($id)->update(['comercial_user_id' => $destinos[random_int(0, $total - 1)]]);
            }
        });
    }

    /** Comercial sin leads: solo limpia FKs "nullOnDelete" para que no se caigan por cascade. */
    private function limpiarReferencias(int $fromId): void
    {
        DB::transaction(function () use ($fromId) {
            ClienteImportado::where('importado_por', $fromId)->update(['importado_por' => null]);
            InternalProject::where('comercial_user_id', $fromId)->update(['comercial_user_id' => null]);
        });
    }
}
