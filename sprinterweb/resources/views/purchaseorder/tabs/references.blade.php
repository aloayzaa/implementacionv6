{{-- <form class="form-horizontal" id="" name=""
      method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <p class="title-view">Pedidos de Compra:</p>
            </div>
            <div class="row">
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input type="hidden" name="ruta_pedidos_de_ordencompra" id="ruta_pedidos_de_ordencompra" value="{{route('referencia_pedido_almacen.purchaseorder')}}">
                    <div class="row">
                        <table id="pedidos_de_ordencompra" class="table display responsive nowrap table-striped table-hover table-bordered"
                               width="100%">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Referencia</th>
                                    <th>Glosa</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a class="form-control btn-primary text-center"><span class="fa fa-folder-open-o"></span> Archivar</a>
                    <br>
                    <a class="form-control btn-primary text-center"><span class="fa fa-file-excel-o"></span> Exportar</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                        <p class="title-view">Ingreso Almacén:</p>
                    </div>
                    <input type="hidden" id="tabla_ingreso_almacen" name="tabla_ingreso_almacen" value="{{route('ingreso_almacen.purchaseorder')}}">
                    <div class="row">
                        <table class="table table-bordered" id="ingreso_almacen" class="table display responsive nowrap table-striped table-hover table-bordered"
                               width="100%">
                            <thead>
                                <th>Fecha</th>
                                <th>Nro.Guía</th>
                                <th>Importe M.N</th>
                                <th>Importe M.E</th>
                                <th>Referencia</th>
                                <th>Glosa</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                        <p class="title-view">Provisiones:</p>
                    </div>
                    <input type="hidden" id="tabla_provisiones" name="tabla_provisiones" value="{{route('provisiones.purchaseorder')}}">
                    <div class="row">
                        <table class="table table-bordered" id="provisiones" class="table display responsive nowrap table-striped table-hover table-bordered"
                               width="100%">
                            <thead>
                                <th>Fecha</th>
                                <th>Referencia</th>
                                <th>Importe M.N</th>
                                <th>Importe M.E</th>
                                <th>Glosa</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--</form>--}}
