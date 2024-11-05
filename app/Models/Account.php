<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'cheque_number',
        'bank_name',
        'amount',
        'status',
        'remark',
    ];
}
