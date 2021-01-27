<form class="form-horizontal" id="frm_generales" name="frm_generales" data-route="{{ route('tariffitems') }}"
      method="POST">
    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="var" name="var" value="{{ $var }}">
    <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <p class="title-view">Pedidos de Compra:</p>
            </div>
            <div class="row">
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="row" id="pedidos_de_compra">
                        <table class="table table-bordered">
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Glosa</th>
                        </table>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="" class="form-control btn-primary text-center"><span class="fa fa-folder-open-o"></span> Archivar</a>
                    <br>
                    <a href="" class="form-control btn-primary text-center"><span class="fa fa-file-excel-o"></span> Exportar</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                        <p class="title-view">Ingreso Almacén:</p>
                    </div>
                    <div class="row" id="ingreso_almacen">
                        <table class="table table-bordered">
                            <th>Fecha</th>
                            <th>Nro.Guía</th>
                            <th>Importe M.N</th>
                            <th>Referencia</th>
                            <th>Glosa</th>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                        <p class="title-view">Provisiones:</p>
                    </div>
                    <div class="row" id="provisiones">
                        <table class="table table-bordered">
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Importe M.N</th>
                            <th>Importe M.E</th>
                            <th>Glosa</th>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
