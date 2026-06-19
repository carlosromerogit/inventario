<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    //
    protected $fillable = [
        'path',
        'computer_id',
    ];
 
    public function computer(): BelongsTo
    {
        return $this->belongsTo(Computer::class);
    }

}
