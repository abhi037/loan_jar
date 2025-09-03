<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class, 'loan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function staff()
{
    return $this->belongsTo(Staff::class, 'id'); // Adjust the foreign key if needed
}
        
}
