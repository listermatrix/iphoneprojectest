<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementsController extends Controller
{
    public function index(User $user)
    {

        /** get all achievement names for the user */
        $achievements = $user->achievement()->orderBy('type')->pluck('name')->toArray();

        $next_available_achievements = $user->availableAchievements() ;

        /**  all achievement counts */
        $achievement_count =  $user->achievements->count();

        /**  get current badge */
        $current =  Badge::query()->where('achievement_count','<=',$achievement_count)
            ->orderBy('achievement_count','desc')->first();

        /**  get current badge */
        $next_badge = Badge::query()->where('achievement_count','>',$achievement_count)
            ->orderBy('achievement_count','asc')->first();

        /**  find the difference between the next badge count and the achievement of achievements **/
        $diff = $next_badge && $next_badge->achievement_count ? $next_badge->achievement_count - $achievement_count :0;

        $remainder  =  $diff <= 0 ? 0 : $diff;  //get rid of negative and rather show 0

        return response()->json([
            'unlocked_achievements' => $achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current->name ?? '', /** if there isn't a current  badge unlocked then assign null else assign badge name  */
            'next_badge' => $next_badge->name ?? '',  /**  if there isn't any badge to unlock then assign null else assign badge name */
            'remaing_to_unlock_next_badge' => $remainder
        ]);



    }

    public function postComment(Request $request)
    {
        $request->validate(
            [   'body'    => 'required|string|',
                'user_id' => 'required|integer|exists:users,id',
            ]);

        $data = $request->all();
        $comment =  Comment::query()->create($data);

        CommentWritten::dispatch($comment);
        return true;

    }

    public function postLessonWatched(Request $request)
    {
        $request->validate(
            [
                'user_id'   => 'required|integer|exists:users,id',
                'lesson_id' => 'required|integer|exists:lessons,id',
            ]);

        $data   = $request->all();
        $user   = User::query()->find($data['user_id']);
        $lesson = Lesson::query()->find($data['lesson_id']);

        DB::table('lesson_user')->updateOrInsert(
            ['user_id'=>$user->id,'lesson_id'=>$lesson->id],
            ['watched'=>true]);

        LessonWatched::dispatch($lesson,$user);

        return true;
    }




}
