<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ArticleController extends Controller
{
    private ArticleService $service;

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
        try {
            $articles = $this->service->getAllArticles();
            if ($articles->isEmpty()) throw new Exception('No Articles retrived');
            return view('dashboard', compact('articles'));
        } catch (Exception $err) {
            // return bc i use blade and i can add if statment to check by empty()
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
            if ($articles->isEmpty()) throw new Exception('No Articles retrived');
            return view('home', compact('articles'));
        } catch (Exception $err) {
            // return bc i use blade and i can add if statment to check by empty()
            Log::error('The Error  in ArticleController (index) is :' . $err->getMessage());
            return view('home', compact('articles'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addArticle');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('/ArticlesImages', 'public');
            }
            if (!$this->service->store($validatedData)) throw new Exception('Failed to create Article');
            return redirect()->route('dashboard');
        } catch (Exception $err) {
            Log::error('The Error  in ArticleController (store) is :' . $err->getMessage());
            return back()->whit('error', 'Plz try Again  or Plz reporte us');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(Request  $request)
    {
        try {

            $articles = $this->service->findByTitle($request->title);
            if (empty($articles)) throw new Exception('No Articles retrived');
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
            // related articles by Category
            $relatedArticles = $this->service->filterByCategory($article->category, $article->articleID);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
