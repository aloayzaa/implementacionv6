<table class="table table-hover table-bordered table-dark table-responsive">
    <thead class="text-center">
    <tr>
        <th>Opción</th>
        <th>Ver</th>
        <th>Crea</th>
        <th>Edita</th>
        <th>Anula</th>
        <th>Borra</th>
        <th>Print</th>
        <th>Aprob</th>
        <th>Precio</th>
    </tr>
    </thead>
    <tbody>
    @foreach($preportes as $value => $reportes)
        <tr>
            <th>{{$reportes->descripcion}}<input type="hidden" name="menu_id[{{$reportes->id}}]" value="{{$reportes->id}}"></th>
            <th class="text-center"><input type="checkbox" name="consulta[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['consulta'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="crea[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->crea'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="edita[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->edita'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="anula[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->anula'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="borra[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->borra'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="imprime[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->imprime'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="aprueba[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->aprueba'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="precio[{{$reportes->id}}]" @if(count($ppreportes)>0)@if($ppreportes[$value]['$reportes->precio'] == 1) checked @endif @endif></th>
        </tr>
    @endforeach
    </tbody>
</table>
