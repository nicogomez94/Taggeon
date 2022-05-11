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


function getSplideProdPublic(param,name_publicador,id_publicador_param,index,thisObj){
      
   openTag(index);
   $("#ancla-desde-home-"+param).find(".tagg").attr('onclick','openTag('+index+')');

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
               let nombre_completo = name_publicador;
               let id_publicador = id_publicador_param;
               let foto_src_prod = `/productos_img/${foto_prod}.png` || "";
               let splide_list = document.querySelector('.splide__list__'+param) || ""
               let attrFetchCarrito = "fetchIdCarrito('"+param+"','"+id_prod_json+"','1')";
               //
               let cant = document.querySelector(".cantidad_value");
               let value_cant = document.querySelector(".cantidad_value").value;
               let btn_carr = document.querySelector(".btn-carrito")
   
               
               //dibujo modal
               dibujarCarousel(id_prod_json,foto_prod)
               
               //DIBUJO DATOS
               $("#descr-data").html(descr_prod)
               $("#titulo-prod").html(nombre_prod)
               $(".by-prod").html("Por: "+marca_prod)
               $(".precio-producto-modal").html("$. "+precio_prod)
               

               $(".by-prod").attr("href",`/ampliar-usuario-redirect.html?id_usuario=${id_publicador}`)
               btn_carr.setAttribute("onclick", attrFetchCarrito);
         
         
               //si cambia la cantidad
               cant.addEventListener("change",function(){
                  btn_carr.setAttribute("onclick", "fetchIdCarrito('"+param+"','"+id_prod_json+"','"+this.value+"')")
               })

               //si no tiene stock
               if(stock_prod == 0){
                  document.querySelector(".btn-carrito").classList.add("disabled");
               }
         
               let splide_fotos = `<li class="prod-tag-public splide__slide splide__slide__img splide__prodtag">
                  <img onerror="this.src=\'/imagen_perfil/generica_prod.jpg\'" data-toggle="modal" data-target="#modal-producto" src="${primer_img}"></li>`;
                  
               //dibujo tags y list de galeria splide
               splide_list.insertAdjacentHTML("beforeend",splide_fotos);
   
            }   

         }else{
            alert("error publics AMPLIAR")
         }
   });//then
}