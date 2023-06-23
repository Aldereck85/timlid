<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_personalTable':
            $json = $data->getpersonalTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          return;
          break;
          case "validar_sucursal":
            $sucursal = $_REQUEST['data'];
            $json = $data->validarSucursal($sucursal);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_sucursalU":
            $sucursal = $_REQUEST['data'];
            $pk = $_REQUEST['data2'];
            $json = $data->validarSucursalU($sucursal,$pk);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_existeRelacionSucursal":
            $fksucursal = $_REQUEST['data'];
            $json = $data->validarRelacionSucursal($fksucursal);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
    }
  }

?>