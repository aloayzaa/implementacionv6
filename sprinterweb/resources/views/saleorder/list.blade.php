@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="var" name="var" value="{{ $var }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>

                    <table id="tableSaleOrder"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Pto. Venta</th>
                            <th>Fecha</th>
                            <th>Número</th>
                            <th>Código</th>
                            <th>Razón social</th>
                            <th>Moneda</th>
                            <th>Total</th>
                            <th>Tipo Venta</th>
                            <th>Estado</th>
                            <th></th>
                            <th></th>

                        </tr>
                        </thead>

                        <tbody></tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableSaleOrder.init('{{ route('list.saleorder') }}');
            $('#tableSaleOrder tbody').on( 'dblclick', 'tr', function () {

                url= '{{route("edit.saleorder", ":id") }}'
                window.location = url.replace(':id', this.id);

            } );
        });
    </script>
@endsection
