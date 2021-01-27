<div class="modal fade" id="myModalEntidadBancaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" style="color: black"><span class="fa fa-check"></span> Cuentas Corriente</h4>
            </div>
            <div class="modal-body">
                <form action="" id="frm_entidad_bank" name="frm_entidad_bank">
                    <input type="hidden" id="row_id" name="row_id" value="">
                    <input type="hidden" name="id_ctactebanco" id="id_ctactebanco">
{{--                    <input type="hidden" name="id_cart" id="id_cart">--}}
                    <input type="hidden" name="tipo_modal" id="tipo_modal">
                    <input type="hidden" name="estado_modal" id="estado_modal">

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">Código</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <input type="text" class="form-control" placeholder="Código" id="code" name="code">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">Cuentas/Descripción</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <select class="form-control"
                                        name="account"
                                        id="account">
                                    <option selected disabled value="0">-Seleccione-</option>
                                    @foreach($pcgs as $pcg)
                                        <option value="{{$pcg->id}}">{{$pcg->codigo}}
                                            | {{$pcg->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">Moneda/Descripción</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <select class="form-control"
                                        name="currency"
                                        id="currency">
                                    <option selected disabled value="0">-Seleccione-</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">Nro. Cheque</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <input type="text" class="form-control" name="check" id="check">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <label class="col-sm-12 col-xs-12">Subdiario Ingresos</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                                <input type="text" class="form-control" name="check" id="check">
                            </div>
                        </div>
                    </div>
            </div>





            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_detalle_ctactebanco()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>


        </div>
    </div>
</div>
