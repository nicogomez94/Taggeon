$(document).ready(function(){

var sizePublic = jsonData.publicaciones.length;
    
if(sizePublic>0){

    var public_cat_size = jsonData.categoria.length;

    //recorre todas las cat y primero dibujo el item de cat
    for(var i=0; i<public_cat_size; i++){


        var json_cat = jsonData.categoria[i].id || 0;
        var json_cat_nombre = jsonData.categoria[i].nombre || "";
        

        var item_html = '<div class="item item-cat-'+json_cat+'">'+
                            '<div class="titulo-col-cont">'+
                                '<div class="titulo-col random-p-'+i+'"><span class="span-titulo">'+json_cat_nombre+'</span></div>'+
                            '</div>'
                        '</div>'
        
        $(".items").append(item_html);
        
        //numero random pattern por ahora
        var random = Math.floor(Math.random() * 7);
        if (random == 0) {random=random+1}
        $(".random-p-"+i).addClass("pattern"+random);

        //recorre solo si la json_cat es igual a la de puid_public_catblic

        for(var x=0; x<sizePublic; x++){

            var id_public = jsonData.publicaciones[x].id || '';
            var id_public_cat = jsonData.publicaciones[x].id_publicacion_categoria || 0;
            var nombre_public = jsonData.publicaciones[x].publicacion_nombre || '';
            var descr_public = jsonData.publicaciones[x].publicacion_descripcion || '';
            var imagen_id = jsonData.publicaciones[x].foto || '';
            var producto = jsonData.publicaciones[x].pid || 0;
            var foto_src = '/publicaciones_img/'+imagen_id+'.png' || 0;//viene siempre png?

            if(json_cat == id_public_cat){

                var public_html = 
                    '<div>'+
                        '<div class="content-col-div content-col-div-'+id_public+' cat-'+id_public_cat+'">'+
                            '<div class="overlay-public">'+
                            '<a class="link-ampliar-home" href="/ampliar-publicacion-home.html?id='+id_public+'&accion=ampliar&cat='+id_public_cat+'"></a>'+
                                '<div class="text-overlay">'+
                                    '<span class="text-overlay-link">'+
                                        '<a href="#"><i class="fas fa-share-alt"></i></a>'+
                                    '</span>'+
                                    '&nbsp;&nbsp;'+
                                    '<span class="text-overlay-link">'+
                                        '<a href="#"><i class="fas fa-heart"></i></a>'+
                                    '</span>'+
                                '</div>'+
                            '</div></a>'+
                        '<img src="'+foto_src+'" alt="img-'+imagen_id+'">'+
                        '</div>'+
                    '</div>';

                $(".item-cat-"+json_cat).append(public_html)
                
            }
            /*$(".content-col-div-"+id_public).on("click",".overlay-public",function(){
                window.location.replace('/ampliar-publicacion-home.html?id='+id_public+'&accion=ampliar&cat='+id_public_cat)
            });*/
        }
        
    }
        
            
}



//fin ready
});