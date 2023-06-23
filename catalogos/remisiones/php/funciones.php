<?php
  include_once("clases.php");
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case 'get_data':
        $data = new get_data();
        switch($_REQUEST['funcion']){
          case 'get_remissionsTable':
            $json = $data->getRemissionsTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case 'get_cliente':
            $json = $data->getClients();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_ordenesPedido':
            $value = $_REQUEST['value'];
            $json = $data->getOrdenesPedido($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_salidas':
            $value = $_REQUEST['value'];
            $json = $data->getSalidas($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_productosSalidas':
            $value = $_REQUEST['value'];
            $json = $data->getProductosSalidas($value);
            echo $json;
          break;
          case 'get_ordenPedido':
            $value = $_REQUEST['value'];
            $json = $data->getOrdenPedido($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_totalSubtotalSalidas':
            
            $json = $data->getTotalSubtotalSalidas();
            echo json_encode($json);
          break;
          case 'get_dataProduct':
            
            $id_row = $_REQUEST['id_row'];
            $json = $data->getDataProduct($id_row);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_impuestoTable':
            $value = $_REQUEST['value'];
            $producto = $_REQUEST['producto'];
            $tipo_doc = $_REQUEST['tipo_doc'];
            $id = $_REQUEST['id'];
            $json = $data->getImpuestoTable($value,$producto,$tipo_doc,$id);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case 'get_impuestos':
            $value = $_REQUEST['value'];
            $json = $data->getImpuestos($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_invoiceDetailTable':
            $value = $_REQUEST['value'];
            $json = $data->getInvoiceDetailTable($value);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case 'get_invoiceDetail':
            $value = $_REQUEST['value'];
            $json = $data->getInvoiceDetail($value);//Guardando el return de la función
            echo json_encode($json);
          break;
          case 'get_productosEditTable':
            $value = $_REQUEST['value'];
            $tipo = $_REQUEST['tipo_doc'];
            $json = $data->getProductosEditTable($value,$tipo);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case 'get_claveSat':
            $value = $_REQUEST['value'];
            $json = $data->getClaveSat($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_claveSatTable':
            $json = $data->getClaveSatTable();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_claveSatTableSearch':
            $value = $_REQUEST['value'];
            $json = $data->getClaveSatTableSearch($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_claveUnidadMedidaTable':
            $json = $data->getClaveUnidadMedidaTable();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_claveUnidadMedidaTableSearch':
            $value = $_REQUEST['value'];
            $json = $data->getClaveUnidadMedidaTableSearch($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_unidadMedida':
            $value = $_REQUEST['value'];
            $json = $data->getUnidadMedida($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_truncateTableProducts':
            $json = $data->getTruncateTableProducts();
            echo json_encode($json);
          break;
        }
      break;
      case "save_data":
        $data = new save_data();
        switch ($_REQUEST['funcion']) {
          case 'save_remision':
            $value = $_REQUEST['value'];
            $pedidos = $_REQUEST['pedidos'];
            $salidas = $_REQUEST['salidas'];
            $json = $data->saveRemission($value,$pedidos,$salidas);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'save_dataTaxes':
            
            $prod = $_REQUEST['producto'];
            $value = $_REQUEST['value'];
            $tasa = $_REQUEST['tasa'];
            $tipo = $_REQUEST['tipo'];
            $id = $_REQUEST['id'];
            $json = $data->saveDataTaxes($prod,$value,$tasa,$id);
            echo json_encode($json);
          break;
        }
      break;
      case "delete_data":
        $data = new delete_data();
        switch($_REQUEST['funcion']){
          case 'delete_taxProducto':
            $cot = $_REQUEST['cot'];
            $prod = $_REQUEST['producto'];
            $value = $_REQUEST['value'];
            $tipo = $_REQUEST['tipo'];
            $id = $_REQUEST['id'];
            $tasa = $_REQUEST['tasa'];
            $json = $data->deleteTaxProducto($cot,$prod,$value,$tipo,$id,$tasa);
            echo json_encode($json);
          break;
        }
      break;
      case "edit_data":
        $data = new edit_data();
        switch($_REQUEST['funcion']){
          case "edit_dataProducto":
            $value = $_REQUEST['value'];
            $json = $data->editDataProducto($value);
            echo json_encode($json);
          break;
          case "edit_claveSat":
            $value = $_REQUEST['value'];
            $prod = $_REQUEST['prod'];
            $json = $data->editClaveSat($value,$prod);
            echo json_encode($json);
          break;
          case "edit_claveSatUnidad":
            $value = $_REQUEST['value'];
            $prod = $_REQUEST['prod'];
            $json = $data->editClaveUnidadSat($value,$prod);
            echo json_encode($json);
          break;
        }
      break;
    }
  }

?>