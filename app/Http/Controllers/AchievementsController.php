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



        $achievements = $user->achievement->pluck('name')->toArray();

        $next_available_achievements =  [];

        //get the latest comment achievement for user
        $lesson = $user->achievements()->whereHas('achievement',function($achievement){
            $achievement->where('type', 'lesson');
        })->latest()->first();

        if($lesson)
        {
            $next =  Achievement::query()
                ->where([['type', 'lesson'],['count','>',$lesson->achievement->count]])
                ->orderBy('count','asc')
                ->first();

            if($next) {

                $next_available_achievements[] = $next->name;

            }
        }

        //get the latest comment achievement for user
        $comment = $user->achievements()->whereHas('achievement',function($achievement){
            $achievement->where('type', 'comment');
        })->latest()->first();

        if($comment)
        {
           $next =  Achievement::query()->where([['type', 'comment'],['count','>',$comment->achievement->count]])
               ->orderBy('count','asc')->first();

           if($next) {
               $next_available_achievements[] =$next->name;
           }

        }
        //get current badge

        $badge_count =  $user->achievements->count();
        $current =  Badge::query()->where('achievement_count','<=',$badge_count)
            ->orderBy('achievement_count','desc')->first();

//        dd($current);

        return response()->json([
            'unlocked_achievements' => $achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current->name ?? '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);



    }
}
