<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class ArticleService
{
    private const ERROR = [
        'create' => 'Failed to create Article',   
        'update' => 'Failed to updating Article',   
        'delete' => 'Failed to deleting Article',   
    ];

    public function getAllArticles()
    {
        try {
            return Article::latest()->paginate(10);
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (getAllArticles) is : ' . $err->getMessage());
            return null;
        }
    }

    public function findByTitle(string $title)
    {
        try {
            // append title for prevent flash error from controller in view whene you switch between paginte page title send vide and flash an error
            return Article::where('title', 'like', '%' . $title . '%')->paginate(10)->appends(['title' => $title]);
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (findByTitle) is : ' . $err->getMessage());
            return null;
        }
    }

    public function filterByCategory(string $category, int $id)
    {
        try {
            return  Article::where('articleID', "!=", $id)
                            ->where('category', $category)->paginate(10);
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (filterByCategory) is : ' . $err->getMessage());
            return null;
        }
    }

    public function store(array $data): bool
    {
        try {
            DB::beginTransaction();
            $article = Article::create($data);
            if (!$article) throw new Exception(self::ERROR['create']);
            DB::commit();
            return true;
        } catch (Exception $err) {
            DB::rollBack();
            Log::error('The Error in ArticleService (store) is : ' . $err->getMessage());
            return false;
        }
    }

    public function update(array $data,Article $article): bool
    {
        try {
            DB::beginTransaction();
            $isUpdated = $article->update($data);
            if (!$isUpdated) throw new Exception(self::ERROR['update']);
            DB::commit();
            return true;
        } catch (Exception $err) {
            DB::rollBack();
            Log::error('The Error in ArticleService (update) is : ' . $err->getMessage());
            return false;
        }
    }

    public function delete(Article $article): bool
    {
        try {
            DB::beginTransaction();
            $isDeleted = $article->delete();
            if (!$isDeleted) throw new Exception(self::ERROR['delete']);
            DB::commit();
            return true;
        } catch (Exception $err) {
            DB::rollBack();
            Log::error('The Error in ArticleService (delete) is : ' . $err->getMessage());
            return false;
        }
    }
}
