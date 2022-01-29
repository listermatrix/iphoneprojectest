<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentListener
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
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {

        //assume the event has been fired.
        $user = $event->comment->user;
        $comments  = $user->comments->count();

        $achievementQuery = Achievement::query()->where('type','comment');

        if($comments > 0) // if comment written is not 0
        {

            //fetch achievement based on count of comments
            $achievement = $achievementQuery->where('count',$comments)->first();

            if($achievement)
            {
                //create user achievement record
               $user->achievements()->create(['achievement_id' => $achievement->id]);
               AchievementUnlockedEvent::dispatch($achievement->name,$user);
            }
        }



//        info("total user comments is/are  $comments");

    }
}
