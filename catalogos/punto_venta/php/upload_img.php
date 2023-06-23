<?php
  session_start();
  include "../../../include/db-conn.php";
  $ban = false;
  $id = $_POST['id'];
  if(isset($_FILES)){
    $src = $_FILES['image']['tmp_name'];
    $filename = $_FILES['image']['name'];
    
    $name = basename($filename);
    $output_dir = $_ENV['RUTA_ARCHIVOS_WRITE'].$_SESSION['IDEmpresa']."/img/".$name;
    
    if(move_uploaded_file($src, $output_dir)){
      $query = sprintf("update productos set Imagen = '{$name}' where PKProducto = {$id}");
      $stmt = $conn->prepare($query);
      $ban = $stmt->execute();
    } else {
      $ban="error al mover el archivo";
    }

  } else {
    $ban = "Archivo vacío";
  }

  return $ban;
  
?>