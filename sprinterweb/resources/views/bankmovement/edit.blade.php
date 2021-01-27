@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $banco->id }}">
                <input type="hidden" id="asiento" value="{{ $asiento_id }}">
                <input type="hidden" name="ruta" id="ruta" value="{{ route('update.bankmovement', $banco->id) }}"/>
                <input type="hidden" name="estado" id="estado" value="{{ $banco->estado }}">
                <input type="hidden" id="lagenteret" name="lagenteret" value="{{$lagenteret}}">
                <input type="hidden" id="ruta_estado" name="ruta_estado" value="{{ route('estado.bankmovement') }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="period">Periodo:</label>
                                <input class="form-control" type="text" name="period" id="period"
                                       value="{{$banco->periodo['descripcion']}}" readonly>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="cbo_unidad_negocio">Unidad Negocio:</label>
                                <select name="cbo_unidad_negocio" id="cbo_unidad_negocio" class="select2">
                                    @foreach($unegocios as $unegocio)
                                        <option value="{{$unegocio->id}}" @if($banco->unegocio_id == $unegocio->id) selected @endif>{{$unegocio->codigo}} | {{$unegocio->descripcion}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-xs-12">
                                <label for="txt_numero">Número:</label>
                                <input class="form-control" type="text" name="txt_numero" id="txt_numero" readonly
                                       value="{{$banco->numero}}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txt_fecha">Fecha:</label>
                                <input class="form-control tipocambio" type="date" name="txt_fecha" id="txt_fecha"
                                       min="{{$period->inicio}}" max="{{$period->final}}"
                                       value="{{$banco->fechaproceso}}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="changerate">T.Cambio:</label>
                                <input class="form-control typechange" name="changerate" id="changerate" readonly
                                       value="{{$banco->tcambio}}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="type">Tipo:</label>
                                <select class="form-control select2" name="type" id="type">
                                    <option selected disabled>-Seleccione-</option>
                                    <option value="I" @if($banco->tipo == 'I') selected @endif>INGRESO</option>
                                    <option value="E" @if($banco->tipo == 'E') selected @endif>EGRESO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="sucursal">Sucursal:</label>
                                <select class="form-control select2" name="sucursal" id="sucursal">
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{$sucursal->id}}"
                                                @if($banco->sucursal_id == $sucursal->id) selected @endif>
                                            {{$sucursal->codigo}} | {{$sucursal->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="reftransfer">Ref.Transfer:</label>
                                <input class="form-control" name="reftransfer" id="reftransfer" value="{{$cod_transfer}}"   readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="ln_solid"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label for="bank">Banco:</label>
                                <select class="form-control select2" name="bank" id="bank">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($bancos as $bancoc)
                                        <option value="{{$bancoc->id}}"
                                                @if($bancoc->id == $banco->banco_id) selected @endif>
                                            {{$bancoc->codigo}} | {{$bancoc->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="paymentway">Medio Pago:</label>
                                <select class="form-control select2" name="paymentway" id="paymentway">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($mediopagos as $mediopago)
                                        <option value="{{$mediopago->id}}"
                                                @if($mediopago->id == $banco->mediopago_id) selected @endif>
                                            {{$mediopago->codigo}} | {{$mediopago->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="nrotransaction">Nro.Transacción bancaria:</label>
                                <input class="form-control" id="nrotransaction" name="nrotransaction" type="text"
                                       value="{{$banco->transaccion}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label for="currentaccount">Cuenta corriente:</label>
                                <select class="form-control select2" name="currentaccount" id="currentaccount">
                                    <option value="">-Seleccione-</option>
                                    @foreach($ctacte as $c)
                                        <option value="{{$c->id}}" @if($banco->ctactebanco_id == $c->id) selected @endif data-moneda ="{{$c->moneda_id}}">{{$c->codigo}} | {{$c->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input type="checkbox" id="chkcheque" name="chkcheque"
                                       @if($banco->concheque == 1) checked @endif><label for="transaction">Con
                                    Cheque:</label>
                                <input class="form-control" id="cheque" name="cheque" type="text" @if($banco->concheque == 0) readonly @endif
                                placeholder="Ingrese Cheque" value="{{$banco->nrocheque}}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <input type="checkbox" id="chkdiffer" name="chkdiffer" @if($banco->concheque == 0) disabled @endif
                                @if($banco->esdiferido == 1) checked @endif><label>Es
                                    Diferido:</label>
                                <input class="form-control" type="date" name="differ" id="differ" @if($banco->esdiferido == 0) readonly @endif
                                value="{{$banco->fechacobro}}">
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label for="girado">Girado a nombre de:</label>
                                <input class="form-control" id="girado" name="girado" type="text" @if($banco->concheque == 0) readonly @endif
                                placeholder="Ingrese glosa" value="{{$banco->giradoa}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="currency">Moneda:</label>
                                <select class="form-control select2" name="currency" id="currency" disabled>
                                    <option selected value="{{$banco->moneda_id}}">{{$banco->moneda['codigo']}}
                                        | {{$banco->moneda['descripcion']}}</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txt_tc">T.C:</label>
                                <input class="form-control" type="text" name="txt_tc" id="txt_tc"
                                       value="{{$banco->tcmoneda}}" readonly>
                            </div>
                            <div class="col-md-1 col-xs-12"></div>
                            <div class="col-md-4 col-xs-12">
                                <label for="comment">Glosa:</label>
                                <input class="form-control" id="comment" name="comment" type="text"
                                       placeholder="Ingrese glosa" value="{{$banco->glosa}}">
                            </div>
                            {{--
                            No existe el campo seriecre en la tabla docbanco - ver bd clasic
                            <div class="col-md-2 col-xs-12">
                                <label for="txt_comprobacion_retencion">Serie comprob. retención:</label>
                                <input type="text" name="txt_comprobacion_retencion" id="txt_comprobacion_retencion" class="form-control">
                            </div>
                            --}}
                        </div>
                        <div class="row">
                            <div class="ln_solid"></div>
                        </div>
                        <div class="row">
                            <table id="listBankCashMovementDetail"
                                   class="table table-striped table-bordered" width="100%">
                                <thead>
                                <tr role="row" id="columnas_detalle">
                                    <th></th>
                                    <th>Item</th>
                                    <th>Ope.</th>
                                    <th>Descripción Ope.</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Referencia</th>
                                    <th>Importe</th>
                                    <th>Percepción</th>
                                    <th>Reten.IGV</th>
                                    <th>Reten.Renta</th>
                                    <th>Reten.Pensión</th>
                                    <th>Total</th>
                                    <th>Cuenta</th>
                                    <th>Glosa</th>
                                    <th>CCI</th>
                                    <th>C.Costo</th>
                                    <th>Descripción C.Costo</th>
                                    <th>Actividad</th>
                                    <th>Descripción Actividad</th>
                                    <th>Proyecto</th>
                                    <th>Descripción Proyecto</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            @if($lagenteret == 1)
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="txt_retencion">Reten.I.G.V:</label>
                                    <input type="text" id="txt_retencion" name="txt_retencion" class="form-control" value="{{$calculos['retencion']}}" readonly>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="txt_retencion_renta">Reten.Renta:</label>
                                    <input class="form-control" id="txt_retencion_renta" name="txt_retencion_renta" type="text"
                                           value="{{$calculos['retencion_renta']}}" readonly>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="txt_retencion_pension">Reten.Pensión:</label>
                                    <input class="form-control" id="txt_retencion_pension" name="txt_retencion_pension" type="text"
                                           value="{{$calculos['retencion_pension']}}" readonly>
                                </div>
                            @endif
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="txt_descuento">Descuentos:</label>
                                <input class="form-control" id="txt_descuento" name="txt_descuento" type="text"
                                       value="{{$calculos['descuento']}}" readonly>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="txt_total_detalle">Total:</label>
                                <input class="form-control" id="txt_total_detalle" name="txt_total_detalle" type="text" value="{{$banco->total}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <script src="{{ asset('js/datatables.js') }}"></script>
            @include ('bankmovement.modals.add_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/bankmovement.js') }}"></script>
    <script>
        $(document).ready(function () {
            if (performance.navigation.type == 0) {
                if (document.referrer.includes('create')) {
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
        backButtonRefresh();

        function asiento(asiento_id) {
            return asiento_id != null ? window.location = '{{ route("show.dailyseat", $asiento_id) }}' : alert('No existe asiento')
        }
    </script>
@endsection
