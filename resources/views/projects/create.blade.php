@extends('layouts.main')

@section('title', __('New Project'))

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="folder" class="icon"></i>
        {{ __('New Project') }}
    </h1>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-right" class="icon" style="transform:rotate(180deg);"></i>
        {{ __('Back to Projects') }}
    </a>
</div>

<div class="form-page">
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Project Details') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                @include('partials.project-form')
                <hr class="divider">
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus" class="icon"></i>
                        {{ __('Create Project') }}
                    </button>
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
