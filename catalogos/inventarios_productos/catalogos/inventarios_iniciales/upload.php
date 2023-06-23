<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
class conectar
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}
$con = new conectar();
$db = $con->getDb();

$PKSucursal = $_POST['sucursal'];
$excelfile = $_FILES['dataexcel'];
$PKInventario = 0;
//echo json_encode(['sucursal' => $PKSucursal, 'file' => $excelfile['name']]);

$directorio = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';
$subir_archivo = $directorio . basename($excelfile['name']);
//echo json_encode(['sdsds' => $excelfile['tmp_name']]);
try {
    if (move_uploaded_file($excelfile['tmp_name'], $subir_archivo)) {

        $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

        if (in_array($excelfile['type'], $allowedFileType)) {

            $extension = explode('.', $excelfile['name']);

            if ($excelfile['type'] == "application/vnd.ms-excel" || $extension[1] == "xls") {
                include '../../../../lib/SimpleXLS/SimpleXLS.php';

                $xls = SimpleXLS::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name']);
                //echo json_encode($xls->rows());

                $PKEmpresa = $_SESSION["IDEmpresa"];

                $count0 = 0;
                $ignorarFila1 = 1;
                $ignorarFila2 = 1;

                foreach ($xls->rows() as $campo) {
                    $datos = validar();
                    foreach($datos as $data){
                        if (trim($campo[0]) == $data['ClaveInterna'] && $count0 == 0) {
                            $ignorarFila1 = 0;
                        }
                        if (trim($campo[0]) == $data['ClaveInterna'] && $count0 == 1) {
                            $ignorarFila2 = 0;
                        } 
                    }
                    ++$count0;
                }

                try {
                    $query = sprintf('call spd_Detalle_Inventario_PorSucursal (?)');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($PKSucursal));

                    $data[0] = ['status' => $status];
                    echo json_encode(['status' => 'success']);

                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }

                $count1 = 0;

                foreach ($xls->rows() as $campo) {
                    switch([$ignorarFila1, $ignorarFila2]){
                        case [1, 1]:
                            if($count1 > 1){
                                if (trim($campo[3]) != '') {
                                    $Clave = trim($campo[0]);
                                    $Cantidad = trim($campo[3]);
                                    //$Serie = trim($campo[4]);
                                    $Lote = trim($campo[5]);
                                    if(date_parse(trim($campo[6]))["year"] == false){
                                        $Caducidad = '0000-00-00';
                                    }else {
                                        $Caducidad = trim($campo[6]);
                                    }
    
                                    //insertar($PKSucursal, $Clave, $Lote, $Serie, $Caducidad, $Cantidad);
                                    insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                                    
                                }
                            }
                        break;
                        case [1, 0]:
                            if($count1 > 0){
                                if (trim($campo[3]) != '') {
                                    $Clave = trim($campo[0]);
                                    $Cantidad = trim($campo[3]);
                                    //$Serie = trim($campo[4]);
                                    $Lote = trim($campo[5]);
                                    if(date_parse(trim($campo[6]))["year"] == false){
                                        $Caducidad = '0000-00-00';
                                    }else {
                                        $Caducidad = trim($campo[6]);
                                    }
    
                                    insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                                    //insertar($PKSucursal, $Clave, $Lote, $Serie, $Caducidad, $Cantidad);
                                    
                                }
                            }
                        break;
                        case [0, 0]:
                            if (trim($campo[3]) != '') {
                                $Clave = trim($campo[0]);
                                $Cantidad = trim($campo[3]);
                                //$Serie = trim($campo[4]);
                                $Lote = trim($campo[5]);
                                if(date_parse(trim($campo[6]))["year"] == false){
                                    $Caducidad = '0000-00-00';
                                }else {
                                    $Caducidad = trim($campo[6]);
                                }

                                insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                                //insertar($PKSucursal, $Clave, $Lote, $Serie, $Caducidad, $Cantidad);
                                
                            }
                        break;
                    }
                    ++$count1;                    
                }

                insertarResto($PKSucursal);

            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../../../lib/SimpleXLS/SimpleXLSX.php';

                $xls = SimpleXLSX::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name']);
                //echo json_encode($xls->rows());

                $PKEmpresa = $_SESSION["IDEmpresa"];

                $count0 = 0;
                $ignorarFila1 = 1;
                $ignorarFila2 = 1;

                foreach ($xls->rows() as $campo) {
                    $datos = validar();
                    foreach($datos as $data){
                        if (trim($campo[0]) == $data['ClaveInterna'] && $count0 == 0) {
                            $ignorarFila1 = 0;
                        }
                        if (trim($campo[0]) == $data['ClaveInterna'] && $count0 == 1) {
                            $ignorarFila2 = 0;
                        } 
                    }
                    ++$count0;
                }

                try {
                    $query = sprintf('call spd_Detalle_Inventario_PorSucursal (?)');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($PKSucursal));

                    $data[0] = ['status' => $status];
                    //echo json_encode(['status' => 'success']);

                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }

                $count1 = 0;

                foreach ($xls->rows() as $campo) {
                    switch([$ignorarFila1, $ignorarFila2]){
                        case [1, 1]:
                            if($count1 > 1){
                                if (trim($campo[3]) != '') {
                                    $Clave = trim($campo[0]);
                                    $Cantidad = trim($campo[3]);
                                    //$Serie = trim($campo[4]);
                                    $Lote = trim($campo[5]);
                                    if(date_parse(trim($campo[6]))["year"] == false){
                                        $Caducidad = '0000-00-00';
                                    }else {
                                        $Caducidad = trim($campo[6]);
                                    }
    
                                    //insertar($PKSucursal, $Clave, $Lote, $Serie, $Caducidad, $Cantidad);
                                    insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                                    
                                }
                            }
                        break;
                        case [1, 0]:
                            if($count1 > 0){
                                if (trim($campo[3]) != '') {
                                    $Clave = trim($campo[0]);
                                    $Cantidad = trim($campo[3]);
                                    //$Serie = trim($campo[4]);
                                    $Lote = trim($campo[5]);
                                    if(date_parse(trim($campo[6]))["year"] == false){
                                        $Caducidad = '0000-00-00';
                                    }else {
                                        $Caducidad = trim($campo[6]);
                                    }
    
                                    insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                                    //insertar($PKSucursal, $Clave, $Lote, $Serie, $Caducidad, $Cantidad);
                                    
                                }
                            }
                        break;
                        case [0, 0]:
                            if (trim($campo[3]) != '') {
                                $Clave = trim($campo[0]);
                                $Cantidad = trim($campo[3]);
                                //$Serie = trim($campo[4]);
                                $Lote = trim($campo[5]);
                                if(date_parse(trim($campo[6]))["year"] == false){
                                    $Caducidad = '0000-00-00';
                                }else {
                                    $Caducidad = trim($campo[6]);
                                }

                                insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                                //insertar($PKSucursal, $Clave, $Lote, $Serie, $Caducidad, $Cantidad);
                                
                            }
                        break;
                    }
                    ++$count1;                    
                }

                insertarResto($PKSucursal);

            }
        }
    } else {
        echo json_encode(['status' => 'fail']);
    }
} catch (\Throwable $th) {
    echo $th;
}

