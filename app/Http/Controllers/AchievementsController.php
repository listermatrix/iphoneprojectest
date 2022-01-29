<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {

        $achievements = $user->achievement()->orderBy('type')->pluck('name')->toArray(); /** get all achievement names for the user */

        $next_available_achievements = $user->availableAchievements() ;


        //get current badge

        $achievement_count =  $user->achievements->count();
        $current =  Badge::query()->where('achievement_count','<=',$achievement_count)
            ->orderBy('achievement_count','desc')->first();

        $next_badge = Badge::query()->where('achievement_count','>',$achievement_count)
            ->orderBy('achievement_count','asc')->first();



        /**  find the difference between the next badge count and the achievement of achievements
         *
         * **/
        $diff = $next_badge->achievement_count ?? 0 - $achievement_count;

        $remainder  =  $diff <= 0 ? 0 : $diff;

        return response()->json([
            'unlocked_achievements' => $achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current->name ?? '', /** if there isn't a current  badge unlocked then assign null else assign badge name  */
            'next_badge' => $next_badge->name ?? '',  /**  if there isn't any badge to unlock then assign null else assign badge name */
            'remaing_to_unlock_next_badge' => $remainder
        ]);



    }




}
