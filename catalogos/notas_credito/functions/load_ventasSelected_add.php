<?php
/////////
///Consulta las facturas con que se seleccionaron en el modal para decidir cuanto se va a  devolver de ella.
/////////
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$cadena = $_REQUEST['cadenaids'];
$table="";
    $all = $conn->prepare("SELECT PKVentaDirecta,Referencia,created_at,Importe,FKEstatusVenta from ventas_directas  where PKVentaDirecta in($cadena) and empresa_id = $empresa and estatus_factura_id not in (1,2);");
    $all->execute();
    $array = $all->fetchAll();

    $decimas = 2;

        // Buscar el mayor numero de decimales en los precios unitarios 
        foreach($array as $p){
            $precio1 = $p['Importe'];

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

        $importe = $row['Importe'];
        

        $importe = number_format($importe, $decimas, ".", ",");

        $btnDelete = '<input type=\"image\" src=\"../../img/timdesk/delete.svg\" width=\"20px\" heigth=\"20px\" onclick=\"deleteFact('.$row['PKVentaDirecta'].')\"/>';
        $input='<input class=\"form-control numericDecimal-only pagoinput\" type=\"text\" name=\"inputs_facturas\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['PKVentaDirecta'].'\" min=\"1\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
        $table.='{"Id":"'.$row['PKVentaDirecta'].
                '","Serie":"'.
                '","Folio factura":"'.$row['Referencia'].
                '","Fecha de timbrado":"'.$row['created_at'].
                '","Monto":"'.$importe.
                '","Credito":"'.$input.
                '","Eliminar":"'.$btnDelete.
                '"},';
    }
    //print_r($Id_ultimas);
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';

?>