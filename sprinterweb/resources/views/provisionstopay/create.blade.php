@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
@endsection
@section('content')

    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="ruta" name="ruta" value="{{ route('store.provisionstopay') }}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">
                        <form id="frm_generales" autocomplete="off">
                            <input type="hidden" id="id" name="id" value="{{ 0 }}">

                            <div class="panel panel-info">
                                <div class="panel-heading">Registro</div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="form-group col-md-2" style="width: 150px">
                                            <label for="txt_periodo">Periodo: </label>
                                            <input type="text" class="form-control" value="{{ $period->descripcion }}"
                                                   readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="txt_unegocio">Unid. Negocio: </label>
                                            <select class="form-control select2" name="txt_unegocio" id="txt_unegocio">
                                                @foreach($unidades as $unidad)
                                                    <option value="{{$unidad->id}}">
                                                        {{$unidad->codigo}} | {{$unidad->descripcion}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2" style="width: 100px">
                                            <label for="txt_numero">Número: </label>
                                            <input type="text" class="form-control text-center" name="txt_numero"
                                                   id="txt_numero" placeholder="0000" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2" style="width: 165px">
                                            <label for="txt_fecha">Fecha: </label>
                                            <input type="date" class="form-control" name="txt_fecha" id="txt_fecha"
                                                   min="{{ $period->inicio }}" max="{{ $period->final }}"
                                                   value="{{ $today }}"> {{--fechaproce para que no interfiera con la fecha de abajo--}}
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_ordencompra">Orden Compra: </label>
                                            {{--     <input type="text" class="form-control" name="txt_ordencompra" ondblclick="add_orden({{ 0 }})" id="txt_ordencompra" value="" readonly>
                                                 <input type="" class="form-control" name="txt_ordencompra_id" id="txt_ordencompra_id" value="" readonly>--}}
                                            <input type="text" class="form-control" name="txt_ordencompra"
                                                   ondblclick="add_orden({{ 0 }})" id="txt_ordencompra" value=""
                                                   readonly>
                                            <input type="hidden" class="form-control" name="txt_ordencompra_id"
                                                   id="txt_ordencompra_id" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2" style="width: 190px">
                                            <label for="txt_sucursal">Sucursal: </label>
                                            <select class="form-control select2" name="txt_sucursal" id="txt_sucursal">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($sucursales as $sucursal)
                                                    <option value="{{ $sucursal->id }}">
                                                        {{ $sucursal->codigo }} | {{ $sucursal->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_tipocompra">Tipo Compra: </label>
                                            <select class="form-control select2" name="txt_tipocompra"
                                                    id="txt_tipocompra">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($tipocompras as $tipocompra)
                                                    <option value="{{ $tipocompra->id }}">
                                                        {{ $tipocompra->codigo }}
                                                        | {{ $tipocompra->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">Documento</div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="txt_tercero">Razón Social: </label>
                                                <select class="form-control select2" name="txt_tercero"
                                                        id="txt_tercero">
                                                    <option value="" selected>-- Seleccione una opción --</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_tipodoc">Tipo Doc: </label>
                                                <select class="form-control select2" name="txt_tipodoc"
                                                        id="txt_tipodoc">
                                                    @foreach($documentoscompra as $documentocompra)
                                                        <option value="{{ $documentocompra->id }}">
                                                            {{ $documentocompra->codigo }}
                                                            | {{ $documentocompra->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_seriedoc">Serie: </label>
                                                <input type="text" class="form-control" name="txt_seriedoc"
                                                       id="txt_seriedoc" placeholder="0000" value="">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_numerodoc">Número: </label>
                                                <input type="text" class="form-control" name="txt_numerodoc"
                                                       id="txt_numerodoc" placeholder="0000" value="">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_fechadoc">Fecha: </label>
                                                <input type="date" class="form-control tipocambio" name="txt_fechadoc"
                                                       id="txt_fechadoc"
                                                       value=""> {{--id es fecha para calc tipocambio--}}
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_tcambio">T. Cambio: </label>
                                                <input type="text" class="form-control typechange" name="txt_tcambio"
                                                       id="txt_tcambio" value="" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="txt_condicionpago">Condición Pago: </label>
                                            <select class="form-control select2" name="txt_condicionpago"
                                                    id="txt_condicionpago">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($condicionpagos as $condicionpago)
                                                    <option value="{{$condicionpago->id}}">
                                                        {{ $condicionpago->codigo }} | {{ $condicionpago->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_vencimiento">Vencimiento: </label>
                                            <input type="date" class="form-control" name="txt_vencimiento"
                                                   id="txt_vencimiento" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_moneda">Moneda: </label>
                                            <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($monedas as $moneda)
                                                    <option value="{{$moneda->id}}">
                                                        {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="txt_tc">T. C: </label>
                                            <input type="text" class="form-control" name="txt_tc" id="txt_tc" value=""
                                                   readonly>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="txt_glosa">Glosa: </label>
                                            <input type="text" class="form-control" name="txt_glosa" id="txt_glosa"
                                                   value="">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">Tributos</div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="txt_clasificacion">Clasificación de Bien o Servicio: (No
                                                funciona 4 y
                                                5) </label>
                                            <select class="form-control select2" name="txt_clasificacion"
                                                    id="txt_clasificacion">
                                                @foreach($servicios as $servicio)
                                                    <option value="{{ $servicio->id }}">
                                                        {{ $servicio->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_adquisicion">Tipo Aquisición: </label>
                                            <select class="form-control select2" name="txt_adquisicion"
                                                    id="txt_adquisicion">
                                                <option value=1>1. Destino Gravado</option>
                                                <option value=2>2. Destino Mixto</option>
                                                <option value=3>3. Destino No Gravado</option>
                                                <option value=4>4. No Gravadas</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_igv_id">IGV: </label>
                                            <select class="form-control select2" name="txt_igv_id" id="txt_igv_id">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($impuestos as $impuesto)
                                                    <option value="{{ $impuesto->id }}">
                                                        {{ $impuesto->nombrecorto }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_percepcion_id">Percepción: </label>
                                            <select class="form-control select2" name="txt_percepcion_id"
                                                    id="txt_percepcion_id">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($impuestos2 as $impuesto2)
                                                    <option value="{{ $impuesto2->id }}">
                                                        {{ $impuesto2->nombrecorto }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_renta_id">Renta: </label>
                                            <select class="form-control select2" name="txt_renta_id" id="txt_renta_id">
                                                <option value="" selected>-- Seleccione una opción --</option>
                                                @foreach($impuestos3 as $impuesto3)
                                                    <option value="{{$impuesto3->id}}">
                                                        {{ $impuesto3->nombrecorto }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">Importes</div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="form-group col-md-1">
                                            <label for="txt_base">Base Afecta: </label>
                                            <input type="text" class="form-control numero twodecimal" name="txt_base"
                                                   id="txt_base" value="0.00">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="txt_inafecto">Inafecto: </label>
                                            <input type="text" class="form-control numero twodecimal"
                                                   name="txt_inafecto"
                                                   id="txt_inafecto" value="0.00">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="txt_igv">IGV: </label>
                                            <input type="text" class="form-control desbloqueable numero twodecimal"
                                                   name="txt_igv" id="txt_igv" value="0.00" readonly>
                                        </div>
                                        <div class="form-group col-md-1" style="margin-top:-20px">
                                            <input type="checkbox" id="check_impuesto"
                                                   name="check_impuesto">Incluye</input>
                                            <label for="txt_percepcion">Percepción: </label>
                                            <input type="text" class="form-control desbloqueable numero twodecimal"
                                                   name="txt_percepcion" id="txt_percepcion" value="0.00" readonly>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="txt_renta">Renta: </label>
                                            <input type="text" class="form-control desbloqueable numero twodecimal"
                                                   name="txt_renta" id="txt_renta" value="0.00" readonly>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="txt_total">Total: </label>
                                            <input type="text" class="form-control" name="txt_total" id="txt_total"
                                                   value="0.00"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab"
                                           aria-expanded="true">Detalle del Documento</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_content2" role="tab" id="aplica-tab" data-toggle="tab"
                                           aria-expanded="false">Datos Adicionales</a>
                                    </li>
                                </ul>
                            </div>

                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                     aria-labelledby="home-tab">
                                    @include('provisionstopay.tabs.detalleDocumento')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                     aria-labelledby="aplica-tab">
                                    @include('provisionstopay.tabs.datosAdicionales')
                                </div>
                            </div>
                        </form>
                        @include ('provisionstopay.modals.edit_cuenta')
                        @include ('provisionstopay.modals.referencia')
                        @include ('provisionstopay.modals.ordencompra')

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/provisionstopay.js') }}"></script>
    {{--    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script>
        backButtonRefresh();

        function cuenta(id) {
            if (id == null) {
                alert('No existe provision')
            } else {
                var url = '{{ route('edit.accountingplans', ["id" => ":id"]) }}';
                window.open(url.replace(':id', id), "_blank");
            }
        }
    </script>
@endsection
