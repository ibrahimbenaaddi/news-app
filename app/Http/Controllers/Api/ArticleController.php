<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Traits\ApiResponses;
use App\Traits\Helper;
use App\Traits\LogError;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class ArticleController extends Controller
{
    use ApiResponses, LogError, Helper;

    private ArticleService $service;
    private const PK_NAME = 'articleID';
    private const TABLE = 'articles';

    public function __construct()
    {
        $this->service = new ArticleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(QueryRequest $request)
    {
        try {
            if (!$articles = $this->service->getAllArticles($request)) {
                return self::failedRead();
            };
            return self::read(ArticleResource::collection($articles));
        } catch (Exception $error) {
            self::theLog('index', 'ArticleController', $error);
            return self::failedRead();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        Gate::forUser($request->user())->authorize('isAdmin');
        try {
            $validationData = $request->validated();
            if ($request->hasFile('image')) {
                $validationData['image'] = $this->storeImage($request->file('image'));
            }
            if (!$article = $this->service->store($validationData)) {
                return self::failedCreate();
            }
            return self::create(new ArticleResource($article));
        } catch (Exception $error) {
            self::theLog('store', 'ArticleController', $error);
            return self::failedCreate();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            if (!self::validationId(self::PK_NAME, self::TABLE, $id)) {
                return self::failedRead();
            }
            if (!$article = $this->service->getArticleById($id)) {
                return self::failedRead();
            };
            return self::read(new ArticleResource($article));
        } catch (Exception $error) {
            self::theLog('show', 'ArticleController', $error);
            return self::failedRead();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, int $id)
    {
        Gate::forUser($request->user())->authorize('isAdmin');
        try {
            if (!self::validationId(self::PK_NAME, self::TABLE, $id)) {
                return self::failedUpdate();
            }
            if (!$article = $this->service->getArticleById($id)) {
                return self::failedUpdate();
            };

            $validationData = $request->validated();
            if ($request->hasFile('image')) {
                $this->deleteImage($article);
                $validationData['image'] = $this->storeImage($request->file('image'));
            }
            if (!$article = $this->service->update($validationData, $article)) {
                return self::failedUpdate();
            };
            return self::updated(new ArticleResource($article));
        } catch (Exception $error) {
            self::theLog('update', 'ArticleController', $error);
            return self::failedUpdate();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        Gate::forUser($request->user())->authorize('isAdmin');
        try {
            if (!self::validationId(self::PK_NAME, self::TABLE, $id)) {
                return self::failedDelete();
            }
            if (!$article = $this->service->getArticleById($id)) {
                return self::failedDelete();
            };
            if (!$this->service->delete($article)) {
                return self::failedDelete();
            }
            $this->deleteImage($article);
            return self::delete();
        } catch (Exception $error) {
            self::theLog('delete', 'ArticleController', $error);
            return self::failedDelete();
        }
    }

    private function storeImage($file): string
    {
        return $file->store('/ArticlesImages', 'public');
    }

    private function deleteImage(Article $article): void
    {
        if ($article->image && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }
    }
}
