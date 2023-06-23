<?php
session_start();
class conectar
{ //Llamado al archivo de la conexión.


    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

    $word = $_REQUEST["palabra"];
    $empresa = $_SESSION["IDEmpresa"];

    $conn = new conectar();
    $db = $conn->getDb(); 

    $query = sprintf('call spc_Concepto_LIKE(?,?)');
    $stmt = $db->prepare($query);
    $stmt->execute(array($word,$empresa));
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($data);
?>