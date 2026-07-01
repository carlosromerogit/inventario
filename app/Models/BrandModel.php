<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrandModel extends Model
{
    //
    protected $fillable = [
        'brand_id',
        'name',
        'type',
    ];
 
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
 
    public function computers(): HasMany
    {
        return $this->hasMany(Computer::class);
    }
 
    public function drives(): HasMany
    {
        return $this->hasMany(Drive::class);
    }

}
