<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\LessonWatched;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        //assume the event has been fired.
        $user = $event->user;
        $watch_count  = $user->watched->count();




        $achievementQuery = Achievement::query()->where('type','lesson');

        if($watch_count > 0) // if lesson watched is not 0
        {

            //fetch achievement based on count of watched videos
            $achievement = $achievementQuery->where('count',$watch_count)->first();


            if($achievement)
            {
                //create user achievement record (First or Create to prevent duplicates)
                $user->achievements()->firstOrCreate(['achievement_id' => $achievement->id]);
                AchievementUnlockedEvent::dispatch($achievement->name,$user);
            }
        }

    }
}
