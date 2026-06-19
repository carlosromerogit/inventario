<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriveType extends Model
{
    //
    protected $fillable = [
        'name',
    ];
 
    public function drives(): HasMany
    {
        return $this->hasMany(Drive::class);
    }

}
