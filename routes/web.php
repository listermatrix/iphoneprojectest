<?php

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);



Route::post('/users/comment/add', [AchievementsController::class, 'postComment']);
Route::post('/users/watched-lesson/add', [AchievementsController::class, 'postLessonWatched']);




