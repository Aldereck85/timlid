<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    
      $turno = $_POST['txtTurno'];
      $entrada = $_POST['txtEntrada'];
      $salida = $_POST['txtSalida'];
      $diasC = count($_POST['cmbDias']);
      $dias = $_POST['cmbDias'];
      $comida = $_POST['txtComida'];

      try{
        $stmt = $conn->prepare('INSERT INTO turnos (Turno,Entrada,Salida,Dias_de_trabajo,TiempoComida)VALUES(:turno,:entrada,:salida,:dias,:comida)');
        $stmt->bindValue(':turno',$turno);
        $stmt->bindValue(':entrada',$entrada);
        $stmt->bindValue(':salida',$salida);
        $stmt->bindValue(':dias',$diasC);
        $stmt->bindValue(':comida',$comida);
        if($stmt->execute()){
          $exito = true;
        }else{
          $exito = false;
        }
        $idLast = $conn->lastInsertId();


        foreach($dias as $d){
          $stmt = $conn->prepare('INSERT INTO dias_turno (Dias,FKTurno)VALUES(:dia, :id)');
          $stmt->bindValue(':dia',$d);
          $stmt->bindValue(':id',$idLast);
          if($stmt->execute()){
            $exito2 = true;
          }else{
            $exito2 = false;
          }
        }

        if($exito2 && $exito == true){
          echo "exito";
        }else{
          echo "fallo";
        }
        
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
  }else {
    header("location:../../dashboard.php");
  }
?>
