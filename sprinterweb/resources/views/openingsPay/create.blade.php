@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" enctype="multipart/form-data" data-route="{{ route('openingToPay') }}">

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

                                    <input class="form-control"
                                           type="text"
                                           name="period"
                                           id="period"
                                           value="{{ $period->descripcion }}"
                                           readonly required>
                                </div>

                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="number">Número:</label>
                                </div>

                                <div class="col-md-1 col-xs-12">

                                    <input class="form-control"
                                           type="text"
                                           name="number"
                                           id="number"
                                           value="{{ $number }}"
                                           readonly required>
                                </div>

                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="date">Fecha:</label>
                                </div>


                                <div class="col-md-2 col-xs-12">

                                    <input class="form-control"
                                           type="date"
                                           name="date"
                                           id="date"
                                           min="{{ $period->inicio }}"
                                           max="{{ $period->final }}"
                                           required>
                                </div>

                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="buy">T. Compra:</label>
                                </div>

                                <div class="col-md-3 col-xs-12">

                                    <select class="form-control select2"
                                            name="buy"
                                            id="buy"
                                            required>
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($buys as $buy)
                                            <option value="{{ $buy->id }}" @if( $buy->id == 1) selected @endif>
                                                {{ $buy->codigo }} | {{ $buy->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="buy">Sucursal:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2"
                                            name="subdiary"
                                            id="subdiary">
                                        @foreach($sucursales as $sucursal)
                                            <option value="{{$sucursal->id}}">{{$sucursal->codigo}}
                                                | {{$sucursal->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="customer">Proveedor:</label>
                                </div>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select2"
                                            name="customer"
                                            id="customer"
                                            data-route="{{ route('validate.openingToPay') }}">
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->codigo }} | {{ $customer->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="document">Documento:</label>
                                </div>

                                <div class="col-md-3 col-xs-12">
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
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="ruc">RUC:</label>
                                </div>

                                <div class="col-md-7 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="ruc"
                                           id="ruc"
                                           readonly>
                                </div>

                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="series">Serie:</label>
                                </div>

                                <div class="col-md-1 col-xs-12">
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

                                <div class="col-md-1 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="numberOfSeries"
                                           id="numberOfSeries"
                                           maxlength="8"
                                           data-route="{{ route('number.openingToPay') }}"
                                           readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="address">Dirección:</label>
                                </div>

                                <div class="col-md-7 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="address"
                                           id="address"
                                           readonly>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="dateInitial">Fecha:</label>
                                </div>

                                <div class="col-md-3 col-xs-12">
                                    <input class="form-control"
                                           type="date"
                                           name="dateInitial"
                                           id="dateInitial"
                                           value="{{ $date }}"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="glosa">Glosa:</label>
                                </div>

                                <div class="col-md-7 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="glosa"
                                           id="glosa"
                                           value="{{ $glosa }}"
                                           required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="dateEnd">Vencimiento:</label>
                                </div>

                                <div class="col-md-3 col-xs-12">
                                    <input class="form-control"
                                           type="date"
                                           name="dateEnd"
                                           id="dateEnd"
                                           value="{{ $date }}"
                                           required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="currency">Moneda:</label>
                                </div>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select2"
                                            type="text"
                                            name="currency"
                                            id="currency"
                                            required>
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" @if($currency->id == 1) selected @endif>
                                                {{ $currency->codigo }} | {{ $currency->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="changerate">T.C:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="changerate"
                                           id="changerate"
                                           readonly required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="total">Total:</label>
                                </div>
                                <div class="col-md-1 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="total"
                                           id="total"
                                           data-route="{{ route('calculateIgv.openingToPay') }}"
                                           required>
                                </div>
                                <div class="col-md-1 col-xs-12 label-input">
                                    <label for="tax">Impuesto:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <select class="form-control select2"
                                            type="text"
                                            name="tax"
                                            id="tax"
                                            required>
                                        <option disabled>-Seleccione-</option>
                                        @foreach($taxes as $tax)
                                            <option value="{{ $tax->id }}" @if($tax->id == 1) selected @endif>
                                                {{ $tax->codigo }} | {{ $tax->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control"
                                           type="text"
                                           name="igv"
                                           id="igv"
                                           value="0.00"
                                           readonly required>
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
    <script src="{{ asset('anikama/ani/buy.js') }}"></script>
@endsection