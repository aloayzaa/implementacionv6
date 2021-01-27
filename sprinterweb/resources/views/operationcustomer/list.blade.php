@extends('templates.app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="frm_reporte" id="frm_reporte" method="GET">
                <input type="hidden" value="{{$var}}" id="var" name="var">
                <div class="x_content">
                    <div class="form-group">
                        <div class="row">
                            <label class="control-label col-md-1">
                                Año
                            </label>
                            <div class="col-md-1 col-xs-4">
                                <input type="number" id="year" name="year" class="form-control"
                                       value="{{substr($period->codigo, 0, 4)}}">
                            </div>

                            <label class="control-label col-md-1">
                                Moneda
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <select class="form-control select2" id="type" name="type">
                                    <option id="P">COSTOS/GASTOS</option>
                                    <option id="C">INGRESOS</option>
                                </select>
                            </div>

                            <div class="col-md-2 col-xs-12 checkbox" style="margin-top: 2px">
                                <label class="" for="period">
                                    <input type="checkbox" id="detailed" name="detailed">Detallado
                                </label>
                            </div>

                            <div class="col-md-1 col-xs-2">
                                <button type="button" class="form-control btn-primary"
                                        value="{{route('list.operationcustomer')}}" name="mostrar"
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
                            <table id="listOPerationCustomer"
                                   class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Contador
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Declarante
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Periodo
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Tipo
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Número
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Importe
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="2" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Apellidos
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Primer
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Segundo
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Razón Social
                                    </th>
                                </tr>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">T.D.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Documento
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Pers
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Doc
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Documento
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Paterno
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Materno
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Nombre
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Nombre
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
    <script src="{{ asset('anikama/ani/operationcustomer.js') }}"></script>
    <script>
        $(function () {
            tableOperationCustomer.init('{{ route('list.operationcustomer') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
