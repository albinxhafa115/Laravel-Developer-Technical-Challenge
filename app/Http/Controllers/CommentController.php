<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issue;

class CommentController extends Controller
{
    public function index(Issue $issue)
    {
        $comments = $issue->comments()->latest()->paginate(5);

        return response()->json($comments);
    }

    public function store(StoreCommentRequest $request, Issue $issue)
    {
        $comment = $issue->comments()->create($request->validated());

        return response()->json($comment, 201);
    }
}
