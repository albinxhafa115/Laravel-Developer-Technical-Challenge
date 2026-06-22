@extends('layouts.main')

@section('title', __('Issues'))

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="bug" class="icon"></i>
        {{ __('Issues') }}
    </h1>
    <a href="{{ route('issues.create') }}" class="btn btn-primary">
        <i data-lucide="plus" class="icon"></i>
        {{ __('New Issue') }}
    </a>
</div>

<form method="GET" id="filter-form">
    <div class="filter-row">
        <div class="search-wrapper">
            <i data-lucide="search" class="icon"></i>
            <input
                type="text"
                name="search"
                id="search-input"
                class="form-control"
                placeholder="{{ __('Search issues...') }}"
                value="{{ request('search') }}"
            >
        </div>

        <select name="status" class="form-control" style="width:160px;flex-shrink:0;">
            <option value="">{{ __('All Statuses') }}</option>
            @foreach(['open', 'in_progress', 'closed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ __(ucfirst(str_replace('_', ' ', $s))) }}
                </option>
            @endforeach
        </select>

        <select name="priority" class="form-control" style="width:150px;flex-shrink:0;">
            <option value="">{{ __('All Priorities') }}</option>
            @foreach(['low', 'medium', 'high'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                    {{ __(ucfirst($p)) }}
                </option>
            @endforeach
        </select>

        <select name="tag" class="form-control" style="width:150px;flex-shrink:0;">
            <option value="">{{ __('All Tags') }}</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

        <div style="display:flex;gap:6px;flex-shrink:0;">
            <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
            <a href="{{ route('issues.index') }}" class="btn btn-secondary btn-sm">{{ __('Reset') }}</a>
        </div>
    </div>
</form>

@if($issues->isEmpty())
    <div class="empty-state">
        <i data-lucide="bug" class="icon"></i>
        <h3>{{ __('No issues found') }}</h3>
        <p>{{ __('Try adjusting your filters or create a new issue.') }}</p>
        <a href="{{ route('issues.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="icon"></i>
            {{ __('New Issue') }}
        </a>
    </div>
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Project') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Priority') }}</th>
                    <th>{{ __('Tags') }}</th>
                    <th>{{ __('Due Date') }}</th>
                    <th style="width:80px;"></th>
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
                        <a href="{{ route('projects.show', $issue->project) }}"
                           style="color:var(--text-muted);font-size:0.8125rem;">
                            {{ $issue->project->name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->status }}">
                            {{ __(str_replace('_', ' ', ucfirst($issue->status))) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->priority }}">
                            {{ __(ucfirst($issue->priority)) }}
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
                    <td style="color:var(--text-muted);font-size:0.8125rem;white-space:nowrap;">
                        {{ $issue->due_date ?? '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('issues.edit', $issue) }}" class="btn btn-ghost btn-sm">
                                <i data-lucide="pencil" class="icon"></i>
                            </a>
                            <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                                  onsubmit="return confirm('{{ __('Delete this issue?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i data-lucide="trash-2" class="icon"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $issues->links() }}</div>
@endif
@endsection

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
