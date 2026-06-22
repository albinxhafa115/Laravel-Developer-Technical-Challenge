@extends('layouts.main')

@section('title', 'Issues')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-bug"></i> Issues</h2>
    <a href="{{ route('issues.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Issue
    </a>
</div>

<form method="GET" class="row g-2 mb-4" id="filter-form">
    <div class="col-md-3">
        <input type="text" name="search" id="search-input" class="form-control form-control-sm"
               placeholder="Search issues..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            @foreach(['open', 'in_progress', 'closed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="priority" class="form-select form-select-sm">
            <option value="">All Priorities</option>
            @foreach(['low', 'medium', 'high'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="tag" class="form-select form-select-sm">
            <option value="">All Tags</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        <a href="{{ route('issues.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

@section('scripts')
<script>
let debounceTimer;
document.getElementById('search-input').addEventListener('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        document.getElementById('filter-form').submit();
    }, 400);
});
</script>
@endsection

@if($issues->isEmpty())
    <div class="alert alert-info">No issues found.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm rounded">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Project</th>
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
                        <a href="{{ route('projects.show', $issue->project) }}" class="text-muted small text-decoration-none">
                            {{ $issue->project->name }}
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
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('issues.edit', $issue) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                                  onsubmit="return confirm('Delete this issue?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $issues->links() }}
@endif
@endsection
