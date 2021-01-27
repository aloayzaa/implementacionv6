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
                        <div class="row">
                            <form name="frm_reporte" id="frm_reporte" method="GET">
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                    <label  for="initialdate">Desde:</label>
                                    <input class="form-control" type="date" value="{{$period->inicio}}"
                                           min="{{$period->inicio}}" max="{{$period->final}}" name="initialdate"
                                           id="initialdate">
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                    <label for="finishdate">Hasta:</label>
                                    <input class="form-control" type="date" value="{{$period->final}}"
                                           min="{{$period->inicio}}" max="{{$period->final}}" name="finishdate"
                                           id="finishdate">
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                    <label for="currency">Moneda:</label>
                                    <select class="form-control select2" id="currency" name="currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}">{{$currency->codigo}}
                                                | {{$currency->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                    <label for="digits">Digitos:</label>
                                    <select id="digits" name="digits" class="form-control select2">
                                        <option value="2">Dos</option>
                                        <option value="3">Tres</option>
                                        <option value="4">Cuatro</option>
                                        <option value="20">Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12 checkbox" style="margin-top: 30px">
                                    <label class="" for="period">
                                        <input type="checkbox" id="checkclosing" name="checkclosing"> Incluir Cierre
                                    </label>
                                </div>
                                <div class="col-md-1 col-sm-2 col-xs-4">
                                    <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                            value="{{route('list.checkingbalance')}}">
                                        <span class="fa fa-play"></span> Mostrar
                                    </button>
                                </div>
                            </form>
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
                                <table id="listCashBanks" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                       class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Cuenta</th>
                                        <th rowspan="2">Descripción</th>
                                        <th colspan="2">Inicial</th>
                                        <th colspan="2">Movimiento</th>
                                        <th colspan="2">Saldo</th>
                                        <th colspan="2">Balance</th>
                                        <th colspan="2">Naturalesa</th>
                                        <th colspan="2">Función</th>
                                    </tr>
                                    <tr>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Pérdida</th>
                                        <th>Ganancia</th>
                                        <th>Pérdida</th>
                                        <th>Ganancia</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th bgcolor="#01DFA5">Totales:</th>
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
    <script src="{{ asset('anikama/ani/checkingbalance.js') }}"></script>
    <script>
        $(function () {
            tableCheckingBalance.init('{{ route('list.checkingbalance') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
