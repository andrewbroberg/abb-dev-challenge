<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuessRequest;
use App\Models\Game;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Guess;

class StoreGuessController extends Controller
{
    public function __invoke(StoreGuessRequest $request)
    {
        try {
            $currentGame = Game::latest()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(Response::HTTP_BAD_REQUEST, 'No game is currently running');
        }

        $currentGuesses = $request->user()->guesses()->with('game')->where('game_id', $currentGame->id)->get();

        if ($currentGuesses->count() >= 6) {
            abort(Response::HTTP_CONFLICT, 'You have used all your guesses');
        }

        $lastGuess = $currentGuesses->last();

        if ($lastGuess && $lastGuess->guess === $lastGuess->game->word) {
            abort(Response::HTTP_CONFLICT, 'You have already gotten the answer correct.');
        }

        $request->user()->guesses()->create([
            'game_id' => $currentGame->id,
            'guess' => $request->input('guess')
        ]);

        $guesses = $request->user()->guesses()->with('game')->where('game_id', $currentGame->id)->get();

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
        ], Response::HTTP_CREATED);
    }
}
