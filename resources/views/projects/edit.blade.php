@extends('layouts.main')

@section('title', 'Edit Project')

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="pencil" class="icon"></i>
        Edit Project
    </h1>
    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
        <i data-lucide="arrow-right" class="icon" style="transform:rotate(180deg);"></i>
        Back to Project
    </a>
</div>

<div class="form-page">
    <div class="card">
        <div class="card-header">
            <h5>{{ $project->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf @method('PUT')
                @include('partials.project-form')
                <hr class="divider">
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
