<html><head>
    <title>arquipick</title>

    <link rel="stylesheet" href="css/styles.css?time=160920-2005">
    <!-- <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="bootstrap-4.5.2-dist/css/bootstrap.min.css">
    <link href="fontawesome-free-5.14.0-web/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
   <br><br>

<div id="background-atras"></div>

<div class="container" id="contenedor-form-subir">
   <h1>Subir Producto</h1>
   <hr>
   <form>
      <div id="col-foto-upload">
            <p class="label-descr">Seleccione una foto para el producto.</p>
            <div class="row">
               <div id="contenedor-subir-foto">
                  <img id="img-subir" alt="imagen" width="100" height="100" />
                  <span id="text-inside-subir"><input type="file" name="imagenes-producto" onchange="$('#img-subir').show();document.getElementById('img-subir').src = window.URL.createObjectURL(this.files[0]);"></span>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-12">
                  <div class="group">      
                     <input type="text" name="nombre-producto" required>
                     <span class="highlight"></span>
                     <span class="bar"></span>
                     <label>Nombre</label>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3">
                  <div class="group">      
                     <input type="text" name="precio-producto" required>
                     <span class="highlight"></span>
                     <span class="bar"></span>
                     <label>Precio</label>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-12">
                  <p class="label-descr">Descripcion del producto</p>
                  <textarea type="text" name="descr-producto" required></textarea>
               </div>
            </div>
            <br><hr>
            <div id="ficha-tecnica">
               <h3>Ficha Tecnica</h3><br>
               <div class="row">
                  <div class="col-lg-6">
                     <div class="group">      
                        <input type="text" name="marca-producto" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Marca</label>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-6">
                     <select name="categoria-producto" id="categoria-foto">
                        <option value="categoria1">Categoria</option>
                        <option value="categoria2">Categoria</option>
                        <option value="categoria3">Categoria</option>
                        <option value="categoria4">Categoria</option>
                        <option value="categoria5">Categoria</option>
                     </select>
                  </div>
                  <div class="col-lg-6">
                     <select name="rubro-producto" id="categoria-foto">
                        <option value="categoria1">Rubro</option>
                        <option value="categoria2">Rubro</option>
                        <option value="categoria3">Rubro</option>
                        <option value="categoria4">Rubro</option>
                        <option value="categoria5">Rubro</option>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-3">
                     <select name="color-producto" id="categoria-foto">
                        <option value="categoria1">Color</option>
                        <option value="categoria2">Color</option>
                        <option value="categoria3">Color</option>
                        <option value="categoria4">Color</option>
                        <option value="categoria5">Color</option>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-3">
                     <select name="envio-producto" id="categoria-foto">
                        <option value="categoria1">Envio</option>
                        <option value="categoria2">Envio</option>
                        <option value="categoria3">Envio</option>
                        <option value="categoria4">Envio</option>
                        <option value="categoria5">Envio</option>
                     </select>
                  </div>
               </div>
            </div>
            
         <div>
            <div class="row">
               <div class="col-lg-4">
                  <br>
                  <input type="submit" class="btn btn-warning" value="Guardar Producto">
               </div>
            </div>
         </div>
      </div>
   </form>
</div>



   <script src="js/jquery-3.5.1.js"></script>
   <script src="bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
   <!-- <script src="js/popper.js"></script> -->
   <script src="js/func.js?time=140920-1829"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script> -->

</body></html>
