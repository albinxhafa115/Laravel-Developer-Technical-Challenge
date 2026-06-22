@extends('layouts.main')

@section('title', __('Tags'))

@section('content')
<div class="page-header">
    <h1>
        <i data-lucide="tag" class="icon"></i>
        {{ __('Tags') }}
    </h1>
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;">
    {{-- New Tag Form --}}
    <div class="card">
        <div class="card-header">
            <h5>{{ __('New Tag') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tags.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="tag-name">
                        {{ __('Name') }} <span class="req">*</span>
                    </label>
                    <input
                        type="text"
                        id="tag-name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="{{ __('e.g. bug, feature, urgent') }}"
                        required
                    >
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="tag-color">{{ __('Color') }}</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <input
                            type="color"
                            id="tag-color"
                            name="color"
                            class="form-control @error('color') is-invalid @enderror"
                            value="{{ old('color', '#4f46e5') }}"
                            style="width:52px;padding:3px 5px;height:38px;cursor:pointer;flex-shrink:0;"
                        >
                        <span style="font-size:0.8125rem;color:var(--text-muted);">{{ __('Pick a color for this tag') }}</span>
                    </div>
                    @error('color')
                        <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i data-lucide="plus" class="icon"></i>
                    {{ __('Create Tag') }}
                </button>
            </form>
        </div>
    </div>

    {{-- All Tags --}}
    <div class="card">
        <div class="card-header">
            <h5>
                <i data-lucide="tag" class="icon"></i>
                {{ __('All Tags') }}
            </h5>
            <span style="font-size:0.8125rem;color:var(--text-muted);">
                {{ $tags->total() }} {{ $tags->total() == 1 ? __('tag') : __('tags') }}
            </span>
        </div>
        <div class="card-body">
            @if($tags->isEmpty())
                <div class="empty-state" style="padding:40px 16px;">
                    <i data-lucide="tag" class="icon"></i>
                    <h3>{{ __('No tags yet') }}</h3>
                    <p>{{ __('Create your first tag using the form on the left.') }}</p>
                </div>
            @else
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px;">
                    @foreach($tags as $tag)
                        <span class="tag-pill" style="background-color: {{ $tag->color ?? '#6c757d' }};font-size:0.875rem;padding:5px 14px;">
                            {{ $tag->name }}
                            <span style="
                                display:inline-flex;align-items:center;
                                background:rgba(255,255,255,0.25);border-radius:20px;
                                padding:1px 7px;font-size:0.75rem;font-weight:700;margin-left:4px;
                            ">{{ $tag->issues_count }}</span>
                        </span>
                    @endforeach
                </div>
                <div>{{ $tags->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
