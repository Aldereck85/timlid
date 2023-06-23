<?php
session_start();
date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $nombre = $_POST['nombreCategoria'];
        $idemp = $_POST['idemp'];
        $usuario = $_POST['usuario'];
        
        $now = date("Y-m-d H:i:s");
        try{
          $stmt = $conn->prepare('INSERT INTO categoria_gastos (Nombre,empresa_id,usuario_creacion_id,usuario_edicion_id,created_at,updated_at,estatus)VALUES(:nombre,:idemp,:usuario_creacion_id,:usuario_edicion_id,:created_at,:updated_at,1)');
          $stmt->bindValue(':nombre',$nombre);
          $stmt->bindValue(':idemp',$idemp);
          $stmt->bindValue(':usuario_creacion_id',$usuario);
          $stmt->bindValue(':usuario_edicion_id',$usuario);
          $stmt->bindValue(':created_at',$now);
          $stmt->bindValue(':updated_at',$now);
          $stmt->execute();
          $categoria_id = $conn->lastInsertId();

          $stmtSub = $conn->prepare('INSERT INTO subcategorias_gastos (Nombre, FKCategoria)VALUES(:nombre,:categoria_id)');
          $stmtSub->bindValue(':nombre','Sin subcategoria');
          $stmtSub->bindValue(':categoria_id',$categoria_id);
          if ($stmtSub->execute()){
            echo "exito";
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }
 ?>