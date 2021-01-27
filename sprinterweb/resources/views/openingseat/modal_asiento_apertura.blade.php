<div class="modal fade" id="myModalAsientoApertura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok"></span> Detalle Provisión
                    por pagar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="txt_id_modelo_modal" id="txt_id_modelo_modal">
                <input type="hidden" name="txt_auxiliar" id="txt_auxiliar">
                <input type="hidden" name="txt_aux_id" id="txt_aux_id">
                <input type="hidden" name="estado_modal" id="estado_modal">

                <div class="form-group">
                    <div class="col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cuenta</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control" id="account" name="account">
                                <option disabled selected value="0">Seleccionar..</option>
                                @foreach($pcgs as $pcg)
                                    <option value="{{$pcg->id}}">{{$pcg->codigo}} | {{$pcg->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cago M.N</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" id="cargomn" name="cargomn">
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Abono M.N</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" id="abonomn" name="abonomn">
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cago M.E</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" id="cargome" name="cargome">
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Abono M.E</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <input class="form-control" type="text" value="0.00" id="abonome" name="abonome">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_cart" id="btn_agregar_cart"><span
                        class="fa fa-save"></span> Guardar Detalle
                </button>
            </div>

        </div>
    </div>
</div>
