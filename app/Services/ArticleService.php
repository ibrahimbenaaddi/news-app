<?php

namespace App\Services;

use App\Models\Article;
use App\Traits\LogError;
use App\Traits\Searchable;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class ArticleService
{
    use LogError, Searchable;

    private const PER_PAGE = 9; // if you have php version > 8.2  use this is typed and safe private const int perPage = 10;

    public function getAllArticles(Request $request)
    {
        try {
            $query = Article::query();
            if ($request->filled('search')) {
                $term = '%' . $request->query('search') . '%';
                $query->where('category', 'like', $term)->orWhere('title', "like", $term);
            }
            $page = self::limitThePages($query, $request, self::PER_PAGE);
            return $query->latest()->paginate(perPage: self::PER_PAGE, page: $page);
        } catch (Exception $error) {
            self::theLog('getAllArticles', 'ArticleService', $error);
            return false;
        }
    }

    public function getArticleById(int $id)
    {
        try {
            return Article::findOrFail($id);
        } catch (Exception $error) {
            self::theLog('getArticleById', 'ArticleService', $error);
            return false;
        }
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            if (!$article = Article::create($data)) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            return $article;
        } catch (Exception $error) {
            DB::rollBack();
            self::theLog('store', 'ArticleService', $error);
            return false;
        }
    }

    public function update(array $data, Article $article)
    {
        try {
            DB::beginTransaction();
            if (!$article->update($data)) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            $article->refresh();
            return $article;
        } catch (Exception $error) {
            DB::rollBack();
            self::theLog('update', 'ArticleService', $error);
            return false;
        }
    }

    public function delete(Article $article): bool
    {
        try {
            DB::beginTransaction();
            if (!$article->delete()){
                DB::rollBack();
                return false;
            };
            DB::commit();
            return true;
        } catch (Exception $error) {
            DB::rollBack();
            self::theLog('delete', 'ArticleService', $error);
            return false;
        }
    }
}
