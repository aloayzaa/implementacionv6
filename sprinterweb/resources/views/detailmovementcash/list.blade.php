@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form name="frm_reporte" id="frm_reporte" method="GET">
                    <div class="panel panel-default">
                        <div class="panel panel-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                    <label for="period">Perido:</label>
                                    <select class="form-control"
                                            name="period"
                                            id="period">
                                        <option value="0" disabled selected>Seleccionar..</option>
                                        @foreach($periods as $period)
                                            <option value="{{$period->id}}"
                                                    @if(Session::get('period_id') == $period->id) selected @endif>
                                                {{$period->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-2 col-xs-4">
                                    <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                            value="{{route('list.detailmovementcash')}}">
                                        <span class="fa fa-play"></span> Mostrar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <hr class="reportes">
                                <div class="contenido_scrollauto">
                                    <input type="hidden" name="proceso" id="proceso"
                                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                                    <input type="hidden" name="route" id="route"
                                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                                    <input type="hidden" name="var" id="var"
                                           @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                                    <table id="listDetailMovementCash" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                           class="tablesorter" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">Caja</th>
                                            <th colspan="2">Correlativo</th>
                                            <th rowspan="2">CUO</th>
                                            <th rowspan="2">Número</th>
                                            <th rowspan="2">Fecha</th>
                                            <th rowspan="2">Glosa</th>
                                            <th colspan="2">Cuenta Contable</th>
                                            <th rowspan="2">Ingresos</th>
                                            <th rowspan="2">Egresos</th>
                                            <th rowspan="2">US$</th>
                                            <th colspan="5">Referencia</th>
                                        </tr>
                                        <tr>
                                            <th>Periodo</th>
                                            <th>Número</th>
                                            <th>Código</th>
                                            <th>Denominación</th>
                                            <th>T.D.</th>
                                            <th>Serie</th>
                                            <th>Número</th>
                                            <th>Fecha</th>
                                            <th>Libro</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th bgcolor="#01DFA5">Totales:</th>
                                            <th colspan="8" style="text-align:left;" bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th colspan="5" style="text-align:left;" bgcolor="#01DFA5"></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
    <script src="{{ asset('anikama/ani/detailmovementcash.js') }}"></script>
    <script>
        $(function () {
            tableDetailMovementCash.init('{{ route('list.detailmovementcash') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
