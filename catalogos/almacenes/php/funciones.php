<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_warehouseTable': //cargar datos en datatables.js
            $json = $data->getWarehouseTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
            
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

          case 'get_warehouseEdit':
            $value = $_REQUEST['value'];
            $json = $data->getWarehouseEdit($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
        }

      break;
    }

  }


?>