<?php
session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = (int) $_POST['idProyectoU'];
        $Proyecto = $_POST['txtProyectoU'];
        $idUsuario = $_POST['cmbIdUsuarioU'];
        $idEquipos = $_POST['cmbIdEquipoU'];
        $cont1 = count($idEquipos);

        try{
        //actualizar Nombre y Responsable en DB Tabla proyectos
          $stmt = $conn->prepare('UPDATE proyectos set Proyecto= :proyecto, FKResponsable= :fkresponsable WHERE PKProyecto = :id');
          $stmt->bindValue(':proyecto',$Proyecto);
          $stmt->bindValue(':fkresponsable',$idUsuario);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

        //actualizar equipos en la tabla de equipos_por_proyecto
          //consultar equipos actuales en la tabla
          $stmt2 = $conn->prepare("SELECT FKEquipo FROM equipos_por_proyecto  WHERE FKProyecto = :id");
          $stmt2->execute(array(':id'=>$id));
          $stmt2->execute();
          $cont2 = $stmt2->rowCount();

          //llenar array de consulta actual de equipos
          $arra_query = array();
          while(($row = $stmt2->fetch()) !== false)
            array_push($arra_query,$row['FKEquipo']);
          
          //llenar array con equipos nuevos
          foreach($idEquipos as $e)
            $cmbArray[]=$e;
          
          //comparar y hacer diferencia con los dos arrays
          $resultadoadd=array_diff($cmbArray, $arra_query);
          $resultadodel=array_diff($arra_query, $cmbArray);

          if($cont2 == 0){//llenar si está vacio
            foreach ($idEquipos as $equipo){ 
              $stmt2 = $conn->prepare('INSERT INTO equipos_por_proyecto (FKProyecto,FKEquipo) VALUES (:idproyecto,:fkequipo)');
              $stmt2->bindValue(':idproyecto',$id);
              $stmt2->bindValue(':fkequipo',$equipo);
              $stmt2->execute();
            }
          }else if($cont1 > $cont2){ //insertar un nuevo usuario seleccionado en el combo
            foreach($resultadoadd as $idteam){
              $stmt2 = $conn->prepare('INSERT INTO equipos_por_proyecto (FKProyecto,FKEquipo) VALUES (:idproyecto,:fkequipo)');
              $stmt2->bindValue(':idproyecto',$id);
              $stmt2->bindValue(':fkequipo',$idteam);
              $stmt2->execute();
            }
          }else{//Eliminar usuario desceleccionado en el combo
            foreach($resultadodel as $idteam){
              $stmt4 = $conn->prepare("DELETE FROM equipos_por_proyecto WHERE FKProyecto = :idproyecto AND FKEquipo = :idequipo"); 
              $stmt4->bindValue(':idproyecto',$id);
              $stmt4->bindValue(':idequipo',$idteam);
              $stmt4->execute();
            }
          } 
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
        
      }
  }else {
    header("location:../../dashboard.php");
  }


 