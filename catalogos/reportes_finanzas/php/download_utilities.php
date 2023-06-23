<?php
    session_start();
    include_once "class.php";
    require_once('../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
    $initialDate = $_REQUEST['initialDate'];
    $finalDate = $_REQUEST['finalDate'];
    
    $arr = get_data::getDataGeneralUtilities($initialDate,$finalDate);
    //echo json_encode($arr);
    
    $nameFile = "";
    //$date_to = $date_to == "000" ? "" : date('Y-m-d', strtotime($date_to . ' + 1 day'));
    $hoy = date("Y-m-d H:i:s");
    $nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];
    $flag = false;
    $headers = array();
    $values = array();
    foreach ($arr as $r) 
    {
        foreach ($r as $k => $v) 
        {
            /* Define los titulos */
            if (!$flag) {
                switch($k){
                    case 'nombre':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Nombre</b></style>";
                    break;
                    case 'cantidad':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Cantidad</b></style>";
                    break;
                    case 'precio_venta':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Precio venta</b></style>";
                    break;
                    case 'precio_compra':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Precio compra</b></style>";
                    break;
                    case 'utilidad_unitaria':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Utilidad unitaria</b></style>";
                    break;
                    case 'utilidad_porcentaje_unitaria':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Utilidad unitaria %</b></style>";
                    break;
                    case 'utilidad_total':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Utilidad total</b></style>";
                    break;
                    case 'utilidad_porcentaje_total':
                        $headers[] = "<style bgcolor=\"#FFFF00\"><b>Utilidad total %</b></style>";
                    break;
                }
                //$headers[] = "<style bgcolor=\"#FFFF00\"><b>" . $k . "</b></style>";
            }
            /* Da formato de moneda */
            switch ($k) {
                case 'precio_venta':
                    $r->precio_venta =  number_format($v, 2, '.', '');
                case 'precio_compra':
                    $r->precio_compra =  number_format($v, 2, '.', '');
                break;
                case 'utilidad_unitaria':
                    $r->utilidad_unitaria =  number_format($v, 2, '.', '');
                break;
                case 'utilidad_porcentaje_unitaria':
                    $r->utilidad_porcentaje_unitaria =  number_format($v, 2, '.', '');
                break;
                case 'utilidad_total':
                    $r->utilidad_total =  number_format($v, 2, '.', '');
                break;
                case 'utilidad_porcentaje_total':
                    $r->utilidad_porcentaje_total =  number_format($v, 2, '.', '');
                break;
                
            }
            
        }
        $values[] = $r;

        $flag = true;
    }

    $tabla[] = $headers;
    $tabla = array_merge($tabla, $values);
    
    $xlsx = Shuchkin\SimpleXLSXGen::fromArray($tabla, "Reporte de utilidades");
    $xlsx->downloadAs('Reporte de utilidades ' . $nameFile . '.xlsx');
       
    
?>