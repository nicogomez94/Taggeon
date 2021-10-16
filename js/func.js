document.addEventListener("DOMContentLoaded", function() {
    
    //activar notifs
    ampliarNotif();

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
    $("img").on("error", function(){
        $(this).attr('src', '../../imagen_perfil/generica.png');
    });

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
    $(function(){
        $("#drop").on("click", function(e) {
            e.stopPropagation();
            e.preventDefault();

            $("#dropdown-user-menu").show();
            
        });
        $(document).click(function () {
            $("#dropdown-user-menu").hide();
        });
    });
    //notifs
    $(".notifs-button").click(function(){
        $(".notifs-button-ampliar").toggle();
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

    /********SUBIR IMAGEN*******/
    $("#subir-foto-perfil").on('submit', function() {

        if(['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].indexOf($("#file-upload").get(0).files[0].type) == -1) {
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
                        //alert (data.mensaje);
                        $(".btn-cambiar-foto-perfil").hide();
                        window.location.replace("/editar-usuario.html");
                    }else{
                        alert(data.mensaje);
                    }

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                    alert(msj);         
               }
            });
        };
        reader.readAsDataURL($("#file-upload").get(0).files[0]);    
        return false;
    });

    /**modal casero editar para foto*/
    $('#edit-btn').click(function(e) {
        e.preventDefault();
        console.log("overlay")
        $(".overlay").show();
    
        $('#cerrar-light').click(function() {
          $('.overlay').css("display", "none");
        });
    });

    

    /**modal casero editar para ediat foto con pins/productos*/
    $('#anadir-productos-btn').click(function(e) {
        e.preventDefault();

        // var src_output = $("#output-imgpins").attr("src");
      /*  $("#imagen-productos-pin").attr("src",src_output);*/
        //$("#map").css("pointer-events","all");
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

    });

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
    console.log(pin_object)

    formData.append("foto_base64",url_imagen_64);
    formData.append("data_pines",pin_object_str);
    formData.delete("publicacion_foto");

    //alert(formData.get("data_pines"))

    $.ajax({
        url: '/app/publicacion.php',
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
                //$("body").addClass("loading");
                window.location.replace("/mis-publicaciones.html");
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

$('#editar-publicacion-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    var formData = new FormData($(this)[0]);
    
    //appendeo la imagen sacada del map. ya la inicio el onchange
    var url_imagen_64 = $("#map").css("background-image").split("url(")[1];
    var sc_url_imagen_64 = url_imagen_64.replace(/['"]+/g, '');
    var sc_url_imagen_642 = sc_url_imagen_64.split(")")[0];//villa mal
    formData.append("foto_base64",sc_url_imagen_642);
    formData.delete("publicacion_foto");//villa tambien

    $.ajax({
        url: '/app/publicacion.php',
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
                $("body").addClass("loading"); 
                window.location.replace("/mis-publicaciones.html");
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
//AÑADIR AL CARRITO
$(".modal").on("click", ".btfn-carrito", function(){
    
    var id_value = $(this).parent().parent().find(".id_prod_carrito").val();
    var cantidad_value = $(this).parent().parent().find(".cantidad_value").val();
    var id_prod = $(this).data("idprod");
    var id_publicacion = $(this).data("idpublic");

    //console.log(id_publicacion)
    var dataCarr = new FormData();
    dataCarr.append("accion","alta");
    dataCarr.append("id",id_value);
    dataCarr.append("cantidad",cantidad_value);
    dataCarr.append("id_prod",id_prod);
    dataCarr.append("id_publicacion",id_publicacion);
    
    $.ajax({
        url: '/app/carrito.php',
        data: dataCarr,
        type: 'POST',
        processData: false,
        contentType: false,
        //dataType: "json",
        //async: false,
        success: function( data, textStatus, jQxhr ){
            var dataJ = JSON.parse(data).status;
            var dataM = JSON.parse(data).mensaje;
           if (dataJ == 'REDIRECT'){
               console.log("REDIRECT-->"+dataM);
               window.location.replace(dataM);														
            }else if(dataJ == 'OK'){
                console.log(dataJ);
                console.log(dataM);
                window.location.replace("/ampliar-carrito.html?id_carrito="+dataM);	
            }else{
                console.log("ELSE-->"+dataJ+"/"+dataM);
                //window.location.replace("/ampliar-carrito.html");
            }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            console.log(dat);
        }
    });
    return false;
    
});



///crear orden de compra carrito
/*$(".boton-checkout-carrito").click(function(){
    
    var id_carrito = jsonData.carrito[0].id_carrito || 0;
    var cantidad = jsonData.carrito[0].cantidad || 0;

    var dataCheckout = new FormData();
    dataCheckout.append("accion","finalizar");
    dataCheckout.append("id_carrito",id_carrito);
    dataCheckout.append("cantidad",cantidad);

    $.ajax({
        url: '/app/carrito.php',
        data: dataCheckout,
        type: 'POST',
        processData: false,
        contentType: false,
        success: function( data, textStatus, jQxhr ){
            var dataJ = JSON.parse(data).status;
            var dataM = JSON.parse(data).mensaje;
           if (dataJ == 'REDIRECT'){
              console.log("REDIRECT-->"+dataM);
              window.location.replace(dataM);														
           }else if(dataJ == 'OK'){
              console.log("OK-->"+dataJ+"/"+dataM);
              window.location.replace("/ampliar-checkout.html");
           }else{
              console.log("ELSE-->"+dataJ+"/"+dataM);
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert(data);
        }
    });
    return false;
});*/


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
               alert(data)
              console.log("REDIRECT-->"+dataM);
              console.log(data)
              //window.location.replace(dataM);														
           }else if(dataJ == 'OK'){
               console.log(data)
              alert(data)
               window.location.replace("/cobrar-compra.html?id="+id_carrito);
           }else{
               alert(data)
            console.log(data)
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert(data);
        }
    });
    return false;
});


///eliminar de carrito


$("#cropear-btn").click(function(){
    $(this).hide();
    $(".toggle-aspect-ratio").hide();

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



///submit comentario_public
$("#comentario_public_send").click(function(){
    console.log("test")
    var dataComentario = new FormData($(this)[0]);
    dataComentario.append("accion","alta");

    var comentario = dataComentario.get("comentario");
    
    var appendeo = $(this).parent().parent().parent().find(".commentbox-list-container");

    var content_html =
    `<div class="commentbox-list media commentbox-id">
       <span class="comment-name">nicolasgomez94</span>//hard
       <span class="comment-text">${comentario}</span>
    </div>`;

    $(appendeo).append(content_html);

    /*var img_perfil = $(".img-perfil-usuario-drop").attr("src");
    $(".commentbox-user-img").attr("src", img_perfil);*/

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
                //window.location.replace(dataM);														
            }else if(dataJ == 'OK'){
                //window.location.replace("/test-cobrar-compra.html?id="+id_carrito);
                console.log(dataJ+"--"+dataM);
            }else{
                //window.location.replace("/ampliar-carrito.html");
                //alert(dataJ+"--"+dataM);
                console.log(dataJ+"--"+dataM);
            }
        },
        error: function( data ){
            console.log(data)
            alert("error->"+data.status);
        }
    });
    return false;*/
});

///submit comentario_prod
$(".comentario_prod").submit(function(){

    var dataComentario = new FormData($(this)[0]);
    dataComentario.append("accion","alta");

    $.ajax({
        url: '/app/comentarioproducto.php',
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
              //window.location.replace(dataM);														
           }else if(dataJ == 'OK'){
              //window.location.replace("/test-cobrar-compra.html?id="+id_carrito);
              alert(dataJ)
           }else{
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert(data);
        }
    });
    return false;
});


