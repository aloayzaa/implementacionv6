@extends('templates.home')

@section('content')

    <div class="x_panel">

        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ route('taxes') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="container-fluid" style="background-color: white">
                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Código </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="code" name="code"
                                               required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Descripción </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="description" name="description"
                                               required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Nombre Corto </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="name" name="name" required
                                               placeholder="Ingrese código Sunat">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-2">
                                        <label for="name">Tipo Calculo </label>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <select class="form-control select2" id="type" name="type" required>
                                            <option value="P">Porcentual</option>
                                            <option value="N">Nominal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-xs-12">
                                        <label for="name">Valor </label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <input class="form-control" type="text" id="value" name="value" required
                                               value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-12">
                        <div class="panel panel-default" style="height: 220px;">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12"></div>
                                    <div class="col-md-4 col-xs-12">
                                        <label> Desde </label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <label> Hasta </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label>Vigencia:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="date" class="form-control alinear_derecha" name="initialdate"
                                               id="initialdate">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="date" class="form-control alinear_derecha" name="finaldate"
                                               id="finaldate">
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <input type="checkbox" class="" id="checkigv" name="checkigv">
                                        Es Impuesto a las ventas (Valor agregado)
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <input type="checkbox" class="" id="checkbase" name="checkbase">
                                        Resta a la base imponible
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <label> Régimen Pensionario:</label>
                                    </div>
                                    <div class="col-md-8 col-xs-12">
                                        <select class="form-control select2" id="pension" name="pension" disabled>
                                            <option disabled selected value="0">Seleccionar..</option>
                                            @foreach($pensiones as $pension)
                                                <option value="{{$pension->id}}">{{$pension->codigo}}
                                                    | {{$pension->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-12">
                        <div class="panel panel-default" style="height: 220px;">
                            <div class="panel-heading">
                                <input type="checkbox" class="" id="checkretention" name="checkretention"> Es para
                                retención
                            </div>
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-2 col-xs-12">
                                        <label class=""> Base para retención:</label>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <input type="text" class="form-control" name="retentionbase"
                                               id="retentionbase" readonly>
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <label class=""> Base para calculo:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <select class="form-control select2" id="calculationbase" name="calculationbase"
                                                disabled>
                                            <option value="0">Ninguno</option>
                                            <option value="B">Base afecta</option>
                                            <option value="I">Inafecto</option>
                                            <option value="A">Base afecta + Inafecto</option>
                                            <option value="T">Total</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <label>Tipo Documento que genera la retención:</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-12">
                                        <select class="form-control select2" disabled
                                                id="retentiontype" name="retentiontype">
                                            <option selected disabled value="0">Seleccionar..</option>
                                            @foreach($documentocompras as $documentocompra)
                                                <option value="{{$documentocompra->id}}">{{$documentocompra->codigo}}
                                                    | {{$documentocompra->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <label> Retención a nombre de (si blanco, el mismo del documento que
                                            retiene):</label>
                                    </div>
                                    <div class="col-md-8 col-xs-12">
                                        <select class="form-control select2" disabled
                                                id="retentionname" name="retentionname">
                                            <option selected disabled value="0">Seleccionar..</option>
                                            @foreach($terceros as $tercero)
                                                <option value="{{$tercero->id}}">{{$tercero->codigo}}
                                                    | {{$tercero->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-body">
                            <div class="row">
                                <div class="col-md-1 col-xs-12">
                                    <label>Cuenta M.N.: </label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" id="cuentamn"
                                            name="cuentamn">
                                        <option selected disabled value="0">Seleccionar..</option>
                                        @foreach($pcgs as $pcg)
                                            <option value="{{$pcg->id}}">{{$pcg->codigo}}
                                                | {{$pcg->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-xs-12">
                                    <label>Cuenta M.E.: </label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" id="cuentame"
                                            name="cuentame">
                                        <option selected disabled value="0">Seleccionar..</option>
                                        @foreach($pcgs as $pcg)
                                            <option value="{{$pcg->id}}">{{$pcg->codigo}}
                                                | {{$pcg->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <div class="ln_solid"></div>
                            </div>

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
    <script src="{{ asset('anikama/ani/taxes.js') }}"></script>
@endsection
