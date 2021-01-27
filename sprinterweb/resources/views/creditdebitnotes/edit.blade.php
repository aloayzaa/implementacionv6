@extends('templates.home')
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT" data-route="{{ $route }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="proceso" name="proceso" value="{{$proceso}}">
                <input type="hidden" id="var" name="var" value="{{$var}}">
                <input type="hidden" id="instancia" name="instancia" value="{{$instancia}}">
                <input type="hidden" id="id" name="id" value="{{ $docxpagar->id }}">
                <input type="hidden" name="txt_ingreso_almacen" id="txt_ingreso_almacen"
                       @if($docxpagar->noalmacen==1) value="{{$ingresoAlmacen->id}}" @endif>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="period">Periodo:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="period"
                                           name="period"
                                           class="form-control"
                                           value="{{$docxpagar->periodo['descripcion']}}"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="number">Número:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="number"
                                           name="number"
                                           class="form-control"
                                           value="{{$docxpagar->numero}}"
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
                                           max="{{$period->final}}"
                                           value="{{$docxpagar->fechaproceso}}">
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
                                                <option value="{{$tipocompra->id}}"
                                                        @if($docxpagar->tipocompra_id == $tipocompra->id) selected @endif>
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
                                            <option value="{{$sucursal->id}}"
                                                    @if($docxpagar->sucursal_id == $sucursal->id) selected @endif>
                                                {{$sucursal->codigo}} | {{$sucursal->descripcion}}</option>
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
                                            <option value="{{$tercero->id}}"
                                                    @if($tercero->id ==$docxpagar->tercero['id']) selected @endif>
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
                                            <option value="{{$documentoCompra->id}}"
                                                    @if($documentoCompra->id==$docxpagar->documento_id) selected @endif>
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
                                <div class="col-md-6 col-xs-12">
                                    <input type="text"
                                           id="ruc"
                                           name="ruc"
                                           class="form-control"
                                           placeholder="RUC"
                                           value="{{$docxpagar->tercero['codigo']}}"
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
                                           value="{{$docxpagar->seriedoc}}"
                                           placeholder="Ingrese serie">
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="docnumber">Número:</label>
                                </div>
                                <div class="col-md-1 col-xs-12">
                                    <input type="text"
                                           id="docnumber"
                                           name="docnumber"
                                           class="form-control"
                                           value="{{$docxpagar->numerodoc}}">
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
                                           value="{{$docxpagar->tercero['via_nombre']}}"
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
                                           value="{{$docxpagar->fechadoc}}"
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
                                           value="{{$docxpagar->tcambio}}"
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
                                           placeholder="Ingrese glosa"
                                           value="{{$docxpagar->glosa}}">
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
                                            name="documentref" readonly
                                            class="form-control">
                                        <option value="{{$docxpagarReferencia->id}}"
                                                selected>@if($docxpagarReferencia){{$docxpagarReferencia->documentocom['codigo']}}
                                            {{$docxpagarReferencia->seriedoc}} {{$docxpagarReferencia->numerodoc}}@endif</option>
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
                                           value="@if($referencia){{$referencia->importe}}@endif"
                                           readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="trader" class="" style="font-weight: 700;">
                                        <input type="checkbox" id="applycheck" name="applycheck"
                                               @if($referencia) @if($referencia->aplicar == 1)checked @endif @endif
                                               class="">Aplicar
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
                                            <option value="{{$moneda->id}}"
                                                    @if($moneda->id == $docxpagar->moneda['id']) selected @endif>
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
                                           value="{{$docxpagar->tcmoneda}}"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 "></div>
                                <div class="col-md-1 col-xs-12 "></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="affect">Base Afecta:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="affect"
                                           name="affect"
                                           placeholder="0.00"
                                           value="{{$docxpagar->base}}"
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
                                            <option value="{{$condicionpago->id}}"
                                                    @if($condicionpago->id == $docxpagar->condicionPago['id']) selected @endif>
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
                                           value="{{$docxpagar->inafecto}}"
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
                                        <option value="1" @if($docxpagar->tipoadq==1) selected @endif>1. Destino
                                            Gravado
                                        </option>
                                        <option value="2" @if($docxpagar->tipoadq==2) selected @endif>2. Destino Mixto
                                        </option>
                                        <option value="3" @if($docxpagar->tipoadq==3) selected @endif>3. Destino No
                                            Grabado
                                        </option>
                                        <option value="4" @if($docxpagar->tipoadq==4) selected @endif>4. No Grabadas
                                        </option>
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
                                           value="{{$docxpagar->impuesto}}"
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
                                            <option value="{{$impuesto->id}}"
                                                    @if($impuesto->id == $docxpagar->impuesto_id) selected @endif>
                                                {{$impuesto->nombrecorto}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 col-xs-12"></div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="total">Total:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text"
                                           id="total"
                                           name="total"
                                           class="form-control"
                                           placeholder="0.00"
                                           value="{{$docxpagar->total}}"
                                           readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ln_solid"></div>
                                <div class="col-xs-12 form-group text-center">
                                    <a id="btn_editar" class="btnCode fifth">
                                        Editar <i class="fa fa-check"></i>
                                    </a>
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
