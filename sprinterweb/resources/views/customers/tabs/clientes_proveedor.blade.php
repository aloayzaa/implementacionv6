<div class="col-md-12 col-xs-12">
    <br>
    <div class="row">
       <div class="col-md-3 col-xs-12">
           <label for="txt_tercero">Tercero:</label>
           <input type="text" id="txt_codigo_cliente_proveedor" name="txt_codigo_tercero" class="form-control" readonly>
       </div>
        <div class="col-md-4 col-xs-12" style="margin-top: 16px;">
            <input type="text" id="txt_razonsocial_cliente_proveedor" name="txt_tercero" class="form-control" readonly>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 10px;">
                <label for="chk_es_trabajador"><input type="checkbox" name="chk_es_trabajador" id="chk_es_trabajador">Es trabajador</label>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <label for="cbo_medico">Médico:</label><br>
            <select name="cbo_medico" id="cbo_medico" class="form-control select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                <option value="1">  Médico</option>
                <option value="2">Médico/Convenio</option>
                <option value="3">Ninguno</option>
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
                <option value="{{ $condicion_pago->id }}" selected>{{ $condicion_pago->codigo }} | {{ $condicion_pago->descripcion }}</option>
                {{-- <option value="">-Seleccionar-</option>  --}}
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="txt_linea_credito">Línea crédito (M.N.):</label><br>
            <input type="text" id="txt_linea_credito" name="txt_linea_credito" class="form-control">
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="cbo_vendedor_cobrador">Vendedor / Cobrador:</label><br>
            <select name="cbo_vendedor_cobrador" id="cbo_vendedor_cobrador" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="cbo_ciudad">Ciudad:</label><br>
            <select name="cbo_ciudad" id="cbo_ciudad" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
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
                <label><input type="radio" name="opt_tipo_precio" checked value="1"> A</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="opt_tipo_precio" value="2"> B</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="opt_tipo_precio" value="3"> C</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="opt_tipo_precio" value="4"> D</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                <option value="{{ $condicion_pago->id }}" selected>{{ $condicion_pago->codigo }} | {{ $condicion_pago->descripcion }}</option>
                {{-- <option value="">-Seleccionar-</option> --}}
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 20px;">
                <label for="chk_sujeto_retencion">
                    <input type="checkbox" name="chk_sujeto_retencion">Está sujeto a retención:
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 20px;">
                <label for="chk_sujeto_detraccion">
                    <input type="checkbox" name="chk_sujeto_detraccion">Está sujeto a detracción:
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
