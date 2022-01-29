<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Events\BadgeUnlockedEvent;
use App\Models\Badge;
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


          $badge = Badge::query()->where('achievement_count', $achievementCount)->first();

          //if a badge is found, then trigger Badge Unlocked Event
          if($badge)
          {
              BadgeUnlockedEvent::dispatch($badge->name,$user);
          }

          info("achievement count of  $achievementCount");


    }
}
