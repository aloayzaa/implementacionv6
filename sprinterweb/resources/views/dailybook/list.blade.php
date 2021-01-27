@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form name="frm_reporte" id="frm_reporte" method="GET">
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
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
                                            <label for="initialdate">Desde:</label>
                                            <input class="form-control" type="date" id="initialdate" name="initialdate"
                                                   min="{{$period->inicio}}" max="{{$period->final}}" value="{{$period->inicio}}"
                                                   required>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label for="finishdate">Hasta:</label>
                                            <input class="form-control" type="date" id="finishdate" name="finishdate"
                                                   min="{{$period->inicio}}" max="{{$period->final}}" value="{{$period->final}}"
                                                   required>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label for="type">Tipo:</label>
                                            <select class="form-control" name="type" id="type">
                                                <option value="A" selected>Analítico</option>
                                                <option value="R">Resumen por día</option>
                                                <option value="M">Resumen por mes</option>
                                                <option value="S">Simplificado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label for="currency">Moneda:</label>
                                            <select class="form-control" name="currency" id="currency">
                                                @foreach($currencies as $currency)
                                                    <option value="{{$currency->id}}">{{$currency->codigo}}
                                                        | {{$currency->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-12 form-group">
                                            <button type="button" class="form-control btn-primary top-check-down" id="btnmostrar" name="btnmostrar"
                                                    value="{{route('list.dailybook')}}">
                                                <span class="fa fa-play"></span> Mostrar
                                            </button>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row">
                                        <input type="hidden" name="proceso" id="proceso"
                                               @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                                        <input type="hidden" name="route" id="route"
                                               @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                                        <input type="hidden" name="var" id="var"
                                               @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                                        <hr class="reportes">
                                        <div class="contenido_scrollauto">
                                            <table id="listDailyBook" class="table table-striped table-bordered table-bordered2
                                             tbl_ctacte" class="tablesorter" width="100%">
                                                <thead>
                                                <tr>
                                                    {{--
                                                    @if($tipo!='S')
                                                        <th colspan="4">Documento</th>
                                                        <th colspan="2">CUO</th>
                                                        <th rowspan="2">Glosa</th>
                                                        <th rowspan="2">Cuenta</th>
                                                        <th rowspan="2">Denominación</th>
                                                        <th colspan="4">Referencia</th>
                                                        <th rowspan="2">Debe</th>
                                                        <th rowspan="2">Haber</th>
                                                        <th colspan="2">Centro Costo</th>
                                                        <th colspan="2">Operación</th>
                                                    @else
                                                        <th rowspan="1">Fecha</th>
                                                        <th rowspan="1">Glosa</th>
                                                        <th rowspan="1">C10</th>
                                                        <th rowspan="1">C12</th>
                                                        <th rowspan="1">C16</th>
                                                        <th rowspan="1">C20</th>
                                                        <th rowspan="1">C21</th>
                                                        <th rowspan="1">C33</th>
                                                        <th rowspan="1">C38</th>
                                                        <th rowspan="1">C39</th>
                                                        <th rowspan="1">C4011</th>
                                                        <th rowspan="1">C4017</th>
                                                        <th rowspan="1">C402</th>
                                                        <th rowspan="1">C42</th>
                                                        <th rowspan="1">C46</th>
                                                        <th rowspan="1">C50</th>
                                                        <th rowspan="1">C58</th>
                                                        <th rowspan="1">C59</th>
                                                        <th rowspan="1">C60</th>
                                                        <th rowspan="1">C61</th>
                                                        <th rowspan="1">C62</th>
                                                        <th rowspan="1">C63</th>
                                                        <th rowspan="1">C65</th>
                                                        <th rowspan="1">C66</th>
                                                        <th rowspan="1">C67</th>
                                                        <th rowspan="1">C68</th>
                                                        <th rowspan="1">C69</th>
                                                        <th rowspan="1">C96</th>
                                                        <th rowspan="1">C97</th>
                                                        <th rowspan="1">C70</th>
                                                        <th rowspan="1">C75</th>
                                                        <th rowspan="1">C77</th>
                                                        <th rowspan="1">C79</th>
                                                    @endif
                                                    --}}
                                                    <th colspan="4">Documento</th>
                                                    <th colspan="2">CUO</th>
                                                    <th rowspan="2">Glosa</th>
                                                    <th rowspan="2">Cuenta</th>
                                                    <th rowspan="2">Denominación</th>
                                                    <th colspan="4">Referencia</th>
                                                    <th rowspan="2">Debe</th>
                                                    <th rowspan="2">Haber</th>
                                                    <th colspan="2">Centro Costo</th>
                                                    <th colspan="2">Operación</th>
                                                </tr>
                                                    {{--
                                                    @if($tipo!='S')
                                                        <tr>
                                                            <th>Subdiario</th>
                                                            <th>Periodo</th>
                                                            <th>Operación</th>
                                                            <th>Fecha</th>
                                                            <th>ID</th>
                                                            <th>Número</th>
                                                            <th>Fecha</th>
                                                            <th>Libro</th>
                                                            <th>Número</th>
                                                            <th>Documento</th>
                                                            <th>Código</th>
                                                            <th>Descripción</th>
                                                            <th>Número</th>
                                                            <th>Fecha</th>
                                                        </tr>
                                                    @endif
                                                    --}}
                                                    <th>Subdiario</th>
                                                    <th>Periodo</th>
                                                    <th>Operación</th>
                                                    <th>Fecha</th>
                                                    <th>ID</th>
                                                    <th>Número</th>
                                                    <th>Fecha</th>
                                                    <th>Libro</th>
                                                    <th>Número</th>
                                                    <th>Documento</th>
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Número</th>
                                                    <th>Fecha</th>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    {{--
                                                    <th bgcolor="#01DFA5">Totales:</th>
                                                    @if($tipo!='S')
                                                        <th colspan="12" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                        <th bgcolor="#01DFA5"></th>
                                                        <th bgcolor="#01DFA5"></th>
                                                        <th colspan="4" bgcolor="#01DFA5"></th>
                                                    @else
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
                                                    @endif
                                                    --}}
                                                    <th bgcolor="#01DFA5">Totales:</th>
                                                    <th colspan="12" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                    <th bgcolor="#01DFA5"></th>
                                                    <th bgcolor="#01DFA5"></th>
                                                    <th colspan="4" bgcolor="#01DFA5"></th>
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
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/dailybook.js') }}"></script>
    <script>
        $(function () {
            tableDailyBook.init('{{ route('list.dailybook') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
