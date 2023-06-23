<?php
session_start();

if(isset($_POST['id'])){
    require_once('../../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM empleados WHERE PKEmpleado= :id");

    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();
    $html = $row['PKEmpleado'];
        
    $json->html = $html;

    $json = json_encode($json);
    echo $json;
    
    $con = null;
      $db = null;
      $stmt = null;
}
?>