@extends('layout')

@section('title')
NewsApp Admin Dashboard
@endSection

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

    /* Global Body Styles */
    body {
        background-color: var(--dark-bg);
        color: var(--text-light);
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        background-color: var(--card-bg);
        padding: 1.5rem 0;
        box-shadow: 4px 0 12px rgba(0, 0, 0, 0.4);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .logo i {
        font-size: 2rem;
        color: var(--primary);
    }

    .logo h1 {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .logo span {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .menu {
        list-style: none;
    }

    .menu-section {
        padding: 1rem 1.5rem 0.5rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .menu-item {
        padding: 0.9rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--text-light);
        text-decoration: none;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .menu-item:hover,
    .menu-item.active {
        background-color: rgba(58, 134, 255, 0.1);
        border-left-color: var(--primary);
        color: var(--primary);
    }

    .menu-item i {
        width: 20px;
        text-align: center;
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        padding: 2rem;
        min-height: 100vh;
        width: calc(100% - 250px);
        display: flex;
        flex-direction: column;
    }

    /* Top Bar */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title h2 {
        font-size: 1.8rem;
        color: #fff;
    }

    .page-title p {
        color: var(--text-muted);
        margin-top: 0.3rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }

    .user-name {
        font-weight: 600;
    }

    .user-role {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: linear-gradient(to bottom right, var(--card-dark), var(--card-bg));
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-icon.articles {
        background: rgba(58, 134, 255, 0.15);
        color: var(--primary);
    }

    .stat-info h3 {
        font-size: 1.8rem;
    }

    .stat-info p {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    /* Dashboard Sections */
    .dashboard-section {
        margin-bottom: 2.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.4rem;
        color: #fff;
    }

    /* Buttons */
    .btn {
        padding: 0.8rem 1.8rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(58, 134, 255, 0.3);
    }

    .btn-secondary {
        background-color: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-light);
    }

    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .btn-success {
        background-color: var(--success);
        color: white;
    }

    .btn-success:hover {
        background-color: #3d8b40;
    }

    /* Tables */
    .table-container {
        background: linear-gradient(to bottom right, var(--card-dark), var(--card-bg));
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    th {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .action-btns {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: none;
        color: var(--text-light);
        cursor: pointer;
        transition: 0.3s;
    }

    .action-btn.edit:hover {
        color: var(--primary);
    }

    .action-btn.delete:hover {
        color: var(--danger);
    }

    /* Footer */
    .footer {
        margin-top: auto;
        text-align: center;
        padding: 1.5rem;
        color: var(--text-muted);
        border-top: 1px solid var(--border-color);
        background: var(--card-bg);
    }

    /* Article Creation Specific Styles */
    .container {
        margin: 0 auto;
        width: 100%;
    }

    /* Article Form */
    .article-form {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        flex: 1;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-light);
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 1rem;
        background-color: #2d2d2d;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-light);
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
    }

    .form-textarea {
        min-height: 300px;
        resize: vertical;
        font-family: inherit;
        line-height: 1.6;
    }

    /* Category Select */
    .form-select {
        cursor: pointer;
        background-position: right 1rem center;
        padding-right: 3rem;
    }

    /* Image Upload */
    .image-upload {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .image-upload:hover {
        border-color: var(--primary);
        background-color: rgba(58, 134, 255, 0.05);
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .upload-text {
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .image-preview {
        display: none;
        margin-top: 1rem;
        text-align: center;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Character Counter */
    .char-counter {
        text-align: right;
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    /* Notification */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: none;
        align-items: center;
        gap: 10px;
    }

    .notification.show {
        display: flex;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Status Badges */
    .status {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status.published {
        background-color: rgba(76, 175, 80, 0.15);
        color: var(--success);
    }

    .status.draft {
        background-color: rgba(255, 152, 0, 0.15);
        color: var(--warning);
    }

    .status.pending {
        background-color: rgba(33, 150, 243, 0.15);
        color: #2196F3;
    }

    .status.scheduled {
        background-color: rgba(156, 39, 176, 0.15);
        color: #9C27B0;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .sidebar {
            width: 70px;
        }

        .logo h1,
        .logo span,
        .menu-item span,
        .menu-section {
            display: none;
        }

        .menu-item {
            justify-content: center;
        }

        .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .top-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .user-info {
            align-self: flex-end;
        }

        .article-form {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endSection

@section('main')
<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo">
        <i class="fas fa-newspaper"></i>
        <div>
            <h1>NewsApp</h1>
            <span>Admin Dashboard</span>
        </div>
    </div>

    <ul class="menu">
        <div class="menu-section">Main</div>
        <li><a href="{{ route('dashboard') }}" class="menu-item"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
        </li>
        <li><a href="{{ route('add') }}" class="menu-item"><i class="fas fa-edit"></i> <span>Write Article</span></a></li>
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item" style="background: none;
                                                border: none;
                                                width: 100%;
                                                text-align: left;
                                                cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</aside>

<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h2>Dashboard Overview</h2>
            <p>Welcome back, Admin! Here's what's happening with your news app today.</p>
        </div>

        <div class="user-info">
            <div class="user-avatar">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <div class="user-name">Admin User</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>

    @yield('dashboardSection')
</main>
@endsection