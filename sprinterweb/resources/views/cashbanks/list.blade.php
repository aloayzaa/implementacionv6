@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel panel-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <label for="period">Perido:</label>
                                <select class="form-control select2"
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
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <label for="currency">Moneda:</label>
                                <select class="form-control select2"
                                        name="currency"
                                        id="currency">
                                    <option value="O" selected>Origen</option>
                                    <option value="N">Soles</option>
                                    <option value="E">Dólares</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-2 col-xs-4">
                                <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                        value="{{route('list.cashbanks')}}">
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
                                <table id="listCashBanks" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                       class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Cuenta</th>
                                        <th rowspan="2">Banco</th>
                                        <th rowspan="2">Nº Cuenta</th>
                                        <th rowspan="2">Moneda</th>
                                        <th colspan="2">Saldo</th>
                                    </tr>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Deudor</th>
                                        <th>Acreedor</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
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
    <script src="{{ asset('anikama/ani/cashbanks.js') }}"></script>
    <script>
        $(function () {
            tableCashBanks.init('{{ route('list.cashbanks') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
