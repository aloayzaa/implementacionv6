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
                                <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                    <label for="period">Periodo:</label>
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
                                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                    <label for="currency">Cuenta:</label>
                                    <select class="form-control select2" name="currency" id="currency">
                                        <option value="12">12/13 Clientes/Relacionadas</option>
                                        <option value="14">14 Personal</option>
                                        <option value="16">16/17 Diversas Terceros/Relacionadas</option>
                                        <option value="18">18 Anticipos</option>
                                        <option value="19">19 Cobranza Dudosa</option>
                                        <option value="28">28 Existencias por Recibir</option>
                                        <option value="30">30 Inv.Mobiliarias</option>
                                        <option value="31">31 Inv.Inmobiliarias</option>
                                        <option value="32">32 Arr.Financiero</option>
                                        <option value="33">33 Inm.Maq.y Equipos</option>
                                        <option value="34">34 Intangibles</option>
                                        <option value="35">35 Activos Biológicos</option>
                                        <option value="36">36 Activo Inmovilizado</option>
                                        <option value="37">37/49 Activo/Pasivo Diferido</option>
                                        <option value="38">38 Otros Activos</option>
                                        <option value="39">39 Depreciación</option>
                                        <option value="40">40 Tributos</option>
                                        <option value="41">41 Remuneraciones</option>
                                        <option value="42">42/43 Proveedores/Relacionadas</option>
                                        <option value="44">44 Accionistas</option>
                                        <option value="45">45 Financieras</option>
                                        <option value="46">46/47 Diversas Terceros/Relacionadas</option>
                                        <option value="47">47 Beneficios Sociales de los Trabajadores</option>
                                        <option value="50">50 Capital</option>
                                        <option value="51">51 Acciones</option>
                                        <option value="52">52 Capital Adicional</option>
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-2 col-xs-4">
                                    <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                            value="{{route('list.currentaccountreport')}}">
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
                                        <th colspan="3">Tercero</th>
                                        <th colspan="5">Comprobante</th>
                                        <th colspan="2">Saldo Cta. Cte.</th>
                                        <th colspan="2">Saldo Contable</th>
                                        <th colspan="2">Diferencia</th>
                                    </tr>
                                    <tr>
                                        <th>T.D.</th>
                                        <th>Número</th>
                                        <th>Razon Social</th>
                                        <th>T.D.</th>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Vencimiento</th>
                                        <th>Moneda</th>
                                        <th>M.N.</th>
                                        <th>M.E.</th>
                                        <th>M.N.</th>
                                        <th>M.E.</th>
                                        <th>M.N.</th>
                                        <th>M.E.</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        <th colspan="8" style="text-align:left;" bgcolor="#01DFA5"></th>
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
    <script src="{{ asset('anikama/ani/currentaccountreport.js') }}"></script>
    <script>
        $(function () {
            tableCurrentAccountReport.init('{{ route('list.currentaccountreport') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
