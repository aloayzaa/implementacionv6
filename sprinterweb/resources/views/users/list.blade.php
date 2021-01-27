@extends('templates.app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                    <table id="listUser"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
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
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableListUsers.init('{{ route('list.users', ['id_empresa' => Session::get('id_empresa')]) }}');
        });

        $('#listUser tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.users', [':id', 'id_empresa' => Session::get('id_empresa')])}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
