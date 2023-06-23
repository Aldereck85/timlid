<?php
include_once("clases.php");
$array = "";
if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case "get_Traspasos":
            $fromDate = $_REQUEST['data3'];
            $toDate = $_REQUEST['data4'];
            $json = $data->getTraspasos($fromDate, $toDate);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "get_cmb_productosSucOrigen":
            $pkSucursalOrigen = $_REQUEST['data'];
            $json = $data->getCmbProductosSucOrigen($pkSucursalOrigen); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "get_estatusSalidaTraspasoPedido":
            $OrdenPedido = $_REQUEST['data'];
            $json = $data->getEstatusSalidaTraspasoPedido($OrdenPedido); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "get_ParcialidadesPedido":
            $PKPedido = $_REQUEST['data'];
            $json = $data->getParcialidadesPedido($PKPedido); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "get_TipoParcialidadesPedido":
            $Folio = $_REQUEST['data'];
            $json = $data->getTipoParcialidadesPedido($Folio); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "get_LotesTraspaso":
            $pkProducto = $_REQUEST['data'];
            $pkSucursalOrigen = $_REQUEST['data2'];
            $json = $data->getLotesTraspaso($pkProducto, $pkSucursalOrigen); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        }
      break;

  }
}


?>
