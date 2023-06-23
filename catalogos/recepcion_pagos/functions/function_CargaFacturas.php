<?php
require_once('../../../include/db-conn.php');
session_start();

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

  if(isset($_GET['cliente'])){
    $carga=$_GET['cliente'];

    if($carga=="f"){
      $carga=0;
    }

    $query=sprintf("CALL spc_facturasCliente_cpc(?,?);");
      $stmt = $conn->prepare($query);
      $stmt->execute(array($_SESSION['IDEmpresa'],$carga));
      $table="";

      while (($row = $stmt->fetch()) !== false) {       
  
        if($row['saldo_anterior']==""){
          $row['saldo_anterior']=$row['saldo_insoluto'];
        }

        if($row['parcialidad']==""){
          $row['parcialidad']="0";
        }

          //cambiamos formato a las fechas
          $row['Fecha_facturacion']=date("d-m-Y", strtotime($row['Fecha_facturacion']));
          $row['Fecha_vencimiento']=date("d-m-Y", strtotime($row['Fecha_vencimiento']));

          $fechaVencimiento=date("Y-m-d", strtotime($row['Fecha_vencimiento']));
          $fechaActual=date("Y-m-d");
    
          if( $fechaActual > $fechaVencimiento){
            $row['Fecha_vencimiento']='<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha_vencimiento'].'</span>';
          }else{
            $row['Fecha_vencimiento']='<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha_vencimiento'].'</span>';
          }

          //elimina los 0 que no se utilizan
          $row['saldo_anterior'] = formatoCantidad($row['saldo_anterior']);
          $row['saldo_insoluto'] = formatoCantidad($row['saldo_insoluto']);
          $row['Monto factura'] = formatoCantidad($row['Monto factura']);
          
          //asignamos el metodo de pago segun su id
          switch($row['metodo_pago']){
            case "1":
              $row['metodo_pago']="En Una Exhibición";
              break;
            case "2":
              $row['metodo_pago']="Inicial y Parcialidades";
              break;
            case "3":
              $row['metodo_pago']="En Parcialidades o Diferido";
              break;
            case "0":
              $row['metodo_pago']="Sin Método";
              break;
          }

          //condicion: que para definir la posición del tooltip en "ver"
          if($stmt->rowCount()<2){
            $posicion="left";
          }else{
            $posicion="auto";
          }

          $checks=$stmt->rowCount();
          //valor del check "id factura - descripcion de la forma de pago - id del metodo de pago
          $row['id']='<input style=\"cursor:pointer\" type=\"checkbox\" onclick=\"sumar(this)\" name=\"invoiceSelected\" id=\"invoiceSelected\" data-id=\"'.$row['tipoDoc'].'\" value=\"'.$row['id'].'-'.$row['id_fp'].'-'.$row['metodo_pago'].'\"> <label for=\"cbox2\">'.$row['Folio'].'</label>';

          $table.='{"Folio":"'.$row['id'].'",
            "F de expedicion":"'.$row['Fecha_facturacion'].'",
            "F de vencimiento":"'.$row['Fecha_vencimiento'].'",
            "Metodo de pago":"'.$row['metodo_pago'].'",
            "Monto factura":"$'.$row['Monto factura'].'",
            "Forma de pago":"'.$row['descripcion'].'",
            "Saldo anterior":"$'.$row['saldo_anterior'].'",
            "Saldo insoluto":"$'.$row['saldo_insoluto'].'",
            "No Parcialidad":"'.$row['parcialidad'].'",
            "Seleccionar":""},';    
        }

       $table = substr($table,0,strlen($table)-1);
        echo '{"data":['.$table.']}';      
  }
 ?>
