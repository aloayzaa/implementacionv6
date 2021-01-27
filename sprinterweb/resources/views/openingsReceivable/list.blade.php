@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="hidden" name="proceso" id="proceso"
                                   @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                            <input type="hidden" name="route" id="route"
                                   @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                            <input type="hidden" name="var" id="var"
                                   @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <table id="openingReceivableCatalog"
                                   class="table display responsive nowrap table-striped table-hover table-bordered"
                                   width="100%">
                                <thead>
                                <tr role="row">
                                    <th>Documento</th>
                                    <th>Código</th>
                                    <th>Razón social/Nombre Completo</th>
                                    <th>Fecha</th>
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

                    <pre></pre>
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
            tableOpeningReceivableCatalog.init('{{ route('list.openingReceivable') }}');
        });
        $('#openingReceivableCatalog tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.openingReceivable', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection




