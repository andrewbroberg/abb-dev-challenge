<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guess' => ['required', 'string', 'size:5', 'regex:$[A-Z]$']
        ];
    }
}
