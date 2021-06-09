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
		fixedWidth: '700px',//antes 100%
		dropPinPath: '/js/dropPin/',
		pin: 'dropPin/defaultpin@2x.png',
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
		init: function(options) {

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

				// add hidden fields - can use these to save to database
				var hiddenCtl= $('<input type="hidden" name="hiddenpin" class="pin '+options.pinclass+'">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(x + "#" + y);
		        hiddenCtl.appendTo(thisObj);

			});

		},
		dropMulti: function(options) {
			var options =  $.extend(defaults, options);
			var thisObj = this;

			thisObj.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : options.backgroundImage,'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var i = 10;
			thisObj.on(options.userevent, function (ev) {

				i = i + 10;
				var $img = $(thisObj);
				var offset = $img.offset();
				var x = ev.pageX - offset.left;
				var y = ev.pageY - offset.top;

				//parseado por mi par que x no hinchee
				var xval = parseInt(x - options.xoffset);
				var yval = parseInt(y - options.yoffset);

				//para pasarlo a porcentaje y despues integer -funca
				xval = parseInt(xval/$("#map").width() * 100);
				yval = parseInt(yval/$("#map").height() * 100);

				// var imgC = $('<img class="pin '+yval+"-"+xval+'">');
				var yval_pop = Math.round(y) + 17;
				var xval_pop = Math.round(x) + 17;
				var imgC = $('<img data-close="'+yval_pop+'-'+xval_pop+'" class="pin '+yval+"-"+xval+'">');
				imgC.css('top', yval+'%');
				imgC.css('left', xval+'%');
				imgC.css('z-index', i);

				// console.log(left_ppc)

				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				//console.log(ev.target);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);
				
				// add hidden fields - can use these to save to database
				var hiddenCtl= $('<input type="hidden" name="" class="pin '+yval+"-"+xval+'" data-close="'+yval_pop+'-'+xval_pop+'">');
				// var hiddenCtl= $('<input type="hidden" name="hiddenpin-'+xval+yval+'" class="pin">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(yval+"-"+xval);
				hiddenCtl.appendTo(thisObj);
				
				// muestro popup para producto
				var popup_overlay = $(".popup-prod-overlay");
				var popup_cont = $("#popup-prod-cont");
				var popup_prod = $(".popup-producto");

				

				popup_overlay.show(0,function(){

					/*var body_w = $('body').width();
					var popup_cont = $('#popup-prod-cont');
					var ppc_width = popup_cont.width();
					var offset_left = popup_cont.offset().left + ppc_width;
					var body_minus = body_w - 200
				
					if (offset_left > body_minus){
						popup_cont.attr("data-close",yval_pop+'-'+xval_pop)
						popup_cont.addClass('over')
						popup_cont.css({
							'top': yval_pop+'px',
							'left': xval_pop+'px'
						});
					}else{
						popup_cont.attr("data-close",yval_pop+'-'+xval_pop)
						popup_cont.removeClass('over')
						popup_cont.css({
							'top': yval_pop+'px',
							'left': xval_pop+'px'
						});
					}*/
					
					popup_cont.attr("data-close",yval_pop+'-'+xval_pop)
					popup_cont.css({
						'top': yval_pop+'px',
						'left': xval_pop+'px'
					});
					popup_prod.css({
						'top': yval_pop+'%',
						'left': xval_pop+'%'
					});

					/***/
					//si se sale el popup del viewport invierto los css
					var salirPopup = document.getElementById("salir-popup");

					if(!inViewport(salirPopup)){
						console.log(inViewport(salirPopup))
						
						var sp = $("#salir-popup");
						var sp_left = sp.css("left");
						sp.css("right",sp_left);
						sp.css("left","unset");

						var ppc = $("#popup-prod-cont");
						var ppc_left = ppc.css("left").split("px")[0];
						ppc_left_posta = parseInt(ppc_left) - 300;
						ppc.css("right",ppc_left_posta+"px");
						ppc.css("left","unset");
						
					}
					
				});
				
				

			});
			//genera producto y futuro click protector
			$("#popup-prod-cont").on("click", ".nombre-producto", function(){
				            
				$(".popup-prod-overlay").hide();
				// var segunda_clase = $(this).attr('class').split(' ')[1];
				var id_producto = $(this).attr('class').split(' ')[1];
				var box_y_prod = $(this).parent().parent().parent().parent().parent().css("top").split('%')[0];
				var box_x_prod = $(this).parent().parent().parent().parent().parent().css("left").split('%')[0];


				/*para que quede centrado*/
				var box_y_prod_posta = box_y_prod - 20;
				var box_x_prod_posta = box_x_prod - 20;

				var pin_a_namear = $("#map").find("."+box_y_prod+"-"+box_x_prod);//1 porque hay 2
				pin_a_namear.attr("name",id_producto);

				var click_protector = '<div class="click-protector '+box_y_prod+"-"+box_x_prod+'">'+
											'<div class="salir-popup-single"><i class="fas fa-times-circle"></i></div></div>';

				$(".click-protector-cont").append(click_protector);
				$("."+box_y_prod+"-"+box_x_prod).css("top",box_y_prod_posta+"px");
				$("."+box_y_prod+"-"+box_x_prod).css("left",box_x_prod_posta+"px");
				$("."+box_y_prod+"-"+box_x_prod+" .salir-popup-single").css("display","none");

			});
			//para salir de la sel de productos y eliminar pin
			$("#popup-prod-cont").on("click","#salir-popup", function(){
				//hago esto porque sino con css() me toma con pixels
				var data_close = $(this).parent().attr("data-close");
				var box_y = data_close.split("-")[0]//.match(/[0-9]+/g)[0];
				var box_x = data_close.split("-")[1]//.match(/[0-9]+/g)[0];

				var pin_a_borrar = $("#map").find("[data-close='"+box_y+"-"+box_x+"']");
				pin_a_borrar.remove();
				$(".popup-prod-overlay").hide();

			});
			//click para que aparezca la cruz
			$(".click-protector-cont").on('click', '.click-protector', function() {
				$(this).find(".salir-popup-single").show();
			});
			//para borrar el single pin
			$(".click-protector-cont").on("click",".salir-popup-single", function(){
				var class_parent = $(this).parent().attr("class").split(" ")[1];
				console.log(class_parent)
				//var box_xc = $(this).parent().css("left").split('%')[0]
				var pin_a_borrar = $("#map").find("[data-close='"+class_parent+"']");
				pin_a_borrar.remove();
				$(this).parent().remove();

			});
			

		},
		showPin: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});

			var xval = (options.pinX);
			var yval = (options.pinY);
			var imgC = $('<img class="pin">');
			imgC.css('top', yval+'%');
			imgC.css('left', xval+'%');

			imgC.attr('src',  options.pin);

			imgC.appendTo(this);
			$(options.hiddenXid).val(xval);
			$(options.hiddenYid).val(yval);

		},
		showPins: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});

			for(var i=0; i < (options.pinDataSet).markers.length; i++)
			{
				var dataPin = options.pinDataSet.markers[i];
				var id_prod = dataPin.name;
				var coords = dataPin.value;
				var ycoord = coords.split("-")[0];
				var xcoord = coords.split("-")[1];

				console.log(ycoord)
				//style="top:'+ycoord+'%; left:'+xcoord+'%;"
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

		}
	};

	// $("#map").click(function(){
	// 	$("#popup-prod").show();
	// })

	if (methods[method]) {

		return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

	} else if (typeof method === 'object' || !method) {

		return methods.init.apply(this, arguments);

	} else {

		alert("method does not exist");

	}


}

})( jQuery );
