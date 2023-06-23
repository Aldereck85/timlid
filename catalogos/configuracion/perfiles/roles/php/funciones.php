<?php
include_once("clases.php");
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

  switch ($_REQUEST['clase']) {
    case "get_data":
      $data = new get_data();
      switch ($_REQUEST['funcion']) {
        case 'getSections':
          $json = $data->getSections(); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'getScreens':
          $json = $data->getScreens(); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'getScreensVal':
          $value = $_REQUEST['value'];
          $json = $data->getScreensVal($value); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'getFunctionsVal':
          $value = $_REQUEST['value'];
          $json = $data->getFunctionsVal($value); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'getFunctionsValues':
          $value = $_REQUEST['value'];
          $value1 = $_REQUEST['id'];
          $json = $data->getFunctionsValues($value, $value1); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'get_roles':
          $json = $data->getRoles(); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'getProfile':
          $value = $_REQUEST['value'];
          $json = $data->getProfile($value); //Guardando el return de la función
          echo json_encode($json);
          break;
      }
      break;

    case "save_data":
      $save = new save_data();
      switch ($_REQUEST['funcion']) {
        case "savePermission":
          $value = $_POST['value'];
          $perfil = $_POST['perfil'];
          $rol = $_POST['rol'];
          $id_perfil = $_POST['id_perfil'];
          $user = $_POST['user'];
          $name = $_POST['name'];
          $role = $_POST['role'];
          $json = $save->savePermission($value, $perfil, $rol, $id_perfil, $user, $name, $role); //Guardando el return de la función
          echo json_encode($json);
          break;
      }
      break;

    case "delete_data":
      $delete = new delete_data();
      switch ($_REQUEST['funcion']) {
        case "deletePermission":
          $value = $_POST['value'];
          $json = $delete->deleteProfile($value); //Guardando el return de la función
          echo json_encode($json);
          break;
      }
      break;
  }
}
