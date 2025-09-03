<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designations';
    protected $fillable = [
        'company_id',
        'department_id',
        'designation_name',
        'status',
        'position_by',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department that owns the designation.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
