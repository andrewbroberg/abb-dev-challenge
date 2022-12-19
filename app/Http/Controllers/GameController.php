<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Guess;

class GameController extends Controller
{
    public function __invoke(Request $request)
    {
        $guesses = $request->user()->guesses()->where('word', config('game.word'))->oldest()->get();

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
            'guesses' => Guess::collection($guesses),
            'word' => $word,
            'status' => $status
        ]);
    }
}
