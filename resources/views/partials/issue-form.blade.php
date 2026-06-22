<div class="form-group">
    <label class="form-label" for="project_id">
        {{ __('Project') }} <span class="req">*</span>
    </label>
    <select
        id="project_id"
        name="project_id"
        class="form-control @error('project_id') is-invalid @enderror"
        required
    >
        <option value="">{{ __('Select a project...') }}</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}"
                {{ old('project_id', $issue->project_id ?? request('project_id')) == $project->id ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    @error('project_id')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="title">
        {{ __('Title') }} <span class="req">*</span>
    </label>
    <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $issue->title ?? '') }}"
        placeholder="{{ __('Brief description of the issue') }}"
        required
    >
    @error('title')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="description">{{ __('Description') }}</label>
    <textarea
        id="description"
        name="description"
        rows="5"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="{{ __('Provide more context, steps to reproduce, expected behavior...') }}"
    >{{ old('description', $issue->description ?? '') }}</textarea>
    @error('description')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="grid grid-3">
    <div class="form-group">
        <label class="form-label" for="status">
            {{ __('Status') }} <span class="req">*</span>
        </label>
        <select
            id="status"
            name="status"
            class="form-control @error('status') is-invalid @enderror"
        >
            @foreach(['open', 'in_progress', 'closed'] as $s)
                <option value="{{ $s }}"
                    {{ old('status', $issue->status ?? 'open') === $s ? 'selected' : '' }}>
                    {{ __(ucfirst(str_replace('_', ' ', $s))) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="priority">
            {{ __('Priority') }} <span class="req">*</span>
        </label>
        <select
            id="priority"
            name="priority"
            class="form-control @error('priority') is-invalid @enderror"
        >
            @foreach(['low', 'medium', 'high'] as $p)
                <option value="{{ $p }}"
                    {{ old('priority', $issue->priority ?? 'medium') === $p ? 'selected' : '' }}>
                    {{ __(ucfirst($p)) }}
                </option>
            @endforeach
        </select>
        @error('priority')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="due_date">{{ __('Due Date') }}</label>
        <input
            type="date"
            id="due_date"
            name="due_date"
            class="form-control @error('due_date') is-invalid @enderror"
            value="{{ old('due_date', $issue->due_date ?? '') }}"
        >
        @error('due_date')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>
