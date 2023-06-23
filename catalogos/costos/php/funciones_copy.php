<?php
  include_once "clases_copy.php";
  $array = "";
  if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    switch ($_REQUEST['clase']) {
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']) {

          case "get_productionOrderTable":
            $json = $data->getProductionOrderTable(); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case "get_detailsDataproductionOrder":
            $value = $_POST['value'];
            $json = $data->getDetailsDataProductionOrder($value);
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case "get_sucursales":
            $json = $data->getSucursales();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case "get_productos":
            $value = $_POST['value'];
            $json = $data->getProductos($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case "get_responsable":
            $json = $data->getResponsable();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case "get_grupoTrabajo":
            $json = $data->getGrupoTrabajo();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_productCompounds':
            $value = $_POST['value'];
            $rowCount = $_POST['rowCount'];
            $json = $data->getProductCompounds($value,$rowCount);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          
          case 'get_productCompoundsTable':
            $value = $_POST['value'];
            $id = $_POST['id'];
            $json = $data->getProductCompoundsTable($value,$id); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case 'get_detailProducts':
            $value = $_POST['value'];
            $json = $data->getDetailProducts($value); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case 'get_grupoTrabajoOrdenProduccion':
            $value = $_POST['value'];
            $json = $data->getGrupoTrabajoOrdenProduccion($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_lotes':
            $value = $_POST['value'];
            $json = $data->getLotes($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_manufacturingHistory':
            $value = $_POST['value'];
            $json = $data->getManufacturingHistory($value); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case 'get_dataProductionOrderTracking':
            $value = $_POST['value'];
            $json = $data->getDataProductionOrderTracking($value); //Guardando el return de la función
            echo json_encode($json);; //Retornando el resultado al ajax
            return;
          break;

          case 'get_compoundsData':
            $value = $_POST['value'];
            $json = $data->getCompoundsData($value); //Guardando el return de la función
            echo json_encode($json);; //Retornando el resultado al ajax
            return;
          break;

          case 'get_lots':
            $value = $_POST['value'];
            $json = $data->getLots($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_stocksGeneral':
            $value = $_POST['value'];
            $json = $data->getSocksGeneral($value); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;

          case 'get_stocksPorLote':
            $value = $_POST['value'];
            $json = $data->getStocksPerLot($value); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;

          case 'get_correctQuantitryPerLot':
            $value = $_POST['value'];
            
            $json = $data->getCorrectQuantitryPerLot($value);
            echo $json; //Retornando el resultado al aja
          break;
        }
      break;
      case "update_data":
        $data = new update_data();
        switch($_REQUEST['funcion']){
          case "update_status_productionOrder":
            $value = $_POST['value'];
            $id = $_POST['id'];
            $json = $data->updateStatusProductionOrder($value,$id);
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case "update_expectedDate":
            $value = $_POST['value'];
            $id = $_POST['id'];
            $json = $data->updateExpectedDate($value,$id);
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case "update_quantity":
            $value = $_POST['value'];
            $id = $_POST['id'];
            $json = $data->updateQuantity($value,$id);
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case "update_responsable":
            $value = $_POST['value'];
            $id = $_POST['id'];
            $json = $data->updateResponsable($value,$id);
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          
          case "update_workgroup":
            $value = $_POST['value'];
            $id = $_POST['id'];
            $json = $data->updateWorkgroup($value,$id);
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case "update_lots":
            $value = $_POST['value'];
            $json = $data->updateLots($value);
            print_r($json); //Retornando el resultado al ajax
            return;
          break;
        }
      break;
      case "save_data":
        $data = new save_data();
        switch($_REQUEST['funcion']){

          case "save_productionOrder":
            $value = $_POST['value'];
            $json = $data->save_produccionOrder($value);
            echo $json;
            
            return;
          break;

          case 'save_workgroup':
            $value = $_POST['value'];
            $json = $data->saveWorkgroup($value);
            echo $json;
            return;
          break;

          case 'save_productionOrderTracking':
            $value = $_POST['value'];
            $json = $data->saveProductionOrderTracking($value);
            echo $json;
            return;
          break;
        }
    }
  }

?>