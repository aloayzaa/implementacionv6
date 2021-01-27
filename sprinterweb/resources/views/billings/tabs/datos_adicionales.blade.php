<div class="panel panel-info">
    <div class="panel-heading">De la Detracción</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 col-xs-12">
                <label for="valor_referencial">Valor Referencial:</label>
                <input class="form-control" type="text" name="valor_referencial" id="valor_referencial" value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="detraccion">Tipo Detracción:</label>
                <select class="form-control select2" name="detraccion" id="detraccion">
                    <option value="">-Seleccione-</option>
                    @foreach($tiposdetraccion as $tiposdetraccion)
                        <option value="{{$tiposdetraccion->id}}">{{$tiposdetraccion->codigo}} | {{$tiposdetraccion->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 col-xs-12">
                <label for="factor_detraccion">%:</label>
                <input class="form-control" type="text" name="factor_detraccion" id="factor_detraccion" value="" readonly>
            </div>
            <div class="col-md-1 col-xs-12">
                <label for="numero_detraccion">Número:</label>
                <input class="form-control" type="text" name="numero_detraccion" id="numero_detraccion" value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="fecha_detraccion">Fecha:</label>
                <input class="form-control" type="date" name="fecha_detraccion" id="fecha_detraccion" value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="importe_detraccion">Importe:</label>
                <input class="form-control" type="text" name="importe_detraccion" id="importe_detraccion" value="">
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="referencia_detraccion">Referencia:</label>
                <input class="form-control" type="text" name="referencia_detraccion" id="referencia_detraccion" value="" readonly>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <label for="fecha_recepcion">Fecha Recepción del Documento: </label>
                <input type="date" id="fecha_recepcion" name="fecha_recepcion" class="form-control">
            </div>
            <div class="col-md-3 col-xs-12">
                <label for="vendedor">Vendedor:</label>
                <select name="vendedor" id="vendedor" class="select2">
                    <option value="">-Seleccionar-</option>
                    @foreach($vendedores as $vendedor)
                        <option value="{{$vendedor->id}}">{{$vendedor->codigo}} | {{$vendedor->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-xs-12">
                <label for="ref_anticipo">Ref. del Anticipo:</label>
                <input type="text" id="ref_anticipo" name="ref_anticipo" class="form-control" readonly>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <label for="edotros"><strong>Otras Observaciones: </strong></label><br>
                <textarea name="edotros" id="edotros" rows="10" style="min-width: 100%"></textarea>
            </div>
            <div class="col-md-8 col-xs-12">
                <label for="fecha_recepcion"><strong>Del pago del documento: </strong></label><br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="formapago">Forma de pago:</label>
                        <select class="form-control select2" name="formapago" id="formapago">
                            <option value="">-Seleccione-</option>
                            @foreach($formaspago as $formapago)
                                <option value="{{$formapago->id}}">{{$formapago->codigo}} | {{$formapago->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="banco">Banco:</label>
                        <select class="form-control select2" name="bancofp" id="bancofp" disabled>
                            <option value="">-Seleccione-</option>
                        </select>                    
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="monedafp">Moneda:</label>
                        <select class="form-control select2" name="monedafp" id="monedafp" disabled>
                            <option value="">-Seleccione-</option>
                        </select>                    
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="mediopagofp">Medio pago:</label>
                        <select class="form-control select2" name="mediopagofp" id="mediopagofp">
                            <option value="">-Seleccione-</option>
                            @foreach($mediospago as $mediopago)
                                <option value="{{$mediopago->id}}">{{$mediopago->codigo}} | {{$mediopago->descripcion}}</option>
                            @endforeach                            
                        </select>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="importefp">Importe:</label>
                        <input type="text" id="importefp" name="importefp" class="form-control">
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="transaccionfp">Transacción:</label>
                        <input type="text" id="transaccionfp" name="transaccionfp" class="form-control">                    
                    </div>                    
                </div>                                
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label for="nrochequefp">Número cheque:</label>
                        <input type="text" id="nrochequefp" name="nrochequefp" class="form-control">                    
                    </div>
                </div>                                                
            </div>            
        </div>
    </div>
</div>
