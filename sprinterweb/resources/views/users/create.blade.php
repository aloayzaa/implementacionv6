@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">

                <div class="form-group">
                    <label class="control-label col-md-3" for="email">
                        Correo electrónico
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="email"
                               id="email"
                               name="email"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="password">
                        Contraseña
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="password"
                               id="password"
                               name="password"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="password_confirmation">
                        Repetir contraseña
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="lastname">
                        Apellidos
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="lastname"
                               name="lastname"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="name">
                        Nombres
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="name"
                               name="name"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="phone">
                        Teléfono
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="phone"
                               name="phone"
                               required>
                    </div>
                </div>
                <div class="ln_solid"></div>

                <div class="form-group" id="empresas">
                    <label class="control-label col-md-3" for="company">
                        Empresa
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control"
                                id="company"
                                name="company">
                            <option disabled selected>Seleccionar</option>

                        </select>
                    </div>
                    <a class="icon-button plus" name="btn_agregar_cart" id="btn_agregar_cart"
                       data-toggle="tooltip" data-placement="right" title="Agregar"
                       onclick="agregar_item('{{$instancia}}', '{{$var}}')">
                        <i class="fa fa-plus"></i><span></span>
                    </a>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="company">
                        Rol
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control"
                                id="rol"
                                name="rol">
                            <option disabled selected>Seleccionar</option>
                            @foreach($roles  as $rol)
                                @if($rol->id != 3)
                                    <option value="{{ $rol->id }}">{{ $rol->rol_nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="detalle_users"></div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/user.js') }}"></script>
@endsection
