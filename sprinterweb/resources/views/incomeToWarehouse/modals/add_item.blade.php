<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Agregar Detalle Ingreso Almacén</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" class="form-horizontal">
                    <input type="hidden" class="form-control" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" class="form-control" id="row_id" name="row_id">
                    <input type="hidden" class="form-control" id="parent_id" name="parent_id" value="{{isset($salida->id) ? $salida->id : '0'}}">

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="modal_id">Codigo/Descripción:</label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select referente-add" name="producto_id" id="add_producto_id">
                                <option value="" disabled selected>Seleccione un Producto</option>
                             {{--   @foreach($productos as $producto)
                                    <option value="{{$producto->id}}">
                                      {{ $producto->codigo }} | {{ $producto->descripcion }}
                                    </option>
                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">UM</label>
                            <input type="text" class="form-control um" id="modal_um" name="um" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Cantidad</label>
                            <input type="number" class="form-control numero" id="modal_cantidad" name="cantidad" value="1">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Precio</label>
                            <input type="number" class="form-control" id="modal_precio" name="precio" value="00" disabled>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Importe</label>
                            <input type="number" class="form-control numero twodecimal" id="modal_importe" name="importe" value="00">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Lote</label>
                            <input type="text" class="form-control" id="modal_lote" name="lote">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Venc. Lote</label>
                            <input type="text" class="form-control" id="modal_fechalote" name="fechalote">
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select" id="ingreso_edit_centrocosto_id" name="centrocosto_id">
                                <option value="" disabled selected>Seleccione un Centro de Costo</option>
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
                <button type="button" class="btn btn-primary" onclick="agregar_item()" ><span class="fa fa-save"></span> Agregar Detalle</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

