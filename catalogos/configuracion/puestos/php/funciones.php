<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case "validar_puesto":
            $puesto = $_REQUEST['data'];
            $json = $data->validarPuesto($puesto);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_existeRelacionPuesto":
            $pkpuesto = $_REQUEST['data'];
            $json = $data->validarRelacionPuesto($pkpuesto);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
    }
  }

?>