<div class="modal fade" id="myModalAsientoDiario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok"></span> Detalle Provisión
                    por pagar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="txt_id_modelo_modal" id="txt_id_modelo_modal">
                <input type="hidden" name="txt_auxiliar" id="txt_auxiliar">
                <input type="hidden" name="txt_aux_id" id="txt_aux_id">
                <input type="hidden" name="estado_modal" id="estado_modal">

                <div class="form-group">
                    <div class="col-sm-4 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cliente</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control" id="customer" name="customer">
                                <option disabled selected value="0">Seleccionar..</option>
                                @foreach($terceros as $tercero)
                                    <option value="{{$tercero->id}}">{{$tercero->codigo}}
                                        | {{$tercero->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cuenta</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control" id="account" name="account">
                                <option disabled selected value="0">Seleccionar..</option>
                                @foreach($pcgs as $pcg)
                                    <option value="{{$pcg->id}}">{{$pcg->codigo}}
                                        | {{$pcg->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Hasta</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input type="date" class="form-control" name="finaldate" id="finaldate"
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
                            <button type="button" class="form-control" id="btnFiltroRef" name="btnFiltroRef"
                                    value="{{ route('list.reference.bankmovement')}}">Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>

                <table id="listCashMovementReference"
                       class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                       cellspacing="0" width="100%" role="grid"
                       aria-describedby="datatable-responsive_info" style="width: 100%;">
                    <thead>
                    <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 10%;" aria-sort="ascending"
                            aria-label="First name: activate to sort column descending">
                        </th>
                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 40%;" aria-sort="ascending"
                            aria-label="First name: activate to sort column descending">Aplicar
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 40%;"
                            aria-label="Last name: activate to sort column ascending">Documento
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 30%;"
                            aria-label="Age: activate to sort column ascending">Fecha
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 25%;"
                            aria-label="Age: activate to sort column ascending">Vence
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 20%;"
                            aria-label="Age: activate to sort column ascending">Mon
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 20%;"
                            aria-label="Age: activate to sort column ascending">Saldo M.N
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 20%;"
                            aria-label="Age: activate to sort column ascending">Saldo M.E
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                            rowspan="1" colspan="1" style="width: 20%;"
                            aria-label="Age: activate to sort column ascending">Glosa
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Referencia</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control" id="cost" name="cost">
                                <option disabled selected value="0"></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">C. Costo</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control" id="cost" name="cost">
                                <option disabled selected value="0">Seleccionar..</option>
                                @foreach($costos as $costo)
                                    <option value="{{$costo->id}}">{{$costo->codigo}}
                                        | {{$costo->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cargo M.N</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" name="cargomn" id="cargomn">
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Abono M.N</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" name="abonomn" id="abonomn">
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cargo M.E</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" name="cargome" id="cargome" readonly>

                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Abono M.E</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" name="abonome" id="abonome" readonly>
                        </div>
                    </div>
                </div>
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
