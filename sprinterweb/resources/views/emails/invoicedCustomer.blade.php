@component('mail::message')

Estimad@: **{!! $documento->razonsocial !!}**  {!! $parrafo1 !!}  

<br>

[http://eSprinter.anikamagroup.com](http://eSprinter.anikamagroup.com)

<br>

Para ingresar a la aplicación web por favor utilice su RUC o DNI como usuario y contraseña.

<br>

Los datos de su comprobante electrónico son:

<br>

**Razón social          :** {!! $documento->razonsocial !!} <br>
**RUC/DNI:              :** {!! $documento->ruc !!} <br>
**Tipo Comprobante      :** {!! $documento->tipodoc_nombre !!} <br>
**Fecha de emisión      :** {!! str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("d"), 2, '0', STR_PAD_LEFT); !!} <br>
**Nro.Comprobante       :** {!! substr($documento->seriedoc, -4) . '-' . trim($documento->numerodoc) !!} <br>
**Valor total           :** {!! $documento->moneda !!}  {!! formatear_numero($documento->total, 2) !!} <br>
**Hash Sunat            :** {!! $documento->cpe_hash !!} <br>

![{{$nombre_xml}}]({{$xml}})


@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
