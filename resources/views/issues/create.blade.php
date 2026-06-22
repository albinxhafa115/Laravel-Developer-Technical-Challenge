@extends('layouts.main')

@section('title', 'New Issue')

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="bug" class="icon"></i>
        New Issue
    </h1>
    <a href="{{ route('issues.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-right" class="icon" style="transform:rotate(180deg);"></i>
        Back to Issues
    </a>
</div>

<div class="form-page" style="max-width:760px;">
    <div class="card">
        <div class="card-header">
            <h5>Issue Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('issues.store') }}" method="POST">
                @csrf
                @include('partials.issue-form')
                <hr class="divider">
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus" class="icon"></i>
                        Create Issue
                    </button>
                    <a href="{{ route('issues.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
