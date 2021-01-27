@extends('templates.app')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.currencies') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ 0 }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Código</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="code_md" name="code_md" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Descripción</label>
                                </div>
                                <div class="col-md-6 col-xs-12" id="nombre_comercial">
                                    <input class="form-control" type="text" id="description_md" name="description_md">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Cód. Sunat</label>
                                </div>
                                <div class="col-md-6 col-xs-12" id="nombre_comercial">
                                    <input class="form-control" type="text" id="sunat" name="sunat"
                                           required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Símbolo</label>
                                </div>
                                <div class="col-md-6 col-xs-12" id="nombre_comercial">
                                    <input class="form-control" type="text" id="symbol" name="symbol"
                                           required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Tipo Moneda</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <label for=""><input type="checkbox" id="typen" name="typen">
                                        Nacional(Oficial)</label>
                                    <br>
                                    <label for=""><input type="checkbox" id="typee" name="typee">
                                        Extranjera</label>
                                    <br>
                                    <label for=""><input type="checkbox" id="typeo" name="typeo">
                                        Otra Moneda</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/currencies.js') }}"></script>
@endsection
