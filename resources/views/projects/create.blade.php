@extends('layouts.main')

@section('title', 'New Project')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">New Project</h5></div>
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    @include('partials.project-form')
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Create Project</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
