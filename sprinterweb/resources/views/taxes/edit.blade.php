@extends('templates.home')

@section('content')

    <div class="x_panel">

        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT" data-route="{{ route('taxes') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" name="id" id="id" value="{{$impuesto->id}}">
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
                                               required value="{{$impuesto->codigo}}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Descripción </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="description" name="description"
                                               required value="{{$impuesto->descripcion}}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Nombre Corto </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="name" name="name" required
                                               placeholder="Ingrese código Sunat" value="{{$impuesto->nombrecorto}}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-2">
                                        <label for="name">Tipo Calculo </label>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <select class="form-control select2" id="type" name="type" required>
                                            <option value="P" @if($impuesto->tipocalculo=='P') selected @endif>
                                                Porcentual
                                            </option>
                                            <option value="N" @if($impuesto->tipocalculo=='N') selected @endif>Nominal
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-xs-12">
                                        <label for="name">Valor </label>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <input class="form-control" type="text" id="value" name="value" required
                                               value="{{$impuesto->valor}}">
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
                                               id="initialdate" value="{{$impuesto->vigentedesde}}">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="date" class="form-control alinear_derecha" name="finaldate"
                                               id="finaldate" value="{{$impuesto->vigentehasta}}">
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <input type="checkbox" class="" id="checkigv" name="checkigv"
                                               @if($impuesto->iva==1) checked @endif>
                                        Es Impuesto a las ventas (Valor agregado)
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <input type="checkbox" class="" id="checkbase" name="checkbase"
                                               @if($impuesto->restabase==1) checked @endif>
                                        Resta a la base imponible
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <label> Régimen Pensionario:</label>
                                    </div>
                                    <div class="col-md-8 col-xs-12">
                                        <select class="form-control select2" id="pension" name="pension"
                                                @if($impuesto->restabase == 0) disabled @endif>
                                            <option disabled selected value="0">Seleccionar..</option>
                                            @foreach($pensiones as $pension)
                                                <option value="{{$pension->id}}"
                                                        @if($impuesto->tiporegpension['id'] == $pension->id) selected @endif>
                                                    {{$pension->codigo}} | {{$pension->descripcion}}</option>
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
                                <input type="checkbox" class="" id="checkretention" name="checkretention"
                                       @if($impuesto->retencion == 1) checked @endif> Es para
                                retención
                            </div>
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-2 col-xs-12">
                                        <label class=""> Base para retención:</label>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <input type="text" class="form-control" name="retentionbase"
                                               id="retentionbase" @if($impuesto->retencion == 0) readonly
                                               @endif value="{{$impuesto->baseretencion}}">
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <label class=""> Base para calculo:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <select class="form-control select2" id="calculationbase" name="calculationbase"
                                                @if($impuesto->retencion == 0) disabled @endif>
                                            <option value="0">Ninguno</option>
                                            <option value="B" @if($impuesto->calculoret=='B') selected @endif>Base
                                                afecta
                                            </option>
                                            <option value="I" @if($impuesto->calculoret=='I') selected @endif>Inafecto
                                            </option>
                                            <option value="A" @if($impuesto->calculoret=='A') selected @endif>Base
                                                afecta + Inafecto
                                            </option>
                                            <option value="T" @if($impuesto->calculoret=='T') selected @endif>Total
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <label>Tipo Documento que genera la retención:</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-12">
                                        <select class="form-control select2" @if($impuesto->retencion == 0) disabled
                                                @endif
                                                id="retentiontype" name="retentiontype">
                                            <option selected disabled value="0">Seleccionar..</option>
                                            @foreach($documentocompras as $documentocompra)
                                                <option value="{{$documentocompra->id}}"
                                                        @if($impuesto->documento['id'] == $documentocompra->id) selected @endif>
                                                    {{$documentocompra->codigo}}
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
                                        <select class="form-control select2" @if($impuesto->retencion == 0) disabled
                                                @endif
                                                id="retentionname" name="retentionname">
                                            <option selected disabled value="0">Seleccionar..</option>
                                            @foreach($terceros as $tercero)
                                                <option value="{{$tercero->id}}"
                                                        @if($impuesto->tercero['id'] == $tercero->id) selected @endif>
                                                    {{$tercero->codigo}} | {{$tercero->descripcion}}</option>
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
                                            <option value="{{$pcg->id}}"
                                                    @if($impuesto_contab->cuentamn['id'] == $pcg->id) selected @endif>
                                                {{$pcg->codigo}} | {{$pcg->descripcion}}</option>
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
                                            <option value="{{$pcg->id}}"
                                                    @if($impuesto_contab->cuentame['id'] == $pcg->id) selected @endif>
                                                {{$pcg->codigo}} | {{$pcg->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <div class="ln_solid"></div>
                            </div>

                            <div class="col-xs-12 form-group text-center">
                                <a id="btn_editar" class="btnCode fifth">
                                    Editar <i class="fa fa-check"></i>
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
