<?php
session_start();
  /*if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){*/
    date_default_timezone_set('America/Mexico_City');
    $now = date("Y-m-d H:i:s");
    require_once('../../../../include/db-conn.php');
      $turno = $_POST['txtTurno'];
      $entrada = $_POST['txtEntrada'];
      $salida = $_POST['txtSalida'];
      $diasC = $_POST['cmbDias'];
      $dias = $_POST['cmbDias'];
      $comida = $_POST['txtComida'];
      $empresa = $_REQUEST['empresa'];
      $usuario = $_REQUEST['usuario'];
      $hrsTrabajadas = $_REQUEST['hrsTrabajadas'];
      $tipo_jornada = $_REQUEST['tipo_jornada'];
      
      $json = json_decode($diasC);

      $diasTrabajados = 0;
      foreach($json as $j){

        if($j){
          $diasTrabajados++;
        }

      }

      try{

        $stmt = $conn->prepare('INSERT INTO turnos (Turno,Entrada, Salida, Dias_de_trabajo, Num_Dias_Trabajo, TiempoComida, tipo_jornada_id, empresa_id, created_at, updated_at, usuario_creacion_id, usuario_edicion_id, estatus, HorasTrabajo) VALUES (:turno, :entrada, :salida, :dias, :Num_Dias_Trabajo, :comida, :tipo_jornada_id, :empresa, :created_at, :updated_at, :usuario_creacion_id, :usuario_edicion_id, 1, :horasTrabajo)');
        $stmt->bindValue(':turno',$turno);
        $stmt->bindValue(':entrada',$entrada);
        $stmt->bindValue(':salida',$salida);
        $stmt->bindValue(':dias',$diasC);
        $stmt->bindValue(':Num_Dias_Trabajo',$diasTrabajados);
        $stmt->bindValue(':comida',$comida);
        $stmt->bindValue(':tipo_jornada_id',$tipo_jornada);
        $stmt->bindValue(':empresa',$empresa);
        $stmt->bindValue(':created_at',$now);
        $stmt->bindValue(':updated_at',$now);
        $stmt->bindValue(':usuario_creacion_id',$usuario);
        $stmt->bindValue(':usuario_edicion_id',$usuario);
        $stmt->bindValue(':horasTrabajo',$hrsTrabajadas);

        if($stmt->execute()){
          $exito = true;
          echo "exito";
        }else{
          $exito = false;
          echo "fallo";
        }
        /* $idLast = $conn->lastInsertId();

        foreach($dias as $d){
          $stmt = $conn->prepare('INSERT INTO dias_turno (Dias,FKTurno)VALUES(:dia, :id)');
          $stmt->bindValue(':dia',$d);
          $stmt->bindValue(':id',$idLast);
          if($stmt->execute()){
            $exito2 = true;
          }else{
            $exito2 = false;
          }
        } */

        /* if($exito2 && $exito == true){
          echo "exito";
        }else{
          echo "fallo";
        } */
        
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
  /*}else {
    header("location:../../../dashboard.php");
  }*/
?>
