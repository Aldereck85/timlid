<?php
  include "../../../include/db-conn.php";

  $query = sprintf("select * from claves_sat");
  $stmt = $conn->prepare($query);
  //$stmt->bindValue(":id",$prod);
  $stmt->execute();


?>