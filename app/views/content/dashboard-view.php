<section class="hero is-light">
	<div class="hero-body">
		<div class="container">
			<div class="columns is-vcentered">
				<div class="column is-narrow">
					<figure class="image is-96x96">
						<?php
							if(is_file("./app/views/fotos/".$_SESSION['foto'])){
								echo '<img class="is-rounded" src="'.APP_URL.'app/views/fotos/'.$_SESSION['foto'].'">';
							}else{
								echo '<img class="is-rounded" src="'.APP_URL.'app/views/fotos/default.png">';
							}
						?>
					</figure>
				</div>
				<div class="column">
					<h1 class="title is-4">¡Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>!</h1>
					<h2 class="subtitle is-6">Panel de control</h2>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	$total_cajas=$insLogin->seleccionarDatos("Normal","caja","caja_id",0);

	$total_usuarios=$insLogin->seleccionarDatos("Normal","usuario WHERE usuario_id!='1' AND usuario_id!='".$_SESSION['id']."'","usuario_id",0);

	$total_clientes=$insLogin->seleccionarDatos("Normal","cliente WHERE cliente_id!='1'","cliente_id",0);

	$total_categorias=$insLogin->seleccionarDatos("Normal","categoria","categoria_id",0);

	$total_productos=$insLogin->seleccionarDatos("Normal","producto","producto_id",0);

	$total_ventas=$insLogin->seleccionarDatos("Normal","venta","venta_id",0);
?>
<div class="container pb-6 pt-6">

		<div class="stats-grid">
			<div>
				<a class="stat-link" href="<?php echo APP_URL; ?>cashierList/">
					<div class="card stat-card">
						<div class="card-content">
							<div class="media">
								<div class="media-left">
									<span class="icon is-large is-primary"><i class="fas fa-cash-register fa-2x"></i></span>
								</div>
								<div class="media-content">
									<p class="title"><?php echo $total_cajas->rowCount(); ?></p>
									<p class="subtitle">Cajas</p>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div>
				<a class="stat-link" href="<?php echo APP_URL; ?>userList/">
					<div class="card stat-card">
						<div class="card-content">
							<div class="media">
								<div class="media-left">
									<span class="icon is-large has-text-info"><i class="fas fa-users fa-2x"></i></span>
								</div>
								<div class="media-content">
									<p class="title"><?php echo $total_usuarios->rowCount(); ?></p>
									<p class="subtitle">Usuarios</p>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div>
				<a class="stat-link" href="<?php echo APP_URL; ?>clientList/">
					<div class="card stat-card">
						<div class="card-content">
							<div class="media">
								<div class="media-left">
									<span class="icon is-large has-text-success"><i class="fas fa-address-book fa-2x"></i></span>
								</div>
								<div class="media-content">
									<p class="title"><?php echo $total_clientes->rowCount(); ?></p>
									<p class="subtitle">Clientes</p>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div>
				<a class="stat-link" href="<?php echo APP_URL; ?>categoryList/">
					<div class="card stat-card">
						<div class="card-content">
							<div class="media">
								<div class="media-left">
									<span class="icon is-large has-text-warning"><i class="fas fa-tags fa-2x"></i></span>
								</div>
								<div class="media-content">
									<p class="title"><?php echo $total_categorias->rowCount(); ?></p>
									<p class="subtitle">Categorías</p>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div>
				<a class="stat-link" href="<?php echo APP_URL; ?>productList/">
					<div class="card stat-card">
						<div class="card-content">
							<div class="media">
								<div class="media-left">
									<span class="icon is-large has-text-danger"><i class="fas fa-cubes fa-2x"></i></span>
								</div>
								<div class="media-content">
									<p class="title"><?php echo $total_productos->rowCount(); ?></p>
									<p class="subtitle">Productos</p>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div>
				<a class="stat-link" href="<?php echo APP_URL; ?>saleList/">
					<div class="card stat-card">
						<div class="card-content">
							<div class="media">
								<div class="media-left">
									<span class="icon is-large has-text-primary"><i class="fas fa-shopping-cart fa-2x"></i></span>
								</div>
								<div class="media-content">
									<p class="title"><?php echo $total_ventas->rowCount(); ?></p>
									<p class="subtitle">Ventas</p>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

		</div>

</div>