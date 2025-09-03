<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'department_name',
        'company_id',
        'position_by',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
