<div class="modal fade" id="myModalDetalleDocumento" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Detalle Del Documento</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" name="form-add-detail">
                    <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" id="row_id" name="row_id">
                    <input type="hidden" id="parent_id" name="parent_id" value="{{isset($ordencompra->id) ? $ordencompra->id : '0'}}">

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="cbo_producto">Producto:</label>
                            <select name="cbo_producto" id="cbo_producto" class="select2 ag-modal-select">
                                <option value="" selected>--Seleccionar--</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_umedida">U.M.</label>
                            <select name="txt_umedida" id="txt_umedida" class="select2">
                            </select>
                            {{--
                            <input type="text" name="txt_umedida" id="txt_umedida" readonly="true" class="form-control">
                            <input type="hidden" name="txt_umedida_id" id="txt_umedida_id" value="">
                            --}}
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_cantidad">Cantidad:</label>
                            <input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_costo_unitario">Costo Unit:</label>
                            <input type="number" name="txt_costo_unitario" id="txt_costo_unitario"
                                   class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_descuento">% Dscto:</label>
                            <input type="text" name="txt_descuento" id="txt_descuento" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_importe_producto">Importe:</label>
                            <input type="number" name="txt_importe_producto" id="txt_importe_producto"
                                   class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="txt_volumen">Volumen:</label>
                            <input type="number" name="txt_volumen" id="txt_volumen" class="form-control" readonly="true">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="txt_glosa">Glosa:</label>
                            <input type="text" name="txt_glosa" id="txt_glosa" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="txt_valor" id="txt_valor" value="0">
                        <input type="hidden" name="txt_subtotal" id="txt_subtotal" value="0">
                        <input type="hidden" name="txt_importe_b" id="txt_importe_b">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_cart" id="btn_agregar_cart"
                        onclick="agregar_detalle_documento()"><span
                        class="fa fa-save"></span> Guardar Detalle
                </button>
            </div>

        </div>
    </div>
</div>
