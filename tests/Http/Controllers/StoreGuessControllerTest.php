<?php

namespace Tests\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Tests\TestCase;
use App\Models\Guess;
use Spectator\Spectator;

class StoreGuessControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Spectator::using('Wordle-Dev-Challenge.yaml');
    }

    /** @test */
    public function guess_must_be_all_uppercase(): void
    {
        $user = User::factory()->create();
        Game::factory()->create();

        $this->actingAs($user)
            ->postJson('api/guesses', [
                'guess' => 'Start',
            ])->assertUnprocessable()
            ->dump()
            ->assertJsonValidationErrors([
                'guess' => 'The guess format is invalid.',
            ]);
    }

    /** @test */
    public function it_can_submit_a_valid_guess(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/guesses', [
                'guess' => 'START'
            ])->assertValidResponse(201)
            ->assertValidRequest();

        $this->assertDatabaseHas(Guess::class, [
            'user_id' => $user->id,
            'game_id' => $game->id,
            'guess' => 'START'
        ]);
    }


}
