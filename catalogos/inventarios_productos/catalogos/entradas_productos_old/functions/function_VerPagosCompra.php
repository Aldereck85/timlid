<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT * FROM pagos_productos as pp
          LEFT JOIN cuentas_bancarias_proveedores as cbp ON pp.FKCuenta = cbp.PKCuentaProveedor
          LEFT JOIN bancos as b ON cbp.FKBanco =b.PKBanco
          WHERE pp.FKCompra = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $table="";
  $no = 1;
  $tipoPago = "";

  while(($row = $stmt->fetch()) !== false){
    switch($row['Tipo_Pago']){
      case 1:
        $tipoPago = "Transferencia electrónica";
      break;
      case 2:
        $tipoPago = "Efectivo";
      break;
      case 3:
        $tipoPago = "Tarjeta de crédito";
      break;
      case 4:
        $tipoPago = "Tarjeta de débito";
      break;
      case 5:
        $tipoPago = "Por definir";
      break;
      default:
        $tipoPago = "No se registró un tipo de pago...";
      break;
    }
    $fecha = date("d/m/Y",strtotime($row['Fecha_Pago']));
    $importe = "$".number_format($row['Importe'],2);

    $table .= '{"No":"'.$no.'","Fecha":"'.$fecha.'","Cuenta":"'.$row['Nombre'].'","Tipo de pago":"'.$tipoPago.'","Importe":"'.$importe.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';



?>
