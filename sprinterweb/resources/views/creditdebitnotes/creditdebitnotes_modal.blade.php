<!-- Modal -->
<div id="myModalNotaCredito" class="modal fade" role="dialog">

    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Referencia</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3 col-xs-12">
                        <label>Hasta</label>
                        <input type="date" class="form-control" name="date_modal" id="date_modal">
                    </div>
                    <div class="col-sm-2 col-xs-12">
                        <label>&nbsp;</label>
                        <a type="button" class="form-control"
                           onclick="filtrar_referencia('{{ route('list.references')}}')">Filtrar
                            <span class="fa fa-filter"></span></a>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table id="listReferences"
                               class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                               cellspacing="0" width="100%" role="grid"
                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style="" aria-sort="ascending"
                                    aria-label="First name: activate to sort column descending">Sel
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Last name: activate to sort column ascending">Documento
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Age: activate to sort column ascending">Fecha
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Age: activate to sort column ascending">Nombre / Razón Social
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Age: activate to sort column ascending">Glosa
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table id="listReferencesDetails"
                               class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                               cellspacing="0" width="100%" role="grid"
                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style="" aria-sort="ascending"
                                    aria-label="First name: activate to sort column descending">Sel
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Last name: activate to sort column ascending">Aplicar
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Age: activate to sort column ascending">Cuenta
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Age: activate to sort column ascending">Descripción
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                    rowspan="1" colspan="1" style=""
                                    aria-label="Age: activate to sort column ascending">Importe
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="preinserta_carro_retencion()">Aceptar</button>
            </div>
        </div>
    </div>
</div>
