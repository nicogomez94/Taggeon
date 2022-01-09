/*!
* dropPin - because image maps are icky
* http://duncanheron.github.com/dropPin/
*
*/
(function( $ ){

	$.fn.dropPin = function(method) {

		var test = $("#map").css("background-image");

		var defaults = {
		fixedHeight: 'auto',
		fixedWidth: 'fit-content',//antes 100%
		dropPinPath: '/js/dropPin/',
		pin: 'dropPin/dot-circle-solid.svg',
		backgroundImage: test,
		backgroundColor: 'transparent',
		xoffset : 0,
		yoffset : 0, //need to change this to work out icon heigh/width then subtract margin from it
		cursor: 'crosshair',
		pinclass: '',
		userevent: 'click',
		hiddenXid: '#xcoord', //used for saving to db via hidden form field
		hiddenYid: '#ycoord', //used for saving to db via hidden form field
		pinX: false, //set to value if you pass pin co-ords to overirde click binding to position
		pinY: false, //set to value if you pass pin co-ords to overirde click binding to position
		pinDataSet: '' //array of pin coordinates for front end render
	}

	

	var methods = {
		/*init: function(options) {

			var options =  $.extend(defaults, options);
			var thisObj = this;

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var i = 10;
			thisObj.on(options.userevent, function (ev) {

				$('.'+options.pinclass).remove();

				i = i + 10;
				var $img = $(thisObj);
				var offset = $img.offset();
				var x = ev.pageX - offset.left;
				var y = ev.pageY - offset.top;

				var xval = (x - options.xoffset);
				var yval = (y - options.yoffset);
				var imgC = $('<img class="pin '+options.pinclass+'">');
				imgC.css('top', yval+'%');
				imgC.css('left', xval+'%');
				imgC.css('z-index', i);
				
				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);

				var hiddenCtl= $('<input type="hidden" name="hiddenpin" class="pin '+options.pinclass+'">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(x + "#" + y);
		        hiddenCtl.appendTo(thisObj);

			});

		},*/
		dropMulti: function(options) {
			var options =  $.extend(defaults, options);
			var thisObj = this;
			var popup_cont = $("#popup-prod-cont");
			var protector_cont = $(".click-protector-cont");
			var popup_overlay = $(".popup-prod-overlay");
			var popup_prod = $(".popup-producto");
			var salirPopup = document.getElementById("salir-popup");
			var xval;
			var yval;
			
			//primero checkeo si viene algo por el param "pinDataSet", si viene dibujo tags
			//si pinDataSet viene como string es que no tiene data

			if(typeof options.pinDataSet !== "string"){
				var tagg_length = (options.pinDataSet).markers.length;
				if(tagg_length>0){
					for(var i=0; i<tagg_length; i++){
						var dataPin = options.pinDataSet.markers[i];
						var id_prod = dataPin.name;
						var coords = dataPin.value;
						var ycoord = coords.split("-")[0];
						var xcoord = coords.split("-")[1];
		
						//img
						var imgC = $('<img data-close="'+ycoord+'-'+xcoord+'" class="pin '+ycoord+"-"+xcoord+'">');
						imgC.css('top', ycoord+'%');
						imgC.css('left', xcoord+'%');
						imgC.css('z-index', i);
						imgC.attr('src',  options.pin);
						imgC.attr('title',  dataPin.title);
						imgC.appendTo(this);

						//input
						var hiddenCtl= $('<input type="hidden" name="" class="pin '+ycoord+"-"+xcoord+'" data-close="'+ycoord+'-'+xcoord+'">');
						hiddenCtl.val(ycoord+"-"+xcoord);
						hiddenCtl.appendTo(this);
						
						var click_protector_html = `<div style="top:${ycoord}%; left:${xcoord}%" data-close="${ycoord}-${xcoord}" class="click-protector ${ycoord}-${xcoord}">
						<div data-close="${ycoord}-${xcoord}" class="salir-popup-single"><i class="fas fa-times-circle"></i></div></div>`;
		
						//añado click protector
						$(".click-protector-cont").append(click_protector_html);
		
					}
				}
			}
			
			thisObj.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : options.backgroundImage,'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var i = 10;
			thisObj.on(options.userevent, function (ev) {

				i = i + 10;
				var $img = $(thisObj);
				var offset = $img.offset();
				var x = ev.pageX - offset.left;
				var y = ev.pageY - offset.top;

				//parseado por mi para que x no hinchee
				xval = parseInt(x - options.xoffset);
				yval = parseInt(y - options.yoffset);

				//para pasarlo a porcentaje y despues integer -funca
				xval = parseInt(xval/$("#map").width() * 100);
				yval = parseInt(yval/$("#map").height() * 100);

				//para que quede bien centrado el popup-prod
				//se deja en pixels porque sino se va proporcionalmente al carajo
				var yval_pop = Math.round(y) + 17;//hardhard esta ya definido en el csss
				var xval_pop = Math.round(x) + 17;//hard esta ya definido en el csss

				// var imgC = $('<img data-close="'+yval_pop+'-'+xval_pop+'" class="pin '+yval+"-"+xval+'">');
				var imgC = $('<img data-close="'+yval+'-'+xval+'" class="pin '+yval+"-"+xval+'">');
				imgC.css('top', yval+'%');
				imgC.css('left', xval+'%');
				imgC.css('z-index', i);

				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				//console.log(ev.target);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);
				
				// add hidden fields - can use these to save to database
				//name vacio porque se llena despues con el idproducto
				var hiddenCtl= $('<input type="hidden" name="" class="pin '+yval+"-"+xval+'" data-close="'+yval+'-'+xval+'">');
		        //hiddenCtl.css('top', y);
		        //hiddenCtl.css('left', x);
		        hiddenCtl.val(yval+"-"+xval);
				hiddenCtl.appendTo(thisObj);
				
				//hago esto porque nescesito sacar el valor de una etiqueta,m porque por js siemnpre va venir uno distinto
				salirPopup.setAttribute("data-close",yval+'-'+xval)

				popup_overlay.show(0,function(){
					
					//se deja en pixels porque sino se va proporcionalmente al carajo
					popup_cont.attr("data-close",yval+'-'+xval)
					popup_cont.css({
						'top': yval_pop+'px',
						'left': xval_pop+'px'
					});
					popup_prod.css({
						'top': yval+'%',
						'left': xval+'%'
					});

					//si se sale el popup del viewport le saco el resto vs wl width del navegdador
					//si es mobile lo hago que sea todo el ancho

					if (window.matchMedia("(max-width: 768px)").matches) {
						popup_cont.css("width","100%")
						popup_cont.css("left","unset")
					}else{
						if(!inViewport(salirPopup)){
	
							let r = salirPopup.getBoundingClientRect();
							let exceso_left = r.left;
							let html = document.documentElement;
							let clientWidth = html.clientWidth;
							let resta = (exceso_left - clientWidth) * 2;
	
							// console.log("----SE EXCEDIO-----",resta)
							//console.log("r.left EN FUNC",r.left)
							//console.log("clientWidth EN FUNC",html.clientWidth)
							
							var ppc = document.querySelector("#popup-prod-cont");
							var ppc_left = ppc.style.left.split("px")[0];
							ppc_left_posta = parseInt(ppc_left) - resta;
							//ppc.style.right = ppc_left_posta+"px";
							ppc.style.left = ppc_left_posta;
							
						}
					}
					
					
				});
				


			});

			//genera tag con producto y futuro click protector
			popup_cont.on("click", ".splide__slide", function(){
				//le paso el id prod ahora porque sino despues no puede hacer this
				let id_prod_data = $(this).data("id-prod");
				enlazarTag(yval,xval,id_prod_data);
			});
			//para salir de la sel de productos y eliminar pin
			popup_cont.on("click","#salir-popup", function(){
				var data_close = this.dataset.close
				console.log(data_close)
				popup_cont.parent().hide();//no lo elimino
				borrarContenido(data_close)
			});

			//click para que aparezca la cruz en el tag
			protector_cont.on('click', '.click-protector', function() {
				$(this).find(".salir-popup-single").show();
			});
			//para borrar tag y su protector
			protector_cont.on("click",".salir-popup-single", function(){
				var data_close = $(this).data("close");
				borrarContenido(data_close)
			});
		},
		/*showPins: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});

			for(var i=0; i < (options.pinDataSet).markers.length; i++)
			{
				var dataPin = options.pinDataSet.markers[i];
				var id_prod = dataPin.name;
				var coords = dataPin.value;
				var ycoord = coords.split("-")[0];
				var xcoord = coords.split("-")[1];

				var imgC = $('<img class="pin '+ycoord+'-'+xcoord+'">');
				imgC.attr('src',  options.pin);
				imgC.attr('title',  dataPin.title);
				imgC.appendTo(this);
				
				var click_protector = '<div class="click-protector '+coords+'">'+
											'<div class="salir-popup-single"><i class="fas fa-times-circle"></i></div></div>';

				$(".click-protector-cont").append(click_protector);
				$("."+ycoord+"-"+xcoord).css("top",ycoord+"%");
				$("."+ycoord+"-"+xcoord).css("left",xcoord+"%");
				$("."+ycoord+"-"+xcoord+" .salir-popup-single").css("display","none");

			}

		}*/
	};

	if (methods[method]) {

		return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

	} else if (typeof method === 'object' || !method) {

		return methods.init.apply(this, arguments);

	} else {

		alert("method does not exist");

	}


}

})( jQuery );


function enlazarTag(yval,xval,id_prod){
    $(".popup-prod-overlay").hide();

    var pin_a_namear = $("#map").find("."+yval+"-"+xval);
    var click_protector_html = `<div style="top:${yval}%; left:${xval}%" data-close="${yval}-${xval}" class="click-protector ${yval}-${xval}"><div data-close="${yval}-${xval}" class="salir-popup-single"><i class="fas fa-times-circle"></i></div></div>`;
    
    pin_a_namear.attr("name",id_prod);

    //añado click protector
    $(".click-protector-cont").append(click_protector_html);
}

function borrarContenido(data_close){
	//#popup-prod-cont, #salir-popup no los toco porque siempre tienen que estar
	var a_borrar = $("[data-close='"+data_close+"']").not("#popup-prod-cont, #salir-popup");
	a_borrar.remove();
}