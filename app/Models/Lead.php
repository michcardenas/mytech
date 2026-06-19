<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'nombre', 'identificacion', 'empresa', 'pais', 'fuente', 'fuente_url', 'descripcion',
        'email', 'telefono', 'telefono2', 'valor_estimado', 'moneda', 'etapa', 'estado',
        'motivo_perdido', 'proxima_accion_at', 'proxima_accion_nota', 'orden',
        'won_at', 'lost_at', 'internal_project_id', 'lote_importacion',
    ];

    protected function casts(): array
    {
        return [
            'valor_estimado' => 'decimal:2',
            'proxima_accion_at' => 'datetime',
            'won_at' => 'datetime',
            'lost_at' => 'datetime',
        ];
    }

    /** Etapas del kanban en orden, con etiqueta y color. */
    public const ETAPAS = [
        'prospecto' => ['label' => 'Prospecto',  'color' => '#6B7280'],
        'contactado' => ['label' => 'Contactado', 'color' => '#6366F1'],
        'propuesta' => ['label' => 'Propuesta',  'color' => '#F59E0B'],
        'reunion' => ['label' => 'Reunión',    'color' => '#06B6D4'],
        'cierre' => ['label' => 'Cierre',     'color' => '#8B5CF6'],
        'ganado' => ['label' => 'Ganado',     'color' => '#16A34A'],
    ];

    /** Fuentes de prospección, con etiqueta e ícono FontAwesome. */
    public const FUENTES = [
        'workana' => ['label' => 'Workana',   'icon' => 'fas fa-briefcase',     'color' => '#2563EB'],
        'facebook' => ['label' => 'Facebook',  'icon' => 'fab fa-facebook-f',    'color' => '#1877F2'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram',     'color' => '#E1306C'],
        'linkedin' => ['label' => 'LinkedIn',  'icon' => 'fab fa-linkedin-in',   'color' => '#0A66C2'],
        'referido' => ['label' => 'Referido',  'icon' => 'fas fa-user-group',    'color' => '#16A34A'],
        'whatsapp' => ['label' => 'WhatsApp',  'icon' => 'fab fa-whatsapp',      'color' => '#25D366'],
        'web' => ['label' => 'Sitio web', 'icon' => 'fas fa-globe',         'color' => '#0EA5E9'],
        'importado' => ['label' => 'Importado', 'icon' => 'fas fa-file-import',   'color' => '#7C3AED'],
        'otro' => ['label' => 'Otro',      'icon' => 'fas fa-tag',           'color' => '#6B7280'],
    ];

    public const ESTADO_ABIERTO = 'abierto';

    public const ESTADO_GANADO = 'ganado';

    public const ESTADO_PERDIDO = 'perdido';

    // ---------- Relaciones ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class)->latest();
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class)->orderBy('scheduled_at');
    }

    public function internalProject(): BelongsTo
    {
        return $this->belongsTo(InternalProject::class);
    }

    // ---------- Scopes ----------

    /** Limita a los leads del usuario salvo que sea admin. */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->hasRole('admin')) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public function scopeAbierto(Builder $query): Builder
    {
        return $query->where('estado', self::ESTADO_ABIERTO);
    }

    public function scopeEnTablero(Builder $query): Builder
    {
        return $query->where('estado', '!=', self::ESTADO_PERDIDO);
    }

    // ---------- Accessors / helpers ----------

    public function getEtapaLabelAttribute(): string
    {
        return self::ETAPAS[$this->etapa]['label'] ?? ucfirst($this->etapa);
    }

    public function getEtapaColorAttribute(): string
    {
        return self::ETAPAS[$this->etapa]['color'] ?? '#6B7280';
    }

    public function getFuenteLabelAttribute(): string
    {
        return self::FUENTES[$this->fuente]['label'] ?? ucfirst($this->fuente);
    }

    public function getFuenteIconAttribute(): string
    {
        return self::FUENTES[$this->fuente]['icon'] ?? 'fas fa-tag';
    }

    public function getFuenteColorAttribute(): string
    {
        return self::FUENTES[$this->fuente]['color'] ?? '#6B7280';
    }

    /** Próxima acción vencida (fecha pasada y aún abierto). */
    public function getEstaVencidoAttribute(): bool
    {
        return $this->estado === self::ESTADO_ABIERTO
            && $this->proxima_accion_at !== null
            && $this->proxima_accion_at->isPast();
    }

    /** Próxima acción para hoy. */
    public function getEsHoyAttribute(): bool
    {
        return $this->proxima_accion_at !== null && $this->proxima_accion_at->isToday();
    }

    public function getDiasEnEtapaAttribute(): int
    {
        return (int) $this->updated_at->diffInDays(now());
    }

    public function getValorFormateadoAttribute(): string
    {
        if ($this->valor_estimado === null) {
            return '—';
        }
        $simbolo = $this->moneda === 'USD' ? 'US$' : '$';

        return $simbolo.number_format((float) $this->valor_estimado, 0, ',', '.');
    }

    public static function etapasTablero(): array
    {
        return self::ETAPAS;
    }
}
