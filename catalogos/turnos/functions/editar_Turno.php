<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    
        $id = (int) $_POST['idTurnoU'];
        $turno = $_POST['txtTurnoU'];
        $entrada = $_POST['txtEntradaU'];
        $salida = $_POST['txtSalidaU'];
        $diasC = count($_POST['cmbDiasU']);
        $dias = $_POST['cmbDiasU'];
        $comida = $_POST['txtComidaU'];
        try{
          $stmt = $conn->prepare('UPDATE turnos set Turno= :turno, Entrada= :entrada, Salida= :salida,Dias_de_trabajo= :dias, TiempoComida= :comida WHERE PKTurno = :id');
          $stmt->bindValue(':turno',$turno);
          $stmt->bindValue(':entrada',$entrada);
          $stmt->bindValue(':salida',$salida);
          $stmt->bindValue(':dias',$diasC);
          $stmt->bindValue(':comida',$comida);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          if($stmt->execute()){
            $exito = true;
          }else{
            $exito = true;
          }

          $stmt = $conn->prepare("DELETE FROM dias_turno WHERE FKTurno=?");
          $stmt->execute(array($id));

          foreach($dias as $d){
            $stmt = $conn->prepare('INSERT INTO dias_turno (Dias,FKTurno)VALUES(:dia, :id)');
            $stmt->bindValue(':dia',$d);
            $stmt->bindValue(':id',$id);
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

      /*if(isset($_POST['idTurnoU'])){
        $id =  $_POST['idTurnoU'];
        $stmt = $conn->prepare('SELECT * FROM turnos WHERE PKTurno= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $turno = $row['Turno'];
        $entrada = $row['Entrada'];
        $salida = $row['Salida'];
        $horas = $row['Horas_de_trabajo'];
        $dias = $row['Dias_de_trabajo'];
      }*/
  }else {
    header("location:../../dashboard.php");
  }
?>
