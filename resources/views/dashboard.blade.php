@extends('layoutdash')

@section('dashboardSection')
@if(blank($articles))
<h1>Ops ,No articles Now At this moment Come Back Later , Thank you</h1>
@else
@if (session('error'))
<div class="alert alert-danger">
   {{ session('error') }}
</div>
@endif
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon articles">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <h3>{{$articles->total()}}</h3>
            <p>Total Articles</p>
        </div>
    </div>
</div>

<!-- Recent Articles Section -->
<div class="dashboard-section">
    <div class="section-header">
        <h3 class="section-title">Recent Articles</h3>
        <a href="{{ route('add') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Article</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                <tr>
                    <td>{{ $article->articleID }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->category }}</td>
                    <td>{{ $article->created_at->format('F d, Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('edit', $article ) }}" class="action-btn edit btn btn-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('delete',$article) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="action-btn delete btn btn-danger" onclick="return confirm('are you sure you delete this article')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $articles->links() }}
@endif
@endsection