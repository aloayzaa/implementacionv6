@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT"
                  data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="empresas">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $company->id }}">

                <div class="form-group">
                    <label class="control-label col-md-3" for="ruc">
                        Registro Único de Contribuyentes
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               id="ruc"
                               name="ruc"
                               value="{{ old('ruc', $company->emp_ruc) }}"
                               required="required"
                               class="form-control"
                               readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="name">
                        Razón social
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $company->emp_razon_social) }}"
                               class="form-control"
                               required
                               readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="address">
                        Dirección
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               id="address"
                               name="address"
                               value="{{ old('contact', $company->emp_direccion) }}"
                               class="form-control"
                               tabindex="-1"
                               required
                               @if($company->emp_direccion) readonly @endif>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="contact">
                        Contacto
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               id="contact"
                               name="contact"
                               class="form-control"
                               value="{{ old('contact', $company->emp_contacto) }}"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="phone">
                        Teléfono
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input type="text"
                               id="phone"
                               class="form-control"
                               name="phone"
                               value="{{ old('phone', $company->emp_telefono) }}"
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
                                  title="Observaciones">{{ old('phone', $company->emp_observaciones) }}</textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
@endsection
