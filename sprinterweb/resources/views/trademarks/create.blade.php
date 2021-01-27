@extends('templates.home')
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.trademarks') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Codigo:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="code_marca" name="code_marca" required value="{{$codigo}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Descripcion:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="tradeName" name="tradeName">
                                </div>
                            </div>
                            <br>
                        </div>
                        @include('trademarks.listado_modelos')
                    </div>
                </div>
            </form>
            @include('trademarks.modals.add_item')
            @include('trademarks.modals.edit_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/marca.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            BrandModels.init('{{ route('lista_modelo.trademarks') }}');
        });
    </script>
@endsection
