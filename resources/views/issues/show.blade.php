@extends('layouts.main')

@section('title', $issue->title)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-1">{{ $issue->title }}</h3>
                        <a href="{{ route('projects.show', $issue->project) }}" class="text-muted small text-decoration-none">
                            <i class="bi bi-folder"></i> {{ $issue->project->name }}
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                              onsubmit="return confirm('Delete this issue?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <span class="badge badge-{{ $issue->status }} fs-6">
                        {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                    </span>
                    <span class="badge badge-{{ $issue->priority }} fs-6">
                        {{ ucfirst($issue->priority) }}
                    </span>
                    @if($issue->due_date)
                        <span class="badge bg-light text-dark border fs-6">
                            <i class="bi bi-calendar"></i> {{ $issue->due_date }}
                        </span>
                    @endif
                </div>

                @if($issue->description)
                    <hr>
                    <p class="mt-3">{{ $issue->description }}</p>
                @endif
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-chat"></i> Comments</h5></div>
            <div class="card-body">
                <form id="comment-form" class="mb-4">
                    @csrf
                    <div class="mb-2">
                        <input type="text" id="author_name" name="author_name"
                               class="form-control" placeholder="Your name">
                        <div class="invalid-feedback" id="error-author_name"></div>
                    </div>
                    <div class="mb-2">
                        <textarea id="body" name="body" rows="3"
                                  class="form-control" placeholder="Write a comment..."></textarea>
                        <div class="invalid-feedback" id="error-body"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                </form>

                <div id="comments-list"></div>
                <div class="text-center mt-3 d-none" id="load-more-wrap">
                    <button id="load-more" class="btn btn-outline-secondary btn-sm">Load more</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tags & Members Section --}}
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-tags"></i> Tags</h6>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tagModal">
                    <i class="bi bi-plus-lg"></i> Manage
                </button>
            </div>
            <div class="card-body" id="tags-container">
                @foreach($issue->tags as $tag)
                    <span class="tag-badge me-1 mb-1 d-inline-flex align-items-center gap-1"
                          style="background-color: {{ $tag->color ?? '#6c757d' }}"
                          data-tag-id="{{ $tag->id }}">
                        {{ $tag->name }}
                        <i class="bi bi-x tag-detach" role="button" data-tag-id="{{ $tag->id }}"></i>
                    </span>
                @endforeach
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-people"></i> Members</h6>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                    <i class="bi bi-plus-lg"></i> Assign
                </button>
            </div>
            <div class="card-body" id="users-container">
                @foreach($issue->users as $member)
                    <span class="badge bg-secondary me-1 mb-1 d-inline-flex align-items-center gap-1"
                          data-user-id="{{ $member->id }}">
                        <i class="bi bi-person"></i> {{ $member->name }}
                        <i class="bi bi-x user-detach" role="button" data-user-id="{{ $member->id }}"></i>
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- User Modal --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <select id="user-select" class="form-select">
                    <option value="">Select a user...</option>
                    @foreach($allUsers as $user)
                        <option value="{{ $user->id }}" data-name="{{ $user->name }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button id="attach-user-btn" class="btn btn-primary">Assign</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Tag Modal --}}
<div class="modal fade" id="tagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attach Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <select id="tag-select" class="form-select">
                    <option value="">Select a tag...</option>
                    @foreach($allTags as $tag)
                        <option value="{{ $tag->id }}"
                                data-color="{{ $tag->color ?? '#6c757d' }}"
                                data-name="{{ $tag->name }}">
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <div id="tag-error" class="text-danger small mt-1" style="display:none"></div>
            </div>
            <div class="modal-footer">
                <button id="attach-tag-btn" class="btn btn-primary">Attach</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const issueId = {{ $issue->id }};
const attachUrl = "{{ route('issues.tags.attach', $issue) }}";
const detachBase = "{{ url('issues/' . $issue->id . '/tags') }}";
const attachUserUrl = "{{ route('issues.users.attach', $issue) }}";
const detachUserBase = "{{ url('issues/' . $issue->id . '/users') }}";
const commentsUrl = "{{ route('issues.comments.index', $issue) }}";
const storeCommentUrl = "{{ route('issues.comments.store', $issue) }}";
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

let currentPage = 1;
let lastPage = 1;

