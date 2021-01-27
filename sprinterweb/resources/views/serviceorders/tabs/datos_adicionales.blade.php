<div class="col-md-12 col-xs-12">
    <div class="row">
        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">De la Detracción</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="ruc">Valor Referencial:</label>
                            <input class="form-control" type="text" name="txt_valor_referencial" id="txt_valor_referencial" value="@if(isset($servicio)) {{ $servicio->referencial }} @endif" required>
                        </div>
                        <div class="col-md-6 col-xs-12 label-input">
                            <label for="ruc">Tipo Detracción:</label>
                            <select class="form-control select2" name="txt_detraccion" id="txt_detraccion">
                                <option value="">-Seleccione-</option>
                                @if(isset($tipodetraccion))
                                    <option value="{{ $tipodetraccion->id }}" selected>{{ $tipodetraccion->codigo }} | {{ $tipodetraccion->descripcion }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="ruc">Importe:</label>
                            <input class="form-control" type="text" name="txt_importe" id="txt_importe" value="@if(isset($servicio)) {{ $servicio->totaldetraccion }} @endif" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="row">
                <br>
                <br>
                <a class="form-control btn-primary text-center" @if(isset($servicio)) onclick="archivar()" @endif><span class="fa fa-folder-open-o"></span> Generar Anticipo</a>
            </div>
        </div>
        {{--<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">Datos Vehículo</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4 col-xs-12 label-input">
                            <label for="ruc">Placa:</label>
                            <select class="form-control select2" name="cbo_placa" id="cbo_placa">
                                <option value="">-Seleccione-</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="ruc">Marca:</label>
                            <input class="form-control" type="text" name="txt_marca" id="txt_marca" required disabled>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="ruc">Modelo:</label>
                            <input class="form-control" type="text" name="txt_modelo" id="txt_modelo" required disabled>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="ruc">Nro. VIN:</label>
                            <input class="form-control" type="text" name="txt_vin" id="txt_vin" required disabled>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="ruc">Nro. Motor:</label>
                            <input class="form-control" type="text" name="txt_motor" id="txt_motor" required disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">Otros Datos</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12 label-input">
                            <label for="ruc">Contacto:</label>
                        </div>
                        <div class="col-md-7 col-xs-12 label-input">
                            <select class="form-control select2" name="txt_contacto" id="txt_contacto">
                                <option value=""></option>
                                @if(isset($tercero_contacto))
                                    @foreach($tercero_contacto as $tc)
                                        <option value="{{$tc->nombre}}" @if($tc->nombre == $servicio->solicitadopor) selected @endif>{{$tc->nrodocidentidad}} | {{$tc->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 col-xs-12 label-input">
                            <label for="ruc">Depositar en:</label>
                        </div>
                        <div class="col-md-7 col-xs-12 label-input">
                            <select class="form-control select2" name="ctacte" id="ctacte">
                                <option value="">-Seleccione-</option>
                                @if(isset($depositar))
                                    @foreach($depositar as $depo)
                                        <option value="{{$depo->codigo}} {{$depo->simbolo}} {{$depo->cuenta}}" @if($depo->codigo." ".$depo->simbolo." ".$depo->cuenta == $servicio->ctacte) selected @endif>{{$depo->codigo}} {{$depo->simbolo}} {{$depo->cuenta}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 col-xs-12 label-input">
                            <label for="ruc">Lugar Entrega:</label>
                        </div>
                        <div class="col-md-7 col-xs-12 label-input">
                            <input class="form-control" type="text" name="txt_direccion" id="txt_direccion" value="@if(isset($servicio)) {{ $servicio->lugarentrega }} @endif" required>
                        </div>
                        <div class="col-md-3 col-xs-12 label-input">
                            <label for="ruc">Fec. Entrega:</label></div>
                        <div class="col-md-4 col-xs-12 label-input">
                            <input class="form-control" type="date" name="txt_fecha_entrega" id="txt_fecha_entrega" value="@if(isset($servicio)) {{ $servicio->fechaentrega }} @endif" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <label for="ruc">Notas Adicionales:</label>
                <textarea name="txt_notas" id="txt_notas" cols="30" rows="8" class="form-control">@if(isset($servicio)){{$servicio->notas}}@endif</textarea>
            </div>
        </div>
    </div>
</div>
