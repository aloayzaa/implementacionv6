@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" enctype="multipart/form-data" data-route="{{ route('recordVoidedDocument') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="period">Periodo:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2"
                                            name="period"
                                            id="period">
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($periods as $period)
                                            <option value="{{ $period->id }}"
                                                    @if($period->descripcion == Session::get('period')) selected @endif>
                                                {{ $period->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="date">Fecha:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                        <input class="form-control"
                                               type="date"
                                               name="date"
                                               id="date"
                                               value="{{ $date }}"
                                               required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="changerate">T.C:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="changerate"
                                           id="changerate"
                                           readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="customer">Proveedor:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control select2"
                                            name="customer"
                                            id="customer"
                                            data-route="{{ route('validate.openingReceivable') }}">
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->codigo }} | {{ $customer->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="document">Documento:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2"
                                            name="document"
                                            id="document"
                                            required>
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($documents as $document)
                                            <option value="{{ $document->id }}" @if($document->id == 2) selected @endif>
                                                {{ $document->codigo }} | {{ $document->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="series">Serie:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="series"
                                           id="series"
                                           maxlength="4"
                                           required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="numberOfSeries">Número:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="numberOfSeries"
                                           id="numberOfSeries"
                                           maxlength="8"
                                           data-route="{{ route('number.openingReceivable') }}"
                                           readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ln_solid"></div>
                                <div class="col-xs-12 form-group text-center">
                                    <a id="btn_grabar" class="btnCode fifth">
                                        Registrar <i class="fa fa-check"></i>
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
    <script src="{{ asset('anikama/ani/recordVoided.js') }}"></script>
@endsection
