<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $project->name ?? '') }}">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
               value="{{ old('start_date', $project->start_date ?? '') }}">
        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Deadline</label>
        <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
               value="{{ old('deadline', $project->deadline ?? '') }}">
        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
