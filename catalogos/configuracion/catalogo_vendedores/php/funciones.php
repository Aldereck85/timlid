<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_sellerTable':
            $json = $data->getSellerTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "validar_existeVendedor":
            $vendedor = $_REQUEST['data'];
            $json = $data->validarExisteVendedor($vendedor);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_existeRelacionVendedor":
            $fkvendedor = $_REQUEST['data'];
            $json = $data->validarExisteRelacionVendedor($fkvendedor);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
    }
  }

?>