@component('mail::message')
# Hola {{ $user->name }}

Su base de datos ha sido creada, Bienvenido a Anikama xD

Gracias. <br>
{{ config('app.name') }}
@endcomponent
