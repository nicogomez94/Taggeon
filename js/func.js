$(document).ready(function() {
  //  actualizarPantallaEditarUsuario();

    //***23/9 15:40 */
    /*funcion para bloquear los botones cuando no estan siendo cambiados*/
    $(".data-datos-editar input").focus(function(e){
        e.preventDefault();

        document.getElementById("submit-editar").disabled = false;
        document.getElementById("reset-form-editar").disabled = false;

        $('#reset-form-editar').click(function() {
            document.getElementById("submit-editar").disabled = true;
            document.getElementById("reset-form-editar").disabled = true;
        });
    });

    $('.carousel').carousel({
        interval: 20001111
    })

    //para que lo inconos de filtro no show
    $(".icon-sort").hide();

    // $("#dropdown-user-menu").hide();
    $("#drop").click(function(){
        $("#dropdown-user-menu").toggle();
    });
    // drop de 
    $("#drop-bottom").click(function(){
        $("#dropdown-user-menu-bottom").toggle();
    });

/*riki*/
$( "#reset-form-editar" ).click(function() {
    actualizarPantallaEditarUsuario();
  });


$('#datos-cuenta').submit(function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
            
        $.ajax({
            url: '/app/editar_perfil.php',
            //type: 'post',
            //data: $("#registro_usuario_seller").serialize(), 
            //dataType : "json",
            data: formData,
            type: 'POST',
            processData: false,
            contentType: false,
            dataType: "json",
            success: function( data, textStatus, jQxhr ){
                if (data.status == 'REDIRECT'){
                    window.location.replace(data.mensaje);														
                }else if(data.status == 'OK'){
                     alert (data.mensaje);
                    // window.location.replace("/app/logout.php");
                    document.getElementById("submit-editar").disabled = true;
                    document.getElementById("reset-form-editar").disabled = true;
                }else{
                     alert (data.mensaje);
                    document.getElementById("submit-editar").disabled = true;
                    document.getElementById("reset-form-editar").disabled = true;
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                       var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                       alert(msj);
            }
       });
       return false;
    });

$('#form_recuperar_pass_paso1').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/recuperar_clave.php',
        //type: 'post',
        //data: $("#registro_usuario_seller").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/app/logout.php");
            }else{
                alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alert(msj);
        }
   });
   return false;
});




$('#form_recuperar_clave_mail').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/public_recuperar_pass_post.php',
        //type: 'post',
        //data: $("#registro_usuario_seller").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/app/logout.php");
            }else{
                alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alert(msj);
        }
   });
   return false;
});



$('#eliminar_usuario_seller').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/eliminar_usuario_seller.php',
        //type: 'post',
        //data: $("#registro_usuario_seller").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/app/logout.php");
            }else{
                alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alert(msj);
        }
   });
   return false;
});

$('#eliminar_usuario_picker').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/eliminar_usuario_picker.php',
        //type: 'post',
        //data: $("#registro_usuario_seller").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/app/logout.php");
            }else{
                alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alert(msj);
        }
   });
   return false;
});

$('#form_registro_cont_pass').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/editar_pass.php',
        //type: 'post',
        //data: $("#registro_usuario_seller").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/");
            }else{
                $("#mensaje-sin-login").css("display","block");
                $("#mensaje-sin-login").html(data.mensaje);
                // alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   $("#mensaje-sin-login").css("display","block");
                   $("#mensaje-sin-login").html(msj);
                   //    alert(msj);
        }
   });
   return false;
});

$('#registro_usuario_seller').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/alta_seller.php',
        //type: 'post',
        //data: $("#registro_usuario_seller").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/");
            }else{
                alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alert(msj);
        }
   });
   return false;
});

$('#registro-comun').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/alta_picker.php',
        //type: 'post',
        //data: $("#registro-comun").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alert (data.mensaje);
                window.location.replace("/");
            }else{
                alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alert(msj);
        }
   });
   return false;
});




$('#iniciar_sesion').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/login.php',
        //type: 'post',
        //data: $("#registro-comun").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK' || data.status == 'ok'){
                window.location.replace("/");
            }else{
                $("#mensaje-sin-login").css("display","block");
                $("#mensaje-sin-login").html(data.mensaje);
                //alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                    var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                    $("#mensaje-sin-login").css("display","block");
                    $("#mensaje-sin-login").html(msj);
                //    alert(msj);
        }
   });
   return false;
});

/*fin riki*/

