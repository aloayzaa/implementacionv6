@extends('templates.app')

@section('content')

    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" id="var" name="var" value="{{ $var }}">
                    <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <table id="tableBilling"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Código</th>
                            <th>Razón social</th>
                            <th>Mon</th>
                            <th>Subtotal</th>
                            <th>Impuestos</th>
                            <th>Total</th>
                            <th>Pto.Venta</th>
                            <th>Tipo Venta</th>
                            <th>Glosa</th>
                            <th>Estado</th>
                            <th>Anular</th>
                            <th>Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <pre></pre>
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
            tableBilling.init('{{ route('list.billing') }}');
        });

        $('#tableBilling tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.billing', ':id')}}';
            window.location = url.replace(':id', this.id);
        });

    </script>
@endsection




