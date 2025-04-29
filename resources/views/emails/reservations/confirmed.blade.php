@component('mail::message')
# Bonjour {{ $reservation->user->name }},

Votre réservation du **{{ $reservation->recurrence_start_date->format('d/m/Y') }}** a
 {{ $reservation?->recurrence_start_time?->format('H:i') }} à **
avec **{{ $reservation->babysitter->name }}** a été **{{ $status }}**

@component('mail::button', ['url' => route('reservations.index')])
Voir mes réservations
@endcomponent

Merci de votre confiance,<br>
{{ config('app.name') }}
@endcomponent
