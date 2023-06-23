<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $start = $_POST['date'];
  $end = $_POST['date2'];
  $auxStart = date('Y-m-d',strtotime($start));
  $auxEnd = date('Y-m-d',strtotime($end."-1 days"));
  $proyecto = $_POST['proyecto'];
  //$tabla = $_POST['tabla'];

  $auxFechaInicio = explode("-",$start);
  $auxFechaTermino = explode("-",$auxEnd);
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

  $startRight = $auxFechaInicio[2]."/".$mesInicial."/".$auxFechaInicio[0];
  $endRigth = $auxFechaTermino[2]."/".$mesFinal."/".$auxFechaTermino[0];
  //$startRight = date('d/m/Y',strtotime($startAux));
  //$endRigth = date('d/m/Y',strtotime($endAux));
  $dateRight = $startRight." a ".$endRigth;

  //echo "Id: ".$id."\nFecha inicio: ".$auxStart."\nFecha termino: ".$auxEnd;
  //if($tabla == "rango"){
    $stmt = $conn->prepare('SELECT * FROM rango_fecha WHERE PKRangoFecha = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $columnaRango = $row['FKColumnaProyecto'];
    $countCrono = $stmt->rowCount();
  //}
  //if($tabla == "fecha"){
  /*
    $stmt = $conn->prepare('SELECT * FROM fecha_tarea WHERE PKFecha = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $columnaFecha = $row['FKColumnaProyecto'];
    $countDate = $stmt->rowCount();
    */
  //}

  if($auxStart == $auxEnd){
    if($countCrono > 0){
      $stmt = $conn->prepare('UPDATE rango_fecha SET Rango=:rango WHERE PKRangoFecha = :id ');
      $stmt->execute(array(':id'=>$id,':rango'=>""));

      //$stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha=:start WHERE PKFecha=:id_tarea)');
      //echo $stmt->execute(array(':start'=>$start,':id_tarea'=>$id));
    }else {
      //$stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha= :start WHERE PKFecha= :id');
      //echo $stmt->execute(array(':start'=>$start,':id'=>$id));
    }
  }else{
    //if($countDate > 0){
      //$stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha= :fecha WHERE PKFecha = :id ');
      //$stmt->execute(array(':id'=>$id,':fecha'=>'0000-00-00'));

      $stmt = $conn->prepare('UPDATE rango_fecha SET Rango=:rango WHERE PKRangoFecha=:id_tarea');
      echo $stmt->execute(array(':rango'=>$dateRight,':id_tarea'=>$id));
    //}else{
    //  $stmt = $conn->prepare('UPDATE rango_fecha SET Rango= :rango WHERE PKRangoFecha= :id');
      //echo $stmt->execute(array(':rango'=>$dateRight,':id'=>$id));
    //}
  }


?>
