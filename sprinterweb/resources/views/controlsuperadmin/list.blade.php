@extends('templates.app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" name="var" id="var" value="{{ $var }}"/>
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                    <table id="listAccounting"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Empresa Principal</th>
                            <th>Contacto</th>
                            <th>Fecha Registro</th>
                            <th>Teléfono</th>
                            <th>Empresas</th>
                            <th>Usuarios</th>
                            <th>Suscripcion</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableListAccounting.init('{{ route('list.superadmin') }}');
        });
    </script>
@endsection
