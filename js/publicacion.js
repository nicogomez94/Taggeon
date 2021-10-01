$(document).ready(function(){

    var sizePublic = jsonData.publicaciones.length;
    

    for(var i=0; i<sizePublic; i++){
       
       if(sizePublic>0){

            var escena_json = JSON.parse(escena);
            var escena_json_length = escena_json.length;
            var id_public = jsonData.publicaciones[i].id || 0;
            var id_public_cat = jsonData.publicaciones[i].subescena1 || "";//que onda esto cuando son mas de una??
            var nombre_public = jsonData.publicaciones[i].publicacion_nombre || "";
            var descr_public = jsonData.publicaciones[i].publicacion_descripcion || "";
            var publicador = jsonData.publicaciones[i].nombre_publicador || "";
            var id_publicador = jsonData.publicaciones[i].id_publicador || "";
            var foto_perfil = jsonData.publicaciones[i].foto_perfil || "";
            var seguidor = "";
            var imagen_id = jsonData.publicaciones[i].foto || 0;
            var producto = jsonData.publicaciones[i].pid || 0;
            var cat_ampliar_home = jsonData.cat || 0;
            var arrCat = escena_json || 0;
            var foto_src = '/publicaciones_img/'+imagen_id+'.png' || 0;//viene siempre png?
            var img_publicador = '/imagen_perfil/'+foto_perfil+'.png' || 0;//viene siempre png?
            var winLoc = window.location.pathname || "";
            var id_usuario = "1";//hard
            var favorito = jsonData.publicaciones[i].favorito || 0;
            var fav_accion = "";
            var seg_accion = "";
            var seguidos = jsonData.seguidos || [];
            var idPublicadorSearch = seguidos.find(o => o.idUsuario === id_publicador) || "";
            var idPublicadorSeguido = idPublicadorSearch.idUsuario;
            var comentarios_obj = jsonData.publicaciones[i].comentarios || []      


            if(cat_ampliar_home == 0) cat_ampliar_home = id_public_cat //si viene por mis-public lo igualo asi no putea


            if(cat_ampliar_home == id_public_cat){
            
               //dibujo la cat arriba de todo
               var objCat = escena_json.find(o => o.id === cat_ampliar_home) || "";
               var nameCat = objCat.nombre || "";
               $(".title-cat").html(nameCat);

               //link NEXT cat
               var cat_ampliar_home_next = parseInt(cat_ampliar_home) + 1;
               var objCatNext = arrCat.find(o => o.id === cat_ampliar_home_next.toString()) || 0;
               var objCatNextId = objCatNext.id || 0;
               var nombre_cat_next = (typeof objCatNext.nombre == "undefined") ? "" : objCatNext.nombre;
               $(".next-cat a").attr("href",'/ampliar-publicacion-home.html?accion=ampliar&cat='+objCatNextId);
               

               //link PREV cat
               var cat_ampliar_home_prev = parseInt(cat_ampliar_home) - 1;
               var objCatPrev = arrCat.find(o => o.id === cat_ampliar_home_prev.toString()) || 0;
               var objCatPrevId = objCatPrev.id || 0;
               var nombre_cat_pre = (typeof objCatPrev.nombre == "undefined") ? "" : objCatPrev.nombre;
               $(".prev-cat a").attr("href",'/ampliar-publicacion-home.html?accion=ampliar&cat='+objCatPrevId);
               if(typeof objCatPrev.nombre == "undefined") $(".prev-cat").hide();

               //rellenos costados
               $(".up_relleno_2_izq").html('<span class="inside_up_relleno">'+nombre_cat_pre+'</span>');
               $(".up_relleno_1_der").html('<span class="inside_up_relleno">'+nombre_cat_next+'</span>');
               
               

            var html_public = '<div id="ancla-desde-home-'+id_public+'" class="public-ampliar public-actual test2">'+
                                 '<div class="header-public header-public-'+id_public+'" onmouseover="showFollow(this)" onmouseout="hideFollow(this)">'+
                                    '<a class="nombre-perfil-public" href="/ampliar-usuario-redirect.html?id_usuario='+id_publicador+'">'+
                                       '<span class="img-perfil-public"><img src="'+img_publicador+'" alt="img-perfil"></span>'+
                                       '<span class="title-public title-public-'+i+'"></span>'+
                                    '</a>'+
                                 '</div>'+
                            '<div class="bodyimg-public-container bodyimg-public-container-'+i+'">'+
                               //'<div><img src="../../img/arrrrte.jpg" alt=""></div>'+
                                  '<img class="imagen-public-'+imagen_id+'" src="'+foto_src+'" alt="">'+
                                  '<div class="tag-container tag-container-'+i+'"></div>'+

                              // '<hr>'+
                            
                              '</div>'+


                              '<div id="ancla-'+i+'" class="productos-public productos-public-'+i+'">'+
                               '<div class="productos-titulo-public">Productos Relacionados:</div><br>'+
                                  '<div class="productos-titulo-public-gallery productos-titulo-public-gallery-'+i+'">'+
                                     '<div class="splide splide-prod-tag-'+i+'">'+
                                        '<div class="splide__track">'+
                                           '<ul class="splide__list splide__list__'+i+'"></ul>'+
                                        '</div>'+
                                     '</div>'+
                                     /**/
                               //'<hr><div class="productos-titulo-public prod-relacionados">Comprar Productos relacionados:</div><br>'+
                                       '<div class="splide splide-related splide-prod-'+i+'">'+
                                          '<div class="splide__track">'+
                                             '<ul class="splide__list splide_list_related"></ul>'+
                                          '</div>'+
                                       '</div>'+
                                    '</div>'+
                                 '</div>'+


                              '<div class="info-public">'+
                                 '<div class="social-public social-public-'+id_public+'">'+
                                       //'<span><i class="fas fa-heart fav-'+i+'" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></i></span>'+
                                       //'<span onclick="seguidores('+id_public+','+id_publicador+','+seg_accion+')"><i class="fas fa-user-plus"></i></span>'+
                                       // '<span class="comment-icon"><i class="fas fa-comment-dots"></i></span>'+
                                       '<span class="share-sm"><i class="fas fa-paper-plane"></i></span>'+
                                       '<span><i class="fas fa-star"></i></span>'+
                                 '</div>'+
                                 '<div class="datos-public">'+
                                 '<div class="info-titulo-public">'+nombre_public+'</div>'+
                                 // '<div class="info-tipo-public"><a href="#">Arte</a> | <a href="#">Diseño</a> | <a href="#">Ambientes</a></div>'+
                                 '<div class="info-descr-public">'+descr_public+'</div><hr>'+
                              '</div>'+
                              '<div id="ancla-test-'+i+'"></div>'+
                              '<div class="commentbox-container">'+
                                 '<div class="commentbox commentbox-id-2">'+
                                       '<div>'+
                                          '<img class="mr-1 commentbox-user-img" src="/imagen_perfil/generica.png" alt="perfil"></div>'+
                                          '<div style="flex-grow: 1;">'+
                                             '<form class="comentario_public comentario_public_'+id_public+'">'+
                                                '<input type="text" name="comentario" style="width: 100%;" placeholder="Ingrese un comentario">'+
                                                '<input type="hidden" name="publicacion" value="'+id_public+'">'+
                                             '</form>'+
                                          '</div>'+
                                          '<div class="ml-1">'+
                                             '<button onclick="$(\'.comentario_public_'+id_public+'\').submit();console.log(\'test\')" class="btn">Enviar</button>'+
                                          '</div>'+
                                       '</div>'+
                                 //'<div class="comment-count"><span>Comentarios</span></div>'+
                                 '<div class="commentbox-list-container commentbox-list-container-'+id_public+'"></div>'+
                              '</div>'+
                           '</div>';

                           
            $(".insert-public").append(html_public);
            $(".title-public-"+i).html(publicador);

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
                  '<div class="commentbox-list media commentbox-id-'+y+'">'+
                  '   <span class="comment-name">nicolasgomez94</span>'+//hard
                  '   <span class="comment-text">'+comentario+'</span>'+
                  '</div>';
                  
                  $(".commentbox-list-container-"+id_public).append(comentario_html);

               }else{
                  var comentario_html2 = "<p>No hay comentarios</p>"
                  
                  $(".commentbox-list-container-"+id_public).append(comentario_html2)
               }
               
            }

            /*apertura y cierre de comments*/
            $(".social-public-"+id_public).on("click", ".comment-icon", function(e){
               e.stopPropagation();
               e.preventDefault();
               
               var prod_public = $(this).parent().parent().find(".commentbox-container");
               prod_public.toggle(100);
            });

            //imgperfil comentarios
            var img_perfil = $(".img-perfil-usuario-drop").attr("src");
            $(".commentbox-user-img").attr("src", img_perfil);
            

            if (favorito==null || favorito == 0) {
               fav_accion="alta";
               var fav_html = '<span><i class="fas fa-heart" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></i></span>'
               $(".social-public-"+id_public).prepend(fav_html);
            }else{
               fav_accion="eliminar";
               var fav_html = '<span><i class="fas fa-heart fav-eliminar" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></span>'
               $(".social-public-"+id_public).prepend(fav_html);
            }
            /**/
               

            if(idPublicadorSeguido==id_publicador) {
               seg_accion="eliminar";
               var seg_html = '<span class="follow_public"><i class="fas fa-user-plus seg-eliminar" onclick="seguidores('+id_public+',\''+id_publicador+'\',\''+seg_accion+'\');$(this).toggleClass(\'seg-eliminar\')"></span>'
               $(".header-public-"+id_public).append(seg_html);
            }else{
               seg_accion="alta";
               var seg_html = '<span class="follow_public"><i class="fas fa-user-plus" onclick="seguidores('+id_public+',\''+id_publicador+'\',\''+seg_accion+'\');$(this).toggleClass(\'seg-eliminar\')"></i></span>'
               $(".header-public-"+id_public).append(seg_html);
            }
            
            
            //productos
            var splide = new Splide( '.splide-prod-tag-'+i, {
               perPage: 6,
               rewind : true,
               pagination: false
            } ).mount();

            ///DIBUJO PINES
            var producto_parse = JSON.parse(producto);
            var producto_parse_size = producto_parse.length;

            for(var x=0; x<producto_parse_size; x++){
               var id_prod = producto_parse[x].name;
               var coords = producto_parse[x].value;
               var ycoord = coords.split("-")[0];
               var xcoord = coords.split("-")[1];
      
               //checkeo si es el mismo id de tag y prod
               //var arr = jsonData.productos;
               //var obj = arr.find(o => o.id === id_prod);

               //checkeo que cat es para mostrar relacionados
               //var objRel = arr.find(o => o.id === cat_actual);
                  //dibujo modales
                  // if(id_prod == obj.id){
                  if(id_prod == null){
      
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
                     var nombre_completo = jsonData.nombre+""+jsonData.apellido;
                     var foto_src_prod = '/productos_img/'+foto_prod+'.png';
                     //var modParam = "modal-producto-rel-"+i;
                     //var marca_filtro = allprod.find(o => o.marca === "Gucci");

                     var objParamModal = {
                        id_prod_p : id_prod,
                        id_public_p : id_public,
                        foto_src_prod_p : foto_src_prod,
                        id_prod_json_p : id_prod_json,
                        marca_prod_p : marca_prod,
                        color_prod_p : color_prod, 
                        descr_prod_p : descr_prod,
                        nombre_prod_p : nombre_prod,
                        nombre_completo_p : nombre_completo,
                        precio_prod_p : precio_prod,
                        i_p : i
                     }


                     traerModalProducto(objParamModal)
                     dibujarSplideRel(arr2,marca_prod,'marca',splide,id_prod_json);
                     
                  }
                
                  var splide_fotos = '<li class="splide__slide splide__slide__img splide__prodtag"><img data-toggle="modal" data-target="#modal-producto-'+id_prod+'" src="'+foto_src_prod+'"></li>';
                  $(".splide__list__"+i).prepend(splide_fotos);
                  
            
                  //dibujo tags
                  var tag_html = `<div href="ancla-${i}" onclick="getProdPublic(${id_prod})" class="tagg tagg-${id_prod}" style="top:${ycoord}%; left: ${xcoord}%">
                              <span><img src="../../plugins/dropPin-master/dropPin/dot-circle-solid.svg"></span></div>`;
                  
                  $(".tag-container-"+i).append(tag_html);

                  //click en tag
                  $(".bodyimg-public-container-"+i).on("click", ".tagg", function(e){
                     e.stopPropagation();
                     e.preventDefault();
                     
                     var prod_public = $(this).parent().parent().parent().find(".productos-public");
                     prod_public.toggle(100);
                     prod_public.toggleClass("prods-abierto");

                     if(prod_public.hasClass("prods-abierto")){
                        $('html,body').animate({
                           scrollTop: prod_public.offset().top - 130
                        }, 0)

                     }

                  });
          
            
            }//fin for prdo
        }
        }//fin for principal

       
   }//fin if principal

   //funcion para que se esconda globocat
   hideGloboCat()

   //lo mando a la public que se selecciono desde home
   var WinLocSplit = window.location.href.split("=")[1].split("&")[0] || "";
   var public_pos = $("#ancla-desde-home-"+WinLocSplit).offset().top - 80;
   $('html,body').scrollTop(public_pos)
   


