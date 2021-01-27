@extends('templates.app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="var" id="var" value="{{$var}}">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <table id="listPurchaseOrder"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Mon</th>
                            <th>Base Imp.</th>
                            <th>Inafecto</th>
                            <th>Impuesto</th>
                            <th>Total</th>
                            <th>Glosa</th>
                            <th>Estado</th>
                            <th>Pto.Emisión</th>
                            <th></th>
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
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tablePurchaseOrder.init('{{ route('list.purchaseorder') }}');
        });
        $('#listPurchaseOrder tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.purchaseorder', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
