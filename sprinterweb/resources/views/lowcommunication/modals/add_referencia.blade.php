<div class="modal fade" id="modal_referencia" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><span class="fa fa-check"></span>Aplicación de Documentos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">Tercero</div>
                            <div class="panel-body">
                                <label for="period">Codigo / Razón Social:</label>
                                <select class="form-control select2" name="tercero_id_refe" id="tercero_id_refe">
                                    <option value="">-Seleccionar-</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">Documento</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <label for="period">TD</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <label for="period">Serie</label>
                                        <input type="text" name="txtserie" id="txtserie" class="form-control">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <label for="period">Número</label>
                                        <input type="text" name="txtnumero" id="txtnumero" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">Fecha</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <label for="period">Desde</label>
                                        <input type="date" name="txtdesde" id="txtdesde" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <label for="period">Hasta</label>
                                        <input type="date" name="txthasta" id="txthasta" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <button class="btn btn-primary btn-sm" id="mostrar" style="float:right">Mostrar Datos</button>
                            <div style="float:right">
                                <input type="checkbox"  name="ckbpendientes" id="ckbpendientes">Ver sólo documentos pendientes
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" role="tab" data-toggle="tab" aria-expanded="true">Documentos por Cobrar</a>
                            </li>
                            <li role="presentation" class="ocultar">
                                <a href="#tab_content2" role="tab" data-toggle="tab" aria-expanded="true">Documentos por Pagar</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in detallepva" id="tab_content1"
                                aria-labelledby="detalle">
                                @include('lowcommunication.tabs.documentosxcobrar')
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div>
                                <input type="checkbox"  name="ckbtodos" id="ckbtodos">Marcar / Desmascar Todos
                            </div>
                            <div class="col-md-2">
                                <input type="text" value="0.00" name="txttotal" id="txttotal" class="form-control ocultar" style="float: right">
                                <input type="hidden" name="txtnum1" id="txtnum1" class="form-control ocultar">
                                <input type="hidden" name="txtid" id="txtid" class="form-control">
                                <input type="hidden" name="txtoperacion" id="txtoperacion" class="form-control">
                                <input type="hidden" name="txtcambio" id="txtcambio" class="form-control">
                                <input type="hidden" name="ctipomon" id="ctipomon" class="form-control">
                                <input type="hidden" name="corigen" id="corigen" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="btn_aceptar" id="btn_aceptar" class="btn btn-success">
                    <span class="fa fa-ban"></span> Aceptar
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span class="fa fa-ban"></span> Cerrar
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


