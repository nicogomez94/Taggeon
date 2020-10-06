<html><head>
    <title>arquipick</title>

    <link rel="stylesheet" href="css/styles.css?time=160920-2005">
    <!-- <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="bootstrap-4.5.2-dist/css/bootstrap.min.css">
    <link href="fontawesome-free-5.14.0-web/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
      var jsonData = {
         "FOTO-PERFIL" : "url-img",
         "NOMBRE"      : "Nombre",
         "APELLIDO"    : "Apellido",
         "SEGUIDORES"  : "0",
         "SEGUIDOS"    : "0",
         // "FAVORITOS" : []
         // "PUBLICACIONES" : []
         // "COMPRAS" : []
      };
      
      
   </script>

</head>
<body>

    <nav id="navbar-principal" class="navbar navbar-expand-md fixed-top">
      <div class="navbar-brand-container">
         <a class="navbar-brand" href="#"><i class="fab fa-affiliatetheme"></i>
            <span> Arquipick</span>
         </a>
      </div>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
         <span class="navbar-toggler-icon"><i class="fas fa-bars" style="line-height: 30px;"></i></span>
       </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
         <!---->
         <input class="form-control search" type="text" placeholder="Buscar" aria-label="Buscar">
         <!---->
         <ul class="navbar-nav px-3" id="items-navbar-derecha">
            <li class="nav-item text-nowrap">
                  <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">
                  Sign In
               </button> -->
                  <!-- <div id="ver-perfil" class="nav-item-icon" data-toggle="modal" data-target="#exampleModalCenter">
                     <i class="fas fa-user"></i>
                  </div> -->
            </li>
            <li id="dropdown-user-menu-cont" class="nav-item text-nowrap" style="margin-left:10px">
                  <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter2">
                     Registrarse
                  </button> -->
                  <div id="drop" class="nav-item-icon">
                     <i class="fas fa-caret-down"></i>
                  </div>
                  <div id="dropdown-user-menu">
                     <div class="media">
                        <i class="fas fa-user media-icon"></i>
                        <!-- <img class="mr-3" src="..." alt="Generic placeholder image"> -->
                        <div class="media-body">
                          <h5 class="mt-0">Usuario</h5>
                          <a href="#">Ver Perfil</a>
                        </div>
                      </div>
                     <hr>
                     <a href="#">Cerrar Sesion</a>
                  </div>
            </li>
            <li id="dropdown-user-menu-responsive">
               <div>
                  <div class="media">
                     <i class="fas fa-user media-icon"></i>
                     <!-- <img class="mr-3" src="..." alt="Generic placeholder image"> -->
                     <div class="media-body">
                       <h5 class="mt-0">Usuario</h5>
                       <a href="#">Ver Perfil</a>
                     </div>
                   </div>
                  <hr>
                  <a href="#">Cerrar Sesion</a>
               </div>
            </li>
         </ul>
      </div>
   </nav>
	
	<!-- Modal INICIO DE SESION -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->
				<div class="modal-body">
					<h1>Bienvenido de vuelta</h1>
					<hr>
					<div id="form_inicio_sesion_cont" class="forms">
						<form action="" method="post">
							<div>
								<input type="email" id="mail-login" name="mail" placeholder="Email">
							</div>
							<div>
								<input type="password" id="pass-login" name="pass" placeholder="Password">
							</div>
							<div>
								<label class="form-check-label" for="exampleCheck1"><a href="#">Olvid&oacute; su contrase&ntilde;a?</a></label>
							</div>
                     <br>
							<button type="submit" class="btn btn-info">Iniciar Sesi&oacute;n</button>
                     <hr>
                     <div>
								<label class="form-check-label" for="exampleCheck1">Todav&iacute;a no est&aacute;s en Arquipick? <a href="#" id="test11" data-toggle="modal" data-target="#exampleModalCenter2">Registrate</a></label>
							</div>
                  </form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal REGISTRO -->
	<div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->
				<div class="modal-body">
					<h1>Te damos la bienvenida a Arquipick</h1>
					<hr>
					<div id="form_registro_cont" class="forms">
						<form action="http://ec2-18-191-182-136.us-east-2.compute.amazonaws.com/app/alta_usuario.php" method="post">
                     <div>
								<input type="text" id="nombre" name="nombre" placeholder="Nombre">
                     </div>
                     <div>
								<input type="text" id="apellido" name="apellido" placeholder="Apellido">
							</div>
                     <div>
								<input type="email" id="mail" name="mail" placeholder="Email">
							</div>
							<div>
								<input type="password" id="pass" name="pass" placeholder="Contrase&ntilde;a">
                     </div>
                     <div>
							   <input type="password" id="cpass" name="cpass" placeholder="Repita la Contrase&ntilde;a">
							</div>
							<div>
                        <button type="submit" class="btn btn-info">Registrarse</button>
                     </div>
                     <!-- <div class="or">&Oacute;</div>
                     <div>
                        <button type="submit" class="btn btn-warning">Registrarse como Seller</button>
                     </div> -->
                     <p class="small-text-form">Al hacer clic en "Registrarse", aceptas nuestras Condiciones, la Pol&iacute;tica de datos y la Pol&iacute;tica de cookies. Es posible que te enviemos notificaciones por SMS, que puedes desactivar cuando quieras.</p>
						</form>
               </div>
                  <hr>
                  <!--  -->
                  
            </div>
            <div id="registro_seller">
               <a href="#" id="registro_seller_btn" data-toggle="modal" data-target="#exampleModalCenter3">Registrarse como Seller</a>
            </div>
			</div>
		</div>
   </div>
   
   <!-- Modal REGISTRO-SELLER -->
	<div class="modal fade" id="exampleModalCenter3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle3" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->
				<div class="modal-body">
					<h1>Te damos la bienvenida a Arquipick</h1>
					<h6 style="color: rgb(197, 197, 197);">Hac&eacute; crecer tu negocio</h6>
					<hr>
					<div id="form_registro_cont" class="forms">
						<form action="http://ec2-18-191-182-136.us-east-2.compute.amazonaws.com/app/alta_usuario_seller.php" method="post">
							<div>
								<input type="text" id="nombre-seller" name="nombre" placeholder="Nombre">
							</div>
							<div>
								<input type="text" id="apellido-seller" name="apellido" placeholder="Apellido">
							</div>
							<div>
								<input type="email" id="mail-seller" name="mail" placeholder="Email">
							</div>
							<div>
								<input type="password" id="pass-seller" name="pass" placeholder="Contrase&ntilde;a">
							</div>
							<div>
								<input type="password" id="cpass-seller" name="cpass" placeholder="Repita la Contrase&ntilde;a">
							</div>
							<div>
								<button type="submit" class="btn btn-warning">Registrarse como Seller</button>
							</div>
							<p class="small-text-form">Al hacer clic en "Registrarte", aceptas nuestras Condiciones, la Pol&iacute;tica de datos y la Pol&iacute;tica de cookies. Es posible que te enviemos notificaciones por SMS, que puedes desactivar cuando quieras.</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<div id="info-user" class="container" style="position: relative;">
   <div id="icons-upper-perfil">
      <span><a href="#"><i class="fas fa-plus"></i></a></span>
      <span><a href="#"><i class="fas fa-user-cog"></i></a></span>      
      <span><a href="#"><i class="fas fa-user-edit"></i></a></span>
   </div>
   <div id="titulo-perfil">
         <img src="https://media.licdn.com/dms/image/C4E0BAQHkIxTtRvXUUg/company-logo_200_200/0?e=2159024400&v=beta&t=xJLEUA9rj1CXcLIgkIqy2To38Vkgy8hDyfCCo26p9To" alt="">
         <div id="titulo-perfil-datos">
            <div>Nombre Apellido</div>
            <div class="fyf"><span class="seguidores">0</span>&nbsp;Seguidores - <span class="siguiendo">0</span>&nbsp; Siguiendo</div>
         </div>
   </div>
   <div id="menu-perfil">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div class="navbar-nav navbar-center">
            <a class="nav-item nav-link active active-custom" href="#">Favoritos <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="#">Compras</a>
            <a class="nav-item nav-link" href="#">Mis Publicaciones</a>
         </div>
       </nav>
   </div>
   
