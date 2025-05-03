<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * If is_recurring is false, recurrence fields are not validated at all.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Base rules always applied
        $rules = [
            'start_time'      => ['required', 'date_format:H:i'],
            'end_time'        => ['required', 'date_format:H:i', 'after:start_time'],
            'babysitter_id'   => ['required', 'exists:users,id'],
            'notes'           => ['nullable', 'string'],
            'is_recurring'    => ['required', 'boolean'],
            'recurrence_start_date' => ['required', 'date'],
        ];

        // Only add recurrence rules if the reservation is recurring
        if ($this->boolean('is_recurring')) {
            $rules = array_merge($rules, [
                'recurrence_end_date'   => ['required', 'date', 'after_or_equal:recurrence_start_date'],
                'recurrence_freq'       => ['required', 'in:daily,weekly,monthly'],
                'recurrence_interval'   => ['required', 'integer', 'min:1'],
                'days_of_week'          => ['required', 'array'],
                'days_of_week.*'        => ['in:MO,TU,WE,TH,FR,SA,SU'],
            ]);
        }

        return $rules;
    }
}
