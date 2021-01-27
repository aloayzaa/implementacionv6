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
                                <input type="hidden" value="{{ $process }}" id="process" name="process">
                                <input type="hidden" value="{{ route('list.salescurrentaccounts') }}" id="ruta">
                                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="provider">Cliente:</label>
                                            <select class="form-control select2" id="provider" name="provider">
                                                <option value="">Seleccionar</option>
                                                @foreach($customers  as $customer)
                                                    <option
                                                        value="{{ $customer->id }}">{{ $customer->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="balances">Tipo:</label>
                                            <select class="form-control select2" id="balances" name="balances">
                                                <option value="CS">Saldos</option>
                                                <option value="CM">Movimienos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="date">A la fecha:</label>
                                            <input class="form-control" type="date" id="date" name="date"
                                                   min="{{$period->inicio}}" max="{{$period->final}}"
                                                   value="{{$fecha}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="pending">
                                                <input type="checkbox" id="pending" name="pending" checked> Ver
                                                Pendientes:
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <button type="button" class="form-control btn-primary" id="mostrar"
                                                    name="mostrar"><span
                                                    class="fa fa-play"></span> Mostrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="contenido_scrollauto">
                                <table id="listCurrentAccounts" class="table table-striped table-bordered table-bordered2" width="100%">
                                    <thead class="text-center">
                                    <tr>
                                        <th colspan="6">Documento</th>
                                        <th colspan="3">M.N.</th>
                                        <th colspan="3">M.E.</th>
                                        <th colspan="1">Interés</th>
                                        <th rowspan="2">Glosa</th>
                                        <th rowspan="2">Cuenta</th>
                                    </tr>
                                    <tr>
                                        <th>TD</th>
                                        <th>Número</th>
                                        <th>F. Proceso</th>
                                        <th>F. Emisión</th>
                                        <th>Vencimiento</th>
                                        <th>Mon</th>
                                        <th>Cargos</th>
                                        <th>Abonos</th>
                                        <th>Saldo</th>
                                        <th>Cargos</th>
                                        <th>Abonos</th>
                                        <th>Saldo</th>
                                        <th>M.N.</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="6" bgcolor="#5f9ea0">Totales:</th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th bgcolor="#5f9ea0"></th>
                                        <th colspan="2" bgcolor="#5f9ea0"></th>
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
    <script src="{{ asset('anikama/ani/currentaccounts.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            /*tableListCurrentAccounts.init('{{ route('list.currentaccounts') }}');*/
        });
    </script>
@endsection