/*func para que al subir un puin no se vaya de contexto
#container {
    border: 1px solid #ccc;
    height:300px;
    width:400px;
}
#el {
    background-color:#ccc;
    height:200px;
    left: 800px;
    position:absolute;
    width:200px;
}
#el.over {
    background-color: #c00;
}


    <div id="el">ELEMENT</div>


*/

$('.seguidores-label').click(function(e) {
    e.preventDefault();

    $(".overlay-seguidores").show();

    $('#cerrar-light').click(function() {
      $('.overlay-seguidores').css("display", "none");
    });
});
$('.seguidos-label').click(function(e) {
    e.preventDefault();

    $(".overlay-seguidos").show();

    $('#cerrar-light').click(function() {
      $('.overlay-seguidos').css("display", "none");
    });
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

//test
//$("#modalFirstLogin").modal('show')

//form intereses
$("#form_intereses").submit(function(e){

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
            alert(data);
            console.log(data,response)
        }
    });
    return false;
    //data.append("")

    /*fetch(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers:{
            'Content-Type': 'application/json'
        }
    }).then(res => res.json())
    .then(response => console.log('Success:', JSON.stringify(response)))
    .catch(error => console.log('Error:', error));*/


});



/***fin document.ready***//***fin document.ready***/
/***fin document.ready***//***fin document.ready***/
/***fin document.ready***//***fin document.ready***/


});


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
                `<img src="${img_perfil}" alt="profile_pic">`
                );
        }
    }
            
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
            alert("Ha ocurrido un error. Recargue la p&aacute;gina")
        }
        
    }
}


