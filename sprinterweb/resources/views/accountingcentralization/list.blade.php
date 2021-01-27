@extends('templates.app')
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal" id="frm_generales" name="frm_generales"
              method="POST" data-route="{{ route('accountingcentralization') }}">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <h2 align="center"><span class="fa fa-gear"></span> Proceso para la generación de
                                voucher contables de los Movimientos de cada
                                Módulo del
                                Sistema</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label for="module">Modulo:</label>
                            <select class="form-control select2" id="module" name="module">
                                <option value="1">ALMACENES</option>
                                <option value="2">VENTAS</option>
                                <option value="3">COMPRAS</option>
                                <option value="4">TESORERIA</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                            <label for="period">Desde:</label>
                            <input type="date" class="form-control" id="initialdate" name="initialdate"
                                   min="{{$period->inicio}}" max="{{$period->final}}" value="{{$period->inicio}}">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                            <label for="finaldate">Hasta:</label>
                            <input type="date" class="form-control" id="finaldate" name="finaldate"
                                   min="{{$period->inicio}}" max="{{$period->final}}" value="{{$period->final}}">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                            <label class="top-check-down" for="period">
                                <input type="checkbox" id="checkctacte" name="checkctacte" disabled> Actualizar
                                Cuenta Corriente
                            </label>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-4">
                            <button type="button" class="form-control btn-primary top-check-down" id="btn_procesar_centralizacion"
                                    name="btn_procesar_centralizacion"><span
                                    class="fa fa-refresh"> Procesar</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="var" name="var" value="{{ $var }}">
                        <div class="col-sm-12">
                            <input type="hidden" name="proceso" id="proceso"
                                   @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                            <input type="hidden" name="route" id="route"
                                   @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                            <input type="hidden" name="var" id="var"
                                   @if(isset($var)) value="{{$var}}" @else value="" @endif/>

                            <table id="listAccountingCentralization"
                                   class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Estado
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Fecha
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Mensaje
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
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/ani/accountingcentralization.js') }}"></script>
@endsection
