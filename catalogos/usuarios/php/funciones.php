<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_userTable':
            $json = $data->getUserTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case 'get_rols':
            $value = $_REQUEST['value'];
            $json = $data->getRols($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_user':
            $value = $_REQUEST['value'];
            $json = $data->getUser($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_employer':
            //$value = $_REQUEST['value'];
            $json = $data->getEmployer();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
        }
      break;
      case "save_data":
        $save = new save_data();
        switch ($_REQUEST['funcion']){
          case "save_user":
            $user = $_REQUEST['user'];
            $name = $_REQUEST['name'];
            $pass = $_REQUEST['pass'];
            $employer = $_REQUEST['employer'];
            $json = $save->saveUser($user, $name, $pass,$employer);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
        }
      break;
      case "update_data":
        $update = new update_data();
        switch($_REQUEST['funcion']){
          case "update_user":
            $idUser = $_REQUEST['value'];
            $user = $_REQUEST['user'];
            $name = $_REQUEST['name'];
            $pass = $_REQUEST['pass'];
            $json = $update->updateUser($idUser, $user, $name, $pass);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
        }
      break;
      case "delete_data":
        $delete = new delete_data();
        switch ($_REQUEST['funcion']) {
          case 'delete_user':
            $value = $_REQUEST['value'];
            $json = $delete->deleteUser($value);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
        }
      break;
    }
  }
?>