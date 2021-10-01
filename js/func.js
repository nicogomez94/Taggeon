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

    //icono gif de carga
    $(document).on({
        ajaxStart: function(){
           $("body").addClass("loading"); 
        },
        ajaxStop: function(){ 
           $("body").removeClass("loading"); 
        }    
     });
     $("#dropdown-user-menu").click(function(e){
        e.stopPropagation();
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

    $('.carousel').carousel({
        interval: 20001111
    })

    //para que lo inconos de filtro no show
    $(".icon-sort").hide();

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

/*fin riki*/

/*NICOOOO*//*NICOOOO*//*NICOOOO*/

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


    /*funciones para que se cierre el otro modal atras del otro*/
    $("#recuperaPass").on('show.bs.modal', function (e) {
        $("#modal-sesion").modal("hide");
    });
    /**/
    $("#modal-registro").on('show.bs.modal', function (e) {
        $("#modal-sesion").modal("hide");
    });
    /**/
    $("#modal-registro-seller").on('show.bs.modal', function (e) {
        $("#modal-registro").modal("hide");
    });




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
$(".modal").on("click", ".btn-carrito", function(){
    
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
$(".boton-checkout-carrito").click(function(){
    
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
        //dataType: "json",
        //async: false,
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
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert(data);
        }
    });
    return false;
});


///finalizar orden
$("#finalizar-orden").submit(function(){

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
              console.log("REDIRECT-->"+dataM);
              //window.location.replace(dataM);														
           }else if(dataJ == 'OK'){
              window.location.replace("/cobrar-compra.html?id="+id_carrito);
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


///eliminar de carrito
$(".eliminar-carrito").bind("click", function(e){//cochinada
    e.preventDefault();
    var id_prod = $(this).parent().find("input.prod-id").val() || 0;
    var carrito_id = $(this).parent().find("input.carrito-id").val() || 0;
    var id_publicacion = $(this).parent().find("input.id-publicacion").val() || 0;
    var id_carrito = jsonData.carrito[0].id_carrito;

    var dataEliminar = new FormData();
    // dataEliminar.append("accion","eliminar");
    dataEliminar.append("accion","alta");
    dataEliminar.append("id",id_prod);
    dataEliminar.append("cantidad","0");
    dataEliminar.append("id_prod",id_prod);
    dataEliminar.append("id_publicacion",id_publicacion);

    $.ajax({
       url: '/app/carrito.php',
       data: dataEliminar,
       type: 'POST',
       processData: false,
       contentType: false,
       //async: false,
       success: function( data, textStatus, jQxhr ){
           var dataJ = JSON.parse(data).status;
           var dataM = JSON.parse(data).mensaje;
          if (dataJ == 'REDIRECT'){
             console.log("REDIRECT-->"+dataM);
             window.location.replace(dataM);														
          }else if(dataJ == 'OK'){
             console.log("OK-->"+dataJ+"/"+dataM);
             window.location.replace("/ampliar-carrito.html");
          }else{
             console.log("ELSE-->"+dataJ+"/"+dataM);
             //window.location.replace("/ampliar-carrito.html");
          }
       },
       error: function( data, jqXhr, textStatus, errorThrown ){
          console.log("ERROR AJAX--> "+response);
          console.log(data);
       }
    });
    return false;

});

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
$(".comentario_public").submit(function(){
    console.log("test")
    var dataComentario = new FormData($(this)[0]);
    dataComentario.append("accion","alta");

    var comentario = dataComentario.get("comentario");
    
    var appendeo = $(this).parent().parent().parent().find(".commentbox-list-container");

    var content_html =
    '<div class="commentbox-list media commentbox-id">'+
    '   <span class="comment-name">nicolasgomez94</span>'+//hard
    '   <span class="comment-text">'+comentario+'</span>'+
    '</div>';

    $(appendeo).append(content_html);

    var img_perfil = $(".img-perfil-usuario-drop").attr("src");
    $(".commentbox-user-img").attr("src", img_perfil);

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
                /*var appendeo = $(this).find("commentbox-list-container");
                $("appendeo")*/
            }else{
                //window.location.replace("/ampliar-carrito.html");
                //alert(dataJ+"--"+dataM);
                console.log(dataJ+"--"+dataM);
            }
        },
        error: function( data ){
            console.log(data)
            /*var dataJ2 = JSON.parse(data).status;
            var dataM2 = JSON.parse(data).mensaje;*/
            alert("error->"+data.status);
        }
    });
    return false;
});

