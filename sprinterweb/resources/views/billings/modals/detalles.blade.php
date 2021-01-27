<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><span class="fa fa-check"></span> Buscar...</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <label for="producto_codigo">Código:</label>
                        <input type="text" id="producto_codigo" name="producto_codigo" class="form-control">
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label for="producto_descripcion">Descripción:</label>
                        <input type="text" id="producto_descripcion" name="producto_descripcion" class="form-control">
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label for="producto_presentacion">Presentación:</label>
                        <input type="text" id="producto_presentacion" name="producto_presentacion" class="form-control">
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <a onclick="bucar_producto()"><i class="flaticon-search" title="Buscar producto" style="font-size: 15px; color: blue"></i></a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="row">
                            <table id="listProductDetailBilling" class="table table-striped table-bordered table-bordered2" width="100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>U.M</th>
                                    <th>Serie</th>
                                    <th>Stock</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>%Dscto</th>
                                    <th>Ubicac</th>
                                    <th>Caracteristicas</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="agregar_productos()">
                    <span class="fa fa-ban"></span> Cerrar
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


