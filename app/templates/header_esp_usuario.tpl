<html><head>
    <title>arquipick1</title>

    <link rel="stylesheet" href="css/styles.css?time=160920-2005">
    <!-- <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="bootstrap-4.5.2-dist/css/bootstrap.min.css">
    <link href="fontawesome-free-5.14.0-web/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>
<body>

    <nav id="navbar-principal" class="navbar navbar-expand-md fixed-top">
      <div class="navbar-brand-container">
         <a class="navbar-brand" href="/"><i class="fab fa-affiliatetheme"></i>
            <span> Arquipick</span>
         </a>
      </div>
      <!---->
      <input id="form-buscar" class="form-control search" type="text" placeholder="Buscar" aria-label="Buscar">
      <!---->
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
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
<img class="mr-3" src="{foto-perfil}" alt="Generic placeholder image"> 
                        <div class="media-body">
                          <h5 class="mt-0">{nombre-usuario}</h5>
                          <a href="/ampliar-usuario.html">Ver Perfil{perfil}</a>
                        </div>
                      </div>
                     <hr>
                     <a href="/app/logout.php">Cerrar Sesi&oacute;n</a>
                  </div>
            </li>
            <li id="dropdown-user-menu-responsive">
               <div>
                  <div class="media">
                     
                      <img class="mr-3" src="{foto-perfil}" alt="Generic placeholder image"> 
                     <div class="media-body">
                       <h5 class="mt-0">{nombre-usuario}</h5>
                       <a href="/ampliar-usuario.html">Ver Perfil{perfil}</a>
                     </div>
                   </div>
                  <hr>
                  <a href="/app/logout.php">Cerrar Sesi&oacute;n</a>
               </div>
            </li>
         </ul>
      </div>
   </nav>
   <!-- navegador bottom fixed usuario logueado -->
   <nav id="navbar-bottom-responsive" class="navbar fixed-bottom navbar-light bg-light">
            <div class="navbar-bottom-col">
               <a href="/">
                  <div><i class="fas fa-home"></i></div>
                  <div>Home</div>
               </a>
            </div>
            <div class="navbar-bottom-col">
               <a href="#" id="drop-bottom">
                  <div><i class="fas fa-user-circle"></i></div>
                  <div>Usuario</div>
               </a>
               <div id="dropdown-user-menu-bottom">
                     <div class="media">
                        <img class="mr-3" src="{foto-perfil}" alt="Generic placeholder image"> 
                        <div class="media-body">
                          <h5 class="mt-0">{nombre-usuario}</h5>
                          <a href="/ampliar-usuario.html">Ver Perfil{perfil}</a>
                        </div>
                      </div>
                     <hr>
                     <a href="/app/logout.php">Cerrar Sesi&oacute;n</a>
               </div>
         </div>
         <div class="navbar-bottom-col">
               <a href="#">
                  <div><i class="fas fa-plus"></i></div>
                  <div>Subir</div>
               </a>
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

<div>