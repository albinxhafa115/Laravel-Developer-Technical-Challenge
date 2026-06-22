@extends('layouts.main')

@section('title', 'Tags')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><h5 class="mb-0">New Tag</h5></div>
            <div class="card-body">
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="color"
                                   class="form-control form-control-color @error('color') is-invalid @enderror"
                                   value="{{ old('color', '#6c757d') }}">
                            <small class="text-muted">Pick a color for the tag</small>
                        </div>
                        @error('color')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Tag</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-tags"></i> All Tags</h5></div>
            <div class="card-body">
                @if($tags->isEmpty())
                    <p class="text-muted">No tags yet.</p>
                @else
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($tags as $tag)
                            <span class="tag-badge d-inline-flex align-items-center gap-1"
                                  style="background-color: {{ $tag->color ?? '#6c757d' }}">
                                {{ $tag->name }}
                                <span class="badge bg-white text-dark ms-1">{{ $tag->issues_count }}</span>
                            </span>
                        @endforeach
                    </div>
                    {{ $tags->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
