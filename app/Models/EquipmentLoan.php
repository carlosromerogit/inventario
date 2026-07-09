<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EquipmentLoan extends Model
{
    //
    protected $guarded = [];

    public function loanable(): MorphTo
    {
        return $this->morphTo();
    }
}