function cargarImgPines(event){
    
    var tipoFile = event.target.files[0].type || "";
    console.log(event)

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
            $("#anadir-productos-btn").show();
            $("#anadir-productos-btn").addClass("disabled");
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

                    if(image.className == 'cropper-hidden'){
                        image.cropper.destroy();
                    }

                });
            });

            //toggle de aspect ratio
            $("#modal-cropper").on('click', '.l-radio', function (e) {
                e.stopPropagation();
                options.aspectRatio = this.dataset.aspect; 
                document.getElementById("aspect-ratio-input").value = options.aspectRatio

                //destruyo el viejo y creo uno nuevo
                image.cropper.destroy();
                var cropper2 = new Cropper(image,options);

                button.classList.remove("disabled");
                
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

                    //activo botones para taguear
                    anadir.classList.remove("disabled");
                    
                };
            });
            


        };
        reader.readAsDataURL(event.target.files[0]);

    }else{
        alert("archivo erroneo")
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
                "perfil": jsonData.perfil
            }

            $.ajax({
                url: '/app/producto.php',
                type: 'post',
                data: data_json,
                dataType: 'json',
                success:function(response){
                console.log(response)
                var resp_len = response.mensaje.length;
                var splide_list = $(".splide__prod_public .splide__list");
                
                splide_list.empty();

                //if(jsonData.perfil == "Picker"){
                    for(var i=0; i<resp_len; i++){

                        var id_prod = response.mensaje[i].id;
                        var nombre_prod = response.mensaje[i].titulo;
                        var foto_prod = response.mensaje[i].foto;
                        var marca = response.mensaje[i].marca;
                        var foto_src = `/productos_img/${foto_prod}.png` || 0;//viene siempre png?

                        /*<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-${i}" src="${img_base_prod}"></li>';*/

                        var html = `<li title="Por: ${marca}" class="splide__slide splide__slide__img ${id_prod}">
                                    <img data-toggle="modal" data-target="#modal-producto-${i}" src="${foto_src}">
                                    <div class="nombre-producto ${id_prod} nombre-producto-${i}">${nombre_prod}</div></li></div>`;
                        // var html = <option class="nombre-producto ${id_prod} nombre-producto-${i}">nombre_prod+</option>'
                        splide_list.append(html);

                    }
                    new Splide( '.splide__prod_public', {
                            perPage: 4,
                            rewind : true,
                            pagination: false
                        } ).mount();

                
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
                    alert("ERROR::"+response)
                }
            });
        }
}




function favoritos(id_publicacion,accion){

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

 function likes(id_publicacion,accion){

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

function seguidores(id_publicacion,idPublicadorParam,accionParam){

    var data = new FormData();
    data.append("accion",accionParam);
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
            
                            
                            toggleFav(favorito,id_public,"buscador",fav_accion);
                            
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
                alert("ERROR::"+response)
                console.log(response)
                console.log(data)
            }
        });
    }else{
        window.location.replace("/");
    }
}

function ampliarNotif(){
    //var jsonData = jsonData || [];
    //console.log(jsonData)
    if(typeof jsonData !== "undefined"){
        if(typeof jsonData.usuario !== "undefined"){
            var notifs = jsonData.notificaciones || [];
            var sizeNotifs = notifs.length || 0;

            if(sizeNotifs>0){
                for(var i=0; i<sizeNotifs; i++){
                    
                    var compracompra = jsonData.notificaciones[i].compracompra || 0;
                    var json_notif = jsonData.notificaciones[i].json_notificacion || "";
                    var json_notif_p = JSON.parse(json_notif) || [];
                    var tipo_notif = jsonData.notificaciones[i].tipo_notificacion || "";
                    var id = jsonData.notificaciones[i].id || 0;
                    var json_notif_p_l = Object.keys(json_notif_p).length || 0;
                    
                    //console.log(json_notif_p)
        
                    var foto = json_notif_p.foto || 0;
                    var id_jn = json_notif_p.id || 0;
                    var id_publicacion_categoria = json_notif_p.id_publicacion_categoria || "";
                    var pid = json_notif_p.pid || "";
                    var publicacion_descripcion = json_notif_p.publicacion_descripcion || "";
                    var nombre = json_notif_p.nombre || "";
                    var nombre_producto = json_notif_p.nombre_producto || "";
                    var publicacion_nombre = json_notif_p.publicacion_nombre || "";
                    var usuario_alta = json_notif_p.usuario_alta || 0;
                    var foto_prod = json_notif_p.foto_id;
                    var comentario = json_notif_p.comentario;
                    var foto_src = `/productos_img/${foto_prod}.png`;
        
                    //aparece el contador de notifs con nro
                    $(".count-notif").show();
                    $(".count-notif").text(sizeNotifs)
                    
                    var html_notif = 
                                `<div class="media notif-id-${id}">
                                    <i class="notif-icon mr-3 fas fa-heart heart-notif"></i>
                                    <div class="media-body"></div>
                                    <i onclick="eliminarNotif('${id}')" class="eliminar-notif fas fa-times"></i>
                                </div>`;
        
                        $(".notifs-button-ampliar").append(html_notif)

                    if(tipo_notif == "favorito"){
                        var html_favorito = `<div>${nombre} a&ntilde;adi&oacute; tu publicaci&oacute;n "${publicacion_nombre}" como favorita</div>`;
                        $(".notif-id-"+id+" .media-body").append(html_favorito)

                    }else if(tipo_notif == "seguidores"){
                        var html_seg = `<div>${nombre} te esta siguiendo</div>`;
                        $(".notif-id-"+id+" .media-body").append(html_seg)

                    }else if(tipo_notif == "vendedor"){
                        var html_vendedor = `<div><a href="/mis-ventas.html">Vendiste ${nombre_producto}!</a></div>`;
                        var html_foto_prod = `<img class="mr-3 img-notifs" src="${foto_src}" alt="img_notif">`;

                        $(".notif-id-"+id).prepend(html_foto_prod);
                        $(".notif-id-"+id+">.notif-icon").remove();
                        $(".notif-id-"+id+">.media-body").html(html_vendedor);

                    }else if(tipo_notif == "comentario"){
                        var html_comentario = `<div><a href="/ampliar-publicacion-home.html?id=${id_jn}&accion=ampliar&cat=${id_publicacion_categoria}">nombre} coment&oacute; "${comentario}" en tu public.: "${publicacion_nombre}"</a></div>`;
                        //var html_foto_prod = <img class="mr-3 img-notifs" src="${foto_src}" alt="img_notif">';

                        $(".notif-id-"+id+" .media-body").append(html_comentario)
                        
                    }
                }

            }else{
                var no_notif = `<div class="no_notif"><i class="fas fa-flag"></i>&nbsp;&nbsp;No hay notificaciones por el momento.</div>`
                $(".notifs-button-ampliar").html(no_notif)
            }
        }
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

function mostrarSeguidores(){

    var seguidores = jsonData.seguidores || [];
    var sizeSeguidores = jsonData.seguidores.length;
    var seguidos = jsonData.seguidos || [];
    var sizeSeguidos = jsonData.seguidos.length || 0;

    //perfil
    $("#following_count").html(sizeSeguidores);
    $("#follower_count").html(sizeSeguidos);

    //popup
    $(".count-seguidos-num").html(sizeSeguidos);
    $(".seguidores-count").html(sizeSeguidores);

    if(sizeSeguidores>0){
        for(var i=0; i<sizeSeguidores; i++){
            
            var apellido = jsonData.seguidores[i].apellido || "";
            var email = jsonData.seguidores[i].email || "";
            var idUsuario = jsonData.seguidores[i].idUsuario || 0;
            var nombre = jsonData.seguidores[i].nombre || "";

            var seguidores_html = 
            `<div class="media seguidor">
                <img class="mr-3 img-seg" src="unknown" alt="Generic placeholder image">
                <div class="media-body">
                    <h5 class="mt-0">${nombre}</h5>
                    <span>${email}</span>'
                </div>
            </div>`;
            
            $(".container-seguidores").append(seguidores_html);
        }
    }else{
        var sin_seg = "<div>Por el momento, nadie te esta siguiendo</div>";
        $(".container-seguidores").html(sin_seg);
    }

    if(sizeSeguidos>0){
        for(var i=0; i<sizeSeguidos; i++){

            var apellido = jsonData.seguidos[i].apellido || "";
            var email = jsonData.seguidos[i].email || "";
            var idUsuario = jsonData.seguidos[i].idUsuario || 0;
            var nombre = jsonData.seguidos[i].nombre || "";

            var seguidos_html = 
            `<div class="media seguido">
                <img class="mr-3 img-seg" src="unknown" alt="Generic placeholder image">
                <div class="media-body">
                    <h5 class="mt-0">${nombre}</h5>
                    <span>${email}</span>'
                </div>
            </div>`;
            
            $(".container-seguidos").append(seguidos_html);
        }
    }else{
        var sin_seg = "<div>Por el momento, no sigues a nadie</div>";
        $(".container-seguidos").html(sin_seg);
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
                alert("ERROR::"+response)
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

                if(target_length > 1){
                    itemsNext.each(function(){
                        $(this).empty()
                        $(this).removeClass("showCat");
                    });
                }
                
                for(var i=0; i<subcats_length; i++) {
                    var cat_id = subcats[i].id;
                    var cat_nombre = subcats[i].nombre;
     
                    var cat_select_html = 
                    `<option value="${cat_id}">${cat_nombre}</option>`;
     
                    targetHtml.append(cat_select_html)
                    targetHtml.addClass("showCat");
                }

            }else{
                console.log("else")
                console.log(response)
                console.log(data)
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alert(msj);
        }
    });
    return false;
}

