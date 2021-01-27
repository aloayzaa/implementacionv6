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
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
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
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                    <label class="control-label col-md-1" for="period">
                                        Desde
                                    </label>
                                    <input class="form-control" type="date" id="initialdate" name="initialdate"
                                           min="{{$period->inicio}}" max="{{$period->final}}" value="{{$period->inicio}}"
                                           required>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label class="control-label col-md-1" for="period">
                                        Hasta
                                    </label>
                                    <input class="form-control" type="date" id="finishdate" name="finishdate"
                                           min="{{$period->inicio}}" max="{{$period->final}}" value="{{$period->final}}"
                                           required>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label class="control-label col-md-1" for="period">
                                        Tipo
                                    </label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="A" selected>Analítico</option>
                                        <option value="R">Resumen</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label class="control-label col-md-1" for="period">
                                        Moneda
                                    </label>
                                    <select class="form-control" name="currency" id="currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}">{{$currency->codigo}}
                                                | {{$currency->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-2 col-xs-4">
                                    <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                            value="{{route('list.ledger')}}">
                                        <span class="fa fa-play"></span> Mostrar
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <hr class="reportes">
                                <div class="contenido_scrollauto">
                                    <input type="hidden" name="proceso" id="proceso"
                                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                                    <input type="hidden" name="route" id="route"
                                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                                    <input type="hidden" name="var" id="var"
                                           @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                                    <table id="listLedger" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                           class="tablesorter" width="100%">
                                        <thead>
                                        <tr>
                                            {{--
                                            @if($tipo=='A')
                                                <th rowspan="2">Cuenta</th>
                                                <th rowspan="2">Descripción</th>
                                            @endif
                                            <th rowspan="2">Operación</th>
                                            <th rowspan="2">Fecha</th>
                                            <th rowspan="2">Glosa</th>
                                            <th rowspan="2">Debe</th>
                                            <th rowspan="2">Haber</th>
                                            <th colspan="2">Centro Costo</th>
                                            <th colspan="2">Operación</th>
                                            --}}
                                            <th rowspan="2">Cuenta</th>
                                            <th rowspan="2">Descripción</th>
                                            <th rowspan="2">Operación</th>
                                            <th rowspan="2">Fecha</th>
                                            <th rowspan="2">Glosa</th>
                                            <th rowspan="2">Debe</th>
                                            <th rowspan="2">Haber</th>
                                            <th colspan="2">Centro Costo</th>
                                            <th colspan="2">Operación</th>
                                        </tr>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Fecha</th>
                                            <th>Número</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            {{--
                                            <th bgcolor="#01DFA5">Totales:</th>
                                            @if($tipo=='A')
                                                <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
                                            @else
                                                <th colspan="2" style="text-align:left;" bgcolor="#01DFA5"></th>
                                            @endif
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
                                            --}}
                                            <th bgcolor="#01DFA5">Totales:</th>
                                            <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th bgcolor="#01DFA5"></th>
                                            <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
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
    <script src="{{ asset('anikama/ani/ledger.js') }}"></script>
    <script>
        $(function () {
            tableLedger.init('{{ route('list.ledger') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
