<?php
  session_start();
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];

        try{
          $stmt = $conn->prepare('UPDATE cotizacion SET facturacion_directa = 1 WHERE PKCotizacion = :idCotizacion AND empresa_id = '.$_SESSION['IDEmpresa']);
          
          if($stmt->execute(array(':idCotizacion'=>$id))){
            echo "1";
          }
          else{
            echo "0";
          }

        }catch(Exception $e){
          echo $e->getMessage();
          exit;
        }
    }
   ?>
