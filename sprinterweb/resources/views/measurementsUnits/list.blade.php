@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                        <input type="hidden" name="var" id="var" @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        <table id="measurementUnit" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="" aria-sort="ascending" aria-label="First name: activate to sort column descending">Código
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="" aria-label="Last name: activate to sort column ascending">Descripción
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 15%;" aria-label="Age: activate to sort column ascending">Estado
                                    </th>
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
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script>
        $(function () {
            tableMeasurementUnit.init('{{ route('list.measurements') }}');
        });
        $('#measurementUnit tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.measurements', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
