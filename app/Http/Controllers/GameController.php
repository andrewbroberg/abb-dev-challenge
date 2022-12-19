<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Guess;
use App\Models\Game;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class GameController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $currentGame = Game::latest()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(Response::HTTP_BAD_REQUEST, 'No game is currently running');
        }

        $guesses = $request->user()->guesses()->with('game')->where('game_id', $currentGame->id)->oldest()->get();

        $status = 'playing';
        $word = null;
        $lastGuess = $guesses->last();

        if ($lastGuess && $lastGuess->guess === $lastGuess->game->word) {
            $status = 'won';
            $word = $currentGame->word;
        } elseif ($guesses->count() === 6) {
            $status = 'lost';
            $word = $currentGame->word;
        }

        return response()->json([
            'guesses' => Guess::collection($guesses),
            'word' => $word,
            'status' => $status
        ]);
    }
}