/*NICOOOO*//*NICOOOO*//*NICOOOO*/

    /********SUBIR IMAGEN*******/
    $("#subir-foto-perfil").on('submit', function() {

        if(['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].indexOf($("#file2").get(0).files[0].type) == -1) {
            alert('Error : Solo JPEG, PNG & GIF permitidos');
            return false;
        }
    
        var reader = new FileReader();
        reader.onload = function(){
            
            var $data = { 
                'file': reader.result,
                'accion':'guardar'
            };

            $.ajax({
                type: 'POST',
                url: '/app/editar_imagen_perfil.php',
                data: $data,
                dataType: "json",
                success: function(data, textStatus, jQxhr ) {
                    if (data.status == 'REDIRECT'){
                        window.location.replace(data.mensaje);														
                    }else if(data.status == 'OK'){
                        alert (data.mensaje);
                        window.location.replace("/editar-usuario.html");
                    }else{
                        alert (data.mensaje);
                    }

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                    alert(msj);         
               }
            });
        };
        reader.readAsDataURL($("#file2").get(0).files[0]);    
        return false;
    });

    /**modal casero editar para foto*/
    $('#edit-btn').click(function(e) {
        e.preventDefault();

        $(".overlay").show();
    
        $('#cerrar-light').click(function() {
          $('.overlay').css("display", "none");
        });
    });


    /*funciones para que se cierre el otro modal atras del otro*/
    $("#recuperaPass").on('show.bs.modal', function (e) {
        $("#exampleModalCenter").modal("hide");
    });
    /**/
    $("#exampleModalCenter2").on('show.bs.modal', function (e) {
        $("#exampleModalCenter").modal("hide");
    });
    /**/
    $("#exampleModalCenter3").on('show.bs.modal', function (e) {
        $("#exampleModalCenter2").modal("hide");
    });

    

/*FIN NICO*//*NICOOOO*//*NICOOOO*/

/*funcion varias imagenes formulario subir*/
// $(function() {
//     var previewImg = function(input, insertarImgPreview) {

//       if (input.files) {
//       var cantidadArchivos = input.files.length;

//          for (i = 0; i < cantidadArchivos; i++) {
//             var reader = new FileReader();

//             reader.onload = function(event) {
//                $($.parseHTML('<img>')).attr('src', event.target.result).addClass("img-"+i).appendTo(insertarImgPreview);
//                // console.log(newFileList)
//             }
//             reader.readAsDataURL(input.files[i]);
         
//          }
         
//          // var newFileList = Array.from(event.target.files);
//          // newFileList.splice(1,1);

//        }
//     };

//     $('#galeria-fotos').on('change', function() {
//        previewImg(this, 'div.galeria');
//     });

// });


/*FORMULARIO SUBIR PRODUCTO*/
$('#producto-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/producto.php',
        //type: 'post',
        //data: $("#registro-comun").serialize(), 
        //dataType : "json",
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        async: false,
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'ERROR'){
                alert(data.mensaje);														
            }else if(data.status == 'OK' || data.status == 'ok'){
                window.location.replace("/ampliar-producto.html");
            }else if(data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);
            }else{
                $("#mensaje-sin-login").css("display","block");
                $("#mensaje-sin-login").html(data.mensaje);
                //alert (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                    var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                    $("#mensaje-sin-login").css("display","block");
                    $("#mensaje-sin-login").html(msj);
                //    alert(msj);
        }
   });
   return false;
});


/****formu-subir***/

var sizeCat = jsonData.categoria.length;

if(sizeCat>0){
    for(var i=0; i<sizeCat; i++){
        var nombre_cat = jsonData.categoria[i].nombre;
        var id_cat = jsonData.categoria[i].id;
        var id_cat_rubro = jsonData.rubro[i].id_categoria;
    
        var html_cat = '<option class="option-cat" value="'+id_cat+'">'+nombre_cat+'</option>';
        $("#categoria-producto").append(html_cat);

        var sizeRubro = jsonData.rubro.length;
        
        if(sizeRubro>0 && id_cat==id_cat_rubro){
            for(var i=0; i<sizeRubro; i++){
                var nombre_rubro = jsonData.rubro[i].nombre;
                var id_rubro = jsonData.rubro[i].id;
                
                var html_rubro = '<option value="'+id_rubro+'">'+nombre_rubro+'</option>';
                $("#rubro-producto").append(html_rubro);
            }
        }

    }
}



// var sizeRubro = jsonData.rubro.length;

// if(sizeRubro>0){
//     for(var i=0; i<sizeRubro; i++){
//         var nombre_rubro = jsonData.rubro[i].nombre;
//         var id_rubro = jsonData.rubro[i].id;
        
//         var html_rubro = '<option value="'+id_rubro+'">'+nombre_rubro+'</option>';
//         $("#rubro-producto").append(html_rubro);
//     }
// }

/***ampliar producto***/ 



var sizeProductos = jsonData.productos.length;

