<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes, HasFactory;

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

    public function relatedArticles()
    {
        return $this->hasMany(Article::class, 'category', 'category')
            ->where('articleID', '!=',$this->articleID)->latest()->limit(6);
    }
}
