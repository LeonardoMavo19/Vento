<div class="container is-fluid mb-6">
	<h1 class="title">Ventas</h1>
	<h2 class="subtitle"><i class="fas fa-cart-plus fa-fw"></i> &nbsp; Nueva venta</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        $check_empresa=$insLogin->seleccionarDatos("Normal","empresa LIMIT 1","*",0);

        if($check_empresa->rowCount()==1){
            $check_empresa=$check_empresa->fetch();
    ?>
    <div class="columns">

        <div class="column pb-6">

            <p class="has-text-centered pt-6 pb-6">
                <small>Para agregar productos debe de digitar el código de barras en el campo "Código de producto" y luego presionar &nbsp; <strong class="is-uppercase" ><i class="far fa-check-circle"></i> &nbsp; Agregar producto</strong>. También puede agregar el producto mediante la opción &nbsp; <strong class="is-uppercase"><i class="fas fa-search"></i> &nbsp; Buscar producto</strong>. Ademas puede escribir el código de barras y presionar la tecla <strong class="is-uppercase">enter</strong></small>
            </p>
            <form class="pt-6 pb-6" id="sale-barcode-form" autocomplete="off">
                <div class="columns">
                    <div class="column is-one-quarter">
                        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-product" ><i class="fas fa-search"></i> &nbsp; Buscar producto</button>
                    </div>
                    <div class="column">
                        <div class="field is-grouped">
                            <p class="control is-expanded">
                                <input class="input" type="text" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70"  autofocus="autofocus" placeholder="Código de barras" id="sale-barcode-input" >
                            </p>
                            <a class="control">
                                <button type="submit" class="button is-info">
                                    <i class="far fa-check-circle"></i> &nbsp; Agregar producto
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                if(isset($_SESSION['alerta_producto_agregado']) && $_SESSION['alerta_producto_agregado']!=""){
                    echo '
                    <div class="notification is-success is-light">
                      '.$_SESSION['alerta_producto_agregado'].'
                    </div>
                    ';
                    unset($_SESSION['alerta_producto_agregado']);
                }

                if(isset($_SESSION['venta_codigo_factura']) && $_SESSION['venta_codigo_factura']!=""){
            ?>
            <div class="notification is-info is-light mb-2 mt-2">
                <h4 class="has-text-centered has-text-weight-bold">Venta realizada</h4>
                <p class="has-text-centered mb-2">La venta se realizó con éxito. ¿Que desea hacer a continuación? </p>
                <br>
                <div class="container">
                    <div class="columns">
                        <div class="column has-text-centered">
                            <button type="button" class="button is-link is-light" onclick="print_ticket('<?php echo APP_URL."app/pdf/ticket.php?code=".$_SESSION['venta_codigo_factura']; ?>')" >
                                <i class="fas fa-receipt fa-2x"></i> &nbsp;
                                Imprimir ticket de venta
                            </buttona>
                        </div>
                        <div class="column has-text-centered">
                            <button type="button" class="button is-link is-light" onclick="print_invoice('<?php echo APP_URL."app/pdf/invoice.php?code=".$_SESSION['venta_codigo_factura']; ?>')" >
                                <i class="fas fa-file-invoice-dollar fa-2x"></i> &nbsp;
                                Imprimir factura de venta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    unset($_SESSION['venta_codigo_factura']);
                }
            ?>
            <div class="table-container">
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered">#</th>
                            <th class="has-text-centered">Código de barras</th>
                            <th class="has-text-centered">Producto</th>
                            <th class="has-text-centered">Cant.</th>
                            <th class="has-text-centered">Precio</th>
                            <th class="has-text-centered">Subtotal</th>
                            <th class="has-text-centered">Actualizar</th>
                            <th class="has-text-centered">Remover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_SESSION['datos_producto_venta']) && count($_SESSION['datos_producto_venta'])>=1){

                                $_SESSION['venta_total']=0;
                                $cc=1;

                                foreach($_SESSION['datos_producto_venta'] as $productos){
                        ?>
                        <tr class="has-text-centered" >
                            <td><?php echo $cc; ?></td>
                            <td><?php echo $productos['producto_codigo']; ?></td>
                            <td><?php echo $productos['venta_detalle_descripcion']; ?></td>
                            <td>
                                <div class="control">
                                    <input class="input sale_input-cant has-text-centered" value="<?php echo $productos['venta_detalle_cantidad']; ?>" id="sale_input_<?php echo str_replace(" ", "_", $productos['producto_codigo']); ?>" type="text" style="max-width: 80px;">
                                </div>
                            </td>
                            <td>
                                <span class="price-display" data-usd="<?php echo $productos['venta_detalle_precio_venta']; ?>">
                                    <?php echo '$'.number_format($productos['venta_detalle_precio_venta'],2,'.',','); ?>
                                </span>
                            </td>
                            <td>
                                <span class="subtotal-display" data-usd="<?php echo $productos['venta_detalle_total']; ?>">
                                    <?php echo '$'.number_format($productos['venta_detalle_total'],2,'.',','); ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="button is-success is-rounded is-small" onclick="actualizar_cantidad('#sale_input_<?php echo str_replace(" ", "_", $productos['producto_codigo']); ?>','<?php echo $productos['producto_codigo']; ?>')" >
                                    <i class="fas fa-redo-alt fa-fw"></i>
                                </button>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ventaAjax.php" method="POST" autocomplete="off">

                                    <input type="hidden" name="producto_codigo" value="<?php echo $productos['producto_codigo']; ?>">
                                    <input type="hidden" name="modulo_venta" value="remover_producto">

                                    <button type="submit" class="button is-danger is-rounded is-small" title="Remover producto">
                                        <i class="fas fa-trash-restore fa-fw"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                                $cc++;
                                $_SESSION['venta_total']+=$productos['venta_detalle_total'];
                            }
                        ?>
                        <tr class="has-text-centered" >
                            <td colspan="4"></td>
                            <td class="has-text-weight-bold">
                                TOTAL
                            </td>
                            <td class="has-text-weight-bold">
                                <span id="venta_total_display" data-usd="<?php echo $_SESSION['venta_total']; ?>">
                                    <?php echo '$'.number_format($_SESSION['venta_total'],2,'.',','); ?>
                                </span>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <?php
                            }else{
                                    $_SESSION['venta_total']=0;
                        ?>
                        <tr class="has-text-centered" >
                            <td colspan="8">
                                No hay productos agregados
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="column is-one-quarter">
            <h2 class="title has-text-centered">Datos de la venta</h2>
            <hr>

            <?php if($_SESSION['venta_total']>0){ ?>
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ventaAjax.php" method="POST" autocomplete="off" name="formsale" >
                <input type="hidden" name="modulo_venta" value="registrar_venta">
            <?php }else { ?>
            <form name="formsale">
            <?php } ?>

                <div class="control mb-5">
                    <label>Fecha</label>
                    <input class="input" type="date" value="<?php echo date("Y-m-d"); ?>" readonly >
                </div>

                <label>Caja de ventas <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                <div class="select mb-5">
                    <select name="venta_caja">
                        <?php
                            $datos_cajas=$insLogin->seleccionarDatos("Normal","caja","*",0);

                            while($campos_caja=$datos_cajas->fetch()){
                                if($campos_caja['caja_id']==$_SESSION['caja']){
                                    echo '<option value="'.$campos_caja['caja_id'].'" selected="" >Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].' (Actual)</option>';
                                }else{
                                    echo '<option value="'.$campos_caja['caja_id'].'">Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <br>

                <label>Cliente</label>
                <?php
                    if(isset($_SESSION['datos_cliente_venta']) && count($_SESSION['datos_cliente_venta'])>=1 && $_SESSION['datos_cliente_venta']['cliente_id']!=1){
                ?>
                <div class="field has-addons mb-5">
                    <div class="control">
                        <input class="input" type="text" readonly id="venta_cliente" value="<?php echo $_SESSION['datos_cliente_venta']['cliente_nombre']." ".$_SESSION['datos_cliente_venta']['cliente_apellido']; ?>" >
                    </div>
                    <div class="control">
                        <a class="button is-danger" title="Remove cliente" id="btn_remove_client" onclick="remover_cliente(<?php echo $_SESSION['datos_cliente_venta']['cliente_id']; ?>)">
                            <i class="fas fa-user-times fa-fw"></i>
                        </a>
                    </div>
                </div>
                <?php 
                    }else{
                        $datos_cliente=$insLogin->seleccionarDatos("Normal","cliente WHERE cliente_id='1'","*",0);
                        if($datos_cliente->rowCount()==1){
                            $datos_cliente=$datos_cliente->fetch();

                            $_SESSION['datos_cliente_venta']=[
                                "cliente_id"=>$datos_cliente['cliente_id'],
                                "cliente_tipo_documento"=>$datos_cliente['cliente_tipo_documento'],
                                "cliente_numero_documento"=>$datos_cliente['cliente_numero_documento'],
                                "cliente_nombre"=>$datos_cliente['cliente_nombre'],
                                "cliente_apellido"=>$datos_cliente['cliente_apellido']
                            ];

                        }else{
                            $_SESSION['datos_cliente_venta']=[
                                "cliente_id"=>1,
                                "cliente_tipo_documento"=>"N/A",
                                "cliente_numero_documento"=>"N/A",
                                "cliente_nombre"=>"Publico",
                                "cliente_apellido"=>"General"
                            ];
                        }
                ?>
                <div class="field has-addons mb-5">
                    <div class="control">
                        <input class="input" type="text" readonly id="venta_cliente" value="<?php echo $_SESSION['datos_cliente_venta']['cliente_nombre']." ".$_SESSION['datos_cliente_venta']['cliente_apellido']; ?>" >
                    </div>
                    <div class="control">
                        <a class="button is-info js-modal-trigger" data-target="modal-js-client" title="Agregar cliente" id="btn_add_client" >
                            <i class="fas fa-user-plus fa-fw"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>

                <div class="control mb-5">
                    <label>Total pagado por cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="venta_abono" id="venta_abono" value="0.00" pattern="[0-9.]{1,25}" maxlength="25" >
                </div>

                <div class="control mb-5">
                    <label>Método de pago</label>
                    <div class="select">
                        <select name="venta_metodo" id="venta_metodo">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Punto de venta">Punto de venta</option>
                            <option value="Pago movil">Pago movil</option>
                            <option value="Biopago">Biopago</option>
                            <option value="Dólares">Dólares</option>
                        </select>
                    </div>
                </div>

                <div class="control mb-5">
                    <label>Moneda</label>
                    <div class="select">
                        <select name="venta_moneda" id="venta_moneda">
                            <option value="bs">Bs</option>
                            <option value="usd" selected>USD</option>
                        </select>
                    </div>
                </div>

                <div class="control mb-5">
                    <label>Cambio devuelto a cliente</label>
                    <input class="input" type="text" id="venta_cambio" value="0.00" readonly >
                </div>

                <div class="has-text-centered mb-5">
                    <h4 class="subtitle is-5 has-text-weight-bold"><small>TOTAL A PAGAR: <span id="total_to_pay_display" data-usd="<?php echo $_SESSION['venta_total']; ?>"><?php echo '$'.number_format($_SESSION['venta_total'],2,'.',','); ?></span></small></h4>
                </div>

                <?php if($_SESSION['venta_total']>0){ ?>
                <p class="has-text-centered">
                    <button type="button" class="button is-warning is-rounded" id="btn_multiple_payments"><i class="fas fa-money-check-alt"></i> &nbsp; Pagos múltiples</button>
                    &nbsp;
                    <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar venta</button>
                </p>
                <?php } ?>
                <p class="has-text-centered pt-6">
                    <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                </p>
                <input type="hidden" value="<?php echo number_format($_SESSION['venta_total'],MONEDA_DECIMALES,'.',''); ?>" id="venta_total_hidden">
            </form>
        </div>

    </div>
    <?php }else{ ?>
        <article class="message is-warning">
             <div class="message-header">
                <p>¡Ocurrio un error inesperado!</p>
             </div>
            <div class="message-body has-text-centered"><i class="fas fa-exclamation-triangle fa-2x"></i><br>No hemos podio seleccionar algunos datos sobre la empresa, por favor <a href="<?php echo APP_URL; ?>companyNew/" >verifique aquí los datos de la empresa</div>
        </article>
    <?php } ?>
</div>

<!-- Modal buscar producto -->
<div class="modal" id="modal-js-product">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Buscar producto</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="field mt-6 mb-6">
                <label class="label">Nombre, marca, modelo</label>
                <div class="control">
                    <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_codigo" id="input_codigo" maxlength="30" >
                </div>
            </div>
            <div class="container" id="tabla_productos"></div>
            <p class="has-text-centered">
                <button type="button" class="button is-link is-light" onclick="buscar_codigo()" ><i class="fas fa-search"></i> &nbsp; Buscar</button>
            </p>
        </section>
    </div>
</div>

<!-- Modal buscar cliente -->
<div class="modal" id="modal-js-client">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Buscar y agregar cliente</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="field mt-6 mb-6">
                <label class="label">Documento, Nombre, Apellido, Teléfono</label>
                <div class="control">
                    <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_cliente" id="input_cliente" maxlength="30" >
                </div>
            </div>
            <div class="container" id="tabla_clientes"></div>
            <p class="has-text-centered">
                <button type="button" class="button is-link is-light" onclick="buscar_cliente()" ><i class="fas fa-search"></i> &nbsp; Buscar</button>
            </p>
        </section>
    </div>
</div>

<script>

    /* Detectar cuando se envia el formulario para agregar producto */
    let sale_form_barcode = document.querySelector("#sale-barcode-form");
    sale_form_barcode.addEventListener('submit', function(event){
        event.preventDefault();
        setTimeout('agregar_producto()',100);
    });


    /* Detectar cuando escanea un codigo en formulario para agregar producto */
    let sale_input_barcode = document.querySelector("#sale-barcode-input");
    sale_input_barcode.addEventListener('paste',function(){
        setTimeout('agregar_producto()',100);
    });


    /* Agregar producto */
    function agregar_producto(){
        let codigo_producto=document.querySelector('#sale-barcode-input').value;

        codigo_producto=codigo_producto.trim();

        if(codigo_producto!=""){
            let datos = new FormData();
            datos.append("producto_codigo", codigo_producto);
            datos.append("modulo_venta", "agregar_producto");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(respuesta =>{
                return alertas_ajax(respuesta);
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el código del producto',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Buscar codigo  ----------*/
    function buscar_codigo(){
        let input_codigo=document.querySelector('#input_codigo').value;

        input_codigo=input_codigo.trim();

        if(input_codigo!=""){

            let datos = new FormData();
            datos.append("buscar_codigo", input_codigo);
            datos.append("modulo_venta", "buscar_codigo");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let tabla_productos=document.querySelector('#tabla_productos');
                tabla_productos.innerHTML=respuesta;
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el Nombre, Marca o Modelo del producto',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Agregar codigo  ----------*/
    function agregar_codigo($codigo){
        document.querySelector('#sale-barcode-input').value=$codigo;
        setTimeout('agregar_producto()',100);
    }


    /* Actualizar cantidad de producto */
    function actualizar_cantidad(id,codigo){
        let cantidad=document.querySelector(id).value;

        cantidad=cantidad.trim();
        codigo.trim();

        if(cantidad>0){

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Desea actualizar la cantidad de productos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, actualizar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed){

                    let datos = new FormData();
                    datos.append("producto_codigo", codigo);
                    datos.append("producto_cantidad", cantidad);
                    datos.append("modulo_venta", "actualizar_producto");

                    fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                        method: 'POST',
                        body: datos
                    })
                    .then(respuesta => respuesta.json())
                    .then(respuesta =>{
                        return alertas_ajax(respuesta);
                    });
                }
            });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir una cantidad mayor a 0',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Buscar cliente  ----------*/
    function buscar_cliente(){
        let input_cliente=document.querySelector('#input_cliente').value;

        input_cliente=input_cliente.trim();

        if(input_cliente!=""){

            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);
            datos.append("modulo_venta", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let tabla_clientes=document.querySelector('#tabla_clientes');
                tabla_clientes.innerHTML=respuesta;
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el Numero de documento, Nombre, Apellido o Teléfono del cliente',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Agregar cliente  ----------*/
    function agregar_cliente(id){

        Swal.fire({
            title: '¿Quieres agregar este cliente?',
            text: "Se va a agregar este cliente para realizar una venta",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, agregar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed){

                let datos = new FormData();
                datos.append("cliente_id", id);
                datos.append("modulo_venta", "agregar_cliente");

                fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });

            }
        });
    }


    /*----------  Remover cliente  ----------*/
    function remover_cliente(id){

        Swal.fire({
            title: '¿Quieres remover este cliente?',
            text: "Se va a quitar el cliente seleccionado de la venta",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, remover',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed){

                let datos = new FormData();
                datos.append("cliente_id", id);
                datos.append("modulo_venta", "remover_cliente");

                fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });

            }
        });
    }

    /*----------  Calcular cambio  ----------*/
    let venta_abono_input = document.querySelector("#venta_abono");
    venta_abono_input.addEventListener('keyup', function(e){
        e.preventDefault();
        // parse numeric with comma support
        function pnum(v){ if(v===undefined||v===null) return 0; v=v.toString().trim().replace(',','.'); return parseFloat(v) || 0; }

        let abono = pnum(document.querySelector('#venta_abono').value);
        // base total is stored in USD in session
        let total_usd = pnum(document.querySelector('#venta_total_hidden').value);

        let moneda_sel = document.querySelector('#venta_moneda');
        let exchange_rate = parseFloat(localStorage.getItem('exchangeRate')) || <?php echo defined('EXCHANGE_RATE_USD_TO_BS')?EXCHANGE_RATE_USD_TO_BS:1.00; ?>;

        if(moneda_sel && moneda_sel.value=="usd"){
            // Working in USD
            if(abono>=total_usd){
                let cambio_usd = abono - total_usd;
                document.querySelector('#venta_cambio').value = parseFloat(cambio_usd).toFixed(2);
            }else{
                document.querySelector('#venta_cambio').value = "0.00";
            }
        }else{
            // Working in Bs: convert total_usd to Bs for comparison
            let total_bs = total_usd * parseFloat(exchange_rate);
            if(abono>=total_bs){
                let cambio_bs = abono - total_bs;
                document.querySelector('#venta_cambio').value = parseFloat(cambio_bs).toFixed(<?php echo MONEDA_DECIMALES; ?>);
            }else{
                document.querySelector('#venta_cambio').value = "0.00";
            }
        }
    });

    /* Sincronizar método -> moneda (si seleccionan Dólares) */
    let venta_metodo_sel = document.querySelector("#venta_metodo");
    let venta_moneda_sel = document.querySelector("#venta_moneda");
    if(venta_metodo_sel && venta_moneda_sel){
        venta_metodo_sel.addEventListener('change', function(){
            if(this.value=="Dólares"){
                venta_moneda_sel.value='usd';
            }else{
                venta_moneda_sel.value='bs';
            }
        });
    }

</script>

<!-- Modal Pagos Múltiples -->
<div class="modal" id="modal-multiple-payments">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Pagos múltiples</p>
            <button class="delete" aria-label="close" id="mp_close"></button>
        </header>
        <section class="modal-card-body">
            <p>Total a pagar: <strong id="mp_total_display"></strong></p>
            <div class="columns mt-4">
                <div class="column">
                    <label>Método 1</label>
                    <div class="select"><select id="mp_method_1"><option>Efectivo</option><option>Punto de venta</option><option>Pago movil</option><option>Biopago</option><option>Dólares</option></select></div>
                </div>
                <div class="column">
                    <label>Monto 1</label>
                    <input id="mp_amount_1" class="input" value="0.00">
                </div>
            </div>
            <div class="columns mt-2">
                <div class="column">
                    <label>Método 2</label>
                    <div class="select"><select id="mp_method_2"><option>Efectivo</option><option>Punto de venta</option><option>Pago movil</option><option>Biopago</option><option>Dólares</option></select></div>
                </div>
                <div class="column">
                    <label>Monto 2</label>
                    <input id="mp_amount_2" class="input" value="0.00">
                </div>
            </div>
            <p class="mt-3">Restante: <strong id="mp_remaining_display">0.00</strong>
                <button type="button" class="button is-small is-light" id="mp_toggle_remaining" style="margin-left:8px;">Ver en Bs</button>
            </p>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-success" id="mp_confirm">Guardar venta</button>
            <button class="button" id="mp_cancel">Cancelar</button>
        </footer>
    </div>
</div>

<script>
// Multiple payments modal logic
(function(){
    const modal = document.getElementById('modal-multiple-payments');
    const btnOpen = document.getElementById('btn_multiple_payments');
    const btnClose = document.getElementById('mp_close');
    const btnCancel = document.getElementById('mp_cancel');
    const btnConfirm = document.getElementById('mp_confirm');
    const totalUsd = parseFloat(document.getElementById('venta_total_hidden').value) || 0; // base USD
    const rate = parseFloat(localStorage.getItem('exchangeRate')) || <?php echo defined('EXCHANGE_RATE_USD_TO_BS')?EXCHANGE_RATE_USD_TO_BS:1.00; ?>;

    function openModal(){
        document.getElementById('mp_total_display').textContent = '$'+Number(totalUsd).toFixed(2);
        document.getElementById('mp_amount_1').value = (Math.floor(totalUsd*100)/100).toFixed(2);
        document.getElementById('mp_amount_2').value = '0.00';
        modal.classList.add('is-active');
        updateRemaining();
    }
    function closeModal(){ modal.classList.remove('is-active'); }

    function parseNumeric(v){ if(v===undefined||v===null) return 0; v=v.toString().trim().replace(',','.'); return parseFloat(v) || 0; }

    function paymentToUsd(method, amount){
        amount = parseNumeric(amount);
        if(method === 'Dólares') return amount; // already USD
        // otherwise assume Bs -> convert to USD
        return amount / parseFloat(rate);
    }

    let mp_show_bs = false;
    function updateRemaining(){
        const m1 = document.getElementById('mp_method_1').value;
        const a1 = document.getElementById('mp_amount_1').value;
        const m2 = document.getElementById('mp_method_2').value;
        const a2 = document.getElementById('mp_amount_2').value;
        const paidUsd = paymentToUsd(m1,a1) + paymentToUsd(m2,a2);
        const remaining = Math.max(0, totalUsd - paidUsd);
        const remEl = document.getElementById('mp_remaining_display');
        if(mp_show_bs){
            const remainingBs = remaining * parseFloat(rate);
            remEl.textContent = (remaining>0? ( '<?php echo MONEDA_SIMBOLO; ?>'+Number(remainingBs).toFixed(<?php echo MONEDA_DECIMALES; ?>) ) : ('<?php echo MONEDA_SIMBOLO; ?>0.00'));
        }else{
            remEl.textContent = (remaining>0? '$'+remaining.toFixed(2) : '$0.00');
        }
    }

    if(btnOpen) btnOpen.addEventListener('click', openModal);
    if(btnClose) btnClose.addEventListener('click', closeModal);
    if(btnCancel) btnCancel.addEventListener('click', closeModal);

    ['mp_method_1','mp_method_2','mp_amount_1','mp_amount_2'].forEach(id=>{
        const el = document.getElementById(id);
        if(el) el.addEventListener('input', updateRemaining);
        if(el) el.addEventListener('change', updateRemaining);
    });

    btnConfirm.addEventListener('click', function(){
        const m1 = document.getElementById('mp_method_1').value;
        const a1 = parseNumeric(document.getElementById('mp_amount_1').value);
        const m2 = document.getElementById('mp_method_2').value;
        const a2 = parseNumeric(document.getElementById('mp_amount_2').value);
        const paidUsd = paymentToUsd(m1,a1) + paymentToUsd(m2,a2);
        if(paidUsd < totalUsd - 0.0001){
            Swal.fire({icon:'error',title:'Pago insuficiente',text:'La suma de los pagos es menor al total.'});
            return;
        }

        // Build payments payload
        const payments = [
            {method: m1, amount: a1, currency: (m1==='Dólares'?'usd':'bs')},
            {method: m2, amount: a2, currency: (m2==='Dólares'?'usd':'bs')}
        ];

        // Prepare form data and submit via AJAX to save the sale immediately
        const form = document.querySelector('form[name="formsale"]') || document.querySelector('form.FormularioAjax');
        if(!form){
            Swal.fire({icon:'error',title:'Error',text:'Formulario de venta no encontrado.'});
            return;
        }

        // venta_metodo: concatenated
        let mstr = payments.map(p=>p.method+': '+Number(p.amount).toFixed(2)+' '+(p.currency==='usd'?'USD':'Bs')).join(' | ');

        // compute total paid in sale currency
        const saleCurrency = (document.getElementById('venta_moneda') && document.getElementById('venta_moneda').value==='bs') ? 'bs' : 'usd';
        let totalPaidInSaleCurrency = 0;
        if(saleCurrency==='usd'){
            totalPaidInSaleCurrency = paidUsd;
        }else{
            totalPaidInSaleCurrency = paidUsd * parseFloat(rate);
        }

        // Build FormData from form and override/add needed fields
        const fd = new FormData(form);
        fd.set('venta_payments', JSON.stringify(payments));
        fd.set('venta_metodo', mstr);
        fd.set('venta_abono', Number(totalPaidInSaleCurrency).toFixed(2));
        fd.set('modulo_venta','registrar_venta');

        // Confirm save and send
        Swal.fire({
            title: '¿Guardar venta?',
            text: 'Se registrará la venta con las formas de pago indicadas.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((res)=>{
            if(res.isConfirmed){
                // Optional: close modal to indicate progress
                closeModal();
                // disable submit buttons to avoid duplicate submits
                const btns = form.querySelectorAll('button, input[type=submit]');
                btns.forEach(b=>b.disabled=true);
                fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{ method: 'POST', body: fd })
                .then(r=>r.json())
                .then(response => {
                    alertas_ajax(response);
                }).catch(err=>{
                    btns.forEach(b=>b.disabled=false);
                    Swal.fire({icon:'error',title:'Error',text:'No se pudo contactar al servidor'});
                });
            }
        });
    });

    // toggle remaining currency button
    const mpToggle = document.getElementById('mp_toggle_remaining');
    if(mpToggle){
        mpToggle.addEventListener('click', function(){
            mp_show_bs = !mp_show_bs;
            this.textContent = mp_show_bs ? 'Ver en USD' : 'Ver en Bs';
            updateRemaining();
        });
    }

})();
</script>

<?php
    include "./app/views/inc/print_invoice_script.php";
?>

<script>
    (function(){
        const MONEDA_DECIMALS = <?php echo MONEDA_DECIMALES; ?>;
        let storedRate = localStorage.getItem('exchangeRate');
        if(storedRate && typeof storedRate === 'string') storedRate = storedRate.replace(',','.');
        let exchangeRate = parseFloat(storedRate) || <?php echo defined('EXCHANGE_RATE_USD_TO_BS')?EXCHANGE_RATE_USD_TO_BS:1.00; ?>;

        const exchangeInput = document.getElementById('exchange_rate_input');
        const saveBtn = document.getElementById('save_exchange_rate_btn');
        if(exchangeInput){ exchangeInput.value = exchangeRate; }
        if(saveBtn){
            saveBtn.addEventListener('click', function(){
                let raw = (exchangeInput.value||'').toString().trim().replace(',','.');
                let v = parseFloat(raw);
                if(!isNaN(v) && v>0){
                    localStorage.setItem('exchangeRate', v);
                    exchangeRate = v;
                    updateAllPrices();
                    // Guardar también en servidor
                    fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                        method: 'POST',
                        headers: {'Accept':'application/json'},
                        body: (function(){ let fd=new FormData(); fd.append('modulo_venta','guardar_tasa'); fd.append('exchange_rate', v); return fd; })()
                    })
                    .then(r=>r.json())
                    .then(resp=>{
                        if(resp.status && resp.status==='success'){
                            Swal.fire({icon:'success',title:'Tasa actualizada',text:'Tasa guardada: '+v});
                        }else{
                            Swal.fire({icon:'warning',title:'Tasa guardada localmente',text: (resp.message?resp.message:'No se guardó en servidor')});
                        }
                    }).catch(()=>{
                        Swal.fire({icon:'warning',title:'Tasa guardada localmente',text:'No se pudo contactar al servidor'});
                    });
                }else{
                    Swal.fire({icon:'error',title:'Tasa inválida',text:'Introduce un valor numérico mayor a 0'});
                }
            });
        }

        const ventaMonedaSel = document.getElementById('venta_moneda');
        // si cambian la moneda, actualizar la vista
        if(ventaMonedaSel){
            ventaMonedaSel.addEventListener('change', function(){
                updateAllPrices();
            });
        }

        function formatBs(value){ return '<?php echo MONEDA_SIMBOLO; ?>'+Number(value).toLocaleString(undefined,{minimumFractionDigits:<?php echo MONEDA_DECIMALES; ?>,maximumFractionDigits:<?php echo MONEDA_DECIMALES; ?>})+' <?php echo MONEDA_NOMBRE; ?>'; }
        function formatUsd(value){ return '$'+Number(value).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); }
        function parseNumeric(v){ if(v===undefined || v===null) return 0; try{ v = v.toString().replace(',','.'); return parseFloat(v) || 0; }catch(e){ return 0; } }

        function updateAllPrices(){
            const rate = parseFloat(localStorage.getItem('exchangeRate')) || exchangeRate;

            // Precios (precio unitario) base USD
            const currentDisplay = (ventaMonedaSel && ventaMonedaSel.value==='bs') ? 'bs' : 'usd';
            document.querySelectorAll('.price-display').forEach(el=>{
                const usd = parseNumeric(el.dataset.usd);
                if(currentDisplay==='usd'){
                    el.textContent = formatUsd(usd);
                }else{
                    el.textContent = formatBs(usd * rate);
                }
            });

            // Subtotales (base USD)
            document.querySelectorAll('.subtotal-display').forEach(el=>{
                const usd = parseNumeric(el.dataset.usd);
                if(currentDisplay==='usd'){
                    el.textContent = formatUsd(usd);
                }else{
                    el.textContent = formatBs(usd * rate);
                }
            });

            // Total en la tabla
            const totalDisplay = document.getElementById('venta_total_display');
            if(totalDisplay){
                const usd = parseFloat(totalDisplay.dataset.usd) || 0;
                const currentDisplay = (ventaMonedaSel && ventaMonedaSel.value==='bs') ? 'bs' : 'usd';
                if(currentDisplay==='usd'){
                    totalDisplay.textContent = formatUsd(usd);
                }else{
                    totalDisplay.textContent = formatBs(usd * rate);
                }
            }

            // Total a pagar en el panel derecho
            const totalToPay = document.getElementById('total_to_pay_display');
            if(totalToPay){
                const usd = parseFloat(totalToPay.dataset.usd) || 0;
                const currentDisplay = (ventaMonedaSel && ventaMonedaSel.value==='bs') ? 'bs' : 'usd';
                if(currentDisplay==='usd'){
                    totalToPay.textContent = formatUsd(usd);
                }else{
                    totalToPay.textContent = formatBs(usd * rate);
                }
            }

            // Mantener value hidden en USD (para cálculos en servidor se interpreta según venta_moneda)
            const hidden = document.getElementById('venta_total_hidden');
            if(hidden){
                const usdVal = parseNumeric(totalDisplay ? totalDisplay.dataset.usd : hidden.value) || 0;
                hidden.value = (usdVal || 0).toFixed(MONEDA_DECIMALS);
            }
        }
        // inicializar
        try{
            updateAllPrices();
        }catch(e){ console.error('updateAllPrices error', e); }

        // actualizar precios al inicio según moneda seleccionada
        updateAllPrices();
    })();
</script>