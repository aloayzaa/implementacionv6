{{--<form class="form-horizontal" id="" name=""
      method="POST">
    <input type="hidden"  name="_token" value="{{ csrf_token() }}">--}}
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <p class="title-view">De la Detracción:</p>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                <label for="txt_valor_referencial">Valor Referencial</label>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="txt_valor_referencial" id="txt_valor_referencial" class="form-control" value="{{$ordencompra->referencial}}">
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                <label for="cbo_tipo_detraccion">Tipo de Detración:</label>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="cbo_tipo_detraccion" id="cbo_tipo_detraccion" class="select2 form-control" style="width: 100%">
                    <option value="{{0}}">-Seleccionar-</option>
                    @foreach($tipos_detraccion as $tipodetraccion)
                        <option value="{{$tipodetraccion->id}}"
                        data-valor="{{$tipodetraccion->valor}}"
                        @if($ordencompra->tipodetraccion_id == $tipodetraccion->id) selected @endif>{{$tipodetraccion->codigo}} | {{$tipodetraccion->descripcion}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                %:
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" id="txt_porcentaje_tipo_detraccion" name="txt_porcentaje_tipo_detraccion" class="form-control" value="{{$detraccion['valor']}}" readonly>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                <label for="txt_importe">Importe:</label>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="txt_importe" id="txt_importe" class="form-control" value="{{$ordencompra->totaldetraccion}}" readonly>
            </div>
        </div>
        <div class="row">
            <p class="title-view">Otros Datos:</p>
        </div>
        <div class="row">
            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                <label for="cbo_contacto_otros_datos">Contacto:</label>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <select name="cbo_contacto_otros_datos" id="cbo_contacto_otros_datos" class="form-control select2" style="width: 100%">
                    @foreach($tercero_contacto as $tercero)
                        <option value="{{$tercero->nrodocidentidad}}" @if(trim($ordencompra->solicitadopor) ==  trim($tercero->nrodocidentidad)) selected @endif>{{$tercero->nrodocidentidad}} | {{$tercero->nombre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                <label for="cbo_depositar_otros_datos">Deporsitar en:</label>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <select name="cbo_depositar_otros_datos" id="cbo_depositar_otros_datos" class="form-control select2" style="width: 100%">
                    @foreach($tercero_cuenta as $tercero)
                    <option value="{{$tercero->banco_codigo . ' ' . $tercero->moneda_simbolo . ' ' . $tercero->cuenta}} " @if(trim($ordencompra->ctacte) == trim($tercero->banco_codigo . ' ' . $tercero->moneda_simbolo . ' ' . $tercero->cuenta)) selected @endif>{{$tercero->banco_codigo . ' ' . $tercero->moneda_simbolo . ' ' . $tercero->cuenta}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                <label for="txt_lugar_entrega">Lugar Entrega:</label>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="txt_lugar_entrega" id="txt_lugar_entrega" class="form-control" value="{{$lugar_entrega}}">
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                {{--espacio--}}
            </div>
            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                <label for="txt_fecha_entrega">Fecha Entrega:</label>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="date" name="txt_fecha_entrega" id="txt_fecha_entrega" class="form-control" value="">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                <label for="txt_notas_adicionales">Notas Adicionales:</label><br>
                <textarea name="txt_notas_adicionales" id="txt_notas_adicionales" cols="30" rows="10" class="form-control">{{$ordencompra->notas}}</textarea>
            </div>
        </div>
    </div>
</div>
{{--</form>--}}
