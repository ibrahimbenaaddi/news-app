@extends('layout')

@section('title')
{{$article->title}}
@endsection


@section('style')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    :root {
        --primary: #3a86ff;
        --primary-dark: #2667cc;
        --secondary: #6c63ff;
        --success: #4CAF50;
        --warning: #FF9800;
        --danger: #F44336;
        --dark-bg: #121212;
        --card-bg: #1e1e1e;
        --card-dark: #2d2d2d;
        --text-light: #e0e0e0;
        --text-muted: #888;
        --border-color: #444;
    }

    body {
        background-color: var(--dark-bg);
        color: var(--text-light);
        min-height: 100vh;
        line-height: 1.6;
    }

    /* Navbar */
    .navbar {
        background-color: var(--card-bg);
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .logo i {
        font-size: 1.8rem;
        color: var(--primary);
    }

    .logo h1 {
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-links a {
        color: var(--text-light);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-links a:hover {
        color: var(--primary);
    }

    .back-btn {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background-color 0.3s;

    }

    .back-btn:hover {
        background-color: var(--primary-dark);
    }

    /* Main Content */
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Article Header */
    .article-header {
        margin-bottom: 3rem;
        position: relative;
    }

    .category-badge {
        display: inline-block;
        background-color: var(--primary);
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .article-title {
        font-size: 2.5rem;
        line-height: 1.3;
        margin-bottom: 1.5rem;
        color: #fff;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 1.2rem;
    }

    .author-details h3 {
        margin-bottom: 0.3rem;
        color: #fff;
    }

    .author-details p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* Article Hero Image */
    .article-hero {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .article-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Article Content */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 3rem;
    }

    .article-content p {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        margin-bottom: 1.5rem;
    }

    .article-content h2 {
        font-size: 1.8rem;
        margin: 2.5rem 0 1.5rem;
        color: #fff;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
    }

    .article-content h3 {
        font-size: 1.4rem;
        margin: 2rem 0 1rem;
        color: #fff;
    }

    .article-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: var(--text-muted);
        font-size: 1.2rem;
    }

    .article-content ul,
    .article-content ol {
        margin: 1.5rem 0;
        padding-left: 1.5rem;
    }

    .article-content li {
        margin-bottom: 0.8rem;
    }

    /* Related Articles */
    .related-section {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .section-title {
        font-size: 1.8rem;
        margin-bottom: 2rem;
        color: #fff;
    }

    .related-articles {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .related-card {
        background: linear-gradient(to bottom right, var(--card-dark), var(--card-bg));
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s;
    }

    .related-card:hover {
        transform: translateY(-5px);
    }

    .related-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .related-card-content {
        padding: 1.5rem;
    }

    .related-card h3 {
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
        color: #fff;
    }

    .related-card p {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .related-card .read-more {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Footer */
    .footer {
        text-align: center;
        padding: 3rem 2rem;
        margin-top: 4rem;
        color: var(--text-muted);
        border-top: 1px solid var(--border-color);
        background-color: var(--card-bg);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .nav-links {
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .article-title {
            font-size: 2rem;
        }

        .container {
            padding: 1.5rem;
        }

    }
</style>
@endsection

@section('nav')
<a href="/" class="logo">
    <i class="fas fa-newspaper"></i>
    <h1>NewsApp</h1>
</a>

<div class="nav-links">
    <a href="/" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to News
    </a>
</div>
@endsection

@section('main')
<!-- Article Header -->
<header class="article-header">
    <span class="category-badge">{{ $article->category }}</span>
    <h1 class="article-title">{{ $article->title }}</h1>
</header>

<!-- Article Hero Image -->
<div class="article-hero">
    <img src="{{ asset('storage/' . ($article->image ?? 'ArticlesImages/default.png')) }}">
</div>

<!-- Article Content -->
<article class="article-content">
    <p>{{ $article->body}}</p>
</article>

@unless(blank($relatedArticles))
<!-- Related Articles -->
<section class="related-section">
    <h2 class="section-title">Related Articles</h2>
    @foreach($relatedArticles as $relatedArticle)
    <div class="related-articles">
        <article class="related-card">
            <img src="{{ asset('storage/' . ($relatedArticle->image ?? 'ArticlesImages/default.png')) }}">
            <div class="related-card-content">
                <h3>{{ $relatedArticle->title}}</h3>
                <a href="{{ route('show', $relatedArticle) }}" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>
        @endforeach
        @if(!$relatedArticles->count() < 10)
        {{$relatedArticles->links()}}
        @endif
</section>
@endunless
@endsection