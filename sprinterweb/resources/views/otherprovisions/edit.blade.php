@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT" data-route="{{ route('otherprovisions') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" name="id" id="id" value="{{$otra_provision->id}}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <p class="title-view">Registro</p>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="period">Periodo:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="period"
                                       id="period"
                                       value="{{$otra_provision->periodo['descripcion']}}"
                                       readonly>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_numero">Número:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="txt_numero"
                                       id="txt_numero"
                                       value="{{$otra_provision->numero}}"
                                       readonly>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="processdate">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="date"
                                       name="processdate"
                                       id="processdate"
                                       min="{{$period->inicio}}" max="{{$period->final}}"
                                       value="{{$otra_provision->fechaproceso}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="subsidiary">Sucursal:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="subsidiary"
                                        id="subsidiary">
                                    <option disabled>-Seleccione-</option>
                                    <option value="{{$sucursal->id}}" selected>
                                        {{$sucursal->codigo}} | {{$sucursal->descripcion}}
                                    </option>
                                </select>
                                <input type="hidden" name="cashe" id="cashe">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="transactiontype">Tipo Transacción:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="transactiontype"
                                        id="transactiontype">
                                    <option disabled>-Seleccione-</option>
                                    <option value="{{$transaccion->id}}" selected>
                                        {{$transaccion->codigo}} | {{$transaccion->descripcion}}
                                    </option>
                                </select>
                                <input type="hidden" name="cashe" id="cashe">
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Documento</p>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="customer">Cliente:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="customer"
                                        id="customer">
                                    <option disabled selected>-Seleccione-</option>
                                    @foreach($terceros as $tercero)
                                        <option value="{{$tercero->id}}"
                                                @if($otra_provision->tercero['id'] == $tercero->id) selected @endif>
                                            {{$tercero->codigo}} | {{$tercero->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="ruc">RUC:</label>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="ruc" readonly placeholder="RUC"
                                           value="{{$otra_provision->ruc}}"
                                           id="ruc">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="type">Tipo:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="type"
                                        id="type">
                                    <option disabled>-Seleccione-</option>
                                    <option value="C" @if($otra_provision->origen=='C') selected @endif>Cliente</option>
                                    <option value="P" @if($otra_provision->origen=='P') selected @endif>Proveedor
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="address">Dirección:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="address" placeholder="Dirección"
                                       id="address" readonly
                                       value="{{$otra_provision->direccion}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="documenttype">Documento:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="documenttype"
                                        id="documenttype">
                                    <option disabled selected>-Seleccione-</option>
                                    @foreach($documentosCompra as $documentoCompra)
                                        <option value="{{$documentoCompra->id}}"
                                                @if($otra_provision->documento_id == $documentoCompra->id) selected @endif>
                                            {{$documentoCompra->codigo}}
                                            | {{$documentoCompra->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="series">Serie:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="series" placeholder="00000"
                                       id="series"
                                       value="{{$otra_provision->seriedoc}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="number">Número:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="number" placeholder="00000000"
                                       id="number"
                                       value="{{$otra_provision->numerodoc}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="date">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="date" class="form-control"
                                       name="date"
                                       id="date" min="{{$period->inicio}}" max="{{$period->final}}"
                                       value="{{$otra_provision->fechadoc}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="changerate">T.C</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" placeholder="0.000"
                                       name="changerate" readonly
                                       id="changerate"
                                       value="{{$otra_provision->tcambio}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="paymentcondition">C.Pago:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="paymentcondition"
                                        id="paymentcondition">
                                    <option disabled>-Seleccione-</option>
                                    <option value="{{$condicion->id}}" selected>{{$condicion->codigo}}
                                        | {{$condicion->descripcion}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="expdatea">Vencimiento:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="date" min="{{$period->inicio}}" max="{{$period->final}}"
                                       name="expdatea" value="{{$otra_provision->vencimiento}}"
                                       id="expdatea">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="currency">Moneda:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control select2"
                                        name="currency"
                                        id="currency">
                                    <option disabled>-Seleccione-</option>
                                    <option value="{{$currency->id}}" selected>{{$currency->codigo}}
                                        | {{$currency->descripcion}}</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_tc">T.C</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input class="form-control"
                                       type="text" readonly
                                       name="txt_tc" placeholder="0.000"
                                       id="txt_tc" value="{{$otra_provision->tcmoneda}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="total">Total:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text" value="{{$otra_provision->total}}"
                                       name="total"
                                       id="total">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="comment">Glosa:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input class="form-control" type="text"
                                       name="comment" value="{{$otra_provision->glosa}}"
                                       id="comment" placeholder="Ingrese Glosa">
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="form-group">
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-md-4 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    F.Pago
                                </label>
                                <select class="form-control select2"
                                        name="paymentway"
                                        id="paymentway">
                                    <option disabled selected>-Seleccione-</option>
                                    @foreach($paymentmethods as $paymentmethod)
                                        <option value="{{$paymentmethod->id}}"
                                                @if($docxpagarFormaPago->docBanco['ctactebanco_id'] == $paymentmethod->id)
                                                selected @endif>{{$paymentmethod->codigo}}
                                            | {{$paymentmethod->descripcion}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="bankcurrentaccount" id="bankcurrentaccount"
                                       value="{{$docxpagarFormaPago->docBanco['ctactebanco_id']}}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    Banco
                                </label>
                                <select class="form-control select2"
                                        name="bank"
                                        id="bank">
                                    <option disabled>-Seleccione-</option>
                                    <option value="{{$ctacte->banco_id}}">{{$ctacte->codigo}}
                                        | {{$ctacte->descripcion}}</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    Moneda
                                </label>
                                <select class="form-control select2"
                                        name="currencypd"
                                        id="currencypd">
                                    <option disabled>-Seleccione-</option>
                                    @if($docbanco)
                                        <option value="{{$docxpagarFormaPago->docBanco['moneda_id']}}">
                                            {{$docbanco->moneda['codigo']}} | {{$docbanco->moneda['descripcion']}}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-md-4 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    M.Pago
                                </label>
                                <select class="form-control select2"
                                        name="paymentwaypd"
                                        id="paymentwaypd">
                                    <option disabled>-Seleccione-</option>
                                    @foreach($mediopagos as $mediopago)
                                        <option value="{{$mediopago->id}}"
                                                @if($docxpagarFormaPago->medioPago['id'] == $mediopago->id) selected @endif>{{$mediopago->codigo}}
                                            | {{$mediopago->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    Importe
                                </label>
                                <input class="form-control"
                                       type="text" placeholder="0.00"
                                       name="amount" value="{{$docxpagarFormaPago->docBanco['total']}}"
                                       id="amount">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    Transacción
                                </label>
                                <input class="form-control"
                                       type="text" value="{{$docxpagarFormaPago->transaccion}}"
                                       name="transaction" placeholder="Ingrese Transacción"
                                       id="transaction">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    Cheque
                                </label>
                                <input type="text" class="form-control"
                                       name="check" placeholder="Ingrese Cheque"
                                       id="check" value="{{$docxpagarFormaPago->docBanco['nrocheque']}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>--}}
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/otherprovisions.js') }}"></script>
@endsection
