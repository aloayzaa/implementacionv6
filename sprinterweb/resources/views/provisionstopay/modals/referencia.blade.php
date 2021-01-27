<div class="modal fade" id="modal_referencia" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Agregar Referencia</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" class="form-horizontal">
                    <input type="hidden" class="form-control" id="token" name="token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="form-row">
                            <input type="hidden" id="id" name="id" value="">
                            <div class="col-md-4">
                                <div>
                                    <label for="referencia_tercero">Tercero: </label>
                                </div>
                                <select class="form-control select2 ag-modal-select-2" name="referencia_tercero" id="referencia_tercero">

                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                  <button type="button" class="btn btn-sm btn-primary" onclick="limpiar_selector('referencia_tercero')">Limpiar</button>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="provision_fecha">Desde: </label>
                                <input type="text" class="form-control" name="provision_fecha" id="provision_fecha" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_fecha">Hasta: </label>
                                <input type="text" class="form-control" name="provision_fecha" id="provision_fecha" value="" readonly>
                            </div>
                            <div class="col-sm-1 col-xs-12">
                                <label class="col-sm-12 col-xs-12">&nbsp;</label>
                                <button type="button" name="buscar" id="buscar" class="btn btn-sm btn-primary buscar"
                                        onclick="mostrarReferencias()">Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <table id="list-references"
                           class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                           cellspacing="0" width="100%" role="grid"
                           aria-describedby="datatable-responsive_info" style="width: 100%;">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">ID
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Aplicar
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Documento
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Fecha
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Vence
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Moneda
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Saldo M.N
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Saldo M.E
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Glosa
                            </th>

                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="add_cuenta_id">Codigo/Descripción:</label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select cuenta-add" id="add_cuenta_id" name="cuenta_id">
                                <option value="" disabled selected>Seleccione una cuenta</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="add_centrocosto_id">Centro Costo:</label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <select class="select2 ag-modal-select" id="add_centrocosto_id" name="centrocosto_id">
                                <option value="" selected>Seleccione un Centro de Costo</option>
                                @foreach($centroscosto as $centrocosto)
                                    <option value="{{ $centrocosto->id }}">
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
                            <input type="text" class="form-control" id="modal_op" name="op" value="">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                            <label for="recipient-name" class="col-form-label">Importe</label>
                            <input type="text" class="form-control" id="modal_importe" name="importe" value="">
                        </div>
                    </div>

                </form>
        </div><!-- /.modal-content -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="insertar_referencia()" ><span class="fa fa-save"></span> Agregar Referencia</button>
            </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
