<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../../../include/db-conn.php');
        
        //$identificador = filter_var($_POST['txtIdentidicador'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $identificador = substr($_POST['txtIdentidicador'], 0, 19);//Truncar la cadena de texto a los primeros 20 caracteres.
        
        //$nombre = filter_var($_POST['txtNombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $nombre = substr($_POST['txtNombre'], 0, 49);//Truncar la cadena de texto a los primeros 50 caracteres.
        
        $tipoInsumo = $_POST['cmbTipoInsumo'];
        $unidadMedida = $_POST['cmbUnidadMedida'];
        $cantidadMin = $_POST['txtCantidadMin'];
        $cantidadExi = $_POST['txtCantidadExi'];
        
        //$descripcionBreve = filter_var($_POST['txtDescripcionBreve'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $descripcionBreve = substr($_POST['txtDescripcionBreve'], 0, 49);//Truncar la cadena de texto a los primeros 50 caracteres.
        
        //$descripcionLarga = filter_var($_POST['txtDescripcionLarga'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $descripcionLarga = substr($_POST['txtDescripcionLarga'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.
        
        $usuario = filter_var($_POST['txtUsuario'],FILTER_SANITIZE_EMAIL); //Elimina todos los caracteres menos letras, dígitos y !#$%&'*+-=?^_`{|}~@.[].
        $usuario = substr($usuario, 0, 39);//Truncar la cadena de texto a los primeros 40 caracteres.

        $estatusInsumo = $_POST['cmbEstatusInsumo'];
        $costo = $_POST['txtCosto'];
        
        //$conn->autocommit(false);
        try{
          $stmt = $conn->prepare('call spi_AgregarInsumo(:identificador,:nombre,:tipoInsumo,:unidadMedida,:cantidadMin,:cantidadExi,:descripcionBreve,:descripcionLarga,:usuario,:estatusInsumo,:costo)');
          $stmt->bindValue(':identificador',$identificador,PDO::PARAM_STR);
          $stmt->bindValue(':nombre',$nombre,PDO::PARAM_STR);
          $stmt->bindValue(':tipoInsumo',$tipoInsumo,PDO::PARAM_INT);
          $stmt->bindValue(':unidadMedida',$unidadMedida,PDO::PARAM_INT);
          $stmt->bindValue(':cantidadMin',$cantidadMin);
          $stmt->bindValue(':cantidadExi',$cantidadExi);
          $stmt->bindValue(':descripcionBreve',$descripcionBreve,PDO::PARAM_STR);
          $stmt->bindValue(':descripcionLarga',$descripcionLarga,PDO::PARAM_STR);
          $stmt->bindValue(':usuario',$usuario,PDO::PARAM_STR);
          $stmt->bindValue(':estatusInsumo',$estatusInsumo,PDO::PARAM_INT);
          $stmt->bindValue(':costo',$costo);
          if ($stmt->execute()){

            $_SESSION['message'] = 'Operación exitosa. Insumo registrado correctamente.';
            $_SESSION['message_type'] = 'success';

            header("Location: ../");
            
            //$conn->commit();
          }else{
            $_SESSION['message'] = 'Operación fallida. Insumo no registrado correctamente.';
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