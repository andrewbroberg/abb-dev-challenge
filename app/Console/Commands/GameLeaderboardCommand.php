<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guess;
use App\Models\Game;

class GameLeaderboardCommand extends Command
{
    protected $signature = 'game:leaderboard';
    protected $description = 'Display current game leaderboard';

    public function handle(): int
    {
        $currentGame = Game::latest()->first();

        dd(Guess::where('game_id', $currentGame->id)->groupBy('user_id')->get()->map(function (loc)));
    }
}