if(sizeProductos>0){
    for(var i=0; i<sizeProductos; i++){
        var nombre_prod = jsonData.productos[i].titulo;
        var precio_prod = jsonData.productos[i].precio;
        var id_prod = jsonData.productos[i].id;

        var listadoProducto = 
            '<div class="row producto">'+
                '<div class="col-lg-3 col-md-3 col-sm-3">'+
                    '<div class="img-producto-container">'+
                        '<img class="img-producto" src="../../img/default.png" alt="">'+
                    '</div>'+
                '</div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 text-left"><span class="titulo-producto">'+nombre_prod+'</span></div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 "><span class="precio-producto">$ '+precio_prod+'</span></div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 text-right"><i data-title="'+i+'" class="fas fa-ellipsis-v ellip"></i></div>'+
                '<div class="acciones-producto acciones-producto-'+i+'">'+
                    '<div class="eliminar-producto" data-title="'+id_prod+'"><a href="#"><i class="fas fa-trash-alt"></i>&nbsp;Eliminar</a></div>'+
                    '<div class="modificar-producto" data-title="'+id_prod+'"><a href="/editar-producto.html?id='+id_prod+'&accion=editar"><i class="fas fa-edit"></i>&nbsp;Modificar</a></div>'+
                '</div>'+
            '</div>';

        $("#listado-mis-productos").append(listadoProducto);

    }
}

/**apertura y cierre las opciones*/ 
$(function(){
    $(".ellip").on("click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var $this = $(this);
        var title = $this.data('title');
        
        $(".acciones-producto-"+title).toggle();
        console.log("ENTRO A .ELLIP")
    });

    // $('body').click(function (e) {
    //     console.log("entro a 'body'")
    //     $(".acciones-producto").hide();
    // });

    $(".acciones-producto").click(function(e) {
        e.stopPropagation();
    });
});


    

/*********eliminar producto******/

$(".eliminar-producto").on("click", function() {  

    var $this = $(this);
    var id_producto = $this.data('title');

    $.post('/app/producto.php', {accion: "eliminar", id: id_producto})
        .done(function(data) {
            var jsonp = JSON.parse(data)
            if (jsonp.status == 'ERROR'){
                alert(jsonp.mensaje);														
            }else if(jsonp.status == 'OK' || jsonp.status == 'ok'){
                window.location.replace("/ampliar-producto.html");
            }else if(jsonp.status == 'REDIRECT'){
                window.location.replace(jsonp.mensaje);
            }else{
                $("#mensaje-sin-login").css("display","block");
                $("#mensaje-sin-login").html(jsonp.mensaje);
                //alert (data.mensaje);
            }
        })
        .fail(function() {
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            $("#mensaje-sin-login").css("display","block");
            $("#mensaje-sin-login").html(msj);
        }); 

    return false;

});


/*filtro/buscador por titulo*/
$("#buscador-titulo").click(function(){

    var value = $("#buscador-titulo-input").val().toLowerCase();
    var cant_elem= $(".producto:not('.header-productos')").length;//sin header-prod
    var cant_json = jsonData.productos.length;

    if(value != ''){
        var search = $(jsonData.productos).filter(function (i,n){
            // return n.titulo===value
            if(n.titulo.indexOf(value) > -1){
                return n.titulo
            }
        });

        $(".producto:not(.header-productos)").hide();

        // if(cant_elem==cant_json)
        for (var i=0;i<search.length;i++){
            

            var nombre_prod = search[i].titulo;
            var precio_prod = search[i].precio;
            var id_prod = search[i].id;

            var listadoProducto = 
            '<div class="row producto">'+
                '<div class="col-lg-3 col-md-3 col-sm-3">'+
                    '<div class="img-producto-container">'+
                        '<img class="img-producto" src="../../img/default.png" alt="">'+
                    '</div>'+
                '</div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 text-left"><span class="titulo-producto">'+nombre_prod+'</span></div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 "><span class="precio-producto">$ '+precio_prod+'</span></div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 text-right"><i data-title="'+i+'" class="fas fa-ellipsis-v ellip"></i></div>'+
                '<div class="acciones-producto acciones-producto-'+i+'">'+
                    '<div class="eliminar-producto" data-title="'+id_prod+'"><a href="#"><i class="fas fa-trash-alt"></i>&nbsp;Eliminar</a></div>'+
                    '<div class="modificar-producto" data-title="'+id_prod+'"><a href="/editar-producto.html"><i class="fas fa-edit"></i>&nbsp;Modificar</a></div>'+
                '</div>'+
            '</div>';

                $("#listado-mis-productos").append(listadoProducto);
        }
            
        
    }else{
        $(".producto:not(.header-productos)").toggle();
    }
    
});


/**FUNC PARA MODIFICAR PRODUCTO*/ 

