@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_title">
            <ul class="nav navbar-right panel_toolbox">
                <a onclick="abrir_modal('{{route('listreference.withholdingdocuments')}}')" title="Agregar"
                   class="icon-button plus"><i class="fa fa-plus"></i><span></span></a>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="instancia" name="instancia" value="{{ $instancia }}">

                <div class="form-group">
                    <div class="col-md-2 col-xs-12">
                        <label class="control-label col-md-1" for="period">
                            Periodo
                        </label>
                        <input class="form-control"
                               type="text"
                               name="period"
                               id="period"
                               value="{{Session::get('period')}}"
                               readonly>
                    </div>

                    <div class="col-md-2 col-xs-12">
                        <label class="control-label" for="number">
                            Número
                        </label>
                        <input class="form-control"
                               type="text"
                               name="txt_numero"
                               id="txt_numero"
                               value="0000"
                               readonly>
                    </div>

                    <div class="col-md-2 col-xs-12">
                        <label class="control-label" for="date">
                            Fecha
                        </label>
                        <input class="form-control"
                               type="date"
                               name="processdate"
                               id="processdate"
                               min="{{$period->inicio}}"
                               max="{{$period->final}}"
                               required>
                    </div>

                    <div class="col-md-3 col-xs-12">
                        <label class="control-label" for="buy">
                            Tipo
                        </label>
                        <select class="form-control select2" name="type" id="type">
                            <option value="C">Cliente</option>
                            <option value="P">Proveedor</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-xs-12">
                        <label class="control-label" for="buy">
                            T. de Transf
                        </label>
                        <select class="form-control select2" name="transfertype" id="transfertype">
                            @foreach($tipotransacciones as $tipotransaccion)
                                <option value="{{$tipotransaccion->id}}">{{$tipotransaccion->codigo}}
                                    | {{$tipotransaccion->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>
                <div class="form-group">
                    <label class="control-label col-md-1" for="customer">
                        Proveedor
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control select2"
                                name="customer"
                                id="customer" onchange="ruc_proveedor(this.value)">
                            <option selected disabled>-Seleccione-</option>
                            @foreach($terceros as $tercero)
                                <option value="{{$tercero->id}}">{{$tercero->codigo}}
                                    | {{$tercero->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="control-label col-md-1" for="document">
                        RUC
                    </label>
                    <div class="col-md-4 col-xs-12">
                        <input class="form-control" name="customerruc" id="customerruc" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-1" for="">
                        Dirección
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text" class="form-control" readonly placeholder="Dirección" id="customeraddress"
                               name="customeraddress">
                    </div>

                    <label class="control-label col-md-1" for="">
                        Cond. Pago
                    </label>
                    <div class="col-md-4 col-xs-12">
                        <select class="form-control select2" name="paymentcondition" id="paymentcondition">
                            @foreach($condicion_pagos as $codigion_pago)
                                <option value="{{$codigion_pago->id}}">{{$codigion_pago->codigo}}
                                    | {{$codigion_pago->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-1" for="">
                        Tipo Doc.
                    </label>
                    <div class="col-md-3 col-xs-12">
                        <select class="form-control select2" name="documenttype" id="documenttype">
                            @foreach($documentosCompra as $documentocompra)
                                <option value="{{$documentocompra->id}}">{{$documentocompra->codigo}}
                                    | {{$documentocompra->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="control-label col-md-1" for="series">
                        Serie
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control" type="text" name="series" id="series" required
                               placeholder="Ingrese Serie">
                    </div>

                    <label class="control-label col-md-1" for="numberOfSeries">
                        Número
                    </label>
                    <div class="col-md-1 col-xs-12">
                        <input class="form-control" type="text" name="number" id="number" value="00000000"
                               required>
                    </div>

                    <label class="control-label col-md-1" for="">
                        Fecha
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control" type="date" name="date" id="date" min="{{$period->inicio}}"
                               max="{{$period->final}}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-1" for="">
                        Vencimiento
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control" type="date" name="expdatea" id="expdatea" required
                               readonly>
                    </div>

                    <label class="control-label col-md-1" for="">
                        Moneda
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <select class="form-control select2" name="currency" id="currency">
                            @foreach($monedas as $moneda)
                                <option value="{{$moneda->id}}">{{$moneda->codigo}} | {{$moneda->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="control-label col-md-1" for="">
                        T.C.
                    </label>
                    <div class="col-md-1 col-xs-12">
                        <input class="form-control" type="text" name="txt_tc" id="txt_tc" readonly placeholder="0.000">
                    </div>

                    <label class="control-label col-md-1" for="">
                        Glosa
                    </label>
                    <div class="col-md-3 col-xs-12">
                        <input class="form-control" type="text" name="comment" id="comment">
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div id="withholding_detail"></div>

                <div class="ln_solid"></div>
                <div class="col-xs-12 form-group text-center">
                    <a id="btn_grabar" class="btnCode fifth">
                        Registrar <i class="fa fa-check"></i>
                    </a>
                </div>
                <script src="{{ asset('js/datatables.js') }}"></script>
                @include('withholdingdocuments.withholding_modal')
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/withholdingdocuments.js') }}"></script>
@endsection
