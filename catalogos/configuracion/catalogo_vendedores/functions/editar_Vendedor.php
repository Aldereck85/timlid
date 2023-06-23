<?php
  session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $id =  $_POST['idVendedorU'];
        $fkusuario = $_POST['txtVendedorU'];
        $estatus = $_POST['cmbEstatusVendedor'];
        try{
          $stmt = $conn->prepare('UPDATE vendedores set FKUsuario= :fkusuario, FKEstatusGeneral= :estatus WHERE PKVendedor = :id');
          $stmt->bindValue(':fkusuario',$fkusuario);
          $stmt->bindValue(':estatus',$estatus);
          $stmt->bindValue(':id', $id);
          
          if($stmt->execute()){
            echo "1";
          }else{
            echo "0";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
  }
?>

