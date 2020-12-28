$(document).ready(function(){

    var sizePublic = jsonData.publicaciones.length;
    
    if(sizePublic>0){
        for(var i=0; i<sizePublic; i++){
          
            var id_public = jsonData.publicaciones[i].id;
            var id_public_cat = jsonData.publicaciones[i].id_publicacion_categoria;
            var nombre_public = jsonData.publicaciones[i].publicacion_nombre;
            var descr_public = jsonData.publicaciones[i].publicacion_descripcion;
            var imagen_id = jsonData.publicaciones[i].foto;
            var producto = jsonData.publicaciones[i].pid;
            var cat_ampliar_home = jsonData.cat;
            
            var foto_src = '/publicaciones_img/'+imagen_id;
            var img_base_public = getImagen(foto_src);
            
            //si viene esta cat ya se que es de home
            if(/*parseInt(cat_ampliar_home) > 0 && */cat_ampliar_home == id_public_cat){
                console.log(cat_ampliar_home)
                console.log(id_public_cat)
            

            var html_public = '<div class="public-ampliar public-actual test2">'+
                               '<div class="header-public">'+
                                  '<span class="img-perfil-public">'+
                                     '<img src="" alt="img-perfil">'+
                                  '</span>'+
                                  '<span class="title-public"></span>'+
                                  '<span class="opciones-public"><i class="fas fa-cog"></i></span>'+
                               '</div>'+
                            '<div class="bodyimg-public-container bodyimg-public-container-'+i+'">'+
                               //'<div><img src="../../img/arrrrte.jpg" alt=""></div>'+
                                  '<img class="imagen-public-'+imagen_id+'" src="'+img_base_public+'" alt="">'+
                                  '<div class="tag-container tag-container-'+i+'">'+
                            '</div>'+
                            '<div class="info-public">'+
                               '<div class="social-public">'+
                                     '<span><i class="fas fa-heart"></i></span>'+
                                     '<span><i class="fas fa-comment-dots"></i></span>'+
                                     '<span><i class="fas fa-paper-plane"></i></span>'+
                               '</div>'+
                            '<div class="datos-public">'+
                               '<div class="info-titulo-public">'+nombre_public+'</div>'+
                               '<div class="info-tipo-public"><a href="#">Arte</a> | <a href="#">Diseño</a> | <a href="#">Ambientes</a></div>'+
                               '<div class="info-descr-public">'+descr_public+'</div>'+
                            '</div>'+
                               '<hr>'+
                            '<div class="productos-public productos-public-'+i+'">'+
                               '<div class="productos-titulo-public">Productos en esta publicacion:</div><br>'+
                                  '<div class="productos-titulo-public-gallery productos-titulo-public-gallery-'+i+'">'+
                                     '<div class="splide splide-prod-tag-'+i+'">'+
                                        '<div class="splide__track">'+
                                           '<ul class="splide__list splide__list__'+i+'"></ul>'+
                                        '</div>'+
                                     '</div>'+
                                     /**/
                               '<hr><div class="productos-titulo-public prod-relacionados">Productos relacionados:</div><br>'+
                                     '<div class="splide splide-related splide-prod-'+i+'">'+
                                        '<div class="splide__track">'+
                                           '<ul class="splide__list">'+
                                           '<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfs.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/D_NQ_NP_685438-MLA31115404061_062019-O.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfsdf.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfs.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/D_NQ_NP_685438-MLA31115404061_062019-O.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfsdf.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfs.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/D_NQ_NP_685438-MLA31115404061_062019-O.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfsdf.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfs.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/D_NQ_NP_685438-MLA31115404061_062019-O.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfsdf.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfs.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/D_NQ_NP_685438-MLA31115404061_062019-O.jpg"></li>'+
'<li class="splide__slide"><img data-toggle="modal" src="/productos_img/sdfsdf.jpg"></li>'+
                                           '</ul>'+
                                        '</div>'+
                                     '</div>'+
                                  '</div>'+
                               '</div>'+
                            '</div>'+
                         '</div>'
                            
            $(".insert-public").append(html_public);
        
            
        
            //imgperfil sacada del menu top
            var img_perfil = $(".img-perfil-usuario-drop").attr("src");
            $(".img-perfil-public img").attr("src", img_perfil);
            $(".test-suggest").attr("src", img_perfil);
        
            //nombre perfil
            var nombre_perfil = $(".user-name-perfil").text();
            $(".title-public").html(nombre_perfil);
            
            //imagen principal de public
            
        
            ///DIBUJO PINES
            var producto_parse = JSON.parse(producto);
            var producto_parse_size = producto_parse.length;

            for(var x=0; x<producto_parse_size; x++){
                var id_prod = producto_parse[x].name;
                var coords = producto_parse[x].value;
                var ycoord = coords.split("-")[0];
                var xcoord = coords.split("-")[1];
        
                //checkeo si es el mismo id de tag y prod
                var arr = jsonData.productos;
                var obj = arr.find(o => o.id === id_prod);
        
                    if(id_prod == obj.id){
        
                        //le saco el index el producto correspondiente
                        var arr2 = jsonData.productos;
                        var index = arr2.findIndex(o => o.id === id_prod);
                        //var test = Object.values(jsonData.productos)[x];
            
                        var nombre_prod = jsonData.productos[index].titulo;
                        var precio_prod = jsonData.productos[index].precio;
                        var marca_prod = jsonData.productos[index].marca;
                        var color_prod = jsonData.productos[index].color;
                        var descr_prod = jsonData.productos[index].descr_producto;
                        var id_prod_json = jsonData.productos[index].id;
                        var stock_prod = jsonData.productos[index].stock;
                        var foto_prod = jsonData.productos[index].foto;
                        var id_cat = jsonData.categoria[index].id;
                        var nombre_completo = jsonData.nombre+""+jsonData.apellido;
                        //onsole.log(getImagen(foto_src))
                        var foto_src_prod = '/productos_img/'+foto_prod;
                        var img_base_prod = getImagen(foto_src_prod);
                            var modal_html =  
                                '<div class="modal fade" id="modal-producto-'+id_prod+'" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">'+
                                '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">'+
                                '<div class="modal-content">'+
                                '<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->'+
                                '<div class="modal-body">'+
                                '<div class="row">'+
                                '<div class="col-lg-7">'+
                                    '<div class="img-modal-prod"><img style="width: 100%;" src="'+img_base_prod+'" alt=""></div>'+
                                '<hr>'+
                                '<div>'+
                                '<table class="tg" style="table-layout: fixed; width: 282px">'+
                                '<colgroup>'+
                                '<col style="width: 153px">'+
                                '<col style="width: 129px">'+
                                '</colgroup>'+
                                '<tbody>'+
                                '<tr>'+
                                    '<td class="tg-9f3l">ID Producto</td>'+
                                    '<td class="tg-wo29">'+id_prod_json+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="tg-9f3l">Marca</td>'+
                                    '<td class="tg-wo29">'+marca_prod+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="tg-9f3l">Color</td>'+
                                    '<td class="tg-wo29">'+color_prod+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="tg-9f3l">Categoria</td>'+
                                    '<td class="tg-wo29">'+id_cat+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td class="tg-9f3l">Rubro</td>'+
                                    '<td class="tg-z6p2">'+id_cat+'</td>'+//hardcodeado
                                '</tr>'+
                                '</tbody>'+
                                '</table>'+
                                '</div></div>'+
                                '<div class="col-lg-5 col-datos-producto">'+
                                '<div>'+
                                    '<h2>'+nombre_prod+'</h2>'+
                                    '<p style="font-size: 0.8em; color: grey; font-style: italic">Por: '+nombre_completo+'</p>'+
                                '</div>'+
                                '<hr>'+
                                '<div>'+
                                '<div class="precio-producto-modal"><span>$. '+precio_prod+'</span></div>'+
                                '<div class="shipment-modal-producto">'+
                                '<i class="fas fa-truck-loading"></i> Shipment dentro de las 5 dias habiles'+//hardcodeado
                                '</div>'+
                                '<hr>'+
                                '<div class="stock-boton-modal">'+
                                    '<span>'+
                                        'Cantidad&nbsp;'+
                                        '<select class="cantidad_value" name="cantidad">'+
                                            '<option value="1">1</option>'+//hardcodeado
                                            '<option value="2">2</option>'+//hardcodeado
                                            '<option value="3">3</option>'+//hardcodeado
                                            '<option value="4">4</option>'+//hardcodeado
                                            '<option value="5">5</option>'+//hardcodeado
                                            '<option value="6">6</option>'+//hardcodeado
                                            '<option value="7">7</option>'+//hardcodeado
                                        '</select>'+
                                        '<input type="hidden" class="id_prod_carrito" name="id" value="'+id_prod_json+'">'+
                                    '</span>&nbsp;'+
                                    '<span><button class="btn btn-warning btn-carrito">Añadir a Carrito</button></span>'+
                                '</div>'+
                                '</div>'+
                                '<hr>'+
                                '<div class="descripcion-modal-producto">'+
                                '<strong>Descripcion:</strong>'+
                                '<div>'+descr_prod+'</div>'+
                                '</div>'+
                                '<hr>'+
                                '</div></div></div></div></div></div>';
            
                            $("body").append(modal_html);
                        
                                //dibujo splide
                        
                    }//fin if prod
                
                    var splide_fotos = '<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-'+id_prod+'" src="'+img_base_prod+'"></li>';
                    $(".splide__list__"+i).append(splide_fotos);
                //dibujo tags
                var tag_html = '<div class="tagg tagg-'+id_prod+'" style="top:'+ycoord+'; left: '+xcoord+'">'+
                            '<span><i class="fas fa-tags"></i></span></div>';
                
                $(".tag-container-"+i).append(tag_html);

                //click en tag
                $(".bodyimg-public-container-"+i).on("click", ".tagg", function(){
                    var prod_public = $(this).parent().parent().find(".productos-public");
                    prod_public.toggle(100);
                    //data-toggle="modal" data-target="#modal-producto-'+id_prod+'"
                });
          
            
            }//fin for prdo
            
            //productos en esta public
            new Splide( '.splide-prod-tag-'+i, {
                perPage: 6,
                rewind : true,
                pagination: false
            } ).mount();
            new Splide( '.splide-prod-'+i, {
                perPage: 6,
                rewind : true,
                pagination: false
            } ).mount();
        }
        }//fin for principal

       
    }



////////////////CARRITO

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
              console.log("OK-->"+dataJ);
              window.location.replace("/ampliar-carrito.html");
           }else{
              console.log("ELSE-->"+dataJ);
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert("ERROR"+response);
            console.log(dat);
        }
   });
   return false;

});

 

 ///crear orden de compra carrito
 $(".boton-checkout-carrito").click(function(){

    var id_carrito = jsonData.carrito[0].id_carrito;

    var dataCheckout = new FormData();
    dataCheckout.append("accion","finalizar");
    dataCheckout.append("id_carrito",id_carrito);

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
              console.log("OK-->"+dataJ);
              window.location.replace("/ampliar-checkout.html");
           }else{
              console.log("ELSE-->"+dataJ);
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert("ERROR"+response);
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
              alert("OK-->"+dataJ);
              //window.location.replace("/ampliar-checkout.html");
           }else{
              alert("ELSE-->"+dataJ);
              //window.location.replace("/ampliar-carrito.html");
           }
        },
        error: function( data, jqXhr, textStatus, errorThrown ){
            alert("ERROR"+response);
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
             console.log("OK-->"+dataJ);
             window.location.replace("/ampliar-carrito.html");
          }else{
             console.log("ELSE-->"+dataJ);
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




//FIN READY
});//FIN READY
//FIN READY