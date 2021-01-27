<div class="panel panel-info">
    <div class="panel-heading">De la Detracción</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 col-xs-12">
                <label for="valor_referencial">Valor Referencial:</label>
                <input class="form-control" type="text" name="valor_referencial" id="valor_referencial" @if(isset($detraccion->referencial)) value="{{$detraccion->referencial}}" @endif value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="detraccion">Tipo Detracción:</label>
                <select class="form-control select2" name="detraccion" id="detraccion">
                    <option value="">-Seleccione-</option>
                    @foreach($tiposdetraccion as $tipodetraccion)                                                
                        <option value="{{$tipodetraccion->id}}" @if(isset($detraccion->tipodetraccion_id)) @if($detraccion->tipodetraccion_id == $tipodetraccion->id) selected @endif @endif>{{$tipodetraccion->codigo}} | {{$tipodetraccion->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 col-xs-12">
                <label for="factor_detraccion">%:</label>
                <input class="form-control" type="text" name="factor_detraccion" id="factor_detraccion" value="{{$factor_detraccion}}" readonly>
            </div>
            <div class="col-md-1 col-xs-12">
                <label for="numero_detraccion">Número:</label>
                <input class="form-control" type="text" name="numero_detraccion" id="numero_detraccion" @if(isset($detraccion->nrodetraccion)) value="{{$detraccion->nrodetraccion}}" @endif value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="fecha_detraccion">Fecha:</label>
                <input class="form-control" type="date" name="fecha_detraccion" id="fecha_detraccion" @if(isset($detraccion->fechadetraccion)) value="{{$detraccion->fechadetraccion}}" @endif value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="importe_detraccion">Importe:</label>
                <input class="form-control" type="text" name="importe_detraccion" id="importe_detraccion" @if(isset($detraccion->totaldetraccion)) value="{{$detraccion->totaldetraccion}}" @endif value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="referencia_detraccion">Referencia:</label>
                <input class="form-control" type="text" name="referencia_detraccion" id="referencia_detraccion" value="{{$referencia_detraccion}}" data-id="{{$referencia_detraccion_id}}" readonly>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <label for="fecha_recepcion">Fecha Recepción del Documento: </label>
                <input type="date" id="fecha_recepcion" name="fecha_recepcion" class="form-control" value="{{$docxpagar->recepcion}}">
            </div>
            <div class="col-md-3 col-xs-12">
                <label for="vendedor">Vendedor:</label>
                <select name="vendedor" id="vendedor" class="select2">
                    <option value="">-Seleccionar-</option>
                    @foreach($vendedores as $vendedor)
                        <option value="{{$vendedor->id}}" @if($docxpagar->vendedor_id == $vendedor->id) selected @endif>{{$vendedor->codigo}} | {{$vendedor->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-xs-12">
                <label for="ref_anticipo">Ref. del Anticipo:</label>
                <input type="text" id="ref_anticipo" name="ref_anticipo" class="form-control" value="{{$ref_anticipo}}" data-id="{{$ref_anticipo_id}}" disabled>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <label for="edotros"><strong>Otras Observaciones: </strong></label><br>
                <textarea name="edotros" id="edotros" rows="10" style="min-width: 100%">{{$docxpagar->observaciones}}</textarea>
            </div>
            <div class="col-md-8 col-xs-12">
                <label for=""><strong>Del pago del documento: </strong></label><br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="formapago">Forma de pago:</label>
                        <select class="form-control select2" name="formapago" id="formapago">
                            <option value="">-Seleccione-</option>                            
                                @foreach($formaspago as $formapago)
                                    <option value="{{$formapago->id}}" @if(isset($docxpagar_formapago->formapago_id))  @if($formapago->id == $docxpagar_formapago->formapago_id) selected @endif @endif>{{$formapago->codigo}} | {{$formapago->descripcion}}</option>
                                @endforeach                            
                        </select>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="banco">Banco:</label>
                        <select class="form-control select2" name="bancofp" id="bancofp" disabled>
                            <option value="">-Seleccione-</option>
                            @if(isset($docxpagar_formapago->banco_id))
                                @foreach($bancos as $banco)
                                    <option value="{{$banco->id}}" @if($banco->id == $docxpagar_formapago->banco_id) selected @endif>{{$banco->codigo}} | {{$banco->descripcion}}</option>
                                @endforeach
                            @endif
                        </select>                    
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="monedafp">Moneda:</label>
                        <select class="form-control select2" name="monedafp" id="monedafp" disabled>
                            <option value="">-Seleccione-</option>
                            @if(isset($docxpagar_formapago->moneda_id))
                                @foreach($monedas as $moneda)
                                    <option value="{{$moneda->id}}" @if($moneda->id == $docxpagar_formapago->moneda_id) selected @endif>{{$moneda->codigo}} | {{$moneda->descripcion}}</option>
                                @endforeach
                            @endif                            
                        </select>                    
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="mediopagofp">Medio pago:</label>
                        <select class="form-control select2" name="mediopagofp" id="mediopagofp">
                            <option value="">-Seleccione-</option>
                            @foreach($mediospago as $mediopago)
                                <option value="{{$mediopago->id}}" @if(isset($docxpagar_formapago->mediopago_id)) @if($mediopago->id == $docxpagar_formapago->mediopago_id) selected @endif @endif>{{$mediopago->codigo}} | {{$mediopago->descripcion}}</option>
                            @endforeach                            
                        </select>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="importefp">Importe:</label>
                        <input type="text" id="importefp" name="importefp" class="form-control" @if(isset($docxpagar_formapago->importe)) value="{{$docxpagar_formapago->importe}}" @endif>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="transaccionfp">Transacción:</label>
                        <input type="text" id="transaccionfp" name="transaccionfp" class="form-control" @if(isset($docxpagar_formapago->transaccion)) value="{{$docxpagar_formapago->transaccion}}" @endif>                    
                    </div>                    
                </div>                                
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="nrochequefp">Número cheque:</label>
                        <input type="text" id="nrochequefp" name="nrochequefp" class="form-control" @if(isset($docxpagar_formapago->nrocheque)) value="{{$docxpagar_formapago->nrocheque}}" @endif>                    
                    </div>
                </div>                                                
            </div>            
        </div>
    </div>
</div>