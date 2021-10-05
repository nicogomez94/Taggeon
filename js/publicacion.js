$(document).ready(function(){


    

    
      
   //funcion para que se esconda globocat
   hideGloboCat()

   //lo mando a la public que se selecciono desde home
   /*var WinLocSplit = window.location.href.split("=")[1].split("&")[0] || "";
   var public_pos = $("#ancla-desde-home-"+WinLocSplit).offset().top - 80;
   $('html,body').scrollTop(public_pos)*/
   


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
         '<div class="img-modal-prod"><img onerror="this.src=\'/imagen_perfil/generica_prod.jpg\'" style="width: 100%;" src="'+foto_src_prod_p+'" alt="foto_src_prod"></div>'+
      '<hr>'+
      '<div>'+
         /*'<h5 style="text-align:left">Ficha T&eacute;cnica</h5>'+
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
         '</table>'+*/
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
         '<span><button class="btn btn-warning btn-carrito" onclick="fetchIdCarrito('+id_public_p+',)" data-idpublic="'+id_public_p+'" data-idprod="'+id_prod_json_p+'">Añadir a Carrito</button></span>'+
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
            '<div class="img-modal-prod"><img onerror="this.src=\'/imagen_perfil/generica_prod.jpg\'" style="width: 100%;" src="'+foto_src_prod_p+'" alt="foto_src_prod"></div>'+
         '<hr>'+
         '<div>'+
            /*'<h5 style="text-align:left">Ficha T&eacute;cnica</h5>'+
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
            '</table>'+*/
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

function getSplideProdPublic(param){
   const URL = `/app/producto.php?accion=getproductos&id=${param}`

   //si ya clickee una vez, no vuelvas a hacer la llamada
   var el =  document.querySelector('.prod-tag-public');
   if (el == null){

      fetch(URL).then(res => res.json())
      .catch(error => console.error('Error:', error))
      .then((response) => {
         
         let resp_len = response.mensaje.length
         console.log(response.mensaje)
         //para que no cree ifninitos items de galeria
         
            if(resp_len > 0){
               for(let i = 0; i < resp_len; i++){
                  let nombre_prod = response.mensaje[i].titulo;
                  let precio_prod = response.mensaje[i].precio;
                  let marca_prod = response.mensaje[i].marca;
                  let color_prod = response.mensaje[i].color;
                  let descr_prod = response.mensaje[i].descr_producto;
                  let id_prod_json = response.mensaje[i].id;
                  let stock_prod = response.mensaje[i].stock;
                  let foto_prod = response.mensaje[i].foto;
                  // nombre_completo = jsonData.nombre+""+jsonData.apellido;
                  let nombre_completo = "test"
                  let foto_src_prod = `/productos_img/${foto_prod}.png`;
                  let splide_list = document.querySelector('.splide__list__'+param)
      
      
                  var objParamModal = {
                           id_prod_p : id_prod_json,
                           id_public_p : param,
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
                     
            
                  let splide_fotos = `<li class="prod-tag-public splide__slide splide__slide__img splide__prodtag">
                     <img onerror="this.src=\'/imagen_perfil/generica_prod.jpg\'" data-toggle="modal" data-target="#modal-producto-${id_prod_json}" src="${foto_src_prod}"></li>`;
                  
                     
                  //dibujo tags y list de galeria splide
                  splide_list.insertAdjacentHTML("beforeend",splide_fotos);
      
               }   

            }else{
               alert("error publics")
            }
      });//then
   }
}