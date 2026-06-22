@extends('layouts.main')

@section('title', $issue->title)

@section('content')
<div class="layout-2col">
    {{-- Left Column: Issue + Comments --}}
    <div>
        {{-- Issue Card --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
                    <div style="flex:1;min-width:0;">
                        <h2 style="font-size:1.25rem;font-weight:700;letter-spacing:-0.01em;color:var(--text);margin-bottom:6px;">
                            {{ $issue->title }}
                        </h2>
                        <a href="{{ route('projects.show', $issue->project) }}"
                           style="font-size:0.875rem;color:var(--text-muted);display:inline-flex;align-items:center;gap:5px;">
                            <i data-lucide="folder" style="width:13px;height:13px;"></i>
                            {{ $issue->project->name }}
                        </a>
                    </div>
                    <div style="display:flex;gap:6px;flex-shrink:0;">
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-secondary btn-sm">
                            <i data-lucide="pencil" class="icon"></i>
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                              onsubmit="return confirm('{{ __('Delete this issue?') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i data-lucide="trash-2" class="icon"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px;">
                    <span class="badge badge-{{ $issue->status }}">
                        {{ __(str_replace('_', ' ', ucfirst($issue->status))) }}
                    </span>
                    <span class="badge badge-{{ $issue->priority }}">
                        {{ __(ucfirst($issue->priority)) }}
                    </span>
                    @if($issue->due_date)
                        <span class="date-pill">
                            <i data-lucide="calendar" class="icon"></i>
                            {{ $issue->due_date }}
                        </span>
                    @endif
                </div>

                @if($issue->description)
                    <hr class="divider">
                    <p style="font-size:0.9rem;color:var(--text);line-height:1.7;white-space:pre-wrap;">{{ $issue->description }}</p>
                @endif
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="card">
            <div class="card-header">
                <h5>
                    <i data-lucide="message-square" class="icon"></i>
                    {{ __('Comments') }}
                </h5>
            </div>
            <div class="card-body">
                <form id="comment-form" style="margin-bottom:24px;">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="author_name">{{ __('Your name') }}</label>
                        <input
                            type="text"
                            id="author_name"
                            name="author_name"
                            class="form-control"
                            placeholder="{{ __('Enter your name') }}"
                        >
                        <span class="invalid-feedback" id="error-author_name"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="body">{{ __('Comment') }}</label>
                        <textarea
                            id="body"
                            name="body"
                            rows="3"
                            class="form-control"
                            placeholder="{{ __('Write a comment...') }}"
                        ></textarea>
                        <span class="invalid-feedback" id="error-body"></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="message-square" class="icon"></i>
                        {{ __('Post Comment') }}
                    </button>
                </form>

                <div id="comments-list"></div>

                <div class="text-center mt-3 d-none" id="load-more-wrap">
                    <button id="load-more" class="btn btn-secondary btn-sm">{{ __('Load more comments') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Tags + Members --}}
    <div>
        {{-- Tags Card --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <h6>
                    <i data-lucide="tag" class="icon"></i>
                    {{ __('Tags') }}
                </h6>
                <button
                    class="btn btn-secondary btn-sm"
                    onclick="document.getElementById('tagModal').classList.add('active')"
                >
                    <i data-lucide="plus" class="icon"></i>
                    {{ __('Manage') }}
                </button>
            </div>
            <div class="card-body" id="tags-container">
                @forelse($issue->tags as $tag)
                    <span class="tag-pill me-1 mb-2"
                          style="background-color: {{ $tag->color ?? '#6c757d' }}"
                          data-tag-id="{{ $tag->id }}">
                        {{ $tag->name }}
                        <span class="tag-x tag-detach" role="button" data-tag-id="{{ $tag->id }}">
                            <i data-lucide="x" class="icon"></i>
                        </span>
                    </span>
                @empty
                    <p style="color:var(--text-muted);font-size:0.8125rem;margin:0;">{{ __('No tags assigned.') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Members Card --}}
        <div class="card">
            <div class="card-header">
                <h6>
                    <i data-lucide="user" class="icon"></i>
                    {{ __('Members') }}
                </h6>
                <button
                    class="btn btn-secondary btn-sm"
                    onclick="document.getElementById('userModal').classList.add('active')"
                >
                    <i data-lucide="plus" class="icon"></i>
                    {{ __('Assign') }}
                </button>
            </div>
            <div class="card-body" id="users-container">
                @forelse($issue->users as $member)
                    <span class="user-pill me-1 mb-2"
                          data-user-id="{{ $member->id }}">
                        <i data-lucide="user" class="icon"></i>
                        {{ $member->name }}
                        <span class="user-detach" role="button" data-user-id="{{ $member->id }}">
                            <i data-lucide="x" class="icon"></i>
                        </span>
                    </span>
                @empty
                    <p style="color:var(--text-muted);font-size:0.8125rem;margin:0;">{{ __('No members assigned.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Tag Modal --}}
<div class="modal-overlay" id="tagModal">
    <div class="modal-box">
        <div class="modal-header">
            <h5>{{ __('Attach Tag') }}</h5>
            <button class="modal-close" onclick="document.getElementById('tagModal').classList.remove('active')">
                <i data-lucide="x" class="icon"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">{{ __('Select a tag') }}</label>
                <select id="tag-select" class="form-control">
                    <option value="">{{ __('Choose tag...') }}</option>
                    @foreach($allTags as $tag)
                        <option value="{{ $tag->id }}"
                                data-color="{{ $tag->color ?? '#6c757d' }}"
                                data-name="{{ $tag->name }}">
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <span id="tag-error" class="invalid-feedback" style="display:none;"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button id="attach-tag-btn" class="btn btn-primary btn-sm">
                <i data-lucide="plus" class="icon"></i>
                {{ __('Attach Tag') }}
            </button>
            <button class="btn btn-secondary btn-sm" onclick="document.getElementById('tagModal').classList.remove('active')">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>
</div>

{{-- User Modal --}}
<div class="modal-overlay" id="userModal">
    <div class="modal-box">
        <div class="modal-header">
            <h5>{{ __('Assign Member') }}</h5>
            <button class="modal-close" onclick="document.getElementById('userModal').classList.remove('active')">
                <i data-lucide="x" class="icon"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">{{ __('Select a user') }}</label>
                <select id="user-select" class="form-control">
                    <option value="">{{ __('Choose user...') }}</option>
                    @foreach($allUsers as $user)
                        <option value="{{ $user->id }}" data-name="{{ $user->name }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button id="attach-user-btn" class="btn btn-primary btn-sm">
                <i data-lucide="plus" class="icon"></i>
                {{ __('Assign') }}
            </button>
            <button class="btn btn-secondary btn-sm" onclick="document.getElementById('userModal').classList.remove('active')">
                {{ __('Cancel') }}
            </button>
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
const msgSelectTag = "{{ __('Please select a tag.') }}";

let currentPage = 1;
let lastPage = 1;

document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) overlay.classList.remove('active');
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function(m) {
            m.classList.remove('active');
        });
    }
});

function renderComment(c) {
    return `<div class="comment-item">
        <div>
            <span class="comment-author">${c.author_name}</span>
            <span class="comment-time">${new Date(c.created_at).toLocaleString()}</span>
        </div>
        <p class="comment-body">${c.body}</p>
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
        const el = document.getElementById(f);
        el.classList.remove('is-invalid');
        const errEl = document.getElementById('error-' + f);
        if (errEl) errEl.textContent = '';
    });

    fetch(storeCommentUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ author_name: author.value, body: body.value })
    })
    .then(r => r.json().then(data => ({ status: r.status, data })))
    .then(({ status, data }) => {
        if (status === 422) {
            Object.entries(data.errors).forEach(([field, msgs]) => {
                const el = document.getElementById(field);
                if (el) el.classList.add('is-invalid');
                const errEl = document.getElementById('error-' + field);
                if (errEl) { errEl.textContent = msgs[0]; errEl.style.display = 'block'; }
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
    const errEl = document.getElementById('tag-error');
    errEl.style.display = 'none';

    if (!tagId) {
        errEl.textContent = msgSelectTag;
        errEl.style.display = 'block';
        return;
    }

    fetch(attachUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ tag_id: tagId })
    })
    .then(r => r.json())
    .then(data => {
        const tag = data.tag;
        if (document.querySelector(`[data-tag-id="${tag.id}"].tag-pill`)) {
            document.getElementById('tagModal').classList.remove('active');
            return;
        }
        const html = `<span class="tag-pill me-1 mb-2"
            style="background-color: ${tag.color ?? '#6c757d'}" data-tag-id="${tag.id}">
            ${tag.name}
            <span class="tag-x tag-detach" role="button" data-tag-id="${tag.id}">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </span>
        </span>`;
        const placeholder = document.querySelector('#tags-container p');
        if (placeholder) placeholder.remove();
        document.getElementById('tags-container').insertAdjacentHTML('beforeend', html);
        document.getElementById('tagModal').classList.remove('active');
        select.value = '';
    });
});

document.getElementById('tags-container').addEventListener('click', function(e) {
    const btn = e.target.closest('.tag-detach');
    if (!btn) return;
    const tagId = btn.dataset.tagId;
    fetch(`${detachBase}/${tagId}/detach`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(() => {
        const pill = document.querySelector(`.tag-pill[data-tag-id="${tagId}"]`);
        if (pill) pill.remove();
    });
});

document.getElementById('attach-user-btn').addEventListener('click', function() {
    const select = document.getElementById('user-select');
    const userId = select.value;
    if (!userId) return;

    fetch(attachUserUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(r => r.json())
    .then(data => {
        const user = data.user;
        if (document.querySelector(`.user-pill[data-user-id="${user.id}"]`)) {
            document.getElementById('userModal').classList.remove('active');
            return;
        }
        const html = `<span class="user-pill me-1 mb-2" data-user-id="${user.id}">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            ${user.name}
            <span class="user-detach" role="button" data-user-id="${user.id}">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </span>
        </span>`;
        const placeholder = document.querySelector('#users-container p');
        if (placeholder) placeholder.remove();
        document.getElementById('users-container').insertAdjacentHTML('beforeend', html);
        document.getElementById('userModal').classList.remove('active');
        select.value = '';
    });
});

document.getElementById('users-container').addEventListener('click', function(e) {
    const btn = e.target.closest('.user-detach');
    if (!btn) return;
    const userId = btn.dataset.userId;
    fetch(`${detachUserBase}/${userId}/detach`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(() => {
        const pill = document.querySelector(`.user-pill[data-user-id="${userId}"]`);
        if (pill) pill.remove();
    });
});

loadComments();
</script>
@endsection