</div>


<!-- PRODUCTO -->
<!-- PRODUCTO -->
<!-- PRODUCTO -->
<!-- PRODUCTO -->
<!-- PRODUCTO -->
<!-- PRODUCTO -->
<!-- PRODUCTO -->
<!-- PRODUCTO -->


<div class="container contenedor-productos">
    <div id="listado-mis-productos" style="margin-top: 100px">
        <div class="row producto">
            <div class="col-lg-3"><img class="img-producto" src="/Users/nikoo/Desktop/7dd2a59d-2c4d-4225-abcd-e1e8523b1c51.jpg" alt=""></div>
            <div class="col-lg-3 text-left"><span class="titulo-producto"><a href="#">TITULO PRODUCTO</a></span></div>
            <div class="col-lg-3"><span class="precio-producto">$20000</span></div>
            <div class="col-lg-3 text-right ellip"><i class="fas fa-ellipsis-v"></i></div>´
            <!-- flotante -->
            <div class="acciones-producto">
                <ul>
                    <li><a href="#">Eliminar producto</a></li>
                    <li><a href="#">Modificar</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- <div id="info-user" class="container">
   <div id="icons-upper-perfil">
      <ul>
         <li><a class="nav-item nav-link" href="#"><i class="fas fa-plus"></i></a></li>
         <li><a class="nav-item nav-link" href="#"><i class="fas fa-user-cog"></i></a></li>
         <li><a class="nav-item nav-link" href="#"><i class="fas fa-user-edit"></i></a></li>            
      </ul>
   </div>
   <div id="titulo-perfil">
      <div class="media">
         <div class="media-body">
            <div class="media-body-titulo">
               <div>Nombre Apellido</div>
               <div class="fyf"><span class="seguidores">0</span>&nbsp;Seguidores - <span class="siguiendo">0</span>&nbsp; Siguiendo</div>
            </div>

         </div>
         <img class="ml-3 align-self-center" src="https://media.licdn.com/dms/image/C4E0BAQHkIxTtRvXUUg/company-logo_200_200/0?e=2159024400&v=beta&t=xJLEUA9rj1CXcLIgkIqy2To38Vkgy8hDyfCCo26p9To" alt="">
      </div>
   </div>
   <div id="menu-perfil">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div class="navbar-nav">
            <a class="nav-item nav-link active active-custom" href="#">Favoritos <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="#">Compras</a>
            <a class="nav-item nav-link" href="#">Mis Publicaciones</a>
            <a class="nav-item nav-link" href="#">Disabled</a>
         </div>
       </nav>
   </div>
   
</div> -->


<!-- <div id="contenido-perfil">
   Contenido Proximamente...
</div> -->

<!-- -->


   <script src="bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
   <!-- <script src="js/popper.js"></script> -->
   <script src="js/func.js?time=140920-1829"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script> -->

</body></html>
