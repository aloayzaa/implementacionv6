@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ route('otherprovisions') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">

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
                                       value="{{Session::get('period')}}"
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
                                       value="0000"
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
                                       min="{{$period->inicio}}" max="{{$period->final}}">
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
                                        <option value="{{$tercero->id}}">
                                            {{$tercero->codigo}} | {{$tercero->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="rucd">RUC:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="ruc" readonly placeholder="RUC"
                                       id="ruc">
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
                                    <option value="C">Cliente</option>
                                    <option value="P">Proveedor</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="address">Dirección:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="address" placeholder="Dirección"
                                       id="address" readonly>
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
                                        <option value="{{$documentoCompra->id}}">{{$documentoCompra->codigo}}
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
                                       id="series">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="number">Número:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="number" placeholder="00000000"
                                       id="number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="date">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="date" class="form-control"
                                       name="date"
                                       id="date" min="{{$period->inicio}}" max="{{$period->final}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label  for="changerated">T.C</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" placeholder="0.000"
                                       name="changerate" readonly
                                       id="changerate">
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
                                       name="expdatea"
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
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text" readonly
                                       name="txt_tc" placeholder="0.000"
                                       id="txt_tc">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="total">Total:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
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
                                       name="comment"
                                       id="comment" placeholder="Ingrese Glosa">
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Del Pago del Documento</p>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label  for="paymentway">Forma de Pago</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="paymentway"
                                        id="paymentway">
                                    <option disabled selected>-Seleccione-</option>
                                    @foreach($paymentmethods as $paymentmethod)
                                        <option value="{{$paymentmethod->id}}">{{$paymentmethod->codigo}}
                                            | {{$paymentmethod->descripcion}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="bankcurrentaccount" id="bankcurrentaccount">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="bank">Banco:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control select2"
                                        name="bank"
                                        id="bank">
                                    <option disabled selected>-Seleccione-</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="period">Moneda:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control select2"
                                        name="currencypd"
                                        id="currencypd">
                                    <option disabled selected>-Seleccione-</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="paymentwaypd">M.Pago:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control select2"
                                        name="paymentwaypd"
                                        id="paymentwaypd">
                                    <option disabled selected>-Seleccione-</option>
                                    @foreach($mediopagos as $mediopago)
                                        <option value="{{$mediopago->id}}">{{$mediopago->codigo}}
                                            | {{$mediopago->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="amount">Importe:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text" placeholder="0.00"
                                       name="amount"
                                       id="amount">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="transaction">Transacción:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="transaction" placeholder="Ingrese Transacción"
                                       id="transaction">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="check">Cheque:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control"
                                       name="check" placeholder="Ingrese Cheque"
                                       id="check">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/otherprovisions.js') }}"></script>
@endsection
