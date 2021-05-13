$(document).ready(function() {



    //icono gif de carga
    $(document).on({
        ajaxStart: function(){
           $("body").addClass("loading"); 
        },
        ajaxStop: function(){ 
           $("body").removeClass("loading"); 
        }    
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

    $(".cerrar-galeria-banner").click(function(){
        $("#carouselExampleIndicators").hide();
    });

    /*PUBLICACIONES*/
    /*eliminar foto img-pins*/
    $("#eliminar-img-flotante").click(function(){
        $("#imagen-pins").val('');
        $("#eliminar-img-flotante").hide();
        $("#img-subir-pins").show();
        $("#output-imgpins").attr("src","");
        $("#output-imgpins").hide();
        $("#map").css({
            "background-image" : "none",
            "width" : "unset",
            "height": "unset"
        });
        $(".img-pines-amapear").hide();
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
    $('#edit-btn, .share-sm').click(function(e) {
        e.preventDefault();

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
        $("#map").css("pointer-events","all");
        $("#terminar-productos-btn").show();
        $("#limpiar-productos-btn").show();
        $("#anadir-productos-btn").hide();
        
        $(".overlay-prod").show(); //TODO
        $("#eliminar-img-flotante").hide();
        
        $('#map').dropPin('dropMulti',{
            cursor: 'crosshair',
            pinclass: 'qtipinfo',
            pin: '../../img/tag-solid.svg'
        });

    });

    /*cerrar modo edicion de pines*/
    $("#terminar-productos-btn").click(function(){
        $(".overlay-prod").hide();
        $("#limpiar-productos-btn").hide();
        $("#terminar-productos-btn").hide();
        $("#anadir-productos-btn").show();
        $("#map").css("pointer-events","none");
    });

    /*SHOW PINES*/
    /**/

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
                $("body").addClass("loading"); 
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

/*FORMULARIO SUBIR PUBLICACION*/
$('#subir-publicacion-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();

    var formData = new FormData($(this)[0]);
    
    //appendeo la imagen sacada del map. ya la inicio el onchange
    /*var url_imagen_64 = $("#map").css("background-image").split("url(")[1];
    var sc_url_imagen_64 = url_imagen_64.replace(/['"]+/g, '');
    var sc_url_imagen_642 = sc_url_imagen_64.split(")")[0];//villa mal*/
    var url_imagen_64 = $(".img-pines-amapear").attr("src")

    var pin_object = $(".pin").serializeArray();
    var pin_object_str = JSON.stringify(pin_object)
    //console.log(pin_object_str)

    formData.append("foto_base64",url_imagen_64);
    formData.append("data_pines",pin_object_str);
    formData.delete("publicacion_foto");
        
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


/****formu-subir***/
var jcat = jsonData.categoria || 0;
var sizeCat = jcat.length || 0;

if(sizeCat>0){
    for(var i=0; i<sizeCat; i++){
        var nombre_cat = jsonData.categoria[i].nombre;
        var id_cat = jsonData.categoria[i].id;
        /*var id_cat_rubro = jsonData.rubro[i].id_categoria;*/
    
        var html_cat = '<option class="option-cat" value="'+id_cat+'">'+nombre_cat+'</option>';
        $("#categoria-producto").append(html_cat);

        /*var sizeRubro = jsonData.rubro.length;*/
        
        /*if(sizeRubro>0 && id_cat==id_cat_rubro){
            for(var i=0; i<sizeRubro; i++){
                var nombre_rubro = jsonData.rubro[i].nombre;
                var id_rubro = jsonData.rubro[i].id;
                
                var html_rubro = '<option value="'+id_rubro+'">'+nombre_rubro+'</option>';
                $("#rubro-producto").append(html_rubro);
            }
        }*/

    }
}





/***ampliar/editar producto***/ 
if(typeof jsonData.productos != "undefined"){
    var sizeProductos = jsonData.productos.length || 0;
    if(sizeProductos>0 || typeof sizeProductos != "undefined"){
        if(window.location.pathname == '/ampliar-producto.html'){
            for(var i=0; i<sizeProductos; i++){
                var nombre_prod = jsonData.productos[i].titulo;
                var precio_prod = jsonData.productos[i].precio;
                var id_prod = jsonData.productos[i].id;
                var stock_prod = jsonData.productos[i].stock;
                var foto_prod = jsonData.productos[i].foto;
                var foto_src = '/productos_img/'+foto_prod+'.png';
        
                var listadoProducto = 
                    '<div class="row producto">'+
                        '<div class="col-lg-2 col-md-2 col-sm-2 col-2"><div class="img-producto-container-'+i+'" data-title="'+foto_prod+'"><img class="img-producto" src="'+foto_src+'"></div></div>'+
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-3 text-left"><span class="titulo-producto">'+nombre_prod+'</span></div>'+
                        '<div class="col-lg-2 col-md-2 col-sm-2 col-2 "><span class="precio-producto">AR$. '+precio_prod+'</span></div>'+
                        '<div class="col-lg-2 col-md-2 col-sm-2 col-2 "><span class="stock-producto">'+stock_prod+'</span></div>'+
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-3 text-right"><i data-title="'+i+'" class="fas fa-ellipsis-v ellip"></i></div>'+
                        '<div class="acciones-producto acciones-producto-'+i+'">'+
                            '<div class="eliminar-producto" data-title="'+id_prod+'"><a href="#"><i class="fas fa-trash-alt"></i>&nbsp;Eliminar</a></div>'+
                            '<div class="modificar-producto" data-title="'+id_prod+'"><a href="/editar-producto.html?id='+id_prod+'&accion=editar"><i class="fas fa-edit"></i>&nbsp;Modificar</a></div>'+
                        '</div>'+
                    '</div>';
        
        
                $("#listado-mis-productos").append(listadoProducto);
        
            }
        }else if(window.location.pathname == '/editar-producto.html'){
            for(var i=0; i<sizeProductos; i++){
    
                var id_prod = jsonData.productos[i].id || 0;
                var nombre_prod = jsonData.productos[i].titulo || "";
                var marca_prod = jsonData.productos[i].marca || "";
                var precio_prod = jsonData.productos[i].precio || 0;
                var envio_prod = jsonData.productos[i].envio || "";
                var garantia_prod = jsonData.productos[i].garantia || "";
                var descr_prod = jsonData.productos[i].descr_producto || "";
                var foto_prod_editar = jsonData.productos[i].foto || "";
                var color_prod = jsonData.productos[i].color || "";
                var nombre_cat = jsonData.categoria[i].nombre || "";
                var stock_prod = jsonData.productos[i].stock || 0;
                var id_cat = jsonData.categoria[i].id || 0;
                //var id_cat_rubro = jsonData.rubro[i].id_categoria;
                
                var fotosArray = foto_prod_editar.split(",") || [];
                //var fotosArrayIndex = fotosArray[];
                for(var i=0; i<fotosArray.length; i++){
                    
                    
                    //inserto img en cubo corresp
                    var foto_src = '/productos_img/'+fotosArray[i]+'.png' || 0;
                    var img = $("#prev_"+i).find(".img-responsive");
                    // img.attr("onerror","this.onerror=null;this.src='img no encontrada';");
                    img.attr("onerror","$(this).parent().addClass('hidden');");
                    img.attr("onload","$(this).parent().parent().find('.new').addClass('hidden')");
                    img.attr("src",foto_src);
                    img.parent().removeClass("hidden");
                    //img.parent().parent().find(".new").addClass("hidden");

                    
                    // img.on("error", function(){
                    //     $(this).attr('src', 'pepe');
                    //     $("#prev_"+i).addClass("hidden");
                    //     $(this).attr("onerror","this.onerror=null")
                    // });
                }

                $("#titulo-producto").val(nombre_prod);
                $("#precio-producto").val(precio_prod);
                $("#stock-producto").val(stock_prod);
                $("#color").val(color_prod);
                $("#descr-producto").val(descr_prod);
                $("#marca-producto").val(marca_prod);
                $("#envio-producto").val(envio_prod);
                $("#garantia-producto").val(garantia_prod);
                
        
                var html_cat = '<option class="option-cat" value="'+id_cat+'" selected>'+nombre_cat+'</option>';
                $("#categoria-producto").append(html_cat);
        
                /*var sizeRubro = jsonData.rubro.length;
                
                if(sizeRubro>0 && id_cat==id_cat_rubro){
                    for(var i=0; i<sizeRubro; i++){
                        var nombre_rubro = jsonData.rubro[i].nombre;
                        var id_rubro = jsonData.rubro[i].id;
        
                        var html_rubro = '<option value="'+id_rubro+'" selected>'+nombre_rubro+'</option>';
                        $("#rubro-producto").append(html_rubro);
                    }
                }*/
        
            }
        }
        
    }else{
        var html_nada = '<div class="html-nada">No se han encontrado resultados.</div>';
        $("#listado-mis-productos").html(html_nada);
    }
}


/**apertura y cierre las opciones*/ 
$(function(){
    $(".ellip").on("click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var $this = $(this);
        var title = $this.data('title');
        
        $(".acciones-producto-"+title).show();
        $(".acciones-producto:not(.acciones-producto-"+title+")").hide();
        
    });
    $(document).click(function () {
        $(".acciones-producto").hide();
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
    var value = $("#buscador-titulo-input").val();
    var cant_elem= $(".producto:not('.header-productos')").length;//sin header-prod
    //var cant_json = jsonData.productos.length;
    var path = window.location.pathname;

    if(value != ''){

        var len = $(".titulo-producto").length;

        var hideMatcheo = $(".titulo-producto:not(:contains("+value+"))").parent().parent();
        var appendMatcheo = $(".titulo-producto:contains("+value+")").parent().parent();

        if(len==0){
            var html_nada = '<div class="html-nada">No se han encontrado resultados.</div>';
            $("#listado-mis-productos").html(html_nada);
        }else if(path=="/ampliar-producto.html"){
            hideMatcheo.hide();
            appendMatcheo.show();
        }else{
            hideMatcheo.parent().parent().hide();
            appendMatcheo.parent().show();
        }
        // $("#listado-mis-productos").append(appendMatcheo);
        
    }else if(path=="/ampliar-producto.html"){
        $(".titulo-producto").parent().parent().show();
    }else{
        $(".titulo-producto").parent().parent().parent().parent().show();
    }
    
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


/*cruz para limpiar buscador*/
$(function(){
    $(".limpiar-buscador").hide();

    var buscador = $("#buscador-titulo-input");
    var limpiar = $(".limpiar-buscador");
    var path = window.location.pathname;

    buscador.keyup(function(){
        limpiar.show();

        if(buscador.val()==""){
            limpiar.hide();
        }
    });

    limpiar.click(function(){
        if(path=="/ampliar-producto.html"){
            $(".titulo-producto").parent().parent().show();
        }else{
            $(".titulo-producto").parent().parent().parent().parent().show();
        }
        limpiar.hide();
        buscador.val("");
    });
});


/*IMPORTAR CSv**/
$("#subir-csv").on('submit', function() {

    var reader = new FileReader();
    reader.onload = function(){
        
        var dataImport = { 
            'file': reader.result,
            'accion':'importar'
        };

        $.ajax({
            type: 'POST',
            url: '/app/producto.php',
            data: dataImport,
            dataType: "json",
            async: true,//por la warning
            success: function(data, textStatus, jQxhr ) {
                if (data.status == 'REDIRECT'){
                    window.location.replace(data.mensaje);														
                }else if(data.status == 'OK'){
                    var html_result = '<div id="result-importar-ok">'+data.mensaje+'</div>';
                    $("#result-importar").html(html_result);
                    $(".fa-times-circle").click(function(){
                        window.location.replace("/ampliar-producto.html");	
                    });
                }else{
                    var html_result = '<div id="result-importar-alt">'+data.mensaje+'</div>';
                    $("#result-importar").html(html_result);
                    $(".fa-times-circle").click(function(){
                        window.location.replace("/ampliar-producto.html");	
                    });
                }

            },
            error: function(jqXhr, textStatus, errorThrown) {
                var msj = "En este momento no podemos atender su petici\u00f3n, por favor espere unos minutos y vuelva a intentarlo.";
                alert(msj);         
           }
        });
    };
    reader.readAsDataURL($("#file-csv").get(0).files[0]);    
    return false;
});

//AÑADIR AL CARRITO
$(".modal").on("click", ".btn-carrito", function(){

    var id_value = $(this).parent().parent().find(".id_prod_carrito").val();
    var cantidad_value = $(this).parent().parent().find(".cantidad_value").val();

    var dataCarr = new FormData();
    dataCarr.append("accion","alta");
    dataCarr.append("id",id_value);
    dataCarr.append("cantidad",cantidad_value);

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
              console.log("OK-->"+dataJ+"/"+dataM);
              window.location.replace("/ampliar-carrito.html");
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
           if (dataJ == 'REDIRECT'){
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
$(".fa-times-circle").bind("click", function(e){
    e.preventDefault();
    var id_prod = $(this).parent().find("input.prod-id").val();
    var id_carrito = jsonData.carrito[0].id_carrito;

    var dataEliminar = new FormData();
    dataEliminar.append("cantidad","0");
    dataEliminar.append("accion","alta");
    dataEliminar.append("id",id_prod);
    dataEliminar.append("id_carrito",id_carrito);
       
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


/***/
$(".cantidad_value").change(function(){
    var valor_selected = $(this).find("option:selected").val();
    var tag_precio_cambiar = $(this).parent().parent().parent().find(".precio-producto-modal>span");
    var tag_precio_cambiar_data = tag_precio_cambiar.attr("data-precio");

    var valor_cambiado = parseInt(tag_precio_cambiar_data) * parseInt(valor_selected);
    tag_precio_cambiar.text("AR$. "+valor_cambiado);

    /*console.log("tag_precio_cambiar "+tag_precio_cambiar)
    console.log("tag_precio_cambiar_split "+tag_precio_cambiar_split)
    console.log("valor_selected "+valor_selected)
    console.log("valor_cambiado "+valor_cambiado)*/
    
})


/***fin document.ready***//***fin document.ready***/
/***fin document.ready***//***fin document.ready***/
/***fin document.ready***//***fin document.ready***/



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
    var reader = new FileReader();
    reader.onload = function(){
        // var output = document.getElementById('output-imgpins');
        // output.src = reader.result;
        $("#img-subir-pins").hide();
        // $("#output-imgpins").show();
        $(".img-pines-amapear").show();
        // $("#map").css("background-image","url('"+reader.result+"')");
        $(".img-pines-amapear").attr("src",reader.result);
        $("#map").css("width","100%");
        // $("#map").css("height","100%");
        $("#eliminar-img-flotante").show();
        $("#anadir-productos-btn").show();

    };
    reader.readAsDataURL(event.target.files[0]);

}


function copiarLink(){
    var copyText = document.getElementById("inputCopiarLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
}

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
                $(".splide__list").empty();

                //if(jsonData.perfil == "Picker"){
                    for(var i=0; i<resp_len; i++){

                        var id_prod = response.mensaje[i].id;
                        var nombre_prod = response.mensaje[i].titulo;
                        var foto_prod = response.mensaje[i].foto;
                        var foto_src = '/productos_img/'+foto_prod+'.png' || 0;//viene siempre png?

                        /*'<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-'+i+'" src="'+img_base_prod+'"></li>';*/

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

function seguidores(id_publicacion,idPublicadorParam){

    var data = new FormData();
    data.append("accion","eliminar");
    data.append("id_publicacion",id_publicacion);
    data.append("id_publicador",idPublicadorParam);
 
    $.ajax({
       url: '/app/seguidores.php',
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
             alert(data)									
          }else if(dataJ == 'OK'){
             console.log("OK-->"+dataJ+"/"+dataM);
             alert(data)
          }else{
             console.log("ELSE-->"+dataJ+"/"+dataM);
             alert(data)
          }
       },
       error: function( data, jqXhr, textStatus, errorThrown ){
          ajax("ERROR AJAX--> "+data);
          console.log(data);
       }
    });
    return false;
 }
