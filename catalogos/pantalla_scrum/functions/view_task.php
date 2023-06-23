<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $json = "";
    $stmt = $conn->prepare('SELECT * FROM tareas WHERE PKTarea = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $idTarea = $row['PKTarea'];
    $tarea = $row['Tarea'];
    $etapa = $row['FKEtapa'];
    $fecha = "dd-mm-aaaa";
    $proyecto = $row['FKProyecto'];
    $texto = "";
    $usuario = "";
    $estatus = "";

    /*
    $stmt = $conn->prepare('SELECT * FROM texto_tarea WHERE FKTarea =:id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    //$contarTexto = $stmt->rowCount();
    if($row['Texto'] != ""){
      $texto = $row['Texto'];
    }
    */
    $contFecha = 0;
    $contResponsable = 0;
    $contEstado = 0;

    $stmt = $conn->prepare('SELECT Tipo FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = :tipo');
    $stmt->execute(array(':id'=>$proyecto,':tipo'=>1));
    $contResponsable = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT Tipo FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = :tipo');
    $stmt->execute(array(':id'=>$proyecto,':tipo'=>2));
    $contEstado = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT Tipo FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = :tipo');
    $stmt->execute(array(':id'=>$proyecto,':tipo'=>3));
    $contFecha = $stmt->rowCount();

    /*
    foreach ($arrayColum as $r) {
      switch ($r['Tipo']) {
        case 1:
          $contResponsable++;
        break;
        case 2:
          $contEstado++;
        break;
        case 3:
          $contFecha++;
        break;
      }
    }

    */
    if($contFecha > 0){
      $stmt = $conn->prepare('SELECT * FROM fecha_tarea WHERE FKTarea =:id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $fecha = $row['Fecha'];
    }

    if($contResponsable > 0){
      $stmt = $conn->prepare('SELECT * FROM responsables_tarea WHERE FKTarea =:id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $idResponsable = $row['FKUsuario'];
      $stmt = $conn->prepare('SELECT user.PKUsuario, CONCAT(emp.Nombres," ",emp.PrimerApellido," ",emp.SegundoApellido) AS employe FROM usuarios AS user
                              INNER JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado
                              WHERE user.PKUsuario = :id');
      $stmt->execute(array(':id'=>$idResponsable));
      $row = $stmt->fetch();
      //$usuario = $row['PKUsuario'];
      if($idResponsable != ""){
        $usuario = $row['PKUsuario'];
      }

    }

    if($contEstado > 0){
      $stmt = $conn->prepare('SELECT * FROM estado_tarea WHERE FKTarea =:id ORDER BY FKTarea ASC LIMIT 1');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $estatus = $row['FKColorColumna'];
    }
    //$contFecha
    //$contResponsable
    //$contEstado
    /*
    //Si están todas las columnas
    if($contFecha != 0 && $contResponsable != 0 && $contEstado != 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","fecha":"'.$fecha.'","usuario":"'.$usuario.'","estatus":"'.$estatus.'"},';
    //Si falta la columna fecha
    }else if($contFecha == 0 && $contResponsable != 0 && $contEstado != 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","usuario":"'.$usuario.'","estatus":"'.$estatus.'"},';
    //si falta la columna responsable
    }else if($contFecha != 0 && $contResponsable == 0 && $contEstado != 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","fecha":"'.$fecha.'","estatus":"'.$estatus.'"},';
    //Si falta la columna estado
    }else if($contFecha != 0 && $contResponsable != 0 && $contEstado == 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","fecha":"'.$fecha.'","usuario":"'.$usuario.'"},';
    //Si falta la columna fecha y responsable
    }else if($contFecha == 0 && $contResponsable == 0 && $contEstado != 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","estatus":"'.$estatus.'"},';
    //SI falta la columa fecha y estado
    }else if($contFecha == 0 && $contResponsable != 0 && $contEstado == 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","usuario":"'.$usuario.'"},';
    //Si falta la columna responsable y estado
    }else if($contFecha != 0 && $contResponsable == 0 && $contEstado == 0){
      $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","fecha":"'.$fecha.'"},';
    }
*/
    $json .= '{"id":"'.$idTarea.'","tarea":"'.$tarea.'","etapa":"'.$etapa.'","texto":"'.$texto.'","fecha":"'.$fecha.'","usuario":"'.$usuario.'","estatus":"'.$estatus.'","conF":"'.$contFecha.'"},';

    $json = substr($json,0,strlen($json)-1);
    echo $json;
  }

?>
