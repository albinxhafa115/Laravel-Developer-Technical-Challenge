<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('projects.index')
        : view('home');
})->name('home');

Route::resource('projects', ProjectController::class);
Route::resource('issues', IssueController::class);
Route::resource('tags', TagController::class)->only(['index', 'store']);

Route::post('issues/{issue}/tags/attach', [TagController::class, 'attach'])->name('issues.tags.attach');
Route::delete('issues/{issue}/tags/{tag}/detach', [TagController::class, 'detach'])->name('issues.tags.detach');

Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

Route::post('issues/{issue}/users/attach', [IssueController::class, 'attachUser'])->name('issues.users.attach');
Route::delete('issues/{issue}/users/{user}/detach', [IssueController::class, 'detachUser'])->name('issues.users.detach');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
