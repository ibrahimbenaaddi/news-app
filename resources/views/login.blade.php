@extends('layout')

@section('title')
NewsApp - Login
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
    --input-bg: #2d2d2d;
}

body {
    background-color: var(--dark-bg);
    color: var(--text-light);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(58, 134, 255, 0.1) 0%, transparent 20%),
        radial-gradient(circle at 80% 20%, rgba(108, 99, 255, 0.1) 0%, transparent 20%);
}

/* Main content wrapper */
.content-wrapper {
    flex: 1;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Logo Container */
.logo-container {
    display: flex;
    width: 100%;
    text-align: center;
    margin-bottom: 2.5rem;
    justify-content: center;
}

.logo {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1rem;
}

.logo i {
    font-size: 2.5rem;
    color: var(--primary);
}

.logo h1 {
    font-size: 2.2rem;
    font-weight: 700;
    background: linear-gradient(to right, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

/* Login Container */
.login-container {
    width: 100%;
    animation: fadeIn 0.8s ease-out;
}

/* Fade animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Login Card */
.login-card {
    width: 100%;
    max-width: 670px;
    margin: 0 auto;
    background: linear-gradient(to bottom right, var(--card-dark), var(--card-bg));
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.card-header {
    text-align: center;
    margin-bottom: 2rem;
}

.card-header h2 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: #fff;
}

.card-header p {
    color: var(--text-muted);
    font-size: 0.95rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.6rem;
    font-weight: 600;
    color: var(--text-light);
    font-size: 0.95rem;
}

.input-with-icon {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 1.1rem;
}

.form-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    background-color: var(--input-bg);
    border: 2px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-light);
    font-size: 1rem;
    transition: all 0.3s;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
}

.form-input::placeholder {
    color: var(--text-muted);
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(to right, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 1.5rem;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(58, 134, 255, 0.3);
}

.submit-btn:active {
    transform: translateY(0);
}

/* Footer */
.footer {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
    border-top: 1px solid var(--border-color);
    background-color: var(--card-bg);
    width: 100%;
    margin-top: auto;
}

/* Responsive Design */
@media (max-width: 480px) {
    .login-card {
        padding: 2rem 1.5rem;
    }

    .logo h1 {
        font-size: 1.8rem;
    }

    .logo i {
        font-size: 2rem;
    }

    .footer {
        padding: 1.5rem 1rem;
    }
}

</style>
@endsection


@section('main')
<div class="login-container">
    <div class="logo-container">
        <div class="logo">
            <i class="fas fa-newspaper"></i>
            <h1>NewsApp</h1>
        </div>
    </div>

    <div class="login-card">
        <div class="card-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your admin account</p>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger errorList">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('failed'))
        <div class="alert alert-danger errorList">
            {{ session('failed') }}
        </div>
        @endif
        <form action="{{ route('login') }}" id="loginForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope input-icon"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="Enter your Email"
                        class="form-input"
                        value="{{ old('email')}}"
                        >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                        placeholder="Enter your password">
                </div>
            </div>
            <button type="submit" class="submit-btn" id="submitBtn">
                <span>Sign In</span>
                <i class="fas fa-sign-in-alt"></i>
            </button>

    </div>

    </form>
</div>
@endsection