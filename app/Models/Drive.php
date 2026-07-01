<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drive extends Model
{
    use HasFactory;

    protected $fillable = [
        'computer_id',
        'drive_type_id',
        'brand_model_id',
        'capacity_value',
        'capacity_unit',
        'capacity_in_mb',
    ];

    /**
     * ACCESOR: Formatea de forma automática la capacidad para las vistas.
     * Convierte los datos nativos de la BD en un string limpio como "512 GB" o "2 TB".
     */
    public function getFormattedCapacityAttribute(): string
    {
        return $this->capacity_value . ' ' . $this->capacity_unit;
    }

    // ─── Relaciones del Modelo ───────────────────────────────────────────────

    public function computer(): BelongsTo
    {
        return $this->belongsTo(Computer::class);
    }

    public function driveType(): BelongsTo
    {
        return $this->belongsTo(DriveType::class);
    }

    public function brandModel(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class);
    }
}