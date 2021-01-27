@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" id="var" name="var" value="{{ $var }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                    <table id="tableExitToWarehouse"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Almacén</th>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Responsable</th>
                            <th>Tipo Movimiento</th>
                            <th>Moneda</th>
                            <th>Total M.N.</th>
                            <th>Total M.E.</th>
                            <th>Glosa</th>
                            <th>Estado</th>
                        </tr>
                        </thead>

                        <tbody></tbody>

                    </table>

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
            tableExitToWarehouse.init('{{ route('list.exittowarehouse') }}');
            $('#tableExitToWarehouse tbody').on( 'dblclick', 'tr', function () {

                url= '{{route("edit.exittowarehouse", ":id") }}'
                window.location = url.replace(':id', this.id);

            } );
        });


    </script>
@endsection
