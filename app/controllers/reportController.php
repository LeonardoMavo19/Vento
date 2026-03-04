<?php

    namespace app\controllers;
    use app\models\mainModel;

    class reportController extends mainModel{

        /* Método público para ejecutar consultas desde la vista */
        public function ejecutarConsulta($consulta){
            return parent::ejecutarConsulta($consulta);
        }

        /* Generar reporte de cierre de caja por día */
        public function generarReporteDia($fecha, $caja_id = 0, $usuario_id = 0){
            $fecha = $this->limpiarCadena($fecha);
            $caja_id = (int) $caja_id;
            $usuario_id = (int) $usuario_id;
            if($fecha==""){
                $fecha = date('Y-m-d');
            }

            $where = "v.venta_fecha='".$fecha."'";
            if($caja_id>0){ $where .= " AND v.caja_id='".$caja_id."'"; }
            if($usuario_id>0){ $where .= " AND v.usuario_id='".$usuario_id."'"; }

            // Ventas del día
            $ventas = $this->ejecutarConsulta("SELECT v.* FROM venta v WHERE $where ORDER BY venta_hora DESC");
            $ventas = $ventas->fetchAll();

            // Totales generales
            $total_ventas = $this->ejecutarConsulta("SELECT COUNT(venta_id) as cantidad_ventas, IFNULL(SUM(venta_total),0) as total_venta, IFNULL(SUM(venta_pagado),0) as total_pagado FROM venta v WHERE $where");
            $total_ventas = $total_ventas->fetch();

            // Totales separados por moneda (BS y USD)
            $totales_bs = $this->ejecutarConsulta("SELECT COUNT(venta_id) as cantidad_ventas, IFNULL(SUM(venta_total),0) as total_venta, IFNULL(SUM(venta_pagado),0) as total_pagado FROM venta v WHERE $where AND (venta_moneda='bs' OR venta_moneda IS NULL OR venta_moneda='')");
            $totales_bs = $totales_bs->fetch();
            
            $totales_usd = $this->ejecutarConsulta("SELECT COUNT(venta_id) as cantidad_ventas, IFNULL(SUM(venta_total),0) as total_venta, IFNULL(SUM(venta_pagado),0) as total_pagado FROM venta v WHERE $where AND venta_moneda='usd'");
            $totales_usd = $totales_usd->fetch();

            // Productos más vendidos - agrupados por nombre
            $top_productos = $this->ejecutarConsulta(
                "SELECT p.producto_nombre, SUM(vd.venta_detalle_cantidad) as total_cantidad, SUM(vd.venta_detalle_total) as total_venta " .
                "FROM venta_detalle vd " .
                "JOIN venta v ON vd.venta_codigo=v.venta_codigo " .
                "LEFT JOIN producto p ON vd.producto_id=p.producto_id " .
                "WHERE $where " .
                "GROUP BY p.producto_nombre ORDER BY total_cantidad DESC LIMIT 10"
            );
            $top_productos = $top_productos->fetchAll();

            // Totales por método de pago (con moneda)
            $por_metodo = $this->ejecutarConsulta("SELECT venta_metodo, COUNT(venta_id) as ventas, IFNULL(SUM(venta_total),0) as total, IFNULL(SUM(venta_pagado),0) as pagado, venta_moneda FROM venta v WHERE $where GROUP BY venta_metodo, venta_moneda");
            $por_metodo = $por_metodo->fetchAll();

            // Ganancia (beneficio) del día - separada por moneda
            $ganancia_bs = $this->ejecutarConsulta(
                "SELECT IFNULL(SUM((vd.venta_detalle_precio_venta - vd.venta_detalle_precio_compra) * vd.venta_detalle_cantidad),0) as ganancia_total " .
                "FROM venta_detalle vd " .
                "JOIN venta v ON vd.venta_codigo=v.venta_codigo " .
                "WHERE $where AND (v.venta_moneda='bs' OR v.venta_moneda IS NULL OR v.venta_moneda='')"
            );
            $ganancia_bs = $ganancia_bs->fetchColumn();
            
            $ganancia_usd = $this->ejecutarConsulta(
                "SELECT IFNULL(SUM((vd.venta_detalle_precio_venta - vd.venta_detalle_precio_compra) * vd.venta_detalle_cantidad),0) as ganancia_total " .
                "FROM venta_detalle vd " .
                "JOIN venta v ON vd.venta_codigo=v.venta_codigo " .
                "WHERE $where AND v.venta_moneda='usd'"
            );
            $ganancia_usd = $ganancia_usd->fetchColumn();
            
            // Convertir ganancia USD a BS usando la tasa de cambio
            $tasa = defined('EXCHANGE_RATE_USD_TO_BS') ? EXCHANGE_RATE_USD_TO_BS : 1;
            $ganancia_usd_en_bs = $ganancia_usd * $tasa;
            $ganancia_total_bs = $ganancia_bs + $ganancia_usd_en_bs;

            return [
                'fecha'=>$fecha,
                'ventas'=>$ventas,
                'totales'=>$total_ventas,
                'totales_bs'=>$totales_bs,
                'totales_usd'=>$totales_usd,
                'top_productos'=>$top_productos,
                'por_metodo'=>$por_metodo,
                'ganancia_bs'=>$ganancia_bs,
                'ganancia_usd'=>$ganancia_usd,
                'ganancia_total_bs'=>$ganancia_total_bs,
                'tasa'=>$tasa
            ];
        }

        /* Obtener detalle de venta por codigo */
        public function obtenerDetalleVenta($venta_codigo){
            $venta_codigo = $this->limpiarCadena($venta_codigo);
            $detalles = $this->ejecutarConsulta("SELECT vd.*, p.producto_nombre FROM venta_detalle vd LEFT JOIN producto p ON vd.producto_id=p.producto_id WHERE vd.venta_codigo='".$venta_codigo."'");
            return $detalles->fetchAll();
        }

        /* Reiniciar caja a 0.00 */
        public function reiniciarCaja(){
            $caja_datos_up = [
                ["campo_nombre"=>"caja_efectivo","campo_marcador"=>":Efectivo","campo_valor"=>"0.00"],
                ["campo_nombre"=>"caja_dolares","campo_marcador"=>":Dolares","campo_valor"=>"0.00"],
                ["campo_nombre"=>"caja_pos","campo_marcador"=>":Pos","campo_valor"=>"0.00"],
                ["campo_nombre"=>"caja_pago_movil","campo_marcador"=>":PagoMovil","campo_valor"=>"0.00"],
                ["campo_nombre"=>"caja_biopago","campo_marcador"=>":Biopago","campo_valor"=>"0.00"],
                ["campo_nombre"=>"caja_otros","campo_marcador"=>":Otros","campo_valor"=>"0.00"]
            ];
            
            $this->actualizarDatos("caja", $caja_datos_up, ["condicion_campo"=>"caja_id", "condicion_marcador"=>":ID", "condicion_valor"=>1]);
            
            return true;
        }
        
        /* Eliminar ventas antiguas (mayor a 7 días) */
        public function eliminarVentasAntiguas($dias = 7){
            $fecha_limite = date('Y-m-d', strtotime("-{$dias} days"));
            
            $ventas_antiguas = $this->ejecutarConsulta("SELECT venta_codigo FROM venta WHERE venta_fecha < '$fecha_limite'");
            $ventas_antiguas = $ventas_antiguas->fetchAll();
            
            foreach($ventas_antiguas as $v){
                $this->eliminarRegistro("venta_detalle", "venta_codigo", $v['venta_codigo']);
                $this->eliminarRegistro("venta", "venta_codigo", $v['venta_codigo']);
            }
            
            return count($ventas_antiguas);
        }

        /* Eliminar todas las ventas del día (para cierre de caja) */
        public function eliminarVentasDelDia($fecha){
            $fecha = $this->limpiarCadena($fecha);
            
            // Primero obtener los códigos de ventas del día
            $ventas_dia = $this->ejecutarConsulta("SELECT venta_codigo FROM venta WHERE venta_fecha = '$fecha'");
            $ventas_dia = $ventas_dia->fetchAll();
            
            // Eliminar detalles primero
            foreach($ventas_dia as $v){
                $this->eliminarRegistro("venta_detalle", "venta_codigo", $v['venta_codigo']);
            }
            
            // Eliminar las ventas
            $this->ejecutarConsulta("DELETE FROM venta WHERE venta_fecha = '$fecha'");
            
            return count($ventas_dia);
        }

        /* Generar CSV del reporte */
        public function generarCSV($fecha, $caja_id = 0, $usuario_id = 0){
            $data = $this->generarReporteDia($fecha, $caja_id, $usuario_id);
            $filename = "reporte_cierre_".$data['fecha'].".csv";
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename='.$filename);
            $output = fopen('php://output', 'w');
            // Cabeceras
            fputcsv($output, ['Reporte de cierre de caja - Fecha', $data['fecha']]);
            fputcsv($output, []);
            fputcsv($output, ['Totales', 'Valor']);
            fputcsv($output, ['Ventas', $data['totales']['cantidad_ventas']]);
            fputcsv($output, ['Total facturado', $data['totales']['total_venta']]);
            fputcsv($output, ['Total pagado', $data['totales']['total_pagado']]);
            fputcsv($output, []);
            fputcsv($output, ['Top productos']);
            fputcsv($output, ['Producto','Cantidad','Total']);
            foreach($data['top_productos'] as $p){ fputcsv($output, [$p['producto_nombre'] ?? 'N/D',$p['total_cantidad'],$p['total_venta']]); }
            fputcsv($output, []);
            fputcsv($output, ['Totales por metodo']);
            fputcsv($output, ['Metodo','Ventas','Total','Pagado']);
            foreach($data['por_metodo'] as $m){ fputcsv($output, [$m['venta_metodo'],$m['ventas'],$m['total'],$m['pagado']]); }
            fputcsv($output, []);
            fputcsv($output, ['Detalle ventas']);
            fputcsv($output, ['Codigo venta','Hora','Metodo','Total']);
            foreach($data['ventas'] as $v){ fputcsv($output, [$v['venta_codigo'],$v['venta_hora'],$v['venta_metodo'],$v['venta_total']]); }
            fclose($output);
            exit();
        }

    }
