<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;

class IssueController extends Controller
{
    public function index()
    {
        $query = Issue::with(['project', 'tags']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('priority')) {
            $query->where('priority', request('priority'));
        }

        if (request('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', request('tag')));
        }

        $issues = $query->latest()->paginate(15)->withQueryString();
        $tags = Tag::orderBy('name')->get();

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();

        return view('issues.create', compact('projects'));
    }

    public function store(StoreIssueRequest $request)
    {
        $issue = Issue::create($request->validated());

        return redirect()->route('issues.show', $issue)->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags']);
        $allTags = Tag::orderBy('name')->get();

        return view('issues.show', compact('issue', 'allTags'));
    }

    public function edit(Issue $issue)
    {
        $projects = Project::orderBy('name')->get();

        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());

        return redirect()->route('issues.show', $issue)->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        $project = $issue->project;
        $issue->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Issue deleted successfully.');
    }
}
