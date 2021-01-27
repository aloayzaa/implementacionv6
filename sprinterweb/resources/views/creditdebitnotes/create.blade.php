@extends('templates.home')
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ $route }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="proceso" name="proceso" value="{{$proceso}}">
                <input type="hidden" id="var" name="var" value="{{$var}}">
                <input type="hidden" id="instancia" name="instancia" value="{{$instancia}}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="period">Periodo:</label>
                                </div>
                                <div class="col-md-2 col-xs-12 has-feedback">
                                    <input type="text"
                                           id="period"
                                           name="period"
                                           class="form-control"
                                           value="{{Session::get('period')}}"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="number">Número:</label>
                                </div>
                                <div class="col-md-2 col-xs-12 has-feedback">
                                    <input type="text"
                                           id="number"
                                           name="number"
                                           class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="processdate">Fecha:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="date"
                                           id="processdate"
                                           name="processdate"
                                           class="form-control"
                                           min="{{$period->inicio}}"
                                           max="{{$period->final}}">
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="purchasetype">T. Compra:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select id="purchasetype"
                                            name="purchasetype"
                                            class="form-control">
                                        @foreach($tipocompras as $tipocompra)
                                            @if($tipocompra->id != 2)
                                                <option value="{{$tipocompra->id}}">
                                                    {{$tipocompra->codigo}} | {{$tipocompra->descripcion}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
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
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="ln_solid"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="customer">Proveedor:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select id="customer"
                                            name="customer"
                                            class="form-control select2">
                                        <option selected disabled value="0">Seleccionar</option>
                                        @foreach($terceros as $tercero)
                                            <option value="{{$tercero->id}}">
                                                {{$tercero->codigo}} | {{$tercero->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="document">Tipo Doc.:</label>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <select id="document"
                                            name="document"
                                            class="form-control">
                                        @foreach($documentosCompra as $documentoCompra)
                                            <option value="{{$documentoCompra->id}}">
                                                {{$documentoCompra->codigo}} | {{$documentoCompra->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="ruc">RUC:</label>
                                </div>
                                <div class="col-md-6 col-xs-12 has-feedback">
                                    <input type="text"
                                           id="ruc"
                                           name="ruc"
                                           class="form-control"
                                           placeholder="RUC"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="docseries">Serie:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="docseries"
                                           name="docseries"
                                           class="form-control"
                                           placeholder="Ingrese serie">
                                </div>
                                <div class="col-md-1 col-xs-12">
                                    <label for="docnumber">Número:</label>
                                </div>
                                <div class="col-md-1 col-xs-12">
                                    <input type="text"
                                           id="docnumber"
                                           name="docnumber"
                                           class="form-control"
                                           value="00000000">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="address">Dirección:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text"
                                           id="address"
                                           name="address"
                                           class="form-control"
                                           placeholder="Dirección"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="date">Fecha:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="date"
                                           id="date"
                                           name="date"
                                           class="form-control"
                                           min="{{$period->inicio}}"
                                           max="{{$period->final}}">
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="changerate">T. Cambio:</label>
                                </div>
                                <div class="col-md-1 col-xs-12">
                                    <input type="text"
                                           id="changerate"
                                           name="changerate"
                                           class="form-control"
                                           readonly
                                           placeholder="0.000">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="comment">Glosa:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text"
                                           id="comment"
                                           name="comment"
                                           class="form-control"
                                           placeholder="Ingrese glosa">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="ln_solid"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="documentref">Documento:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select id="documentref"
                                            name="documentref" readonly="true"
                                            class="form-control">
                                        <option value="0" disabled selected>Documento</option>
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="amount">Importe:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text"
                                           id="amount"
                                           name="amount"
                                           class="form-control"
                                           placeholder="Importe"
                                           readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="trader" class="" style="font-weight: 700;">
                                        <input type="checkbox" id="applycheck" name="applycheck" class=""> Aplicar
                                    </label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <a type="button"
                                       id=""
                                       name=""
                                       class="btn btn-primary"
                                       onclick="referencia_notacredito()">Ver referencia
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="ln_solid"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="trader">Moneda:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select id="trader"
                                            name="trader"
                                            class="form-control select2">
                                        @foreach($monedas as $moneda)
                                            <option value="{{$moneda->id}}">
                                                {{$moneda->codigo}} | {{$moneda->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="txt_tc">T.C:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="txt_tc"
                                           name="txt_tc"
                                           class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 "></div>
                                <div class="col-md-1 col-xs-12 "></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="affect">Base Afecta:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="base"
                                           name="base"
                                           placeholder="0.00"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="paymentcondition">Cond. Pago:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select id="paymentcondition"
                                            name="paymentcondition"
                                            class="form-control select2">
                                        @foreach($condicionpagos as $condicionpago)
                                            <option value="{{$condicionpago->id}}">
                                                {{$condicionpago->codigo}} | {{$condicionpago->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 col-xs-12"></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="inactive">Inafecto:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="inactive"
                                           name="inactive"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="acquisition">Adquisición:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select id="acquisition"
                                            name="acquisition"
                                            class="form-control select2">
                                        <option value="1">1. Destino Gravado</option>
                                        <option value="2">2. Destino Mixto</option>
                                        <option value="3">3. Destino No Grabado</option>
                                        <option value="4">4. No Grabadas</option>
                                    </select>
                                </div>
                                <div class="col-md-5 col-xs-12"></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="igvtotal">I.G.V:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="igvtotal"
                                           name="igvtotal"
                                           class="form-control"
                                           placeholder="0.00"
                                           readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="igv">I.G.V:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select id="igv"
                                            name="igv"
                                            class="form-control select2">
                                        @foreach($impuestos as $impuesto)
                                            <option value="{{$impuesto->id}}">
                                                {{$impuesto->nombrecorto}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 col-xs-12"></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="total">Percepción:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" name="perceptiontotal" id="perceptiontotal">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="igv">Percepción</label>
                                    <label for="ruc">
                                        <input class="" type="checkbox" name="perceptioncheck" id="perceptioncheck">
                                        Incluye
                                    </label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select class="form-control select2" name="perception" id="perception">
                                        @foreach($impuestos2 as $impuesto2)
                                            <option value="{{$impuesto2->id}}">{{$impuesto2->nombrecorto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 col-xs-12"></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="total">Total:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text" id="total" name="total" class="form-control" placeholder="0.00"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="{{ asset('js/datatables.js') }}"></script>
                @include('creditdebitnotes.creditdebitnotes_modal')
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/creditdebitnotes.js') }}"></script>
@endsection
