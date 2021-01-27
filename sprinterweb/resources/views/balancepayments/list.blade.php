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
                        <div class="col-md-12 col-xs-12">
                            <form name="frm_reporte" id="frm_reporte" method="GET">
                                <input type="hidden" value="{{ route('list.balancepayments') }}" id="ruta">
                                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="date">A la fecha:</label>
                                        <input class="form-control" type="date" id="date" name="date" min="{{$period->inicio}}" max="{{$period->final}}" value="{{$fecha}}" required>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="date">Sucursal</label>
                                        <select name="sucursal" id="sucursal" class="form-control">
                                            <option value="">Todas las sucursales</option>
                                            @foreach($sucursal as $sucursal)
                                                <option value="{{$sucursal->id}}">{{$sucursal->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="date" >Categoría Cta. Cte.</label>
                                        <select name="categoria" id="categoria" class="form-control">
                                            <option value="">Ninguno</option>
                                            @foreach($categctactec as $categctactec)
                                                <option value="{{$categctactec->id}}">{{$categctactec->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="date">Moneda Saldos de</label>
                                        <select name="moneda" id="moneda" class="form-control">
                                            <option value="O">Moneda Origen</option>
                                            <option value="A">Ambas Monedas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <br>
                                        <input type="checkbox" name="detalle" id="detalle">Detallado
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <button type="button" id="mostrar" name="mostrar" class="form-control btn-primary">
                                            <span class="fa fa-play"></span> Mostrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12">
                            <br>
                        </div>
                        <div class="row">
                            <hr class="reportes">
                            <div class="contenido_scrollauto">
                                <table id="listBalancePayments" class="table table-striped table-bordered table-bordered2 tbl_ctacte" class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Código</th>
                                        <th rowspan="2">Nombre o Razón Social</th>
                                        <th colspan="3">Nuevo Soles</th>
                                        <th colspan="3">Dólares Americanos</th>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <th>A Cuenta</th>
                                        <th>Saldo</th>
                                        <th>Total</th>
                                        <th>A Cuenta</th>
                                        <th>Saldo</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/balancepayments.js') }}"></script>
    <script>
        /*$(function () {
            tableListBalancePayments.init('{{ route('list.balancepayments') }}');
        });*/
    </script>
    @include('templates.export_files')
@endsection
