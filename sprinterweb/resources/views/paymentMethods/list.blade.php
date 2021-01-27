@extends('templates.app')

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">

                <div id="datatable-responsive_wrapper"
                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">

                         <input type="hidden" id="var" name="var" value="{{ $var }}">
                         <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">

                          <table id="tablePaymentMethods"
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
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tablePaymentMethods.init('{{ route('list.paymentMethods') }}');
        });
        $('#tablePaymentMethods tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.paymentMethods', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
