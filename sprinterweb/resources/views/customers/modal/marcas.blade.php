<div class="modal fade" id="myModalMarca" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Marcas:</h4>
            </div>
            <div class="modal-body">
                <form id="frm_marcas" name="frm_marcas" method="POST">
                    <input type="hidden" id="row_id_marca" name="row_id_marca" value="">
                    <input type="hidden" id="tercero_id_tercero_marca" name="tercero_id_tercero_marca" value="">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            {{--<label for="marca_id_tercero_marca">Marca:</label>--}}
                            <select name="marca_id_tercero_marca" id="marca_id_tercero_marca" class="select2">
                                <option value="">-Seleccionar-</option>
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
                        onclick="enviar_marcas()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
