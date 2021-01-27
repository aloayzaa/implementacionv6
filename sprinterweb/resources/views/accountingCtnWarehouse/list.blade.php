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
                    <table id="tableAccountingCentralizationWarehouse"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th></th>
                            <th></th>
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
            tableAccountingCentralizationWarehouse.init('{{ route('list.accountingctnwarehouse') }}');
            $('#tableAccountingCentralizationWarehouse tbody').on( 'dblclick', 'tr', function () {

                url= '{{route("edit.accountingctnwarehouse", ":id") }}';
                window.location = url.replace(':id', this.id);

            } );
        });
    </script>
@endsection
