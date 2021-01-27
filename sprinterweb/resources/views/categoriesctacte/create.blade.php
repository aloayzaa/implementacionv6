@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.categoriesctacte') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_codigo_categoriasctacte">Código:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="text" id="txt_codigo_categoriasctacte" name="txt_codigo_categoriasctacte" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_descripcion_categoriasctacte">Descripción:</label>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="text" name="txt_descripcion_categoriasctacte" id="txt_descripcion_categoriasctacte" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="cbo_origen">Origen:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <select name="cbo_origen" id="cbo_origen" class="form-control">
                                    <option value="">Seleccionar -</option>
                                    <option value="C">Cobrar</option>
                                    <option value="P">Pagar</option>
                                </select>
                            </div>
                        </div>
                        @include('categoriesctacte.doc_asignados')
                    </div>
                </div>
            </form>
            @include('categoriesctacte.modals.add_item')
            @include('categoriesctacte.modals.edit_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/categoriesctacte.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            TypesAssignedDocuments.init('{{ route('list_docuemntos_asignados.categoriesctacte') }}');
        });
    </script>
@endsection
