<?php

namespace App\Services;

use App\Models\Article;
use App\Traits\LogError;
use App\Traits\Searchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class ArticleService
{
    use LogError, Searchable;

    private const PER_PAGE = 10; // if you have php version > 8.2  use this is typed and safe private const int perPage = 10;
    public function getAllArticles(Request $request)
    {
        try {
            $query = Article::query();
            if ($request->filled('title')) {
                $term = '%' . $request->query('title') . '%';
                $query->where('title', 'like', $term);
            }
            $page = self::limitThePages($query, $request, self::PER_PAGE);
            return $query->latest()->paginate(page: $page);
        } catch (Exception $error) {
            return LogError::theLog('getAllArticles', 'ArticleService', $error);
        }
    }

    public function filterByCategory(string $category, int $id)
    {
        try {
            return  Article::where('articleID', "!=", $id)
                ->where('category', $category)->paginate(4);
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (filterByCategory) is : ' . $err->getMessage());
            return null;
        }
    }

    public function show(int $id)
    {
        try {
            return  Article::find($id);
        } catch (Exception $err) {
            Log::error('The Error in ArticleService (show) is : ' . $err->getMessage());
            return null;
        }
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $article = Article::create($data);
            if (!$article) throw new Exception(self::ERROR['create']);
            DB::commit();
            return $article;
        } catch (Exception $err) {
            DB::rollBack();
            Log::error('The Error in ArticleService (store) is : ' . $err->getMessage());
            return false;
        }
    }

    public function update(array $data, Article $article): bool
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
