<?php
session_start();
require_once('../include/db-conn.php');

$valido    = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/', $_POST["Contrasena"]);
if(!$valido){
  echo "fallo";
  return;
}

$token = $_POST["csr_token_78L4"];
$contrasena = htmlspecialchars($_POST["Contrasena"], ENT_QUOTES);

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        echo "error-general";
    }
    else{
          include_once("../functions/functions.php");

          try{
            $password = password_hash($contrasena, PASSWORD_DEFAULT);

            $id = encryptor("decrypt", $_POST["Id"]);
            $statement = $conn->prepare("UPDATE usuarios SET password = :contrasena WHERE id = :id");
            $statement->bindValue(':contrasena',$password);
            $statement->bindValue(':id', $id);
            if($statement->execute()){
              echo "exito";
            }
            else{
              echo "fallo";
            }
            
          }
          catch(PDOException $error)
          {
                $message = $error->getMessage();
          }
    }
}
else{
    echo "error-general2";
}

?>