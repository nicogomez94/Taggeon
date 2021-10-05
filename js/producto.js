document.addEventListener("DOMContentLoaded", function() {

    /***ampliar/editar producto***/ 
    if(typeof jsonData != "undefined" && typeof jsonData.productos != "undefined" ){
        var sizeProductos = jsonData.productos.length || 0;
        
        var showResultados = document.querySelector(".show-result-num") || 0;
        showResultados.innerHTML = sizeProductos; 

        if(window.location.pathname == '/editar-producto.html'){
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
                    
                    var fotosArray = foto_prod_editar.split(",") || [];

                    for(var i=0; i<fotosArray.length; i++){

                        var foto_src = '/productos_img/'+fotosArray[i]+'.png' || 0;
                        var img = $("#prev_"+i).find(".img-responsive");
                        img.attr("onerror","$(this).parent().addClass('hidden');");
                        img.attr("onload","$(this).parent().parent().find('.new').addClass('hidden')");
                        img.attr("src",foto_src);
                        img.parent().removeClass("hidden");
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

});