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

            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new Exception(self::ERROR['index']);
            }

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

            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new Exception(self::ERROR['index']);
            }

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
