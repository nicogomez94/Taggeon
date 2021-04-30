$(document).ready(function(){

    var sizePublic = jsonData.publicaciones.length;
    
    if(sizePublic>0){
        for(var i=0; i<sizePublic; i++){
          
            var id_public = jsonData.publicaciones[i].id || 0;
            var id_public_cat = jsonData.publicaciones[i].id_publicacion_categoria || "";
            var nombre_public = jsonData.publicaciones[i].publicacion_nombre || "";
            var descr_public = jsonData.publicaciones[i].publicacion_descripcion || "";
            var imagen_id = jsonData.publicaciones[i].foto || 0;
            var producto = jsonData.publicaciones[i].pid || 0;
            var cat_ampliar_home = jsonData.cat || 0;
            var arrCat = jsonData.categoria || 0;
            var foto_src = '/publicaciones_img/'+imagen_id+'.png' || 0;//viene siempre png?
            var winLoc = window.location.pathname || "";
            if(cat_ampliar_home == 0) cat_ampliar_home = id_public_cat //si viene por mis-public lo igualo asi no putea

            if(cat_ampliar_home == id_public_cat){

               //dibujo la cat arriba de todo
               var objCat = arrCat.find(o => o.id === cat_ampliar_home) || "";
               var nameCat = objCat.nombre || "";
               $(".title-cat").html(nameCat);

               //link NEXT cat
               var cat_ampliar_home_next = parseInt(cat_ampliar_home) + 1;
               var objCatNext = arrCat.find(o => o.id === cat_ampliar_home_next.toString()) || 0;
               var objCatNextId = objCatNext.id || 0;
               $(".next-cat a").attr("href",'/ampliar-publicacion-home.html?accion=ampliar&cat='+objCatNextId);
               

               //link PREV cat
               var cat_ampliar_home_prev = parseInt(cat_ampliar_home) - 1;
               var objCatPrev = arrCat.find(o => o.id === cat_ampliar_home_prev.toString()) || 0;
               var objCatPrevId = objCatPrev.id || 0;
               $(".prev-cat a").attr("href",'/ampliar-publicacion-home.html?accion=ampliar&cat='+objCatPrevId);

               //rellenos costados
               $(".up_relleno_1_der").html('<span class="inside_up_relleno">'+objCatNext.nombre+'</span>');
               $(".up_relleno_2_izq").html('<span class="inside_up_relleno">'+objCatPrev.nombre+'</span>');
               


            var html_public = '<div id="ancla-desde-home-'+id_public+'" class="public-ampliar public-actual test2">'+
                               '<div class="header-public">'+
                                  '<span class="img-perfil-public">'+
                                     '<img src="" alt="img-perfil">'+
                                  '</span>'+
                                  '<span class="title-public"></span>'+
                                  '<span class="opciones-public"><i class="fas fa-cog"></i></span>'+
                               '</div>'+
                            '<div class="bodyimg-public-container bodyimg-public-container-'+i+'">'+
                               //'<div><img src="../../img/arrrrte.jpg" alt=""></div>'+
                                  '<img class="imagen-public-'+imagen_id+'" src="'+foto_src+'" alt="">'+
                                  '<div class="tag-container tag-container-'+i+'"></div>'+

                              // '<hr>'+
                            
                              '</div>'+


                              '<div id="ancla-'+i+'" class="productos-public productos-public-'+i+'">'+
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
                                             '<ul class="splide__list splide_list_related"></ul>'+
                                          '</div>'+
                                       '</div>'+
                                    '</div>'+
                                 '</div>'+


                              '<div class="info-public">'+
                                 '<div class="social-public">'+
                                       '<span><i class="fas fa-heart"></i></span>'+
                                       '<span><i class="fas fa-comment-dots"></i></span>'+
                                       '<span class="share-sm"><i class="fas fa-paper-plane"></i></span>'+
                                 '</div>'+
                                 '<div class="datos-public">'+
                                 '<div class="info-titulo-public">'+nombre_public+'</div>'+
                                 '<div class="info-tipo-public"><a href="#">Arte</a> | <a href="#">Diseño</a> | <a href="#">Ambientes</a></div>'+
                                 '<div class="info-descr-public">'+descr_public+'</div><hr>'+
                              '</div>'+
                           '</div>'

                           
            $(".insert-public").append(html_public);
            
            
            
            
            //lo mando a la public seleccionada
            //winLoc.split("=")[1].split("&")[0];
            //document.location.href="#ancla-desde-home-"+winLoc;

            //imgperfil sacada del menu top
            var img_perfil = $(".img-perfil-usuario-drop").attr("src");
            $(".img-perfil-public img").attr("src", img_perfil);
            $(".test-suggest").attr("src", img_perfil);
        
            //nombre perfil
            var nombre_perfil = $(".user-name-perfil").text();
            $(".title-public").html(nombre_perfil);

            ///DIBUJO PINES
            var producto_parse = JSON.parse(producto);
            var producto_parse_size = producto_parse.length;

            for(var x=0; x<producto_parse_size; x++){
               var id_prod = producto_parse[x].name;
               var coords = producto_parse[x].value;
               var ycoord = coords.split("-")[0];
               var xcoord = coords.split("-")[1];
               var cat_actual_prod = "1";
               var allprod = jsonData.productos || [];
      
               //checkeo si es el mismo id de tag y prod
               var arr = jsonData.productos;
               var obj = arr.find(o => o.id === id_prod);

               //checkeo que cat es para mostrar relacionados
               //var objRel = arr.find(o => o.id === cat_actual);
                  //dibujo modales
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
                     var nombre_completo = jsonData.nombre+""+jsonData.apellido;
                     var foto_src_prod = '/productos_img/'+foto_prod+'.png';

                           var modal_html =  
                              '<div class="modal fade" id="modal-producto-'+id_prod+'" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">'+
                              '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">'+
                              '<div class="modal-content">'+
                              '<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->'+
                              '<div class="modal-body">'+
                              '<div class="row">'+
                              '<div class="col-lg-7">'+
                                 '<div class="img-modal-prod"><img style="width: 100%;" src="'+foto_src_prod+'" alt="foto_src_prod"></div>'+
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

                           //related (por ahora traigo todos los prod)
                           for(var y=0; y<allprod.length; y++){

                              var obj = allprod.find(o => o.marca === "Gucci");
                              console.log(obj.marca)

                              var foto_prod_rel = jsonData.productos[y].foto;
                              var foto_src_prod_rel = '/productos_img/'+foto_prod_rel+'.png';
                              var html_related = '<li class="splide__slide"><img data-toggle="modal" src="'+foto_src_prod_rel+'"></li>';
                              //encontrar el id de catdel prod y suar ese y fue
                              $(".splide_list_related").append(html_related);
                           }
                     
                  }
                
                  var splide_fotos = '<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-'+id_prod+'" src="'+foto_src_prod+'"></li>';
                  $(".splide__list__"+i).append(splide_fotos);

                  
            
                  //dibujo tags
                  var tag_html = '<div href="ancla-'+i+'" class="tagg tagg-'+id_prod+'" style="top:'+ycoord+'%; left: '+xcoord+'%">'+
                              '<span><i class="fas fa-tags"></i></span></div>';
                  
                  $(".tag-container-"+i).append(tag_html);

                  //click en tag
                  $(".bodyimg-public-container-"+i).on("click", ".tagg", function(){
                     var prod_public = $(this).parent().parent().parent().find(".productos-public");
                     prod_public.toggle(100);
                     $('html,body').animate({
                        scrollTop: prod_public.offset().top - 130
                     }, 200);
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

       
   }//fin if principal


   //lo mando a la public que se selecciono desde home
   if(winLoc=="/ampliar-publicacion-home.html"){
      console.log(winLoc)
      var WinLocSplit = window.location.href.split("=")[1].split("&")[0] || "";
      $('html,body').animate({
         scrollTop: $("#ancla-desde-home-"+WinLocSplit).offset().top - 80
      }, 0);
   }

////////////////CARRITO





//FIN READY
});//FIN READY
//FIN READY