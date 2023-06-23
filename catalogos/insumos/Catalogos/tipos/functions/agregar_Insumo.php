<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../../../include/db-conn.php');
    
        //$tipo = filter_var($_POST['txtTipo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $tipo = substr($_POST['txtTipo'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.
        
        //$conn->autocommit(false);
        try{
          $stmt = $conn->prepare('call spi_AgregarTipoInsumo(:tipo)');
          $stmt->bindValue(':tipo',$tipo,PDO::PARAM_STR);
          if ($stmt->execute()){

            $_SESSION['message'] = 'Operación exitosa. Tipo de insumo registrado correctamente.';
            $_SESSION['message_type'] = 'success';

            header("Location: ../");
            
            //$conn->commit();
          }else{
            $_SESSION['message'] = 'Operación fallida. Tipo de insumo no registrado correctamente.';
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