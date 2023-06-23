<?php
session_start();
if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        try{
          $stmt = $conn->prepare('SELECT s.sucursal FROM sucursales s WHERE s.sucursal = :nombre AND empresa_id = :id_empresa AND estatus = 1');
          $stmt->execute(array(':nombre'=>$_REQUEST['data'],':id_empresa'=>$_SESSION['IDEmpresa']));
          if($stmt->rowCount() > 0){
            echo "fallo";
          }else{
            echo "exito";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
    }
?>