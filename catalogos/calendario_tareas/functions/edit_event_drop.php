<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $start = $_POST['date'];
    //$end = $_POST['date2'];

    if(isset($_POST['date2']) && $_POST['date2'] != ""){
      $end = $_POST['date2'];
      $auxFechaInicio = explode("-",$start);
      $auxFechaTermino = explode("-",$end);
      switch($auxFechaInicio[1]){
        case "01":
          $mesInicial = "Ene";
          break;
        case "02":
          $mesInicial = "Feb";
          break;
        case "03":
          $mesInicial = "Mar";
          break;
        case "04":
          $mesInicial = "Abr";
          break;
        case "05":
          $mesInicial = "May";
          break;
        case "06":
          $mesInicial = "Jun";
          break;
        case "07":
          $mesInicial = "Jul";
          break;
        case "08":
          $mesInicial = "Ago";
          break;
        case "09":
          $mesInicial = "Sep";
          break;
        case "10":
          $mesInicial = "Oct";
          break;
        case "11":
          $mesInicial = "Nov";
          break;
        case "12":
          $mesInicial = "Dic";
          break;
      }

      switch($auxFechaTermino[1]){
        case "01":
          $mesFinal = "Ene";
          break;
        case "02":
          $mesFinal = "Feb";
          break;
        case "03":
          $mesFinal = "Mar";
          break;
        case "04":
          $mesFinal = "Abr";
          break;
        case "05":
          $mesFinal = "May";
          break;
        case "06":
          $mesFinal = "Jun";
          break;
        case "07":
          $mesFinal = "Jul";
          break;
        case "08":
          $mesFinal = "Ago";
          break;
        case "09":
          $mesFinal = "Sep";
          break;
        case "10":
          $mesFinal = "Oct";
          break;
        case "11":
          $mesFinal = "Nov";
          break;
        case "12":
          $mesFinal = "Dic";
          break;
      }
      $startAux = $auxFechaInicio[2]."-".$mesInicial."-"$auxFechaInicio[0];
      $endAux = $auxFechaTermino[2]."-".$mesFinal."-"$auxFechaTermino[0];
      $startRight = date('d/m/Y',strtotime($startAux));
      $endRigth = date('d/m/Y',strtotime($endAux));
      $dateRight = $startRight." a ".$endRigth;
      $stmt = $conn->prepare('UPDATE rango_fecha SET Rango= :rango WHERE PKRangoFecha= :id');
      echo $stmt->execute(array(':rango'=>$dateRight,':id'=>$id));
    }else{
      $stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha= :inicio WHERE PKFecha= :id');
      echo $stmt->execute(array(':inicio'=>$start,':id'=>$id));
    }

  }



?>
