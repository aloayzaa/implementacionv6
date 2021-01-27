@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_title">
            <ul class="nav navbar-right panel_toolbox">
                <a onclick="abrir_modal()"
                   title="Agregar" class="icon-button plus"><i class="fa fa-plus"></i><span></span></a>
            </ul>
        </div>
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                {{--                <input type="hidden" id="instancia" name="instancia" value="{{ $instancia }}">--}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="period">Periodo:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">

                                    <input class="form-control"
                                           type="text"
                                           name="period"
                                           id="period"
                                           value="{{Session::get('period')}}"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="number">Número:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">

                                    <input class="form-control"
                                           type="text"
                                           name="txt_numero"
                                           id="txt_numero"
                                           value="0000"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="date">Fecha:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">

                                    <input class="form-control"
                                           type="date"
                                           name="processdate"
                                           id="processdate"
                                           min="{{$period->inicio}}"
                                           max="{{$period->final}}"
                                           required>
                                </div>
                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="buy">Sucursal:</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <select class="form-control select2"
                                                name="subdiary"
                                                id="subdiary">
                                            @foreach($sucursales as $sucursal)
                                                <option value="{{$sucursal->id}}">{{$sucursal->codigo}}
                                                    | {{$sucursal->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="buy">T. Compra:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">

                                    <select class="form-control select2"
                                            name="buy"
                                            id="buy">
                                        @foreach($tipocompras as $tipocompra)
                                            <option value="{{$tipocompra->id}}">{{$tipocompra->codigo}}
                                                | {{$tipocompra->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="txt_tercero">Proveedor:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control select2"
                                            name="txt_tercero"
                                            id="txt_tercero" onchange="ruc_proveedor(this.value)">
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($terceros as $tercero)
                                            <option value="{{$tercero->id}}">
                                                {{$tercero->codigo}} | {{$tercero->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="customerruc">RUC:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           name="customerruc"
                                           id="customerruc"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="documenttype">Tipo Doc:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2"
                                            name="documenttype"
                                            id="documenttype">
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($documentoscompra as $documentocompra)
                                            <option value="{{$documentocompra->id}}">
                                                {{$documentocompra->codigo}} | {{$documentocompra->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="series">Serie:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="series"
                                           id="series"
                                           required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="numberOfSeries">Número:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="numberseries"
                                           id="numberseries"
                                           value="00000000"
                                           required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="">Fecha:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="date"
                                           name="date"
                                           id="date"
                                           min="{{$period->inicio}}"
                                           max="{{$period->final}}"
                                           onchange="expirationdate()"
                                           required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="changerate">T. cambio:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="changerate"
                                           id="changerate"
                                           required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="comment">Glosa:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="comment"
                                           id="comment">
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="">TC:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" name="txt_tc" id="txt_tc" readonly
                                           value="0.000">
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="">Moneda:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2" name="currency" id="currency">
                                        @foreach($monedas as $moneda)
                                            <option value="{{$moneda->id}}">
                                                {{$moneda->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="ruc">Total:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" name="total" id="total" value="0.00"
                                           readonly>
                                </div>
                            </div>

            </form>
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Detalle de
                        la Liquidación</a>
                </li>

                <li role="presentation" class="">
                    <a href="#tab_content2" role="tab" id="aplica-tab" data-toggle="tab" aria-expanded="false">Aplicaciones
                        </a>
                </li>

            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                    @include('settlementexpenses.tabs.detalle_liquidacion')
                </div>

                {{--<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="aplica-tab">
                    @include('settlementexpenses.tabs.aplicaciones')
                </div>--}}
            </div>

        </div>
    </div>



@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    {{--<script src="{{ asset('anikama/ani/provisionstopay.js') }}"></script>--}}
@endsection