///submit comentario_prod
$(".comentario_prod").submit(function(){

    var dataComentario = new FormData($(this)[0]);
    dataComentario.append("accion","alta");

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


$("")

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
                '<img src="' + img_perfil + '" alt="profile_pic">'
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
                
                var resp_len = response.mensaje.length;
                var splide_list = $(".splide__prod_public .splide__list");
                
                splide_list.empty();

                //if(jsonData.perfil == "Picker"){
                    for(var i=0; i<resp_len; i++){

                        var id_prod = response.mensaje[i].id;
                        var nombre_prod = response.mensaje[i].titulo;
                        var foto_prod = response.mensaje[i].foto;
                        var foto_src = '/productos_img/'+foto_prod+'.png' || 0;//viene siempre png?

                        /*'<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-'+i+'" src="'+img_base_prod+'"></li>';*/

                        var html = '<li class="splide__slide splide__slide__img '+id_prod+'">'+
                                    '<img data-toggle="modal" data-target="#modal-producto-'+i+'" src="'+foto_src+'">'+
                                    '<div class="nombre-producto '+id_prod+' nombre-producto-'+i+'">'+nombre_prod+'</div></li></div>';
                        // var html = '<option class="nombre-producto '+id_prod+' nombre-producto-'+i+'">'+nombre_prod+'</option>'
                        splide_list.append(html);

                    }
                    new Splide( '.splide__prod_public', {
                            perPage: 4,
                            rewind : true,
                            pagination: false
                        } ).mount();

                
                //si es seller
                /*}else{
                    
                    for(var i=0; i<jsonData.productos.length; i++){

                        var id_prod = jsonData.productos[i].id;
                        var nombre_prod = jsonData.productos[i].titulo;
                        var foto_prod = jsonData.productos[i].foto;
                        var foto_src = '/productos_img/'+foto_prod+'.png' || 0;//viene siempre png?

                        var html = '<li class="splide__slide">'+
                                    '<img data-toggle="modal" data-target="#modal-producto-'+i+'" src="'+foto_src+'">'+
                                    '<div class="nombre-producto '+id_prod+' nombre-producto-'+i+'">'+nombre_prod+'</div></li></div>';
                        // var html = '<option class="nombre-producto '+id_prod+' nombre-producto-'+i+'">'+nombre_prod+'</option>'
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
                console.log(response)
                var jsonData = response;
                
                $(".splide__home").empty();
                $("#main-super-container").empty();
                $("#carousel-index").empty();

                    if(sizePublic>0){   
                        var public_cat_size = escena.length;
                        
                        /*si encontro publics, creo la grid*/
                        var grid_ = '<div class="grid"></div>';
                        var board_el = document.querySelector(".board");
                        var mscontainer = document.querySelector("#main-super-container")
                        
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
                            var nombre_public = jsonData.publicaciones[x].publicacion_nombre || '';
                            var descr_public = jsonData.publicaciones[x].publicacion_descripcion || '';
                            var imagen_id = jsonData.publicaciones[x].foto || '';
                            var producto = jsonData.publicaciones[x].pid || 0;
                            var foto_src = '/publicaciones_img/'+imagen_id+'.png' || 0;//viene siempre png?
                            var favorito = jsonData.publicaciones[x].favorito || 0;
                            var fav_accion = "";
                            var full_url = '/ampliar-publicacion-home.html?id='+id_public+'&accion=ampliar&cat='+id_public_cat

                            var public_html2 =
                                '<div class="grid-item">'+
                                    '<div class="content-col-div content-col-div-'+id_public+' cat-'+id_public_cat+'">'+
                                        '<div class="overlay-public">'+
                                            '<a class="link-ampliar-home" href="'+full_url+'"></a>'+
                                            // '<a class="link-ampliar-home"></a>'+
                                            '<div class="public-title-home">'+nombre_public+'</div>'+
                                            '<div class="text-overlay">'+
                                                '<span class="text-overlay-link share-sm" onclick="pathShareHome(\''+full_url+'\')">'+
                                                    '<a href="#"><i class="fas fa-share-alt" ></i></a>'+
                                                '</span>'+
                                                '<span class="text-overlay-link text-overlay-link-'+id_public+'">'+
                                                //'<label><input onclick="favoritos('+id_public+',\''+fav_accion+'\')" type="checkbox"><div class="like-btn-svg"></div></label>'+
                                                '</span>'+
                                            '</div>'+
                                        '</div>'+
                                        '<img src="'+foto_src+'" alt="img-'+imagen_id+'">'+
                                    '</div>'+
                                '</div>';

                            $(".grid").append(public_html2)
            
                            
                            if (favorito==null || favorito == 0) {
                                fav_accion="alta";
                                var fav_html = '<a href="#"><i class="fas fa-heart" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></i></a>'
                                $(".text-overlay-link-"+id_public).append(fav_html)
                            }else{
                                fav_accion="eliminar";
                                var fav_html = '<a href="#"><i class="fas fa-heart fav-eliminar" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></i></a>'
                                $(".text-overlay-link-"+id_public).append(fav_html)
                            }  
                            
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
                        var sin_result = '<div class="sin-result-index">Lo sentimos, no hemos encontrado ninguna publicaci&oacute;n para esta b&uacute;squeda.</div>'
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
                    var foto_src = '/productos_img/'+foto_prod+'.png';
        
                    //aparece el contador de notifs con nro
                    $(".count-notif").show();
                    $(".count-notif").text(sizeNotifs)
                    
                    var html_notif = 
                                '<div class="media notif-id-'+id+'">'+
                                    '<i class="notif-icon mr-3 fas fa-heart heart-notif"></i>'+
                                    '<div class="media-body"></div>'+
                                    '<i onclick="eliminarNotif(\''+id+'\')" class="eliminar-notif fas fa-times"></i>'+
                                '</div>';
        
                        $(".notifs-button-ampliar").append(html_notif)

                    if(tipo_notif == "favorito"){
                        var html_favorito = '<div>'+nombre+' a&ntilde;adi&oacute; tu publicaci&oacute;n "'+publicacion_nombre+'" como favorita</div>';
                        $(".notif-id-"+id+" .media-body").append(html_favorito)

                    }else if(tipo_notif == "seguidores"){
                        var html_seg = '<div>'+nombre+' te esta siguiendo</div>';
                        $(".notif-id-"+id+" .media-body").append(html_seg)

                    }else if(tipo_notif == "vendedor"){
                        var html_vendedor = '<div><a href="/mis-ventas.html">Vendiste '+nombre_producto+'!</a></div>';
                        var html_foto_prod = '<img class="mr-3 img-notifs" src="'+foto_src+'" alt="img_notif">';

                        $(".notif-id-"+id).prepend(html_foto_prod);
                        $(".notif-id-"+id+">.notif-icon").remove();
                        $(".notif-id-"+id+">.media-body").html(html_vendedor);

                    }else if(tipo_notif == "comentario"){
                        var html_comentario = '<div><a href="/ampliar-publicacion-home.html?id='+id_jn+'&accion=ampliar&cat='+id_publicacion_categoria+'">'+nombre+' coment&oacute; "'+comentario+'" en tu public.: "'+publicacion_nombre+'"</a></div>';
                        //var html_foto_prod = '<img class="mr-3 img-notifs" src="'+foto_src+'" alt="img_notif">';

                        $(".notif-id-"+id+" .media-body").append(html_comentario)
                        
                    }
                }

            }else{
                var no_notif = '<div class="no_notif"><i class="fas fa-flag"></i>&nbsp;&nbsp;No hay notificaciones por el momento.</div>'
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
                    var no_notif = '<div class="no_notif"><i class="fas fa-flag"></i>&nbsp;&nbsp;No hay notificaciones por el momento.</div>'
                    $(".notifs-button-ampliar").html(no_notif);
                    
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
    console.log(event)
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
    //console.log(r)
    //console.log(typeof !!r)

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
                '<div class="form-group">'+
                    '<label for="'+element+'_public">'+element+'</label>'+
                    '<input type="text" id="'+element+'_public" class="form-control" name="'+element+'_public">';
                '</div>'

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
    console.log("teset",sizeSeguidos)

    //perfil
    $(".seguidos-label").html(sizeSeguidos+" Seguidos");
    $(".seguidores-label").html(sizeSeguidores+" Seguidores");

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
            '<div class="media seguidor">'+
                '<img class="mr-3 img-seg" src="unknown" alt="Generic placeholder image">'+
                '<div class="media-body">'+
                    '<h5 class="mt-0">'+nombre+'</h5>'+
                    '<span>'+email+'</span>'
                '</div>'+
            '</div>';
            
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
            '<div class="media seguido">'+
                '<img class="mr-3 img-seg" src="unknown" alt="Generic placeholder image">'+
                '<div class="media-body">'+
                    '<h5 class="mt-0">'+nombre+'</h5>'+
                    '<span>'+email+'</span>'
                '</div>'+
            '</div>';
            
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

    console.log(Url+param)
    $("#inputCopiarLink").val(Url+param)
	document.getElementById("fa-facebook-square").href="http://www.facebook.com/share.php?u=" + UrlEncoded;
	document.getElementById("fa-twitter-square").href="http://twitter.com/home?status=" + title + " " + UrlEncoded;
	//document.getElementById("fa-instagram-square").href="http://twitter.com/home?status=" + title + " " + UrlEncoded;
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
                
                for(var i=0; i<subcats_length; i++) {
                    var cat_id = subcats[i].id;
                    var cat_nombre = subcats[i].nombre;
                    var targetHtml = $(target);
     
                    var cat_select_html = 
                    '<option value="'+cat_id+'">'+cat_nombre+'</option>';
     
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
                
                console.log("-----------------------------------")
                console.log("subEscena",subEscena)
                console.log("source",source)
                console.log("target",target)
                console.log("-----------------------------------")
                
                //si ya hay options en el select, borra esos 
                if(target_length > 1){
                    itemsNext.each(function(){
                        console.log("estos hay")
                        console.log($(this))
                        console.log("-----------------------------------")
                        $(this).empty()
                        $(this).removeClass("showCat");
                    });
                }
                
                for(var i=0; i<subEscena_length; i++) {
                    var cat_id = subEscena[i].id;
                    var cat_nombre = subEscena[i].nombre;

                    var cat_select_html = 
                    '<option value="'+cat_id+'">'+cat_nombre+'</option>';
     
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
                    '<option value="'+cat_id+'">'+cat_nombre+'</option>';
     
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
                '<option value="'+cat_id+'">'+cat_nombre+'</option>';
        
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
                '<option value="'+cat_id+'">'+cat_nombre+'</option>';
        
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


function fetchIdCarrito(){
    const URL = "/app/carrito.php"

    let dataCarr = new FormData();
    dataCarr.append("accion","alta");

    fetch(URL, {
        method: 'POST',
        body: dataCarr,
    }).then(res => res.json())
    .catch(error => console.error('Error:', error))
    .then(response => console.log('Success:', response));

}

function getProdPublic(param){
    const URL = `/app/producto.php?accion=get&id=${param}`

    /*let dataCarr = new FormData();
    dataCarr.append("accion","alta");*/

    fetch(URL).then(res => res.json())
    .catch(error => console.error('Error:', error))
    .then(response => console.log('Success:', response));

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
    if(footer !== null) observer.observe(footer)

    function handleIntersect(entries) {
        if (entries[0].isIntersecting) {
            console.warn("intersect");
            getDataPaging(dataPaging);
        }
    }
}


function getDataPaging(dataPaging) {
    var url = `/app/${dataPaging.url}?cant=${dataPaging.cantidad}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            var cant = parseInt(data.length);
            dataPaging.cantidad = dataPaging.cantidad+data.length;
            console.log(dataPaging.cantidad);
            console.log(data);
        });
}