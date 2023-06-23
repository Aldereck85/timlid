<?php
session_start();
include "../../../include/db-conn.php";

    $PKProducto = 0;
    $idSucursal = $_REQUEST['idSucursal'];  
    $existencia = $_REQUEST['existenciaFabricacion'];  
    $clave = $_REQUEST['clave'];  
    $unidadSat = $_REQUEST['unidadSat'];  

    if(isset($_REQUEST['idImpuestosArray'])){
        $idImpuestosArray = $_REQUEST['idImpuestosArray'];
    }
    else{
        $idImpuestosArray = array();
    }

    if(isset($_REQUEST['tasaImpuestosArray'])){
        $tasaImpuestosArray = $_REQUEST['tasaImpuestosArray'];
    }
    else{
        $tasaImpuestosArray = array();
    }

    if(trim($existencia) == ''){
        $existencia = 0;
    }

    try {
        $query = "INSERT INTO productos(Nombre, ClaveInterna, FKCategoriaProducto, FKTipoProducto, FKMarcaProducto, usuario_creacion_id, usuario_edicion_id, empresa_id, created_at, updated_at, estatus, serie, lote, fecha_caducidad)
                  VALUES (:nombre, :clave, 1, :tipo, 1, :usuario, :usuario_e, :empresa_id, (SELECT NOW()), (SELECT NOW()), 1, 0, 0, 0)";
        $stmt = $conn->prepare($query);
        if($stmt->execute(array(':nombre' => $_REQUEST['nombre'],':clave' => strtoupper($clave), ':tipo' => $_REQUEST['tipo'], ':usuario' => $_SESSION['PKUsuario'],':usuario_e' => $_SESSION['PKUsuario'], ':empresa_id' => $_SESSION["IDEmpresa"]))){
            $PKProducto = $conn->lastInsertId();

            if($idSucursal != null){

                $query = sprintf('SELECT activar_inventario FROM sucursales WHERE id = ?');
                $stmt = $conn->prepare($query);
                $stmt->execute(array($idSucursal));
                $inventario = $stmt->fetch();

                if($inventario['activar_inventario'] == 1){
                    $query = sprintf('INSERT INTO existencia_por_productos (existencia_minima, existencia_maxima, punto_reorden, numero_lote, numero_serie, caducidad, existencia, sucursal_id, producto_id, clave_producto) VALUES (0,0,0,"","","0000-00-00",?,?,?,?)');
                    $stmt = $conn->prepare($query);
                    $stmt->execute(array($existencia, $idSucursal, $PKProducto, $clave));
                }
            }


            $query = sprintf('insert into info_fiscal_productos ( FKClaveSAT, FKProducto, FKClaveSATUnidad )  VALUES (?,?,?)');
            $stmt = $conn->prepare($query);
            $stmt->execute(array(1, $PKProducto, $unidadSat));
            $idInfoFiscal = $conn->lastInsertId();    

            $contador = 0;
            $cantidadImp = count($idImpuestosArray);

            if($cantidadImp > 0){

                $arrayTasas = array();

                foreach($tasaImpuestosArray as $tas){
                    $arrayTasas[$contador] = $tas;
                    $contador++;
                }

                $contador = 0;
                foreach($idImpuestosArray as $imp){

                    $query = sprintf('insert into impuestos_productos ( FKInfoFiscalProducto, FKImpuesto, Tasa )  VALUES (?,?,?)');
                    $stmt = $conn->prepare($query);
                    $stmt->execute(array($idInfoFiscal, $imp, $arrayTasas[$contador]));

                    $contador++;
                    
                }    
            }    

            $queryC = sprintf('insert into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo)  VALUES (?,?,?,?,?,?,?,?,?)');
            $stmtC = $conn->prepare($queryC);
            $stmtC->execute(array(1.00, 100, $PKProducto, 0.00, 100, 0.00, 100, 0.00, 100));

            $queryOp = sprintf('insert into operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto)  VALUES (?,?,?,?,?)');
            $stmtOp = $conn->prepare($queryOp);
            $stmtOp->execute(array(0, 1, 0, 0, $PKProducto));

        }else{
            return 'fallo';
        }

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $querySP = 'CALL spi_Prod_AgregarCliente(:id_cliente,:costo_especial,:id_moneda,:id_producto,:costo_general,:id_moneda_general,:id_usuario)';
        $stmtSP = $conn->prepare($querySP);
        if($stmtSP->execute(array(':id_cliente' => $_REQUEST['cliente'], ':costo_especial' => 1.00, ':id_moneda' => 100, ':id_producto' => $PKProducto, ':costo_general' => 1.00, ':id_moneda_general' => 100, ':id_usuario' => $_SESSION['PKUsuario']))){
            echo 'exito';
        }
       

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

?>