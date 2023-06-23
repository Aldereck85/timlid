<?php
session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $fkusuario = $_POST['fkusuario'];
        try{
          $stmt = $conn->prepare('INSERT INTO vendedores (FKUsuario,FKEstatusGeneral)VALUES(:fkusuario,:fkestatus)');
          $stmt->bindValue(':fkusuario',$fkusuario);
          $stmt->bindValue(':fkestatus',1);
          if ($stmt->execute()){
            echo "exito";
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }
 ?>

