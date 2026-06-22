@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Edit Project</h5></div>
            <div class="card-body">
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf @method('PUT')
                    @include('partials.project-form')
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
