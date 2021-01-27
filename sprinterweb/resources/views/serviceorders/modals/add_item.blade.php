<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><span class="fa fa-check"></span> Agregar Detalle del Documento</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form_detalle" class="form-horizontal">
                    <input type="hidden" class="form-control" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" class="form-control" id="row_id" name="row_id">
                    <input type="hidden" class="form-control" id="parent_id" name="parent_id" value="{{isset($servicio->id) ? $servicio->id : '0'}}">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for=" " class="col-form-label">Codigo / Descripción:</label>
                            <select class="select2 ag-modal-select product" id="cbo_producto" name="producto_id">
                                <option value="">-Seleccione-</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">UM</label>
                            <input type="text" class="form-control" id="txt_umedida" name="um" readonly>
                            <input type="hidden" name="txt_umedida_id" id="txt_umedida_id">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Cantidad</label>
                            <input type="number" class="form-control cantidad" id="modal_cantidad" name="cantidad">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Costo Unit</label>
                            <input type="number" class="form-control precio" id="modal_costounidad" placeholder="0.000" name="costounidad">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Importe</label>
                            <input type="number" class="form-control importe" id="modal_importe" placeholder="0.00" name="importe">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="" class="col-form-label">OP:</label>
                            <select class="select2 ag-modal-select" id="modal_op" name="modal_op">
                                <option value="">-Seleccione-</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="" class="col-form-label">C. Costo / Descripción C. Costo:</label>
                            <select class="select2 ag-modal-select product" id="modal_centrocosto_id" name="centrocosto_id">
                                <option value="">-Seleccione-</option>
                                @foreach($centroscosto as $cc)
                                    <option value="{{$cc->id}}"> {{ $cc->codigo }} | {{ $cc->descripcion }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Glosa</label>
                            <input type="text" class="form-control glosa" id="modal_glosa" name="glosa">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="" class="col-form-label">Proyecto / Descripción Proyecto:</label>
                            <select class="select2 ag-modal-select product" id="modal_proyecto_id" name="modal_proyecto_id">
                                <option value="">-Seleccione-</option>
                                @foreach($proyectos as $proyecto)
                                    <option value="{{$proyecto->id}}"> {{ $proyecto->codigo }} | {{ $proyecto->descripcion }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="" class="col-form-label">Actividad / Descripción Actividad:</label>
                            <select class="select2 ag-modal-select product" id="modal_actividad_id" name="modal_actividad_id">
                                <option value="">-Seleccione-</option>
                                @foreach($actividades as $actividad)
                                    <option value="{{$actividad->id}}"> {{ $actividad->codigo }} | {{ $actividad->descripcion }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="agregar_item()"><span class="fa fa-save"></span>
                    Agregar Detalle
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


