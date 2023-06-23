<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          
          case 'get_countriesCombo'://cargar combo paises
            $value = $_REQUEST['value'];
            $json = $data->getCountriesCombo($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_statesCombo':
            $value = $_REQUEST['value'];
            $json = $data->getStatesCombo($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_paqueteria':
            $value = $_REQUEST['value'];
            $json = $data->getPaqueteria($value);
            echo json_encode($json);
            return;
          break;
        }
      break;
      
      case 'save_data':
        $save = new save_data();
        switch($_REQUEST['funcion']){

          case 'save_paqueteria':
            $value = $_REQUEST['value'];
            $empresa = $_REQUEST['empresa'];
            $usuario = $_REQUEST['usuario'];
            $json = $save->savePaqueteria($value,$empresa,$usuario);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        }
      break;

      case 'edit_data':
        $edit = new edit_data();
        switch($_REQUEST['funcion']){
          case 'edit_paqueteria':
            $value = $_REQUEST['value'];
            $usuario = $_REQUEST['usuario'];
            $json = $edit->editPaqueteria($value,$usuario);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        }
      break;

      case 'delete_data':
        $delete = new delete_data();
        switch ($_REQUEST['funcion']) {
          case 'delete_paqueteria':
            $value = $_REQUEST['value'];
            $usuario = $_REQUEST['usuario'];
            $json = $delete->deletePaqueteria($value,$usuario);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        }
      break;
    }
  }

?>