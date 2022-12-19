<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuessRequest;
use App\Http\Requests\UpdateGuessRequest;
use App\Models\Guess;
use Illuminate\Http\Response;

class StoreGuessController extends Controller
{
    public function __invoke(StoreGuessRequest $request)
    {
        $currentGuesses = $request->user()->guesses()->where('word', config('game.word'))->get();

        if ($currentGuesses->count() >= 6) {
            return response()->json(['error' => 'You have used all your guesses'], 409);
        }

        if ($currentGuesses->last() && $currentGuesses->last()->guess === config('game.word')) {
            return response()->json(['error' => 'You have already gotten the answer correct.'], 409);
        }

        $request->user()->guesses()->create([
            'word' => config('game.word'),
            'guess' => $request->input('guess')
        ]);

        $guesses = $request->user()->guesses()->where('word', config('game.word'))->get();

        $status = 'playing';
        $word = null;

        if ($guesses->last() && $guesses->last()->guess === config('game.word')) {
            $status = 'won';
            $word = config('game.word');
        } elseif ($guesses->count() === 6) {
            $status = 'lost';
            $word = config('game.word');
        }

        return response()->json([
            'guesses' => \App\Http\Resources\Guess::collection($guesses),
            'word' => $word,
            'status' => $status
        ]);
    }
}
