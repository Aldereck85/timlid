<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"]) && ($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4)){
    require_once('../../../include/db-conn.php');
    
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];

    $stmt = $conn->prepare('UPDATE cuentas_bancarias_empresa SET Estado = :estado WHERE PKCuenta = :id');
    $stmt->bindValue(':estado',$tipo);
    $stmt->bindValue(':id',$id);
    $stmt->execute();

    if($tipo == 0){
      try{
        $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (FKCuenta,Fecha,Tipo,Descripcion) VALUES (:cuenta,:fecha,:tipo,:descripcion)');
        $stmt->bindValue(':cuenta',$id);
        $stmt->bindValue(':fecha',date('Y-m-d'));
        $stmt->bindValue(':tipo',6);
        $stmt->bindValue(':descripcion','Desactivar cuenta');
        $stmt->execute();
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }else{
      try{
        $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (FKCuenta,Fecha,Tipo,Descripcion) VALUES (:cuenta,:fecha,:tipo,:descripcion)');
        $stmt->bindValue(':cuenta',$id);
        $stmt->bindValue(':fecha',date('Y-m-d'));
        $stmt->bindValue(':tipo',7);
        $stmt->bindValue(':descripcion','Reactivar cuenta');
        $stmt->execute();
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

  }

?>
