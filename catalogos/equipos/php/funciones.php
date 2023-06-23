<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_teamsTable':
            $json = $data->getTeamsTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;

          case 'get_userComboEdit':
            $idEquipo = $_REQUEST['idEquipo'];
            $json = $data->getUserComboEdit($idEquipo);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_teamsEdit':
            $value = $_REQUEST['value'];
            $json = $data->getTeamsEdit($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_userEditCombo':
            $json = $data->getUserEditCombo();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            
          break;

          case 'get_userEditComboMult':
            $json = $data->getUserEditComboMulti();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_members':
            $value = $_REQUEST['value'];
            $d = $_REQUEST['data'];
            $json = $data->getMembers($value,$d);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
        }
      break;
    }
  }


?>