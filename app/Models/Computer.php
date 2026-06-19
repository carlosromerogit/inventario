<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Computer extends Model
{
    //
    protected $fillable = [
        'brand_model_id',
        'serial',
        'department_id',
        'processor',
        'ram',
        'employee_id',
        'operating_system_id',
    ];
 
    public function brandModel(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class);
    }
 
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
 
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
 
    public function operatingSystem(): BelongsTo
    {
        return $this->belongsTo(OperatingSystem::class);
    }
 
    public function drives(): HasMany
    {
        return $this->hasMany(Drive::class);
    }
 
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

}
