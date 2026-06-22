@extends('layouts.main')

@section('title', $project->name)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h2 class="mb-1">{{ $project->name }}</h2>
        <p class="text-muted mb-1">{{ $project->description }}</p>
        <div class="d-flex gap-3 small text-muted">
            @if($project->start_date)
                <span><i class="bi bi-calendar"></i> Start: {{ $project->start_date }}</span>
            @endif
            @if($project->deadline)
                <span><i class="bi bi-calendar-x text-danger"></i> Deadline: {{ $project->deadline }}</span>
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <form action="{{ route('projects.destroy', $project) }}" method="POST"
              onsubmit="return confirm('Delete this project and all its issues?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Issues ({{ $issues->total() }})</h5>
    <a href="{{ route('issues.create') }}?project_id={{ $project->id }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Issue
    </a>
</div>

@if($issues->isEmpty())
    <div class="alert alert-info">No issues for this project yet.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm rounded">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Tags</th>
                    <th>Due Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                <tr>
                    <td>
                        <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none fw-semibold">
                            {{ $issue->title }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->status }}">
                            {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->priority }}">{{ ucfirst($issue->priority) }}</span>
                    </td>
                    <td>
                        @foreach($issue->tags as $tag)
                            <span class="tag-badge" style="background-color: {{ $tag->color ?? '#6c757d' }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="small text-muted">{{ $issue->due_date ?? '—' }}</td>
                    <td>
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $issues->links() }}
@endif
@endsection
