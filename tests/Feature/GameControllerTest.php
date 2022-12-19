<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Guess;
use Illuminate\Testing\Fluent\AssertableJson;
use Spectator\Spectator;

class GameControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Spectator::using('Wordle-Dev-Challenge.yaml');

    }

    /** @test */
    public function it_returns_an_error_if_no_game_is_running()
    {
        $this->actingAs(User::factory()->create())
            ->getJson('api/game')
            ->assertValidRequest()
            ->assertValidResponse(400);
    }

    /** @test */
    public function it_can_display_the_current_game_status_of_playing()
    {
        Game::factory()->create();

        $this->actingAs(User::factory()->create())
            ->getJson('api/game')
            ->assertValidRequest()
            ->assertValidResponse(200)
            ->assertJson([
                'guesses' => [],
                'word' => null,
                'status' => 'playing',
            ]);
    }

    /** @test */
    public function it_displays_status_of_won_if_user_guessed_the_word()
    {
        $user = User::factory()->create();
        $game = Game::factory()->create([
            'WORD' => 'AGILE',
        ]);

        Guess::factory()
            ->for($user)
            ->for($game)
            ->create([
                'guess' => 'AGILE',
            ]);

        $this->actingAs($user)
            ->get('api/game')
            ->assertValidRequest()
            ->assertValidResponse(200)
            ->assertJson(fn(AssertableJson $json) => $json->where('status', 'won')->etc()
            );
    }

    /** @test */
    public function it_displays_status_of_list_if_user_used_all_their_guesses()
    {
        $user = User::factory()->create();
        $game = Game::factory()->create([
            'WORD' => 'AGILE',
        ]);

        Guess::factory()
            ->count(6)
            ->for($user)
            ->for($game)
            ->create([
                'guess' => 'START',
            ]);

        $this->actingAs($user)
            ->get('api/game')
            ->assertValidRequest()
            ->assertValidResponse(200)
            ->assertJson(fn(AssertableJson $json) => $json->where('status', 'lost')->etc()
            );
    }
}
