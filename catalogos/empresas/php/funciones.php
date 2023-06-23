<?php
  include_once("clases.php");
  $array = "";
  if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

    switch ($_REQUEST['clase']) {
      case 'get_data':
        $data = new get_data();
        switch ($_REQUEST['funcion']) {
          case 'get_dataEnterprise':
            $json = $data->getDataEnterprise(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_regimenFiscal':
            $json = $data->getRegimenFiscal(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_estados':
            $json = $data->getEstados(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_validRazonSocial':
            $value = $_POST['value'];
            $json = $data->getValidRazonSocial($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
        }

      break;
      case 'update_data':
        $update = new update_data();
        switch ($_REQUEST['funcion']) {
          case 'update_data_enterprise':
            $data = $_POST['data'];
            $json = $update->updateDataEnterprise($data); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          
          
          
        }
      break;
    }

  }

?>