<?php
require_once('../../include/db-conn.php');

$nodeDrag = $_POST['nodeDrag'];
$nodeDrop = $_POST['nodeDrop'];

try{

  if($nodeDrag != "" && $nodeDrop != ""){
	  $statement = $conn->prepare("UPDATE organigrama SET ParentNode = :parentNode WHERE PKOrganigrama = :idorganigrama ");
	  $statement->bindValue(':parentNode', $nodeDrop);
	  $statement->bindValue(':idorganigrama', $nodeDrag);
	  
	  if($statement->execute())
		  echo "exito";
	  else
		  echo "fallo";
  }
  else{
	  echo "fallo";
  }
}
catch(PDOException $error)
{
      $message = $error->getMessage();
}


?>