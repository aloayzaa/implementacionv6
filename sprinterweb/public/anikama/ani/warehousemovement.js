function procesar_valorizacion(){
    var finicio=$('#fecha_inicio').val();
    var ffin=$('#fecha_fin').val();
    if (finicio!='') {
        if (finicio>ffin) {
            error('error', "Verifique fecha de Inicio", 'Error!!')
        }
        else{
            var formVal=$("#frmProcesarValorizacion").serialize();

            $.ajax({
                type:"POST",
                url:"/movementwarehouse/value",
                dataType:"JSON",
                data:formVal,
                success:function(data){

                    if(data.mensaje == null || data.mensaje==""){
                        success('success',"Se valorizó correctamente", 'Bien !!')
                    }
                    else{
                        error('error', data.mensaje, 'Error !!');
                    }
                }
            });
        }
    }
    else{
        error('error', "Verifique fecha de Inicio", 'Error!!')
    }
}
