<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\saleController;

	if(isset($_POST['modulo_venta'])){

		$insVenta = new saleController();

		/*--------- Buscar producto por codigo ---------*/
		if($_POST['modulo_venta']=="buscar_codigo"){
			echo $insVenta->buscarCodigoVentaControlador();
		}

		/*--------- Agregar producto a carrito ---------*/
		if($_POST['modulo_venta']=="agregar_producto"){
			echo $insVenta->agregarProductoCarritoControlador();
        }

        /*--------- Remover producto de carrito ---------*/
		if($_POST['modulo_venta']=="remover_producto"){
			echo $insVenta->removerProductoCarritoControlador();
		}

		/*--------- Actualizar producto de carrito ---------*/
		if($_POST['modulo_venta']=="actualizar_producto"){
			echo $insVenta->actualizarProductoCarritoControlador();
		}

		/*--------- Buscar cliente ---------*/
		if($_POST['modulo_venta']=="buscar_cliente"){
			echo $insVenta->buscarClienteVentaControlador();
		}

		/*--------- Agregar cliente a carrito ---------*/
		if($_POST['modulo_venta']=="agregar_cliente"){
			echo $insVenta->agregarClienteVentaControlador();
		}

		/*--------- Remover cliente de carrito ---------*/
		if($_POST['modulo_venta']=="remover_cliente"){
			echo $insVenta->removerClienteVentaControlador();
		}

		/*--------- Registrar venta ---------*/
		if($_POST['modulo_venta']=="registrar_venta"){
			echo $insVenta->registrarVentaControlador();
		}

		/*--------- Eliminar venta ---------*/
		if($_POST['modulo_venta']=="eliminar_venta"){
			echo $insVenta->eliminarVentaControlador();
		}

		/*--------- Guardar tasa de cambio en servidor ---------*/
		if($_POST['modulo_venta']=="guardar_tasa"){
			$rate = isset($_POST['exchange_rate']) ? trim($_POST['exchange_rate']) : '';
			// Normalizar coma decimal a punto
			$rate = str_replace(',', '.', $rate);
			if($rate=='' || !is_numeric($rate) || floatval($rate)<=0){
				echo json_encode(['status'=>'error','message'=>'Tasa inválida']);
				exit;
			}

			// Ruta al archivo de configuración
			$configFile = __DIR__ . '/../../config/app.php';
			if(!is_writable($configFile)){
				echo json_encode(['status'=>'error','message'=>'Archivo de configuración no escribible']);
				exit;
			}

			$content = file_get_contents($configFile);
			if($content===false){
				echo json_encode(['status'=>'error','message'=>'No se pudo leer config']);
				exit;
			}

			// Reemplazar la constante EXCHANGE_RATE_USD_TO_BS
			$newRate = number_format(floatval($rate), 2, '.', '');
			$pattern = '/const\s+EXCHANGE_RATE_USD_TO_BS\s*=\s*([0-9]+\.?[0-9]*)\s*;/' ;
			$replacement = "const EXCHANGE_RATE_USD_TO_BS = $newRate;";
			if(preg_match($pattern, $content)){
				$content = preg_replace($pattern, $replacement, $content, 1);
				if(file_put_contents($configFile, $content) !== false){
					echo json_encode(['status'=>'success','message'=>'Tasa guardada en servidor','rate'=>floatval($newRate)]);
				}else{
					echo json_encode(['status'=>'error','message'=>'No se pudo escribir el archivo de configuración']);
				}
			}else{
				echo json_encode(['status'=>'error','message'=>'Patrón de tasa no encontrado en config']);
			}
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}