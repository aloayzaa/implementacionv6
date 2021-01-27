@component('mail::message')
# Hola {{ $user->name }}, Gracias por registrarte.

Por favor verifica tu cuenta:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verificar cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
