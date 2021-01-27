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
                                    <label for="initialdate">Desde:</label>
                                    <input type="date" class="form-control" id="initialdate" name="initialdate"
                                           value="{{$period->inicio}}" min="{{$period->inicio}}" max="{{$period->final}}">
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="finishdate">Hasta:</label>
                                    <input type="date" class="form-control" id="finishdate" name="finishdate"
                                           value="{{$period->final}}" min="{{$period->inicio}}" max="{{$period->final}}">
                                </div>
                                <div class="col-md-1 col-sm-2 col-xs-4">
                                    <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                            value="{{route('list.seatvalidation')}}">
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
                                <table id="listBalancesAccount" class="table-striped table-bordered table-bordered2 tbl_ctacte"
                                       class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Voucher</th>
                                        <th>Glosa</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Mensaje</th>
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
    <script src="{{ asset('anikama/ani/seatvalidation.js') }}"></script>
    <script>
        $(function () {
            tableSeatValidation.init('{{ route('list.seatvalidation') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
