<?php
$id = $_SESSION["PKUsuario"];
$empresa = $_SESSION["IDEmpresa"];

$stmt = $conn->prepare("select fp.funcion_ver, fp.funcion_exportar, fp.funcion_editar, fp.funcion_eliminar, fp.funcion_agregar from funciones_permisos fp 
inner join usuarios u on u.perfil_id=fp.perfil_id where u.id='$id' and u.empresa_id=$empresa and fp.pantalla_id = 31");
        $stmt->execute();
        $row = $stmt->fetch();
        $rows = $stmt->rowCount();
        if($rows<=0){
            $row['funcion_ver']=0;
            $row['funcion_exportar']=0;
            $row['funcion_editar']=0;
            $row['funcion_eliminar']=0;
            $row['funcion_agregar']=0;
        }
    echo ('<input id="ver" type="hidden" value="'.$row['funcion_ver'].'">');
    echo ('<input id="exportar" type="hidden" value="'.$row['funcion_exportar'].'">');
    echo ('<input id="edit" type="hidden" value="'.$row['funcion_editar'].'">');
    echo ('<input id="delete" type="hidden" value="'.$row['funcion_eliminar'].'">');
    echo ('<input id="add" type="hidden" value="'.$row['funcion_agregar'].'">');

?>