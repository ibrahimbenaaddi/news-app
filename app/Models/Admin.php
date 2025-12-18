<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends User
{
    use SoftDeletes;

    protected $primaryKey = "adminID";

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];
}
