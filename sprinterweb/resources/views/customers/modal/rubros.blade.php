<div class="modal fade" id="myModalRubros" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Rubros:</h4>
            </div>
            <div class="modal-body">
                <form id="frm_rubros" name="frm_rubros" method="POST">
                    <input type="hidden" id="row_id_rubro" name="row_id_rubro" value="">
                    <input type="hidden" id="tercero_id_tercero_rubro" name="tercero_id_tercero_rubro" value="">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            {{--<label for="tiporubro_id_tercero_rubro">Rubros:</label>--}}
                            <select name="tiporubro_id_tercero_rubro" id="tiporubro_id_tercero_rubro" class="select2">
                                <option value="">-Seleccionar-</option>
                                @foreach($tiporubro as $t)
                                    <option value="{{ $t->id }}">{{ $t->codigo }} | {{ $t->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_tercero_rubro()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
