<?php
session_start();

/* Validamos la sesión */
if (isset($_SESSION["PKUsuario"]) && isset($_SESSION["IDEmpresa"])) {
  if ((isset($_FILES['avatar']) && !empty($_FILES['avatar']))) {
    require_once '../../../include/db-conn.php';
    $idUsuario = $_SESSION["PKUsuario"];
    $avatar = $_FILES['avatar'];
    $nombreAvatar = $_SESSION["PKUsuario"] . '-avatar.png';
    $appUrlWrite = $_ENV['RUTA_ARCHIVOS_WRITE'] . $_SESSION["IDEmpresa"] . '/img/';

    try {
      $query = sprintf('UPDATE usuarios SET imagen = :avatar WHERE id = :idUsuario');
      $stmt = $conn->prepare($query);
      if (!$stmt->execute([':avatar' => $nombreAvatar, ':idUsuario' => $idUsuario])) {
        throw new \Exception('Fallo al editar el usuario');
      }

      $location = $appUrlWrite . $nombreAvatar;
      $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
      $imageFileType = strtolower($imageFileType);
      $valid_extensions = array('png', 'gif', 'svg');

      if (in_array(strtolower($imageFileType), $valid_extensions)) {
        if (file_exists($location)) {
          chmod($location, 0755); //Change the file permissions if allowed
          unlink($location); //remove the file
        }
        if (!move_uploaded_file($avatar['tmp_name'], $location)) {
          throw new \Exception('Fallo al editar el avatar');
        }
      } else {
        throw new \Exception('No tiene una extensión valida');
      }

      $data['status'] = 'success';
      $data['message'] = 'Avatar actualizado';

      echo json_encode($data);
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      echo json_encode($data);
    }
  } else {
    echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
  }
} else {
  session_destroy();
  header("location:../../../index.php");
}