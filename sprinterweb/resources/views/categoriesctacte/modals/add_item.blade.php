<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span> Asignaciones de documentos</h4>
            </div>
            <div class="modal-body">
                <form id="form_detalle" name="form_detalle" class="form-horizontal" method="get">
                    <input type="hidden" class="form-control" id="row_id" name="row_id">
                    <input type="hidden" class="form-control" id="parent_id" name="parent_id" value="{{isset($pedido->id) ? $pedido->id : '0'}}">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="modal_doc_asig_id">Codigo:</label>
                            <select class="select2 ag-modal-select referente-add form-control" id="modal_doc_asig_id" name="modal_doc_asig_id">
                                <option value="">Seleccionar-</option>
                                @foreach($tipo_documento as $tdoc)
                                    <option value="{{$tdoc->id}}">{{ $tdoc->codigo }} | {{ $tdoc->descripcion }}</option>
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

