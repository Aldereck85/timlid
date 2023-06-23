<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case "validar_turno":
            $turno = $_REQUEST['data'];
            $json = $data->validarTurno($turno);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_existeRelacionTurno":
            $pkturno = $_REQUEST['data'];
            $json = $data->validarRelacionTurno($pkturno);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
    }
  }

?>