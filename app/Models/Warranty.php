<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Warranty extends Model
{
    //
    protected $fillable = [
        'seller',
        'purchase_order',
        'purchase_order_pdf_path',
        'start_date',
        'end_date',
    ];
    
    public function warrantable(): MorphTo
    {
        return $this->morphTo();
    }
}
