<?php

namespace Tests\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Tests\TestCase;

class StoreGuessControllerTest extends TestCase
{
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
}
