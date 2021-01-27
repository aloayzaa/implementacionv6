@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="frm_reporte" id="frm_reporte" method="GET">
                <div class="x_content">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-1 col-xs-12">
                                        <div class="form-group">
                                            <label for="currency">Moneda:</label>
                                            <select class="form-control"
                                                    id="currency"
                                                    name="currency">
                                                <option value="O">Origen</option>
                                                <option value="A">Ambas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="">Tipo:</label><br>
                                            <input type="radio" id="rdtype" name="rdtype" checked value="1"> Lista
                                            <input type="radio" id="rdtype" name="rdtype" value="2"> Detalle
                                            <input type="radio" id="rdtype" name="rdtype" value="0"> Venta
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <label for="type">Por:</label>
                                        <select class="form-control"
                                                id="type"
                                                name="type" onchange="typedata()">
                                            <option value="C">Cliente</option>
                                            <option value="B">Producto</option>
                                            <option value="M">Marca</option>
                                            <option value="F">Familia Producto</option>
                                            <option value="P">Punto Venta</option>
                                            <option value="T">Tipo Venta</option>
                                            <option value="O">Centro Costo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <label for="account">Seleccione Cuenta:</label>
                                        <select class="form-control select2"
                                                id="account"
                                                name="account">
                                            <option selected disabled>Seleccionar..</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-xs-4" style="margin-top: 20px;">
                                        <button type="button" onclick="table()" class="form-control btn-primary"><span class="fa fa-play"></span> Mostrar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <hr class="reportes">
                                    <div class="contenido_scrollauto">
                                        <input type="hidden" value="{{$var}}" id="var" name="var">
                                        <table id="listSalesDetails" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                               class="tablesorter" width="100%">
                                            <thead>
                                            <tr>
                                                {{--
                                                <th colspan="2">Sucursal</th>
                                                <th colspan="2">Pto. Venta</th>
                                                <th colspan="2">T. Venta</th>
                                                <th colspan="2">T. Afectación IGV</th>
                                                <th rowspan="2">Certamen</th>
                                                <th colspan="4">Documento</th>
                                                <th colspan="1">Guia Rem.</th>
                                                @if($rb_tipo==2)
                                                    <th colspan="4">Producto</th>
                                                    <th colspan="2">Familia</th>
                                                    <th rowspan="2">Cantidad</th>
                                                @endif
                                                <th colspan="2">Cliente</th>
                                                @if($rb_tipo==2)
                                                    <th colspan="2">Otros Datos</th>
                                                @endif
                                                <th colspan="4">Moneda Nacional</th>
                                                <th rowspan="2">Costo</th>
                                                <th rowspan="2">Utilidad</th>
                                                <th rowspan="2">Margen Com. %</th>
                                                <th rowspan="2">Rentab. %</th>
                                                <th colspan="4">Moneda Extranjera</th>
                                                <th rowspan="2">Costo</th>
                                                <th rowspan="2">Utilidad</th>
                                                <th rowspan="2">Margen Com. %</th>
                                                <th rowspan="2">Rentab. %</th>
                                                @if($rb_tipo==2)
                                                    <th colspan="2">Cuenta</th>
                                                @endif
                                                <th colspan="3">Vendedor</th>
                                                <th colspan="2">Cond. Pago</th>
                                                <th rowspan="2">Glosa</th>
                                                --}}
                                                <th colspan="2">Sucursal</th>
                                                <th colspan="2">Pto. Venta</th>
                                                <th colspan="2">T. Venta</th>
                                                <th colspan="2">T. Afectación IGV</th>
                                                <th rowspan="2">Certamen</th>
                                                <th colspan="4">Documento</th>
                                                <th colspan="1">Guia Rem.</th>
                                                <th colspan="2">Cliente</th>
                                                <th colspan="4">Moneda Nacional</th>
                                                <th rowspan="2">Costo</th>
                                                <th rowspan="2">Utilidad</th>
                                                <th rowspan="2">Margen Com. %</th>
                                                <th rowspan="2">Rentab. %</th>
                                                <th colspan="4">Moneda Extranjera</th>
                                                <th rowspan="2">Costo</th>
                                                <th rowspan="2">Utilidad</th>
                                                <th rowspan="2">Margen Com. %</th>
                                                <th rowspan="2">Rentab. %</th>
                                                <th colspan="3">Vendedor</th>
                                                <th colspan="2">Cond. Pago</th>
                                                <th rowspan="2">Glosa</th>
                                            </tr>
                                            <tr>
                                                {{--
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Número</th>
                                                <th>Fecha</th>
                                                <th>Mon</th>
                                                <th>T. Cambio</th>
                                                <th>Sal. Almacén</th>
                                                @if($rb_tipo==2)
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>U.M</th>
                                                    <th>Marca</th>
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                @endif
                                                <th>Código</th>
                                                <th>Nombre/R. Social</th>
                                                @if($rb_tipo==2)
                                                    <th>Dirección</th>
                                                    <th>Ubigeo</th>
                                                @endif
                                                <th>V.Venta</th>
                                                <th>IGV</th>
                                                <th>Servicio</th>
                                                <th>Total</th>
                                                <th>V.Venta</th>
                                                <th>IGV</th>
                                                <th>Servicio</th>
                                                <th>Total</th>
                                                @if($rb_tipo==2)
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                @endif
                                                <th>Código</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                --}}
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Número</th>
                                                <th>Fecha</th>
                                                <th>Mon</th>
                                                <th>T. Cambio</th>
                                                <th>Sal. Almacén</th>
                                                <th>Código</th>
                                                <th>Nombre/R. Social</th>
                                                <th>V.Venta</th>
                                                <th>IGV</th>
                                                <th>Servicio</th>
                                                <th>Total</th>
                                                <th>V.Venta</th>
                                                <th>IGV</th>
                                                <th>Servicio</th>
                                                <th>Total</th>
                                                <th>Código</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                {{--
                                                <th bgcolor="#01DFA5">Totales:</th>
                                                @if($rb_tipo==2)
                                                    <th colspan="24" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                @else
                                                    <th colspan="15" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                @endif
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
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                @if($rb_tipo==2)
                                                    <th colspan="8" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                @else
                                                    <th colspan="6" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                @endif
                                                --}}
                                                <th bgcolor="#01DFA5">Totales:</th>
                                                <th colspan="15" bgcolor="#01DFA5"></th>
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
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th colspan="6" bgcolor="#01DFA5"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/salesdetails.js') }}"></script>
    <script>
        $(function () {
            tableListSalesDetails.init('{{ route('list.salesdetails') }}');
        });
    </script>
@endsection
