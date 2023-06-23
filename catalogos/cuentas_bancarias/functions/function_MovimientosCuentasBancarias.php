<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $table = "";
  $no = 1;
  $tipo = "";
  $retiro = "-";
  $deposito = "-";
  $saldo = "-";
  $stmt = $conn->prepare('SELECT * FROM movimientos_cuentas_bancarias_empresa WHERE FKCuenta = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();

  while($row = $stmt->fetch()){
    $fecha = date("d/m/Y", strtotime($row['Fecha']));
    if($row['Tipo'] == 1){
      $tipo = "Saldo inicial";
    }else if($row['Tipo'] == 2){
      $tipo = "Venta";
    }else if($row['Tipo'] == 3){
      $tipo = "Compra";
    }else if($row['Tipo'] == 4){
      $tipo = "Desactivado";
    }else if($row['Tipo'] == 5){
      $tipo = "Reactivar";
    }

    if($row['Tipo'] != 4 || $row['Tipo'] != 5){
      $retiro = "$".number_format($row['Retiro'],2);
      $deposito = "$".number_format($row['Deposito'],2);
      $saldo = "$".number_format($row['Saldo'],2);
    }

    $table.='{"Fecha":"'.$fecha.'","Tipo":"'.$tipo.'","Descripcion":"'.$row['Descripcion'].'","Retiro":"'.$retiro.'","Deposito":"'.$deposito.'","Saldo":"'.$saldo.'","Referencia":"'.$row['Referencia'].'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';



?>
