<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    if($_POST['actualizar'] == 0){
        echo '<option value="">Elegir opción</option>';
          
        $stmt = $conn->prepare('SELECT p.PKProducto,p.Nombre,p.ClaveInterna FROM productos as p WHERE p.estatus = 1 AND p.empresa_id = :idempresa ORDER BY p.Nombre ASC');
        $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
        $stmt->execute();
        
        foreach ($stmt as $option){
            echo '<option value="'.$option['PKProducto'].'">';
                              
              if(trim($option['ClaveInterna']) == ""){
                echo $option['Nombre']."</option>";
              }
              else{
                echo $option['ClaveInterna'] . " - " . $option['Nombre']."</option>";
              }
        }
    }
    else{
        echo '<option value="">Elegir opción</option>';
          
        $stmt = $conn->prepare('SELECT p.PKProducto,p.Nombre,p.ClaveInterna FROM productos as p INNER JOIN tipos_productos as tp ON p.FKTipoProducto = tp.PKTipoProducto LEFT JOIN rel_acciones_producto as rap ON rap.FKProducto = p.PKProducto WHERE rap.FKAccionProducto = 2 AND p.estatus = 1 AND p.empresa_id = :idempresa ORDER BY p.Nombre ASC');
        $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
        $stmt->execute();
        
        foreach ($stmt as $option){
            echo '<option value="'.$option['PKProducto'].'">';
                              
              if(trim($option['ClaveInterna']) == ""){
                echo $option['Nombre']."</option>";
              }
              else{
                echo $option['ClaveInterna'] . " - " . $option['Nombre']."</option>";
              }
        }
    }
}