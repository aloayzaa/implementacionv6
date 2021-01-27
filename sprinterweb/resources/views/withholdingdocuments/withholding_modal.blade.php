<div class="modal fade" id="myModalDetalleRetencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok"></span> Detalle del
                    Documento</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-label-left input_mask" id="frm_detalle_retencion"
                      name="frm_detalle_retencion" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="parent_id" id="parent_id">
                    <input type="hidden" name="id_cart" id="id_cart">
                    <input type="hidden" name="item" id="item">
                    <input type="hidden" name="tipo_modal" id="tipo_modal">

                    <div class="form-group">
                        <div class="col-sm-3 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">Hasta</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <input type="date" name="finishdate" id="finishdate" class="form-control"
                                       max="{{$period->final}}" min="{{$period->inicio}}">
                            </div>
                        </div>
                        <div class="col-sm-2 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">&nbsp;</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <button type="button" class="form-control btn-primary" id="btnmostrar" name="btnmostrar"
                                        value="{{route('listreference.withholdingdocuments')}}">Filtrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <div class="row">
                                    <table id="listWithholdingDocumentsReference"
                                           class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                           role="grid" aria-describedby="datatable-responsive_info"
                                           style="width: 100%;">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                                aria-label="First name: activate to sort column descending">
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
                                                aria-label="First name: activate to sort column descending">Mon
                                            </th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                                aria-label="First name: activate to sort column descending">Saldo M.N.
                                            </th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                                aria-label="First name: activate to sort column descending">Saldo M.E.
                                            </th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                                aria-label="First name: activate to sort column descending">Glosa
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_cart" id="btn_agregar_cart"><span
                        class="fa fa-save"></span> Guardar Detalle
                </button>
            </div>
        </div>
    </div>
</div>
