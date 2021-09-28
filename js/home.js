document.addEventListener("DOMContentLoaded", function(event) { 
    
    const dataPaging = {cantidad : 50}
    const url = "/app/paginador_home.php?cant="+dataPaging.cantidad;
    getData(url,dataPaging);
    
    let options = {
        root: null,
        rootMargins: "0px",
        threshold: 0.5
    };
    const observer = new IntersectionObserver(handleIntersect, options);
    const footer = document.querySelector("footer")
    observer.observe(footer);
    
    function handleIntersect(entries) {
        if (entries[0].isIntersecting) {
            console.warn("intersect viewport");
            getData(url,dataPaging);
        }
    }
    function getData(url,dataPaging) {
        let main = document.querySelector("main");
        console.log("fetch");
        fetch(url)
            .then(response => response.json())
            .then(data => {
                var cant = parseInt(data.length);
                dataPaging.cantidad = data.length;
                console.log(dataPaging.cantidad);
                console.log(data);
            });
    }

    if(typeof jsonData !== "undefined"){
        
        var reverse = jsonData.publicaciones.reverse();
        var sizePublic = reverse.length;
        
        if(sizePublic>0){
            
            var escena_json = JSON.parse(escena);
            var escena_json_length = escena_json.length;
            
            //recorre todas las cat y primero dibujo el item de cat
            for(var i=0; i<escena_json_length; i++){
                
                
                var id_padre = escena_json[i].id_padre;
                
                if(id_padre == null){
                    
                    var json_cat = escena_json[i].id || 0;
                    var json_cat_nombre = escena_json[i].nombre || "";
                    
                    var item_html = '<li class="splide__slide item item-cat-'+json_cat+'">'+
                    '<div class="titulo-col-cont" onclick="window.location.replace(\''+window.location.href+'ampliar-publicacion-home.html?accion=ampliar&cat='+json_cat+'\')">'+
                    '<div class="titulo-col random-p-'+i+'"><span class="span-titulo">'+json_cat_nombre+'</span></div>'+
                    '</div>'
                    '</li>'
                    
                    $(".splide__list__home").append(item_html);
                    
                    //numero random pattern por ahora
                    var random = Math.floor(Math.random() * 7);
                    if (random == 0) {random=random+1}
                    $(".random-p-"+i).addClass("pattern"+random);
                    
                    //recorre solo si la json_cat es igual a la de puid_public_catblic
                    
                    for(var x=0; x<sizePublic; x++){
                        
                        var id_public = jsonData.publicaciones[x].id || '';
                        var id_public_cat = jsonData.publicaciones[x].subescena1 || 0;
                        var nombre_public = jsonData.publicaciones[x].publicacion_nombre || '';
                        var descr_public = jsonData.publicaciones[x].publicacion_descripcion || '';
                        var imagen_id = jsonData.publicaciones[x].foto || '';
                        var producto = jsonData.publicaciones[x].pid || 0;
                        var foto_src = '/publicaciones_img/'+imagen_id+'.png' || 0;//viene siempre png?
                        var favorito = jsonData.publicaciones[x].favorito || 0;
                        var fav_accion = "";
                        var full_url = '/ampliar-publicacion-home.html?id='+id_public+'&accion=ampliar&cat='+id_public_cat;
                        
                        
                        if(json_cat == id_public_cat){
                            
                            var public_html = 
                            '<div>'+
                            '<div class="content-col-div content-col-div-'+id_public+' cat-'+id_public_cat+'">'+
                                        '<div class="overlay-public">'+
                                        '<a class="link-ampliar-home" href="'+full_url+'"></a>'+
                                        '<div class="public-title-home">'+nombre_public+'</div>'+
                                        '<div class="text-overlay">'+
                                        '<span class="text-overlay-link share-sm" onclick="pathShareHome(\''+full_url+'\')">'+
                                        '<a href="#"><i class="fas fa-share-alt"></i></a>'+
                                        '</span>'+
                                        '&nbsp;&nbsp;'+
                                        '<span class="text-overlay-link text-overlay-link-'+id_public+'">'+
                                        //'<label><input onclick="favoritos('+id_public+',\''+fav_accion+'\')" type="checkbox"><div class="like-btn-svg"></div></label>'+
                                        
                                        '</span>'+
                                        '</div>'+
                                        '</div>'+
                                        '<img src="'+foto_src+'" alt="img-'+imagen_id+'">'+
                                        '</div>'+
                                        '</div>';
                                        
                                        $(".item-cat-"+json_cat).append(public_html)
                                        
                                        if (favorito==null || favorito == 0) {
                                            fav_accion="alta";
                                            var fav_html = '<a href="#"><i class="fas fa-heart" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></i></a>'
                                            $(".text-overlay-link-"+id_public).append(fav_html)
                                        }else{
                                            fav_accion="eliminar";
                                            var fav_html = '<a href="#"><i class="fas fa-heart fav-eliminar" onclick="favoritos('+id_public+',\''+fav_accion+'\');$(this).toggleClass(\'fav-eliminar\')"></i></a>'
                                            $(".text-overlay-link-"+id_public).append(fav_html)
                                        }
                                        
                                        
                                    }
                                }
                            }
            }
            
            
        }
        
        
        /*$('.share-sm').click(function(e) {
            e.preventDefault();
            console.log("overlay")
            $(".overlay").show();
            
            $('#cerrar-light').click(function() {
                $('.overlay').css("display", "none");
            });
        });*/
        
        
        /*buscador*/
        $("#buscador-titulo-input").keyup(function(){
            activarBuscador($(this));
        });
        //fin ready
        
    }
});
