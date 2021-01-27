@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="ruta" name="ruta" value="{{ route('store.paymentMethods') }}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">

                        <form class="form-horizontal" id="frm_generales">

                            <div class="form-group">
                                <label class="control-label col-md-3" for="codigo">
                                    Código
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="codigo" name="codigo" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" for="descripcion">
                                    Descripción
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="descripcion" name="descripcion" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" for="ctactebanco_id">
                                    Cuenta asociada:
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" name="ctactebanco_id" id="ctactebanco_id">
                                        <option value="" selected>-- Seleccione una opción --</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}">
                                                {{ $bank->codigo }} | {{ $bank->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/ani/paymentMethod.js') }}"></script>
@endsection
