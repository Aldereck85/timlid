<?php
include_once "clases.php";
$array = "";
if (isset($_POST['clase']) && isset($_POST['funcion'])) {
  switch ($_POST['clase']) {
    case "get_data":
      $data = new get_data();
      switch ($_POST['funcion']) {
        case "get_secciones":
          $result = $data->getSecciones();
          echo json_encode($result);
          break;
        case "validate_seccion":
          $valor = $_POST['valor'];
          $tipo = $_POST['tipo'];
          $result = $data->validateSeccion($valor, $tipo);
          echo json_encode($result);
          break;
      }
      break;
    case "save_data":
      $data = new save_data();
      switch ($_POST['funcion']) {
        case "save_seccion":
          $nombre = $_POST['nombre'];
          $siglas = $_POST['siglas'];
          $perfiles = $_POST['perfiles'];
          $icono = $_FILES['icono'];
          $result = $data->saveSeccion($nombre, $siglas, $perfiles, $icono);
          echo json_encode($result);
          break;
        case "save_pantalla":
          $nombre = $_POST['nombre'];
          $url = $_POST['url'];
          $seccion = $_POST['seccion'];
          $perfiles = $_POST['perfiles'];
          $result = $data->savePantalla($nombre, $url, $seccion, $perfiles);
          echo json_encode($result);
          break;
      }
      break;
    case "edit_data":
      $data = new edit_data();
      switch ($_POST['funcion']) {
        case "edit_orden_secciones":
          $nuevoOrden = $_POST["nuevoOrden"];
          $result = $data->editOrdenSecciones($nuevoOrden);
          echo json_encode($result);
          break;
        case "edit_orden_pantallas":
          $seccion = $_POST["seccion"];
          $nuevoOrden = $_POST["nuevoOrden"];
          $result = $data->editOrdenPantallas($seccion, $nuevoOrden);
          echo json_encode($result);
          break;
        case "validar_editar":
          $id = $_POST['id'];
          $valor = $_POST['valor'];
          $tipo = $_POST['tipo'];
          $result = $data->validarEditar($id, $valor, $tipo);
          echo json_encode($result);
          break;
      }
      break;
    case "delete_data":
      $data = new delete_data();
      switch ($_POST['funcion']) {
        case "eliminar_pantalla_seccion":
          $id = $_POST['id'];
          $tipo = $_POST['tipo'];
          $json = $data->eliminarPantallaSeccion($id, $tipo);
          echo json_encode($json);
          break;
      }
      break;
  }
}
