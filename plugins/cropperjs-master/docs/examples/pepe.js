 paypal.Buttons({

    env: 'sandbox', /* sandbox | production */
    style: {
                layout: 'horizontal',   // horizontal | vertical
                size:   'medium',   /* medium | large | responsive*/
                shape:  'pill',         /* pill | rect*/
                color:  'blue',         /* gold | blue | silver | black*/
                label: 'checkout',
                fundingicons: false,    /* true | false */
                tagline: false          /* true | false */
            },

      /* createOrder() is called when the button is clicked */

   onCancel: function (data) {
    // Show a cancel page, or return to cart
     //vuelvo a mostrar si se cierra popup
      var boton = $("#paypal-button-container");
               boton.css({
                  "opacity":"1",
                  "pointer-events": "all"
               });
            var htmlMsj2 ='<div class="mensaje__onaprove">Por favor, no cierre la ventana para proceder con el pago</div>';
               boton.after(htmlMsj2)


   },
   onError: function (err) {
    // For example, redirect to a specific error page

      var boton = $("#paypal-button-container");
            boton.css({
                  "opacity":"1",
                  "pointer-events": "all"
               });
         var htmlMsj2 ='<div class="mensaje__onaprove">Por favor, no cierre la ventana para proceder con el pago</div>';
               boton.after(htmlMsj2)

       
   },
    createOrder: function() {

        /* Set up a url on your server to create the order */




        document.getElementById('btn_accion').value = "F3_confirmar_pago";
        var div_decidir = document.createElement("div");
        div_decidir.setAttribute("id", 'div_form_decidir');
        // Inscripto
        var codInsc = 0;
        if (document.getElementById('cod_inscripto')) {
            codInsc = document.getElementById('cod_inscripto').value;
        }
        // Trans
        var trans = 0;
        if (document.getElementById('peoplesoft_trans')) {
            trans = document.getElementById('peoplesoft_trans').value;
        }
        // Medio de Pago
        var medios_pagos = document.getElementsByName('medio_pago');
        var medio = '';
        for (var i = 0; i < medios_pagos.length; i++) {
            if (medios_pagos[i].checked) {
                medio = medios_pagos[i].value;
                break;
            }
        }
        // Variable Arancelaria que indica la moneda
        var sel_group = '';
        if (document.getElementById('moneda')) {
            var selec = document.getElementById('moneda');
            sel_group = selec.options[selec.selectedIndex].value;
        }
        // Conceptos
        var conceptos_check = document.getElementsByName('concepto');
        var conceptos = '';
        for (var i = 0; i < conceptos_check.length; i++) {
            if (conceptos_check[i].checked) {
                conceptos += "&concepto=" + conceptos_check[i].value;

                      
            }
        // Cadena
        var cadena = "&btn_accion=F3_confirmar_pago&cod_inscripto=" + codInsc + "&peoplesoft_trans=" + trans + "&medio_pago=" + medio + conceptos + "&moneda=" + sel_group;

        //Ejemplo
        /*
         &btn_accion=F3_confirmar_pago&cod_inscripto=3500&peoplesoft_trans=58078&medio_pago=1&concepto=TES_APPFGD--3&concepto=TES_ARGRDD--8&moneda=
        */


        var CREATE_URL = '/cgi-bin/paypal/paypal.pl?action=create_order'+cadena;


        /* Make a call to your server to set up the payment */

        return fetch(CREATE_URL)
         .then(function(res) {
          return res.json();
         }).then(function(data) {
            if (data[0] == 'OK'){

               //boton opaco y sin eventos
               var boton = $("#paypal-button-container");
               boton.css({
                  "opacity":".3",
                  "pointer-events": "none"
               })
               //cierro si esta abierto mensaje anterior
               var msjAp = $(".mensaje__onaprove");
               if(msjAp.length>0){
                  msjAp.remove();
               }

               return (data[1]);
            }else if (data[0] == 'REDIRECT'){
              window.location.replace(data[1]);
            }else{
               alert(data[1]);
            }

         });

    }
    },

    onApprove: function(data, actions) {
         return actions.order.capture().then(function(details) {
            var boton = $("#paypal-button-container");

            //boton opaco y sin eventos
            boton.css({
                  "opacity":".3",
                  "pointer-events": "none"
               });
            var msjAp2 = $(".mensaje__onaprove")
            if(msjAp2.length>0){
                msjAp2.remove();
            }

         var htmlMsj ='<div class="mensaje__onaprove">Transaccion completada por '+details.payer.name.given_name+'</div>';
            boton.after(htmlMsj)
            var CREATE_URL = '/cgi-bin/paypal/paypal.pl?action=transaction_complete&orderID='+data.orderID;


            return fetch(CREATE_URL)
            .then(function(res) {
             return res.json();
            }).then(function(data) {
               if (data[0] == 'OK'){
                  alert (data[1]);

                  console.log("ok"+data[1])
                  window.location.replace(data[1]);

               }else if (data[0] == 'REDIRECT'){

                  window.location.replace(data[1]);

               }else{

                  alert(data[1]);
               }

            });

      });
   }


}).render('#paypal-button-container');
