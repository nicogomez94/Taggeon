var loading = $("#loading-gif img");

document.addEventListener("DOMContentLoaded", function() {
    
    if(typeof jsonData !== 'undefined'){
        let sizeSeguidores = jsonData.seguidores.length || 0;
        let sizeSeguidos = jsonData.seguidos.length || 0;
    
        //perfil
        $("#seguidores_count").html(sizeSeguidores);
        $("#seguidos_count").html(sizeSeguidos);
    }/*TODO pasar bien a pedido*/


    //on/off de arrows
    $(".board.splide__arrow").hide(500);
    $(".board")
        .mouseenter(function() {
        $(".splide__arrow").css("display","inline");
      })
      .mouseleave(function() {
        $(".splide__arrow").css("display","none");
      });

    //si la img viene con error
    /*$("img").on("error", function(){
        $(this).attr('src', '../../imagen_perfil/generica.png');
    });*/

    /**/
    //elegir foto editar perfil}
    $('#file-upload').bind('change', function() { 
        var result = $("#result-file-upload");
        var filename = $(this).val();
        result.css("display","inline-block")
        result.append(filename); 
        $(".btn-cambiar-foto-perfil").show();
    });

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

    /*subir public traidop de template*/
    $("#buscador-titulo-input").keyup(function(){
        activarBuscador($(this));
     });
    /**cierre del modal de tagueo*/
    $("#modal-cropper").on('show.bs.modal', function (e) {
        $("#modal-data").modal("hide");
    });
    /**/
    $("#modal-data").on('show.bs.modal', function (e) {
        $("#modal-cropper").modal("hide");
    });

    /************************************/

    //para que lo inconos de filtro no show
    //$(".icon-sort").hide();

    // abro/cierro menu perfil
    $(document).not(".notifs-button-ampliar").click(function () {
        $("#dropdown-user-menu").hide();
        $(".notifs-button-ampliar").hide();
    });
    //dropdown del user
    //notifs
    $(".notifs-button").click(function(e){
        e.stopPropagation();
        e.preventDefault();

        $(".notifs-button-ampliar").toggle();
        $("#dropdown-user-menu").hide();
    });
    // drop de 
    $("#drop-bottom").click(function(){
        $("#dropdown-user-menu-bottom").toggle();
    });

    $(".cerrar-galeria-banner").click(function(){
        $("#carousel-index").hide();
    });

    /**/


    /*PUBLICACIONES*/
    /*eliminar foto img-pins*/
    $("#eliminar-img-flotante").click(function(){
        $("#imagen-pins").val('');
        $("#eliminar-img-flotante").hide();
        $("#img-subir-pins").show();
        $("#output-imgpins").attr("src","");
        $("#output-imgpins").hide();
        $("#cropear-btn").hide();
        $("#map").css({
            "background-image" : "none",
            "width" : "unset",
            "height": "unset"
        });
        $("#img-pines-amapear").hide();
    });

    /*slick carrusel productos en ampliar publicaciones*/
    // $('.items-carrusel').slick({
    //         infinite: true,
    //         slidesToShow: 3,
    //         slidesToScroll: 3,
    //   });




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
                    // window.location.replace("/app/logout.php");
                    document.getElementById("submit-editar").disabled = true;
                    document.getElementById("reset-form-editar").disabled = true;
                }else{
                    document.getElementById("submit-editar").disabled = true;
                    document.getElementById("reset-form-editar").disabled = true;
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                       var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                       alertify.error(msj);
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
                alertify.success(data.mensaje);
                window.location.replace("/app/logout.php");
            }else{
                console.log (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   console.log(msj);
        }
   });
   return false;
});




$('#form_recuperar_clave_mail').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/public_recuperar_pass_post.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                console.log (data.mensaje);
                window.location.replace("/app/logout.php");
            }else{
                console.log (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   console.log(msj);
        }
   });
   return false;
});



$('#eliminar_usuario').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/eliminar_usuario_seller.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alertify.success(data.mensaje)
                window.location.replace("/app/logout.php");
            }else{
                console.log (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   console.log(msj);
        }
   });
   return false;
});

$('#form_registro_cont_pass').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
        
    $.ajax({
        url: '/app/editar_pass.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                alertify.success(data.mensaje)
                window.location.replace("/");
            }else{
                alertify.error(data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj)
            //    console.log(msj);
        }
   });
   return false;
});

$('#registro_usuario_seller').submit(function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    var mail = formData.get("mail");
    var pass = formData.get("pass");
        
    $.ajax({
        url: '/app/alta_seller.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){
                //alertify.success(data.mensaje)
                iniciar_sesion(mail,pass)
            }else{
                alertify.error(data.mensaje)
                console.log (data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                   var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                   alertify.error(data.mensaje)
        }
   });
   return false;
});


$('#iniciar_sesion, #iniciar_sesion_welcome').submit(function (e) {
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
                //alertify.success(data.mensaje)
                window.location.replace("/");
            }else{
                alertify.error(data.mensaje)
                //alertify.error(data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
                    var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                    $("#mensaje-sin-login").css("display","block");
                    $("#mensaje-sin-login").html(msj);
                //    alertify.error(msj);
        }
   });
   return false;
});

    /********SUBIR IMAGEN*******/
    $("#subir-foto-perfil").on('submit', function() {

        if(['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].indexOf($("#file-upload").get(0).files[0].type) == -1) {
            alertify.error('Error : Solo JPEG, PNG & GIF permitidos');
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
                        //window.location.replace(data.mensaje);														
                    }else if(data.status == 'OK'){
                        alertify.success(data.mensaje);
                        window.location.replace("/editar-usuario.html");
                    }else{
                        alertify.error(data.mensaje);
                    }

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                    alertify.error(msj);         
               }
            });
        };
        reader.readAsDataURL($("#file-upload").get(0).files[0]);    
        return false;
    });

    /**modal casero editar para foto*/
    $('#edit-btn').click(function(e) {
        e.preventDefault();
        $(".overlay-foto-perfil").show();
    });

    
    //boton de añadir productos
    /*$('#anadir-productos-btn').click(function(e) {
        e.preventDefault();

        $("#terminar-productos-btn").show();
        //$("#limpiar-productos-btn").show();
        $("#anadir-productos-btn").hide();
        $("#map").css("pointer-events","all");
        
        //$(".overlay-prod").show(); //TODO
        $("#eliminar-img-flotante").hide();
        
        $('#map').dropPin('dropMulti',{
            cursor: 'crosshair',
            pinclass: 'qtipinfo',
            pin: '../../plugins/dropPin-master/dropPin/dot-circle-solid.svg'
        });

    });*/

    $("#terminar-productos-btn").click(function(){
        $("#map").css("pointer-events","none");
        $("#terminar-productos-btn").hide();
        $("#anadir-productos-btn").show();
    })


/*FORMULARIO SUBIR PUBLICACION*/
$('#subir-publicacion-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();

    var formData = new FormData($(this)[0]);

    var url_imagen_64 = $("#img-pines-amapear").attr("src")
    var pin_object = $(".pin").serializeArray();
    var pin_object_str = JSON.stringify(pin_object)
    var subescenas_obj = JSON.stringify($(".tipo_esp").serializeArray());
    
    formData.append("foto_base64",url_imagen_64);
    formData.append("data_pines",pin_object_str);
    formData.append("subescena_json",subescenas_obj);
    formData.delete("publicacion_foto");
    
    loading.show();
    $.ajax({
        url: '/app/publicacion.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        async: false,
        success: function( data, textStatus, jQxhr ){
            loading.hide();
            if (data.status == 'ERROR'){
                alertify.error(data.mensaje);														
            }else if(data.status == 'OK' || data.status == 'ok'){
                alertify.success(data.mensaje);	
                window.location.replace("/mis-publicaciones.html");
            }else{
                alertify.error(data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            loading.hide();
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj);
        }
    });
    return false;
});


$('#editar-publicacion-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    var formData = new FormData($(this)[0]);
    let imgbase64;

    var url_imagen_64 = $("#img-pines-amapear").attr("src")
    formData.delete("publicacion_foto");
    var pin_object = $(".pin").serializeArray();
    var pin_object_str = JSON.stringify(pin_object)
    var subescenas_obj = JSON.stringify($(".tipo_esp").serializeArray());

    formData.append("subescena_json",subescenas_obj);
    formData.append("data_pines",pin_object_str);
    loading.show();
    
    $.ajax({
        url: '/app/publicacion.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        async: false,
        success: function( data, textStatus, jQxhr ){
            loading.hide();
            if (data.status == 'ERROR'){
                alertify.error(data.mensaje);														
            }else if(data.status == 'OK' || data.status == 'ok'){
                $("body").addClass("loading"); 
                //window.location.replace("/mis-publicaciones.html");
            }else if(data.status == 'REDIRECT'){
                //window.location.replace(data.mensaje);
            }else{
                alertify.error(data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            loading.hide();
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj);
        }
   });
   return false;
});






//ordenar mis compras mis ventas
$("#ordenar").change(function(){
    $("select option:selected").each(function() {
       var curr = $(this).val();
       var path = window.location.pathname.split("/")[1];
       if(curr == "recientes"){
          sortProducto('recientes','sort-nombre',path);
       }else if(curr == "titulo"){
          sortProducto('titulo-producto','sort-nombre',path);
       }
    });
 });


///finalizar orden
$("#finalizar-orden").submit(function(){
    console.log("test")
    var id_carrito = jsonData.carrito[0].id_carrito;

    var dataForden = new FormData($(this)[0]);
    dataForden.append("id_carrito",id_carrito);

    $.ajax({
        url: '/app/carrito.php',
        data: dataForden,
        type: 'POST',
        processData: false,
        contentType: false,
        //dataType: "json",
        async: false,
        success: function( data, textStatus, jQxhr ){
            var dataJ = JSON.parse(data).status;
            var dataM = JSON.parse(data).mensaje;
           if (dataJ == "REDIRECT"){
               alertify.error(data)
              console.log("REDIRECT-->"+dataM);
              console.log(data)
              //window.location.replace(dataM);														
           }else if(dataJ == 'OK'){
               console.log(data)
              alertify.success(data)
               window.location.replace("/cobrar-compra.html?id="+id_carrito);
           }else{
               alertify.error(data)
            console.log(data)
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alertify.error(data);
        }
    });
    return false;
});


$("#cropear-btn").click(function(){
    $(this).hide();
    $(".toggle-aspect-ratio").hide();
    $("#anadir-productos-btn").show();
});
$("#terminar-productos-btn").click(function(){
    var cant_pines = $(".click-protector-cont").children().length;
    if(cant_pines>=1){
        $("#btn-siguiente").show();
    }
});

$("#btn-siguiente").click(function(){
    var cant_pines = $(".click-protector-cont").children().length;
    if(cant_pines>=1){
        $("#modal-data").modal("show");
    }else{
        $('.tooltip-nico').show(500);
    }
});


/***/
$(".cantidad_value").change(function(){
    var valor_selected = $(this).find("option:selected").val();
    var tag_precio_cambiar = $(this).parent().parent().parent().find(".precio-producto-modal>span");
    var tag_precio_cambiar_data = tag_precio_cambiar.attr("data-precio");

    var valor_cambiado = parseInt(tag_precio_cambiar_data) * parseInt(valor_selected);
    tag_precio_cambiar.text("AR$. "+valor_cambiado);
    
});

//activo buscador
$("#buscador-index-input").keyup(function(e){

    activarBuscadorRelated($(this));
    
    if(e.key === "Enter"){
        //console.log("enter")
        buscadorIndex($(this));
    }
});

//form intereses
/*$("#form_intereses").submit(function(e){
    
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    var checkbox = $(this).find("input[type=checkbox]");
    formData.append("accion","alta")

    $.ajax({
        url: '/app/intereses.php',
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, response ){
           if (data.mensaje == "REDIRECT"){
              console.log("REDIRECT-->"+response);
              //window.location.replace(dataM);														
           }else if(data.mensaje == 'OK'){
              //window.location.replace("/test-cobrar-compra.html?id="+id_carrito);
              console.log("elseif-->"+response);
           }else{
              //window.location.replace("/ampliar-carrito.html");
              console.log("else-->"+response);
              console.log(data);
           }
        },
        error: function(data,response){
            alertify.error(data);
            console.log(data,response)
        }
    });
    return false;

});*/



/***fin document.ready***//***fin document.ready***/
/***fin document.ready***//***fin document.ready***/
/***fin document.ready***//***fin document.ready***/


});

