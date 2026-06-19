<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperatingSystem extends Model
{
    //
    protected $fillable = [
        'name',
    ];
 
    public function computers(): HasMany
    {
        return $this->hasMany(Computer::class);
    }

}
