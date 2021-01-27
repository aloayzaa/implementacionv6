@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id_empresa" name="id_empresa" value="{{$id_empresa}}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">

                <div class="form-group">
                    <label class="control-label col-md-3" for="txt_ruc">
                        Registro Único de Contribuyentes
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="txt_ruc"
                               name="txt_ruc"
                               onchange="busca_ruc()"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="txt_empresa">
                        Razón social
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="txt_empresa"
                               name="txt_empresa"
                               tabindex="-1"
                               required readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="address">
                        Dirección
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="txt_address"
                               name="txt_address"
                               tabindex="-1"
                               required readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="contact">
                        Contacto
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="contact"
                               name="contact"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="phone">
                        Teléfono
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control"
                               type="text"
                               id="phone"
                               name="phone"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="contact">
                        Observaciones
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <textarea class="form-control"
                                  type="text"
                                  id="observation"
                                  name="observation"
                                  title="Observaciones"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/company.js') }}"></script>
@endsection
