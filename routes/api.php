<?php

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::any('comment',function (){

    $comment =  Comment::query()->create(['user_id'=>1,'body'=>'slice of onions']);
    CommentWritten::dispatch($comment);

});



Route::any('lesson',function (){

    $user = User::query()->find(1);

    $LessonWatched = Lesson::query()->first();

    $watched = $user->watched->pluck('id')->toArray();


//    if(!in_array($LessonWatched->id,$watched)) {
         LessonWatched::dispatch($LessonWatched,$user);
//    }


});
