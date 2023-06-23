<?php
session_start();
if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        try{
          $stmt = $conn->prepare('SELECT p.PKProducto FROM  productos p WHERE p.Nombre = :nombre AND p.empresa_id = :id_empresa');
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