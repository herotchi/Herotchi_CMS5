<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'login_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
