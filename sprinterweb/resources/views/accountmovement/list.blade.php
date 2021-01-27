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
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <label for="type">Cuenta:</label>
                                <select class="form-control select2" id="type" name="type">
                                    <option value="0" disabled selected>Seleccionar..</option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}">{{$account->codigo}}
                                            | {{$account->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="initialdate">Desde:</label>
                                <input class="form-control"
                                       type="date"
                                       id="initialdate"
                                       name="initialdate"
                                       min="{{$period->inicio}}"
                                       max="{{$period->final}}"
                                       value="{{$period->inicio}}"
                                       required>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="finishdate">Hasta:</label>
                                <input class="form-control"
                                       type="date"
                                       id="finishdate"
                                       name="finishdate"
                                       min="{{$period->inicio}}"
                                       max="{{$period->final}}"
                                       value="{{$period->final}}"
                                       required>
                            </div>
                            <div class="col-md-1 col-sm-2 col-xs-4">
                                <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar"
                                        name="btnmostrar" value="{{route('list.accountmovement')}}">
                                    <span class="fa fa-play"></span> Mostrar
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <hr class="reportes">
                            <div class="contenido_scrollauto">
                                <table id="listAccountMovement" class="table table-striped table-bordered table-bordered2
                                tbl_ctacte" class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Fecha</th>
                                        <th rowspan="2">Voucher</th>
                                        <th rowspan="2">Cuenta</th>
                                        <th rowspan="2">Descripción</th>
                                        <th colspan="2">C. Costo</th>
                                        <th rowspan="2">glosa</th>
                                        <th colspan="2">M.N.</th>
                                        <th colspan="2">M.E.</th>
                                        <th rowspan="2">O.P</th>
                                        <th colspan="4">Referencia</th>
                                        <th colspan="2">Asiento Dest.</th>
                                    </tr>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Documento</th>
                                        <th>Fecha</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        <th colspan="6" style="text-align:left;" bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th colspan="7" bgcolor="#01DFA5"></th>
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
    <script src="{{ asset('anikama/ani/accountmovement.js') }}"></script>
    <script>
        $(function () {
            tableAccountMovement.init('{{ route('list.accountmovement') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
