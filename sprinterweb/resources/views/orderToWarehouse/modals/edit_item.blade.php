<div class="modal fade" id="modal_edit_item" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_edit_itemLabel"><span class="fa fa-check"></span> Editar Detalle Pedido Almacén</h4>
            </div>
            <div class="modal-body">
                <form id="form-edit-item" action="">

                    <input type="hidden" class="form-control" id="modal_edit_row_id" name="row_id">

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="modal_edit_producto_id">Codigo/Descripción:</label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select referente-edit" id="modal_edit_producto_id" name="producto_id" >
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">UM</label>
                            <input type="text" class="form-control um" id="modal_edit_um" name="um" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Stock</label>
                            <input type="number" class="form-control stock" id="modal_edit_stock" name="stock_pedido" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Cantidad</label>
                            <input type="number" class="form-control numero" id="modal_edit_cantidad" value="0" name="cantidad">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Lote</label>
                            <input type="text" class="form-control" id="modal_edit_lote" name="lote">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Venc. Lote</label>
                            <input type="text" class="form-control" id="modal_fechalote" name="fechalote">
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select" id="modal_edit_centrocosto_id" name="centrocosto_id" >
                                <option value="" selected>Seleccione un Producto</option>
                                @foreach($centroscosto as $centrocosto)
                                    <option value="{{$centrocosto->id}}">
                                        {{ $centrocosto->codigo }} | {{ $centrocosto->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="update_item()"><span class="fa fa-save"></span> Actualizar Detalle</button>
            </div>
        </div>
    </div>
</div>