function btnAnadirTag(){

    $("#terminar-productos-btn").show();
    //$("#limpiar-productos-btn").show();
    $("#anadir-productos-btn").hide();
    $("#map").css("pointer-events","all");
    
    //$(".overlay-prod").show(); //TODO
    $("#eliminar-img-flotante").hide();

    $('#map').dropPin('dropMulti',{
        cursor: 'crosshair',
        pinclass: 'qtipinfo',
        pin: '../../plugins/dropPin-master/dropPin/dot-circle-solid.svg'
    });
}

function loadingScreen(){
    $(document).ajaxStart( function() {
        $(".backdrop").css("display","grid")
    });
    $(document).ajaxStop( function() {
        $(".backdrop").css("display","none")
    });
}

function cambiarCant(val,precio,target,target2,target3){
    let precio_multiplicado = val*precio;

    let target_html = document.querySelector(target)
    let target_html2 = document.querySelector(target2)
    let target_html3 = document.querySelector(target3)

    target_html.innerHTML = precio_multiplicado;
    target_html2.innerHTML = precio_multiplicado;
    target_html3.innerHTML = precio_multiplicado;
}

/*
function cambiarCant(obj){
    for (var i=0; i<obj.length; i++) {
        let obj_el = obj[i] || [];
        let target_html = document.querySelector(obj_el)
        let selected_value = this.value

        target_html.innerHTML = selected_value;
    }
}*/

function iniciar_sesion(mail,pass){

    var formData2 = new FormData();
    formData2.append("mail",mail)
    formData2.append("pass",pass)
    
    $.ajax({
        url: '/app/login.php',
        data: formData2,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data, textStatus, jQxhr ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK' || data.status == 'ok'){
                alertify.success("El usuario se registro con exito!")
                window.location.replace("/");
            }else{
                $("#mensaje-sin-login").css("display","block");
                $("#mensaje-sin-login").html(data.mensaje);
                //alertify.error(data.mensaje);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            $("#mensaje-sin-login").css("display","block");
            $("#mensaje-sin-login").html(msj);
        }
    });
    return false;
}

function sortProducto(paramTitulo, paramSort, page){

    $(".icon-sort").show();

    
    var ordenar = function(a, b) {
        if(paramTitulo=='titulo-producto'){//para letras   
            return a.innerHTML.toLowerCase().localeCompare(b.innerHTML.toLowerCase());
        }else if(paramTitulo=='precio-producto' || paramTitulo=='stock-producto' || paramTitulo=='recientes'){ //para nros
            return a.innerHTML.toLowerCase() - b.innerHTML.toLowerCase();
        }
    }


    var order = $("."+paramSort).data('order');

    if(order == "asc"){
        console.log("asc")
        var lista = $("."+paramTitulo).get();
        lista.reverse(ordenar);
        $("."+paramSort).data('order', "desc");
        $(".icon-sort").html('<i class="fas fa-sort-down"></i>');
    }else{
        console.log("desc")
        var lista = $("."+paramTitulo).get();
        lista.sort(ordenar);
        $("."+paramSort).data('order', "asc");
        $(".icon-sort").html('<i class="fas fa-sort-up"></i>');
    }
    

    for (var i=0; i<lista.length; i++) {
        if(page == "mis-compras.html"){
            $('.container-sorteable').append(lista[i].parentNode.parentNode.parentNode.parentNode);
        }else if(page == "ampliar-producto.html"){
            $('.container-sorteable').append(lista[i].parentNode.parentNode);
        }else{
            alertify.error("Ha ocurrido un error. Recargue la p&aacute;gina")
        }
        
    }
}


function cargarImgPines(event){
    
    var tipoFile = event.target.files[0].type || "";

    if(tipoFile=="image/jpeg" || tipoFile=="image/png" || tipoFile=="image/jpg"){

        var reader = new FileReader();
        reader.onload = function(){
            // var output = document.getElementById('output-imgpins');
            var map = $('<div id="map">');
            var img_pin = $('<img id="img-pines-amapear">');
            //lo creo en ves de tocarlo
            $(".contenedor-content").append(map);
            map.append(img_pin)
            
            $("#img-pines-amapear").show();
            $("#img-pines-amapear").attr("src",reader.result);
            $("#map").css("width","fit-content");
            // $("#anadir-productos-btn").show();
            // $("#anadir-productos-btn").addClass("disabled");
            // $("#terminar-productos-btn").addClass("disabled");
            $("#cropear-btn").show();
            //mostrar modal
            $("#modal-cropper").modal('show');
            $(".toggle-aspect-ratio").show();

            //cropper
            var button = document.getElementById('cropear-btn');
            var result = document.getElementById('result');
            var map = document.getElementById('map');
            var image = document.querySelector('#img-pines-amapear');
            var anadir = document.querySelector("#anadir-productos-btn");
            var options = {
                dragMode: 'move',
                aspectRatio: 1 / 1,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
                background: false,
                restore: false,
                guides: true,
                center: false,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false
            }

            $('#modal-cropper').on('shown.bs.modal', function (event) {
                event.stopPropagation();
                var desdeBack = event.relatedTarget;

                if(!desdeBack){
                    var cropper = new Cropper(image,options);
                }

            });


            $('.cerrarModal').on('click', function(event) {
                var disparador = $(event.target).attr("id");
            
                $(this).closest('.modal').one('hidden.bs.modal', function(event) {

                    //event.stopPropagation();
                    var target = event.target.id;
                    //reseteo input file
                    $("#imagen-pins").val("");
                    $("#map").remove();
                    $("#img-pines-amapear").attr("src","");
                    $(".click-protector-cont").html("");
                    $("#terminar-productos-btn").hide();
                    $("#anadir-productos-btn").hide();
                    $("#popup-prod-cont").hide();

                    if(image.className == 'cropper-hidden'){
                        image.cropper.destroy();
                    }

                });
            });

            //toggle del aspect ratio
            $("#modal-cropper").on('click', '.l-radio', function (e) {
                e.stopPropagation();
                options.aspectRatio = this.dataset.aspect; 
                document.getElementById("aspect-ratio-input").value = options.aspectRatio

                //destruyo el viejo y creo uno nuevo
                image.cropper.destroy();
                var cropper2 = new Cropper(image,options);

                button.classList.remove("disabled");
                
                //click en el boton de "terminar ajuste"
                //lo hago canvas y despues lo paso a img para que siempre quede bien la proporcion
                //despues de eso se elimina el contenedor del cropper y queda solo el "map" para taggear
                button.onclick = function (e) {
                    e.stopPropagation();

                    result.innerHTML = '';
                    result.appendChild(cropper2.getCroppedCanvas(
                        {
                            fillColor: "#aaa",
                            maxHeight: 4096,
                            maxWidth: 4096
                        }
                    ));
                    image.cropper.destroy()

                    var canvas = document.querySelector("#result canvas");
                    var toImg = canvas.toDataURL();
                    image.src = toImg;
                    canvas.style.display = "none";
                    
                };
            });
            


        };
        reader.readAsDataURL(event.target.files[0]);

    }else{
        alertify.error("archivo erroneo")
    }
            
}


