<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }


    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }


    public function achievement()
    {
        return $this->belongsToMany(Achievement::class,'user_achievements');
    }


    public function availableAchievements()
    {
        $next_available_achievements = [];
        //get the latest lesson achievement for user
        $lesson = $this->achievements()->whereHas('achievement',function($achievement){
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
        $comment = $this->achievements()->whereHas('achievement',function($achievement){
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



        return $next_available_achievements;
    }
}
