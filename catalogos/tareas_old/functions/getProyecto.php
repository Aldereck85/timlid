<?php
    if(isset($_POST['id'])){
        require_once('../../../include/db-conn.php');
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT * FROM proyectos WHERE PKProyecto = :id");
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();

        if(count($row) > 0){
            $html = $row['Proyecto'];
        }
        else{
            $html = 'No tiene nombre de proyecto.';
        }
        echo $html;

    }

?>