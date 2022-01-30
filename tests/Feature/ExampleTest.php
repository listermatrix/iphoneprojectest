<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::query()->first();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            return
                $json->whereType('unlocked_achievements', 'array')
                     ->whereType('next_available_achievements', 'array')
                     ->whereType('current_badge', 'string')
                     ->whereType('next_badge', 'string')
                     ->whereType('remaing_to_unlock_next_badge', 'integer');

        }
        );
    }
}
