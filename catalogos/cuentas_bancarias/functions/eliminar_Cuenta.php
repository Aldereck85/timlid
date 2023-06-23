<?php
  require_once('../../../include/db-conn.php');
  //var_dump($_POST);
  //$id = $_POST['idCuentaU'];
  //echo $id;
  if(isset($_POST['idCuentaU'])){

      $id = $_POST['idCuentaU'];
      $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa WHERE PKCuenta = :id');
      $stmt->execute(array(':id'=>$id));
      $stmt->execute();
      $row = $stmt->fetch();
      $tipoC = $row['tipo_cuenta'];

      if($tipoC==1){
        try{
          $stmt = $conn->prepare("DELETE FROM cuentas_cheques WHERE FKCuenta=?");
          if($stmt->execute(array($id))){
            echo "exito";
            
            $stmt2 = $conn->prepare("DELETE FROM cuentas_bancarias_empresa WHERE PKCuenta=:id");
            $stmt2->bindValue(':id',$id);
            $stmt2->execute();
          }else{
            echo "fallo";
          }  
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }else if($tipoC==2){
        try{
          $stmt = $conn->prepare("DELETE FROM cuentas_credito WHERE FKCuenta=?");
          if($stmt->execute(array($id))){
            echo "exito";
            $stmt2 = $conn->prepare("DELETE FROM cuentas_bancarias_empresa WHERE PKCuenta=:id");
            $stmt2->bindValue(':id',$id);
            $stmt2->execute();
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }else if($tipoC==3){
        try{
          $stmt = $conn->prepare("DELETE FROM cuentas_otras WHERE FKCuenta=?");
          if($stmt->execute(array($id))){
            echo "exito";
            $stmt2 = $conn->prepare("DELETE FROM cuentas_bancarias_empresa WHERE PKCuenta=:id");
            $stmt2->bindValue(':id',$id);
            $stmt2->execute();
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }else {
        try{
          $stmt = $conn->prepare("DELETE FROM cuenta_caja_chica WHERE FKCuenta=?");
          if($stmt->execute(array($id))){
            echo "exito";
            
            $stmt2 = $conn->prepare("DELETE FROM cuentas_bancarias_empresa WHERE PKCuenta=:id");
            $stmt2->bindValue(':id',$id);
            $stmt2->execute();
          }else{
            echo "fallo";
            
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }
      //header("location:index.php");
  }
?>
