<div class="modal fade" id="modal_edit_cuenta" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_edit_itemLabel"><span class="fa fa-check"></span> Editar Detalle Provisión </h4>
            </div>
            <div class="modal-body">
                <form id="form-edit-item" action="">

                    <input type="hidden" class="form-control" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" class="form-control" id="modal_edit_row_id" name="row_id">


                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="add_cuenta_id">Codigo/Descripción:</label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select" id="edit_cuenta_id" name="cuenta_id">
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="modal_edit_centrocosto_id">Centro Costo:</label>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select" id="modal_edit_centrocosto_id" name="centrocosto_id">
                                <option value="" selected>Seleccione un Centro de Costo</option>
                                @foreach($centroscosto as $centrocosto)
                                    <option value="{{$centrocosto->id}}">
                                        {{ $centrocosto->codigo }} | {{ $centrocosto->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">OP</label>
                            <input type="text" class="form-control" id="modal_edit_op" name="op" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Importe</label>
                            <input type="text" class="form-control" id="modal_edit_importe" name="importe" value="">
                        </div>
                    </div>

                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="update_item()" >Save changes</button>
            </div>
        </div>
    </div>
</div>

