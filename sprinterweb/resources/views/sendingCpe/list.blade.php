@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
    <style>
        .color_listado {
            background-color: #ccc!important;
        }
    </style>
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div id="datatable-responsive_wrapper"
                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                    <div class="panel panel-default">

                        <div class="panel-body">

                            <input type="hidden" name="var" id="var" value="{{ $var }}"/>
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                            <div class="row">

                                <div class="col-sm-2 col-xs-12">
                                    <label for="txt_desde">Desde:</label>
                                    <input type="date" id="txt_desde" name="txt_desde" class="form-control" value="{{$today}}">
                                </div>

                                <div class="col-sm-2 col-xs-12">
                                    <label for="txt_hasta">Desde:</label>
                                    <input type="date" id="txt_hasta" name="txt_hasta" class="form-control" value="{{$today}}">
                                </div>

                                <div class="col-sm-2 col-xs-12">
                                    <label for="cbo_tipo_doc">Tipo documento:</label>
                                    <select name="cbo_tipo_doc" id="cbo_tipo_doc" class="select2">
                                        <option value="0">Todos</option>
                                        <option value="F">Facturas</option>
                                        <option value="B">Boletas</option>
                                        <option value="R">Comprob.Retención</option>
                                        <option value="T">Guías Remisión</option>
                                    </select>
                                </div>

                                <div class="col-sm-1 col-xs-12">
                                    <button type="button" id="btn_mostrar" class="btn btn-xs btn-primary">Mostrar</button>
                                </div>

                                <div class="col-sm-2 col-xs-12">
                                    <label for="cbo_tipo_procesamiento">Tipo Procesamiento:</label>
                                    <select name="cbo_tipo_procesamiento" id="cbo_tipo_procesamiento" class="select2">
                                        <option value="S">Envío Sunat/OSE</option>
                                        <option value="C">Recuperar CDR + Enviar Correo</option>
                                        <option value="E">Enviar Correo</option>
                                    </select>
                                </div>


                                <div class="col-sm-2 col-xs-12">
                                    <label for="chk_marcar_todos"><input type="checkbox" value="" id="chk_marcar_todos" name="chk_marcar_todos"> seleccionar todos</label>
                                </div>

                                <div class="col-sm-1 col-xs-12">
                                    <button type="button" id="btn_procesar" name="btn_procesar" class="btn btn-xs btn-primary">Procesar</button>
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">

                                    <table id="tableSendingCpe" class="table display nowrap table-striped table-hover table-bordered" width="100%">

                                        <thead>

                                            <tr>
                                                <th rowspan='2'>Item</th>
                                                <th colspan='4' style="text-align:center">Documento</th>
                                                <th colspan='2' style="text-align:center">Cliente</th>
                                                <th rowspan='2'>Mon</th>
                                                <th rowspan='2'>Total</th>
                                                <th rowspan='2'>Estado</th>
                                                <th colspan='4' style="text-align:center">Procesado</th>
                                                <th rowspan='2'>Enviar</th>
                                            </tr>

                                            <tr>
                                                <th>Fecha</th>
                                                <th>TD</th>
                                                <th>Serie</th>
                                                <th>Número</th>
                                                <th>Código</th>
                                                <th>Nombre / Razón Social</th>
                                                <th>F.Envío</th>
                                                <th>F.Recepción</th>
                                                <th>Respuesta</th>
                                                <th>Tipo Servidor</th>
                                            </tr>
                                        </thead>

                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5">Totales:</th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"><p id="total_listado"></p></th>
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
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/ani/sendingcpe.js') }}"></script>

@endsection
