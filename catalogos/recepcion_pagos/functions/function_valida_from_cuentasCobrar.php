<?php
session_start();
require_once('../../../include/db-conn.php');
$data['estatus']="no";

if (isset($_REQUEST["id"])) {

    $factura=$_REQUEST["id"];
    $is_invoice = $_REQUEST['is_invoice'];

    //recupera el metodo y forma de pago de la factura
    if($is_invoice == 2){
        $stmt = $conn->prepare("SELECT fp.id, f.metodo_pago from facturacion as f, formas_pago_sat as fp where f.id=:factura and fp.id=f.forma_pago_id and f.prefactura = 0;");
        $stmt->bindValue(":factura",$factura);
        $stmt->execute();
        $row = $stmt->fetch();
    }else{
      $row['metodo_pago'] = 0;
      $row['id'] = 22;
    }

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

    $data['estatus']="ok";
    $data['forma']=$row['id'];
    $data['metodo']=$row['metodo_pago'];
}

echo json_encode($data);
?>