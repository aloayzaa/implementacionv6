<div class="col-md-12 col-xs-12">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <input type="hidden" name="tipopersona" id="tipopersona">
                    <div class="row">
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">Cliente</label>
                            <input class="form-control" type="text" name="code" id="code">
                        </div>
                        <div class="col-md-4 col-xs-12 label-input">
                            <label for=""></label>
                            <input type="hidden" name="razonsocial" id="razonsocial">
                            <input type="hidden" name="ubigeo_id" id="ubigeo_id">
                            <select name="cliente" id="cliente" class="ocultar form-control" readonly></select>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for=""></label>
                            <input class="form-control" type="text" name="documento" id="documento" readonly>
                        </div>
                        <div class="col-md-4 col-xs-12 label-input">
                            <label for="">Dirección</label>
                            <!--input type="hidden" name="direccion" id="direccion"-->
                            <select name="direccion_id" id="direccion_id" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">Tipo Doc.</label>
                            <select name="cbotipodoc" id="cbotipodoc" class="form-control">
                                <option value="">-Seleccione-</option>
                                @foreach($documentocom as $documentocom)
                                    <option value="{{$documentocom->id}}">{{$documentocom->codigo}} | {{$documentocom->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">Serie</label>
                            <input type="text" name="txtserie" id="txtserie" class="form-control ocultar">
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">Número</label>
                            <input class="form-control ocultar" type="text" name="txtnumero" id="txtnumero">
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">Fecha</label>
                            <input type="date" name="txt_fecha" value="{{$fecha}}" id="txt_fecha" class="form-control tipocambio" readonly>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">T.Cambio</label>
                            <input type="text" name="tcambio" id="tcambio" class="form-control typechange" placeholder="0.00" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12 label-input">
                            <label for="">Vendedor</label>
                            <select name="vendedor_id" id="vendedor_id" class="form-control select2"></select>
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for="">Gía de Remisión</label>
                            <input class="form-control" type="text" name="remision1" id="remision1">
                        </div>
                        <div class="col-md-2 col-xs-12 label-input">
                            <label for=""></label>
                            <input type="text" name="remision2" id="remision2" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-12 col-xs-12">
            <br>
            <!--div class="row">
                <div class="col-md-2">
                    <button type="button" class=" btn btn-success" id="tipo_forma_pago"><i class="fa fa-plus"></i></button>
                </div>
            </div-->
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <table id="listCobranzaPointOfsale" class="table table-striped table-bordered table-bordered2" width="100%">
                        <thead>
                        <tr role="row" class="text-center">
                            <th>FP</th>
                            <th>Descripción</th>
                            <th>REF</th>
                            <th>Importe</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <br>
            <div class="row">
                <div class="col-md-8">
                    <input type="hidden" name="txtnum3" id="txtnum3" value="0.00" class="form-control" readonly>
                    <input type="hidden" name="txtnum4" id="txtnum4" value="0.00" class="form-control" readonly>
                    <br><br>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Neto Pagar</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="txtneto" id="txtneto" value="0.00" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Recibido</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="txtnum1" id="txtnum1" class="form-control" value="0.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Vuelto</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="txtnum2" id="txtnum2" class="form-control" value="0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="btn_cancelar" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Cancelar</button>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                        <div class="col-md-12">
                            <button type="button" id="btn_grabar" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
