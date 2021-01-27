<div class="modal fade" id="myModalAlmacen" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Buscar Almacen</h4>
            </div>
            <div class="modal-body">
                <form action="" id="frm_ubicacion_almacen" name="frm_ubicacion_almacen">
                    <input type="hidden" id="row_id" name="row_id" value="">
                    <input type="hidden" id="productoubicacion_id" name="productoubicacion_id" value="">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label>Buscar:</label><br>
                            {!! $selector_ubicacion_almacen !!}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="txt_ubicacion_almacen">Ubicación:</label>
                            <input type="text" name="txt_ubicacion_almacen" id="txt_ubicacion_almacen" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_ubicacion_almacen()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>

        </div>
    </div>
</div>
