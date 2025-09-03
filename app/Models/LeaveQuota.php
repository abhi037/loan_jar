<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveQuota extends Model
{
    protected $table = 'leave_quotas';
    protected $fillable = [
        'leavetype',
        'duration',
    ];
}
