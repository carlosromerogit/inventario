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

    public function getFormattedCapacityAttribute(): string
    {
        return $this->capacity_value . ' ' . $this->capacity_unit;
    }

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