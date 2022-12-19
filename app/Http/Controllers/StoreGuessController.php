<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuessRequest;
use App\Models\Game;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\MultipleItemsFoundException;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Guess;

class StoreGuessController extends Controller
{
    public function __invoke(StoreGuessRequest $request)
    {
        try {
            $currentGame = Game::latest()->first();
        } catch (ModelNotFoundException $e) {
            abort(Response::HTTP_CONFLICT, 'No game is currently running');
        }

        $currentGuesses = $request->user()->guesses()->with('game')->where('game_id', $currentGame->id)->get();

        if ($currentGuesses->count() >= 6) {
            return response()->json(['message' => 'You have used all your guesses'], 409);
        }

        $lastGuess = $currentGuesses->last();

        if ($lastGuess && $lastGuess->guess === $lastGuess->game->word) {
            return response()->json(['message' => 'You have already gotten the answer correct.'], 409);
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
        ]);
    }
}