//FIN READY
});//FIN READY
//FIN READY

function hideGloboCat(){
   var position = $(window).scrollTop(); 

   $(".globo-cat").show();
   $(window).scroll(function() {
      var scroll = $(window).scrollTop();
      if(scroll > position) {
         $(".globo-cat").fadeOut(200);
      } else {
         $(".globo-cat").fadeIn(200);
      }
      position = scroll;
   });
}

/* CUANDO VENAGN BIEN PROD
function showRelated(){

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
         var dataJ = JSON.parse(data).status;
         var dataM = JSON.parse(data).mensaje;

         if (dataJ == 'REDIRECT'){
               console.log("REDIRECT-->"+dataM);									
         }else if(dataJ == 'OK'){
               console.log("OK-->"+dataJ+"/"+dataM);
               $(".notif-id-"+id_notif).remove();
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

}*/

function traerModalProducto({id_prod_p,id_public_p,foto_src_prod_p,id_prod_json_p,marca_prod_p,color_prod_p,descr_prod_p,
   nombre_prod_p,nombre_completo_p,precio_prod_p,i_p}){

   var modal_producto_html =  
      '<div class="modal fade" id="modal-producto-'+id_prod_p+'" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">'+
      '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">'+
      '<div class="modal-content">'+
      '<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->'+
      '<div class="modal-body">'+
      '<div class="row">'+
      '<div class="col-lg-7">'+
         '<div class="img-modal-prod"><img style="width: 100%;" src="'+foto_src_prod_p+'" alt="foto_src_prod"></div>'+
      '<hr>'+
      '<div>'+
         '<h5 style="text-align:left">Ficha T&eacute;cnica</h5>'+
         '<table class="tg" style="table-layout: fixed; width: 282px">'+
         '<colgroup>'+
         '<col style="width: 153px">'+
         '<col style="width: 129px">'+
         '</colgroup>'+
         '<tbody>'+
         '<tr>'+
            '<td class="tg-9f3l">ID Producto</td>'+
            '<td class="tg-wo29">'+id_prod_json_p+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="tg-9f3l">Marca</td>'+
            '<td class="tg-wo29">'+marca_prod_p+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="tg-9f3l">Color</td>'+
            '<td class="tg-wo29">'+color_prod_p+'</td>'+
            '</tr>'+
            '<tr>'+
            '<td class="tg-9f3l">Categoria</td>'+
            '<td class="tg-wo29">21</td>'+//hardcodeado
            '</tr>'+
            '<tr>'+
            '<td class="tg-9f3l">Rubro</td>'+
            '<td class="tg-z6p2">15</td>'+//hardcodeado
         '</tr>'+
         '</tbody>'+
         '</table>'+
      '</div></div>'+
      '<div class="col-lg-5 col-datos-producto">'+
      '<div>'+
         '<h2>'+nombre_prod_p+'</h2>'+
         '<p style="font-size: 0.8em; color: grey; font-style: italic">Por: '+nombre_completo_p+'</p>'+
      '</div>'+
      '<hr>'+
      '<div>'+
      '<div class="precio-producto-modal"><span data-precio="'+precio_prod_p+'">AR$ '+precio_prod_p+'</span></div>'+
      '<div class="shipment-modal-producto">'+
      '<i class="fas fa-truck-loading"></i> Shipment dentro de las 5 d&iacute;as h&aacute;biles'+//hardcodeado
      '</div>'+
      '<hr>'+
      '<div class="stock-boton-modal">'+
         '<span>'+
               'Cantidad&nbsp;'+
               '<select class="cantidad_value" name="cantidad">'+
                  '<option value="1">1</option>'+
                  '<option value="2">2</option>'+
                  '<option value="3">3</option>'+
                  '<option value="4">4</option>'+
                  '<option value="5">5</option>'+
               '</select>'+
               '<input type="hidden" class="id_prod_carrito" name="id" value="'+id_prod_json_p+'">'+
         '</span>&nbsp;'+
         '<span><button class="btn btn-warning btn-carrito" onclick="fetchIdCarrito()" data-idpublic="'+id_public_p+'" data-idprod="'+id_prod_json_p+'">Añadir a Carrito</button></span>'+
      '</div>'+
      '</div>'+
      '<hr>'+
      '<div class="descripcion-modal-producto">'+
      '<strong>Descripcion:</strong>'+
      '<div>'+descr_prod_p+'</div>'+
      '</div>'+
      '<hr>'+
      '</div>'+
      // separador comments
      '<div class="commentbox-container" style="display:none">'+
         '<hr><div class="commentbox media commentbox-id-'+id_prod_p+'">'+
            '<img class="mr-3 commentbox-user-img" src="" alt="perfil">'+
            '<div class="media-body">'+
               '<form class="comentario_prod">'+
                  '<div class="textarea-container">'+
                     '<textarea placeholder="Deja un comentario" maxlength="16384"></textarea>'+
                  '</div>'+
                  '<input type="hidden" name="id_producto" value="'+id_prod_json_p+'">'+
                  '<button class="btn btn-warning">Enviar</button>'+
                  /*'<div class="rating">'+
                  '   <input name="stars" id="e5" type="radio"></a><label for="e5">☆</label>'+
                  '   <input name="stars" id="e4" type="radio"></a><label for="e4">☆</label>'+
                  '   <input name="stars" id="e3" type="radio"></a><label for="e3">☆</label>'+
                  '   <input name="stars" id="e2" type="radio"></a><label for="e2">☆</label>'+
                  '   <input name="stars" id="e1" type="radio"></a><label for="e1">☆</label>'+
                  '</div>'+*/
               '</form>'+
            '</div>'+
         '</div>'+
         '<div class="comment-count"><span>Comentarios</span></div>'+
         '<div class="commentbox-list-container">'+
            '<div class="commentbox-list media commentbox-id-'+id_prod_p+'">'+
               '<img class="mr-3 commentbox-user-img" src="" alt="perfil">'+
               '<div class="media-body">'+
                  '<p>Ive tried embeding it in the new google sites - the comment box showed up, but required authentication. It would be nice to have it simply allowing anon comments. Yet, once the signin was made, it keeps showing the message "The supplied URL is not a part of this proje - yet everything seems ok in the project config.</p>'+
                     '<div class="commentbox-actions">'+
                     '   <span class="actions-name">Nicolas Gómez</span>&nbsp;&middot;&nbsp;'+
                     '   <span class="actions-time">1m</span>'+
                     '</div>'+
               '</div>'+
            '</div>'+
            //

         '</div>'+
      '</div>'+
      '</div></div></div></div></div>';

      // document.body.appendChild(modal_producto_html);
      $("body").append(modal_producto_html)
}

