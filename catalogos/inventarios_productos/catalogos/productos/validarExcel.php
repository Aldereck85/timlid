<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
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

$tipo = $_POST['tipo'];
$excelfile = $_FILES['dataexcel'];
//echo json_encode(['tipo' => $PKSucursal, 'file' => $excelfile['name']]);

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

                    $formatoIncorrecto = 0;
                    $c = 0;
                    foreach ($xls->rows() as $campo) {
                        if ($c == 0 && trim($campo[0]) == '' && trim($campo[1]) == '' && trim($campo[2]) == '' && trim($campo[3]) == '' && trim($campo[4]) == '' && trim($campo[5]) == '' && trim($campo[6]) == '') {
                            echo "<p style='font-size: 15px'>Formato incorrecto</p>";
                            $formatoIncorrecto = 1;
                        }
                        ++$c;
                    }

                    if($formatoIncorrecto == 0){

                        $count1 = 1;
                        $contadorClaves = 0;
                        $arrayFilasClave =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[0]) == ''){
                                $arrayFilasClave[$contadorClaves] = $count1;
                                ++$contadorClaves;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasClave)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Productos de las filas: ";
                            foreach($arrayFilasClave as $array){
                                if($i == count($arrayFilasClave)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen clave</p> <br><br>";
                        }

                        $count2 = 0;
                        $contadorNombres = 0;
                        $arrayFilasNombre = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[1]) == ''){
                                $arrayFilasNombre[$contadorNombres] = $count2;
                                ++$contadorNombres;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasNombre)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                            foreach($arrayFilasNombre as $array){
                                if($i == count($arrayFilasNombre)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen nombre</p> <br><br>";
                        }

                        $count3 = 0;
                        $count4 = 0;
                        $arrayClaves = array();

                        foreach ($xls->rows() as $campo) {
                            if($count3 > 1 && trim($campo[0]) != ''){
                                $arrayClaves[$count4] = trim($campo[0]);
                            }
                            ++$count3;
                        }
                        
                        $arrayClavesDuplicadas = array();

                        if(count(array_unique($arrayClaves)) != count($arrayClaves)){

                            $contadorClavesDuplicadas = 0;
                            
                            foreach(array_count_values($arrayClaves) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayClavesDuplicadas[$contadorClavesDuplicadas] = $arrayCount;
                                }
                            }
                            
                        }

                        if(!empty($arrayClavesDuplicadas)){
                            echo "<p style='font-size: 15px'>Las claves: <br>";
                            for ($i=1; $i <= count($arrayClavesDuplicadas) ; $i++) { 
                                if($i == count($arrayClavesDuplicadas)){
                                    echo $arrayClavesDuplicadas[$i - 1];
                                }else {
                                    echo $arrayClavesDuplicadas[$i - 1] . ", ";
                                }
                            }
                            echo " están duplicadas</p> <br><br>";
                        }

                        $count5 = 1;
                        $index = 0;
                        $contadorClavesBD = 0;
                        $arrayFilasClavesBD =  array();
                        $arrayBD = array();
                        $datos = claves();
                        foreach($datos as $claves){
                            $arrayBD[$index] = $claves['claves'];
                            ++$index;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && in_array(trim($campo[0]), $arrayBD)){
                                $arrayFilasClavesBD[$contadorClavesBD] = $count5;
                                ++$contadorClavesBD;
                            }
                            ++$count5;
                        }
                        
                        if(!empty($arrayFilasClavesBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las claves de las filas: <br>";
                            foreach($arrayFilasClavesBD as $array){
                                if($i == count($arrayFilasClavesBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                        if($tipo == 3){

                            $num_cols = 0;

                            foreach ($xls->rows() as $r){
                                $num_cols = count($r);
                                break;
                            }
                            
                            if($num_cols < 7){
                                echo "<p style='font-size: 15px'>Se requieren los costos de fabricación y el tipo de moneda</p><br>";
                            }

                            if($num_cols == 7){
                                echo "<p style='font-size: 15px'>Se requiere el tipo de moneda</p><br>";
                            }

                            if ($num_cols > 6) {
                                $count6 = 1;
                                $contadorCostosFabricacion = 0;
                                $arrayFilasCostosFabricacion =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count6 > 1 && trim($campo[6]) == ''){
                                        $arrayFilasCostosFabricacion[$contadorCostosFabricacion] = $count6;
                                        ++$contadorCostosFabricacion;
                                    }
                                    ++$count6;
                                }

                                if(!empty($arrayFilasCostosFabricacion)){
                                    $i = 1;
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasCostosFabricacion as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "no tienen costo de fabricación</p> <br><br>";
                                }

                                $count7 = 1;
                                $contadorCostosFabricacionInv = 0;
                                $arrayFilasCostosFabricacionInv =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count7 > 1 && !is_numeric(trim($campo[6]))){
                                        $arrayFilasCostosFabricacionInv[$contadorCostosFabricacionInv] = $count7;
                                        ++$contadorCostosFabricacionInv;
                                    }
                                    ++$count7;
                                }

                                if(!empty($arrayFilasCostosFabricacionInv)){
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasCostosFabricacionInv as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "tienen costo de fabricación inválido</p> <br><br>";
                                }
                            }

                            if ($num_cols > 7) {
                                $count8 = 1;
                                $contadorMoneda = 0;
                                $arrayFilasMoneda =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count8 > 1 && trim($campo[7]) == ''){
                                        $arrayFilasMoneda[$contadorMoneda] = $count8;
                                        ++$contadorMoneda;
                                    }
                                    ++$count8;
                                }

                                if(!empty($arrayFilasMoneda)){
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasMoneda as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "no tienen el tipo de moneda</p> <br><br>";
                                }

                                $count9 = 1;
                                $contadorMonedaInv = 0;
                                $arrayFilasMonedaInv =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count9 > 1 && trim($campo[7]) != 'MXN' && trim($campo[7]) != 'mxn' && trim($campo[7]) != 'USD' && trim($campo[7]) != 'usd' && trim($campo[7]) != 'EUR' && trim($campo[7]) != 'eur'){
                                        $arrayFilasMonedaInv[$contadorMonedaInv] = $count9;
                                        ++$contadorMonedaInv;
                                    }
                                    ++$count9;
                                }

                                if(!empty($arrayFilasMonedaInv)){
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasMonedaInv as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "tienen un tipo de moneda inválida</p> <br><br>";
                                }
                            }

                        }
                    }

                } else {
                    echo "error" . SimpleXLS::parseError();
                }
                

            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../../../lib/SimpleXLS/SimpleXLSX.php';

                if($xls = SimpleXLSX::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name'])){
                    //echo json_encode($xls->rows());

                    $formatoIncorrecto = 0;
                    $c = 0;
                    foreach ($xls->rows() as $campo) {
                        if ($c == 0 && trim($campo[0]) == '' && trim($campo[1]) == '' && trim($campo[2]) == '' && trim($campo[3]) == '' && trim($campo[4]) == '' && trim($campo[5]) == '' && trim($campo[6]) == '') {
                            echo "<p style='font-size: 15px'>Formato incorrecto</p>";
                            $formatoIncorrecto = 1;
                        }
                        ++$c;
                    }

                    if($formatoIncorrecto == 0){

                        $count1 = 1;
                        $contadorClaves = 0;
                        $arrayFilasClave =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[0]) == ''){
                                $arrayFilasClave[$contadorClaves] = $count1;
                                ++$contadorClaves;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasClave)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Productos de las filas: ";
                            foreach($arrayFilasClave as $array){
                                if($i == count($arrayFilasClave)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen clave</p> <br><br>";
                        }

                        $count2 = 0;
                        $contadorNombres = 0;
                        $arrayFilasNombre = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[1]) == ''){
                                $arrayFilasNombre[$contadorNombres] = $count2;
                                ++$contadorNombres;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasNombre)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                            foreach($arrayFilasNombre as $array){
                                if($i == count($arrayFilasNombre)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen nombre</p> <br><br>";
                        }

                        $count3 = 0;
                        $count4 = 0;
                        $arrayClaves = array();

                        foreach ($xls->rows() as $campo) {
                            if($count3 > 1 && trim($campo[0]) != ''){
                                $arrayClaves[$count4] = trim($campo[0]);
                            }
                            ++$count3;
                        }
                        
                        $arrayClavesDuplicadas = array();

                        if(count(array_unique($arrayClaves)) != count($arrayClaves)){

                            $contadorClavesDuplicadas = 0;
                            
                            foreach(array_count_values($arrayClaves) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayClavesDuplicadas[$contadorClavesDuplicadas] = $arrayCount;
                                }
                            }
                            
                        }

                        if(!empty($arrayClavesDuplicadas)){
                            echo "<p style='font-size: 15px'>Las claves: <br>";
                            for ($i=1; $i <= count($arrayClavesDuplicadas) ; $i++) { 
                                if($i == count($arrayClavesDuplicadas)){
                                    echo $arrayClavesDuplicadas[$i - 1];
                                }else {
                                    echo $arrayClavesDuplicadas[$i - 1] . ", ";
                                }
                            }
                            echo " están duplicadas</p> <br><br>";
                        }

                        $count5 = 1;
                        $index = 0;
                        $contadorClavesBD = 0;
                        $arrayFilasClavesBD =  array();
                        $arrayBD = array();
                        $datos = claves();
                        foreach($datos as $claves){
                            $arrayBD[$index] = $claves['claves'];
                            ++$index;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && in_array(trim($campo[0]), $arrayBD)){
                                $arrayFilasClavesBD[$contadorClavesBD] = $count5;
                                ++$contadorClavesBD;
                            }
                            ++$count5;
                        }
                        
                        if(!empty($arrayFilasClavesBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las claves de las filas: <br>";
                            foreach($arrayFilasClavesBD as $array){
                                if($i == count($arrayFilasClavesBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                        if($tipo == 3){

                            $num_cols = 0;

                            foreach ($xls->rows() as $r){
                                $num_cols = count($r);
                                break;
                            }
                            
                            if($num_cols < 7){
                                echo "<p style='font-size: 15px'>Se requieren los costos de fabricación y el tipo de moneda</p><br>";
                            }

                            if($num_cols == 7){
                                echo "<p style='font-size: 15px'>Se requiere el tipo de moneda</p><br>";
                            }

                            if ($num_cols > 6) {
                                $count6 = 1;
                                $contadorCostosFabricacion = 0;
                                $arrayFilasCostosFabricacion =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count6 > 1 && trim($campo[6]) == ''){
                                        $arrayFilasCostosFabricacion[$contadorCostosFabricacion] = $count6;
                                        ++$contadorCostosFabricacion;
                                    }
                                    ++$count6;
                                }

                                if(!empty($arrayFilasCostosFabricacion)){
                                    $i = 1;
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasCostosFabricacion as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "no tienen costo de fabricación</p> <br><br>";
                                }

                                $count7 = 1;
                                $contadorCostosFabricacionInv = 0;
                                $arrayFilasCostosFabricacionInv =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count7 > 1 && !is_numeric(trim($campo[6]))){
                                        $arrayFilasCostosFabricacionInv[$contadorCostosFabricacionInv] = $count7;
                                        ++$contadorCostosFabricacionInv;
                                    }
                                    ++$count7;
                                }

                                if(!empty($arrayFilasCostosFabricacionInv)){
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasCostosFabricacionInv as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "tienen costo de fabricación inválido</p> <br><br>";
                                }
                            }

                            if ($num_cols > 7) {
                                $count8 = 1;
                                $contadorMoneda = 0;
                                $arrayFilasMoneda =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count8 > 1 && trim($campo[7]) == ''){
                                        $arrayFilasMoneda[$contadorMoneda] = $count8;
                                        ++$contadorMoneda;
                                    }
                                    ++$count8;
                                }

                                if(!empty($arrayFilasMoneda)){
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasMoneda as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "no tienen el tipo de moneda</p> <br><br>";
                                }

                                $count9 = 1;
                                $contadorMonedaInv = 0;
                                $arrayFilasMonedaInv =  array();
                                
                                foreach ($xls->rows() as $campo) {
                                    if($count9 > 1 && trim($campo[7]) != 'MXN' && trim($campo[7]) != 'mxn' && trim($campo[7]) != 'USD' && trim($campo[7]) != 'usd' && trim($campo[7]) != 'EUR' && trim($campo[7]) != 'eur'){
                                        $arrayFilasMonedaInv[$contadorMonedaInv] = $count9;
                                        ++$contadorMonedaInv;
                                    }
                                    ++$count9;
                                }

                                if(!empty($arrayFilasMonedaInv)){
                                    echo "<p style='font-size: 15px'>Productos de las filas: <br>";
                                    foreach($arrayFilasMonedaInv as $array){
                                        echo $array . "<br>";
                                    }
                                    echo "tienen un tipo de moneda inválida</p> <br><br>";
                                }
                            }
                        }

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
function claves(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('call spc_Claves_Productos_Empresa(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

unlink($subir_archivo);
