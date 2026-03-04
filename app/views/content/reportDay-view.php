<?php
    use app\controllers\reportController;

    $insReport = new reportController();

    $fecha = isset($_POST['report_date']) ? $_POST['report_date'] : date('Y-m-d');
    $caja_id = isset($_POST['caja_id']) ? (int)$_POST['caja_id'] : 0;
    $usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;

    // Si presionan Generar (cierre de caja)
    $es_cierre = isset($_POST['cierre_caja']) && $_POST['cierre_caja']=="1";
    
    // Primero obtener los datos del día (antes de borrar)
    $data = $insReport->generarReporteDia($fecha, $caja_id, $usuario_id);
    
    if($es_cierre){
        // Reiniciar caja a 0.00
        $insReport->reiniciarCaja();
        // Eliminar ventas antiguas (más de 7 días)
        $ventas_eliminadas = $insReport->eliminarVentasAntiguas(7);
    }

    // Obtener listas para filtros
    $cajas = $insReport->ejecutarConsulta("SELECT caja_id, caja_nombre FROM caja ORDER BY caja_nombre ASC");
    $cajas = $cajas->fetchAll();
    $usuarios = $insReport->ejecutarConsulta("SELECT usuario_id, usuario_nombre FROM usuario ORDER BY usuario_nombre ASC");
    $usuarios = $usuarios->fetchAll();
?>

<div class="container is-fluid mb-6">
    <h1 class="title">Reporte - Cierre de caja</h1>
    <h2 class="subtitle">Resumen diario y análisis</h2>
</div>

