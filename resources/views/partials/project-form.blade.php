<div class="form-group">
    <label class="form-label" for="name">
        {{ __('Project Name') }} <span class="req">*</span>
    </label>
    <input
        type="text"
        id="name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $project->name ?? '') }}"
        placeholder="{{ __('e.g. Website Redesign') }}"
        required
    >
    @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="description">{{ __('Description') }}</label>
    <textarea
        id="description"
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="{{ __('Briefly describe this project...') }}"
    >{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="grid grid-2">
    <div class="form-group">
        <label class="form-label" for="start_date">{{ __('Start Date') }}</label>
        <input
            type="date"
            id="start_date"
            name="start_date"
            class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date', $project->start_date ?? '') }}"
        >
        @error('start_date')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="deadline">{{ __('Deadline') }}</label>
        <input
            type="date"
            id="deadline"
            name="deadline"
            class="form-control @error('deadline') is-invalid @enderror"
            value="{{ old('deadline', $project->deadline ?? '') }}"
        >
        @error('deadline')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>
