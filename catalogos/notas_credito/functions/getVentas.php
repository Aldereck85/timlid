<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$idCliente = $_REQUEST['id_cliente'];
$table="";
    $all = $conn->prepare("SELECT PKVentaDirecta,Referencia,created_at,Importe,FKEstatusVenta,ifnull(saldo_insoluto_venta,0) as saldo_insoluto_venta from ventas_directas where (empresa_id = $empresa) and estatus_factura_id not in (1,2) and FKCliente = $idCliente order by PKVentaDirecta desc");
    $all->execute();
    $array = $all->fetchAll();

    $decimas = 2;

        // Buscar el mayor numero de decimales en los precios unitarios 
        foreach($array as $p){
            $precio1 = $p['saldo_insoluto_venta'];

            // Separar parte entera del decimal 
            $explPreecio1 = explode(".",$precio1);
            // convertir la parte decimal a una array 
            $precio_split1 = str_split($explPreecio1[1]);
            // Guarda donde se detecta la ultima parte decimal 
            $flagdecimales1 = 2;
            // Cuenta las posisiones del array recorridas 
            $count1=0;

                foreach($precio_split1 as $numero1){
                    $count1++;

                    if($numero1 == 0){
                        
                    }else{
                        // Si el numero que encontro no es 0 guarda la posision en la posision de decimal 
                        $flagdecimales1 = $count1;
                    }
                }
                if($flagdecimales1 > $decimas){
                    $decimas = $flagdecimales1;
                }
        }

    //while (($row = $all->fetch()) !== false) {
        foreach($array as $row){
        $htmlAcciones = '<input name=\"checks[]\" class=\"check\" onclick=\"get_ids(this)\" type=\"radio\" value=\"'.$row['PKVentaDirecta'].'\" id=\"'.$row['PKVentaDirecta'].'\" >';
        $importe = $row['saldo_insoluto_venta'];
        

        $importe = number_format($importe, 2, ".", ",");
        
        if($row['FKEstatusVenta']=="1"){
            $estado = '<span class=\"left-dot green-dot\">Nueva</span>';
        }elseif($row['FKEstatusVenta']=="6"){
            $estado = '<span class=\"left-dot yellow-dot\">Factura pendiente</span>';
        }elseif($row['FKEstatusVenta']=="3"){
            $estado = '<span class=\"left-dot yellow-dot\">Parcialmente surtida</span>';
        }elseif($row['FKEstatusVenta']=="4"){
            $estado = '<span class=\"left-dot blue-dark-dot\">surtida completa</span>';
        }
        $table.='{"Id":"'.$row['PKVentaDirecta'].
                '","Folio venta":"'.$row['Referencia'].
                '","Fecha":"'.$row['created_at'].
                '","Monto":"'.$importe.
                '","Estado":"'.$estado.
                '","Seleccionar":"'.$htmlAcciones.
                '"},';
    }
    //print_r($Id_ultimas);
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';

?>