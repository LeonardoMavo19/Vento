<div class="container is-fluid mb-6">
	<h1 class="title">Cajas</h1>
	<h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar caja</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
	
		include "./app/views/inc/btn_back.php";

		$id=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Unico","caja","caja_id",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos['caja_nombre']." #".$datos['caja_numero']; ?></h2>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/cajaAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_caja" value="actualizar">
		<input type="hidden" name="caja_id" value="<?php echo $datos['caja_id']; ?>">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Numero de caja <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="caja_numero" pattern="[0-9]{1,5}" maxlength="5" value="<?php echo $datos['caja_numero']; ?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Nombre o código de caja <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="caja_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ:# ]{3,70}" maxlength="70" value="<?php echo $datos['caja_nombre']; ?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
				<label>Efectivo en caja <?php echo CAMPO_OBLIGATORIO; ?></label>
			  		<input class="input" type="text" name="caja_efectivo" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo number_format($datos['caja_efectivo'],2,'.',''); ?>" required >
				</div>
		  	</div>
		</div>

		<div class="columns">
		  <div class="column">
		    <div class="control">
				<label>Dólares (USD)</label>
			  		<input class="input" type="text" name="caja_dolares" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo number_format(isset($datos['caja_dolares'])?$datos['caja_dolares']:0,2,'.',''); ?>" >
				</div>
		  	</div>
		  <div class="column">
		    <div class="control">
				<label>POS (Bs)</label>
			  		<input class="input" type="text" name="caja_pos" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo number_format(isset($datos['caja_pos'])?$datos['caja_pos']:0,2,'.',''); ?>" >
				</div>
		  	</div>
		  <div class="column">
		    <div class="control">
				<label>Biopago (Bs)</label>
			  		<input class="input" type="text" name="caja_biopago" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo number_format(isset($datos['caja_biopago'])?$datos['caja_biopago']:0,2,'.',''); ?>" >
				</div>
		  	</div>
		</div>

		<div class="columns">
		  <div class="column">
		    <div class="control">
				<label>Pago móvil (Bs)</label>
			  		<input class="input" type="text" name="caja_pago_movil" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo number_format(isset($datos['caja_pago_movil'])?$datos['caja_pago_movil']:0,2,'.',''); ?>" >
				</div>
		  	</div>
		  <div class="column">
		    <div class="control">
				<label>Otros (Bs)</label>
			  		<input class="input" type="text" name="caja_otros" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo number_format(isset($datos['caja_otros'])?$datos['caja_otros']:0,2,'.',''); ?>" >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
		</p>
		<p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
	</form>
	<?php
		}else{
			include "./app/views/inc/error_alert.php";
		}
	?>
</div>