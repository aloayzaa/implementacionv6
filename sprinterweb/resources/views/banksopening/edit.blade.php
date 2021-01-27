@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  data-route="{{ route('banksopening') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{$proceso}}">
                <input type="hidden" id="id" name="id" value="{{$docbanco_apertura->id}}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="period">Periodo:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="period"
                                       id="period"
                                       value="{{$period->descripcion}}"
                                       readonly>
                            </div>
                            <div class="col-md-2 col-xs-12 label-input">
                                <label for="txt_numero">Número:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="txt_numero"
                                       id="txt_numero"
                                       value="{{$docbanco_apertura->numero}}"
                                       readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="subsidiary">Sucursal:</label>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <select class="form-control"
                                        name="subsidiary"
                                        id="subsidiary">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{$sucursal->id}}"
                                                @if($docbanco_apertura->sucursal_id == $sucursal->id) selected @endif>
                                            {{$sucursal->codigo}} | {{$sucursal->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="period">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input type="date" class="form-control" name="processdate" id="processdate"
                                       min="{{$period->inicio}}" max="{{$period->final}}"
                                       value="{{$docbanco_apertura->fechaproceso}}">
                            </div>
                            <div class="col-md-2 col-xs-12 label-input">
                                <label for="changerate">Tipo de cambio:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input class="form-control" name="changerate" id="changerate" readonly
                                       value="{{$docbanco_apertura->tcambio}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="ln_solid"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12">
                                <label for="bank">Banco:</label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <select class="form-control"
                                        name="bank"
                                        id="bank">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{$banco->id}}"
                                                @if($docbanco_apertura->banco_id == $banco->id) selected @endif>
                                            {{$banco->codigo}} | {{$banco->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12 label-input">
                                <label for="currentaccount">Cuenta Corriente:</label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <select class="form-control"
                                        name="currentaccount"
                                        id="currentaccount">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($ctactebanks as $ctactebank)
                                        <option value="{{$ctactebank->id}}"
                                                @if($docbanco_apertura->ctactebanco_id == $ctactebank->id) selected @endif>
                                            {{$ctactebank->codigo}} | {{$ctactebank->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="currency">Moneda:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <select class="form-control"
                                        name="currency"
                                        id="currency">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($currencies as $currency)
                                        @if($currency->id == $docbanco_apertura->moneda_id)
                                            <option value="{{$currency->id}}" selected>
                                                {{$currency->codigo}} | {{$currency->descripcion}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-xs-12 label-input">
                                <label class="control-label" for="txt_tc">Tipo Cambio:</label>
                            </div>
                            <div class="col-md-1 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="txt_tc"
                                       id="txt_tc"
                                       value="{{$docbanco_apertura->tcmoneda}}"
                                       readonly>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="total">Total:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="total" id="total"
                                       value="{{$docbanco_apertura->total}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12">
                                <label for="customer">Cliente/Proveedor:</label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <select class="form-control"
                                        name="customer"
                                        id="customer">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{$cliente->id}}"
                                                @if($docbanco_apertura->tercero_id == $cliente->id) selected @endif>
                                            {{$cliente->codigo}} | {{$cliente->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                               {{--espacio--}}
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input class="" id="check" name="check" type="checkbox"
                                       @if($docbanco_apertura->concheque == 1) checked @endif>Con cheque
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12">
                                <label for="comment">Glosa:</label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <input class="form-control" id="comment" name="comment" type="text"
                                       placeholder="Ingrese glosa" value="{{$docbanco_apertura->glosa}}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                {{--espacio--}}
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <input class="form-control" id="checktxt" name="checktxt" type="text"
                                       placeholder="Ingrese cheque" @if($docbanco_apertura->concheque == 1)
                                       value="{{$docbanco_apertura->nrocheque}}" @else readonly @endif>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="totalmn">Total M.N:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="totalmn"
                                       id="totalmn"
                                       value="{{$docbanco_apertura->totalmn}}">
                            </div>
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="totalme">Total M.E:</label>
                            </div>
                            <div class="col-md-1 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="totalme"
                                       id="totalme"
                                       value="{{$docbanco_apertura->totalme}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="ln_solid"></div>
                        </div>
                        <div class="col-xs-12 form-group text-center">
                            <a id="btn_editar" class="btnCode fifth">
                                Editar <i class="fa fa-check"></i>
                            </a>
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
    <script src="{{ asset('anikama/ani/banksopening.js') }}"></script>
@endsection
