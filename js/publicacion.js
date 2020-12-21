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
            
            var foto_src = '/publicaciones_img/'+imagen_id;
            var img_base_posta_public = getImagen(foto_src);
        
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
                                  '<img class="imagen-public-'+imagen_id+'" src="'+img_base_posta_public+'" alt="">'+
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
                                           '<ul class="splide__list"></ul>'+
                                        '</div>'+
                                     '</div>'+
                                     /**/
                               '<hr><div class="productos-titulo-public">Productos relacionados:</div><br>'+
                                     '<div class="splide splide-prod-'+i+'">'+
                                        '<div class="splide__track">'+
                                           '<ul class="splide__list"></ul>'+
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
                var img_base_posta = getImagen(foto_src);
                    var modal_html =  
                        '<div class="modal fade" id="modal-producto-'+index+'" tabindex="-1" role="dialog" aria-labelledby="modal-producto-title" aria-hidden="true">'+
                        '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">'+
                        '<div class="modal-content">'+
                        '<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->'+
                        '<div class="modal-body">'+
                        '<div class="row">'+
                        '<div class="col-lg-7">'+
                            '<div class="img-modal-prod"><img style="width: 100%;" src="'+img_base_posta+'" alt=""></div>'+
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
                        '<div class="precio-producto-modal"><span>'+precio_prod+'</span></div>'+
                        '<div class="shipment-modal-producto">'+
                        '<i class="fas fa-truck-loading"></i> Shipment dentro de las 5 dias habiles'+//hardcodeado
                        '</div>'+
                        '<hr>'+
                        '<div class="stock-boton-modal">'+
                        '<span>'+
                        'Cantidad&nbsp;'+
                        '<select name="" id="">'+
                        '<option value="1">1</option>'+//hardcodeado
                        '</select>'+
                        '</span>&nbsp;'+
                        '<span><a href="#" class="btn btn-warning">Añadir a Carrito</a></span>'+
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
                    var splide_fotos = '<li class="splide__slide"><img data-toggle="modal" data-target="#modal-producto-'+index+' src=""></li>'+
                    $(".splide__list").append(splide_fotos)
                    //para que el prod muestre el modal correspondiente
                    //$(".splide-prod-tag-"+i+" img").attr("data-target","#modal-producto-"+index);
    
             }//fin prod
             var tag_html = '<div class="tagg tagg-'+id_prod+'" style="top:'+ycoord+'; left: '+xcoord+'">'+
                            '<span><i class="fas fa-tags"></i></span></div>';
             
             $(".tag-container-"+i).append(tag_html);
             $(".bodyimg-public-container-"+i).on("click", ".tagg", function(){
                var prod_public = $(this).parent().parent().find(".productos-public");
                prod_public.toggle(100);
                //data-toggle="modal" data-target="#modal-producto-'+id_prod+'"
             });
    
          
             
          }
          //productos en esta public
          new Splide( '.splide-prod-tag-'+i, {
             perPage: 6,/*medio extraño*/
             rewind : true,
             pagination: false
          } ).mount();
          //productos relacionados
          new Splide( '.splide-prod-'+i, {
             perPage: 6,/*medio extraño*/
             rewind : true,
             pagination: false
          }).mount();
          
       }
    }

       
});