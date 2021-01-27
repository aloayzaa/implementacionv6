@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT"
                  data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                <input type="hidden" id="id_empresa" name="id_empresa" value="{{ $id_empresa }}">
                <input type="hidden" id="instancia" name="instancia" value="{{$instancia}}">

                <div class="form-group">
                    <label class="control-label col-md-3" for="email">
                        Correo electrónico
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->usu_correo) }}"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="password">
                        Contraseña
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input id="password"
                               type="password"
                               name="password"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="password_confirmation">
                        Repetir contraseña
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="name">
                        Apellidos
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               class="form-control"
                               name="lastname"
                               id="lastname"
                               value="{{ old('lastname', $user->usu_apellidos) }}"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="name">
                        Nombres
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               class="form-control"
                               name="name"
                               id="name"
                               value="{{ old('name', $user->usu_nombres) }}"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="phone">
                        Teléfono
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user->usu_telefono) }}"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <label class="control-label col-md-3">
                        Empresas
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control"
                                name="company"
                                id="company">
                            <option disabled selected>Seleccionar</option>
                            @foreach($companies  as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="company">
                        Menú
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <select class="form-control" id="menu" name="menu">
                            <option disabled selected>Seleccionar</option>
                            @foreach($menus  as $menu)
                                <option value="{{$menu->codigo}}">{{ $menu->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <input type="hidden" name="routelistoptions" id="routelistoptions" value="{{$routemenu}}">
                    <table id="listMenuOptions"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Opción</th>
                            <th>Ver</th>
                            <th>Crea</th>
                            <th>Edita</th>
                            <th>Anula</th>
                            <th>Borra</th>
                            <th>Print</th>
                            <th>Aprobado</th>
                            <th>Precio</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </form>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/user.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableMenuOptions.init('{{ route('listmenuoptions.users') }}');
        });
    </script>
@endsection

