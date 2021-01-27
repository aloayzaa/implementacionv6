<h5 class="">Registro</h5>
<div class="row">
    <div class="form-row">

        <div class="form-group col-md-2">
            <label for="txt_periodo">Periodo: </label>
            <input type="text" class="form-control" id="txt_periodo" value="{{$docxpagar->periodo->descripcion}}" readonly>
        </div>
        <div class="form-group col-md-2">
            <label for="txt_numero">Número: </label>
            <input type="text" class="form-control" id="txt_numero" value="{{$docxpagar->numero}}" readonly>
        </div>
        <div class="form-group col-md-3">
            <label for="txt_ptventa">Pto. Venta: </label>
            <input type="text" class="form-control" id="txt_ptventa" value="{{$docxpagar->puntoventa->codigo}}
                @if($docxpagar->puntoventa->id != '') | @endif  {{$docxpagar->puntoventa->descripcion}}" readonly>
        </div>
        <div class="form-group col-md-3">
            <label for="txt_tipoventa">T. Venta: </label>
            <input type="text" class="form-control" id="txt_tipoventa" value="{{$docxpagar->tipoventa->codigo}}
            @if($docxpagar->tipoventa->id != '') | @endif  {{$docxpagar->tipoventa->descripcion}}" readonly>
        </div>
    </div>
</div>
<h5 class="">Documento</h5>
<div class="row">
    <div class="form-row">

        <div class="form-group col-md-8">
            <label for="txt_cliente">Cliente: </label>
            <input type="text" class="form-control" id="txt_razon_soc" value="{{$docxpagar->tercero->codigo}}
            @if($docxpagar->tercero->id != '') | @endif  {{$docxpagar->tercero->descripcion}}" readonly>
        </div>
        <div class="form-group col-md-4">
            <label for="txt_tipo_doc">Tipo Doc: </label>
            <input type="text" class="form-control" id="txt_tipo_doc" value="@if($docxpagar){{$docxpagar->documento->codigo}}
            @if($docxpagar->documento->id != '') | @endif {{$docxpagar->documento->descripcion}}@endif" readonly>
        </div>


        <div class="form-group col-md-4">
            <label for="txt_ruc">Ruc: </label>
            <input type="text" class="form-control" id="txt_ruc" value="{{$docxpagar->tercero->codigo}}" readonly>
        </div>
        <div class="form-group col-md-4">
            <label for="txt_dirección">Dirección: </label>
            <input type="text" class="form-control" id="txt_dirección" value="{{$docxpagar->tercero->via_nombre}}" readonly>
        </div>
        <div class="form-group col-md-1">
            <label for="txt_serie">Serie: </label>
            <input type="text" class="form-control" id="txt_serie" value="{{$docxpagar->seriedoc}}" readonly>
        </div>
        <div class="form-group col-md-2">
            <label for="txt_numero_doc">Numero: </label>
            <input type="text" class="form-control" id="txt_numero_doc" value="{{$docxpagar->numerodoc}}" readonly>
        </div>
        <div class="form-group col-md-1">
            <label for="txt_tcambio">T. Cambio: </label>
            <input type="text" class="form-control" id="txt_tcambio" value="{{$docxpagar->tcambio}}" readonly>
        </div>


        <div class="form-group col-md-3">
            <label for="txt_condicion">Cond. Pago: </label>
            <input type="text" class="form-control" id="txt_condicion" value="{{$docxpagar->condicionpago->codigo}}
            @if($docxpagar->condicionpago->id != '') | @endif  {{$docxpagar->condicionpago->descripcion}}" readonly>
        </div>
        <div class="form-group col-md-3">
            <label for="txt_moneda">Moneda: </label>
            <input type="text" class="form-control" id="txt_moneda" value="{{$docxpagar->currency->codigo}}
            @if($docxpagar->currency->id != '') | @endif  {{$docxpagar->currency->descripcion}}" readonly>
        </div>
        <div class="form-group col-md-2">
            <label for="txt_tc">T.C: </label>
            <input type="text" class="form-control" id="txt_tc" value="{{$docxpagar->tcmoneda}}" readonly>
        </div>
        <div class="form-group col-md-2">
            <label for="txt_fecha">Fecha: </label>
            <input type="text" class="form-control" id="txt_fecha" value="{{$docxpagar->fechadoc}}" readonly>
        </div>
        <div class="form-group col-md-2">
            <label for="txt_fecha_vencimiento">Vencimiento: </label>
            <input type="text" class="form-control" id="txt_fecha_vencimiento" value="{{$docxpagar->vencimiento}}" readonly>
        </div>

    </div>
</div>

<h5 class="">Referencia Nota Crédito/Débito</h5>
<div class="row">
    <div class="form-row">


    </div>
</div>
