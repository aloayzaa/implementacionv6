<div class="modal fade" id="modal_provision" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Provision De Compra</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" class="form-horizontal">
                    <input type="hidden" class="form-control" id="token" name="token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="form-row">
                            <input type="hidden" id="id" name="id" value="">
                            <div class="form-group col-md-2">
                                <label for="provision_periodo">Periodo: </label>
                                <input type="text" class="form-control" value="" name="" id="provision_periodo" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_unegocio">Unid. Negocio: </label>
                                <input type="text" class="form-control" name="provision_unegocio" id="provision_unegocio" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_numero">Número: </label>
                                <input type="text" class="form-control" name="provision_numero" id="provision_numero"  value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_fecha">Fecha: </label>
                                <input type="text" class="form-control" name="provision_fecha" id="provision_fecha" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_sucursal">Sucursal: </label>
                                <input type="text" class="form-control" name="provision_sucursal" id="provision_sucursal" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_transaccion">Tipo Transacción: </label>
                                <input type="text" class="form-control" name="provision_transaccion" id="provision_transaccion" value="" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="provision_tercero">Cliente / Proveedor: </label>
                                <input type="text" class="form-control" name="provision_tercero" id="provision_tercero" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_doc">Tipo Doc: </label>
                                <input type="text" class="form-control" name="provision_doc" id="provision_doc" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_serie">Serie: </label>
                                <input type="text" class="form-control" name="provision_serie" id="provision_serie" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_numero">Numero Doc: </label>
                                <input type="text" class="form-control" name="provision_numerodoc" id="provision_numerodoc" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_fechadoc">Fecha Doc: </label>
                                <input type="text" class="form-control" name="provision_fechadoc" id="provision_fechadoc" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-row">


                            <div class="form-group col-md-2">
                                <label for="provision_moneda">Moneda: </label>
                                <input type="text" class="form-control" name="provision_moneda" id="provision_moneda" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_tcambio">Tipo Cambio: </label>
                                <input type="text" class="form-control" name="provision_tcambio" id="provision_tcambio" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_condicionpago">Condicion Pago: </label>
                                <input type="text" class="form-control" name="provision_condicionpago" id="provision_condicionpago" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_vencimiento">Vencimiento: </label>
                                <input type="text" class="form-control" name="provision_vencimiento" id="provision_vencimiento" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_tc">T. C: </label>
                                <input type="text" class="form-control" name="provision_tc" id="provision_tc" value="" placeholder="0.000" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="provision_glosa">Glosa: </label>
                                <input type="text" class="form-control" name="provision_glosa" id="provision_glosa" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_base">Base Imponible: </label>
                                <input type="text" class="form-control" name="provision_base" id="provision_base" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_inafecto">Inafecto: </label>
                                <input type="text" class="form-control" name="provision_inafecto" id="provision_inafecto" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_impuestos">Impuestos: </label>
                                <input type="text" class="form-control" name="provision_impuestos" id="provision_impuestos" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="provision_total">Total: </label>
                                <input type="text" class="form-control" name="provision_total" id="provision_total" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <table id="listprov-detail"
                           class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                           cellspacing="0" width="100%" role="grid"
                           aria-describedby="datatable-responsive_info" style="width: 100%;">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Fecha
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Referencia
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Glosa
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Importe M.N
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                rowspan="1" colspan="1" style="" aria-sort="ascending"
                                aria-label="First name: activate to sort column descending">Importe M.N
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

