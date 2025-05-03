<?php

namespace App\Http\Requests\Reservation;

class ReservationRequest extends \Illuminate\Foundation\Http\FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {

        return [
            // Basic reservation data
            'start_time'             => ['required', 'date_format:H:i'],
            'end_time'               => ['required', 'date_format:H:i', 'after:start_time'],
            'babysitter_id'          => ['required', 'exists:users,id'],
            'notes'                  => ['nullable', 'string'],

            // Toggle for recurrence
            'is_recurring'           => ['required', 'boolean'],
            // Only required when is_recurring = true
            'recurrence_end_date'    => [
                'date',
            ],
            // Always required: the date of the (first) reservation
            'recurrence_start_date'  => ['required', 'date'],
            // Optional: the end date of the recurrence
            'recurrence_freq'        => [
                'required_if:is_recurring,1',
                'in:daily,weekly,monthly',
                'after_or_equal:recurrence_start_date',
            ],
            'recurrence_interval'    => [
                'required_if:is_recurring,1',
                'integer',
                'min:1',
            ],
            'days_of_week'           => [
                'required_if:is_recurring,1',
                'array',
            ],
            // Validate each selected day
            'days_of_week.*'         => [
                'required_if:is_recurring,1',
                'in:MO,TU,WE,TH,FR,SA,SU',
            ],

        ];
    }
}