if(jsonData.productos.length>0){
    for(var i=0; i<jsonData.productos.length; i++){

        var id_prod = jsonData.productos[i].id;
        var nombre_prod = jsonData.productos[i].titulo;
        var marca_prod = jsonData.productos[i].marca;
        var precio_prod = jsonData.productos[i].precio;
        var envio_prod = jsonData.productos[i].envio;
        var garantia_prod = jsonData.productos[i].garantia;
        var descr_prod = jsonData.productos[i].descr_producto;
        var nombre_cat = jsonData.categoria[i].nombre;
        var id_cat = jsonData.categoria[i].id;
        var id_cat_rubro = jsonData.rubro[i].id_categoria;

        $("#titulo-producto").val(nombre_prod);
        $("#precio-producto").val(precio_prod);
        $("#descr-producto").val(descr_prod);
        $("#marca-producto").val(marca_prod);
        $("#envio-producto").val(envio_prod);
        $("#garantia-producto").val(garantia_prod);

        
        var html_cat = '<option class="option-cat" value="'+id_cat+'" selected>'+nombre_cat+'</option>';
        $("#categoria-producto").append(html_cat);

        var sizeRubro = jsonData.rubro.length;
        
        if(sizeRubro>0 && id_cat==id_cat_rubro){
            for(var i=0; i<sizeRubro; i++){
                var nombre_rubro = jsonData.rubro[i].nombre;
                var id_rubro = jsonData.rubro[i].id;
                
                var html_rubro = '<option value="'+id_rubro+'" selected>'+nombre_rubro+'</option>';
                $("#rubro-producto").val(nombre_rubro);
            }
        }

    }
}





});



/******/

var slider = document.querySelector('.items');
if (slider){

    var isDown = false;
    var startX;
    var scrollLeft;
    
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    
    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        var x = e.pageX - slider.offsetLeft;
        var walk = (x - startX) * 3; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
        // console.log(walk);
    });
}
    
function actualizarPantallaEditarUsuario () {
    if (jsonDatosEditar != undefined){
        var nombre = jsonDatosEditar["NOMBRE"] || '';
        var apellido = jsonDatosEditar["APELLIDO"] || '';
        var usuario = jsonDatosEditar["USUARIO"] || '';
        var contacto = jsonDatosEditar["CONTACTO"] || '';
        var img_perfil = jsonDatosEditar["FOTO-PERFIL"] || '';
        $("#nombre-completo").val(nombre);
        $("#contacto-apellido").val(apellido);
        $("#nombre-usuario").val(usuario);
        $("#contacto-mail").val(contacto);
        if (img_perfil != ''){
            $("#img-perfil-cont").html(
                '<img src="' + img_perfil + '" alt="profile_pic">'
                );
        }
    }
            
}


/**ordernar por nombre,etc**/
function sortProducto(sortParam){
    
    //para que aparezcan los iconitos cuando tocas
    $(".icon-sort").show();

    var json_prod;
    var sort_nombre;

    if(sortParam=='titulo'){
        json_prod = jsonData.productos;
        sort_nombre = json_prod.sort(function (a, b) {
            console.log("dentro titulo")
            return a.titulo.localeCompare(b.titulo);
        });
    }else if(sortParam=='precio'){
        json_prod = jsonData.productos;
        sort_nombre = json_prod.sort(function (a, b) {
            console.log("dentro precio")
            return a.precio.localeCompare(b.precio);
        });
    }
    

    $(".producto:not(.header-productos)").hide();
    
    for (var i=0;i<sort_nombre.length;i++){
        
        var nombre_prod = sort_nombre[i].titulo;
        var precio_prod = sort_nombre[i].precio;
        var id_prod = sort_nombre[i].id;

        var listadoProducto = 
        '<div class="row producto">'+
            '<div class="col-lg-3 col-md-3 col-sm-3">'+
                '<div class="img-producto-container">'+
                    '<img class="img-producto" src="../../img/default.png" alt="">'+
                '</div>'+
            '</div>'+
            '<div class="col-lg-3 col-md-3 col-sm-3 text-left"><span class="titulo-producto">'+nombre_prod+'</span></div>'+
            '<div class="col-lg-3 col-md-3 col-sm-3 "><span class="precio-producto">$ '+precio_prod+'</span></div>'+
            '<div class="col-lg-3 col-md-3 col-sm-3 text-right"><i data-title="'+i+'" class="fas fa-ellipsis-v ellip"></i></div>'+
            '<div class="acciones-producto acciones-producto-'+i+'">'+
                '<div class="eliminar-producto" data-title="'+id_prod+'"><a href="#"><i class="fas fa-trash-alt"></i>&nbsp;Eliminar</a></div>'+
                '<div class="modificar-producto" data-title="'+id_prod+'"><a href="/editar-producto.html"><i class="fas fa-edit"></i>&nbsp;Modificar</a></div>'+
            '</div>'+
        '</div>';

        $("#listado-mis-productos").append(listadoProducto);
    }


}


      





