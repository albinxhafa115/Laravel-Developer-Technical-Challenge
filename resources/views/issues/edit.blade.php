@extends('layouts.main')

@section('title', 'Edit Issue')

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="pencil" class="icon"></i>
        Edit Issue
    </h1>
    <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary">
        <i data-lucide="arrow-right" class="icon" style="transform:rotate(180deg);"></i>
        Back to Issue
    </a>
</div>

<div class="form-page" style="max-width:760px;">
    <div class="card">
        <div class="card-header">
            <h5>{{ $issue->title }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('issues.update', $issue) }}" method="POST">
                @csrf @method('PUT')
                @include('partials.issue-form')
                <hr class="divider">
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
