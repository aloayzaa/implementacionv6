@extends('templates.app')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT"
                  data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $proyecto->id }}">

                <div class="container-fluid" style="background-color: white">
                    <div class="col-md-12 col-xs-12">
                        <div class="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="name">Código</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <input class="form-control" type="text" id="code" name="code" required
                                               value="{{ $proyecto->codigo }}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="tradeName">Descripción</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <input class="form-control" type="text" id="description" name="description"
                                               required value="{{ $proyecto->descripcion }}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="tradeName">Observaciones</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <textarea class="form-control" id="observations"
                                                  name="observations">{{$proyecto->glosa}}</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="tradeName">Fecha Inicio</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <input class="form-control" type="date" id="initialdate" name="initialdate"
                                               required value="{{$proyecto->inicio}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="tradeName">Fecha Térmmino</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <input class="form-control" type="date" id="finishdate" name="finishdate"
                                               required value="{{$proyecto->final}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="col-md-12 col-sm-2 col-xs-12 form-group">
                                            <label class="top-check-down" for="period">
                                                <input type="checkbox" id="chkconfig" name="chkconfig"
                                                       @if($proyecto->encurso == 1) checked @endif>
                                                Configuración para Reconocimiento de los Trabajos en Curso
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <label for="name"> Cuando el proyecto se centraliza con la cuenta </label>
                                    </div>
                                    <div class="col-md-1 col-xs-12">
                                        <input class="form-control" type="text" id="account" name="account"
                                               @if($proyecto->encurso == 0) disabled
                                               @endif value="{{$proyecto->ctareferencia}}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <label for="name">Generar asiento con las siguientes cuentas</label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-1 col-xs-12">
                                        <label for="name">Cuenta Cargo</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <select class="form-control select2" @if($proyecto->encurso == 0) disabled
                                                @endif id="chargeaccount"
                                                name="chargeaccount">
                                            <option value="">Seleccionar..</option>
                                            @foreach($pcgs as $pcg)
                                                <option value="{{$pcg->id}}"
                                                        @if($proyecto->ctacargo_id == $pcg->id) selected @endif>
                                                    {{$pcg->codigo}}
                                                    | {{$pcg->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-1 col-xs-12">
                                        <label for="name">Cuenta Abono</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <select class="form-control select2" @if($proyecto->encurso == 0) disabled
                                                @endif id="paymentaccount"
                                                name="paymentaccount">
                                            <option value="">Seleccionar..</option>
                                            @foreach($pcgs as $pcg)
                                                <option value="{{$pcg->id}}"
                                                        @if($proyecto->ctaabono_id == $pcg->id) selected @endif>
                                                    {{$pcg->codigo}}
                                                    | {{$pcg->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
    <script src="{{ asset('anikama/ani/projects.js') }}"></script>
@endsection
