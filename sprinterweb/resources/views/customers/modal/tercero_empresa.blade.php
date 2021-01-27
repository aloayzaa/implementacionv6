<div class="modal fade" id="myModal_tercero_empresa" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Facturar a nombre de :</h4>
            </div>
            <div class="modal-body">
                <form id="frm_tercero_empresa" name="frm_tercero_empresa" method="POST">
                    <input type="hidden" id="row_id_tercero_empresa" name="row_id_tercero_empresa" value="">
                    <input type="hidden" id="tercero_id_tercero_empresa" name="tercero_id_tercero_empresa" value="">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <label for="ruc_tercero_empresa">DNI/RUC:</label>
                            <input type="text" id="ruc_tercero_empresa" name="ruc_tercero_empresa" class="form-control">
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <label for="razonsocial_tercero_empresa">Nombre o razón social:</label>
                            <input type="text" id="razonsocial_tercero_empresa" name="razonsocial_tercero_empresa" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="direccion_tercero_empresa">Dirección:</label>
                            <input type="text" id="direccion_tercero_empresa" name="direccion_tercero_empresa" class="form-control">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="tipo_tercero_empresa">Tipo:</label>
                            <select name="tipo_tercero_empresa" id="tipo_tercero_empresa" class="form-control">
                                <option value="N">Ninguno</option>
                                <option value="P">Padre</option>
                                <option value="M">Madre</option>
                                <option value="T">Tutor</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_tercero_empresa()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
