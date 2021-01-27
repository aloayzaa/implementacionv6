<div class="row">
   <div class="row">
       <p class="title-view">Detraccion :</p>
       <div class="clearfix"></div>
    </div>
    <div class="row">
        <div class="col-md-2 col-xs-12 label-input">
            <label for="refvalue">Valor Referencial:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="tipodetraccion">Tipo detracción:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="detra_porcentaje">Porcentaje %:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="detra_numero">Número:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="">Fecha:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="total_detra">Importe:</label>
        </div>
    </div>
    <div id="div-detraccion" class="row">
        <div class="col-md-2 col-xs-12">
            <input class="form-control noedit" type="text" name="refvalue" id="refvalue" value="@if($docxpagarDetraccion){{ $docxpagarDetraccion->referencial }}@endif">
        </div>
        <div class="col-md-2 col-xs-12">
            <input type="hidden" id="detraccion_id" name="detraccion_id" value="{{ $docxpagarDetraccion ? $docxpagarDetraccion->parent_id : null }}">
            <select class="form-control select2 noedit" name="tipodetraccion" id="tipodetraccion">
                <option selected disabled>-Seleccione-</option>
                @foreach($tipodetraccion as $tipodetra)
                    <option value="{{$tipodetra->id}}"
                        @if($docxpagarDetraccion)@if($tipodetra->id == $docxpagarDetraccion->tipoDetraccion['id']) selected @endif @endif>
                        {{ $tipodetra->codigo }} | {{ $tipodetra->descripcion }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <input class="form-control" type="text" name="detra_porcentaje" id="detra_porcentaje"
                   value="@if($docxpagarDetraccion){{ $docxpagarDetraccion->tipoDetraccion['valor'] }}@endif" readonly>
        </div>
        <div class="col-md-2 col-xs-12">
            <input class="form-control" type="text" name="detra_numero" id="detra_numero"
                   value="@if($docxpagarDetraccion){{$docxpagarDetraccion->nrodetraccion}}@endif">
        </div>
        <div class="col-md-2 col-xs-12">
            <input class="form-control" type="date" name="detra_fecha" id="detra_fecha"
                   value="@if($docxpagarDetraccion){{$docxpagarDetraccion->fechadetraccion}}@endif">
        </div>
        <div class="col-md-2 col-xs-12">
            <input class="form-control noedit" type="text" name="total_detra" id="total_detra"
                   value="@if($docxpagarDetraccion){{$docxpagarDetraccion->totaldetraccion}}@endif">
        </div>
    </div>

    <div class="row">
        <p class="title-view">Pago Documento:</p>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <div class="col-md-2 col-xs-12 label-input">
            <label for="paymentmetho">Forma pago:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="bankcurrentaccount">Banco:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="currencypg">Moneda:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="paymentway">Medio pago:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="totalpd">Importe:</label>
        </div>
        <div class="col-md-2 col-xs-12 label-input">
            <label for="transaction">Transacción:</label>
        </div>
    </div>
    <div id="div-formapago" class="row">
        <div class="col-md-2 col-xs-12">
            <input type="hidden" id="formapago_id" name="formapago_id" value="{{ $docxpagarFormaPago ? $docxpagarFormaPago->item : null }}">
            <select class="form-control select2" name="txt_formapago" id="txt_formapago">
                <option selected disabled>-Seleccione-</option>
                @foreach($formasdepago as $formadepago)
                    <option value="{{ $formadepago->id }}"
                    @if($docxpagarFormaPago) @if($formadepago->id == $docxpagarFormaPago->formaPago['id']) selected @endif @endif>
                        {{ $formadepago->codigo }} | {{ $formadepago->descripcion }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <select class="form-control select2" name="cta_banco" id="cta_banco" disabled>
                <option selected disabled>-Seleccione-</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <select class="form-control select2" name="moneda_pago" id="moneda_pago" disabled>
                <option selected disabled>-Seleccione-</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <select class="form-control select2" name="mediopago" id="mediopago">
                <option selected disabled>-Seleccione-</option>
                @foreach($mediospago as $mediopago)
                    <option value="{{ $mediopago->id }}"
                    @if($docxpagarFormaPago) @if($mediopago->id == $docxpagarFormaPago->mediopago_id) selected @endif @endif>
                        {{ $mediopago->codigo }} | {{ $mediopago->descripcion }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <input class="form-control" type="text" name="importepago" id="importepago"
                   value="@if($docxpagarFormaPago){{$docxpagarFormaPago->docBanco['total']}}@endif"
                   placeholder="0.00">
        </div>
        <div class="col-md-2 col-xs-12">
            <input class="form-control" type="text" name="transaccion_pago" id="transaccion_pago"
                   value="@if($docxpagarFormaPago){{$docxpagarFormaPago->transaccion}}@endif">
        </div>
    </div>
</div>

