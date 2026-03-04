<?php

	const APP_URL="http://localhost/vento/";
	const APP_NAME="VENTO";
	const APP_SESSION_NAME="POS";

	/*----------  Tipos de documentos  ----------*/
	const DOCUMENTOS_USUARIOS=["DUI","DNI","Cedula","Licencia","Pasaporte","Otro"];


	/*----------  Tipos de unidades de productos  ----------*/
	const PRODUCTO_UNIDAD=["Unidad","Libra","Kilogramo","Caja","Paquete","Lata","Galon","Botella","Tira","Sobre","Bolsa","Saco","Tarjeta","Otro"];

	/*----------  Configuración de moneda (moneda principal: Bs) ----------*/
	const MONEDA_SIMBOLO="Bs";
	const MONEDA_NOMBRE="bs";
	const MONEDA_DECIMALES="2";
	const MONEDA_SEPARADOR_MILLAR=",";
	const MONEDA_SEPARADOR_DECIMAL=".";

	/*----------  Tasa de conversión USD -> Bs (manual por defecto) ----------*/
	const EXCHANGE_RATE_SOURCE = "manual"; // 'manual' o 'api'
	const EXCHANGE_RATE_USD_TO_BS = 396.37; // Cambia este valor por la tasa real


	/*----------  Marcador de campos obligatorios (Font Awesome) ----------*/
	const CAMPO_OBLIGATORIO='&nbsp; <i class="fas fa-edit"></i> &nbsp;';

	/*----------  Zona horaria  ----------*/
	date_default_timezone_set("america/Caracas");

	