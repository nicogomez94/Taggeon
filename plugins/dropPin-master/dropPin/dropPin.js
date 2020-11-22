/*!
* dropPin - because image maps are icky
* http://duncanheron.github.com/dropPin/
*
*/
(function( $ ){

	$.fn.dropPin = function(method) {

		var test = $("#map").css("background-image");

		var defaults = {
		fixedHeight: 300,
		fixedWidth: '100%',
		dropPinPath: '/js/dropPin/',
		pin: 'dropPin/defaultpin@2x.png',
		backgroundImage: test,
		backgroundColor: 'transparent',
		xoffset : 10,
		yoffset : 30, //need to change this to work out icon heigh/width then subtract margin from it
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
			console.log(typeof options)
			console.log(options)
			console.log("-------------")
			console.log(typeof thisObj)
			console.log(thisObj)

			thisObj.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : options.backgroundImage,'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var i = 10;
			thisObj.on(options.userevent, function (ev) {

				i = i + 10;
				var $img = $(thisObj);
				var offset = $img.offset();
				var x = ev.pageX - offset.left;
				var y = ev.pageY - offset.top;

				
				var xval = (x - options.xoffset);
				var yval = (y - options.yoffset);
				var imgC = $('<img class="pin pin-id-'+x+y+'">');
				imgC.css('top', yval+'px');
				imgC.css('left', xval+'px');
				imgC.css('z-index', i);

				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				//console.log(ev.target);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);

				// add hidden fields - can use these to save to database
				var hiddenCtl= $('<input type="hidden" name="hiddenpin" class="pin">');
				// var hiddenCtl= $('<input type="hidden" name="hiddenpin-'+xval+yval+'" class="pin">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(x + "#" + y);
				hiddenCtl.appendTo(thisObj);
				
				// muestro popup para producto
				var popup_overlay = $(".popup-prod-overlay");
				var popup_cont = $(".popup-prod-cont");
				var popup_prod = $(".popup-producto");
				var yval_pop = yval+20;
				var xval_pop = xval+20;
				
				/*console.log("xval"+xval)
				console.log("yval"+yval)
				
				console.log("xval"+xval_pop)
				console.log("yval"+yval_pop)*/
				
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

				$(".popup-producto-1").click(function(){
					$(".popup-prod-overlay").hide();
					// var html_input_prod = 
					// añadir hidden productto
					var hiddenProd= $('<input type="hidden" name="hiddenpin" class="pin producto">');
					 // var hiddenCtl= $('<input type="hidden" name="hiddenpin-'+xval+yval+'" class="pin">');
					hiddenProd.val("idProducto");
					hiddenProd.appendTo(thisObj);
				});
				$(".salir-popup").click(function(){
					$(".popup-prod-overlay").hide();
					$(".pin-id-"+x+y).remove();
					hiddenCtl.remove();
					// hiddenProd.remove();
				});
				
			});
			/*$(".salir-popup").on(options.userevent, function (ev) {
				console.log("inside salir poop")
				var namespace = {};
				namespace.this = {};
				delete namespace.this;
			});*/
			

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

				var imgC = $('<img rel="/map-content.php?id='+dataPin.id+'" class="pin '+options.pinclass+'" style="top:'+dataPin.ycoord+'px;left:'+dataPin.xcoord+'px;">');
				imgC.attr('src',  options.pin);
				imgC.attr('title',  dataPin.title);

				imgC.appendTo(this);
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
