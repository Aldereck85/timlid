<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){
        $Proyecto = $_POST['txtProyecto'];
        $idUsuario = $_POST['cmbIdUsuario']; //id usuario
        //$idEquipo = $_POST['cmbIdEquipo']; //id equipo

        try{
          //insertar DB proyectos
          $stmt = $conn->prepare('INSERT INTO proyectos (Proyecto,FKResponsable) VALUES (:proyecto,:fkresponsable)');
          $stmt->bindValue(':proyecto',$Proyecto);
          $stmt->bindValue(':fkresponsable',$idUsuario);
          $stmt->execute();

          $lastInsertId = $conn->lastInsertId();

          //insertar DB equipos
          foreach ($_POST['cmbIdEquipo'] as $idEquipo){
            $stmt2 = $conn->prepare('INSERT INTO equipos_por_proyecto (FKProyecto,FKEquipo) VALUES (:idproyecto,:fkequipo)');
            $stmt2->bindValue(':idproyecto',$lastInsertId);
            $stmt2->bindValue(':fkequipo',$idEquipo);
            $stmt2->execute();
          }

          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }
  }else {
    header("location:../../dashboard.php");
  }