function getSubEscena(valueParam,source,target){

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
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){

                var subEscena = data.mensaje || [];
                var subEscena_length = data.mensaje.length || 0;
                var targetHtml = $(target);
                var esc_ind = $("#esc_ind");
                var target_length = targetHtml.find("option").length;
                var itemsNext = $(source).nextAll().not(esc_ind);
                
                //si ya hay options en el select, borra esos 
                if(target_length > 1){
                    itemsNext.each(function(){
                        $(this).empty()
                        $(this).removeClass("showCat");
                    });
                }
                
                for(var i=0; i<subEscena_length; i++) {
                    var cat_id = subEscena[i].id;
                    var cat_nombre = subEscena[i].nombre;

                    var cat_select_html = 
                    `<option value="${cat_id}">${cat_nombre}</option>`;
     
                    targetHtml.append(cat_select_html)
                    targetHtml.addClass("showCat");
                }

            }else{
                console.log("else")
                console.log(response)
                console.log(data)
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alert(msj);
        }
    });
    return false;
}

function getSubEscenaTest(valueParam,source,target){

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
            if (data.status == 'REDIRECT'){
                window.location.replace(data.mensaje);														
            }else if(data.status == 'OK'){

                var subEscena = data.mensaje || [];
                var subEscena_length = data.mensaje.length || 0;

                console.log("subEscena",subEscena)
                console.log("source",source)
                console.log("target",target)
                
                for(var i=0; i<subEscena_length; i++) {
                    var cat_id = subEscena[i].id;
                    var cat_nombre = subEscena[i].nombre;
                    var targetHtml = $('<select name="subescena1" id="esc_arq" onchange="getSubEscena(this.value,\'#esc_arq\',\'#esc_arq2\')">');
     
                    var cat_select_html = 
                    `<option value="${cat_id}">${cat_nombre}</option>`;
     
                    targetHtml.append(cat_select_html)
                    targetHtml.addClass("showCat");
                }

            }else{
                console.log("else")
                console.log(response)
                console.log(data)
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
            alert(msj);
        }
    });
    return false;
}

