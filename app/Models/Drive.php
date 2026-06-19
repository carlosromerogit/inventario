<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drive extends Model
{
    //
    protected $fillable = [
        'computer_id',
        'drive_type_id',
        'brand_model_id',
        'capacity_id',
    ];
 
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
 
    public function capacity(): BelongsTo
    {
        return $this->belongsTo(Capacity::class);
    }

}
