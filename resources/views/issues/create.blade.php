@extends('layouts.main')

@section('title', 'New Issue')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">New Issue</h5></div>
            <div class="card-body">
                <form action="{{ route('issues.store') }}" method="POST">
                    @csrf
                    @include('partials.issue-form')
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Create Issue</button>
                        <a href="{{ route('issues.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
