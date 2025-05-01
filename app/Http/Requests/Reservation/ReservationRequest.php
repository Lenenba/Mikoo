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
            'recurrence_start_date' => 'required|date',
            'recurrence_end_date' => 'required|date|after_or_equal:recurrence_start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'babysitter_id' => 'required|exists:users,id',
            'recurrence_interval' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'recurrence_freq' => 'required|in:daily,weekly,monthly',
            'is_recurring' => 'boolean',
        ];
    }
}
