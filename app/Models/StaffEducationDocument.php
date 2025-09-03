<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffEducationDocument extends Model
{
    protected $table = 'staff_education_documents';

    protected $fillable = [
        'user_id',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

}
