@component('mail::message')
## Nuevo Usuario Registrado

**Datos de Usuario**:

* Usuario: {{ $user->name }}
* Email: {{ $user->email }}
* Teléfono: {{ $user->phone }}

**Datos de la Empresa**:

* Nombre: {{ $user->companies->pluck('name')->first() }}
* Ruc: {{ $user->companies->pluck('ruc')->first() }}


@component('mail::button', ['url' => route('verify_admin', $user->verification_token)])
Activar Usuario
@endcomponent

@endcomponent
