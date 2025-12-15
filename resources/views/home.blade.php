<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsApp - Latest Articles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: #121212;
            color: #e0e0e0;
            min-height: 100vh;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #1e1e1e;
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
        }

        .logo i {
            font-size: 1.8rem;
            color: #3a86ff;
        }

        .logo h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(to right, #3a86ff, #6c63ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .search-container {
            display: flex;
            width: 100%;
            max-width: 500px;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px 0 0 8px;
            background-color: #2d2d2d;
            color: #e0e0e0;
            font-size: 1rem;
            outline: none;
        }

        .search-input::placeholder {
            color: #aaa;
        }

        .search-btn {
            background-color: #3a86ff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background-color: #2667cc;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-title {
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
            color: #fff;
        }

        /* Cards Grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        /* Card Styles */
        .article-card {
            background: linear-gradient(to bottom right, #2d2d2d, #1a1a1a);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .article-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.7);
        }

        .card-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
            border-bottom: 3px solid #3a86ff;
        }

        .card-category {
            display: inline-block;
            background-color: #3a86ff;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            align-self: flex-start;
        }

        .card-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: #fff;
            line-height: 1.4;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #444;
        }

        .card-date {
            color: #888;
            font-size: 0.9rem;
        }

        .read-more {
            color: #3a86ff;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s;
        }

        .read-more:hover {
            color: #6c63ff;
        }

        .read-more i {
            font-size: 0.9rem;
            transition: transform 0.3s;
        }

        .read-more:hover i {
            transform: translateX(4px);
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: #888;
            border-top: 1px solid #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .search-container {
                max-width: 100%;
            }

            .articles-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-newspaper"></i>
            <h1>NewsApp</h1>
        </div>
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search for news, topics, or authors...">
            <button class="search-btn">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <h2 class="page-title">Today's Top Stories</h2>
        @if(empty($articles))
            <h1>Ops ,No articles Now At this moment Come Back Later , Thank you</h1>
        @else
        <div class="articles-grid">

            <!-- Article Card Template -->
            @foreach($articles as $article)
            <article class="article-card">
                <img src="{{ asset('storage/' . ($article->image ?? 'ArticlesImages/default.png')) }}" class="card-image">
                <div class="card-content">
                    <span class="card-category">{{ $article->category }}</span>
                    <h3 class="card-title">{{ $article->title }}</h3>
                    <div class="card-footer">
                        <span class="card-date">{{ $article->created_at->format('F d, Y') }}</span>
                        <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025/2026 NewsApp. All rights reserved. | Stay informed with the latest news from around the world.</p>
    </footer>
</body>

</html>