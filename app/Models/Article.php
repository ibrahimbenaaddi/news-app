<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends User
{
    use SoftDeletes;

    protected $table = 'articles';
    protected $primaryKey = 'articleID';

    protected $fillable = [
        'articleID',
        'title',
        'body',
        'category',
        'image',
    ];

    public function getImage()
    {
        return is_null($this->image) ? asset('default.png') : asset('storage/' . $this->image);                                             
    }

}
