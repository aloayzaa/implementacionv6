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
    @foreach($trreportes as $value => $reportes)
        <tr>
            <th>{{$reportes->descripcion}}<input type="hidden" name="menu_id[{{$reportes->id}}]" value="{{$reportes->id}}"></th>
            <th class="text-center"><input type="checkbox" name="consulta[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['consulta'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="crea[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['crea'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="edita[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['edita'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="anula[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['anula'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="borra[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['borra'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="imprime[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['imprime'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="aprueba[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['aprueba'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="precio[{{$reportes->id}}]" @if(count($trpreportes)>0)@if($trpreportes[$value]['precio'] == 1) checked @endif @endif></th>
        </tr>
    @endforeach
    </tbody>
</table>
