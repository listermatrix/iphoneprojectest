<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_add_comment_unlock_first_achievement()
    {
        $user = User::factory()->create();
        $data = ['user_id'=>$user->id,'body'=>$this->faker->text];

        $response = $this->post('/users/comment/add',$data);
        $response->assertStatus(200);
    }

    public function test_add_empty_comment_body()
    {
        $user = User::first();
        $data = ['user_id'=>$user->id];

        $response = $this->post('/users/comment/add',$data);
        $response->assertStatus(302);
    }

    public function test_post_empty_user_id()
    {
        $data = ['body'=>$this->faker->text];
        $response = $this->post('/users/comment/add',$data);
        $response->assertStatus(302);
    }

    public function test_post_empty_parameters()
    {

        $data = [];
        $response = $this->post('/users/comment/add',$data);
        $response->assertStatus(302);
    }

    public function test_unlock_up_to_ten_comment_achievement()
    {
        $user = User::first();
        for ($i=0; $i < 10; $i++)
        {
            $data = ['user_id'=>$user->id,'body'=>$this->faker->text];
            $response = $this->post('/users/comment/add',$data);

        }

        $response->assertStatus(200)->assertSee(true);

    }

    public function test_unlock_all_comment_achievement()
    {
        $user = User::first();
        for ($i=0; $i < 20; $i++)
        {
            $data = ['user_id'=>$user->id,'body'=>$this->faker->text];
            $response = $this->post('/users/comment/add',$data);

        }

        $response->assertStatus(200)->assertSee(true);

    }

    public function test_add_lesson_watched_first_achievement()
    {
        $user = User::query()->first();
        $lesson = Lesson::query()->first();
        $data = ['user_id'=>$user->id,'lesson_id'=>$lesson->id];
        $response = $this->post('/users/watched-lesson/add',$data);
        $response->assertStatus(200)->assertSee(true);
    }

    public function test_add_empty_user_id()
    {
        $lesson = Lesson::query()->first();
        $data = ['lesson_id'=>$lesson->id];
        $response = $this->post('/users/watched-lesson/add',$data);
        $response->assertStatus(302);
    }

    public function test_unlock_up_to_ten_lesson_achievement()
    {

        $user = User::first();
        $response = null;
        Lesson::query()->limit(10)->each(function ($lesson) use ($user,&$response){
            $data = ['user_id'=>$user->id,'lesson_id'=>$lesson->id];
            $response = $this->post('/users/watched-lesson/add',$data);
        });


        $response->assertStatus(200)->assertSee(true);

    }

    public function test_unlock_all_lesson_achievement()
    {

        $user = User::first();
        $response = null;
        Lesson::query()->each(function ($lesson) use ($user,&$response){
            $data = ['user_id'=>$user->id,'lesson_id'=>$lesson->id];
            $response = $this->post('/users/watched-lesson/add',$data);
        });

        $response->assertStatus(200)->assertSee(true);

    }


}
