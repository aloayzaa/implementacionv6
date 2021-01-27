@extends('templates.app')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.deductions') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Código</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="code" name="code" required value="{{$codigo}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Descripción</label>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <input class="form-control" type="text" id="description" name="description" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Cod. Sunat</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="sunatcode" name="sunatcode" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Símbolo</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="symbol" name="symbol" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Valor</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="value" name="value" required>
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
    <script src="{{ asset('anikama/ani/deductions.js') }}"></script>
@endsection
