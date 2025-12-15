<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Exception;
class ArticleService
{

    public function getAllArticles()
    {
        try {
            return Article::all();
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (getAllArticles) is : ' . $err->getMessage() );
            return null;
        }
    }
}
