<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin \App\Models\Guess
 */
class Guess extends JsonResource
{
    public function toArray($request)
    {
        $characters = Str::of($this->guess)->ucsplit();
        $word = Str::of($this->word)->ucsplit();

        return $characters->map(function ($letter, $key) use ($word) {
            $status = 'incorrect';

            if ($word[$key] === $letter) {
                $status = 'correct';
            } elseif ($word->contains($letter)) {
                $status = 'wrong_location';
            }

            return [
                'letter' => $letter,
                'status' => $status
            ];
        })->toArray();
    }
}