function getEscenas(valueParam){

    if(valueParam == "Arquitectura"){
        var arq = $("#esc_arq");
        arq.addClass("showCat");
        $("#esc_ind").removeClass("showCat")

        var escena_parse = JSON.parse(escena)
        var escena_length = escena_parse.length || 0;
        
        for(var i=0; i<escena_length; i++) {
            var id_padre = escena_parse[i].id_padre;

            if(id_padre == null){
                var cat_id = escena_parse[i].id;
                var cat_nombre = escena_parse[i].nombre;
        
                var cat_select_html = 
                `<option value="${cat_id}">${cat_nombre}</option>`;
        
                arq.append(cat_select_html)

            }
        }

    }else if(valueParam == "Indumentaria"){

        var ind = $("#esc_ind");
        ind.addClass("showCat");
        $("#esc_arq").removeClass("showCat")

        var escena2_parse = JSON.parse(escena2)
        var escena_length = escena2_parse.length || 0;
        
        for(var i=0; i<escena_length; i++) {
            var id_padre2 = escena2_parse[i].id_padre;
            
            if(id_padre2 == null){
                var cat_id = escena2_parse[i].id;
                var cat_nombre = escena2_parse[i].nombre;
        
                var cat_select_html = 
                `<option value="${cat_id}">${cat_nombre}</option>`;
        
                ind.append(cat_select_html)
            }
        }

    }

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

function cerrarOverlay(clase){
    var el = document.querySelector("."+clase);
    el.style.display = "none";
}

function posicionarPublic(){
    var WinLocSplit = window.location.href.split("=")[1].split("&")[0] || "";
    var ancla_html = $("#ancla-desde-home-"+WinLocSplit);
    if(ancla_html.length>0){
        var public_pos = ancla_html.offset().top - 80;
        $('html,body').scrollTop(public_pos)
    }
}

function fetchIdCarrito(id_public,id_prod){
    const URL = "/app/carrito.php"

    let dataCarr = new FormData();
    dataCarr.append("accion","alta");
    dataCarr.append("id",id_prod);
    dataCarr.append("id_publicacion",id_public);
    dataCarr.append("cantidad","1");//hard

    fetch(URL, {
        method: 'POST',
        body: dataCarr,
    }).then(res => res.json())
    .then(response => {
        let id_carrito = response.mensaje;
        window.location.replace("/carritos.html?id_carrito="+id_carrito)
    })
    .catch(error => console.error('Error:', error))

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
            alert("ERROR")
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
            let full_url = `/ampliar-publicacion.html?id=${id_public}&accion=ampliar&cat=${id_public_cat}`
            //let imagen_public_html = document.querySelector(".imagen-public-"+imagen_id);

            var foto_src = `/publicaciones_img/${imagen_id}.png` || 0;        

            var public_html2 =
                `<div class="grid-item">
                    <div class="content-col-div content-col-div-${id_public} cat-${id_public_cat}">
                        <div class="overlay-public">
                            <a class="link-ampliar-home" href="${full_url}"></a>
                            <div class="public-title-home">${nombre_public}</div>
                            <div class="text-overlay">
                                <span class="text-overlay-link"><a href="/editar-publicacion.html?id=${id_public}&accion=editar"><i title="Editar Publicaci&oacute;n" class="fas fa-edit"></i></a></span>&nbsp;
                                <span class="text-overlay-link eliminar-public" data-title="${id_public}"><a href="/app/publicacion.php?id=${id_public}&accion=eliminar"><i title="Eliminar Publicaci&oacute;n" class="fas fa-trash-alt"></i></a></span>
                            </div>
                        </div>
                        <img src="${foto_src}" alt="img-${imagen_id}">
                    </div>
                </div>`;

            grid.insertAdjacentHTML("beforeend",public_html2)
            //imagen_public_html.attr("src", foto_src);

        }
        
    }else{
        //var html_sin_public = <p style="color:gray; font-style: italic; text-align: center">No hay Publicaciones subidas.</p>';
        //grid.insertAdjacentHTML(html_sin_public);
    }
}

function getMisCompras(data){
    const sizeCompras = data.length;
    const flex_listado = document.querySelector(".flex-listado")

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
            `<div>
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
                
                flex_listado.insertAdjacentHTML('beforeend', compras_html) 
                

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
    const flex_listado = document.querySelector(".flex-listado")

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
            `<div>
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
               
            flex_listado.insertAdjacentHTML('beforeend', ventas_html) 
        }
    }else{
        document.querySelector(".inner-ventas").innerHTML = `<hr class="mt-5"><h3 class="text-center"><i> No tienes ninguna venta realizada<i></h3>`
    }

}

