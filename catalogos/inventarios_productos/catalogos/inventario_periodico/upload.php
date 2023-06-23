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
                echo json_encode($xls->rows());

                try {
                    $query = sprintf('call spd_Detalle_InventarioPeriodico (?)');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($PKSucursal));

                    $data[0] = ['status' => $status];
                    //echo json_encode(['status' => 'success']);

                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }

                $count = 0;

                foreach ($xls->rows() as $campo) {
                    if ($count > 1) {
                        $campoVacio = false;
                        if (trim($campo[3]) != '') {
                            $Clave = trim($campo[0]);
                            $Cantidad = trim($campo[3]);
                            $Lote = trim($campo[4]);
                            if(date_parse(trim($campo[6]))["year"] == false){
                                $Caducidad = '0000-00-00';
                            }else {
                                $Caducidad = trim($campo[6]);
                            }

                            insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad);
                        }
                    }
                    ++$count;
                }
            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../../../lib/SimpleXLS/SimpleXLSX.php';

                $xls = SimpleXLSX::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name']);
                //echo json_encode($xls->rows());

                try {
                    $query = sprintf('call spd_Detalle_InventarioPeriodico (?)');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($PKSucursal));

                    $data[0] = ['status' => $status];
                    //echo json_encode(['status' => 'success']);

                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }

                $count = 0;

                foreach ($xls->rows() as $campo) {
                    if ($count > 1) {
                        $campoVacio = false;
                        if (trim($campo[3]) != '') {
                            $Clave = trim($campo[0]);
                            $Cantidad = trim($campo[3]);
                            $Lote = trim($campo[4]);
                            if(date_parse(trim($campo[5]))["year"] == false){
                                $Caducidad = '0000-00-00';
                            }else {
                                $Caducidad = trim($campo[5]);
                            }
                            echo $count;
                            insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad, $PKEmpresa);
                        }
                    }
                    $count++;
                }
            }
        }
    } else {
        echo json_encode(['status' => 'fail']);
    }
} catch (\Throwable $th) {
    echo $th;
}

function insertar($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad, $PKEmpresa){
    $con = new conectar();
    $db = $con->getDb();
    try {
        $query = sprintf('call spi_Detalle_InventariosPeriodicos_Excel (?,?,?,?,?,?)');
        $stmt = $stmt = $db->prepare($query);
        $status = $stmt->execute(array($PKSucursal, $Clave, $Lote, $Caducidad, $Cantidad, $PKEmpresa));

        $data[0] = ['status' => $status];
        echo json_encode(['status' => 'success']);

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

unlink($subir_archivo);
