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
        'extension',
        'department_id',
        'company_id',
        'employee_code',
        'work_shift'
    ];
 
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
 
    public function computers(): HasMany
    {
        return $this->hasMany(Computer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
