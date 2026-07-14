<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Warranty extends Model
{
    protected $fillable = [
        'warranty_code',
        'provider',
        'start_date',
        'end_date',
        'notes',
        'document_path'
    ];

    public function computers(): MorphToMany
    {
        return $this->morphedByMany(
            Computer::class,
            'warrantable',
            'warrantables',
            'warranty_id',
            'warrantable_id'
        );
    }
}