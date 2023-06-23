<?php
include_once "clases.php";

session_start();
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];

if (isset($_POST['clase']) && isset($_POST['funcion'])) {
  switch ($_POST['clase']) {
    case "get_data":
      $data = new get_data();
      switch ($_POST['funcion']) {
        case "get_tareas":
          $terminoBusqueda = $_POST['terminoBusqueda'];
          $filtros = isset($_POST['filtros']) ? $_POST['filtros'] : [];
          $result = $data->getTareas($idUsuario, $terminoBusqueda, $filtros);
          echo json_encode($result);
          break;
        case "get_tarea":
          $id = $_POST['id'];
          $result = $data->getTarea($id);
          echo json_encode($result);
          break;
        case "check_concurrencias":
          $result = $data->checkRecurrencias($idUsuario);
          echo json_encode($result);
          break;
      }
      break;
    case "save_data":
      $data = new save_data();
      switch ($_POST['funcion']) {
        case "save_tarea":
          $tarea = $_POST['tarea'];
          $result = $data->saveTarea($idUsuario, $tarea);
          echo json_encode($result);
          break;
      }
      break;
    case "edit_data":
      $data = new edit_data();
      switch ($_POST['funcion']) {
        case "edit_tarea":
          $tarea = $_POST["tarea"];
          $result = $data->editTarea($tarea);
          echo json_encode($result);
          break;
        case "finalizar_tarea":
          $id = $_POST["id"];
          $result = $data->finalizarTarea($id);
          echo json_encode($result);
          break;
      }
      break;
    case "delete_data":
      $data = new delete_data();
      switch ($_POST['funcion']) {
        case "delete_tarea":
          $id = $_POST['id'];
          $json = $data->delete_tarea($id);
          echo json_encode($json);
          break;
      }
      break;
  }
}