function dibujarSplideRel(array,key,prop,splideParam,idProdTag){

   prop = (typeof prop === 'undefined') ? 'name' : prop;    

   for (var i=0; i<array.length; i++) {
      if(array[i][prop] === key) {
         var foto_prod_rel = array[i].foto;
         var id_prod_rel = array[i].id;
         if(id_prod_rel != idProdTag){
            var foto_src_prod_rel = '/productos_img/'+foto_prod_rel+'.png';
            var html_related = '<li class="splide__slide splide__slide__img">'+
            '<img data-toggle="modal" onclick="createModalRelAjax(\''+id_prod_rel+'\');$(\'#modal-producto-'+id_prod_rel+'\').modal(\'show\');" data-target="modal-producto-'+id_prod_rel+'" src="'+foto_src_prod_rel+'"></li>';
   
            splideParam.add(html_related);
         }
      }
   }
}

function createModalRelAjax(idParam){

   var arr = jsonData.productos;
   var obj = arr.find(o => o.id === idParam);
   //TODO HARD falta añadir funcionalidad para que no se creen infinitos modales

   if(idParam == obj.id){
      var id_prod_p = obj.id || 0;
      var id_public_p = obj.id_public || 0;
      var id_prod_json_p = obj.id || 0;
      var marca_prod_p = obj.marca || "";
      var descr_prod_p = obj.descr_producto || "";
      var nombre_prod_p = obj.titulo || "";
      var nombre_completo = jsonData.nombre+""+jsonData.apellido;
      var nombre_completo_p = obj.nombre_completo || "";
      var precio_prod_p = obj.precio || 0;
      var color_prod_p = obj.color || "";
      var foto_prod = obj.foto || "";
      var foto_src_prod_p = '/productos_img/'+foto_prod+'.png';


      var modal_producto_html =  
         '<div class="modal fade" id="modal-producto-'+id_prod_p+'" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">'+
         '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">'+
         '<div class="modal-content">'+
         '<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->'+
         '<div class="modal-body">'+
         '<div class="row">'+
         '<div class="col-lg-7">'+
            '<div class="img-modal-prod"><img style="width: 100%;" src="'+foto_src_prod_p+'" alt="foto_src_prod"></div>'+
         '<hr>'+
         '<div>'+
            '<h5 style="text-align:left">Ficha T&eacute;cnica</h5>'+
            '<table class="tg" style="table-layout: fixed; width: 282px">'+
            '<colgroup>'+
            '<col style="width: 153px">'+
            '<col style="width: 129px">'+
            '</colgroup>'+
            '<tbody>'+
            '<tr>'+
               '<td class="tg-9f3l">ID Producto</td>'+
               '<td class="tg-wo29">'+id_prod_json_p+'</td>'+
               '</tr>'+
               '<tr>'+
               '<td class="tg-9f3l">Marca</td>'+
               '<td class="tg-wo29">'+marca_prod_p+'</td>'+
               '</tr>'+
               '<tr>'+
               '<td class="tg-9f3l">Color</td>'+
               '<td class="tg-wo29">'+color_prod_p+'</td>'+
               '</tr>'+
               '<tr>'+
               '<td class="tg-9f3l">Categoria</td>'+
               '<td class="tg-wo29">21</td>'+//hardcodeado
               '</tr>'+
               '<tr>'+
               '<td class="tg-9f3l">Rubro</td>'+
               '<td class="tg-z6p2">15</td>'+//hardcodeado
            '</tr>'+
            '</tbody>'+
            '</table>'+
         '</div></div>'+
         '<div class="col-lg-5 col-datos-producto">'+
         '<div>'+
            '<h2>'+nombre_prod_p+'</h2>'+
            '<p style="font-size: 0.8em; color: grey; font-style: italic">Por: '+nombre_completo_p+'</p>'+
         '</div>'+
         '<hr>'+
         '<div>'+
         '<div class="precio-producto-modal"><span data-precio="'+precio_prod_p+'">AR$ '+precio_prod_p+'</span></div>'+
         '<div class="shipment-modal-producto">'+
         '<i class="fas fa-truck-loading"></i> Shipment dentro de las 5 d&iacute;as h&aacute;biles'+//hardcodeado
         '</div>'+
         '<hr>'+
         '<div class="stock-boton-modal">'+
            '<span>'+
                  'Cantidad&nbsp;'+
                  '<select class="cantidad_value" name="cantidad">'+
                     '<option value="1">1</option>'+
                     '<option value="2">2</option>'+
                     '<option value="3">3</option>'+
                     '<option value="4">4</option>'+
                     '<option value="5">5</option>'+
                  '</select>'+
                  '<input type="hidden" class="id_prod_carrito" name="id" value="'+id_prod_json_p+'">'+
            '</span>&nbsp;'+
            '<span><button class="btn btn-warning btn-carrito" data-idpublic="'+id_public_p+'" data-idprod="'+id_prod_json_p+'">Añadir a Carrito</button></span>'+
         '</div>'+
         '</div>'+
         '<hr>'+
         '<div class="descripcion-modal-producto">'+
         '<strong>Descripcion:</strong>'+
         '<div>'+descr_prod_p+'</div>'+
         '</div>'+
         '<hr>'+
         '</div>'+
         // separador comments
         '<div class="commentbox-container" style="display:none">'+
            '<hr><div class="commentbox media commentbox-id-'+id_prod_p+'">'+
               '<img class="mr-3 commentbox-user-img" src="" alt="perfil">'+
               '<div class="media-body">'+
                  '<form class="comentario_prod">'+
                     '<div class="textarea-container">'+
                        '<textarea placeholder="Deja un comentario" maxlength="16384"></textarea>'+
                     '</div>'+
                     '<input type="hidden" name="id_producto" value="'+id_prod_json_p+'">'+
                     '<button class="btn btn-warning">Enviar</button>'+
                     /*'<div class="rating">'+
                     '   <input name="stars" id="e5" type="radio"></a><label for="e5">☆</label>'+
                     '   <input name="stars" id="e4" type="radio"></a><label for="e4">☆</label>'+
                     '   <input name="stars" id="e3" type="radio"></a><label for="e3">☆</label>'+
                     '   <input name="stars" id="e2" type="radio"></a><label for="e2">☆</label>'+
                     '   <input name="stars" id="e1" type="radio"></a><label for="e1">☆</label>'+
                     '</div>'+*/
                  '</form>'+
               '</div>'+
            '</div>'+
            '<div class="comment-count"><span>Comentarios</span></div>'+
            '<div class="commentbox-list-container">'+
               '<div class="commentbox-list media commentbox-id-'+id_prod_p+'">'+
                  '<img class="mr-3 commentbox-user-img" src="" alt="perfil">'+
                  '<div class="media-body">'+
                     '<p>Ive tried embeding it in the new google sites - the comment box showed up, but required authentication. It would be nice to have it simply allowing anon comments. Yet, once the signin was made, it keeps showing the message "The supplied URL is not a part of this proje - yet everything seems ok in the project config.</p>'+
                        '<div class="commentbox-actions">'+
                        '   <span class="actions-name">Nicolas Gómez</span>&nbsp;&middot;&nbsp;'+
                        '   <span class="actions-time">1m</span>'+
                        '</div>'+
                  '</div>'+
               '</div>'+
               //

            '</div>'+
         '</div>'+
         '</div></div></div></div></div>';

         $("body").append(modal_producto_html)
   }
      

}

function appearTooltip(msjParam){
   
   /*var tooltip = document.querySelector(".tooltip-nico");
   tooltip.style.right="blue";
   tooltip.innerHTML = msjParam;*/

   //document.getElementsByClassName("tooltip-nico").style.color="blue";
}