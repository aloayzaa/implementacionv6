@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{0}}">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.cashmovement') }}">
                <input type="hidden" id="lagenteret" name="lagenteret" value="{{$lagenteret}}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="period">Periodo:</label>
                                <input class="form-control" type="text" name="period" id="period"
                                       value="{{Session::get('period')}}" readonly>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="cbo_unidad_negocio">Unidad Negocio:</label>
                                <select name="cbo_unidad_negocio" id="cbo_unidad_negocio" class="select2">
                                    @foreach($unegocios as $unegocio)
                                        <option value="{{$unegocio->id}}">{{$unegocio->codigo}} | {{$unegocio->descripcion}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-xs-12">
                                <label for="txt_numero">Número:</label>
                                <input class="form-control" type="text" name="txt_numero" id="txt_numero" value="0000"
                                       readonly>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txt_fecha">Fecha:</label>
                                <input class="form-control tipocambio" type="date" name="txt_fecha" id="txt_fecha"
                                       min="{{$period->inicio}}" max="{{$period->final}}" value="{{ $today }}">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="changerate">T.Cambio:</label>
                                <input class="form-control typechange" name="changerate" id="changerate" readonly>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="type">Tipo:</label>
                                <select class="form-control select2" name="type" id="type">
                                    <option value="I">INGRESO</option>
                                    <option value="E">EGRESO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="sucursal">Sucursal:</label>
                                <select class="form-control select2" name="sucursal" id="sucursal">
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{$sucursal->id}}">{{$sucursal->codigo}}
                                            | {{$sucursal->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="reftransfer">Ref.Transfer:</label>
                                <input class="form-control" name="reftransfer" id="reftransfer" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="ln_solid"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label for="bank">Caja:</label>
                                <select class="form-control select2" name="bank" id="bank">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{$banco->id}}">
                                            {{$banco->codigo}} | {{$banco->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="paymentway">Medio Pago:</label>
                                <select class="form-control select2" name="paymentway" id="paymentway">
                                    <option selected disabled>-Seleccione-</option>
                                    @foreach($mediopagos as $mediopago)
                                        <option value="{{$mediopago->id}}">
                                            {{$mediopago->codigo}} | {{$mediopago->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label for="currentaccount">Cuenta corriente:</label>
                                <select class="form-control select2" name="currentaccount" id="currentaccount">
                                    <option selected disabled>-Seleccione-</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="cobrador">Cobrador</label>
                                <select class="form-control select2" name="cobrador" id="cobrador">
                                    <option value="" selected disabled>-Seleccione-</option>
                                    @foreach($cobradores as $cobrador)
                                        <option value="{{$cobrador->id}}">
                                            {{$cobrador->codigo}} | {{$cobrador->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- preguntar en el clasic esta, pero no se guarda
                            <div class="col-md-3 col-xs-12">
                                <label for="cheque">Cheque:</label>
                                <input class="form-control" id="cheque" name="cheque" type="text" placeholder="Ingrese Cheque">
                            </div>
                             --}}
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="currency">Moneda:</label>
                                <select class="form-control select2" disabled name="currency" id="currency">
                                    <option selected disabled>-Seleccione-</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txt_tc">T.C:</label>
                                <input class="form-control" type="text" name="txt_tc" id="txt_tc" value="0.000"
                                       readonly>
                            </div>
                            <div class="col-md-1 col-xs-12"></div>
                            <div class="col-md-4 col-xs-12">
                                <label for="comment">Glosa:</label>
                                <input class="form-control" id="comment" name="comment" type="text"
                                       placeholder="Ingrese glosa">
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
                                <tr role="row">
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
                                    <input type="text" id="txt_retencion" name="txt_retencion" class="form-control" value="0.00" readonly>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="txt_retencion_renta">Reten.Renta:</label>
                                    <input class="form-control" id="txt_retencion_renta" name="txt_retencion_renta" type="text"
                                           value="0.00" readonly>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label for="txt_retencion_pension">Reten.Pensión:</label>
                                    <input class="form-control" id="txt_retencion_pension" name="txt_retencion_pension" type="text"
                                           value="0.00" readonly>
                                </div>
                            @endif
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="txt_descuento">Descuentos:</label>
                                <input class="form-control" id="txt_descuento" name="txt_descuento" type="text"
                                       value="0.00" readonly>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="txt_total_detalle">Total:</label>
                                <input class="form-control" id="txt_total_detalle" name="txt_total_detalle" type="text" value="0.00" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <script src="{{ asset('js/datatables.js') }}"></script>
            @include ('cashmovement.modals.add_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/cashmovement.js') }}"></script>
    <script>
        backButtonRefresh();
    </script>
@endsection
