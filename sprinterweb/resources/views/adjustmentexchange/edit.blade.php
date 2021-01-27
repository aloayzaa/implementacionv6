@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" name="ruta" id="ruta" value="{{ route('update.adjustmentexchange', $notacontable->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="instancia" name="instancia" value="{{ $instancia }}">
                <input type="hidden" name="id" id="id" value="{{$notacontable->id}}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                                <label for="period">Periodo:</label>
                                <input type="text" class="form-control" id="period" name="period" value="{{Session::get('period')}}" readonly>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 label-input">
                                <label for="buy">Unidad Negocio:</label>
                                <select class="form-control select2" name="cbo_unegocio" id="cbo_unegocio">
                                    @foreach($unegocio as $un)
                                        <option value="{{$un->id}}" @if($un->id == $notacontable->unegocio_id) selected @endif>{{$un->codigo}} | {{$un->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 label-input">
                                <label for="subdiary">Subdiario:</label>
                                <select class="form-control select2" name="subdiary" id="subdiary">
                                    <option value="">-Seleccionar-</option>
                                    @foreach($subdiarios as $subiario)
                                        <option value="{{$subiario->id}}" @if($subiario->id == $notacontable->subdiario_id) selected @endif>{{$subiario->codigo}} | {{$subiario->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                                <label for="txt_numero">Número:</label>
                                <input type="text" class="form-control" name="txt_numero" id="txt_numero" value="{{$notacontable->numero}}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="processdate">Fecha:</label>
                                <input type="date" class="form-control tipocambio" name="processdate" id="txt_fecha" max="{{$period->final}}" min="{{$period->inicio}}" value="{{ $notacontable->fechaproceso }}">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="changeratecompra">T.C.Compra:</label>
                                <input type="text" class="form-control typechangecompra" name="changeratecompra" id="changeratecompra" readonly>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="changerate">T.C.Venta:</label>
                                <input type="text" class="form-control typechange" name="changerate"  id="changerate" readonly>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="currency">Moneda:</label>
                                <select class="form-control select2" id="currency" name="currency">
                                    <option disabled selected>-Seleccionar-</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}" @if($currency->id == $notacontable->moneda_id) selected @endif>{{$currency->codigo}} | {{$currency->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-xs-12 label-input">
                                <label for="">Sucursal:</label>
                                <select class="form-control select2" id="sucursal_id" name="sucursal_id">
                                    <option value="">-Seleccionar-</option>
                                    @foreach($sucursal as $sucursal)
                                        <option value="{{$sucursal->id}}" @if($sucursal->id == $notacontable->sucursal_id) selected @endif>{{$sucursal->codigo}} | {{$sucursal->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12 label-input">
                                <label for="comment">Glosa:</label>
                                <input type="text" class="form-control" placeholder="Ingrese código" name="comment" id="comment" value="{{$notacontable->glosa}}">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-4">
                                <br>
                                <button type="button" class="form-control btn-primary" id="btn_procesar"
                                        name="btn_grabar">
                                    <span class="fa fa-refresh"></span> Procesar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Detalle del Documento</p>
                            @include('adjustmentexchange.detalle_adjustmentexchange')
                        </div>
                        <div>
                            <input type="hidden" name="totalmn" id="totalmn">
                            <input type="hidden" name="totalme" id="totalme">
                            <input type="hidden" name="cargomn" id="cargomn">
                            <input type="hidden" name="cargome" id="cargome">
                            <input type="hidden" name="abonomn" id="abonomn">
                            <input type="hidden" name="abonome" id="abonome">
                            <input type="hidden" name="txtnum1" id="txtnum1">
                            <input type="hidden" name="txtnum2" id="txtnum2">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/adjustmentexchange.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableOpeningSeatDetail.init('{{ route('listdetail.adjustmentexchange') }}');
            if(performance.navigation.type == 0){
                if(document.referrer.includes('adjustmentexchange/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('adjustmentexchange/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>
@endsection
