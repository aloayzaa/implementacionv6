@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="col-xs-12">
                    <table id="expenseCatalog"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr role="row">
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Estado</th>
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
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableExpenseCatalog.init('{{ route('list.expenses') }}');
        });
    </script>
@endsection