function copiarLink(){
    var copyText = document.getElementById("inputCopiarLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
}

//buscador productos en publicaciones
function activarBuscador(param){
    var search = param.val();

    if(search != ""){

            var data_json = {
                "input": search, 
                "accion": "search",
                "perfil": "seller"
            }

            $.ajax({
                url: '/app/producto.php',
                type: 'post',
                data: data_json,
                dataType: 'json',
                success:function(response){
                var resp_len = response.mensaje.length || 0;
                var splide_list = $(".splide__prod_public .splide__list");
                var msj_no_result = $(".msj-no-result");
                
                splide_list.empty();
                msj_no_result.empty();

                if(resp_len>0){
                    //if(jsonData.perfil == "Picker"){
                        for(var i=0; i<resp_len; i++){
    
                            var id_prod = response.mensaje[i].id;
                            var nombre_prod = response.mensaje[i].titulo;
                            var foto_prod = response.mensaje[i].foto;
                            var marca = response.mensaje[i].marca;
                            var foto_src = `/productos_img/${foto_prod}.png` || 0;//viene siempre png?
    
                            /*<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-${i}" src="${img_base_prod}"></li>';*/
    
                            var html = `<li data-id-prod="${id_prod}" title="Por: ${marca}" class="splide__slide splide__slide__img ${id_prod}">
                                        <img data-toggle="modal" data-target="#modal-producto-${i}" src="${foto_src}">
                                        <div class="nombre-producto nombre-producto-${i}">${nombre_prod}</div></li></div>`;
                            // var html = <option class="nombre-producto ${id_prod} nombre-producto-${i}">nombre_prod+</option>'
                            splide_list.append(html);
    
                        }
                        new Splide( '.splide__prod_public', {
                                perPage: 4,
                                rewind : true,
                                pagination: false
                            } ).mount();

                }else{
                    msj_no_result.html('<span class="msj-chico">No se encontraron productos.</span>')
                }

                
                //si es seller
                /*}else{
                    
                    for(var i=0; i<data.length; i++){

                        var id_prod = data[i].id;
                        var nombre_prod = data[i].titulo;
                        var foto_prod = data[i].foto;
                        var foto_src = '/productos_img/${foto_prod}.png' || 0;//viene siempre png?

                        var html = <li class="splide__slide">
                                    <img data-toggle="modal" data-target="#modal-producto-${i}" src="${foto_src}">
                                    <div class="nombre-producto ${id_prod} nombre-producto-${i}">nombre_prod+</div></li></div>';
                        // var html = <option class="nombre-producto ${id_prod} nombre-producto-${i}">nombre_prod+</option>'
                        $(".splide__list").append(html);

                    }
                    new Splide( '.splide', {
                            perPage: 5,
                            rewind : true,
                            pagination: false
                        } ).mount();
                }*/


                },
                error:function(response){
                    alertify.error("ERROR::"+response)
                }
            });
        }
}




function toggleFav(id_publicacion,accion,icon){

    //reemplazo la clase para que se vea como faveado
    let classToggle = (accion == "eliminar") ? "alta" : "eliminar";
    icon.classList.replace("fav-"+accion,"fav-"+classToggle);
    icon.setAttribute("onclick",`toggleFav(${id_publicacion},'${classToggle}',this)`)

    var data = new FormData();
    data.append("accion",accion);
    data.append("id",id_publicacion);
 
    $.ajax({
       url: '/app/favorito.php',
       data: data,
       type: 'POST',
       processData: false,
       contentType: false,
       //async: false,
       success: function(data){
          var dataJ = JSON.parse(data).status;
          var dataM = JSON.parse(data).mensaje;
 
          if (dataJ == 'REDIRECT'){
             console.log("REDIRECT-->"+dataM);							
          }else if(dataJ == 'OK'){
             console.log("OK-->"+dataJ+"/"+dataM);
            if(accion=="eliminar"){
                alertify.error("Sacaste la publicacion a tus favoritos")
            }else{
                alertify.success("Agregaste la publicacion a tus favoritos")
            }
          }else{
             console.log("ELSE-->"+dataJ+"/"+dataM);
          }
       },
       error: function( data, jqXhr, textStatus, errorThrown ){
          alertify.error("ERROR AJAX--> "+data);
          console.log(data);
       }
    });
    return false;
 }

function toggleLikes(id_publicacion,accion,icon){

    let classToggle = (accion == "eliminar") ? "alta" : "eliminar";
    icon.classList.replace("like-"+accion,"like-"+classToggle);
    icon.setAttribute("onclick",`toggleLikes(${id_publicacion},'${classToggle}',this)`)

    var data = new FormData();
    data.append("accion",accion);
    data.append("id",id_publicacion);
 
    $.ajax({
       url: '/app/megusta.php',
       data: data,
       type: 'POST',
       processData: false,
       contentType: false,
       //async: false,
       success: function(data){
          var dataJ = JSON.parse(data).status;
          var dataM = JSON.parse(data).mensaje;
 
          if (dataJ == 'REDIRECT'){
             console.log("REDIRECT-->"+dataM);							
          }else if(dataJ == 'OK'){
             console.log("OK-->"+dataJ+"/"+dataM);
          }else{
             console.log("ELSE-->"+dataJ+"/"+dataM);
          }
       },
       error: function( data, jqXhr, textStatus, errorThrown ){
          ajax("ERROR AJAX--> "+data);
          console.log(data);
       }
    });
    return false;
}

function toggleFollow(id_publicacion,idPublicadorParam,accion,nombre_publicador,icon){

    let classToggle = (accion == "eliminar") ? "alta" : "eliminar";
    icon.classList.replace("seg-"+accion,"seg-"+classToggle);
    icon.setAttribute("onclick",`toggleFollow(${id_publicacion},'${idPublicadorParam}','${classToggle}','${nombre_publicador}',this)`)

    var data = new FormData();
    data.append("accion",accion);
    data.append("id_publicacion",id_publicacion);
    data.append("id_publicador",idPublicadorParam);
 
    $.ajax({
        url: '/app/seguidores.php',
        data: data,
        type: 'POST',
        processData: false,
        contentType: false,
        success: function(data){
        //async: false,
            var dataJ = JSON.parse(data).status;
            var dataM = JSON.parse(data).mensaje;
 
            if (dataJ == 'REDIRECT'){
                console.log("REDIRECT-->"+dataM);									
            }else if(dataJ == 'OK'){
                if(accion=="eliminar"){
                    alertify.error("Dejaste de seguir a "+nombre_publicador)
                }else{
                    alertify.success("Estás siguiendo a "+nombre_publicador)
                }
            }else{
                console.log("ELSE-->"+dataJ+"/"+dataM);
            }
       },
       error: function( data, jqXhr, textStatus, errorThrown ){
          ajax("ERROR AJAX--> "+data);
          console.log(data);
       }
    });
    return false;
}

function appearDataUsuario(){
    $("#dropdown-user-menu").toggle();
    $(".notifs-button-ampliar").hide();
}

function atrasHistory(){
    window.history.back();
}

function buscadorIndex(paramIndex){
    console.log("entro")
    var search = paramIndex.val();
    
    if(search != ""){

        var data_json = {
            "input": search, 
            "accion": "search"
        }

        $.ajax({
            url: '/app/search.php',
            type: 'post',
            data: data_json,
            dataType: 'json',
            success:function(response){

                var sizePublic = response.publicaciones.length;
                var jsonData = response;
                
                $(".splide__home").empty();
                $("#main-super-container").empty();
                $("#carousel-index").empty();
                $(".show-result").empty();
                $(".grid").empty();
                $(".container").empty();

                    if(sizePublic>0){   
                        var public_cat_size = escena.length;
                        var grid_ = '<div class="grid"></div>';
                        var board_el = document.querySelector(".board");
                        var mscontainer = document.querySelector("#main-super-container")
                        var escena_json = JSON.parse(escena);
                        var escena_json_length = escena_json.length;
                        
                        if(board_el == null){
                            var board_new = document.createElement("div");
                            board_new.innerHTML = grid_
                            board_new.classList.add("board");
                            mscontainer.appendChild(board_new);
                        }else{
                            board_el.innerHTML = grid_
                        }

                        for(var x=0; x<sizePublic; x++){           
                            var id_public = jsonData.publicaciones[x].id || '';
                            var id_public_cat = jsonData.publicaciones[x].id_publicacion_categoria || 0;
                            var subescena1 = jsonData.publicaciones[x].subescena1;
                            var nombre_public = jsonData.publicaciones[x].publicacion_nombre || '';
                            var descr_public = jsonData.publicaciones[x].publicacion_descripcion || '';
                            var imagen_id = jsonData.publicaciones[x].foto || '';
                            var producto = jsonData.publicaciones[x].pid || 0;
                            var foto_src = `/publicaciones_img/${imagen_id}.png` || 0;//viene siempre png?
                            var favorito = jsonData.publicaciones[x].favorito || 0;
                            var fav_accion = "";
                            var full_url = `/ampliar-publicacion-home.html?id=${id_public}&accion=ampliar&cat=${subescena1}`

                            var public_html2 =
                                `<div class="grid-item">
                                    <div class="content-col-div content-col-div-${id_public} cat-${id_public_cat}">
                                        <div class="overlay-public">
                                            <a class="link-ampliar-home" href="${full_url}"></a>
                                            <div class="public-title-home">${nombre_public}</div>
                                            <div class="text-overlay">
                                                <span class="text-overlay-link share-sm" onclick="pathShareHome('${full_url}')">
                                                    <a href="javascript:void(0)"><i class="fas fa-share-alt" ></i></a>
                                                </span>
                                                <span class="text-overlay-link text-overlay-link-${id_public}"></span>
                                            </div>
                                        </div>
                                        <img src="${foto_src}" alt="img-${imagen_id}">
                                    </div>
                                </div>`;

                            $(".grid").append(public_html2)
            
                            
                        }//end for

                        var $grid = $('.grid').imagesLoaded( function() {
                            // init Masonry after all images have loaded
                            $grid.masonry({
                                itemSelector: '.grid-item',
                                columnWidth: '.grid-sizer',
                                // percentPosition: true,
                                gutter: 10,
                                horizontalOrder: true
                            });
                        });

                    }else{
                        var sin_result = `<div class="sin-result-index">Lo sentimos, no hemos encontrado ninguna publicaci&oacute;n para esta b&uacute;squeda.</div>`
                        $(".board").html(sin_result)

                    }
            },
            error:function(response,data){
                alertify.error("ERROR::"+response)
                console.log(response)
                console.log(data)
            }
        });
    }else{
        window.location.replace("/");
    }
}

function ampliarNotif(notifs){

    let sizeNotifs = notifs.length || 0;
    let sizeContenedorNotifs = document.querySelector(".notifs-button-ampliar").childElementCount;

    if(sizeNotifs>0){
        for(var i=0; i<sizeNotifs; i++){
            
            let compracompra = notifs[i].compracompra || 0;
            let json_notif = notifs[i].json_notificacion || "";
            let json_notif_p = JSON.parse(json_notif) || [];
            let tipo_notif = notifs[i].tipo_notificacion || "";
            let id = notifs[i].id || 0;
            let json_notif_p_l = Object.keys(json_notif_p).length || 0;

            let foto = json_notif_p.foto || 0;
            let id_jn = json_notif_p.id || 0;
            let id_publicacion_categoria = json_notif_p.id_publicacion_categoria || "";
            let pid = json_notif_p.pid || "";
            let publicacion_descripcion = json_notif_p.publicacion_descripcion || "";
            let nombre = json_notif_p.nombre || "";
            let nombre_producto = json_notif_p.nombre_producto || "";
            let publicacion_nombre = json_notif_p.publicacion_nombre || "";
            let usuario_alta = json_notif_p.usuario_alta || 0;
            let foto_prod = json_notif_p.foto_id;
            let comentario = json_notif_p.comentario;
            let foto_src = `/productos_img/${foto_prod}.png`;
            let id_special = id+i;//parqa que no se matchee con los que viene en la otra paginacion
            
            let html_favorito = `<div>${nombre} a&ntilde;adi&oacute; tu publicaci&oacute;n "${publicacion_nombre}" como favorita</div>`;
            let html_seg = `<div>${nombre} te esta siguiendo</div>`;
            let html_vendedor = `<div><a href="/mis-ventas.html">Vendiste ${nombre_producto}!</a></div>`;
            let html_foto_prod = `<img class="mr-3 img-notifs" src="${foto_src}" alt="img_notif">`;
            let html_comentario = `<div><a href="/ampliar-publicacion-home.html?id=${id_jn}&accion=ampliar&cat=${id_publicacion_categoria}">${nombre} coment&oacute; "${comentario}" en tu public.: "${publicacion_nombre}"</a></div>`;
            let html_notif = 
                        `<div class="media notif-id-${id_special}">
                            <i class="notif-icon notif-icon-${id_special} mr-3 fas fa-heart heart-notif"></i>
                            <div class="media-body media-body-${id_special}"></div>
                            <i onclick="eliminarNotif('${id_special}')" class="eliminar-notif fas fa-times"></i>
                        </div>`;
            
                        
                        let notif_id_media = $(".media-body-"+id_special);
                        let notif_icon = $(".notif-icon-"+id_special);
                        let notif_id = $(".notif-id-"+id_special);
                        
            //aparece el contador de notifs con nro
            $(".count-notif").show();
            $(".count-notif").text(sizeNotifs)
            //

            $(".notifs-listado").append(html_notif)

            switch(tipo_notif){
                case "favorito" : 
                    $(".media-body-"+id_special).append(html_favorito)
                    break;
                case "seguidores" : 
                    $(".media-body-"+id_special).append(html_seg)
                    break;
                case "vendedor" : 
                    $(".notif-id-"+id_special).prepend(html_foto_prod);
                    $(".notif-icon-"+id_special).remove();
                    $(".media-body-"+id_special).html(html_vendedor);
                    break;
                case "comentario" : 
                    $(".media-body-"+id_special).append(html_comentario)
                    break;
                default: alertify.error("error en notificaciones")
            }

        }

    }else if(sizeContenedorNotifs<0){
        //si no hay ninguna dibujada
        let no_notif = `<div class="no_notif"><i class="fas fa-flag"></i>&nbsp;&nbsp;No hay notificaciones por el momento.</div>`
        $(".notifs-button-ampliar").html(no_notif);
    }else{
        let no_notif2 = `<div class="no_notif"><i class="fas fa-flag"></i>&nbsp;&nbsp;No hay m&aacute;s notificaciones por el momento.</div>`
        $(".notifs-vermas").css("display","none");
        $(".notifs-button-ampliar").append(no_notif2);
    }
        
}

function eliminarNotif(id_notif){

    var data = new FormData();
    data.append("accion","eliminar");
    data.append("id",id_notif);
 
    $.ajax({
        url: '/app/notificaciones.php',
        data: data,
        type: 'POST',
        processData: false,
        contentType: false,
        success: function(data){
        //async: false,
            var dataJ = JSON.parse(data).status;
            var dataM = JSON.parse(data).mensaje;
            console.log(data)
            if(dataJ == 'OK'){
                //console.log("OK-->"+dataJ+"/"+dataM);
                var siHayNotifs =  $(".notifs-button-ampliar").find(".media").length;
                var count_notif = document.querySelector(".count-notif")
                var count_notif_int = parseInt(document.querySelector(".count-notif").innerText)
                
                if(siHayNotifs == 1){
                    $(".notif-id-"+id_notif).remove();
                    var no_notif = `<div class="no_notif"><i class="fas fa-flag"></i>&nbsp;&nbsp;No hay notificaciones por el momento.</div>
                    $(".notifs-button-ampliar").html(no_notif)`;
                    
                    //bajo cantidad de notifs
                    count_notif_int = count_notif_int - 1;
                    count_notif.style.display = "none";
                }else{
                    
                    $(".notif-id-"+id_notif).remove();
                    count_notif_int = count_notif_int - 1;
                    count_notif.innerHTML = count_notif_int;
                }
            }else{
                console.log("ELSE-->"+dataJ+"/"+dataM);
            }
       },
       error: function( data, jqXhr, textStatus, errorThrown ){
          ajax("ERROR AJAX--> "+data);
          console.log(data);
       }
    });
    return false;
}
    
function showSearchMobile(){
    var brand = document.getElementById("brand-container");
    var items = document.getElementById("items-navbar-container");
    var search = document.getElementById("search-container");
    var cancelar = document.getElementById("cancelar-search");

    brand.style.display = "none";
    items.style.display = "none";
    search.style.display = "inline-block";
    cancelar.style.display = "inline-block";
}

function cancelSearchMobile(){
    var brand = document.getElementById("brand-container");
    var items = document.getElementById("items-navbar-container");
    var search = document.getElementById("search-container");
    var cancelar = document.getElementById("cancelar-search");

    brand.style.display = "inline-block";
    items.style.display = "inline-block";
    search.style.display = "none";
    cancelar.style.display = "none";
}

function siguiente(event){

    event.stopPropagation();
    var target = event.target.id;
    //reseteo input file
    $("#imagen-pins").val("");
    
    if(target != "modal-cropper"){                
        $("#map").remove();
        $("#img-pines-amapear").attr("src","");
        $(".click-protector-cont").html("");
        $("#terminar-productos-btn").hide();

    }else if(image.className == 'cropper-hidden'){
        image.cropper.destroy();
    }

}

function inViewport(el){
    //var pe = document.getElementsByClassName("test-pop");
    var r, html;
    if ( !el || 1 !== el.nodeType ) { return false; }
    html = document.documentElement;
    r = el.getBoundingClientRect();
    /*console.log("r.left",r.left)
    console.log("widt client",html.clientWidth)*/

    return ( !!r
       && r.bottom >= 0
       && r.right >= 0
       && r.top <= html.clientHeight
       && r.left <= html.clientWidth
    );
}

function appearSelect(){
    
    var escena = $("#escena-param-container");
    //primero limpio el container
    escena.empty();
    escena.show();

    var jesc = jsonTestSelect.escenas || 0;
    var sizeEsc = jesc.length || 0;

    for(var i=0; i<sizeEsc; i++){
        var nombre_esc = jsonTestSelect.escenas[i].nombre || 0;
        var id_esc = jsonTestSelect.escenas[i].id || 0;
        //
        var param = jsonTestSelect.escenas[i].param || [];
        var paramArray = param.split(",");
        var option_selected = $('#publicacion_categoria option').filter(':selected').text();

        if(nombre_esc == option_selected){
            for(var y=0; y<paramArray.length; y++) {
                var element = paramArray[y];
                console.log(element)
                var input_estilo = 
                `<div class="form-group">
                    <label for="${element}_public">${element}</label>
                    <input type="text" id="${element}_public" class="form-control" name="${element}_public">';
                </div>`

                escena.append(input_estilo)
            }
        }
    }
    
}

function showSeguidoresSeguidos(tipo){
    
    let obj = (tipo=="seguidores") ? jsonData.seguidores : jsonData.seguidos;
    let sizeObj = obj.length;
    let body = document.querySelector("body");
    let overlay_html =
        `<div class="overlay overlay-${tipo}">
            <div style="width: 400px;" class="lightBox lightBox-${tipo}">
                <a href="javascript:void(0)" id="cerrar-light" onclick="cerrarOverlay('overlay-${tipo}')"><i class="fas fa-times-circle"></i></a>
                <h3 class="count-${tipo}">Tus ${tipo}</h3>
                <hr>
                <div class="container-${tipo}"></div>
            </div>
        </div>`

    body.insertAdjacentHTML("afterbegin",overlay_html)
    document.querySelector(".overlay-"+tipo).style.display = "block";

    if(sizeObj>0){
        for(var i=0; i<sizeObj; i++){

            //let idUsuario = obj[i].idUsuario || 0;
            let email = obj[i].email || "";
            let apellido = obj[i].apellido || "";
            let nombre = obj[i].nombre || "";
            let container_append = document.querySelector(".container-"+tipo);
            let overlay_html_media = 
                `<div class="media seguidor">
                    <img class="mr-3 img-seg" src="/imagen_perfil/generica.png" alt="${nombre}">
                    <div class="media-body">
                        <h5 class="mt-0">${nombre} ${apellido}</h5>
                        <span>${email}</span>
                    </div>
                </div>`
            
            container_append.insertAdjacentHTML("beforeend",overlay_html_media)
        }
    }else{
        alertify.error("No ten&eacute;s ning&uacute;n"+tipo)
    }
    
}

  
/**search*/

function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
  }



