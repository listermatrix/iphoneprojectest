<?php

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);




Route::get('/',function(Request $request){
    $comment = Comment::query()->create(['user_id'=>1,'body'=>'hello Jesus']);

    \App\Events\CommentWritten::dispatch($comment);

});
