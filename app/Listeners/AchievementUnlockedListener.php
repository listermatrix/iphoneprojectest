<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AchievementUnlockedListener
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
     * @param  \App\Events\AchievementUnlockedEvent  $event
     * @return void
     */
    public function handle(AchievementUnlockedEvent $event)
    {
//        info("achievement unlocked name  $event->name");
//        info("achievement unlocked by user  {$event->user->name}");


          $user  =  $event->user;
          $achievementCount = $user->achievements->count();


//          $badges = Badge

        info("achievement count of  $achievementCount");


    }
}
