<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_notiTask':
            $value = $_REQUEST['data'];
            $ruta = $_REQUEST['ruta'];
            $json = $data->getNotiTask($value,$ruta);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_notiChat':
            $value = $_REQUEST['data'];
            $json = $data->getNotiChat($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_notiSubTask':
            $value = $_REQUEST['data'];
            $json = $data->getNotiSubTask($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_notiVerification':
            $value = $_REQUEST['data'];
            $json = $data->getNotiVerification($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_countNoti':
            $value = $_REQUEST['data'];
            $ruta = $_REQUEST['ruta'];
            $json = $data->getCountNoti($value,$ruta);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case 'get_notiTotal':
            $value = $_REQUEST['data'];
            $ruta = $_REQUEST['ruta'];
            $json = $data->getNotiTotal($value,$ruta);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        break;
        }
      break;
    }
  }



?>
