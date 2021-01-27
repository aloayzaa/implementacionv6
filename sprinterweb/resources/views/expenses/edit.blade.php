@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  data-route="{{ route('expenses') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                {{--<input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">--}}
                {{--<input type="hidden" id="tipo_tercero" name="tipo_tercero">--}}
                <input type="hidden" id="id" name="id" value="{{ $expense->id }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="type">Tipo de gasto:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2"
                                            name="type"
                                            id="type"
                                            required>
                                        <option disabled selected>-Seleccione-</option>
                                        @foreach($typeServices  as $typeService)
                                            @if($expense->tipobienservicio_id == $typeService->id)
                                                <option selected value="{{ $typeService->id }}">
                                                    {{ $typeService->descripcion }}
                                                </option>
                                            @else
                                                <option value="{{ $typeService->id }}">{{ $typeService->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           id="code"
                                           name="code"
                                           value="{{ $expense->codigo }}"
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
                                           value="{{ $expense->descripcion }}"
                                           required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codSunat">Código sunat:</label>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           id="codSunat"
                                           name="codSunat"
                                           value="{{ $expense->codsunat }}"
                                           required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="account">Cuenta:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2"
                                            name="account"
                                            id="account">
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($accountingPlan  as $accountPlan)
                                            @if($expense->cuenta_id == $accountPlan->id)
                                                <option selected value="{{ $accountPlan->id }}">
                                                    {{ $accountPlan->codigo }} | {{ $accountPlan->descripcion }}
                                                </option>
                                            @else
                                                <option value="{{ $accountPlan->id }}">
                                                    {{ $accountPlan->codigo }} | {{ $accountPlan->descripcion }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                    <div class="ln_solid"></div>
                                </div>

                                <div class="col-xs-12 form-group text-center">
                                    <a id="btn_editar" class="btnCode fifth">
                                        Actualizar <i class="fa fa-check"></i>
                                    </a>
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
    <script src="{{ asset('anikama/ani/expense.js') }}"></script>
@endsection
