<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'getSections':
            $json = $data->getSections();//Guardando el return de la función
            echo json_encode($json);
          break;
          case 'getScreens':
            $json = $data->getScreens();//Guardando el return de la función
            echo json_encode($json);
          break;
          case 'getScreensVal':
            $value = $_REQUEST['value'];
            $json = $data->getScreensVal($value);//Guardando el return de la función
            echo json_encode($json);
          break;
          case 'getFunctionsVal':
            $value = $_REQUEST['value'];
            $json = $data->getFunctionsVal($value);//Guardando el return de la función
            echo json_encode($json);
          break;
          case 'getFunctionsValues':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['id'];
            $json = $data->getFunctionsValues($value,$value1);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
      break;

      case "save_data":
        $save = new save_data();
        switch($_REQUEST['funcion']){
          case "savePermission":
            $value = $_POST['value'];
            $user = $_POST['usuario'];
            $json = $save->savePermission($value,$user);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
      break;
    }

  }

?>