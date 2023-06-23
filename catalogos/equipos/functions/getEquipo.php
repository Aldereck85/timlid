<?php
    if(isset($_POST['id'])){
        require_once('../../../include/db-conn.php');
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT * FROM equipos WHERE PKEquipo = :id");
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();

        if(count($row) > 0){
            $html = $row['Nombre_Equipo'];
        }
        else{
            $html = 'No tiene nombre de equipo';
        }
        echo $html;
    }
?>