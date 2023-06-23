<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../../../include/db-conn.php');
    
        $fkInsumo = $_POST['cmbInsumo'];
        $costo = $_POST['txtCosto'];
        $cantidadMov = $_POST['txtCantidadEnt'];

        //$descripcion = filter_var($_POST['txtDescripcionLarga'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $descripcion = substr($_POST['txtDescripcionLarga'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.
        
        $usuario = filter_var($_POST['txtUsuario'],FILTER_SANITIZE_EMAIL); //Elimina todos los caracteres menos letras, dígitos y !#$%&'*+-=?^_`{|}~@.[].
        $usuario = substr($usuario, 0, 39);//Truncar la cadena de texto a los primeros 40 caracteres.
        
        //$conn->autocommit(false);
        try{
          $stmt = $conn->prepare('call spi_AgregarEntrada(:fkInsumo,:costo,:cantidadMov,:descripcion,:usuario)');
          $stmt->bindValue(':fkInsumo',$fkInsumo,PDO::PARAM_INT);
          $stmt->bindValue(':costo',$costo);
          $stmt->bindValue(':cantidadMov',$cantidadMov);
          $stmt->bindValue(':descripcion',$descripcion,PDO::PARAM_STR);
          $stmt->bindValue(':usuario',$usuario,PDO::PARAM_STR);
          if ($stmt->execute()){

            $_SESSION['message'] = 'Operación exitosa. Entrada registrada correctamente.';
            $_SESSION['message_type'] = 'success';

            header("Location: ../");
            
            //$conn->commit();
          }else{
            $_SESSION['message'] = 'Operación fallida. Entrada no registrada correctamente.';
            $_SESSION['message_type'] = 'warning';

            header("Location: ../");
            //$conn->rollback();
          }
          //header('Location:../index.php?p='.$puesto.'&a=add');
        }catch(PDOException $ex){
          echo $ex->getMessage();
          $conn->rollback();
        }
  }else {
    header("location:../../../../stock.php");
  }
 ?>