function validar(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('call spc_Validar_Excel_Inventario_Inicial(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

function insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad){// $Serie,
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('call spi_Detalle_Inventarios_Iniciales_PorSucursalExcel(?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal, $Clave, $Lote,  $Caducidad, $Cantidad, $PKEmpresa));//$Serie, between cantidad y lote

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

function insertarResto($PKSucursal){
    $con = new conectar();
    $db = $con->getDb();
    try {
        $query = sprintf("SELECT id FROM inventario_por_sucursales WHERE sucursal_id = _PKSucursal");
        $stmt = $stmt = $db->prepare($query);
        $status = $stmt->execute(array($PKSucursal));
        $PKInventario = $stmt->fetch(PDO::FETCH_ASSOC)['id']; 

        $data[0] = ['status' => $status];
        //echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $query = sprintf("INSERT INTO  detalle_inventario_por_sucursales(producto_id, clave, numero_lote, caducidad, cantidad_toma, `cantidad_toma 1`, `cantidad_toma 2`, `cantidad_toma 3`, cantidad_sistema, inventario_id, estatus)
        SELECT PKProducto, ClaveInterna, '', '', '0000-00-00', 0, NULL, NULL, NULL, 0, :IdInventario, 1 
        FROM productos p INNER JOIN sucursales s ON p.empresa_id=s.empresa_id AND s.id=:PKSucursal
        WHERE PKProducto NOT IN (SELECT producto_id FROM detalle_inventario_por_sucursales WHERE inventario_id=:IdInventario2)");
        $stmt = $stmt = $db->prepare($query);
        $status = $stmt->execute(array(':IdInventario' => $PKInventario, ':PKSucursal' => $PKSucursal, ':IdInventario2' => $PKInventario));

        $data[0] = ['status' => $status];
        //echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

unlink($directorio);