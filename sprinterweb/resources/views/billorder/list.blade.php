@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" id="ruta" name="ruta" value="{{ route('store.billorder') }}">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>

                    <div class="row">
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
                    <br>
                    <div class="row">
                        <table id="order-detail-ref" class="table table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>U.M.</th>
                                <th>Cantidad</th>
                                <th>P Unit</th>
                                <th>Dscto %</th>
                                <th>Importe</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <button id="pagar" class="btn btn-primary" disabled>Pagar</button>

                    @include ('billorder.modals.pagar')

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/billorder.js') }}"></script>
    <script>
        $(function () {
            $('#tableSaleOrder tbody').on( 'dblclick', 'tr', function () {

                url= '{{route("edit.saleorder", ":id") }}'
                window.location = url.replace(':id', this.id);

            } );
        });
    </script>
@endsection
