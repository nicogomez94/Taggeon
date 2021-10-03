document.addEventListener("DOMContentLoaded", function(event) { 

    if(typeof jsonData !== "undefined"){
        
        
        
        
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
