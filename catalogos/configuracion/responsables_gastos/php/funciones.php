<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_responsableTable':
            $idemp = $_REQUEST['data'];
            $json = $data->getResponsableTable($idemp);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "validar_existeResponsable":
            $responsable = $_REQUEST['data'];
            $json = $data->validarExisteResponsable($responsable);
            echo json_encode($json);
          break;
          case "validar_existeRelacionResponsable":
            $fkresponsable = $_REQUEST['data'];
            $json = $data->validarExisteRelacionResponsable($fkresponsable);
            echo json_encode($json);
          break;
          case "get_cmb_empleados":
            $json = $data->getCmbEmpleados(); 
            echo json_encode($json); 
            return;
          break;
        }
    }
  }

?>