<div class="modal fade" id="myModalUmedidaedit" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Unidad de Medida</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form_detalle" name="form_detalle">
                    <input type="hidden" id="row_id" name="row_id" value="">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label>UM:</label>
                            <select class="form-control select2 edit_2" name="cbo_um" id="cbo_um" disabled>
                                <option value="">Seleccionar-</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}"> {{ $unidad->codigo }} || {{ $unidad->descripcion }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="txt_umedida">Factor:</label>
                            <input type="number" name="txt_factor" id="txt_factor" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base" onclick="update_item()">
                    <span class="fa fa-save"></span> Aceptar
                </button>
            </div>

        </div>
    </div>
</div>
