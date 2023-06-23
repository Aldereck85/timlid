<?php
include_once("clases.php");
$array = "";
if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case "get_OrdenPedido":
            $json = $data->getOrdenPedido();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;

        }
      break;

  }
}


?>
