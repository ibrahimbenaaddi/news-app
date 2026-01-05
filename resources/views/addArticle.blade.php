@extends('layoutdash')

@section('dashboardSection')
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-newspaper"></i>
                <h1>NewsApp</h1>
            </div>
            <h2>Create New Article</h2>
            <p>Write and publish your story</p>
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
        @if (session('error'))
        <div class="alert alert-danger errorList">
            {{ session('error') }}
        </div>
        @endif
        <!-- Article Form -->
        <form class="article-form" id="articleForm" action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Title -->
            <div class="form-group">
                <label class="form-label" for="articleTitle">Article Title</label>
                <input type="text" class="form-input" id="articleTitle" name='title' value="{{ old('title') }}"placeholder="Enter your article title" >

            </div>

            <!-- Category -->
            <div class="form-group">
                <label class="form-label" for="articleCategory">Category</label>
                <select class="form-select" id="articleCategory" name="category" >
                    <option value="">Select a category</option>
                    <option value="Technology">Technology</option>
                    <option value="Business">Business</option>
                    <option value="Health">Health</option>
                    <option value="Sports">Sports</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Environment">Environment</option>
                </select>
            </div>

            <!-- Body Content -->
            <div class="form-group">
                <label class="form-label" for="articleBody">Article Content</label>
                <textarea class="form-textarea" name="body" id="articleBody" placeholder="Write your article content here..." >{{ old('body') }}</textarea>
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
                    <i class="fas fa-paper-plane"></i> Publish Article
                </button>
            </div>
        </form>
    </div>
@endsection