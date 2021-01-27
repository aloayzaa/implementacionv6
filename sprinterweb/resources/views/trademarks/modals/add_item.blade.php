<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span> Modelos</h4>
            </div>
            <div class="modal-body">
                <form id="form_detalle" name="form_detalle" class="form-horizontal" method="get">
                    <input type="hidden" class="form-control" id="row_id" name="row_id">
                    <input type="hidden" class="form-control" id="marca_id" name="marca_id" value="">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="modelo_id">Codigo:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="modelo_id" name="modelo_id">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="modelo_id">Descripción:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="modelo_descripcion" name="modelo_descripcion">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="modelo_id">Nombre Comercial:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="modelo_nombrecomercial" name="modelo_nombrecomercial">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="agregar_item()" ><span class="fa fa-save"></span> Agregar Detalle</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

