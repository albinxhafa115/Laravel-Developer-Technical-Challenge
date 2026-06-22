@extends('layouts.main')

@section('title', 'Projects')

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="folder" class="icon"></i>
        Projects
    </h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i data-lucide="plus" class="icon"></i>
        New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="empty-state">
        <i data-lucide="folder" class="icon"></i>
        <h3>No projects yet</h3>
        <p>Create your first project to start tracking issues.</p>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="icon"></i>
            New Project
        </a>
    </div>
@else
    <div class="project-grid">
        @foreach($projects as $project)
        <div class="project-card">
            <div class="project-card-body">
                <div class="project-card-title">
                    <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                </div>
                @if($project->description)
                    <p class="project-card-desc">{{ Str::limit($project->description, 110) }}</p>
                @else
                    <p class="project-card-desc" style="font-style:italic;">No description provided.</p>
                @endif
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    @if($project->start_date)
                        <span class="date-pill">
                            <i data-lucide="calendar" class="icon"></i>
                            {{ $project->start_date }}
                        </span>
                    @endif
                    @if($project->deadline)
                        <span class="date-pill" style="color:#dc2626;border-color:#fecaca;background:#fff5f5;">
                            <i data-lucide="calendar" class="icon" style="stroke:#dc2626;"></i>
                            {{ $project->deadline }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="project-card-footer">
                <span class="issues-count">
                    <i data-lucide="bug" style="width:13px;height:13px;stroke:#94a3b8;margin-right:4px;vertical-align:middle;"></i>
                    {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                </span>
                <div style="display:flex;gap:6px;">
                    @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary btn-sm">
                        <i data-lucide="pencil" class="icon"></i>
                    </a>
                    @endcan
                    @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                          onsubmit="return confirm('Delete this project and all its issues?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i data-lucide="trash-2" class="icon"></i>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>
@endif
@endsection
