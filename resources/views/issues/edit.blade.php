@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Edit Issue</h5></div>
            <div class="card-body">
                <form action="{{ route('issues.update', $issue) }}" method="POST">
                    @csrf @method('PUT')
                    @include('partials.issue-form')
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
