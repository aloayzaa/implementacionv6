<div class="modal fade" id="myModalCuentasBancarias" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Cuentas Bancarias:</h4>
            </div>
            <div class="modal-body">
                <form id="frm_cuentas_bancarias" name="frm_cuentas_bancarias" method="POST">
                    <input type="hidden" id="row_id_cuentasbancarias" name="row_id_cuentasbancarias" value="">
                    <input type="hidden" id="tercero_id_tercero_cuenta" name="tercero_id_tercero_cuenta" value="">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="banco_id_tercero_cuenta">Banco:</label>
                            <select name="banco_id_tercero_cuenta" id="banco_id_tercero_cuenta" class="select2">
                                <option value="">-Seleccionar-</option>
                                @foreach($banco as $b)
                                    <option value="{{ $b->id }}">{{ $b->codigo }} | {{ $b->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="cuenta_tercero_cuenta">Nro.Cuenta:</label>
                            <input type="text" id="cuenta_tercero_cuenta" name="cuenta_tercero_cuenta" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="moneda_id_tercero_cuenta">Moneda:</label>
                            <select name="moneda_id_tercero_cuenta" id="moneda_id_tercero_cuenta" class="select2">
                                <option value="">-Seleccionar-</option>
                                @foreach($moneda as $m)
                                    <option value="{{ $m->id }}">{{ $m->codigo }} | {{ $m->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="tipocuenta_tercero_cuenta">Tipo Cta.</label>
                            <select name="tipocuenta_tercero_cuenta" id="tipocuenta_tercero_cuenta" class="select2">
                                <option value="">-Seleccionar-</option>
                                <option value="H">Ahorro</option>
                                <option value="T">Cta. Cte.</option>
                                <option value="D">Detracción</option>
                                <option value="M">Maestro</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="checkbox">
                                <label for="defecto_tercero_cuenta"><input type="checkbox" id="defecto_tercero_cuenta" name="defecto_tercero_cuenta"> Defecto</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="cci_tercero_cuenta">CCI:</label>
                            <input type="text" id="cci_tercero_cuenta" name="cci_tercero_cuenta" class="form-control">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="swift_tercero_cuenta">Swift:</label>
                            <input type="text" id="swift_tercero_cuenta" name="swift_tercero_cuenta" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_cuentas_bancarias()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
