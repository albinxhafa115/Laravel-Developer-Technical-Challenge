@extends('layouts.main')

@section('title', $project->name)

@section('content')
{{-- Project Header --}}
<div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);padding:24px;margin-bottom:24px;">
    <div class="page-header" style="margin-bottom:12px;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:700;letter-spacing:-0.02em;color:var(--text);margin-bottom:6px;">
                {{ $project->name }}
            </h1>
            @if($project->description)
                <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:10px;">{{ $project->description }}</p>
            @endif
            <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                @if($project->start_date)
                    <span class="date-pill">
                        <i data-lucide="calendar" class="icon"></i>
                        Start: {{ $project->start_date }}
                    </span>
                @endif
                @if($project->deadline)
                    <span class="date-pill" style="color:#dc2626;border-color:#fecaca;background:#fff5f5;">
                        <i data-lucide="calendar" class="icon" style="stroke:#dc2626;"></i>
                        Deadline: {{ $project->deadline }}
                    </span>
                @endif
                @if($project->user)
                    <span class="date-pill">
                        <i data-lucide="user" class="icon"></i>
                        {{ $project->user->name }}
                    </span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-shrink:0;">
            @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary btn-sm">
                <i data-lucide="pencil" class="icon"></i>
                Edit
            </a>
            @endcan
            @can('delete', $project)
            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                  onsubmit="return confirm('Delete this project and all its issues?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i data-lucide="trash-2" class="icon"></i>
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>

{{-- Issues --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
    <div>
        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);">
            Issues
            <span style="background:var(--bg);border:1px solid var(--border);border-radius:20px;font-size:0.8rem;padding:2px 10px;margin-left:6px;color:var(--text-muted);">{{ $issues->total() }}</span>
        </h2>
    </div>
    <a href="{{ route('issues.create') }}?project_id={{ $project->id }}" class="btn btn-primary btn-sm">
        <i data-lucide="plus" class="icon"></i>
        Add Issue
    </a>
</div>

@if($issues->isEmpty())
    <div class="empty-state">
        <i data-lucide="bug" class="icon"></i>
        <h3>No issues yet</h3>
        <p>Add your first issue to this project.</p>
        <a href="{{ route('issues.create') }}?project_id={{ $project->id }}" class="btn btn-primary">
            <i data-lucide="plus" class="icon"></i>
            Add Issue
        </a>
    </div>
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Tags</th>
                    <th>Due Date</th>
                    <th style="width:60px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                <tr>
                    <td>
                        <a href="{{ route('issues.show', $issue) }}" class="issue-link">
                            {{ $issue->title }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->status }}">
                            {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->priority }}">
                            {{ ucfirst($issue->priority) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            @foreach($issue->tags as $tag)
                                <span class="tag-pill" style="background-color: {{ $tag->color ?? '#6c757d' }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td style="color:var(--text-muted);font-size:0.8125rem;">
                        {{ $issue->due_date ?? '—' }}
                    </td>
                    <td>
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-ghost btn-sm">
                            <i data-lucide="pencil" class="icon"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $issues->links() }}</div>
@endif
@endsection