function renderComment(c) {
    return `<div class="border-bottom py-2 mb-2">
        <strong>${c.author_name}</strong>
        <span class="text-muted small ms-2">${new Date(c.created_at).toLocaleString()}</span>
        <p class="mb-0 mt-1">${c.body}</p>
    </div>`;
}

function loadComments(page = 1) {
    fetch(`${commentsUrl}?page=${page}`)
        .then(r => r.json())
        .then(data => {
            lastPage = data.last_page;
            currentPage = data.current_page;
            if (page === 1) document.getElementById('comments-list').innerHTML = '';
            data.data.forEach(c => {
                document.getElementById('comments-list').insertAdjacentHTML('beforeend', renderComment(c));
            });
            const wrap = document.getElementById('load-more-wrap');
            wrap.classList.toggle('d-none', currentPage >= lastPage);
        });
}

document.getElementById('comment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const author = document.getElementById('author_name');
    const body = document.getElementById('body');
    ['author_name', 'body'].forEach(f => {
        document.getElementById(f).classList.remove('is-invalid');
        document.getElementById('error-' + f).textContent = '';
    });

    fetch(storeCommentUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ author_name: author.value, body: body.value })
    }).then(r => r.json().then(data => ({ status: r.status, data })))
      .then(({ status, data }) => {
        if (status === 422) {
            Object.entries(data.errors).forEach(([field, msgs]) => {
                document.getElementById(field)?.classList.add('is-invalid');
                const errEl = document.getElementById('error-' + field);
                if (errEl) errEl.textContent = msgs[0];
            });
        } else {
            document.getElementById('comments-list').insertAdjacentHTML('afterbegin', renderComment(data));
            author.value = '';
            body.value = '';
        }
    });
});

document.getElementById('load-more').addEventListener('click', () => loadComments(currentPage + 1));

document.getElementById('attach-tag-btn').addEventListener('click', function() {
    const select = document.getElementById('tag-select');
    const tagId = select.value;
    if (!tagId) return;

    fetch(attachUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ tag_id: tagId })
    }).then(r => r.json()).then(data => {
        const tag = data.tag;
        if (document.querySelector(`[data-tag-id="${tag.id}"]`)) return;
        const html = `<span class="tag-badge me-1 mb-1 d-inline-flex align-items-center gap-1"
            style="background-color: ${tag.color ?? '#6c757d'}" data-tag-id="${tag.id}">
            ${tag.name}
            <i class="bi bi-x tag-detach" role="button" data-tag-id="${tag.id}"></i>
        </span>`;
        document.getElementById('tags-container').insertAdjacentHTML('beforeend', html);
        bootstrap.Modal.getInstance(document.getElementById('tagModal')).hide();
    });
});

document.getElementById('tags-container').addEventListener('click', function(e) {
    const btn = e.target.closest('.tag-detach');
    if (!btn) return;
    const tagId = btn.dataset.tagId;

    fetch(`${detachBase}/${tagId}/detach`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(r => r.json()).then(() => {
        document.querySelector(`[data-tag-id="${tagId}"].tag-badge`)?.remove();
    });
});

document.getElementById('attach-user-btn').addEventListener('click', function () {
    const select = document.getElementById('user-select');
    const userId = select.value;
    if (!userId) return;

    fetch(attachUserUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ user_id: userId })
    }).then(r => r.json()).then(data => {
        const user = data.user;
        if (document.querySelector(`[data-user-id="${user.id}"].badge`)) return;
        const html = `<span class="badge bg-secondary me-1 mb-1 d-inline-flex align-items-center gap-1"
            data-user-id="${user.id}">
            <i class="bi bi-person"></i> ${user.name}
            <i class="bi bi-x user-detach" role="button" data-user-id="${user.id}"></i>
        </span>`;
        document.getElementById('users-container').insertAdjacentHTML('beforeend', html);
        bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
    });
});

document.getElementById('users-container').addEventListener('click', function (e) {
    const btn = e.target.closest('.user-detach');
    if (!btn) return;
    const userId = btn.dataset.userId;

    fetch(`${detachUserBase}/${userId}/detach`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(r => r.json()).then(() => {
        document.querySelector(`[data-user-id="${userId}"].badge`)?.remove();
    });
});

loadComments();
</script>
@endsection
