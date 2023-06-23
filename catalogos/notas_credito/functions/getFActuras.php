<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$idCliente = $_REQUEST['id_cliente'];
$table="";
    $all = $conn->prepare("SELECT id,id_api,serie,folio,fecha_timbrado,total_facturado,estatus,saldo_insoluto from facturacion where (id_api != 'null') and (empresa_id = $empresa) and ((estatus = 1) or (estatus = 2) or (estatus = 3)) and cliente_id = $idCliente order by id desc;");
    $all->execute();
    $array = $all->fetchAll();

    $decimas = 2;

        // Buscar el mayor numero de decimales en los precios unitarios 
        foreach($array as $p){
            $precio1 = $p['saldo_insoluto'];

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
        $htmlAcciones = '<input name=\"checks[]\" class=\"check\" onclick=\"get_ids(this)\" type=\"radio\" value=\"'.$row['id'].'\" id=\"'.$row['id'].'\" >';
        $importe = $row['saldo_insoluto'];
        

        $importe = number_format($importe, 2, ".", ",");
        
        if($row['estatus']=="1"){
            $estado = '<span class=\"left-dot green-dot\">Timbrada</span>';
            //$bDescargar = '<i class=\"fas fa-file-pdf pointer\" style=\"cursor:pointer; padding-right: 5px;\"  width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" title=\"Descargar PDF\" onclick=\"descargarF(\''.$row['id_Nota_Facturapi'].'\')\"></i>'.$botonDescXML.$botonCancelar;

        }elseif($row['estatus']=="2"){
            $estado = '<span class=\"left-dot yellow-dot\">Parcialmente Pagada</span>';
        }elseif($row['estatus']=="3"){
            $estado = '<span class=\"left-dot blue-dark-dot\">Pagada</span>';
        }elseif($row['estatus']=="4"){
            $estado = '<span class=\"left-dot red-dot\">Cancelada</span>';
        }
        $table.='{"Id":"'.$row['id'].
                '","Serie":"'.$row['serie'].
                '","Folio factura":"'.$row['folio'].
                '","Fecha de timbrado":"'.$row['fecha_timbrado'].
                '","Monto":"'.$importe.
                '","Estado":"'.$estado.
                '","Seleccionar":"'.$htmlAcciones.
                '"},';
    }
    //print_r($Id_ultimas);
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';

?>