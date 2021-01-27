<div class="modal fade" id="myDatosNPK" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal_title">Buscar</h4>
            </div>
            <div class="modal-body">
                <form action="" id="frm_npk" name="frm_npk">
                    <input type="hidden" id="row_id_npk" name="row_id_npk" value="">
                    <input type="hidden" id="productonpk_id" name="productonpk_id" value="">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label>Buscar:</label><br>
                            <select name="cbo_productos_npk" id="cbo_productos_npk" class='form-control select2 ag-modal-select'>
                                <option value='0'>-Seleccionar-</option>
                                @foreach($nutriente as $n)
                                    <option value="{{$n->id}}">{{$n->codigo}} | {{$n->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="txt_conc">Conc.(%):</label>
                            <input type="number" name="txt_conc" id="txt_conc" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_modal_base" id="btn_modal_base"
                        onclick="enviar_datos_npk()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>

        </div>
    </div>
</div>
