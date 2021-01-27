@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ route('banksopening') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
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
                                        value="{{Session::get('period')}}"
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
                                        value="0000"
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
                                         <option value="{{$sucursal->id}}">
                                             {{$sucursal->codigo}} | {{$sucursal->descripcion}}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-1 col-xs-12 label-input">
                                 <label for="processdate">Fecha:</label>
                             </div>
                             <div class="col-md-2 col-xs-12">
                                 <input type="date" class="form-control" name="processdate" id="processdate"
                                        min="{{$period->inicio}}" max="{{$period->final}}"/>
                             </div>
                             <div class="col-md-2 col-xs-12 label-input">
                                 <label for="changerate">Tipo de cambio:</label>
                             </div>
                             <div class="col-md-2 col-xs-12">
                                 <input class="form-control" name="changerate" id="changerate" readonly>
                             </div>
                         </div>
                         <div class="row">
                             <div class="ln_solid"></div>
                         </div>
                         <div class="row">
                             <div class="col-md-1 col-xs-12 label-input">
                                 <label for="bank">Banco:</label>
                             </div>
                             <div class="col-md-4 col-xs-12">
                                 <select class="form-control"
                                         name="bank"
                                         id="bank">
                                     <option selected disabled>-Seleccione-</option>
                                     @foreach($bancos as $banco)
                                         <option value="{{$banco->id}}">
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
                                 </select>
                             </div>
                             <div class="col-md-1 col-xs-12 label-input">
                                 <label for="txt_tc">T.C:</label>
                             </div>
                             <div class="col-md-1 col-xs-12">
                                 <input class="form-control"
                                        type="text"
                                        name="txt_tc"
                                        id="txt_tc"
                                        value="0.000"
                                        readonly>
                             </div>
                             <div class="col-md-2 col-xs-12 label-input">
                                 <label for="total">Total:</label>
                             </div>
                             <div class="col-md-4 col-xs-12">
                                 <input type="text" class="form-control" name="total" id="total" value="0.00">
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-1 col-xs-12 label-input">
                                 <label for="customer">Cliente/Proveedor:</label>
                             </div>
                             <div class="col-md-4 col-xs-12">
                                 <select class="form-control"
                                         name="customer"
                                         id="customer">
                                     <option selected disabled>-Seleccione-</option>
                                     @foreach($clientes as $cliente)
                                         <option value="{{$cliente->id}}">
                                             {{$cliente->codigo}} | {{$cliente->descripcion}}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                             <div class="col-md-2 col-xs-12">
                                 {{--espacio--}}
                             </div>
                             <div class="col-md-2 col-xs-12">
                                 <label for="check" style="font-weight: 700;">
                                     <input id="check" name="check" type="checkbox">Con cheque
                                 </label>
                             </div>
                         </div>
                         <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="comment">Glosa:</label>
                            </div>
                             <div class="col-md-4 col-xs-12">
                                 <input class="form-control" id="comment" name="comment" type="text"
                                        placeholder="Ingrese glosa">
                             </div>
                             <div class="col-md-2 col-xs-12">
                                 {{--espacio--}}
                             </div>
                             <div class="col-md-4 col-xs-4">
                                 <input class="form-control" id="checktxt" name="checktxt" type="text"
                                        placeholder="Ingrese cheque" readonly>
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
                                        value="0.00">
                             </div>
                             <div class="col-md-1 col-xs-12">
                                 <label for="totalme">Total M.E:</label>
                             </div>
                             <div class="col-md-1 col-xs-12">
                                 <input class="form-control"
                                        type="text"
                                        name="totalme"
                                        id="totalme"
                                        value="0.00">
                             </div>
                         </div>
                         <div class="row">
                             <div class="ln_solid"></div>
                         </div>
                         <div class="row">
                             <div class="col-xs-12 form-group text-center">
                                 <a id="btn_grabar" class="btnCode fifth">
                                     Registrar <i class="fa fa-check"></i>
                                 </a>
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
    <script src="{{ asset('anikama/ani/banksopening.js') }}"></script>
@endsection
