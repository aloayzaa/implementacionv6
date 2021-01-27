<div class="modal fade" id="myModalDetalleDocumentoEditar" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Detalle Del Documento</h4>
            </div>
            <div class="modal-body">
                <form name="form-edit-item" id="form-edit-item" action="">
                    <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" id="modal_edit_row_id" name="modal_edit_row_id">

                    <input type="hidden" name="estado_modal" id="estado_modal"
                           class="form-control">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="cbo_producto_editar">Producto:</label>
                            <select name="cbo_producto_editar" id="cbo_producto_editar" class="form-control select2 ag-modal-select">
                                <option value="">-Seleccionar-</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_umedida_editar">U.M.</label>
                            <select name="txt_umedida_editar" id="txt_umedida_editar" class="select2">
                            </select>
                            {{--
                            <input type="text" name="txt_umedida_editar" id="txt_umedida_editar" readonly="true" class="form-control">
                            <input type="hidden" name="txt_umedida_id_editar" id="txt_umedida_id_editar" value="">
                            --}}
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_cantidad_editar">Cantidad:</label>
                            <input type="number" name="txt_cantidad_editar" id="txt_cantidad_editar" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_costo_unitario_editar">Costo Unit:</label>
                            <input type="number" name="txt_costo_unitario_editar" id="txt_costo_unitario_editar" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_descuento_editar">% Dscto:</label>
                            <input type="text" name="txt_descuento_editar" id="txt_descuento_editar" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_importe_producto_editar">Importe:</label>
                            <input type="number" name="txt_importe_producto_editar" id="txt_importe_producto_editar"
                                   class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_volumen_editar">Volumen:</label>
                            <input type="number" name="txt_volumen_editar" id="txt_volumen_editar" class="form-control" readonly="true">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="txt_glosa_editar">Glosa:</label>
                            <input type="text" name="txt_glosa_editar" id="txt_glosa_editar" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="txt_valor_editar" id="txt_valor_editar" value="">
                        <input type="hidden" name="txt_subtotal_editar" id="txt_subtotal_editar" value="">
                        <input type="hidden" name="txt_importe_b" id="txt_importe_b">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_cart" id="btn_agregar_cart"
                        onclick="editar_detalle_documento()"><span
                        class="fa fa-save"></span> Editar Detalle
                </button>
            </div>

        </div>
    </div>
</div>
