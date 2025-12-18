<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class ArticleService
{

    public function getAllArticles()
    {
        try {
            return Article::paginate(10);
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

    public function filterByCategory(string $category , int $id)
    {
        try {
            return  Article::where('articleID',"!=", $id)
                            ->where('category', $category)->paginate(10);
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (filterByCategory) is : ' . $err->getMessage());
            return null;
        }
    }

    public function store(array $data ,): bool
    {
        try {
            DB::beginTransaction();
            $article = Article::create($data);
            if (!$article) throw new Exception('Failed to create Article');
            DB::commit();
            return true;
        } catch (Exception $err) {
            DB::rollBack();
            Log::error('The Error in ArticleService (store) is : ' . $err->getMessage());
            return false;
        }
    }
}
