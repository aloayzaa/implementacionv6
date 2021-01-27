<div class="modal fade" id="myModal_tercero_direccion" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Tercero dirección:</h4>
            </div>
            <div class="modal-body">
                <form id="frm_tercero_direccion" name="frm_tercero_direccion" method="POST">
                    <input type="hidden" id="row_id_tercero_direccion" name="row_id_tercero_direccion" value="">
                    <input type="hidden" id="tercero_id_tercero_direccion" name="tercero_id_tercero_direccion" value="">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="ubigeo_id_tercero_direccion">Ubigeo:</label>
                            <select name="ubigeo_id_tercero_direccion" id="ubigeo_id_tercero_direccion" class="select2">
                                <option value="">-Seleccionar-</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="via_nombre_tercero_direccion">Dirección:</label>
                            <input type="text" id="via_nombre_tercero_direccion" name="via_nombre_tercero_direccion" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_tercero_direccion()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
