<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    private ArticleService $service;
    private const ERROR = [
        'index'  => 'No Articles retrived',
        'find'   => 'Article Not Found',
        'create' => 'Failed to create Article',
        'update' => 'Failed to updating Article',
        'delete' => 'Failed to deleting Article',
    ];

    public function __construct()
    {
        $this->service = new ArticleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = $this->service->getAllArticles();
            if (blank($articles)) throw new Exception(self::ERROR['index']);
            return response()->json(
                [
                    'status' => true,
                    'articles' => ArticleResource::collection($articles),
                    'pagination' => [
                        'current_page' => $articles->currentPage(),
                        'per_page' => $articles->count(),
                        'total' => $articles->total(),
                        'last_page' => $articles->lastPage(),
                    ]
                ],
                200
            );
        } catch (Exception $err) {
            Log::error('The Error in ArticleController(index) api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => self::ERROR['index']
                ],
                404
            );
        }
    }

    public function find(Request $request)
    {
        $valdation = $request->validate([
            'title' => 'required|string|max:100'
        ]);
        try {
            $articles = $this->service->findByTitle($valdation['title']);
            if (blank($articles)) throw new Exception(self::ERROR['index']);
            return response()->json(
                [
                    'status' => true,
                    'articles' => ArticleResource::collection($articles),
                    'pagination' => [
                        'current_page' => $articles->currentPage(),
                        'per_page' => $articles->count(),
                        'total' => $articles->total(),
                        'last_page' => $articles->lastPage(),
                    ]
                ],
                200
            );
        } catch (Exception $err) {
            Log::error('The Error in ArticleController(find) api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => self::ERROR['index']
                ],
                404
            );
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
            $article = $this->service->store($validationData);
            if (!$article) throw new Exception(self::ERROR['create']);
            return response()->json(
                [
                    'status' => true,
                    'article' => new ArticleResource($article)
                ],
                201
            );
        } catch (Exception $err) {
            Log::error('The Error in ArticleController(store) api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => self::ERROR['create']
                ],
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $article = $this->service->show($id);
            if (blank($article)) throw new Exception(self::ERROR['index']);
            $relatedArticles = $this->service->filterByCategory($article->category, $article->articleID) ?? null;
            return response()->json(
                [
                    'status' => true,
                    'article' => new ArticleResource($article),
                    'relatedArticles' => [
                        'articles' => ArticleResource::collection($relatedArticles),
                        'pagination' => [
                            'current_page' => $relatedArticles->currentPage(),
                            'per_page' => $relatedArticles->count(),
                            'total' => $relatedArticles->total(),
                            'last_page' => $relatedArticles->lastPage(),
                        ]
                    ]
                ],
                200
            );
        } catch (Exception $err) {
            Log::error('The Error in ArticleController(show) api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => self::ERROR['index']
                ],
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, int $id)
    {
        Gate::forUser($request->user())->authorize('isAdmin');
        try {
            // first check if the article exists
            $article = $this->service->show($id);
            if (blank($article)) throw new Exception(self::ERROR['find']);
            $validationData = $request->validated();
            if ($request->hasFile('image')) {
                $this->deleteImage($article);
                $validationData['image'] = $this->storeImage($request->file('image'));
            }
            if (!$this->service->update($validationData, $article)) throw new Exception(self::ERROR['update']);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Your updating is done',
                ],
                200
            );
        } catch (Exception $err) {
            Log::error('The Error in ArticleController(Update) in api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => self::ERROR['update']
                ],
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        Gate::forUser($request->user())->authorize('isAdmin');
        try {
            // first check if the article exists
            $article = $this->service->show($id);
            if (blank($article)) throw new Exception(self::ERROR['find']);
            if (!$this->service->delete($article)) throw new Exception(self::ERROR['delete']);
            $this->deleteImage($article);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Your deleting is done',
                ],
                200
            );
        } catch (Exception $err) {
            Log::error('The Error in ArticleController(delete) in api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => self::ERROR['delete']
                ],
                500
            );
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
