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
		fixedWidth: '100%',
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
				imgC.css('top', yval+'px');
				imgC.css('left', xval+'px');
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

				//para pasarlo a porcentaje -funca
				//var position = $(thisObj).position();
				//var xval = xval/$(window).width() * 100;
				//var yval = yval/$(window).height() *100;
				

				// var imgC = $('<img class="pin '+yval+"-"+xval+'">');
				var imgC = $('<img class="pin '+yval+"-"+xval+'">');
				imgC.css('top', yval+'px');
				imgC.css('left', xval+'px');
				imgC.css('z-index', i);

				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				//console.log(ev.target);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);
				
				// add hidden fields - can use these to save to database
				var hiddenCtl= $('<input type="hidden" name="" class="pin '+yval+"-"+xval+'">');
				// var hiddenCtl= $('<input type="hidden" name="hiddenpin-'+xval+yval+'" class="pin">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(yval+"-"+xval);
				hiddenCtl.appendTo(thisObj);
				
				// muestro popup para producto
				var popup_overlay = $(".popup-prod-overlay");
				var popup_cont = $(".popup-prod-cont");
				var popup_prod = $(".popup-producto");
				var yval_pop = yval+20;
				var xval_pop = xval+20;
				
				popup_overlay.show(0,function(){
					popup_cont.css({
						'top': yval_pop+'px',
						'left': xval_pop+'px'
					});
					popup_prod.css({
						'top': yval_pop+'px',
						'left': xval_pop+'px'
					});
				});
				
				

			});
			//genera producto y futuro click protector
			$(".popup-prod-cont").on("click", ".nombre-producto", function(){
				
				$(".popup-prod-overlay").hide();
				// var segunda_clase = $(this).attr('class').split(' ')[1];
				var id_producto = $(this).attr('class').split(' ')[1];
				var box_y_prod = $(this).parent().parent().parent().parent().parent().css("top").split('px')[0];
				var box_x_prod = $(this).parent().parent().parent().parent().parent().css("left").split('px')[0];
				

				var box_y_prod_posta = box_y_prod - 20;
				var box_x_prod_posta = box_x_prod - 20;


				var pin_a_namear = $("#map").find("."+box_y_prod_posta+"-"+box_x_prod_posta);//1 porque hay 2
				pin_a_namear.attr("name",id_producto);

				var click_protector = '<div class="click-protector '+box_y_prod_posta+"-"+box_x_prod_posta+'">'+
											'<div class="salir-popup-single"><i class="fas fa-times-circle"></i></div></div>';

				$(".click-protector-cont").append(click_protector);
				$("."+box_y_prod_posta+"-"+box_x_prod_posta).css("top",box_y_prod_posta);
				$("."+box_y_prod_posta+"-"+box_x_prod_posta).css("left",box_x_prod_posta);
				$("."+box_y_prod_posta+"-"+box_x_prod_posta+" .salir-popup-single").css("display","none");

			});
			//para salir de la sel de productos y eliminar pin
			$(".popup-prod-cont").on("click",".salir-popup", function(){

				var box_y = $(this).parent().css("top").split('px')[0];
				var box_x = $(this).parent().css("left").split('px')[0];

				var box_y_new = box_y - 20;
				var box_x_new = box_x - 20;

				var pin_a_borrar = $("#map").find("."+box_y_new+"-"+box_x_new);

				pin_a_borrar.remove();
				$(".popup-prod-overlay").hide();

			});
			//para borrar el single pin
			$(".click-protector-cont").on("click",".salir-popup-single", function(){

				var class_parent = $(this).parent().attr("class").split(" ")[1];
				console.log(class_parent)
				//var box_xc = $(this).parent().css("left").split('px')[0]
				var pin_a_borrar = $("#map").find("."+class_parent);
				console.log(pin_a_borrar)
				pin_a_borrar.remove();
				$(this).parent().remove();

			});
			//click para que aparezca la cruz
			$(".click-protector-cont").on('click', '.click-protector', function() {
				$(this).find(".salir-popup-single").show();
			});
			

		},
		showPin: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});

			var xval = (options.pinX);
			var yval = (options.pinY);
			var imgC = $('<img class="pin">');
			imgC.css('top', yval+'px');
			imgC.css('left', xval+'px');

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

				var imgC = $('<img class="pin '+id_prod+'" style="top:'+ycoord+'px;left:'+xcoord+'px;">');
				imgC.attr('src',  options.pin);
				imgC.attr('title',  dataPin.title);
				imgC.appendTo(this);
				
				var click_protector = '<div class="click-protector '+coords+'">'+
											'<div class="salir-popup-single"><i class="fas fa-times-circle"></i></div></div>';

				$(".click-protector-cont").append(click_protector);
				$("."+ycoord+"-"+xcoord).css("top",ycoord);
				$("."+ycoord+"-"+xcoord).css("left",xcoord);
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
