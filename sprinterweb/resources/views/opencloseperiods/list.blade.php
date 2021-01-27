@extends('templates.app')
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal" id="frm_generales" name="frm_generales"
              method="POST" data-route="{{ route('opencloseperiods') }}">
            <div class="x_panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="codigo">Año:</label>
                            </div>
                            <div class="col-sm-3 col-md-3 col-xs-12">
                                <input type="text" class="form-control" id="codigo" name="codigo"
                                       value="{{substr($period->codigo, 0, -2)}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-3">
                                <button type="button" class="form-control btn-primary" id="btn_buscar_per"
                                        name="btn_buscar_per" value="{{ route('list.opencloseperiods') }}">
                                    <span class="fa fa-filter"></span> Filtrar
                                </button>
                            </div>
                        </div>
                        <br>
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

                                <table id="listOpenClosePeriods"
                                       class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                       cellspacing="0" width="100%" role="grid"
                                       aria-describedby="datatable-responsive_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Código
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Descripción
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Compras
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Ventas
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Almacen
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Tesorería
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Contabilidad
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Planillas
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Gestión
                                            Tributaria
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Activos
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
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/ani/opencloseperiods.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableOpenClosePeriods.init('{{ route('list.opencloseperiods') }}');
        });
    </script>
@endsection
