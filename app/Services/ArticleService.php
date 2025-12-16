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
            Log::error('The Error in ArticleService (getAllArticles) is : ' . $err->getMessage());
            return null;
        }
    }

    public function findByTitle(string $title)
    {
        try {
            return Article::where('title', 'like', '%' . $title . '%')->get();
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (findByTitle) is : ' . $err->getMessage());
            return null;
        }
    }

    public function filterByCategory(string $category)
    {
        try {
            return Article::where('category', $category )->get();
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (filterByCategory) is : ' . $err->getMessage());
            return null;
        }
    }
}