function activarBuscadorRelated(param){
    var search = param.val();

    if(search != ""){
        var data_json = {
            "input": search, 
            "accion": "search"
        }

        $.ajax({
            url: '/app/busqueda.php',
            type: 'post',
            data: data_json,
            dataType: 'json',
            success:function(response){
                if(response.mensaje.length > 0){
                    if(response.mensaje[0] !== "undefined"){
                        var result_search = response.mensaje[0].search;
                        var relatedArr = [];
                        var buscadorNode = document.getElementById("buscador-index-input");

                        relatedArr.splice(0,0,result_search);
    
                        //llamo a func
                        autocomplete(buscadorNode, relatedArr);
                    }else{
                        // console.log("no hay result")
                    }
                }else{
                    // console.log("no hay result")
                }
            },
            error:function(response){
                alertify.error("ERROR::"+response)
            }
        });
    }
}

function pathShareHome(param){
    /*link a public para copiar*/
    var Url = window.location.href;
    var UrlEncoded = encodeURIComponent(Url);
    var title = "taggeon";
    
    $(".overlayShare").show();
    
    $('#cerrar-light').click(function() {
        $('.overlayShare').css("display", "none");
    });
    console.log(param)
    if(param == ""){
        $("#inputCopiarLink").val("https://taggeon.com/")
    }else{
        $("#inputCopiarLink").val(Url+param)
    }
    
	/*document.getElementById("fa-facebook-square").href="http://www.facebook.com/share.php?u=" + UrlEncoded;
	document.getElementById("fa-twitter-square").href="http://twitter.com/home?status=" + title + " " + UrlEncoded;
	//document.getElementById("fa-instagram-square").href="http://twitter.com/home?status=" + title + " " + UrlEncoded;*/
	//document.getElementById("fa-pinterest-square").href="mailto:?body=Take a look at this page I found: " + title + ". You can read it here: " + Url;
}

function getSubCat(valueParam,source,target){

    var catData = new FormData();
    catData.append("accion","subcategoria");
    catData.append("id",valueParam);
    
    $.ajax({
        url: '/app/producto.php',
        data: catData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function( data ,response ){
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){

                var subcats = data.mensaje || [];
                var subcats_length = data.mensaje.length || 0;
                var targetHtml = $(target);
                var target_length = targetHtml.find("option").length;
                var itemsNext = $(source).nextAll();
                var cat_select_html = ''

                if(subcats=="" || subcats==null){
                    return 0;
                }

                //si hay options en el select proximo, lo borro
                if(target_length > 1){
                    //console.log(itemsNext)
                    itemsNext.each(function(){
                        $(this).empty()
                        $(this).removeClass("showCat");
                    });
                }
                
                for(var i=0; i<subcats_length; i++) {
                    var cat_id = subcats[i].id;
                    var cat_nombre = subcats[i].nombre;
     
                    cat_select_html += '<option value="'+cat_id+'">'+cat_nombre+'</option>';
     
                }
                targetHtml.html(cat_select_html)
                targetHtml.addClass("showCat");

            }else{
                alertify.error("En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.")
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj);
        }
    });
    return false;
}

function showLoadingSvg(thisParam){
    let html = `<div class="loading-wrap">
    <div class="loading-hole">&nbsp;</div>
    </div>`
    console.log(thisParam)
    thisParam.insertAdjacentHTML("afterbegin",html)
}

function hideLoadingSvg(thisParam){

    let parent = thisParam.parentElement; 
    console.log("parent",parent)
    let loading = parent.querySelector(".loading-wrap");
    console.log("loading",loading)
    
    loading.remove();
}

function getEscenas(valueParam,idSub){
    
    let escena_parse = (valueParam=="Arquitectura") ? JSON.parse(escena) : JSON.parse(escena2);
    let escena_length = escena_parse.length || 0;
    let cat_select_html = '';
    //let sub_esc = (subescena_json==null) ? "this.value" : "this.value,"+subescena_json;
    const espacio_container = document.querySelector(".tipo-espacio-container");
    const sel_tipo_esp = document.querySelector("#sel_tipo_esp");
    const label_hidden = document.querySelector(".label-hidden")
    const subescenas_container = document.querySelector("#subescenas-container")
    
    if(valueParam=="Indumentaria"){
        label_hidden.style.display = "none";
        subescenas_container.style.display = "none";
        sel_tipo_esp.removeAttribute("onchange")
    }else{
        sel_tipo_esp.setAttribute("onchange","getSubEscena(this.value)")
    }

    for(var i=0; i<escena_length; i++) {
        let id_padre = escena_parse[i].id_padre;
        let cat_id = escena_parse[i].id;
        let cat_nombre = escena_parse[i].nombre;
        let op_selected = '<option value="'+cat_id+'" selected>'+cat_nombre+'</option>';
        let op = '<option value="'+cat_id+'">'+cat_nombre+'</option>';
        let option_html = (idSub==cat_id) ? op_selected : op;
        
        if(id_padre == null){
            cat_select_html += option_html;
        }
    }
    sel_tipo_esp.innerHTML = cat_select_html;
    espacio_container.style.display = "block";
    
}

function getSubEscena(valueParam,subescena_json){

    var catData = new FormData();
    catData.append("accion","subescena");
    catData.append("id",valueParam);
    
    $.ajax({
        url: '/app/publicacion.php',
        data: catData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(data,response){
            if(data.status == 'OK'){
                var subEscena = data.mensaje || [];
                var subEscena_length = data.mensaje.length || 0;
                var subesc_container = $("#subescenas-container");
                var label_hidden = $(".label-hidden")
                var targetHtml = $("#subescenas-container");
                var cat_select_html = '';

                for(var i=0; i<subEscena_length; i++) {
                    var cat_id = subEscena[i].id;
                    var cat_nombre = subEscena[i].nombre;
                    cat_select_html += 
                    '<div><label for="esc_'+cat_id+'">'+cat_nombre+'</label><select data-name-sel="'+cat_nombre+'" class="tipo_esp" name="'+cat_nombre+'" id="esc_'+cat_id+'" onchange="showSelected(\'esc_'+cat_id+'\');this.removeAttribute(\'onchange\')" onclick="getParamTipoEspacio('+cat_id+',\'esc_'+cat_id+'\');this.removeAttribute(\'onclick\')">'+
                       '<option value="" selected disabled hidden required>Seleccione un par&aacute;metro</option>'+
                    '</select></div>';
                }
                targetHtml.html(cat_select_html)
                subesc_container.css("display","block");
                label_hidden.css("display","block");
                
            }else{
                console.log("else")
                console.log(response)
                console.log(data)
            }
        },
        complete: function(data){//para el editar
            if(window.location.pathname=="/editar-publicacion.html" && subescena_json !== undefined){
                subescena_json.forEach(el => {
                    let value_id = el.value.split("-")[0];
                    let value_name = el.value.split("-")[1];
                    let name = el.name;
                    let name_sel = $("#subescenas-container").find("[data-name-sel='"+name+"']");
                    let option = '<option value="'+value_id+'-'+value_name+'" selected>'+value_name+'</option>'
                    
                    //le apendeo el option que fue antes seleccionado. si quiere cambiarlo se refresca el select
                    name_sel.html(option);
                    name_sel.css("background-color","beige");
                    
                });
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj);
        }
    });
    return false;
}

function showSelected(thisParam){
    let obj = document.querySelector("#"+thisParam)
    let temp = '&nbsp;<i class="fas fa-check-double"></i>'
    obj.insertAdjacentHTML("afterend",temp)
    obj.style.backgroundColor = "beige";
}

