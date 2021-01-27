@extends('templates.home')
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT" enctype="multipart/form-data">
                        <input type="hidden" id="ruta" name="ruta" value="{{ route('update.products', $product->id) }}">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="var" name="var" value="{{ $var }}">
                        <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                        <input type="hidden" id="id" name="id" value="{{$product->id}}">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="generales" role="tab" data-toggle="tab" aria-expanded="true">Datos Generales</a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content2" role="tab" id="otros" data-toggle="tab" aria-expanded="false">Otros Detalles</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="generales">
                                @include('products.tabs.general_editar')
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="otros">
                                @include('products.tabs.otros_editar')
                            </div>
                        </div>
                    </form>
                </div>
                @include('products.modal.almacen')
                @include('products.modal.datos_npk')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/products.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            ProductUbicacionAlmacen.init('{{ route('list_ubicacion_almacen.products') }}');
            ProductDatosNPK.init('{{ route('list_datos_npk.products') }}');
            if(performance.navigation.type == 0){
                if(document.referrer.includes('products/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('products/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>
@endsection
