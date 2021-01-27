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
                                Periodo
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <select class="form-control select2" id="period" name="period">
                                    @foreach($periods as $period)
                                        <option value="{{$period->id}}" @if($period->id == Session::get('period_id')) selected @endif>
                                            {{$period->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="control-label col-md-1">
                                Tipo
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <select class="form-control select2" id="type" name="type">
                                    <option value="H">RECIBO HONORARIO</option>
                                    <option value="R">COMPROBANTES RETENCIÓN</option>
                                    <option value="P">COMPROBANTES PERCEPCIÓN</option>
                                    <option value="L">LIQUIDACIÓN COMPRAS</option>
                                </select>
                            </div>

                            <div class="col-md-1 col-xs-2">
                                <button type="button" class="form-control btn-primary"
                                        value="{{route('list.monthlyincometax')}}" name="mostrar"
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
                            <table id="listMonthlyIncomeTax"
                                   class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">R.U.C.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Ap.Paterno
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Ap. Materno
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Nombres
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="3" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Valor
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Ret.
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="2" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">IES
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Primer
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="4" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Valor
                                    </th>
                                </tr>
                                <tr role="row">
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
                                        aria-label="First name: activate to sort column descending">Fecha
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Bruto
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Ret. Renta
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Ret. Pensión
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Neto
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
    <script src="{{ asset('anikama/ani/monthlyincometax.js') }}"></script>
    <script>
        $(function () {
            tableMonthlyIncomeTax.init('{{ route('list.monthlyincometax') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
