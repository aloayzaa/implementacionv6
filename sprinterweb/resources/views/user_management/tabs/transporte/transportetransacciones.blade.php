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
    @foreach($trtransacciones as $value => $transacciones)
        <tr>
            <th>{{$transacciones->descripcion}}<input type="hidden" name="menu_id[{{$transacciones->id}}]" value="{{$transacciones->id}}"></th>
            <th class="text-center"><input type="checkbox" name="consulta[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['consulta'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="crea[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['crea'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="edita[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['edita'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="anula[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['anula'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="borra[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['borra'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="imprime[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['imprime'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="aprueba[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['aprueba'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="precio[{{$transacciones->id}}]" @if(count($trptransacciones)>0)@if($trptransacciones[$value]['precio'] == 1) checked @endif @endif></th>
        </tr>
    @endforeach
    </tbody>
</table>
