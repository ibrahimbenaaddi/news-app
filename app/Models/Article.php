<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends User
{
    use SoftDeletes;

    protected $primaryKey = 'articleID';

    protected $fillable = [
        'articleID',
        'title',
        'body',
        'category',
        'image',
    ];
}
