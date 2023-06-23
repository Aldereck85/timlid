<?php
session_start();
if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        try{
          $stmt = $conn->prepare('SELECT tp.PKTipoProducto, tp.TipoProducto from tipos_productos tp where tp.estatus = "1" order by tp.TipoProducto  asc');
          if($stmt->execute()){
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
    }
?>