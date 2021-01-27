@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  data-route="{{ route('categories') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">

                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código:</label>
                                </div>

                                <div class="col-md-5 col-xs-12" >
                                    <input class="form-control"
                                           type="text"
                                           id="code"
                                           name="code"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Descripción:</label>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           id="name"
                                           name="name"
                                           required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codSunat">Código sunat:</label>
                                </div>

                                <div class="col-md-5 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           id="codSunat"
                                           name="codSunat">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="account">Simbolo:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           id="codSimbolo"
                                           name="codSimbolo">
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                    <div class="ln_solid"></div>
                                </div>

{{--                                <div class="col-xs-12 form-group text-center">--}}
{{--                                    <a id="btn_grabar" class="btnCode fifth">--}}
{{--                                        Registrar <i class="fa fa-check"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-xs-12 form-group text-center">--}}
{{--                                <button type="button" class="btn btn-primary" onclick="store()" >Registrar</button>--}}
{{--                            </div>--}}
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
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/categories.js') }}"></script>
@endsection
