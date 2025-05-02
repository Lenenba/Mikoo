<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BabysitterProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $base = [
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'birthdate'    => ['required', 'date'],
            'phone'        => ['required', 'string', 'max:15'],
            'address'      => ['required', 'string', 'max:255'],
            'bio'          => ['nullable', 'string'],
            'experience'   => ['nullable', 'string'],
        ];

        // Si l'utilisateur courant est babysitter, alors price_per_hour est obligatoire
        if (Auth::user()->role->name === env('BABYSITTER_ROLE')) {
            $base['price_per_hour'] = ['required', 'integer', 'min:5'];
        }

        return $base;
    }
}
