@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <form class="" id="form_kardex">
                                <input type="hidden" id="var" name="var" value="{{ $var }}">
                                <input type="hidden" name="proceso" id="proceso"
                                       @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                                <input type="hidden" name="route" id="route"
                                       @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="buy">Seleccione un Producto: </label>
                                            <select class="form-control select2" name="product_id" id="product_id"
                                                    name="buy"
                                                    id="buy">
                                                @foreach($productos as $producto)
                                                    <option value="{{$producto->id}}">
                                                        {{$producto->descripcion}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="txt_um">Um:</label>
                                            <input type="text" class="form-control" id="txt_um" name="txt_um"
                                                   readonly="true" placeholder="Ingrese UM">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="txt_desde">Desde:</label>
                                            <input type="date" class="form-control" id="txt_desde" name="txt_desde" data-inputmask="'mask': '99/99/9999'"
                                                   placeholder="00/00/0000" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="txt_hasta">Hasta:</label>
                                            <input type="date" class="form-control" id="txt_hasta" name="txt_hasta" data-inputmask="'mask': '99/99/9999'"
                                                   placeholder="00/00/0000" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12 control-m-top-15">
                                        <button class="btn btn-info mb-2" id="btn_showData" name="btn_showData"><span class="fa fa-play">
                                            </span>  Mostrar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <hr class="reportes">
                                    <div class="contenido_scrollauto">
                                        <table id="tableProductKardex" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                               class="tablesorter" width="100%">
                                            <thead>
                                            <tr>
                                                <th rowspan="2">Alm.</th>
                                                <th colspan="2">Operación</th>
                                                <th colspan="1">Documento</th>
                                                <th colspan="3">Cantidades</th>
                                                <th colspan="4">Importe M.N.</th>
                                                <th colspan="4">Importe M.E.</th>
                                                <th rowspan="2">Tipo Mov.</th>
                                                <th rowspan="2">Glosa</th>
                                                <th colspan="2">Tercero</th>
                                                <th colspan="3">Destino</th>
                                            </tr>
                                            <tr>
                                                <th>Numero</th>
                                                <th>Fecha</th>
                                                <th>Ord. Prod.</th>
                                                <th>Ingresos</th>
                                                <th>Salidas</th>
                                                <th>Saldo</th>
                                                <th>P.Unit.</th>
                                                <th>Ingresos</th>
                                                <th>Salidas</th>
                                                <th>Saldo</th>
                                                <th>P.Unit.</th>
                                                <th>Ingresos</th>
                                                <th>Salidas</th>
                                                <th>Saldo</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>C. Costo</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th bgcolor="#01DFA5">Totales:</th>
                                                <th colspan="3" style="text-align:left;" bgcolor="#01DFA5"></th>
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
                                                <th colspan="5" bgcolor="#01DFA5"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('anikama/ani/productkardex.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableProductKardex.init('{{ route('list.productkardex') }}');
        });
    </script>
@endsection
