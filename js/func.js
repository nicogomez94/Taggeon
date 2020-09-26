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



      





