@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form name="frm_reporte" id="frm_reporte" method="GET">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <label for="financialstate">Cuenta:</label>
                                    <select class="form-control select2" id="financialstate" name="financialstate">
                                        @foreach($pcgs as $pcg)
                                            <option value="{{$pcg->id}}">{{$pcg->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="year">Año:</label>
                                    <input type="text" class="form-control" id="year" name="year" value="2019">
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="currency">Moneda:</label>
                                    <select class="form-control select2" id="currency" name="currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}">{{$currency->codigo}}
                                                | {{$currency->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-2 col-xs-4">
                                    <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                            value="{{route('list.financialstate')}}">
                                        <span class="fa fa-play"></span> Mostrar
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <hr class="reportes">
                            <div class="contenido_scrollauto">
                                <input type="hidden" name="proceso" id="proceso"
                                       @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                                <input type="hidden" name="route" id="route"
                                       @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                                <input type="hidden" name="var" id="var"
                                       @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                                <table id="listBalancesAccount" class="table table-striped table-bordered table-bordered2
                                tbl_ctacte" class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Mes</th>
                                        <th rowspan="2">Cargo</th>
                                        <th rowspan="2">Abono</th>
                                        <th colspan="2">Saldo</th>
                                    </tr>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Acumulado</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/balancesaccount.js') }}"></script>
    <script>
        $(function () {
            tableBalancesAccount.init('{{ route('list.balancesaccount') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
