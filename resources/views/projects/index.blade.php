@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-folder"></i> Projects</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="alert alert-info">No projects yet. Create your first one!</div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($projects as $project)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none">
                            {{ $project->name }}
                        </a>
                    </h5>
                    <p class="card-text text-muted small">{{ Str::limit($project->description, 100) }}</p>
                    <div class="d-flex gap-3 small text-muted mt-2">
                        @if($project->start_date)
                            <span><i class="bi bi-calendar"></i> {{ $project->start_date }}</span>
                        @endif
                        @if($project->deadline)
                            <span><i class="bi bi-calendar-x"></i> {{ $project->deadline }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span class="badge bg-secondary">{{ $project->issues_count }} issues</span>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST"
                              onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>
@endif
@endsection
