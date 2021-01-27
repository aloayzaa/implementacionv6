@extends('templates.app')

@section('content')

    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.customers', $tercero->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $tercero->id }}">

                <div class="panel panel-default">
                    <div id="carga"></div>
                    <div class="panel-body">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="generales" role="tab" data-toggle="tab" aria-expanded="true">Datos Generales</a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content2" role="tab" id="cliente_provedor" data-toggle="tab" aria-expanded="false">Datos Cliente/Proveedor</a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content3" role="tab" id="otros_datos" data-toggle="tab" aria-expanded="false">Otros Datos</a>
                            </li
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="generales">
                                @include('customers.tabs.generales_editar')
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="cliente_provedor">
                                @include('customers.tabs.clientes_proveedor_editar')
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="otros_datos">
                                @include('customers.tabs.otros_datos_editar')
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include('customers.modal.contacto')
            @include('customers.modal.cuentas_bancarias')
            @include('customers.modal.marcas')
            @include('customers.modal.rubros')
            @include('customers.modal.tercero_direccion')
            @include('customers.modal.tercero_empresa')
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/customer.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            TerceroContacto.init('{{ route('list_contactos.customers') }}');
            TerceroCuentaBancarias.init('{{ route('list_cuentas_bancarias.customers') }}');
            TerceroMarca.init('{{ route('list_tercero_marca.customers') }}');
            TerceroRubro.init('{{ route('list_tercero_rubro.customers') }}');
            TerceroDireccion.init('{{ route('list_tercero_direccion.customers') }}');
            TerceroEmpresa.init('{{ route('list_tercero_empresa.customers') }}');
            if(performance.navigation.type == 0){
                if(document.referrer.includes('customers/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
    </script>

@endsection
