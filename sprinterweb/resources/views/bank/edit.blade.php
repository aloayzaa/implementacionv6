@extends('templates.home')

@section('content')

    <div class="x_panel identificador ocultar">

        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT" data-route="{{ route('bank') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="instancia" name="instancia" value="{{ $instancia }}">
                <input type="hidden" id="id" name="id" value="{{ $banco->id }}">

                <div class="form-group">
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">

                            <div class="col-md-12 col-xs-12">
                                <label class="control-label col-md-1" for="period">
                                    Código
                                </label>
                                <input type="text" class="form-control" placeholder="Ingrese código" name="bcode"
                                       id="bcode" value="{{$banco->codigo}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="control-label">
                                    Descripción
                                </label>
                                <input type="text" class="form-control" placeholder="Ingrese Descripción"
                                       id="bdescription" name="bdescription" value="{{$banco->descripcion}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="control-label col-md-1">
                                    Cód.Sunat
                                </label>
                                <input type="text" class="form-control" name="bsunat" id="bsunat"
                                       value="{{$banco->codsunat}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="bcheck" name="bcheck"
                                           @if($banco->efectivo==1) checked @endif>
                                    <label class="custom-control-label" for="defaultUnchecked">Es efectivo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>

                <div id="{{$instancia}}"></div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <div class="ln_solid"></div>
                </div>

                <div class="col-xs-12 form-group text-center">
                    <a id="btn_editar" class="btnCode fifth">
                        Editar <i class="fa fa-check"></i>
                    </a>
                </div>
                @include('bank.modals.ctacte_modal')
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/bank.js') }}"></script>
@endsection
