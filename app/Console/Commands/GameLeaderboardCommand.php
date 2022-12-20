<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guess;
use App\Models\Game;
use Illuminate\Support\Collection;

class GameLeaderboardCommand extends Command
{
    protected $signature = 'game:leaderboard';
    protected $description = 'Display current game leaderboard';

    public function handle(): int
    {
        $currentGame = Game::latest()->first();

        $leaderboard = Guess::where('game_id', $currentGame->id)->get()
            ->groupBy('user_id')
            ->reject(function (Collection $guesses) {
                $lastGuess = $guesses->last();

                return $lastGuess->guess !== $lastGuess->game->word;
            })->map(function (Collection $guesses) {
                $lastGuess = $guesses->last();

                return [
                    'name' => $lastGuess->user->name,
                    'count' => $guesses->count(),
                    'solved_at' => $lastGuess->created_at,
                ];
            })->sortBy(['count', 'solved_at']);

        $this->table(['Team', 'Guesses', 'Solved At'], $leaderboard->toArray());

        return self::SUCCESS;
    }
}