function getCommentsPublic(comentarios_obj){
    //recorro comentarios en la public
    for(var y=0; y<comentarios_obj.length; y++){
        if(comentarios_obj.length>0){
            var comentario = comentarios_obj[y].comentario || "";
            var eliminar = comentarios_obj[y].eliminar || "";
            var fecha_alta = comentarios_obj[y].fecha_alta || "";
            var fecha_update = comentarios_obj[y].fecha_update || "";
            var id = comentarios_obj[y].id || 0;
            var id_publicacion = comentarios_obj[y].id_publicacion || 0;
            var usuario_alta = comentarios_obj[y].usuario_alta || "";
            var usuario_editar = comentarios_obj[y].usuario_editar || "";
            
            var comentario_html = 
            `<div class="commentbox-list media commentbox-id-${y}">
               <span class="comment-name">nicolasgomez94</span>
               <span class="comment-text">${comentario}</span>
            </div>`;
            
            $(".commentbox-list-container-"+id_publicacion).append(comentario_html);

        }else{
            var comentario_html2 = "<p>No hay comentarios</p>"
            
            $(".commentbox-list-container-"+id_publicacion).append(comentario_html2)
        }

    }

}

function getCommentsProd(comentarios_obj){
    //recorro comentarios en la public
    for(var y=0; y<comentarios_obj.length; y++){
        if(comentarios_obj.length>0){
            var comentario = comentarios_obj[y].comentario || "";
            var eliminar = comentarios_obj[y].eliminar || "";
            var fecha_alta = comentarios_obj[y].fecha_alta || "";
            var fecha_update = comentarios_obj[y].fecha_update || "";
            var id = comentarios_obj[y].id || 0;
            var id_publicacion = comentarios_obj[y].id_publicacion || 0;
            var usuario_alta = comentarios_obj[y].usuario_alta || "";
            var usuario_editar = comentarios_obj[y].usuario_editar || "";
            
            var comentario_html = 
            `<div class="commentbox-list media commentbox-id-${y}">
               <span class="comment-name">nicolasgomez94</span>
               <span class="comment-text">${comentario}</span>
            </div>`;
            
            $(".commentbox-list-container-"+id_publicacion).append(comentario_html);

        }else{
            var comentario_html2 = "<p>No hay comentarios</p>"
            
            $(".commentbox-list-container-"+id_publicacion).append(comentario_html2)
        }

    }

}

function toggleFav(favorito,id_public,desde,fav_accion){

    let appendeo = (desde=="ampliar") ? $(".social-public-"+id_public) : $(".text-overlay-link-"+id_public);
    
    if (favorito==null || favorito == 0) {
        fav_accion="alta";
        var fav_html = `<span><i class="fas fa-star" onclick="favoritos(${id_public},'${fav_accion}');$(this).toggleClass('fav-eliminar')"></i></span>`
        appendeo.prepend(fav_html);
    }else{
        fav_accion="eliminar";
        var fav_html = `<span><i class="fas fa-star fav-eliminar" onclick="favoritos(${id_public},'${fav_accion}');$(this).toggleClass('fav-eliminar')"></span>`
        appendeo.prepend(fav_html);
    }

    return fav_accion;
}

function toggleFollow(idPublicadorSeguido,id_publicador,id_public){
    
    if(idPublicadorSeguido==id_publicador) {
        seg_accion="eliminar";
        var seg_html = `<span class="follow_public"><i class="fas fa-user-plus seg-eliminar" onclick="seguidores(${id_public},'${id_publicador}','${seg_accion}');$(this).toggleClass('seg-eliminar')"></span>`
        $(".header-public-"+id_public).append(seg_html);
    }else{
        seg_accion="alta";
        var seg_html = `<span class="follow_public"><i class="fas fa-user-plus" onclick="seguidores(${id_public},'${id_publicador}','${seg_accion}');$(this).toggleClass('seg-eliminar')"></i></span>`
        $(".header-public-"+id_public).append(seg_html);
    }

    return seg_accion;
}

