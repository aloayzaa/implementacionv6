<div class="modal fade" id="forma_pago" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button-->
                <h4 class="modal-title"><span class="fa fa-check"></span>Buscar...</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label for="producto_codigo">Código:</label>
                        <select name="forma_pago" id="forma_pago" class="form-control select2">
                            <option value=""></option>
                            @foreach($formapago as $formapago)
                                <option value="{{$formapago->id}}">{{$formapago->codigo}} || {{$formapago->descripcion}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="valor_resto" id="valor_resto">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="detalle_cobro()">
                    <span class="fa fa-ban"></span> Cerrar
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


