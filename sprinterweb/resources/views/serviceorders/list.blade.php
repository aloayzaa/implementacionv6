@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <table id="listServiceOrders" class="table table-striped table-bordered table-bordered2 tbl_ctacte" width="100%">
                                <thead>
                                <tr role="row">
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Mon.</th>
                                    <th>Base Imp.</th>
                                    <th>Inafecto</th>
                                    <th>Impuesto</th>
                                    <th>Total</th>
                                    <th>Glosa</th>
                                    <th>Estado</th>
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
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableListServiceOrders.init('{{ route('list.serviceorders') }}');
        });

        $('#listServiceOrders tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.serviceorders', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
