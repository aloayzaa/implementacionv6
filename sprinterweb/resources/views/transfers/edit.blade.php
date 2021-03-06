@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_title">
            <ul class="nav navbar-right panel_toolbox">
                <a data-toggle="tooltip" data-placement="left" onclick="abrir_modal()"
                   title="Agregar" class="icon-button plus"><i class="fa fa-plus"></i><span></span></a>
            </ul>
        </div>
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ route('transfers') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" name="id" id="id" value="{{$docbanco_transito->id}}">
                <div class="panel panel-default">
                    <div class="panel-body">
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
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <input class="form-control"
                                       type="text"
                                       name="txt_numero"
                                       id="txt_numero"
                                       value="{{$docbanco_transito->numero}}"
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
                                       value="{{$docbanco_transito->fechaproceso}}"
                                       min="{{$period->inicio}}" max="{{$period->final}}">
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Por el Egreso</p>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_numeroe">Número:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" readonly name="txt_numeroe"
                                       id="txt_numeroe" value="{{$docbanco_egreso->numero}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="processdatee">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="date"
                                       name="processdatee"
                                       id="processdatee" value="{{$docbanco_egreso->fechaproceso}}"
                                       min="{{$period->inicio}}" max="{{$period->final}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="changeratee">T.C</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="changeratee" value="{{$docbanco_egreso->tcambio}}"
                                       id="changeratee" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="operatione">Operación Egreso:</label>
                            </div>
                            <div class="col-md-5 col-sm-5  col-xs-12">
                                <select class="form-control"
                                        name="operatione"
                                        id="operatione">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($operacionese as $operacion)
                                        <option value="{{$operacion->id}}"
                                                @if($operacion->id == $docBancoDetalle_egreso->tipooperacion['id']) selected @endif>
                                            {{$operacion->codigo}} | {{$operacion->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <label for="banke">Banco:
                                </label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control"
                                        name="banke"
                                        id="banke">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{$banco->id}}"
                                                @if($banco->id == $docbanco_egreso->banco['id']) selected @endif>
                                            {{$banco->codigo}} | {{$banco->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" value="{{$docbanco_egreso->banco['efectivo']}}" name="cashe"
                                       id="cashe">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="currentaccounte">Cuenta Corriente:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control"
                                        name="currentaccounte"
                                        id="currentaccounte">
                                    @foreach($ctactebankse as $ctactebanse)
                                        <option value="{{$ctactebanse->id}}"
                                                @if($ctactebanse->id == $docBancoDetalle_egreso->id) selected @endif>
                                            {{$ctactebanse->codigo}} | {{$ctactebanse->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="currencye">Moneda:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control"
                                        name="currencye"
                                        id="currencye">
                                    @foreach($currencies as $currency)
                                        @if($currency->id == $docbanco_egreso->moneda_id)
                                            <option value="{{$currency->id}}" selected>
                                                {{$currency->codigo}} | {{$currency->descripcion}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label  for="txt_tce">T.C</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="txt_tce"
                                       id="txt_tce"
                                       value="{{$docbanco_egreso->tcmoneda}}"
                                       readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="amounte">Imp.Transferido:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" name="amounte" id="amounte"
                                       value="{{$docbanco_egreso->total}}">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                {{--espacio--}}
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="commente">Glosa:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input class="form-control" type="text" placeholder="Ingrese glosa" name="commente"
                                       id="commente" value="{{$docbanco_egreso->glosa}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="paymentwaye">Medio Pago:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control"
                                        name="paymentwaye"
                                        id="paymentwaye">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($mediopagos as $mediopago)
                                        <option value="{{$mediopago->id}}"
                                                @if($mediopago->id == $docbanco_egreso->mediopago['id']) selected @endif>
                                            {{$mediopago->codigo}} | {{$mediopago->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="transferatione"># Trans. Bancaria:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="transferatione"
                                       id="transferatione"
                                       value="{{$docbanco_egreso->transaccion}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="checkchequee" class="check-top">
                                    <input type="checkbox" name="checkchequee" id="checkchequee"
                                     @if($docbanco_egreso->concheque=='1') Checked @endif> Cheque
                                </label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" name="checke" id="checke" readonly
                                       value="{{$docbanco_egreso->nrocheque}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="namee">Nombre:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" name="namee" id="namee" readonly
                                       value="{{$docbanco_egreso->giradoa}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="deferredcheck"><input type="checkbox"
                                  name="deferredcheck" id="deferredcheck" @if($docbanco_egreso->esdiferido=='1') checked @endif>
                                    Diferido
                                </label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="date" name="deferred" id="deferred" readonly
                                       value="{{$docbanco_egreso->fechacobro}}">
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Por el Ingreso</p>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_numeroi">Número:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" value="{{$docbanco_ingreso->numero}}" readonly
                                       name="txt_numeroi"
                                       id="txt_numeroi">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="processdatei">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="date"
                                       name="processdatei"
                                       id="processdatei"
                                       value="{{$docbanco_ingreso->fechaproceso}}"
                                       min="{{$period->inicio}}" max="{{$period->final}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label  for="changeratei">T.C</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="changeratei"
                                       value="{{$docbanco_ingreso->tcambio}}"
                                       id="changeratei" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="period">Operación Ingreso:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control"
                                        name="operationi"
                                        id="operationi">
                                    @foreach($operacionesi as $operacion)
                                        <option value="{{$operacion->id}}"
                                                @if($operacion->id == $docBancoDetalle_ingreso->tipooperacion['id']) selected @endif>
                                            {{$operacion->codigo}} | {{$operacion->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="banki">Banco:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control"
                                        name="banki"
                                        id="banki">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{$banco->id}}"
                                                @if($banco->id == $docbanco_ingreso->banco['id']) selected @endif>
                                            {{$banco->codigo}} | {{$banco->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" value="{{$docbanco_egreso->banco['efectivo']}}" name="cashi"
                                       id="cashi">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="currentaccounti">Cuenta Corriente:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <select class="form-control"
                                        name="currentaccounti"
                                        id="currentaccounti">
                                    @foreach($ctactebankse as $ctactebanse)
                                        <option value="{{$ctactebanse->id}}"
                                                @if($ctactebanse->id == $docBancoDetalle_ingreso->id) selected @endif>
                                            {{$ctactebanse->codigo}} | {{$ctactebanse->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="currencyi">Moneda:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control"
                                        name="currencyi"
                                        id="currencyi">
                                    @foreach($currencies as $currency)
                                        @if($currency->id == $docbanco_ingreso->moneda_id)
                                            <option value="{{$currency->id}}" selected>
                                                {{$currency->codigo}} | {{$currency->descripcion}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_tci">T.C</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="txt_tci"
                                       id="txt_tci"
                                       value="{{$docbanco_ingreso->tcmoneda}}"
                                       readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="amounti">Imp. Recibido:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" id="amounti" name="amounti"
                                       value="{{$docbanco_ingreso->total}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="period">Glosa:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input class="form-control" type="text" placeholder="Ingrese glosa" name="commenti"
                                       id="commenti" value="{{$docbanco_ingreso->glosa}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="transferationi"># Trans. Bancaria:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="transferationi"
                                       id="transferationi"
                                       value="{{$docbanco_ingreso->transaccion}}">
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
    <script src="{{ asset('anikama/ani/transfers.js') }}"></script>
@endsection
