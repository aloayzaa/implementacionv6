<div class="col-md-12 col-xs-12">
    <br>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label for="txt_tercero">Tercero:</label>
            <input type="text" id="txt_codigo_cliente_proveedor" name="txt_codigo_tercero" class="form-control" value="{{ $tercero->codigo }}" readonly>
        </div>
        <div class="col-md-4 col-xs-12" style="margin-top: 16px;">
            <input type="text" id="txt_razonsocial_cliente_proveedor" name="txt_tercero" class="form-control" value="{{ $tercero->descripcion }}" readonly>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 10px;">
                <label for="chk_es_trabajador"><input type="checkbox" name="chk_es_trabajador" id="chk_es_trabajador" @if($tercero->estrabajador == 1) checked @endif>Es trabajador</label>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <label for="cbo_medico">Médico:</label><br>
            <select name="cbo_medico" id="cbo_medico" class="form-control select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                <option value="1" @if($tercero->esservidor == 1) selected @endif>  Médico</option>
                <option value="2" @if($tercero->esservidor == 2) selected @endif>Médico/Convenio</option>
                <option value="3" @if($tercero->esservidor == 3) selected @endif>Ninguno</option>
            </select>
        </div>
    </div>
    <div class="row">
        <p class="title-view">Párametros del cliente:</p>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label for="cbo_condicion_pago_cliente">Condición de Pago:</label><br>
            <select name="cbo_condicion_pago_cliente" id="cbo_condicion_pago_cliente" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                @if($tercero->condicioncobr_id)
                    <option value="{{ $tercero->condicioncobr_id }}" selected>{{ $tercero->condicionpagocliente['codigo'] }} | {{ $tercero->condicionpagocliente['descripcion'] }}</option>
                @endif
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="txt_linea_credito">Línea crédito (M.N.):</label><br>
            <input type="text" id="txt_linea_credito" name="txt_linea_credito" class="form-control" value="{{ $tercero->lineacredito }}">
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="cbo_vendedor_cobrador">Vendedor / Cobrador:</label><br>
            <select name="cbo_vendedor_cobrador" id="cbo_vendedor_cobrador" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                @if($tercero->vendedor_id)
                    <option value="{{ $tercero->vendedor_id }}" selected>{{ $tercero->vendedor['codigo'] }} | {{ $tercero->vendedor['descripcion'] }}</option>
                @endif
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="cbo_ciudad">Ciudad:</label><br>
            <select name="cbo_ciudad" id="cbo_ciudad" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                @if($tercero->sucursal_id)
                    <option value="{{$tercero->sucursal_id}}" selected> {{ $tercero->sucursal['codigo'] }} | {{$tercero->sucursal['descripcion'] }}</option>
                @endif
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1 col-xs-12">
            <label>Tipo precio:</label>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="radio">
                <label><input type="radio" name="opt_tipo_precio" value="1" @if($tercero->precio == 1) checked @endif> A</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="opt_tipo_precio" value="2" @if($tercero->precio == 2) checked @endif> B</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="opt_tipo_precio" value="3" @if($tercero->precio == 3) checked @endif> C</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="opt_tipo_precio" value="4" @if($tercero->precio == 4) checked @endif> D</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <p class="title-view">Párametros del proveedor:</p>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label for="cbo_condicion_pago_proveedor">Condición de Pago:</label><br>
            <select name="cbo_condicion_pago_proveedor" id="cbo_condicion_pago_proveedor" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                @if($tercero->condicionpago_id)
                    <option value="{{ $tercero->condicionpago_id }}" selected>{{ $tercero->condicionpagoproveedor['codigo'] }} | {{ $tercero->condicionpagoproveedor['descripcion'] }}</option>
                @endif
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 20px;">
                <label for="chk_sujeto_retencion">
                    <input type="checkbox" name="chk_sujeto_retencion" @if($tercero->conretencion == 1) checked @endif>Está sujeto a retención:
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 20px;">
                <label for="chk_sujeto_detraccion">
                    <input type="checkbox" name="chk_sujeto_detraccion" @if($tercero->condetraccion == 1) checked @endif>Está sujeto a detracción:
                </label>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label>Cuentas bancarias:</label>
            <a id="modal_cuentas_bancarias" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <table class="table table-striped" id="tabla_cuentas_bancarias" style="width: 100%;">
                <thead>
                <tr>
                    <th>Banco</th>
                    <th>Nro. Cuenta</th>
                    <th>Moneda</th>
                    <th>Tipo Cta.</th>
                    <th>Defecto</th>
                    <th>CCI</th>
                    <th>Swift</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label>Marcas que provee:</label>
            <a id="modal_marcas_provee" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
            <table class="table table-striped" id="tabla_tercero_marca" style="width: 100%">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col-md-6 col-xs-12">
            <label>Rubros que provee:</label>
            <a id="modal_rubros" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
            <table class="table table-striped" id="tabla_tercero_rubro" style="width: 100%;">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
