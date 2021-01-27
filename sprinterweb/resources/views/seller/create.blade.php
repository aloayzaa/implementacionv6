@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.seller') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="txt_codigo_vendedores">Código:</label>
                                <input type="text" id="txt_codigo_vendedores" name="txt_codigo_vendedores" class="form-control">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="txt_descripcion">Descripción:</label>
                                <input type="text" name="txt_descripcion_vendedores" id="txt_descripcion_vendedores" class="form-control">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="txt_telefono">Telefóno:</label>
                                <input type="number" name="txt_telefono" id="txt_telefono" class="form-control">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 label-input">
                                <label for="txt_email">Email:</label>
                                <input type="text" id="txt_email" name="txt_email" class="form-control">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 label-input">
                                <label for="cbo_personal_asociado">Personal Asociado:</label>
                                <select name="cbo_personal_asociado" id="cbo_personal_asociado" class="form-control select2">
                                    <option value="">Seleccionar-</option>
                                    @foreach($personas as $p)
                                        <option value="{{ $p->id }}">{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 label-input">
                                <label for="cbo_usuario_asociado">Usuario Asociado:</label>
                                <select name="cbo_usuario_asociado" id="cbo_usuario_asociado" class="form-control select2">
                                    <option value="">Seleccionar-</option>
                                    @foreach($usuarios as $u)
                                        <option value="{{$u->id}}">{{ $u->codigo }} | {{$u->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 label-input">
                                <label for="cbo_ctacte_asociado">Cta. Cte.Banco:</label>
                                <select name="cbo_ctacte_asociado" id="cbo_ctacte_asociado" class="form-control select2">
                                    <option value="">Seleccionar-</option>
                                    @foreach($ctactebanco as $banco)
                                        <option value="{{ $banco->id }}">{{ $banco->codigo }} | {{ $banco->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 label-input">
                                <br>
                                <label for="chk_aplica_metamarca" style="margin-left: 10px;"><input type="checkbox" name="chk_aplica_metamarca" id="chk_aplica_metamarca" class=""> Aplica Meta por Marca. Si no aplica, se tomará
                                    como meta global del vendedor lo registrado  en la primera fila.</label>
                            </div>
                        </div>
                        <div class="row">
                            <p class="title-view">Comisiones por Marca:</p>
                        </div>
                        @include('seller.comisiones_marca')
                    </div>
                </div>
            </form>
            @include('seller.modals.add_item')
            @include('seller.modals.edit_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/seller.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            SellBrandCommissions.init('{{ route('list_marca_comision.seller') }}');
        });
    </script>
@endsection
