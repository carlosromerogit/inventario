<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    //
    protected $fillable = [
        'first_name',
        'last_name',
        'department_id',
    ];
 
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
 
    public function computers(): HasMany
    {
        return $this->hasMany(Computer::class);
    }

}
