<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Staff extends Model
{
    protected $table = 'staffdetails';
    protected $guarded = [];
    protected $fillable = [
        'company_id',
        'department_id',
        'designation_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'status',
        'icon',
        'aadhar_number',
        'aadhar_certificate',
        'pan_number',
        'pan_certificate',
        'domicile',
        'employee_category',
        'experience_letter',
        'offer_letter',
        'revealing_letter',
        'joining_letter',
        'bank_name',
        'account_number',
        'ifsc_code',
        'joining_date',
        'confirmation_date',
        'resignation_date',
        'exit_date',

    ];
    protected $hidden = [
        'password',
        'remember_token',
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
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
   
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
}
