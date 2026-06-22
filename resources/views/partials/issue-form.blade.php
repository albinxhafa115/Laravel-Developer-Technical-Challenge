<div class="mb-3">
    <label class="form-label">Project <span class="text-danger">*</span></label>
    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
        <option value="">Select project...</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}"
                {{ old('project_id', $issue->project_id ?? request('project_id')) == $project->id ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $issue->title ?? '') }}">
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="4"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $issue->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach(['open', 'in_progress', 'closed'] as $s)
                <option value="{{ $s }}" {{ old('status', $issue->status ?? 'open') === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                </option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Priority <span class="text-danger">*</span></label>
        <select name="priority" class="form-select @error('priority') is-invalid @enderror">
            @foreach(['low', 'medium', 'high'] as $p)
                <option value="{{ $p }}" {{ old('priority', $issue->priority ?? 'medium') === $p ? 'selected' : '' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
        @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
               value="{{ old('due_date', $issue->due_date ?? '') }}">
        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
