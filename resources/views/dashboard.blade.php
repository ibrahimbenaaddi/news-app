@extends('layoutdash')

@section('dashboardSection')
@if($articles->isEmpty())
<h1>Ops ,No articles Now At this moment Come Back Later , Thank you</h1>
@else
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
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->category }}</td>
                        <td>{{ $article->created_at->format('F d, Y') }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
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