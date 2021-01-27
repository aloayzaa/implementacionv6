<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Detalle Salida Almacén</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" class="form-horizontal">
                    <input type="hidden" class="form-control" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" class="form-control" id="row_id" name="row_id">
                    <input type="hidden" class="form-control" id="parent_id" name="parent_id" value="{{isset($salida->id) ? $salida->id : '0'}}">

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="producto_id">Codigo/Descripción:</label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select" name="producto_id" id="add_producto_id">
                                @foreach($productos as $producto)
                                    <option value="{{$producto->id}}">
                                        {{ $producto->codigo }} | {{ $producto->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">UM</label>
                            <input type="text" class="form-control" id="modal_um" name="um" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Stock</label>
                            <input type="text" class="form-control" id="modal_stock" name="stock" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Cantidad</label>
                            <input type="text" class="form-control" id="modal_cantidad" name="cantidad">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Costo Unitario</label>
                            <input type="text" class="form-control" id="modal_costounitario" name="costoUnitario" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Lote</label>
                            <input type="text" class="form-control" id="modal_lote" name="lote">
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