function getParamTipoEspacio(valueParam,target){
    //$(this).removeA
    var catData = new FormData();
    catData.append("accion","subescena");
    catData.append("id",valueParam);

    $.ajax({
        url: '/app/publicacion.php',
        data: catData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(data,response){
            if(data.status == 'OK'){
                var subEscena = data.mensaje || [];
                var subEscena_length = data.mensaje.length || 0;
                var targetHtml = $("#"+target);
                var cat_select_html = '';
                
                for(var i=0; i<subEscena_length; i++) {
                    var cat_id = subEscena[i].id;
                    var cat_nombre = subEscena[i].nombre;
                    cat_select_html += '<option value="'+cat_id+'-'+cat_nombre+'">'+cat_nombre+'</option>';
                }
                //targetHtml.prepend('<option selected disabled hidden required value="">-</option>')
                targetHtml.html(cat_select_html)
            }else{
                console.log("else")
                console.log(response)
                console.log(data)
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj);
        }
    });
    return false;
}

function getParamTipoEspacioEditar(titulo_sel,id_option,tipo_espacio){
    var catData = new FormData();
    catData.append("accion","subescena");
    catData.append("id",tipo_espacio);

    $.ajax({
        url: '/app/publicacion.php',
        data: catData,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(data,response){
            if(data.status == 'OK'){
                var subEscena = data.mensaje || [];
                var subEscena_length = data.mensaje.length || 0;
                var name_sel = $("#subescenas-container").find("[data-name-sel='"+titulo_sel+"']");
                var cat_select_html = '';
                //name_sel.prop("onclick", null)
                //spolo seleccionooooo SIN AJAXXXXX
                for(var i=0; i<subEscena_length; i++) {
                    var cat_id = subEscena[i].id;
                    var cat_nombre = subEscena[i].nombre;
                    cat_select_html += '<option value="'+cat_id+'-'+cat_nombre+'">'+cat_nombre+'</option>';
                }
                name_sel.html(cat_select_html)
            }else{
                alertify.error(data);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alertify.error(msj);
        }
    });
    return false;
}

function showFollow(el){
    var clase = el.classList[0];
    var toShow = $("."+clase).find(".follow_public");
    toShow.addClass("showFollow");
}

function hideFollow(el){
    var clase = el.classList[0];
    var toShow = $("."+clase).find(".follow_public");
    toShow.removeClass("showFollow");
}

function ampliarOverlay(clase){
    let classAmpliar = $("."+clase);
    let otrosOverlay = $(".overlay:not(."+clase+")");


    classAmpliar.css("display", "block");
    otrosOverlay.css("display", "none");
    //cierro si hay otro overlay abierto
}

function cerrarOverlay(obj){
    obj.style.display = "none"
}

function posicionarPublic(){
    var WinLocSplit = window.location.href.split("=")[1].split("&")[0] || "";
    var ancla_html = $("#ancla-desde-home-"+WinLocSplit);
    if(ancla_html.length>0){
        var public_pos = ancla_html.offset().top - 80;
        $('html,body').scrollTop(public_pos)
    }
}

function fetchIdCarrito(id_public,id_prod,value_cant){
    const URL = "/app/carrito.php"

    let dataCarr = new FormData();
    dataCarr.append("accion","alta");
    dataCarr.append("id",id_prod);
    dataCarr.append("id_publicacion",id_public);
    dataCarr.append("cantidad",value_cant);//hard
    
    fetch(URL, {
        method: 'POST',
        body: dataCarr,
    }).then(res => res.json())
    .then(response => {
        let id_carrito = response.mensaje;
        window.location.replace("/carritos.html?id_carrito="+id_carrito)
    })
    .catch(error => alertify.error("No se pudo agregar el producto. Intente mas tarde"))

}

function retirarDinero(){
    console.log("fes")
    const URL = "/app/metrica.php";

    let data_d = new FormData();
    data_d.append("accion","solicitud");

    fetch(URL, {
       method: 'POST',
       body: data_d,
    }).then(res => res.json())
    .then(response => {
       console.log(response)
    })
    .catch(error => alertify.error("Intente mas tarde"))
}

function confirmarEnvioDinero(){
    const URL = "/app/metrica.php";
    let totalJson = jsonData.total;

    let data_d = new FormData();
    data_d.append("accion","confirmar");
    data_d.append("total",totalJson);

    fetch(URL, {
       method: 'POST',
       body: data_d,
    }).then(res => res.json())
    .then(response => {
       console.log(response)
    })
    .catch(error => alertify.error("Intente mas tarde"))
}

function desvincularMp(urlParam,accionParam){

    let data = new FormData();
    data.append('accion',accionParam)

    fetch(urlParam, {
        method: 'POST',
        body: data,
    })
    .then(res => res.json())
    .then(response => {
        window.location.replace("/editar-usuario.html")
    })
    .catch(error => alertify.error("No se pudo desvincular"+error))    
}

function llamadaSimple(idParam,accionParam,urlParam){

    let data = new FormData();
    data.append('id',idParam)
    data.append('accion',accionParam)

    fetch(urlParam, {
        method: 'POST',
        body: data,
    })
    .then(res => res.json())
    .then(response => {
        let path = window.location.pathname;
        window.location.replace(path)
    })
    .catch(error => alertify.error("No se pudo borrar publicacion"+error))
}

function checkout(id_public,id_prod){
    const URL = "/app/carrito.php"

    let dataCarr = new FormData();
    dataCarr.append("accion","alta");

    fetch(URL, {
        method: 'POST',
        body: dataCarr,
    }).then(res => res.json())
    .then(response => {
        let id_carrito = response.mensaje;
        console.log(id_carrito)
        //window.location.replace("/cobrar-compra.html?id="+id_carrito)
    })
    .catch(error => console.error('Error:', error))

}

function getProdPublicTest(param){
    const URL = `/app/producto.php?accion=get&id=${param}`

    /*let dataCarr = new FormData();
    dataCarr.append("accion","alta");*/

    fetch(URL).then(res => res.json())
    .catch(error => console.error('Error:', error))
    .then(response => function(){
        
        let resp_len = response.mensaje.length
        let nombre_prod = jsonData.productos[index].titulo;
        let precio_prod = jsonData.productos[index].precio;
        let marca_prod = jsonData.productos[index].marca;
        let color_prod = jsonData.productos[index].color;
        let descr_prod = jsonData.productos[index].descr_producto;
        let id_prod_json = jsonData.productos[index].id;
        let stock_prod = jsonData.productos[index].stock;
        let foto_prod = jsonData.productos[index].foto;
        let nombre_completo = jsonData.nombre+""+jsonData.apellido;
        let foto_src_prod = `/productos_img/${foto_prod}.png`;

        if(resp_len > 0){



        }else{
            alertify.error("ERROR")
        }
        
    });

}


function getMisPublic(data){
    var sizePublic = data.length;
    //console.log("publics-->",data)
    let grid = document.querySelector(".grid");

    if(sizePublic>0){
        for(let i=0; i<sizePublic; i++){


            let id_public = data[i].id;
            let id_public_cat = data[i].id_publicacion_categoria;
            let nombre_public = data[i].publicacion_nombre;
            let descr_public = data[i].publicacion_descripcion;
            let imagen_id = data[i].foto;
            // let full_url = `/ampliar-publicacion.html?id=${id_public}&accion=ampliar&cat=${id_public_cat}`
            let full_url = `/ampliar-publicacion.html?id=${id_public}&accion=ampliar`
            //let imagen_public_html = document.querySelector(".imagen-public-"+imagen_id);
            ///app/publicacion.php?id=${id_public}&accion=eliminar"

            var foto_src = `/publicaciones_img/${imagen_id}.png` || 0;        

            var public_html2 =
                `<div class="grid-item">
                    <div class="content-col-div content-col-div-${id_public} cat-${id_public_cat}">
                        <div class="overlay-public">
                            <a class="link-ampliar-home" href="${full_url}"></a>
                            <div class="public-title-home">${nombre_public}</div>
                            <div class="text-overlay">
                                <span class="text-overlay-link"><a href="/editar-publicacion.html?id=${id_public}&accion=editar"><i title="Editar Publicaci&oacute;n" class="fas fa-edit"></i></a></span>&nbsp;
                                <span class="text-overlay-link eliminar-public" data-title="${id_public}"><a onclick="llamadaSimple('${id_public}','eliminar','/app/publicacion.php')" href="javascript:void(0)"><i title="Eliminar Publicaci&oacute;n" class="fas fa-trash-alt"></i></a></span>
                            </div>
                        </div>
                        <img src="${foto_src}" alt="img-${imagen_id}">
                    </div>
                </div>`;

            grid.insertAdjacentHTML("beforeend",public_html2)
            //imagen_public_html.attr("src", foto_src);

        }
        
    }else{
        var html_sin_public = `<div class="no-content">No hay Publicaciones subidas.<br>Haz click en "+" en el menu principal para crear una.</div>`;
        grid.insertAdjacentHTML("afterend",html_sin_public);
    }
}

function getMisFavoritos(data){
    var sizePublic = data.length;
    let grid = document.querySelector(".grid");     
    console.log(data) 

    if(sizePublic>0){
        for(var i=0; i<sizePublic; i++){
            var id_public = data[i].id;
            var id_public_cat = data[i].id_publicacion_categoria;
            var nombre_public = data[i].publicacion_nombre;
            var descr_public = data[i].publicacion_descripcion;
            var imagen_id = data[i].foto;
            var full_url = '/ampliar-publicacion.html?id='+id_public+'&accion=ampliar';
            var foto_src = '/publicaciones_img/'+imagen_id+'.png' || 0;  

            var html_public =
            `<div class="grid-item">
                <div class="content-col-div content-col-div-${id_public} cat-${id_public_cat}">
                    <div class="overlay-public">
                        <a class="link-ampliar-home" href="${full_url}"></a>
                        <div class="public-title-home">${nombre_public}</div>
                        <div class="text-overlay">
                            <span class="text-overlay-link eliminar-public" data-title="${id_public}"><a href="/app/publicacion.php?id=${id_public}&accion=eliminar"><i title="Eliminar Publicaci&oacute;n" class="fas fa-trash-alt"></i></a></span>
                        </div>
                    </div>
                    <img src="${foto_src}" alt="img-${imagen_id}">
                </div>
            </div>`;
                                
            grid.insertAdjacentHTML("beforeend",html_public)

        }
    }else{
        var html_sin_public = '<p style="color:gray; font-style: italic; text-align: center">No hay Publicaciones faveadas.</p>';
        $(".contenedor-mis-public").append(html_sin_public);
    }
}

function dibujarMetricas(data){
    var data_l = data.length;

    if(data_l>0){
        for(var i=0; i<data_l; i++){

            var cantidad = data[i].cantidad || 0;
            var carrito_subtotal = data[i].carrito_subtotal || 0;
            var carrito_total = data[i].carrito_total || 0;
            var operacion = data[i].operacion || "";
            var comision = data[i].comision || "";
            var fecha_alta = data[i].fecha_alta || 0;
            var foto = data[i].foto || "";
            var pago_id = data[i].pago_id || 0;
            var id_producto = data[i].id_producto || 0;
            var id_publicacion = data[i].id_publicacion || "";
            var id_usuario_publicador = data[i].id_usuario_publicador || 0;
            var id_vendedor = data[i].id_vendedor || 0;
            var nombre_producto = data[i].nombre_producto || "";
            var costo_venta = data[i].costo_venta || "";
            var publicacion_nombre = data[i].publicacion_nombre || "";
            var total = jsonData.total || 0;
            var restan = jsonData.restan || 0;
            var usuario_vendedor = data[i].usuario_vendedor || "";
            var total_taggeador = data[i].total_taggeador || 0;
            var total_tienda = data[i].total_tienda || 0;
            var total_vendedor = data[i].total_vendedor || 0;
            var usuario_alta = data[i].usuario_alta || 0;
            var restan_html = document.querySelector(".num-restan")  || [];
            var el = document.querySelector(".data-metricas>tbody");
            var a_liquidar_html = document.querySelector(".num-big") || [];

            //Operaci,Fecha,Producto,Tienda,Costo,Comision
            a_liquidar_html.innerHTML = "$"+total;
            restan_html.innerHTML = "$"+restan+".00";;

            var child = `
            <tr>
                <td>${pago_id}</td>
                <td>${fecha_alta}</td>
                <td>${nombre_producto}</td>
                <td>${usuario_vendedor}</td>
                <td>${costo_venta}</td>
                <td>${comision}</td>
                <td>-</td>
                <td>-</td>
            </tr>
            `

            el.insertAdjacentHTML("beforeend",child);
        }
    }else{
        var no_metricas = '<h2>Metricas</h2><hr><h1 class="text-center">Todavía no hay datos para mostrar</h1>'
        $(".inner-compras").html(no_metricas)
    }
}

function getMisCompras(data){
    const sizeCompras = data.length;
    const flex_container = document.querySelector(".flex-container")

    if(sizeCompras>0){
        for(var i=0; i<sizeCompras; i++){
        
            var nombre_producto = data[i].nombre_producto || "";
            var precio_producto = data[i].precio || 0;
            var id = data[i].id || 0;
            var direccion = data[i].envio_nombre_apellido || "";
            var localidad = data[i].envio_ciudad_localidad || "";
            var id_carrito = data[i].id_carrito || 0;
            var name_comprador = data[i].nombre || "";
            var imagen_id = data[i].foto_id || 0;
            var vendedor = data[i].vendedor || "";
            var sizeVendedor = jsonData.vendedor.length || 0;
            var foto_src = `/productos_img/${imagen_id}.png` || 0;
            var compras_html = 
            `<div class="flex-listado">
                <div class="overlay-public">
                    <div class="text-overlay-prod">
                        <!--<span data-title="${id}" class="text-overlay-link">
                            <a href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
                        </span>-->
                        <span data-title="${id}" class="text-overlay-link text-overlay-link-${id}">
                            <a href="/ampliar-compras.html?id=${id_carrito}"><i class="fas fa-eye"></i></a>
                        </span>
                    </div>
                </div>
                <img src="${foto_src}" alt="${foto_src}">
                <div class="prod-datos">
                    <div class="nombre-prod">${nombre_producto}</div>
                    <div class="precio-prod">$ ${precio_producto}</div>
                </div>
            </div>`;
                
            flex_container.insertAdjacentHTML('beforeend', compras_html) 
                

                for(var x=0; x<sizeVendedor; x++){
                    var arr = jsonData.vendedor; 
                    var idUsuario = jsonData.vendedor[x].idUsuario;
                    var obj = arr.find(o => o.idUsuario === idUsuario);
                    if(obj.idUsuario == vendedor){
                        $(".label-compra-vendedor").html(obj.nombre/*" "+obj.apellido*/)
                    }
                }

        }
    }else{
        document.querySelector(".inner-compras").innerHTML = `<hr class="mt-5"><h3 class="text-center"><i> No tienes ninguna compra realizada<i></h3>`
    }

}

function getMisVentas(data){
    const sizeVentas = data.length;
    const flex_container = document.querySelector(".flex-container")

    if(sizeVentas>0){
        for(var i=0; i<sizeVentas; i++){
            var nombre_producto = data[i].nombre_producto || "";
            var precio_producto = data[i].precio || 0;
            var id = data[i].id || 0;
            var direccion = data[i].envio_nombre_apellido || "";
            var localidad = data[i].envio_ciudad_localidad || "";
            var id_carrito = data[i].id_carrito || 0;
            var name_comprador = data[i].envio_nombre_apellido || "";
            var imagen_id = data[i].foto_id || 0;
            var foto_src = `/productos_img/${imagen_id}.png` || "";

            var ventas_html = 
            `<div class="flex-listado">
                <div class="overlay-public">
                    <div class="text-overlay-prod">
                        <!--<span data-title="${id}" class="text-overlay-link">
                            <a href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
                        </span>-->
                        <span data-title="${id}" class="text-overlay-link text-overlay-link-${id}">
                            <a href="/ampliar-mis-ventas.html?id=${id_carrito}"><i class="fas fa-eye"></i></a>
                        </span>
                    </div>
                </div>
                <img src="${foto_src}" alt="${foto_src}">
                <div class="prod-datos">
                    <div class="nombre-prod">${nombre_producto}</div>
                    <div class="precio-prod">$ ${precio_producto}</div>
                </div>
            </div>`;
               
            flex_container.insertAdjacentHTML('beforeend', ventas_html) 
        }
    }else{
        document.querySelector(".inner-ventas").innerHTML = `<hr class="mt-5"><h3 class="text-center"><i> No tienes ninguna venta realizada<i></h3>`
    }

}

function activarNotifs(cant,el){
    let new_cant = cant+5;
    console.log(new_cant)
    el.setAttribute("onclick",`activarNotifs(${new_cant},this)`)

    const dataPaging = {
        cantidad : cant,
        url: "paginador_notificaciones.php"
    }
    getDataPaging(dataPaging);
}

function activarComentarios(cant,tipoParam,idParam,el){
    let new_cant = cant+5;
    el.setAttribute("onclick",`activarComentarios(${new_cant},this)`)

    let tipo = (tipoParam == "producto") ? "paginador_comentarios_producto.php" : "paginador_comentarios.php";

    const dataPaging = {
        cantidad : cant,
        url: tipo,
        id: idParam
    }
    getDataPaging(dataPaging);
}

function getComentarios(comentarios_obj,desde){

    //recorro comentarios en la public
    let comment_length = comentarios_obj.length;

    if(comment_length>0){
        for(var y=0; y<comment_length; y++){
            if(comentarios_obj.length>0){
                let comentario = comentarios_obj[y].comentario || "";
                //let eliminar = comentarios_obj[y].eliminar || "";
                //let fecha_alta = comentarios_obj[y].fecha_alta || "";
                //let fecha_update = comentarios_obj[y].fecha_update || "";
                let id = comentarios_obj[y].id || 0;
                let id_publicacion = comentarios_obj[y].id_publicacion || 0;
                let id_producto = comentarios_obj[y].id_producto || 0;
                let id_switch = (desde == "paginador_comentarios_producto.php") ? id_producto : id_publicacion;
                let nombre_usuario = comentarios_obj[y].nombre_usuario || "";
                let list_container = document.querySelector(".commentbox-list-container-"+id_switch);
                console.log(id_publicacion)
                
                let comentario_html = 
                `<div class="commentbox-list media commentbox-id-${y}">
                    <span class="comment-name">${nombre_usuario}</span>
                   <span class="comment-text">${comentario}</span>
                </div>`;
                let comentario_html2 = "<p>No hay comentarios</p>"
                
                list_container.insertAdjacentHTML("beforeend",comentario_html);
    
            }else{
                list_container.insertAdjacentHTML("beforeend",comentario_html2)
            }
        }
    }else{
        alertify.error("No hay comentarios")
    }

}


function getPublicsAmpliar(data){

    const sizePublic = data.length;
    const escena_json = JSON.parse(escena);
    const escena_json_length = escena_json.length;

    //console.log("publics",data)
    for(var i=0; i<sizePublic; i++){
       
        if(sizePublic>0){
 
            let id_public = data[i].id || 0;
            let id_public_cat = data[i].subescena1 || "";//que onda esto cuando son mas de una??
            let nombre_public = data[i].publicacion_nombre || "";
            let descr_public = data[i].publicacion_descripcion || "";
            let publicador = data[i].nombre_publicador || "";
            let id_publicador = data[i].id_publicador || "";
            let foto_perfil = data[i].foto_perfil || "";
            let like = data[i].megusta || "";
            let imagen_id = data[i].foto || 0;
            let producto = data[i].pid || 0;
            let cat_ampliar_home = jsonData.cat || 0;
            let favorito = data[i].favorito || 0;
            let arrCat = escena_json || 0;
            let foto_src = `/publicaciones_img/${imagen_id}.png` || 0;//viene siempre png?
            let img_publicador = `/imagen_perfil/${foto_perfil}.png` || 0;//viene siempre png?
            let winLoc = window.location.pathname || "";
            let id_usuario = "1";//hard
            let seguidor = "";
            let seguidos = jsonData.seguidos || [];
            let idPublicadorSearch = seguidos.find(o => o.idUsuario === id_publicador) || "";
            let idPublicadorSeguido = idPublicadorSearch.idUsuario;
            let comentarios_obj = data[i].comentarios || [];
            let full_url = window.location.href;      
            let fav_sw = (favorito == null || favorito == 0) ? 'alta' : 'eliminar';
            let like_sw = (like == null || like == 0) ? 'alta' : 'eliminar';
            let seg_sw = (idPublicadorSeguido==id_publicador) ? 'eliminar' : 'alta';


                //dibujo la cat arriba de todo
               var objCat = escena_json.find(o => o.id === cat_ampliar_home) || "";
               var nameCat = objCat.nombre || "";
               $(".title-cat").html(nameCat);
 
            let html_public = `<div id="ancla-desde-home-${id_public}" class="public-ampliar public-actual test2">
                                <div class="header-public header-public-${id_public}" onmouseover="showFollow(this)" onmouseout="hideFollow(this)">
                                    <a class="nombre-perfil-public" href="/ampliar-usuario-redirect.html?id_usuario=${id_publicador}">
                                    <span class="img-perfil-public"><img onerror="this.src=\'/imagen_perfil/generica.png\'" src="${img_publicador}" alt="img-perfil"></span>
                                    <span class="title-public title-public-${i}"></span>
                                    </a>
                                    <span class="follow_public"><i class="fas fa-user-plus seg-${seg_sw}" onclick="toggleFollow(${id_public},'${id_publicador}','${seg_sw}','${publicador}',this);"></i></span>
                                </div>
                                <div class="bodyimg-public-container bodyimg-public-container-${i}">
                                   <img class="imagen-public-${imagen_id}" src="${foto_src}" alt="">
                                   <div class="tag-container tag-container-${i}"></div>
                                </div>
 
 
                               <div id="ancla-${i}" class="productos-public productos-public-${i}">
                                <div class="productos-titulo-public">Productos Relacionados:</div><br>
                                   <div class="productos-titulo-public-gallery productos-titulo-public-gallery-${i}">
                                      <div class="splide splide-prod-tag-${id_public}">
                                         <div class="splide__track">
                                            <ul class="splide__list splide__list__${id_public}"></ul>
                                         </div>
                                      </div>
                                        <div class="splide splide-related splide-prod-${id_public}">
                                           <div class="splide__track">
                                              <ul class="splide__list__${id_public} splide_list_related"></ul>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
 
 
                               <div class="info-public">
                                  <div class="social-public social-public-${id_public}">
                                        <span><i class="fas fa-heart like-${like_sw}" onclick="toggleLikes(${id_public},'${like_sw}',this)"></i></span>
                                        <span><i class="fas fa-star fav-${fav_sw}" onclick="toggleFav(${id_public},'${fav_sw}',this)"></i></span>
                                        <span class="share-sm" onclick="pathShareHome('${full_url}')"><i class="fas fa-paper-plane"></i></span>
                                  </div>
                                  <div class="datos-public">
                                  <div class="info-titulo-public">${nombre_public}</div>
                                  <div class="info-descr-public">${descr_public}</div><hr>
                               </div>
                               <div id="ancla-test-${i}"></div>
                               <div class="commentbox-container">
                                  <div class="commentbox commentbox-id-2">
                                        <div>
                                            <img class="mr-1 commentbox-user-img" src="/imagen_perfil/generica.png" alt="perfil"></div>
                                            <div style="flex-grow: 1;">
                                                <input type="text" id="comentario-${i}" name="comentario" style="width: 100%;" placeholder="Ingrese un comentario">
                                            </div>
                                            <div class="ml-1">
                                                <button onclick="sendComentario('${id_public}','${i}')" value="enviar" class="btn">Enviar</button>
                                            </div>
                                        </div>
                                        <div class="vm-comentarios" onclick="activarComentarios('5','publicacion','${id_public}',this);this.removeAttribute('onclick')"><a href="javascript:void(0)">Ver Comentarios</a></div>
                                  <div class="commentbox-list-container commentbox-list-container-${id_public}">
                                  </div>
                               </div>
                            </div>`;
                            
                document.querySelector(".insert-public").insertAdjacentHTML("beforeend",html_public);
                document.querySelector(".title-public-"+i).innerHTML = publicador;
                console.log(id_public)
                getPublicTags(id_public,producto,i,publicador,id_publicador);
            
            
            
            //imgperfil comentarios
            var img_perfil = $(".img-perfil-usuario-drop").attr("src");
            $(".commentbox-user-img").attr("src", img_perfil);
            
        }else{
            alertify.error("no hay publicaciones")
        }
    }//fin for

    observer()
    posicionarPublic();

}

function getPublicsAmpliarHome(data){

    const sizePublic = data.length;
    const escena_json = JSON.parse(escena);
    const escena_json_length = escena_json.length;

    //console.log("publics",data)
    for(var i=0; i<sizePublic; i++){
       
        if(sizePublic>0){
 
            let id_public = data[i].id || 0;
            let id_public_cat = data[i].subescena1 || "";//que onda esto cuando son mas de una??
            let nombre_public = data[i].publicacion_nombre || "";
            let descr_public = data[i].publicacion_descripcion || "";
            let publicador = data[i].nombre_publicador || "";
            let id_publicador = data[i].id_publicador || "";
            let foto_perfil = data[i].foto_perfil || "";
            let like = data[i].megusta || "";
            let imagen_id = data[i].foto || 0;
            let producto = data[i].pid || 0;
            let cat_ampliar_home = jsonData.cat || 0;
            let favorito = data[i].favorito || 0;
            let arrCat = escena_json || 0;
            let foto_src = `/publicaciones_img/${imagen_id}.png` || 0;//viene siempre png?
            let img_publicador = `/imagen_perfil/${foto_perfil}.png` || 0;//viene siempre png?
            let winLoc = window.location.pathname || "";
            let id_usuario = "1";//hard
            let seguidor = "";
            let seguidos = jsonData.seguidos || [];
            let idPublicadorSearch = seguidos.find(o => o.idUsuario === id_publicador) || "";
            let idPublicadorSeguido = idPublicadorSearch.idUsuario;
            let comentarios_obj = data[i].comentarios || [];
            let full_url = window.location.href;      
            let fav_sw = (favorito == null || favorito == 0) ? 'alta' : 'eliminar';
            let like_sw = (like == null || like == 0) ? 'alta' : 'eliminar';
            let seg_sw = (idPublicadorSeguido==id_publicador) ? 'eliminar' : 'alta';

            if(cat_ampliar_home == id_public_cat){

                //dibujo la cat arriba de todo
               var objCat = escena_json.find(o => o.id === cat_ampliar_home) || "";
               var nameCat = objCat.nombre || "";
               $(".title-cat").html(nameCat);
 
            let html_public = `<div id="ancla-desde-home-${id_public}" class="public-ampliar public-actual test2">
                                <div class="header-public header-public-${id_public}" onmouseover="showFollow(this)" onmouseout="hideFollow(this)">
                                    <a class="nombre-perfil-public" href="/ampliar-usuario-redirect.html?id_usuario=${id_publicador}">
                                    <span class="img-perfil-public"><img onerror="this.src=\'/imagen_perfil/generica.png\'" src="${img_publicador}" alt="img-perfil"></span>
                                    <span class="title-public title-public-${i}"></span>
                                    </a>
                                    <span class="follow_public"><i class="fas fa-user-plus seg-${seg_sw}" onclick="toggleFollow(${id_public},'${id_publicador}','${seg_sw}','${publicador}',this);"></i></span>
                                </div>
                                <div class="bodyimg-public-container bodyimg-public-container-${i}">
                                   <img class="imagen-public-${imagen_id}" src="${foto_src}" alt="">
                                   <div class="tag-container tag-container-${i}"></div>
                                </div>
 
 
                               <div id="ancla-${i}" class="productos-public productos-public-${i}">
                                <div class="productos-titulo-public">Productos Relacionados:</div><br>
                                   <div class="productos-titulo-public-gallery productos-titulo-public-gallery-${i}">
                                      <div class="splide splide-prod-tag-${id_public}">
                                         <div class="splide__track">
                                            <ul class="splide__list splide__list__${id_public}"></ul>
                                         </div>
                                      </div>
                                        <div class="splide splide-related splide-prod-${id_public}">
                                           <div class="splide__track">
                                              <ul class="splide__list__${id_public} splide_list_related"></ul>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
 
 
                               <div class="info-public">
                                  <div class="social-public social-public-${id_public}">
                                        <span><i class="fas fa-heart like-${like_sw}" onclick="toggleLikes(${id_public},'${like_sw}',this)"></i></span>
                                        <span><i class="fas fa-star fav-${fav_sw}" onclick="toggleFav(${id_public},'${fav_sw}',this)"></i></span>
                                        <span class="share-sm" onclick="pathShareHome('${full_url}')"><i class="fas fa-paper-plane"></i></span>
                                  </div>
                                  <div class="datos-public">
                                  <div class="info-titulo-public">${nombre_public}</div>
                                  <div class="info-descr-public">${descr_public}</div><hr>
                               </div>
                               <div id="ancla-test-${i}"></div>
                               <div class="commentbox-container">
                                  <div class="commentbox commentbox-id-2">
                                        <div>
                                            <img class="mr-1 commentbox-user-img" src="/imagen_perfil/generica.png" alt="perfil"></div>
                                            <div style="flex-grow: 1;">
                                                <input type="text" id="comentario-${i}" name="comentario" style="width: 100%;" placeholder="Ingrese un comentario">
                                            </div>
                                            <div class="ml-1">
                                                <button onclick="sendComentario('${id_public}','${i}')" value="enviar" class="btn">Enviar</button>
                                            </div>
                                        </div>
                                        <div class="vm-comentarios" onclick="activarComentarios('5','publicacion','${id_public}',this);this.removeAttribute('onclick')"><a href="javascript:void(0)">Ver Comentarios</a></div>
                                  <div class="commentbox-list-container commentbox-list-container-${id_public}">
                                  </div>
                               </div>
                            </div>`;
                            
                document.querySelector(".insert-public").insertAdjacentHTML("beforeend",html_public);
                document.querySelector(".title-public-"+i).innerHTML = publicador;
                console.log(id_public)
                getPublicTags(id_public,producto,i,publicador,id_publicador);
            
                
             
            } 
            
            //imgperfil comentarios
            var img_perfil = $(".img-perfil-usuario-drop").attr("src");
            $(".commentbox-user-img").attr("src", img_perfil);
            
        }else{
            alertify.error("no hay publicaciones")
        }
    }//fin for

    observer()
    posicionarPublic();

}


function getPublicTags(id_public,tags,index,publicador,id_publicador){

    let splide = new Splide( `.splide-prod-tag-${id_public}`, {
        perPage: 6,
        rewind : true,
        pagination: false
    }).mount();

    let producto_parse = JSON.parse(tags);
    let producto_parse_size = producto_parse.length;
     
    for(let x=0; x<producto_parse_size; x++){
        let id_prod = producto_parse[x].name;
        let coords = producto_parse[x].value;
        let ycoord = coords.split("-")[0];
        let xcoord = coords.split("-")[1];
        console.log("en getpublictags",id_public)
        let tag_html = `<div onclick="getSplideProdPublic(${id_public},'${publicador}',${id_publicador},${index},this)" class="tagg tagg-${id_public}" style="top:${ycoord}%; left: ${xcoord}%">
        <span><img src="../../plugins/dropPin-master/dropPin/dot-circle-solid.svg"></span></div>`;
        document.querySelector(".tag-container-"+index).insertAdjacentHTML("beforeend",tag_html);
            
    }
}

function openTag(index){
    //click en tag ANIMACION
    let prod_public = $(".productos-public-"+index);
    prod_public.toggle(100);
    prod_public.toggleClass("prods-abierto");
    
    if(prod_public.hasClass("prods-abierto")){
        $('html,body').animate({
            scrollTop: prod_public.offset().top - 130
        }, 800)
    }
}


function getPublicsHome(data){
    var sizePublic = data.length;
    console.log(data)
    if(sizePublic>0){
        
        let arrayAll = [];
        
        for(var i=0; i<sizePublic; i++){
            
            let objSubescena = [];
            let tipo_escena = data[i].escena_sel || "";
            let id_public = data[i].id || '';
            let id_public_cat = data[i].subescena1 || 0;
            let nombre_public = data[i].publicacion_nombre || '';
            let descr_public = data[i].publicacion_descripcion || '';
            let imagen_id = data[i].foto || '';
            let producto = data[i].pid || 0;
            let foto_src = `/publicaciones_img/${imagen_id}.png` || 0;//viene siempre png?
            let favorito = data[i].favorito;
            let fav_accion = "";
            let full_url = `/ampliar-publicacion-home.html?id=${id_public}&accion=ampliar&cat=${id_public_cat}`;//tipo de escena,json
            let fav_sw = (favorito == null || favorito == 0) ? 'alta' : 'eliminar';
            let subescena_json = JSON.parse(data[i].subescena_json);
            let nombre_subes1 = data[i].nombre_subescena1;
            const public_html = 
            `<div>
                <div class="content-col-div content-col-div-${id_public} cat-${id_public_cat}">
                    <div class="overlay-public">
                    <a class="link-ampliar-home" href="${full_url}"></a>
                    <div class="public-title-home">${nombre_public}</div>
                        <div class="text-overlay">
                            <span class="text-overlay-link share-sm" onclick="pathShareHome('${full_url}')">
                                <i class="fas fa-share-alt"></i>
                            </span>
                            <span class="text-overlay-link"><i class="fas fa-star fav-${fav_sw}" onclick="toggleFav(${id_public},'${fav_sw}',this)"></i></span>
                        </div>
                    </div>
                    <img src="${foto_src}" alt="img-${imagen_id}">
                </div>
            </div>`;
            
            
            //1. primero lleno un array con los subesc que vengan con esta public
            for(var y=0; y<subescena_json.length; y++){
                let tipo_esp_param_padre = subescena_json[y].name
                let tipo_esp_param_id = subescena_json[y].value.split("-")[0]
                let tipo_esp_param_child =  subescena_json[y].value.split("-")[1]
                //let full_name_array = [`${nombre_subes1} ${tipo_esp_param_child}`] //le pego el nombre principal adelante
                let full_name = `${nombre_subes1} ${tipo_esp_param_child}`
                let full_id = id_public_cat+tipo_esp_param_id

                const newObj = {
                    id : full_id,
                    subesc : full_name
                }

                objSubescena.push(newObj)

                //antes de pushear al array principal, checkeo si ya existe esa combinacion
                let find_array = arrayAll.find(o => o.id === full_id)
                if(find_array == undefined){
                    arrayAll.push(newObj)
                }
                
            }

            let se_length = objSubescena.length;

            //leo el array generado arriba y le apendeo la publicacion
            if(se_length>0){
                for(var x=0; x<se_length; x++){
                    let nombre_sub = objSubescena[x].subesc || "";
                    let id_sub = objSubescena[x].id || 0;

                    const globos_html = `<li class="splide__slide item item-cat-${id_sub}">
                    <div class="titulo-col-cont" onclick="window.location.replace('${window.location.href}ampliar-publicacion-home.html?accion=ampliar&cat=${id_sub}')">
                    <div class="titulo-col random-p-${x}"><span class="span-titulo">${nombre_sub}</span></div>
                    </div>
                    </li>`
                    
                    $(".splide__list__home").append(globos_html)
                    $(".item-cat-"+id_sub).append(public_html)
                }
            }else{
                alertify.error("error con publics")
            }
            

            

        }    
        //hardcodeado (cada vez que se llame va a volver a crearse)
        let splide_test = new Splide( '.splide__home', {
                type     : 'slide',
                perPage: 2,
                autoWidth: true,
                pagination: false,
                autoHeight: true
        } ).mount();
    
    }
}

function showCantResult(length){
    var showResultados = document.querySelector(".show-result-num") || 0;
    showResultados.innerHTML = length; 
}

function toDataURL(src, callback, outputFormat) {
    var img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = function() {
       var canvas = document.createElement('CANVAS');
       var ctx = canvas.getContext('2d');
       var dataURL;
       canvas.height = this.naturalHeight;
       canvas.width = this.naturalWidth;
       ctx.drawImage(this, 0, 0);
       dataURL = canvas.toDataURL(outputFormat);
       callback(dataURL);
    };
    img.src = src;
    if (img.complete || img.complete === undefined) {
       img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
       img.src = src;
    }
}

function getMisProductos(data){
    //console.log(data)
    var sizeProductos = data.length || 0; 

    for(var i=0; i<sizeProductos; i++){
        let nombre_prod = data[i].titulo;
        let precio_prod = data[i].precio;
        let id_prod = data[i].id;
        let stock_prod = data[i].stock;
        let foto_prod = data[i].foto;
        let foto_src = `/productos_img/${foto_prod}.png`;
        const flex_listado = document.querySelector(".flex-container")
        
        let listadoProducto = 
        `<div class="flex-listado">
            <div class="overlay-public">
                <div class="text-overlay-prod">
                    <span onclick="eliminarProd('${id_prod}')" class="eliminar-producto text-overlay-link share-sm">
                        <a href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
                    </span>
                    <span class="text-overlay-link text-overlay-link-id_prod">
                        <a href="/editar-producto.html?id=${id_prod}&accion=editar"><i class="fas fa-edit"></i></a>
                    </span>
                </div>
            </div>
            <img onerror="this.src=\'/imagen_perfil/generica_prod.jpg\'" src="${foto_src}" alt="${foto_src}">
            <div class="prod-datos">
                <div class="nombre-prod">${nombre_prod}</div>
                <div class="precio-prod">$ ${precio_prod}</div>
            </div>
        </div>`;

        flex_listado.insertAdjacentHTML('beforeend', listadoProducto) 
    }
}

function eliminarProd(idParam) {  

    $.post('/app/producto.php', {accion: "eliminar", id: idParam})
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

}

function intObserver(dataPaging){
    //paginador infinito
    const options = {
        root: null,
        rootMargins: "0px",
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver(handleIntersect, options);
    const footer = document.querySelector("footer");
    const targets = document.querySelectorAll('.public-ampliar');
    if(footer !== null) observer.observe(footer)

    function handleIntersect(entries) {
        if (entries[0].isIntersecting) {
            //console.warn("intersect");
            //getDataPaging(dataPaging);
            //observer.disconnect();
        }
    }
}

function observer(){
    const targets = document.querySelectorAll('.public-ampliar');
    const lazyLoad = target => {
        const io = new IntersectionObserver((entries, observer) => {
            
            entries.forEach(function(i, idx, array){
                if (idx === array.length - 1){ 
                    /*console.log("Last callback call at index " + idx + " with value ",i ); */
                }
             });

        });
        io.observe(target)
    };
    targets.forEach(lazyLoad);
}


function navCats(){
    //navegacion de categorias
    //hago un array con las escenas posta
    //------ Despues cambiarlo con la estructura posta de ESCENA - SUBSECENA - ETC
    const escena_json = JSON.parse(escena);
    const escena_json_length = escena_json.length;
    
    let id_padres_array = []
    for(var i=0; i<escena_json_length; i++){
        let id_padre = escena_json[i].id_padre;
        let id = escena_json[i].id;
        let nombre = escena_json[i].nombre;
        let obj = {}
        
        if(id_padre==null){
            obj = {"id" : id,"nombre" : nombre}
            id_padres_array.push(obj); 
        }
    }

    //busco la cat que es gual a la que ya estamos, dependiendo del index que tenga en el nuevo array
    let cat_index = id_padres_array.findIndex(el => el.id === jsonData.cat);
    let cat_index_next = cat_index + 1;
    let cat_index_prev = cat_index - 1;
    
    if(cat_index_prev >= 0){
        let id_cat_prev = id_padres_array[cat_index_prev].id || 0;
        let name_cat_prev = id_padres_array[cat_index_prev].nombre || "";

        $(".prev-cat a").attr("href",`/ampliar-publicacion-home.html?accion=ampliar&cat=${id_cat_prev}`);
        $(".up_relleno_2_izq").html(name_cat_prev);
    }else{
        $(".prev-cat a").attr("href",`/ampliar-publicacion-home.html?accion=ampliar&cat=${jsonData.cat}`);
        $(".up_relleno_2_izq").html("");
    }

    let id_cat_next = id_padres_array[cat_index_next].id || 0;
    let name_cat_next = id_padres_array[cat_index_next].nombre || "";

    $(".next-cat a").attr("href",`/ampliar-publicacion-home.html?accion=ampliar&cat=${id_cat_next}`);
    $(".up_relleno_1_der").html(name_cat_next);

    

}

function getDataPaging(dataPaging) {

    let url_temp = dataPaging.url || "";
    let URL = "";
    document.body.classList.add("loading"); 
    

    if(dataPaging.hasOwnProperty("id")){
        //si tiene id entonces es un comentario
        let tipo_comentario = (url_temp=="paginador_comentarios_producto.php") ? "id_producto" : "id_publicacion";
        let id_comentario = dataPaging.id || 0;
        URL = `/app/${url_temp}?cant=${dataPaging.cantidad}&${tipo_comentario}=${id_comentario}`;
    }else{
        URL = `/app/${url_temp}?cant=${dataPaging.cantidad}`;
    }
    
    console.log(URL)
    fetch(URL)
    .then(response => response.json())
    .then(data => {
            //console.log(data)
            var cant = parseInt(data.length);
            dataPaging.cantidad = dataPaging.cantidad+cant;

            //dibujo listados
            switch (url_temp){
                case "paginador_home.php":
                    getPublicsHome(data);
                    break;
                case "paginador_ampliar-publicacion.php":
                    getPublicsAmpliar(data);
                    break;
                case "paginador_ampliar-publicacion-home.php":
                    getPublicsAmpliarHome(data);
                    posicionarPublic();
                    navCats();
                    break;
                case "paginador_mis-publicaciones.php":
                    getMisPublic(data);
                    break;
                case "paginador_mis-compras.php":
                    getMisCompras(data);
                    break;
                case "paginador_mis-ventas.php":
                    getMisVentas(data);
                    break;
                case "paginador_ampliar-producto.php":
                    getMisProductos(data);
                    break;
                case "paginador_notificaciones.php":
                    ampliarNotif(data);
                    break;
                case "paginador_comentarios.php":
                    getComentarios(data,url_temp);
                    break;
                case "paginador_comentarios_producto.php":
                    getComentarios(data,url_temp);
                    break;
                case "paginador_favorito.php":
                    getMisFavoritos(data);
                    break;
                default: alertify.error("Ha ocurrido un error con el servidor. Intente de nuevamente mas tarde")
            }

            showCantResult(data.length)
            document.body.classList.remove("loading"); 
        })
        .catch(function(error){
            alertify.error("Ha ocurrido un error con el servidor. Intente de nuevamente mas tarde")
        })
        
}


function eliminarProdAmpliarCarrito(id_carrito,id_publicacion,id_prod){

    const URL = "/app/carrito.php"

    let dataEliminar = new FormData();
    dataEliminar.append("accion","alta");
    dataEliminar.append("id",id_prod);
    dataEliminar.append("cantidad","0");
    dataEliminar.append("id_carrito",id_carrito);
    dataEliminar.append("id_publicacion",id_publicacion);
    
    fetch(URL, {
        method: 'POST',
        body: dataEliminar,
    }).then(res => res.json())
    .then(response => {
        window.location.replace("/carritos.html")
    })
    .catch(error => console.error('Error:', error))

}

function eliminarCarrito(id_carrito,id_publicacion,id_prod){

    const URL = "/app/carrito.php"

    let dataEliminar = new FormData();
    dataEliminar.append("accion","eliminarCarrito");
    dataEliminar.append("id_carrito",id_carrito);
    
    fetch(URL, {
        method: 'POST',
        body: dataEliminar,
    }).then(res => res.json())
    .then(response => {
        window.location.replace("/carritos.html")
    })
    .catch(error => console.error('Error:', error))

}

function sendComentario(idParam,indexParam,desde){

    const URL = (desde == "prod") ? '/app/comentarioproducto.php' : '/app/comentario.php';
    let pop = (desde == "prod") ? "producto" : "publicacion";
    let val = document.querySelector("#comentario-"+indexParam).value;


    let dataComentario = new FormData();
    dataComentario.append("accion","alta");
    dataComentario.append(pop,idParam);
    dataComentario.append("comentario",val);

    let appendeo = document.querySelector(".commentbox-list-container-"+idParam);
    let nombre_usuario = jsonData.nombre;
    
    fetch(URL, {
        method: 'POST',
        body: dataComentario,
    }).then(res => res.json())
    .then(response => {

        let resp_msj = response.mensaje;
        let resp_status = response.status; 

        if(resp_status == "REDIRECT"){
            window.location.replace(resp_msj);														
        }else if(resp_status == 'OK'){
            console.log("append",appendeo)
            var content_html =
            `<div class="commentbox-list media commentbox-id">
               <span class="comment-name">${nombre_usuario}</span>
               <span class="comment-text">${val}</span>
            </div>`;
        
            appendeo.insertAdjacentHTML("afterbegin",content_html);
        }else{
            alertify.error(resp_msj);
        }
    })
    .catch(error => console.error('Error:', error))
    
    /*$.ajax({
        url: '/app/comentario.php',
        data: dataComentario,
        type: 'POST',
        processData: false,
        contentType: false,
        //dataType: "json",
        async: false,
        success: function( data, textStatus, jQxhr ){
            var dataJ = JSON.parse(data).status;
            var dataM = JSON.parse(data).mensaje;
            if (dataJ == "REDIRECT"){
                console.log("REDIRECT-->"+dataM);
                window.location.replace(dataM);														
            }else if(dataJ == 'OK'){
                //window.location.replace("/test-cobrar-compra.html?id="+id_carrito);
                console.log(dataJ+"--"+dataM);
                var content_html =
                `<div class="commentbox-list media commentbox-id">
                   <span class="comment-name">${nombre_usuario}</span>
                   <span class="comment-text">${val}</span>
                </div>`;
            
                appendeo.insertAdjacentHTML("beforeend",content_html);
            }else{
                //window.location.replace("/ampliar-carrito.html");
                //alertify.error(dataJ+"--"+dataM);
                console.log(dataJ+"--"+dataM);
            }
        },
        error: function( data ){
            console.log(data)
            alertify.error("error->"+data.status);
        }
    });
    return false;*/
}

function dibujarCarousel(id_prod,foto_obj){

    let split = foto_obj.split(',');
    let carousel_inner = document.querySelector(".carousel-inner-"+id_prod)
    let carousel_indicators = document.querySelector(".carousel-indicators-"+id_prod)

    for(let i = 0; i <split.length; i++){     
        
        let foto = split[i]; 
        
        let carousel_item_html = 
        `<div class="carousel-item carousel-item-${id_prod}"> 
            <img onerror="this.src='/imagen_perfil/generica_prod.jpg'" src="/productos_img/${foto}.png" alt="carousel-item"> 
        </div>`;

        let carousel_thumb_html =
        `<li class="list-inline-item list-inline-item-${id_prod}"> 
            <a id="carousel-selector-${i}" data-slide-to="${i}" data-target="#custCarousel-${id_prod}">
                <img onerror="this.src='/imagen_perfil/generica_prod.jpg'" src="/productos_img/${foto}.png" class="list-inline-item">
            </a> 
        </li>`;

        
        carousel_inner.insertAdjacentHTML("beforeend",carousel_item_html)
        carousel_indicators.insertAdjacentHTML("beforeend",carousel_thumb_html)

        //a la primera imagen solamente le agrego "active"
        if(i==0){
            document.querySelector(".carousel-item-"+id_prod).classList.add("active")
            document.querySelector(".list-inline-item-"+id_prod).classList.add("active")
        }
    }
    
}