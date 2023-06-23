<?php
session_start();
if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
        $id = (int) $_POST['id'];
        try{
            $stmt = $conn->prepare("SELECT * FROM estados_federativos WHERE FKPais = :id");
            $stmt->execute(array(':id'=>$id));
            $row = $stmt->fetchAll(); //PDO::FETCH_OBJ
            if (count($row) > 0){
                echo "<option value='' disabled selected hidden>Seleccionar estado</option>";
                foreach($row as $r){
                    echo "<option value='".$r['PKEstado']."'>".$r['Estado']."</option>";
                }  
            }else{
                echo "<option value='' disabled>No hay registros para mostrar.</option>";
            }
            
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
}
?>
