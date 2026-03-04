<aside class="menu navLateral scroll" id="navLateral">
	<div class="box navLateral-body has-background-white">
	<div class="media">
	  <div class="media-left">
		<?php
		  if(is_file("./app/views/fotos/".$_SESSION['foto'])){
			echo '<img class="is-rounded" style="width:64px;height:64px;" src="'.APP_URL.'app/views/fotos/'.$_SESSION['foto'].'">';
		  }else{
			echo '<img class="is-rounded" style="width:64px;height:64px;" src="'.APP_URL.'app/views/fotos/default.png">';
		  }
		?>
	  </div>
	  <div class="media-content" style="align-self:center;">
		<p class="title is-6"><?php echo $_SESSION['nombre']; ?></p>
		<p class="subtitle is-7">@<?php echo $_SESSION['usuario']; ?></p>
	  </div>
	</div>

	<ul class="menu-list">
	  <li><a href="<?php echo APP_URL; ?>dashboard/"><i class="fab fa-dashcube"></i> Inicio</a></li>
	</ul>

	<p class="menu-label">Gestionar</p>
		<ul class="menu-list">
			<li>
				<a href="#" class="btn-subMenu has-text-weight-semibold">Cajas <span class="fas fa-chevron-down" style="float:right"></span></a>
				<ul class="sub-menu-options">
					<li><a href="<?php echo APP_URL; ?>cashierNew/">Nueva caja</a></li>
					<li><a href="<?php echo APP_URL; ?>cashierList/">Lista de cajas</a></li>
					<li><a href="<?php echo APP_URL; ?>cashierSearch/">Buscar caja</a></li>
				</ul>
			</li>
			<li>
				<a href="#" class="btn-subMenu has-text-weight-semibold">Usuarios <span class="fas fa-chevron-down" style="float:right"></span></a>
				<ul class="sub-menu-options">
					<li><a href="<?php echo APP_URL; ?>userNew/">Nuevo usuario</a></li>
					<li><a href="<?php echo APP_URL; ?>userList/">Lista de usuarios</a></li>
					<li><a href="<?php echo APP_URL; ?>userSearch/">Buscar usuario</a></li>
				</ul>
			</li>
			<li>
				<a href="#" class="btn-subMenu has-text-weight-semibold">Clientes <span class="fas fa-chevron-down" style="float:right"></span></a>
				<ul class="sub-menu-options">
					<li><a href="<?php echo APP_URL; ?>clientNew/">Nuevo cliente</a></li>
					<li><a href="<?php echo APP_URL; ?>clientList/">Lista de clientes</a></li>
					<li><a href="<?php echo APP_URL; ?>clientSearch/">Buscar cliente</a></li>
				</ul>
			</li>
		</ul>

	<p class="menu-label">Inventario</p>
	<ul class="menu-list">
	  <li>
		<a href="#" class="btn-subMenu">Categorías <span class="fas fa-chevron-down" style="float:right"></span></a>
		<ul class="sub-menu-options">
		  <li><a href="<?php echo APP_URL; ?>categoryNew/">Nueva categoría</a></li>
		  <li><a href="<?php echo APP_URL; ?>categoryList/">Lista de categorías</a></li>
		</ul>
	  </li>
	  <li>
		<a href="#" class="btn-subMenu">Productos <span class="fas fa-chevron-down" style="float:right"></span></a>
		<ul class="sub-menu-options">
		  <li><a href="<?php echo APP_URL; ?>productNew/">Nuevo producto</a></li>
		  <li><a href="<?php echo APP_URL; ?>productList/">Lista de productos</a></li>
		  <li><a href="<?php echo APP_URL; ?>productSearch/">Buscar producto</a></li>
		</ul>
	  </li>
	</ul>

	<p class="menu-label">Ventas</p>
	<ul class="menu-list">
	  <li>
		<a href="#" class="btn-subMenu">Ventas <span class="fas fa-chevron-down" style="float:right"></span></a>
		<ul class="sub-menu-options">
		  <li><a href="<?php echo APP_URL; ?>saleNew/">Nueva venta</a></li>
		  <li><a href="<?php echo APP_URL; ?>saleList/">Lista de ventas</a></li>
		  <li><a href="<?php echo APP_URL; ?>saleSearch/">Buscar venta</a></li>
		  <li><a href="<?php echo APP_URL; ?>reportDay/">Reporte cierre</a></li>
		</ul>
	  </li>
	</ul>

	<p class="menu-label">Configuración</p>
	<ul class="menu-list">
	  <li>
		<a href="#" class="btn-subMenu">Configuración <span class="fas fa-chevron-down" style="float:right"></span></a>
		<ul class="sub-menu-options">
		  <li><a href="<?php echo APP_URL; ?>companyNew/">Datos de empresa</a></li>
		  <li><a href="<?php echo APP_URL."userUpdate/".$_SESSION['id']."/"; ?>">Mi cuenta</a></li>
		  <li><a href="<?php echo APP_URL."userPhoto/".$_SESSION['id']."/"; ?>">Mi foto</a></li>
		</ul>
	  </li>
	</ul>

		<div style="padding-top:10px;">
			<a class="button is-fullwidth is-danger" href="<?php echo APP_URL."logOut/"; ?>">Cerrar sesión</a>
		</div>
  </div>
</aside>