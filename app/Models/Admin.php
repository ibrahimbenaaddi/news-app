<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Admin extends User
{
    use SoftDeletes ,HasApiTokens;

    protected $primaryKey = "adminID";

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];
}
