<div class="modal fade" id="modal_pagar" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Documentos de Referencia</h4>
            </div>
            <div class="modal-body">
                <form action="" id="frm_generales" class="form-horizontal">
                    <div class="row">
                        <input type="hidden" id="id" name="id" value="">
                            <div class="form-group col-md-2">
                                <label for="txt_tipodoc">Tipo Doc: </label>
                                <select class="form-control select2" name="txt_tipodoc" id="txt_tipodoc">
                                    <option value="01" selected>01 | Factura</option>
                                    <option value="03" >02 | Boleta</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_serie">Serie: </label>
                                <input type="text" class="form-control" name="txt_serie" id="txt_serie" value="">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_numero">Numero: </label>
                                <input type="text" class="form-control" name="txt_numero" id="txt_numero" value="">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_fecha">Fecha: </label>
                                <input type="text" class="form-control" name="txt_fecha" id="txt_fecha" value="">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_tcambio">T. Cambio: </label>
                                <input type="text" class="form-control typechange" name="txt_tcambio" id="txt_tcambio" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_guia">Guia de Remisión: </label>
                                <input type="text" class="form-control" name="txt_guia" id="txt_guia" value="">
                            </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="txt_moneda">Moneda : </label>
                            <input type="text" class="form-control" name="txt_moneda" id="txt_moneda" value="" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="txt_condicionpago">Condicion Pago : </label>
                            <input type="text" class="form-control" name="txt_condicionpago" id="txt_condicionpago" value="" readonly>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <table id="tabla-caja" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Descripción</th>
                                    <th>Importe</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group col-md-2">
                                <label for="txt_valorventa">V. Venta: </label>
                                <input type="text" class="form-control" name="txt_valorventa" id="txt_valorventa" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_descuento">Descuento: </label>
                                <input type="text" class="form-control" name="txt_descuento" id="txt_descuento" value="0.00" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_igv">IGV: </label>
                                <input type="text" class="form-control" name="txt_igv" id="txt_igv" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_icbper">ICBPER: </label>
                                <input type="text" class="form-control" name="txt_icbper" id="txt_icbper" value="0.00" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="txt_total">Total: </label>
                                <input type="text" class="form-control" name="txt_total" id="txt_total" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <br>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="pagar()" ><span class="fa fa-save"></span> Pagar</button>
                </div>
            </div><!-- /.modal-dialog -->
        </div>
    </div>
</div>
