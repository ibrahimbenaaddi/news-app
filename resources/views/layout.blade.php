<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    @yield('style')
</head>

<body>
    @hasSection('nav')
    <!-- Navbar -->
    <nav class="navbar">
        @yield('nav')
    </nav>
    @endif


    <!-- Main Content -->
    <main class="container" >
        @yield('main')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025/2026 NewsApp. All rights reserved. | Stay informed with the latest news from around the world.</p>
    </footer>
</body>

</html>