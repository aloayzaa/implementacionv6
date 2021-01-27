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
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                    <label for="finaldate">Hasta:</label>
                                    <input class="form-control" type="date" value="{{$period->final}}"
                                           min="{{$period->inicio}}" max="{{$period->final}}" name="finaldate"
                                           id="finaldate">
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <label for="period">Estado Financiero:</label>
                                    <select class="form-control select2" id="financialstate" name="financialstate">
                                        @foreach($estados as $estado)
                                            <option value="{{$estado->id}}">{{$estado->descripcion}}</option>
                                        @endforeach
                                    </select>
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
                                <div class="col-md-2 col-sm-2 col-xs-12 checkbox" style="margin-top: 30px">
                                    <label class="" for="behavior">
                                        <input type="checkbox" id="behavior" name="behavior"> Comportamiento Mensual
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-12 checkbox" style="margin-top: 30px">
                                    <label class="" for="view">
                                        <input type="checkbox" id="view" name="view" checked disabled> Vista
                                        Acumulada
                                    </label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12 checkbox" style="margin-top: 30px">
                                    <label for="detailed">
                                        <input type="checkbox" id="detailed" name="detailed"> Detallado
                                    </label>
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
                                <table id="listFinancialState" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                       class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        {{--
                                        <th rowspan="1">Descripción</th>
                                        @if($chk_mensual==1)
                                            <th rowspan="1">Inicial</th>
                                            <th rowspan="1">Enero</th>
                                            <th rowspan="1">Febrero</th>
                                            <th rowspan="1">Marzo</th>
                                            <th rowspan="1">Abril</th>
                                            <th rowspan="1">Mayo</th>
                                            <th rowspan="1">Junio</th>
                                            <th rowspan="1">Julio</th>
                                            <th rowspan="1">Agosto</th>
                                            <th rowspan="1">Setiembre</th>
                                            <th rowspan="1">Octubre</th>
                                            <th rowspan="1">Noviembre</th>
                                            <th rowspan="1">Diciembre</th>
                                        @endif
                                        <th rowspan="1">Importe</th>
                                        --}}
                                        <th rowspan="1">Descripción</th>
                                        <th rowspan="1">Importe</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        {{--
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        @if($chk_mensual==1)
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                        @endif
                                        <th bgcolor="#01DFA5"></th>
                                        --}}
                                        <th bgcolor="#01DFA5">Totales:</th>
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
    <script src="{{ asset('anikama/ani/financialstate.js') }}"></script>
    <script>
        $(function () {
            tableFinancialState.init('{{ route('list.financialstate') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
