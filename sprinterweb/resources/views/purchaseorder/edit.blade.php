@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="pull-right">
                        {{--
                        <a onclick="abrir_modal()" class="btn primary" style="font-size: 30px;">
                            <span class="fa fa-plus-circle"></span></a>
                        --}}
                    </div>
                    <form class="form-horizontal" id="frm_generales" name="frm_generales" data-route="{{ route('purchaseorder') }}">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="var" name="var" value="{{ $var }}">
                        <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                        <input type="hidden" id="id" name="id" value="{{$ordencompra->id}}">
                        <input type="hidden" id="ruta" name="ruta" value="{{ route('update.purchaseorder') }}">
                        <input type="hidden" id="ruta_estado" name="ruta_estado" value="{{ route('estado.purchaseorder') }}">
                        <div class="row">
                            <p class="title-view">Registro:</p>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="txt_periodo">Periodo:</label>
                                <input type="text" name="txt_periodo" id="txt_periodo" class="form-control"
                                       value="{{$periodo->descripcion}}" readonly="true">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="cbo_unidad_negocio">Unidad Negocio:</label>
                                <select name="cbo_unidad_negocio" id="cbo_unidad_negocio" class="form-control select2">
                                    @foreach($unegocio as $u)
                                        <option value="{{$u->id}}" @if($ordencompra->unegocio_id == $u->id)
                                        selected @endif>{{$u->codigo}} | {{$u->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 ">
                                <label for="txt_serie">Serie:</label>
                                <input type="text" name="txt_serie" id="txt_serie"
                                       class="form-control" value="{{ ceros_izquierda($ordencompra->serie,5) }}">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="txt_numero">Número</label>
                                <input type="text" name="txt_numero" id="txt_numero"
                                       class="form-control" value="{{ ceros_izquierda($ordencompra->numero,3) }}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="txt_fecha">Fecha:</label>
                                <input type="date" name="txt_fecha" id="txt_fecha" min="{{ $periodo->inicio }}" max="{{ $periodo->final }}"
                                       class="form-control tipocambio" value="{{$ordencompra->fechaproceso}}">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="changerate">T.Cambio</label>
                                <input type="text" name="changerate" id="changerate" class="form-control typechange" value="{{$ordencompra->tcambio}}" readonly="true">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label for="cbo_punto_emision">Punto de Emisión:</label>
                                <select name="cbo_punto_emision" id="cbo_punto_emision" class="select2 form-control">
                                    @foreach($puntoventa as $p)
                                        <option value="{{$p->id}}" @if($ordencompra->puntoventa_id == $p->id)
                                        selected @endif>{{$p->codigo}} | {{$p->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <p class="title-view">Datos de la Orden:</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="cbo_razon_social">Código y Razón Social:</label>
                                        <select name="cbo_razon_social" id="cbo_razon_social" class="select2 form-control">
                                            <option value="{{$ordencompra->tercero_id}}">{{$ordencompra->tercero->codigo}} | {{$ordencompra->tercero->descripcion}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="txt_ruc">RUC:</label>
                                        <input type="text" name="txt_ruc" id="txt_ruc" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="cbo_tipo_producto">Tipo:</label>
                                        <select name="cbo_tipo_producto" id="cbo_tipo_producto" class="select2 form-control">
                                            <option value="P">Productos</option>
                                            <option value="A">Activos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="cbo_condicion_pago">Condición de Pago:</label>
                                        <select name="cbo_condicion_pago" id="cbo_condicion_pago" class="form-control select2">
                                            @foreach($condicionpago as $c)
                                                <option value="{{$c->id}}" @if($ordencompra->condicionpago_id == $c->id)
                                                selected @endif>{{$c->codigo}} | {{$c->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="cbo_moneda_orden">Moneda:</label>
                                        <select name="cbo_moneda_orden" id="cbo_moneda_orden" class="form-control select2">
                                            @foreach($moneda as $m)
                                                <option value="{{$m->id}}" @if($ordencompra->moneda_id == $m->id )
                                                selected @endif>{{$m->codigo}} | {{$m->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="txt_tc_orden">T.C.:</label>
                                        <input type="text" name="txt_tc_orden" id="txt_tc_orden" class="form-control"
                                        value="{{$ordencompra->tcmoneda}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="txt_observaciones">Observaciones:</label>
                                        <input type="text" name="txt_observaciones" id="txt_observaciones" class="form-control"
                                        value="{{$ordencompra->glosa}}">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="txt_dscto_base">Dscto.Base:</label>
                                        <input type="text" name="txt_dscto_base" id="txt_dscto_base" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="radio" id="" name="opt_tipopeso" value="1"
                                        @if($ordencompra->tipopeso == 1) checked @endif>
                                        <label for="opt_tipopeso">Con Peso Llegada o Neto</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="radio" id="" name="opt_tipopeso" value="2"
                                        @if($ordencompra->tipopeso == 2) checked @endif>
                                        <label for="opt_tipopeso">Con Peso Recojo</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label for="chk_incluye_impuestos">
                                            <input type="checkbox" id="chk_incluye_impuestos"
                                            @if($ordencompra->incluyeimpto == 1) checked @endif
                                            name="chk_incluye_impuestos"> Los precios incluyen impuestos:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="row">
                                            <p class="title-view">Tributos:</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="cbo_igv_tributos">I.G.V:</label>
                                                <select name="cbo_igv_tributos" id="cbo_igv_tributos"
                                                        class="select2 form-control">
                                                    @foreach($impuesto_one as $i)
                                                        <option value="{{$i->id}}"
                                                        data-tipocalculo="{{$i->tipocalculo}}"
                                                        data-valor="{{$i->valor}}" data-codigo="{{$i->codigo}}" @if($ordencompra->impuesto_id == $i->id) selected @endif>
                                                        {{$i->nombrecorto}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="cbo_percepcion_tributos">Percepción:</label>
                                                <select name="cbo_percepcion_tributos" id="cbo_percepcion_tributos"
                                                        class="form-control select2" @if($ordencompra->impuesto1_fk['codigo'] == '99' || $ordencompra->impuesto_id == '') disabled @endif>
                                                    @foreach($impuesto_two as $i)
                                                        <option value="{{$i->id}}" @if($ordencompra->impuesto2_id == $i->id) selected @endif>
                                                        {{$i->nombrecorto}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="row">
                                            <p class="title-view">Importes:</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="txt_base">Afecto:</label>
                                                <input type="text" id="txt_base" name="txt_base"
                                                value="{{$ordencompra->base}}" class="form-control" readonly="true">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="txt_inafecto">Inafecto:</label>
                                                <input type="text" id="txt_inafecto" name="txt_inafecto"
                                                value="{{$ordencompra->inafecto}}" class="form-control" readonly="true">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="txt_impuesto">I.G.V:</label>
                                                <input type="text" id="txt_impuesto" name="txt_impuesto"
                                                value="{{$ordencompra->impuesto}}"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="txt_impuesto2">Percepción:</label>
                                                <input type="text" name="txt_impuesto2" id="txt_impuesto2"
                                                value="{{$ordencompra->impuesto2}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="txt_total">Total:</label>
                                                <input type="text" name="txt_total" id="txt_total"
                                                value="{{$ordencompra->total}}" class="form-control" readonly="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{--espacio--}}
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <div class="row">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <button type="button" class="btn-primary form-control text-center"
                                                id="btn_pedidos" name="btn_pedidos">
                                            <span class="fa fa-book"></span> Pedidos
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="txt_total_volumen">Total Volumen:</label>
                                        <input type="text" id="txt_total_volumen" name="txt_total_volumen" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="txt_total_cantidad">Total Cantidad:</label>
                                        <input type="text" id="txt_total_cantidad" name="txt_total_cantidad" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="txt_num1" name="txt_num1" value="">
                        </div>
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">General</a>
                            </li>

                            <li role="presentation" class="">
                                <a href="#tab_content2" role="tab" id="aplica-tab" data-toggle="tab" aria-expanded="false">Datos Adicionales</a>
                            </li>

                            <li role="presentation" class="">
                                <a href="#tab_content3" role="tab" id="aplica-tab" data-toggle="tab" aria-expanded="false">Referencias</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                @include('purchaseorder.tabs_editar.general')
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="aplica-tab">
                                @include('purchaseorder.tabs_editar.additional')
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="aplica-tab">
                                @include('purchaseorder.tabs_editar.references')
                            </div>
                        </div>
                    </form>
                    @include('purchaseorder.modal.detalle_documento')
                    @include('purchaseorder.modal.detalle_documento_editar')
                    @include('purchaseorder.modal.pedidos')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/purchaseorder.js') }}"></script>
    <script>
        $(document).ready(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
        backButtonRefresh();
        function producto(id) {
            var url = '{{ route('edit.products', ["id" => ":id"]) }}';
            window.open(url.replace(':id', id), "_blank");
        }
        function pedido(id) {
            var url = '{{ route('edit.ordertowarehouse', ["id" => ":id"]) }}';
            window.open(url.replace(':id', id), "_blank");
        }
    </script>
@endsection
