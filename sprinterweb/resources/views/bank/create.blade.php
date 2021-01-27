@extends('templates.home')

@section('content')

    <div class="x_content">
        <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
            <input type="hidden" id="ruta" name="ruta" value="{{ route('store.bank') }}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="var" name="var" value="{{ $var }}">
            <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="bcode">Código:</label>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <input type="text" class="form-control" placeholder="Ingrese código" name="bcode"
                                       id="bcode">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="bdescription">Descripción:</label>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" placeholder="Ingrese Descripción"
                                       id="bdescription" name="bdescription">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12 label-input">
                                <label for="bsunat">Cód.Sunat:</label>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <input type="text" class="form-control" name="bsunat" id="bsunat">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-12">{{--espacio--}}</div>
                            <div class="col-md-2 col-xs-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="bcheck" name="bcheck">
                                    <label class="custom-control-label" for="defaultUnchecked">Es efectivo</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <div class="ln_solid"></div>
                            </div>
                        </div>
                    </div>
                    @include('bank.general')
                </div>
            </div>
            @include('bank.modals.ctacte_modal')
        </form>

    </div>


@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/bank.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>


    <script>
        $(function () {
            DetalleEntidadBancaria.init('{{ route('list_docbanco.bank') }}');
        });
    </script>
@endsection
