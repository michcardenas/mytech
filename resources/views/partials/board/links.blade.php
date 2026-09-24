{{-- Enlaces importantes del proyecto. Variables: $project, $esAdmin --}}
<style>
    .plinks { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.2rem; }
    .plinks-head { display: flex; align-items: center; gap: 0.5rem; font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.4px; margin-bottom: 0.6rem; }
    .plinks-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .plink { display: inline-flex; align-items: center; gap: 0.45rem; border-radius: 9px; padding: 0.45rem 0.75rem; font-size: 0.8rem; font-weight: 700; text-decoration: none; }
    .plink-repo { background: #f1f5f9; color: #334155; }
    .plink-prod { background: #ecfdf5; color: #047857; }
    .plink-test { background: #fef9c3; color: #854d0e; }
    .plink:hover { filter: brightness(0.97); }
    .plinks-empty { font-size: 0.8rem; color: #94a3b8; }
    .plinks-form { display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 0.5rem; margin-top: 0.7rem; align-items: end; }
    .plinks-form .f label { display: block; font-size: 0.66rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; margin-bottom: 0.25rem; }
    .plinks-form input { width: 100%; padding: 0.42rem 0.6rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.8rem; }
    .plinks-form button { border: none; background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; border-radius: 8px; padding: 0.5rem 0.9rem; font-weight: 700; font-size: 0.78rem; cursor: pointer; white-space: nowrap; }
    @media (max-width: 720px) { .plinks-form { grid-template-columns: 1fr; } }
</style>
<div class="plinks">
    <div class="plinks-head"><i class="fas fa-link"></i> Enlaces del proyecto</div>
    <div class="plinks-list">
        @if($project->repo_url)
            <a href="{{ $project->repo_url }}" target="_blank" class="plink plink-repo"><i class="fab fa-github"></i> Repositorio</a>
        @endif
        @if($project->url_produccion)
            <a href="{{ $project->url_produccion }}" target="_blank" class="plink plink-prod"><i class="fas fa-globe"></i> Producción</a>
        @endif
        @if($project->url_pruebas)
            <a href="{{ $project->url_pruebas }}" target="_blank" class="plink plink-test"><i class="fas fa-flask"></i> Pruebas</a>
        @endif
        @if(! $project->repo_url && ! $project->url_produccion && ! $project->url_pruebas)
            <span class="plinks-empty">Sin enlaces {{ $esAdmin ? 'todavía — agrégalos abajo.' : 'registrados.' }}</span>
        @endif
    </div>

    @if($esAdmin)
        <form class="plinks-form" action="{{ route('admin.internal-projects.links.update', $project) }}" method="POST">
            @csrf @method('PUT')
            <div class="f"><label>Repositorio</label><input type="url" name="repo_url" value="{{ $project->repo_url }}" placeholder="https://github.com/..."></div>
            <div class="f"><label>Producción</label><input type="url" name="url_produccion" value="{{ $project->url_produccion }}" placeholder="https://..."></div>
            <div class="f"><label>Pruebas</label><input type="url" name="url_pruebas" value="{{ $project->url_pruebas }}" placeholder="https://staging..."></div>
            <button type="submit"><i class="fas fa-save"></i> Guardar</button>
        </form>
    @endif
</div>