function toggleLike(like,id_public,desde,like_accion){
    
    let appendeo = (desde=="ampliar") ? $(".social-public-"+id_public) : $(".text-overlay-link-"+id_public);

    if (like==null || like == 0) {
        like_accion="alta";
        var like_html = `<span><i class="fas fa-heart" onclick="likes(${id_public},'${like_accion}');$(this).toggleClass('like-eliminar')"></i></span>`
        appendeo.prepend(like_html);
    }else{
        like_accion="eliminar";
        var like_html = `<span><i class="fas fa-heart like-eliminar" onclick="likes(${id_public},'${like_accion}');$(this).toggleClass('like-eliminar')"></span>`
        appendeo.prepend(like_html);
    }

    return like_accion;
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
            let seguidor = "";
            let imagen_id = data[i].foto || 0;
            let producto = data[i].pid || 0;
            let cat_ampliar_home = jsonData.cat || 0;
            let arrCat = escena_json || 0;
            let foto_src = `/publicaciones_img/${imagen_id}.png` || 0;//viene siempre png?
            let img_publicador = `/imagen_perfil/${foto_perfil}.png` || 0;//viene siempre png?
            let winLoc = window.location.pathname || "";
            let id_usuario = "1";//hard
            let favorito = data[i].favorito || 0;
            let fav_accion = "";
            let seg_accion = "";
            let like_accion = "";
            let like="";
            let seguidos = jsonData.seguidos || [];
            let idPublicadorSearch = seguidos.find(o => o.idUsuario === id_publicador) || "";
            let idPublicadorSeguido = idPublicadorSearch.idUsuario;
            let comentarios_obj = data[i].comentarios || [];
            let full_url = window.location.href;      

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
                                        <span class="share-sm" onclick="pathShareHome('${full_url}')"><i class="fas fa-paper-plane"></i></span>
                                        <span><i class="fas fa-star"></i></span>
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
                                                <button onclick="sendComentarioPublic('${id_public}',$(this),'${i}')" value="enviar" class="btn">Enviar</button>
                                            </div>
                                        </div>
                                  <div class="commentbox-list-container commentbox-list-container-${id_public}"></div>
                               </div>
                            </div>`;
                            
                document.querySelector(".insert-public").insertAdjacentHTML("beforeend",html_public);
                document.querySelector(".title-public-"+i).innerHTML = publicador;
                
                getPublicTags(id_public,producto,i);
                getCommentsPublic(comentarios_obj);
             
            } 
            
            //imgperfil comentarios
            var img_perfil = $(".img-perfil-usuario-drop").attr("src");
            $(".commentbox-user-img").attr("src", img_perfil);
            
            /**/
            toggleFav(favorito,id_public,"ampliar",fav_accion);
            toggleLike(like,id_public,"ampliar",fav_accion);
            toggleFollow(idPublicadorSeguido,id_publicador,id_public)
            /**/
            
        }else{
            alert("no hay publicaciones")
        }
    }//fin for

    observer()
    posicionarPublic();

}


function getPublicTags(id_public,tags,index){

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
        
        let tag_html = `<div href="ancla-${index}" onclick="getSplideProdPublic(${id_public});this.removeAttribute('onclick')" class="tagg tagg-${id_public}" style="top:${ycoord}%; left: ${xcoord}%">
        <span><img src="../../plugins/dropPin-master/dropPin/dot-circle-solid.svg"></span></div>`;
        document.querySelector(`.tag-container-${index}`).insertAdjacentHTML("beforeend",tag_html);
        

        //click en tag ANIMACION
        $(".bodyimg-public-container-"+index).on("click", ".tagg", function(e){
            e.stopPropagation();
            e.preventDefault();
            
            let prod_public = $(this).parent().parent().parent().find(".productos-public");
            prod_public.toggle(100);
                prod_public.toggleClass("prods-abierto");
                
                if(prod_public.hasClass("prods-abierto")){
                    $('html,body').animate({
                        scrollTop: prod_public.offset().top - 130
                    }, 0)
                }
        });
            
    }
}

function getPublicsHome(data){
    var sizePublic = data.length;
        
    if(sizePublic>0){
        
        var escena_json = JSON.parse(escena);
        var escena_json_length = escena_json.length;
    
        
        //recorre todas las cat y primero dibujo el item de cat
        for(var i=0; i<escena_json_length; i++){
            
            
            var id_padre = escena_json[i].id_padre;
            
            if(id_padre == null){
                
                var json_cat = escena_json[i].id || 0;
                var json_cat_nombre = escena_json[i].nombre || "";
                
                var item_html = `<li class="splide__slide item item-cat-${json_cat}">
                <div class="titulo-col-cont" onclick="window.location.replace('${window.location.href}ampliar-publicacion-home.html?accion=ampliar&cat=${json_cat}')">
                <div class="titulo-col random-p-${i}"><span class="span-titulo">${json_cat_nombre}</span></div>
                </div>
                </li>`;
                
                $(".splide__list__home").append(item_html);
                
                //numero random pattern por ahora
                var random = Math.floor(Math.random() * 7);
                if (random == 0) {random=random+1}
                $(".random-p-"+i).addClass("pattern"+random);
                
                //recorre solo si la json_cat es igual a la de puid_public_catblic
                
                for(var x=0; x<sizePublic; x++){
                    
                    var id_public = data[x].id || '';
                    var id_public_cat = data[x].subescena1 || 0;
                    var nombre_public = data[x].publicacion_nombre || '';
                    var descr_public = data[x].publicacion_descripcion || '';
                    var imagen_id = data[x].foto || '';
                    var producto = data[x].pid || 0;
                    var foto_src = `/publicaciones_img/${imagen_id}.png` || 0;//viene siempre png?
                    var favorito = data[x].favorito;
                    var fav_accion = "";
                    var full_url = `/ampliar-publicacion-home.html?id=${id_public}&accion=ampliar&cat=${id_public_cat}`;
                    
                    
                    if(json_cat == id_public_cat){
                        
                        var public_html = 
                        `<div>
                        <div class="content-col-div content-col-div-${id_public} cat-${id_public_cat}">
                                    <div class="overlay-public">
                                    <a class="link-ampliar-home" href="${full_url}"></a>
                                    <div class="public-title-home">${nombre_public}</div>
                                    <div class="text-overlay">
                                    <span class="text-overlay-link share-sm" onclick="pathShareHome('${full_url}')">
                                    <a href="javascript:void(0)"><i class="fas fa-share-alt"></i></a>
                                    </span>
                                    &nbsp;&nbsp;
                                    <span class="text-overlay-link text-overlay-link-${id_public}">
                                    
                                    </span>
                                    </div>
                                    </div>
                                    <img src="${foto_src}" alt="img-${imagen_id}">
                                    </div>
                                    </div>`;
                                    
                            $(".item-cat-"+json_cat).append(public_html)
                                    
                            toggleFav(favorito,id_public,"",fav_accion);
                                    
                                    
                    }
                }
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

function getMisProductos(data){
    console.log(data)
    var sizeProductos = data.length || 0; 
    var showResultados = document.querySelector(".show-result-num") || 0;
    showResultados.innerHTML = sizeProductos; 

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
    //return new Promise((resolve, reject) => {

    //const {url,dataPaging} = dataPaging;
    const URL = `/app/${dataPaging.url}?cant=${dataPaging.cantidad}`;
    let url_temp = dataPaging.url || "";
    
    document.body.classList.add("loading"); 

    fetch(URL)
    .then(response => response.json())
    .then(data => {
            //console.log(data)
            var cant = parseInt(data.length);
            dataPaging.cantidad = dataPaging.cantidad+cant;
            //console.log(dataPaging.cantidad)
            //let test = data[12].favorito || 0;
            console.log("desde --> ",url_temp)
           // console.table(test)

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
                default: alert("error")
            }

            document.body.classList.remove("loading"); 
        })
        //.catch(error => console.log("error"))
        
}
/*

function getSeguidoresPopup(){
var seguidosHtml =
`<div class="overlay overlay-seguidores" style="display: none;">
   <div style="width: 400px;" class="lightBox lightBox-seguidores">
      <div>
         <a href="javascript:void(0)" id="cerrar-light"><i class="fas fa-times-circle"></i></a>
         <h3 class="count-seguidores"></h3>
         <hr>
           <div class="container-seguidores"></div>
         </div>
      </div>
   </div>
</div>`
}

function getSeguidosPopup(){
var seguidosHtml =
`<div class="overlay overlay-seguidos" style="display: none;">
   <div style="width: 400px;" class="lightBox lightBox-seguidos">
      <div>
         <a href="javascript:void(0)" id="cerrar-light"><i class="fas fa-times-circle"></i></a>
         <h3 class="count-seguidos"></h3>
         <hr>
         <div class="container-seguidos"></div>
         </div>
      </div>
   </div>
</div>`
}*/

function eliminarCarrito(id_prod,id_publicacion,tipo_carrito){

    const URL = "/app/carrito.php"

    var dataEliminar = new FormData();
    dataEliminar.append("accion","alta");
    dataEliminar.append("id",id_prod);
    dataEliminar.append("cantidad","0");
    dataEliminar.append("id_prod",id_prod);
    dataEliminar.append("id_publicacion",id_publicacion);
    
    fetch(URL, {
        method: 'POST',
        body: dataEliminar,
    }).then(res => res.json())
    .then(response => {
        console.log(response)
        //window.location.replace(tipo_carrito)
    })
    .catch(error => console.error('Error:', error))

}

function sendComentarioPublic(id_public,thisParam,indexParam){

    let val = $("#comentario-"+indexParam).val();

    var dataComentario = new FormData();
    dataComentario.append("accion","alta");
    dataComentario.append("publicacion",id_public);
    dataComentario.append("comentario",val);
    
    var appendeo = thisParam.parent().parent().parent().find(".commentbox-list-container");
    console.log(appendeo)
    console.log(val)

    var content_html =
    `<div class="commentbox-list media commentbox-id">
       <span class="comment-name">nicolasgomez94</span>
       <span class="comment-text">${val}</span>
    </div>`;

    $(appendeo).append(content_html);

    /*var img_perfil = $(".img-perfil-usuario-drop").attr("src");
    $(".commentbox-user-img").attr("src", img_perfil);*/

    $.ajax({
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
                //window.location.replace(dataM);														
            }else if(dataJ == 'OK'){
                //window.location.replace("/test-cobrar-compra.html?id="+id_carrito);
                console.log(dataJ+"--"+dataM);
            }else{
                //window.location.replace("/ampliar-carrito.html");
                //alert(dataJ+"--"+dataM);
                console.log(dataJ+"--"+dataM);
            }
        },
        error: function( data ){
            console.log(data)
            alert("error->"+data.status);
        }
    });
    return false;
}


function sendComentarioProd(id_public,thisParam,indexParam){

    let val = $("#comentario-"+indexParam).val();

    var dataComentario = new FormData();
    dataComentario.append("accion","alta");
    dataComentario.append("publicacion",id_public);
    dataComentario.append("comentario",val);
    
    var appendeo = thisParam.parent().parent().parent().find(".commentbox-list-container");
    console.log(appendeo)
    console.log(val)

    var content_html =
    `<div class="commentbox-list media commentbox-id">
       <span class="comment-name">nicolasgomez94</span>
       <span class="comment-text">${val}</span>
    </div>`;

    $(appendeo).append(content_html);

    /*var img_perfil = $(".img-perfil-usuario-drop").attr("src");
    $(".commentbox-user-img").attr("src", img_perfil);*/

    $.ajax({
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
                //window.location.replace(dataM);														
            }else if(dataJ == 'OK'){
                //window.location.replace("/test-cobrar-compra.html?id="+id_carrito);
                console.log(dataJ+"--"+dataM);
            }else{
                //window.location.replace("/ampliar-carrito.html");
                //alert(dataJ+"--"+dataM);
                console.log(dataJ+"--"+dataM);
            }
        },
        error: function( data ){
            console.log(data)
            alert("error->"+data.status);
        }
    });
    return false;
}