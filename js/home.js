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
            // var foto_src = '/publicaciones_img/'+imagen_id;
            var foto_src = '/publicaciones_img/'+imagen_id;
            var img_base_public = getImagen(foto_src);

            if(id_public_cat == "17" ){
                var public_html = '<div><div class="content-col-div"><div class="overlay-public">'+
                '<div class="text-overlay">'+
                '<span class="text-overlay-link">'+
                    '<a href="/ampliar-publicacion-home.html?id='+id_public+'&accion=ampliar&cat='+id_public_cat+'"><i title="Ver Publicaci&oacute;n" class="fas fa-eye"></i></a>'+
                '</span>'+
                '&nbsp;&nbsp;'+
                '<span class="text-overlay-link">'+
                    '<a href="#"><i class="fas fa-heart"></i></a>'+
                '</span>'+
                '</div>'+
                '</div>'+
                '<img src="'+img_base_public+'"></img></div></div>';

                $(".item1").append(public_html)
            }
            

            
        }
    }

});