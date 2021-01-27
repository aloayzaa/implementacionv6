<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><span class="fa fa-check"></span>Buscar...</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label for="producto_codigo">Tercero:</label>
                                <select class="form-control select2" name="tercero_id" id="tercero_id">
                                    <option value="">-Seleccionar-</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label for="txtdocreferencial">Referencia:</label>
                                <input type="text" id="txtdocreferencial" name="txtdocreferencial" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="agregar_producto()">
                    <span class="fa fa-ban"></span> Cerrar
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


