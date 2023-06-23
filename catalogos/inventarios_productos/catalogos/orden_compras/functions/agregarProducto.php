<?php
session_start();
include "../../../../../include/db-conn.php";

    $PKProducto = 0;

    try {
        $query = "INSERT INTO productos(Nombre, ClaveInterna, FKCategoriaProducto, FKTipoProducto, FKMarcaProducto, usuario_creacion_id, usuario_edicion_id, empresa_id, created_at, updated_at, estatus, serie, lote, fecha_caducidad)
                  VALUES (:nombre, :clave, 1, :tipo, 1, :usuario, :usuario_e, :empresa_id, (SELECT NOW()), (SELECT NOW()), 1, 0, 0, 0)";
        $stmt = $conn->prepare($query);
        if($stmt->execute(array(':nombre' => $_REQUEST['nombre'],':clave' => $_REQUEST['clave'], ':tipo' => $_REQUEST['tipo'], ':usuario' => $_SESSION['PKUsuario'],':usuario_e' => $_SESSION['PKUsuario'], ':empresa_id' => $_SESSION["IDEmpresa"]))){
            $PKProducto = $conn->lastInsertId();
        }else{
            return 'fallo';
        }

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $querySP = 'INSERT INTO datos_producto_proveedores (NombreProducto, Clave, Precio, FKTipoMoneda, FKProveedor, FKProducto) VALUES(:nombre_producto,:clave,:precio,:id_moneda,:id_proveedor,:id_producto)';
        $stmtSP = $conn->prepare($querySP);
        if($stmtSP->execute(array(':nombre_producto' => $_REQUEST['nombre'], ':clave' => $_REQUEST['clave'], ':precio' => 1.00, ':id_moneda' => 100, ':id_proveedor' => $_REQUEST['proveedor'], ':id_producto' => $PKProducto))){
            echo 'exito';
        }
       

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

?>