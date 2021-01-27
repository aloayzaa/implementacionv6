<div class="modal fade" id="myModalDocumentAssociated" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Documentos Asociados</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" name="form-add-detail">
                    <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    <input type="hidden" id="rowId" name="rowId">
                    <input type="hidden" id="pv_series_id" name="pv_series_id">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="cbo_documentocom">Documento:</label>
                            <select name="cbo_documentocom" id="cbo_documentocom" class="select2">
                                <option value="" selected>-Seleccionar-</option>
                                @foreach($documentocoms as $documentocom)
                                    <option value="{{$documentocom->id}}">{{$documentocom->codigo}} | {{$documentocom->descripcion}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <label for="txt_serie">Serie:</label>
                            <input type="number" name="txt_serie" id="txt_serie" class="form-control">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <label for="txt_lineas">Líneas:</label>
                            <input type="number" name="txt_lineas" id="txt_lineas" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_detalle" id="btn_agregar_detalle"
                        onclick="agregar_detalle()"><span
                        class="fa fa-save"></span> Guardar Detalle
                </button>
            </div>

        </div>
    </div>
</div>
