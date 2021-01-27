// function store() {
//
//     form = $('#frm_categories').serialize();
//     console.log(form)
//
//     $.ajax({
//         headers: {
//             'X-CSRF-Token': '{{ csrf_token() }}',
//         },
//         type: "POST",
//         url:"/categories/store/",
//         data: form,
//         success: function (data) {
//         //    console.log(data);
//             success('success', 'Registro Correcto', 'Guardado');
//             Window.location = '{{route("categories")}}';
//
//         },
//
//         error: function (data) {
//             json = data.responseJSON.errors;
//             console.log(data.responseJSON)
//             for (var clave in json){
//                 // Controlando que json realmente tenga esa propiedad
//                 if (json.hasOwnProperty(clave)) {
//                     // Mostrando en pantalla la clave junto a su valor
//                     error('error', json[clave] , 'Error!!');
//                 }
//             }
//         }
//     });
// }
//
//
$("#frm_generales").validate({
    ignore: "input[type='text']:hidden",
    rules: {
        code:{
            required:true,
            maxlength:20
        },
        name:{
            required:true,
            maxlength:200
        },
        codSunat:{
            maxlength:10
        },
        codSimbolo:{
            maxlength:20
        }

    },
    messages: {
        code:{
            required:"Campo Obligatorio",
            maxlength:"Longitud máxima 10 caracteres"
        },
        name:{
            required:"Campo Obligatorio",
            maxlength:"Longitud máxima 200 caracteres"
        },
        codSunat: "Longitud máxima 10 caracteres",
        codSimbolo: "Longitud máxima 20 carcateres"
    }

});


