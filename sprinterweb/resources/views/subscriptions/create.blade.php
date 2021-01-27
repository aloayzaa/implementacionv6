@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" enctype="multipart/form-data" action="{{route('store.subscriptions')}}"
                  data-route="{{ route($var) }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">

                <div class="form-group">
                    <label class="control-label col-md-3" for="version">
                        Empresa
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control select2" lang="es" name="company" id="company" required>
                            <option disabled selected>Seleccionar..</option>
                            @foreach($empresas as $empresa)
                                <option value="{{$empresa->id}}">{{$empresa->emp_codigo}} | {{$empresa->emp_descripcion}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="version">
                        Plan
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control select2"
                                lang="es"
                                name="plan"
                                id="plan"
                                onchange="precio(this.value)"
                                required>
                            <option disabled selected>Seleccionar..</option>
                            @foreach($planes as $plan)
                                <option value="{{$plan->id}}">{{$plan->pla_descripcion}} - ${{$plan->pla_preciome}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="importe">
                        Importe
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="importe"
                               name="importe"
                               readonly
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3">
                        Imagen
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <div class="wrap-custom-file">
                            <input type="file"
                                   name="imagen"
                                   id="image1"
                                   accept=".gif, .jpg, .png"
                                   required/>
                            <label for="image1">
                                <span>Adjuntar imagen</span>
                                <i class="fa fa-plus-circle"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="col-xs-12 form-group text-center">
                    <button type="submit" class="btnCode fifth">
                        Registrar <i class="fa fa-check"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/suscription.js') }}"></script>
@endsection
