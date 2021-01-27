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
                  method="POST" data-route="{{ route('openingseat') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="instancia" name="instancia" value="{{ $instancia }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="period">Periodo:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" id="period" name="period"
                                       value="{{Session::get('period')}}" readonly>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="subdiaryd">Subdiario:</label>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control select2" name="subdiary" id="subdiary">
                                    <option disabled selected>Seleccionar</option>
                                    @foreach($subdiarios as $subiario)
                                        <option value="{{$subiario->id}}">{{$subiario->codigo}}
                                            | {{$subiario->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_numero">Número:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" name="txt_numero"
                                       id="txt_numero" value="00000" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="processdate">Fecha:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="date" class="form-control" name="processdate"
                                       id="processdate" max="{{$period->final}}" min="{{$period->inicio}}">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="changerate">T.Cambio:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" class="form-control" name="changerate" value="0.00"
                                       id="changerate" readonly>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                {{--espacio--}}
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="currency">Moneda:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control select2" id="currency" name="currency">
                                    <option disabled selected>Seleccionar..</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->codigo}}
                                            | {{$currency->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="comment">Glosa:</label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input type="text" class="form-control" placeholder="Ingrese código" name="comment"
                                       id="comment">
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Detalle Contable</p>
                        </div>
                        <div class="row">
                            <div id="{{$instancia}}"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="tcargomn">Totales M.N:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" value="0.00" readonly name="tcargomn"
                                       id="tcargomn">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" value="0.00" readonly name="tabonomn"
                                       id="tabonomn">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" value="0.00" readonly name="totalmn"
                                       id="totalmn">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label>Totales M.E:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" value="0.00" readonly name="tcargome"
                                       id="tcargome">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" value="0.00" readonly name="tabonome"
                                       id="tabonome">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control" type="text" value="0.00" readonly name="totalme"
                                       id="totalme">
                            </div>
                        </div>
                    </div>
                </div>
                @include('openingseat.modal_asiento_apertura')
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/openingseat.js') }}"></script>
@endsection
