<?php

    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\reportController;

    if(isset($_POST['action'])){
        $insReport = new reportController();

        if($_POST['action']=="detalleVenta"){
            $codigo = isset($_POST['venta_codigo']) ? $_POST['venta_codigo'] : '';
            $det = $insReport->obtenerDetalleVenta($codigo);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status'=>1,'data'=>$det]);
            exit();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }

