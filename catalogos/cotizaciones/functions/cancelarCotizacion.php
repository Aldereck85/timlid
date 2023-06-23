<?php
  session_start();

  $jwt_ruta = "../../../";
  require_once '../../jwt.php';

  require_once('../../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');

  $token = $_POST["csr_token_7ALF1"];

  if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;
  }

  if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
  }

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];
    $FKUsuario = $_POST['FKUsuario'];
    $FechaIngreso = date("Y-m-d H:i:s");

        try{

          $stmt = $conn->prepare('SELECT estatus_cotizacion_id FROM cotizacion WHERE PKCotizacion = :id AND empresa_id = '.$_SESSION['IDEmpresa']);
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();

          if($row['estatus_cotizacion_id'] == 5 ){

              $stmt = $conn->prepare('UPDATE cotizacion SET estatus_cotizacion_id = 3 WHERE PKCotizacion = :id AND empresa_id = '.$_SESSION['IDEmpresa']);
              
              if($stmt->execute(array(':id'=>$id))){

                $stmt = $conn->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, :fechamovimiento, :fkmensaje, :fkcotizacion)");
                $stmt->bindValue(':fkusuario',$FKUsuario);
                $stmt->bindValue(':fechamovimiento',$FechaIngreso);
                $stmt->bindValue(':fkmensaje', 20);
                $stmt->bindValue(':fkcotizacion',$id);
                $stmt->execute(); 

                echo "exito";
              }
              else{
                echo "fallo";
              }
          }
          else{
              echo "fallo-cancelacion";
          }
          
        }catch(Exception $ex){
          echo $ex->getMessage();
        }
    }
?>
