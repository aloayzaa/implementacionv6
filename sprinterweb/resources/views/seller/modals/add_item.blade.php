<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span> Comisiones por Marca</h4>
            </div>
            <div class="modal-body">
                <form id="form_detalle" name="form_detalle" class="form-horizontal" method="get">
                    <input type="hidden" class="form-control" id="row_id" name="row_id">
                    <input type="hidden" class="form-control" id="parent_id" name="parent_id" value="{{isset($pedido->id) ? $pedido->id : '0'}}">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="add_marca_id">Codigo:</label>
                            <select class="select2 ag-modal-select referente-add form-control" id="modal_marca_id" name="modal_marca_id">
                                <option value="">Seleccionar-</option>
                                @foreach($marca as $marcas)
                                    <option value="{{$marcas->id}}">{{ $marcas->codigo }} | {{ $marcas->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--
                        <div class="col-md-6 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Descripción:</label>
                            <input type="text" class="form-control des" id="modal_descripcion" name="modal_descripcion" value="" readonly>
                        </div>
                        --}}
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Desde:</label>
                            <input type="date" class="form-control desde" id="modal_desde" name="modal_desde">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Hasta:</label>
                            <input type="date" class="form-control hasta" id="modal_hasta" name="modal_hasta">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Meta(S/.)</label>
                            <input type="number" class="form-control meta" id="modal_meta" name="modal_meta">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Comisión(%)</label>
                            <input type="number" class="form-control comision" id="modal_comision" name="modal_comision">
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

