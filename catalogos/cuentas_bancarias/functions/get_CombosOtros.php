<?php
session_start();
  if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    //print_r("SIII");
    $id = $_POST['id'];
    $stmt = $conn->prepare('SELECT o.Saldo_Inicial,
    mo.Descripcion FROM cuentas_otras as o
    INNER JOIN monedas as mo ON o.FKMoneda=mo.PKMoneda WHERE FKCuenta = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();

  $saldoInicialO = $row['Saldo_Inicial'];
  $moDescripcion = $row['Descripcion']; 

  $json->moDescripcion = $moDescripcion;

    $stmt = $conn->prepare('SELECT o.PKCuentaOtra,
    o.FKCuenta,
    cbe.Nombre,
    mo.Descripcion,
    o.Saldo_Inicial
    FROM cuentas_otras as o INNER JOIN cuentas_bancarias_empresa as cbe  ON o.FKCuenta=cbe.PKCuenta  INNER JOIN monedas as mo ON o.FKMoneda=mo.PKMoneda WHERE o.FKCuenta != :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetchAll();

    $lista = "";
      foreach($row as $d){
      $lista .= "<option value='".$d["FKCuenta"]."'";
        $lista .= " selected";
      $lista .= ">".$d['Nombre']."</option>";
      }

    $json->saldoInicialO = $saldoInicialO;
    $json->idCajaActual = $id;
    $json->listaO = $lista;

    $json = json_encode($json);
    echo $json;
  }
?>