<div class="col-md-12 col-xs-12">
    <br>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label for="code">Código:</label>
            {{-- <input class="form-control" type="text" id="code" name="code" onchange="valida_codigo('{{ $var }}')" required> --}}
            <input type="text" id="code" name="code"  class="form-control" required>
        </div>
        <div class="col-md-3 col-xs-12">
            <label for="tipo">Tipo:</label><br>
            <select name="cbo_tipo" id="cbo_tipo" class="select2" style="width: 100%;">
                <option value="1">Jurídica</option>
                <option value="2">Natural</option>
                <option value="3">No Domiciliado</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_estado">Estado:</label>
            <input type="text" id="txt_estado" name="txt_estado" class="form-control" readonly>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_situacion">Situación:</label>
            <input type="text" id="txt_situacion" name="txt_situacion" class="form-control" readonly>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_afecto_rus">Afecto RUS:</label>
            <input type="text" id="txt_afecto_rus" name="txt_afecto_rus" class="form-control" readonly>
        </div>
    </div>
    <div class="row">
        <div id="cambiar_tipo">
            <div class="col-md-6 col-xs-12">
                <label for="name">Razón social / Nombre completoss</label>
                <input class="form-control" type="text" id="descripcion_tercero" name="descripcion_tercero">
            </div>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 10px;">
                <label for="chk_cliente"><input type="checkbox" name="chk_cliente" id="chk_cliente" checked>Es Cliente</label>
            </div>
        </div>
        <div class="col-md-2 col-xs-12">
            <div class="checkbox" style="margin-top: 10px;">
                <label for="chk_proveedor"><input type="checkbox" name="chk_proveedor" id="chk_proveedor">Es proveedor</label>
            </div>
        </div>
    </div>
    <div class="row">
       <div class="col-md-6 col-xs-12">
           <label for="txt_nombre_comercial">Nombre comercial:</label>
           <input type="text" id="txt_nombre_comercial" name="txt_nombre_comercial" class="form-control">
       </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label for="cbo_documento">Documeto:</label><br>
            <select name="cbo_documento" id="cbo_documento" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                @foreach($documentoide as $d)
                    <option value="{{ $d->id }}">{{ $d->codigo }} | {{ $d->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-xs-12" style="margin-top: 20px;">
            <input type="text" id="txt_documento" name="txt_documento" class="form-control">
        </div>
        <div class="col-md-3 col-xs-12">
            <label for="cbo_dni">DNI:</label><br>
            <select name="cbo_dni" id="cbo_dni" class="select2" style="width: 100%;">
                <option value="">-Seleccionar-</option>
                @foreach($documentoide as $d)
                    <option value="{{ $d->id }}">{{ $d->codigo }} | {{ $d->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-xs-12" style="margin-top: 20px;">
            <input type="text" id="txt_dni" name="txt_dni" class="form-control">
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="cbo_pais">País:</label><br>
            <select name="cbo_pais" id="cbo_pais" class="select2" style="width: 100%;">
                <option value="">-Seleccionar-</option>
            </select>
        </div>
    </div>
    <div class="row">
        <p class="title-view">Dirección:</p>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label for="cbo_via_tipo">Tipo Vía</label>
            <select name="cbo_via_tipo" id="cbo_via_tipo" class="select2">
                <option value="">-Seleccionar-</option>
                @foreach($tipo_via as $tv)
                    <option value="{{ $tv->id }}">{{ $tv->codigo }} | {{ $tv->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="txt_nombre_via">Nombre Vía:</label>
            <input type="text" class="form-control" id="txt_nombre_via" name="txt_nombre_via">
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_numero_via">Número:</label>
            <input type="text" class="form-control" id="txt_numero_via" name="txt_numero_via">
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_interior_via">Interior:</label>
            <input type="text" class="form-control" id="txt_interior_via" name="txt_interior_via">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label for="cbo_tipo_zona">Tipo Zona:</label><br>
            <select name="cbo_tipo_zona" id="cbo_tipo_zona" class="select2">
                <option value="">-Seleccionar-</option>
                @foreach($tipo_zona as $tz)
                    <option value="{{ $tz->id }}">{{ $tz->codigo }} | {{ $tz->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="txt_nombre_zona">Nombre Zona:</label>
            <input type="text" id="txt_nombre_zona" name="txt_nombre_zona" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label for="cbo_ubigeo">Ubigeo:</label><br>
            <select name="cbo_ubigeo" id="cbo_ubigeo" class="select2">
                <option value="">-Seleccionar-</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_telefono1">Teléfonos:</label>
            <input type="text" name="txt_telefono1" id="txt_telefono1" class="form-control" placeholder="Teléfono">
        </div>
        <div class="col-md-2 col-xs-12">
            <input type="text" name="txt_telefono2" id="txt_telefono2" class="form-control" style="margin-top: 20px;" placeholder="Teléfono">
        </div>
        <div class="col-md-2 col-xs-12">
            <input type="text" name="txt_telefono3" id="txt_telefono3" class="form-control" style="margin-top: 20px;" placeholder="Teléfono">
        </div>
    </div>
    <div class="row">
       <div class="col-md-4 col-xs-12">
           <label for="txt_email">E-mail:</label>
           <input type="text" id="txt_email" name="txt_email" class="form-control">
       </div>
        <div class="col-md-4 col-xs-12">
            <label for="txt_web">Web:</label>
            <input type="text" id="txt_web" name="txt_web" class="form-control">
        </div>
    </div>
    <div class="row">
        <p class="title-view">Contactos: <a id="modal_contactos" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a></p>
    </div>
    <div class="row">
       <table class="table table-striped" id="tabla_contactos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Cargo</th>
                    <th>CPE</th>
                    <th>E-mail</th>
                    <th>Teléfono</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
           <tbody></tbody>
       </table>
    </div>
</div>
