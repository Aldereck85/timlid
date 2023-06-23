<?php
  session_start();
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];
    $FKUsuario = $_POST['FKUsuario'];
    $FechaIngreso = date("Y-m-d");

        try{
          $stmt = $conn->prepare('UPDATE cotizacion SET estatus_factura_id = 1 WHERE PKCotizacion = :id AND empresa_id = '.$_SESSION['IDEmpresa']);
          
          if($stmt->execute(array(':id'=>$id))){

            $stmt = $conn->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, :fechamovimiento, :fkmensaje, :fkcotizacion)");
            $stmt->bindValue(':fkusuario',$FKUsuario);
            $stmt->bindValue(':fechamovimiento',$FechaIngreso);
            $stmt->bindValue(':fkmensaje', 16);
            $stmt->bindValue(':fkcotizacion',$id);
            $stmt->execute(); 

            echo "exito";
          }
          else{
            echo "fallo";
          }
          
        }catch(Exception $e){
          echo $e->getMessage();
          exit;
        }
    }
?>
