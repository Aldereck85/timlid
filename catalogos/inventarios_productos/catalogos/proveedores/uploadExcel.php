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

$excelfile = $_FILES['dataexcel'];
//echo json_encode(['file' => $excelfile['name']]);

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

                if($xls = SimpleXLS::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name'])){
                    //echo json_encode($xls->rows());

                    $count1 = 0;
                    $num_cols = 0;

                    foreach ($xls->rows() as $r){
                        $num_cols = count($r);
                        break;
                    }

                    foreach ($xls->rows() as $campo) {
                        if($count1 > 0){
                            $Estatus = trim($campo[0]);
                            //Se divide la cadena en un arreglo para saber si el primer y segundo caracter son dígitos
                            $DiasCredito = str_split(trim($campo[1]), 1);
                            if(strtolower(trim($campo[1])) == 'contado'){
                                $Credito = 0;
                            }
                            if(ctype_digit($DiasCredito[0])){
                                $Credito = $DiasCredito[0];
                            }
                            if(ctype_digit($DiasCredito[1])){
                                $Credito = substr(trim($campo[1]), 0, 2);
                            }
                            $CreditoInt = intval($Credito);
                            $TipoPersona = trim($campo[2]);
                            $RFC = trim($campo[3]);
                            $RazonSocial = trim($campo[4]);
                            $NombreComercial = trim($campo[5]);
                            $Giro = trim($campo[6]);
                            $Vendedor = trim($campo[7]);
                            $PrimerCorreo = trim($campo[8]);
                            $SegundoCorreo = trim($campo[9]);
                            $Telefono = trim($campo[10]);
                            $Movil = trim($campo[11]);
                            $Calle = trim($campo[12]);
                            $NombreInterior = trim($campo[13]);
                            $NombreExterior = trim($campo[14]);
                            $Colonia = trim($campo[15]);
                            $Localidad = trim($campo[16]);
                            $Estado = trim($campo[17]);
                            $Municipio = trim($campo[18]);
                            $CodigoPostal = trim($campo[19]);
                            $Referencia = trim($campo[20]);
                            $País = trim($campo[21]);

                            insertar(
                                $Estatus,
                                $CreditoInt,
                                $TipoPersona,
                                $RFC,
                                $RazonSocial,
                                $NombreComercial,
                                $Giro,
                                $Vendedor,
                                $PrimerCorreo,
                                $SegundoCorreo,
                                $Telefono,
                                $Movil,
                                $Calle,
                                $NombreInterior,
                                $NombreExterior,
                                $Colonia,
                                $Localidad,
                                $Estado,
                                $Municipio,
                                $CodigoPostal,
                                $Referencia,
                                $País
                            );
                        }
                        ++$count1;                    
                    }

                } else {
                    echo "error" . SimpleXLS::parseError();
                }
                

            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../../../lib/SimpleXLS/SimpleXLSX.php';

                if($xls = SimpleXLSX::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name'])){
                    //echo json_encode($xls->rows());

                    $count1 = 0;
                    $num_cols = 0;

                    foreach ($xls->rows() as $r){
                        $num_cols = count($r);
                        break;
                    }

                    foreach ($xls->rows() as $campo) {
                        if($count1 > 0){
                            $Estatus = trim($campo[0]);
                            //Se divide la cadena en un arreglo para saber si el primer y segundo caracter son dígitos
                            $DiasCredito = str_split(trim($campo[1]), 1);
                            if(strtolower(trim($campo[1])) == 'contado'){
                                $Credito = 0;
                            }
                            if(ctype_digit($DiasCredito[0])){
                                $Credito = $DiasCredito[0];
                            }
                            if(ctype_digit($DiasCredito[1])){
                                $Credito = substr(trim($campo[1]), 0, 2);
                            }
                            $CreditoInt = intval($Credito);
                            $TipoPersona = trim($campo[2]);
                            $RFC = trim($campo[3]);
                            $RazonSocial = trim($campo[4]);
                            $NombreComercial = trim($campo[5]);
                            $Giro = trim($campo[6]);
                            $Vendedor = trim($campo[7]);
                            $PrimerCorreo = trim($campo[8]);
                            $SegundoCorreo = trim($campo[9]);
                            $Telefono = trim($campo[10]);
                            $Movil = trim($campo[11]);
                            $Calle = trim($campo[12]);
                            $NombreInterior = trim($campo[13]);
                            $NombreExterior = trim($campo[14]);
                            $Colonia = trim($campo[15]);
                            $Localidad = trim($campo[16]);
                            $Estado = trim($campo[17]);
                            $Municipio = trim($campo[18]);
                            $CodigoPostal = trim($campo[19]);
                            $Referencia = trim($campo[20]);
                            $País = trim($campo[21]);

                            insertar(
                                $Estatus,
                                $CreditoInt,
                                $TipoPersona,
                                $RFC,
                                $RazonSocial,
                                $NombreComercial,
                                $Giro,
                                $Vendedor,
                                $PrimerCorreo,
                                $SegundoCorreo,
                                $Telefono,
                                $Movil,
                                $Calle,
                                $NombreInterior,
                                $NombreExterior,
                                $Colonia,
                                $Localidad,
                                $Estado,
                                $Municipio,
                                $CodigoPostal,
                                $Referencia,
                                $País
                            );
                            echo "count: ".$count1." sl";
                        }
                        ++$count1;                    
                    }

                } else {
                    echo "error" . SimpleXLSX::parseError();
                }

            }
        }else {
            echo 'Formato no aceptado';
        }
    } else {
        echo json_encode(['status' => 'fail']);
    }
} catch (\Throwable $th) {
    echo $th;
}
function insertar($Estatus, $Credito, $TipoPersona, $RFC, $RazonSocial, $NombreComercial, $Giro, $Vendedor, $PrimerCorreo, $SegundoCorreo, $Telefono, $Movil, $Calle, $NombreInterior, $NombreExterior, $Colonia, $Localidad, $Estado, $Municipio, $CodigoPostal, $Referencia, $País){
    $con = new conectar();
    $db = $con->getDb();
    $PKUsuario = $_SESSION["PKUsuario"];
    echo "inserta\n";
    try {
        $query = sprintf('call spi_Proveedores_Excel(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $Estatus, $Credito, $TipoPersona, $RFC, $RazonSocial, $NombreComercial, $Giro, $Vendedor, $PrimerCorreo, $SegundoCorreo, $Telefono, $Movil, $Calle, $NombreInterior, $NombreExterior, $Colonia, $Localidad, $Estado, $Municipio, $CodigoPostal, $Referencia, $País));

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

unlink($subir_archivo);