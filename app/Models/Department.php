<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    //
    protected $fillable = [
        'name',
    ];
 
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
 
    public function computers(): HasMany
    {
        return $this->hasMany(Computer::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

}
