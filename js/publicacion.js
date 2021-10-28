$(document).ready(function(){


    

    
      
   //funcion para que se esconda globocat
   hideGloboCat()

   //lo mando a la public que se selecciono desde home
   
   


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

function traerModalProducto({id_prod_p,id_public_p,foto_src_prod_p,id_prod_json_p,marca_prod_p,color_prod_p,descr_prod_p,
   nombre_prod_p,nombre_completo_p,precio_prod_p,i_p,comentarios_obj_p,foto_prod_p}){


   var modal_producto_html =  
      `<div class="modal fade" id="modal-producto-${id_prod_p}" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
      <div class="modal-body">
      <div class="row">
      <div class="col-lg-7">
         <div class="img-modal-prod">
            <!--<img onerror="this.src='/imagen_perfil/generica_prod.jpg'" style="width: 100%;" src="${foto_src_prod_p}" alt="foto_src_prod">-->
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                        <div id="custCarousel-${id_prod_p}" class="carousel slide custCarousel" data-ride="carousel" align="center">
                           <div class="carousel-inner carousel-inner-${id_prod_p}"></div>
                           <!-- Controles --> 
                           <a class="carousel-control-prev" href="#custCarousel-${id_prod_p}" data-slide="prev"> 
                              <span class="carousel-control-prev-icon"></span> 
                           </a> 
                           <a class="carousel-control-next" href="#custCarousel-${id_prod_p}" data-slide="next"> 
                              <span class="carousel-control-next-icon"></span>
                           </a> 
                           <!-- Thumbnails -->
                           <ol class="carousel-indicators carousel-indicators-${id_prod_p} list-inline"></ol>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      <hr>
      <div>
         <h5 style="text-align:left">Ficha T&eacute;cnica</h5>
         <table class="tg" style="table-layout: fixed; width: 282px">
         <colgroup>
         <col style="width: 153px">
         <col style="width: 129px">
         </colgroup>
         <tbody>
         <tr>
            <td class="tg-9f3l">ID Producto</td>
            <td class="tg-wo29">${id_prod_json_p}</td>
            </tr>
            <tr>
            <td class="tg-9f3l">Marca</td>
            <td class="tg-wo29">${marca_prod_p}</td>
            </tr>
            <tr>
            <td class="tg-9f3l">Color</td>
            <td class="tg-wo29">${color_prod_p}</td>
            </tr>
            <tr>
         </tr>
         </tbody>
         </table>
      </div></div>
      <div class="col-lg-5 col-datos-producto">
      <div>
         <h2>${nombre_prod_p}</h2>
         <p style="font-size: 0.8em; color: grey; font-style: italic">Por: ${nombre_completo_p}</p>
      </div>
      <hr>
      <div>
      <div class="precio-producto-modal"><span>AR$ ${precio_prod_p}</span></div>
      <div class="shipment-modal-producto">
         <i class="fas fa-truck-loading"></i> Shipment dentro de las 5 d&iacute;as h&aacute;biles
      </div>
      <hr>
      <div class="stock-boton-modal">
         <span>
               Cantidad&nbsp;
               <select class="cantidad_value" name="cantidad">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
               </select>
               <input type="hidden" class="id_prod_carrito" name="id" value="${id_prod_json_p}">
         </span>&nbsp;
         <span><button class="btn btn-warning btn-carrito" onclick="fetchIdCarrito('${id_public_p}','${id_prod_p}','1')" data-idpublic="${id_public_p}" data-idprod="${id_prod_json_p}">Añadir a Carrito</button></span>
      </div>
      </div>
      <hr>
      <div class="descripcion-modal-producto">
      <strong>Descripcion:</strong>
      <div>${descr_prod_p}</div>
      </div>
      <hr>
      </div>
      <hr>
      <div class="commentbox-container">
         <div class="commentbox commentbox-id-2">
            <div>
               <img class="mr-1 commentbox-user-img" src="/imagen_perfil/generica.png" alt="perfil"></div>
               <div style="flex-grow: 1;">
                  <input type="text" id="comentario-${i_p}" name="comentario" style="width: 100%;" placeholder="Ingrese un comentario">
               </div>
               <div class="ml-1">
                  <button onclick="sendComentario('${id_prod_p}','${i_p}','prod')" value="enviar" class="btn">Enviar</button>
               </div>
            </div>
         </div>
         <div class="commentbox-list-container commentbox-list-container-${id_prod_p} commentbox-list-container-prod"></div>
      </div>
      </div></div></div></div></div>`;

      // document.body.appendChild(modal_producto_html);
      $("body").append(modal_producto_html)
      getComentarios(comentarios_obj_p,"prod")
      dibujarCarousel(id_prod_p,foto_prod_p)

      let cant = document.querySelector(".cantidad_value");
      let value_cant = document.querySelector(".cantidad_value").value;
      let btn_carr = document.querySelector(".btn-carrito")

      cant.addEventListener("change",function(){
         let attr = "fetchIdCarrito('"+id_public_p+"','"+id_prod_p+"','"+this.value+"')";
         console.log(attr);
         console.log(typeof attr);
         btn_carr.setAttribute("onclick", attr)
      })

}

function dibujarSplideRel(array,key,prop,splideParam,idProdTag){

   prop = (typeof prop === 'undefined') ? 'name' : prop;    

   for (var i=0; i<array.length; i++) {
      if(array[i][prop] === key) {
         var foto_prod_rel = array[i].foto;
         var id_prod_rel = array[i].id;
         if(id_prod_rel != idProdTag){
            var foto_src_prod_rel = '/productos_img/'+foto_prod_rel+'.png';
            var html_related = `<li class="splide__slide splide__slide__img">
            <img data-toggle="modal" onclick="createModalRelAjax('${id_prod_rel}');$('#modal-producto-${id_prod_rel}).modal('show');" data-target="modal-producto-${id_prod_rel}" src="${foto_src_prod_rel}"></li>`;
   
            splideParam.add(html_related);
         }
      }
   }
}
/*
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
         `<div class="modal fade" id="modal-producto-${id_prod_p}" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
         <div class="modal-content">
         <div class="modal-body">
         <div class="row">
         <div class="col-lg-7">
            <div class="img-modal-prod"><img onerror="this.src='/imagen_perfil/generica_prod.jpg'" style="width: 100%;" src="${foto_src_prod_p}" alt="foto_src_prod"></div>
         <hr>
         <div>
            <h5 style="text-align:left">Ficha T&eacute;cnica</h5>
            <table class="tg" style="table-layout: fixed; width: 282px">
            <colgroup>
            <col style="width: 153px">
            <col style="width: 129px">
            </colgroup>
            <tbody>
            <tr>
               <td class="tg-9f3l">ID Producto</td>
               <td class="tg-wo29">${id_prod_json_p}</td>
               </tr>
               <tr>
               <td class="tg-9f3l">Marca</td>
               <td class="tg-wo29">${marca_prod_p}</td>
               </tr>
               <tr>
               <td class="tg-9f3l">Color</td>
               <td class="tg-wo29">${color_prod_p}</td>
               </tr>
               <tr>
               <td class="tg-9f3l">Categoria</td>
               <td class="tg-wo29">21</td>
               </tr>
               <tr>
               <td class="tg-9f3l">Rubro</td>
               <td class="tg-z6p2">15</td>
            </tr>
            </tbody>
            </table>
         </div></div>
         <div class="col-lg-5 col-datos-producto">
         <div>
            <h2>${nombre_prod_p}</h2>
            <p style="font-size: 0.8em; color: grey; font-style: italic">Por: ${nombre_completo_p}</p>
         </div>
         <hr>
         <div>
         <div class="precio-producto-modal"><span data-precio="'${precio_prod_p}'">AR$ ${precio_prod_p}</span></div>
         <div class="shipment-modal-producto">
         <i class="fas fa-truck-loading"></i> Shipment dentro de las 5 d&iacute;as h&aacute;biles
         </div>
         <hr>
         <div class="stock-boton-modal">
            <span>
                  Cantidad&nbsp;
                  <select class="cantidad_value" name="cantidad">
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                     <option value="5">5</option>
                  </select>
                  <input type="hidden" class="id_prod_carrito" name="id" value="${id_prod_json_p}">
            </span>&nbsp;
            <span><button class="btn btn-warning" data-idpublic="${id_public_p}" data-idprod="${id_prod_json_p}">Añadir a Carrito</button></span>
         </div>
         </div>
         <hr>
         <div class="descripcion-modal-producto">
         <strong>Descripcion:</strong>
         <div>${descr_prod_p}</div>
         </div>
         <hr>
         </div>

         <div class="commentbox-container" style="display:none">
            <hr><div class="commentbox media commentbox-id-${id_prod_p}">
               <img class="mr-3 commentbox-user-img" src="" alt="perfil">
               <div class="media-body">
                  <form class="comentario_prod">
                     <div class="textarea-container">
                        <textarea placeholder="Deja un comentario" maxlength="16384"></textarea>
                     </div>
                     <input type="hidden" name="id_producto" value="${id_prod_json_p}">
                     <button class="btn btn-warning">Enviar</button>
                  </form>
               </div>
            </div>
            <div class="comment-count"><span>Comentarios</span></div>
            <div class="commentbox-list-container">
               <div class="commentbox-list media commentbox-id-${id_prod_p}">
                  <img class="mr-3 commentbox-user-img" src="" alt="perfil">
                  <div class="media-body">
                     <p>Ive tried embeding it in the new google sites - the comment box showed up, but required authentication. It would be nice to have it simply allowing anon comments. Yet, once the signin was made, it keeps showing the message "The supplied URL is not a part of this proje - yet everything seems ok in the project config.</p>
                        <div class="commentbox-actions">
                           <span class="actions-name">Nicolas Gómez</span>&nbsp;&middot;&nbsp;
                           <span class="actions-time">1m</span>
                        </div>
                  </div>
               </div>
               //

            </div>
         </div>
         </div></div></div></div></div>`;

         $("body").append(modal_producto_html)

   }
      

}
*/


function getSplideProdPublic(param){
      
   const URL = `/app/producto.php?accion=getproductos&id=${param}`

   fetch(URL).then(res => res.json())
   .catch(error => console.error('Error:', error))
   .then((response) => {
      
      let resp_len = response.mensaje.length
      console.log(response.mensaje)
      
         if(resp_len > 0){
            for(let i = 0; i < resp_len; i++){
               let nombre_prod = response.mensaje[i].titulo || 0;
               let precio_prod = response.mensaje[i].precio || 0;
               let marca_prod = response.mensaje[i].marca || "";
               let color_prod = response.mensaje[i].color || "";
               let descr_prod = response.mensaje[i].descr_producto || "";
               let id_prod_json = response.mensaje[i].id || 0;
               let stock_prod = response.mensaje[i].stock || 0;
               let foto_prod = response.mensaje[i].foto || "";
               let comentarios_obj = response.mensaje[i].comentarios || "";
               let primer_img_split = foto_prod.split(",")[0] || "";
               let primer_img = "/productos_img/"+primer_img_split+".png" || "";
               let nombre_completo = "test";
               let foto_src_prod = `/productos_img/${foto_prod}.png` || "";
               let splide_list = document.querySelector('.splide__list__'+param) || ""
   
               var objParamModal = 
                     {
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
                        i_p : i,
                        comentarios_obj_p : comentarios_obj,
                        foto_prod_p: foto_prod 
                     }
   
               traerModalProducto(objParamModal)

               //si no tiene stock
               if(stock_prod == 0){
                  document.querySelector(".btn-carrito").classList.add("disabled");
               }
         
               let splide_fotos = `<li class="prod-tag-public splide__slide splide__slide__img splide__prodtag">
                  <img onerror="this.src=\'/imagen_perfil/generica_prod.jpg\'" data-toggle="modal" data-target="#modal-producto-${id_prod_json}" src="${primer_img}"></li>`;
                  
               //dibujo tags y list de galeria splide
               splide_list.insertAdjacentHTML("beforeend",splide_fotos);
   
            }   

         }else{
            alert("error publics")
         }
   });//then
}