<div class="container pb-6 pt-6">

    <form method="post">
        <div class="field has-addons">
            <div class="control">
                <input class="input" type="date" name="report_date" value="<?php echo $data['fecha']; ?>">
            </div>
            <div class="control">
                <div class="select">
                    <select name="caja_id">
                        <option value="0">Todas las cajas</option>
                        <?php foreach($cajas as $c): ?>
                            <option value="<?php echo $c['caja_id']; ?>" <?php if($caja_id==$c['caja_id']) echo 'selected'; ?>><?php echo $c['caja_nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control">
                <div class="select">
                    <select name="usuario_id">
                        <option value="0">Todos los usuarios</option>
                        <?php foreach($usuarios as $u): ?>
                            <option value="<?php echo $u['usuario_id']; ?>" <?php if($usuario_id==$u['usuario_id']) echo 'selected'; ?>><?php echo $u['usuario_nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control">
                <input type="hidden" name="cierre_caja" value="1">
                <button class="button is-primary" type="submit">Generar</button>
            </div>
        </div>
    </form>

    <?php if($es_cierre): ?>
    <!-- Resumen de cierre de caja -->
    <div class="box mt-4">
        <h3 class="title is-4">📊 Resumen de Cierre de Caja - <?php echo $data['fecha']; ?></h3>
        
        <div class="columns is-multiline">
            <div class="column is-3">
                <div class="box has-background-primary-light">
                    <p class="heading">Total Ventas</p>
                    <p class="title is-3"><?php echo $data['totales']['cantidad_ventas']; ?></p>
                </div>
            </div>
            <div class="column is-3">
                <div class="box has-background-success-light">
                    <p class="heading">Total Facturado (Bs)</p>
                    <p class="title is-4"><?php echo number_format($data['totales_bs']['total_venta'],2); ?> Bs</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="box has-background-info-light">
                    <p class="heading">Total Facturado (USD)</p>
                    <p class="title is-4">$<?php echo number_format($data['totales_usd']['total_venta'],2); ?></p>
                </div>
            </div>
            <div class="column is-3">
                <div class="box has-background-warning-light">
                    <p class="heading">Total Pagado (Bs)</p>
                    <p class="title is-4"><?php echo number_format($data['totales_bs']['total_pagado'],2); ?> Bs</p>
                </div>
            </div>
        </div>
        
        <div class="columns is-multiline">
            <div class="column is-3">
                <div class="box has-background-warning-light">
                    <p class="heading">Total Pagado (USD)</p>
                    <p class="title is-4">$<?php echo number_format($data['totales_usd']['total_pagado'],2); ?></p>
                </div>
            </div>
            <div class="column is-3">
                <div class="box">
                    <p class="heading">Ganancia (Bs)</p>
                    <p class="title is-4"><?php echo number_format($data['ganancia_bs'],2); ?> Bs</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="box">
                    <p class="heading">Ganancia (USD)</p>
                    <p class="title is-4">$<?php echo number_format($data['ganancia_usd'],2); ?></p>
                </div>
            </div>
            <div class="column is-3">
                <div class="box has-background-danger-light">
                    <p class="heading">Ganancia Total (Bs)</p>
                    <p class="title is-4"><?php echo number_format($data['ganancia_total_bs'],2); ?> Bs</p>
                    <p class="subtitle is-6">Tasa: <?php echo number_format($data['tasa'],2); ?> Bs/USD</p>
                </div>
            </div>
        </div>

        <div class="notification is-success is-light">
            <p><strong>✅ Cierre de caja realizado con éxito</strong></p>
        </div>
    </div>
    <?php else: ?>

    <!-- Vista normal con tablas detalladas -->
    <div class="box mt-4">
        <h3 class="title is-5">Totales del día (<?php echo $data['fecha']; ?>)</h3>
        <div class="columns is-multiline">
            <div class="column is-3">
                <div class="box">
                    <p class="heading">Ventas</p>
                    <p class="title"><?php echo $data['totales']['cantidad_ventas']; ?></p>
                </div>
            </div>
            <div class="column is-4">
                <div class="box">
                    <p class="heading">Total facturado (Bs)</p>
                    <p class="title"><?php echo number_format($data['totales_bs']['total_venta'],2); ?></p>
                    <p class="heading">Total facturado (USD)</p>
                    <p class="title">$<?php echo number_format($data['totales_usd']['total_venta'],2); ?></p>
                </div>
            </div>
            <div class="column is-5">
                <div class="box">
                    <p class="heading">Total pagado (Bs)</p>
                    <p class="title"><?php echo number_format($data['totales_bs']['total_pagado'],2); ?></p>
                    <p class="heading">Total pagado (USD)</p>
                    <p class="title">$<?php echo number_format($data['totales_usd']['total_pagado'],2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column is-6">
            <div class="box">
                <h4 class="title is-6">Lo vendido (ventas detalle resumido)</h4>
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Codigo venta</th>
                            <th>Hora</th>
                            <th>Método</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['ventas'] as $v): ?>
                        <tr class="sale-row" data-codigo="<?php echo $v['venta_codigo']; ?>">
                            <td><?php echo $v['venta_codigo']; ?></td>
                            <td><?php echo $v['venta_hora']; ?></td>
                            <td><?php echo $v['venta_metodo']; ?></td>
                            <td><?php echo (isset($v['venta_moneda']) && $v['venta_moneda']=='usd') ? '$' : ''; ?><?php echo number_format($v['venta_total'],2); ?> <?php echo (isset($v['venta_moneda']) && $v['venta_moneda']=='usd') ? 'USD' : 'Bs'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="column is-6">
            <div class="box">
                <h4 class="title is-6">Lo que más se vendió</h4>
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['top_productos'] as $p): ?>
                        <tr>
                            <td><?php echo $p['producto_nombre'] ?? 'N/D'; ?></td>
                            <td><?php echo $p['total_cantidad']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column is-6">
            <div class="box">
                <h4 class="title is-6">Total por método de pago</h4>
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Método</th>
                            <th>Ventas</th>
                            <th>Total</th>
                            <th>Pagado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['por_metodo'] as $m): ?>
                        <tr>
                            <td><?php echo $m['venta_metodo']; ?></td>
                            <td><?php echo $m['ventas']; ?></td>
                            <td><?php echo (isset($m['venta_moneda']) && $m['venta_moneda']=='usd') ? '$' : ''; ?><?php echo number_format($m['total'],2); ?> <?php echo (isset($m['venta_moneda']) && $m['venta_moneda']=='usd') ? 'USD' : 'Bs'; ?></td>
                            <td><?php echo (isset($m['venta_moneda']) && $m['venta_moneda']=='usd') ? '$' : ''; ?><?php echo number_format($m['pagado'],2); ?> <?php echo (isset($m['venta_moneda']) && $m['venta_moneda']=='usd') ? 'USD' : 'Bs'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="column is-6">
            <div class="box">
                <h4 class="title is-6">Ganancia estimada</h4>
                <p class="heading">Ganancia Bs</p>
                <p class="title"><?php echo number_format($data['ganancia_bs'],2); ?></p>
                <p class="heading">Ganancia USD</p>
                <p class="title">$<?php echo number_format($data['ganancia_usd'],2); ?></p>
                <p class="heading">Ganancia total (convertida a Bs)</p>
                <p class="title"><?php echo number_format($data['ganancia_total_bs'],2); ?> Bs</p>
                <p class="subtitle is-6">(Tasa de cambio: <?php echo number_format($data['tasa'],2); ?> Bs/USD)</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Modal venta detalle -->
 <div id="modalVentaDetalle" class="modal">
     <div class="modal-background"></div>
     <div class="modal-card">
         <header class="modal-card-head">
             <p class="modal-card-title">Detalle de venta <span id="modalVentaCodigo"></span></p>
             <button class="delete" aria-label="close" id="closeModal"></button>
         </header>
         <section class="modal-card-body">
             <table class="table is-fullwidth is-striped">
                 <thead>
                     <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></tr>
                 </thead>
                 <tbody id="modalVentaBody">
                 </tbody>
             </table>
         </section>
         <footer class="modal-card-foot">
             <button class="button" id="closeModal2">Cerrar</button>
         </footer>
     </div>
 </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
        const rows = document.querySelectorAll('.sale-row');
        rows.forEach(r=> r.addEventListener('click', async function(){
                const codigo = this.dataset.codigo;
                if(!codigo) return;
                // solicitar detalle via ajax
                const fd = new FormData(); fd.append('action','detalleVenta'); fd.append('venta_codigo', codigo);
                const res = await fetch('<?php echo APP_URL; ?>app/ajax/reportAjax.php', { method:'POST', body: fd });
                const json = await res.json();
                if(json.status==1){
                        const body = document.getElementById('modalVentaBody'); body.innerHTML='';
                        json.data.forEach(d=>{
                                const tr = document.createElement('tr');
                                tr.innerHTML = `<td>${d.producto_nombre||'N/D'}</td><td>${d.venta_detalle_cantidad}</td><td>${parseFloat(d.venta_detalle_precio_venta).toFixed(2)}</td><td>${parseFloat(d.venta_detalle_total).toFixed(2)}</td>`;
                                body.appendChild(tr);
                        });
                        document.getElementById('modalVentaCodigo').textContent = codigo;
                        const modal = document.getElementById('modalVentaDetalle'); modal.classList.add('is-active');
                }
        }));

        document.getElementById('closeModal').addEventListener('click', ()=>{ document.getElementById('modalVentaDetalle').classList.remove('is-active'); });
        document.getElementById('closeModal2').addEventListener('click', ()=>{ document.getElementById('modalVentaDetalle').classList.remove('is-active'); });
});
</script>
