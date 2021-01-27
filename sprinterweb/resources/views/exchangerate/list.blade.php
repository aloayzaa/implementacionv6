@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="form-group">
                    <form class="form-horizontal" id="frm_generales" name="frm_generales">
                        <div class="row">
                            <label class="control-label col-md-1">
                                Periodo
                            </label>
                            <div class="col-md-2 col-xs-4">
                                <select id="period" name="period" class="form-control select2">
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Setiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>

                            <label class="control-label col-md-1"> Año </label>
                            <div class="col-md-2 col-xs-4">
                                <input type="text" class="form-control" id="year" name="year"
                                       value="{{substr($period->codigo,0,4)}}">
                            </div>

                            <div class="col-md-1 col-xs-4">
                                <button type="button" class="form-control btn-primary" id="mostrar" name="mostrar" disabled
                                        value="{{route('list.exchangerate')}}">Mostrar
                                </button>
                            </div>

                            <div class="col-md-2 col-xs-2">
                                <button type="button" class="form-control btn-danger" id="update_tcambio" disabled>Actualizar T. Cambio</button>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div id="datatable-responsive_wrapper"
                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" name="proceso" id="proceso"
                                   @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                            <input type="hidden" name="route" id="route"
                                   @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                            <input type="hidden" name="var" id="var"
                                   @if(isset($var)) value="{{$var}}" @else value="" @endif/>

                            <table id="listExchangeRate"
                                   class="table table-striped table-bordered dataTable"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Código
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style=""
                                        aria-label="Last name: activate to sort column ascending">Fecha
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style=""
                                        aria-label="Last name: activate to sort column ascending">Compra
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style=""
                                        aria-label="Last name: activate to sort column ascending">Venta
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 15%;"
                                        aria-label="Age: activate to sort column ascending">Estado
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
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/exchangerate.js') }}"></script>
    <script>
        $(function () {
            tableExchangeRate.init('{{ route('list.exchangerate') }}');
        });
        $('#listExchangeRate tbody').on( 'dblclick', 'tr', function () {
            url= '{{route("edit.exchangerate", ":id") }}'
            window.location = url.replace(':id', this.id);
        } );
    </script>
@endsection
