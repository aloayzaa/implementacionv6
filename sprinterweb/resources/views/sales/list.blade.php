@extends('templates.app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="frm_reporte" id="frm_generales" method="GET">
                <input type="hidden" value="{{$var}}" id="var" name="var">
                <div class="x_content">
                    <div class="form-group">
                        <div class="row">
                            <label class="control-label col-md-1">
                                Desde
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <input class="form-control"
                                       type="date"
                                       id="initialdate"
                                       name="initialdate"
                                       min="{{$period->inicio}}"
                                       max="{{$period->final}}"
                                       value="{{$period->inicio}}"
                                       required>
                            </div>

                            <label class="control-label col-md-1">
                                Hasta
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <input class="form-control"
                                       type="date"
                                       id="finishdate"
                                       name="finishdate"
                                       min="{{$period->inicio}}"
                                       max="{{$period->final}}"
                                       value="{{$period->final}}"
                                       required>
                            </div>

                            <label class="control-label col-md-1">
                                Moneda
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <select class="form-control select2" id="currency" name="currency">
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->codigo}}
                                            | {{$currency->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-xs-12 checkbox" style="margin-top: 2px">
                                <label class="" for="period">
                                    <input type="checkbox" id="checkdocuments" name="checkdocuments"> Agrupar Boletas
                                    por Día
                                </label>
                            </div>

                            <div class="col-md-1 col-xs-2">
                                <button type="button" class="form-control btn-primary"
                                        value="{{route('list.sales')}}" name="mostrar"
                                        id="mostrar">
                                    Mostrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="x_content">
                <div id="datatable-responsive_wrapper"
                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="listSales"
                                   class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Correlativo
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">CUO
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Número
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="5" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Documento
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Cliente
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Valor Fact.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Base Imponible
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Operación
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">I.G.V. / I.P.M.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">I.S.C.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Otros
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Total
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Total M.E.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Mon
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">T. Cambio
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="4" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Referencia
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Contrato
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Error 1
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">M. Pago
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Retención
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Valor
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Glosa
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Núm. Asiento
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Usuario
                                    </th>
                                </tr>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Periodo
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Número
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
                                        aria-label="First name: activate to sort column descending">T.D.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Serie
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Número
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">RUC
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Razón Social
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Exportación
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Op. Gravada
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Descuento
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Exoneración
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Inafecta
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">I.G.V.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Descuento
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Impuestos
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Fecha
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">T.D.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Serie
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Número
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Número
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Importe
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Referencia
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <pre></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/sales.js') }}"></script>
    <script>
        $(function () {
            tableListSales.init('{{ route('list.sales') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
