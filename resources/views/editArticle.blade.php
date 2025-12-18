@extends('layoutdash')

@section('dashboardSection')
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-newspaper"></i>
                <h1>NewsApp</h1>
            </div>
            <h2>Edit The Article</h2>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <!-- Article Form -->
        <form class="article-form" id="articleForm" action="{{ route('update' , $article ) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <!-- Title -->
            <div class="form-group">
                <label class="form-label" for="articleTitle">Article Title</label>
                <input type="text" class="form-input" id="articleTitle" name='title' value="{{ old('title', $article->title ) }}"placeholder="Enter your article title" >

            </div>

            <!-- Category -->
            <div class="form-group">
                <label class="form-label" for="articleCategory">Category</label>
                <select class="form-select" id="articleCategory" name="category" >
                    <option value="">Select a category</option>
                    <option value="Technology" {{ old('category', $article->category) == 'Technology' ? 'selected' : '' }} >Technology</option>
                    <option value="Business" {{ old('category', $article->category ) == 'Business' ? 'selected' : '' }} >Business</option>
                    <option value="Health" {{ old('category', $article->category) == 'Health' ? 'selected' : '' }} >Health</option>
                    <option value="Sports" {{ old('category', $article->category) == 'Sports' ? 'selected' : '' }} >Sports</option>
                    <option value="Entertainment" {{ old('category', $article->category) == 'Entertainment' ? 'selected' : '' }} >Entertainment</option>
                    <option value="Environment" {{ old('category', $article->category) == 'Environment' ? 'selected' : '' }} >Environment</option>
                </select>
            </div>

            <!-- Body Content -->
            <div class="form-group">
                <label class="form-label" for="articleBody">Article Content</label>
                <textarea class="form-textarea" name="body" id="articleBody" placeholder="Write your article content here..." >{{ old('body', $article->body) }}</textarea>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label class="form-label">Featured Image</label>
                <div class="image-upload" id="imageUpload">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        Click to upload or drag and drop
                    </div>
                    <div class="upload-hint">
                        JPG, PNG or GIF
                    </div>
                    <input type="file" id="imageFile" accept=".png,.jpeg,.jpg" name="image">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary" id="cancelBtn">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="publishBtn">
                    <i class="fas fa-paper-plane"></i> Update Article
                </button>
            </div>
        </form>
    </div>
@endsection