<div class="modal fade" id="myModalContacto" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Contactos:</h4>
            </div>
            <div class="modal-body">
                <form id="frm_contacto" name="frm_contacto" method="POST">
                    <input type="hidden" id="row_id_contacto" name="row_id_contacto" value="">
                    <input type="hidden" id="tercero_id_tercero_contacto" name="tercero_id_tercero_contacto" value="">
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="nombre_tercero_contacto">Nombre:</label>
                            <input type="text" id="nombre_tercero_contacto" name="nombre_tercero_contacto" class="form-control">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="nrodocidentidad_tercero_contacto">DNI:</label>
                            <input type="text" id="nrodocidentidad_tercero_contacto" name="nrodocidentidad_tercero_contacto" class="form-control">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="cargo_tercero_contacto">Cargo:</label>
                            <input type="text" id="cargo_tercero_contacto" name="cargo_tercero_contacto" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="checkbox">
                                <label for="cpe_tercero_contacto"><input type="checkbox" id="cpe_tercero_contacto" name="cpe_tercero_contacto"> CPE</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="email_tercero_contacto">E-mail:</label>
                            <input type="email" id="email_tercero_contacto" name="email_tercero_contacto" class="form-control">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="telefono_tercero_contacto">Teléfono:</label>
                            <input type="text" id="telefono_tercero_contacto" name="telefono_tercero_contacto" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_contactos()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
