<?php
$id = $_SESSION["PKUsuario"];
$empresa = $_SESSION["IDEmpresa"];

$stmt = $conn->prepare("select fp.funcion_ver, fp.funcion_exportar, fp.funcion_editar from funciones_permisos fp 
inner join usuarios u on u.perfil_id=fp.perfil_id where u.id=:id and u.empresa_id=:empresa and fp.pantalla_id = 30");

$stmt->bindValue(":id",$id);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();
        $row = $stmt->fetch();
        $rows = $stmt->rowCount();
    if($rows<=0){
        $row['funcion_ver']=0;
        $row['funcion_exportar']=0;
    }
    echo ('<input id="ver" type="hidden" value="'.$row['funcion_ver'].'">');
    echo ('<input id="exportar" type="hidden" value="'.$row['funcion_exportar'].'">');
?>