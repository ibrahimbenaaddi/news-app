<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Exception;

class ArticleController extends Controller
{
    private ArticleService $service;
    private const ERROR = [
        'index' =>  'No Articles retrived',
        'create' => 'Failed to create Article',
        'update' => 'Failed to updating Article',
        'delete' => 'Failed to deleting Article',
    ];

    public function __construct()
    {
        $this->middleware('isAuth')->except(['home', 'show', 'find']);
        $this->service = new ArticleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isAdmin');
        try {
            $articles = $this->service->getAllArticles();
            // the usage of isEmpty() on null give us Error insted use blank() handle null empty array & collection 
            if (blank($articles)) throw new Exception(self::ERROR['index']);
            return view('dashboard', compact('articles'));
        } catch (Exception $err) {
            // return bc i use blade and i can add if statment to check by blank()
            Log::error('The Error  in AdminController (index) is :' . $err->getMessage());
            return view('dashboard', compact('articles'));
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        try {
            $articles = $this->service->getAllArticles();
            if (blank($articles)) throw new Exception(self::ERROR['index']);
            return view('home', compact('articles'));
        } catch (Exception $err) {
            // return bc i use blade and i can add if statment to check by blank()
            Log::error('The Error  in ArticleController (home) is :' . $err->getMessage());
            return view('home', compact('articles'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isAdmin');
        return view('addArticle');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if ($request->hasFile('image')) {
                $validatedData['image'] = $this->storeImage($request->file('image'));
            }
            if (!$this->service->store($validatedData)) throw new Exception(self::ERROR['create']);
            return redirect()->route('dashboard');
        } catch (Exception $err) {
            Log::error('The Error  in ArticleController (store) is :' . $err->getMessage());
            return back()->whit('error', 'Plz try Again, '.self::ERROR['create']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(Request  $request)
    {
        try {
            if (empty($request->title)) {
                return back()->with('error', 'plz enter the Title not string vide , Thank you');
            }
            $articles = $this->service->findByTitle($request->title);
            if (blank($articles)) throw new Exception(self::ERROR['index']);
            return view('home', compact('articles'));
        } catch (Exception $err) {
            Log::error('The Error  in ArticleController (find) is :' . $err->getMessage());
            return view('home', compact('articles'));
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try {
            // related articles by Category / i add blank() on it in blade
            $relatedArticles = $this->service->filterByCategory($article->category, $article->articleID) ?? null ;
            return view('article', compact(['article', 'relatedArticles']));
        } catch (Exception $err) {
            Log::error('The Error  in ArticleController (show) is :' . $err->getMessage());
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isAdmin');
        return view('editArticle', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isAdmin');
        try {
            $validatedData = $request->validated();
            if ($request->hasFile('image')) {
                $this->deleteImage($article);
                $validatedData['image'] = $this->storeImage($request->file('image'));
            }
            if (!$this->service->update($validatedData, $article)) throw new Exception(self::ERROR['update']);
            return redirect()->route('dashboard');
        } catch (Exception $err) {
            Log::error('The Error in ArticleController (update) is : ' . $err->getMessage());
            return back()->with('error', 'try Again, '.self::ERROR['update']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isAdmin');
        try {
            $this->deleteImage($article);
            if (!$this->service->delete($article)) throw new Exception(self::ERROR['delete']);
            return redirect()->route('dashboard');
        } catch (Exception $err) {
            Log::error('The Error in ArticleController (update) is : ' . $err->getMessage());
            return back()->with('error', 'try Again, '.self::ERROR['delete']);
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
