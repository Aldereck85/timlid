<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
class conectar
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../include/db-conn.php";
        return $conn;
    }
}
$con = new conectar();
$db = $con->getDb();

$excelfile = $_FILES['dataexcel'];
//echo json_encode('file' => $excelfile['name']]);

$directorio = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';
$subir_archivo = $directorio . basename($excelfile['name']);
//echo json_encode(['sdsds' => $excelfile['tmp_name']]);
try {
    if (move_uploaded_file($excelfile['tmp_name'], $subir_archivo)) {

        $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

        if (in_array($excelfile['type'], $allowedFileType)) {

            $extension = explode('.', $excelfile['name']);

            if ($excelfile['type'] == "application/vnd.ms-excel" || $extension[1] == "xls") {
                include '../../lib/SimpleXLS/SimpleXLS.php';

                if($xls = SimpleXLS::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name'])){
                    //echo json_encode($xls->rows());

                    $num_cols = 0;

                    foreach ($xls->rows() as $r){
                        $num_cols = count($r);
                        break;
                    }
                    
                    if($num_cols == 0){
                        echo "<p style='font-size: 15px'>Formato incorrecto</p>";
                    }
                    
                    if($num_cols < 18){
                        echo "<p style='font-size: 15px'>Formato incompleto</p>";
                    }

                    if($num_cols > 0){

                        $count1 = 1;
                        $contadorNombre = 0;
                        $arrayFilasNombre =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[0]) == ''){
                                $arrayFilasNombre[$contadorNombre] = $count1;
                                ++$contadorNombre;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasNombre)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasNombre as $array){
                                if($i == count($arrayFilasNombre)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen nombre</p> <br>";
                        }

                        $count23 = 1;
                        $contadorNombreInvalido = 0;
                        $arrayFilasNombreInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', trim($campo[0])) || strlen(trim($campo[0])) > 55) && trim($campo[0]) != ''){
                                $arrayFilasNombreInvalido[$contadorNombreInvalido] = $count23;
                                ++$contadorNombreInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasNombreInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los nombres de las filas: ";
                            foreach($arrayFilasNombreInvalido as $array){
                                if($i == count($arrayFilasNombreInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 1){

                        $count2 = 1;
                        $contadorApPaterno = 0;
                        $arrayFilasApPaterno = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[1]) == ''){
                                $arrayFilasApPaterno[$contadorApPaterno] = $count2;
                                ++$contadorApPaterno;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasApPaterno)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasApPaterno as $array){
                                if($i == count($arrayFilasApPaterno)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen apellido paterno</p> <br>";
                        }
                        
                        $count23 = 1;
                        $contadorApPaternoInvalido = 0;
                        $arrayFilasApPaternoInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', trim($campo[1])) || strlen(trim($campo[0])) > 25) && trim($campo[1]) != ''){
                                $arrayFilasApPaternoInvalido[$contadorApPaternoInvalido] = $count23;
                                ++$contadorApPaternoInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasApPaternoInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los apellidos paternos de las filas: ";
                            foreach($arrayFilasApPaternoInvalido as $array){
                                if($i == count($arrayFilasApPaternoInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 2){

                        $count23 = 1;
                        $contadorApMaternoInvalido = 0;
                        $arrayFilasApMaternoInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', trim($campo[2])) || strlen(trim($campo[2])) > 25) && trim($campo[2]) != ''){
                                $arrayFilasApMaternoInvalido[$contadorApMaternoInvalido] = $count23;
                                ++$contadorApMaternoInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasApMaternoInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los apellidos maternos de las filas: ";
                            foreach($arrayFilasApMaternoInvalido as $array){
                                if($i == count($arrayFilasApMaternoInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 3){

                        $count5 = 1;
                        $contadorEC = 0;
                        $arrayFilasEC = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && trim($campo[3]) != '' && trim($campo[3]) != 'Soltero' && trim($campo[3]) != 'soltero' && trim($campo[3]) != 'Casado' && trim($campo[3]) != 'casado' && trim($campo[3]) != 'Divorciado' && trim($campo[3]) != 'divorciado' && trim($campo[3]) != 'Viudo' && trim($campo[3]) != 'viudo' && trim($campo[3]) != 'Concubinato' && trim($campo[3]) != 'concubinato' && trim($campo[3]) != ''){
                                $arrayFilasEC[$contadorEC] = $count5;
                                ++$contadorEC;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasEC)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasEC as $array){
                                if($i == count($arrayFilasEC)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un estado civil inválido</p> <br>";
                        }

                    }

                    if($num_cols > 4){

                        $count4 = 1;
                        $contadorGenero = 0;
                        $arrayFilasGenero = array();

                        foreach ($xls->rows() as $campo) {
                            if($count4 > 1 && trim($campo[4]) == ''){
                                $arrayFilasGenero[$contadorGenero] = $count4;
                                ++$contadorGenero;
                            }
                            ++$count4;
                        }

                        if(!empty($arrayFilasGenero)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasGenero as $array){
                                if($i == count($arrayFilasGenero)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen género</p> <br>";
                        }

                        $count5 = 1;
                        $contadorGeneroVendedorInv = 0;
                        $arrayFilasGeneroVendedorInv = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && trim($campo[4]) != '' && trim($campo[4]) != 'Femenino' && trim($campo[4]) != 'femenino' && trim($campo[4]) != 'Masculino' && trim($campo[4]) != 'masculino' && trim($campo[4]) != ''){
                                $arrayFilasGeneroVendedorInv[$contadorGeneroVendedorInv] = $count5;
                                ++$contadorGeneroVendedorInv;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasGeneroVendedorInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasGeneroVendedorInv as $array){
                                if($i == count($arrayFilasGeneroVendedorInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un género inválido</p> <br>";
                        }

                    }

                    if($num_cols > 5){

                        $count2 = 1;
                        $contadorRol = 0;
                        $arrayFilasRol = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[5]) == ''){
                                $arrayFilasRol[$contadorRol] = $count2;
                                ++$contadorRol;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasRol)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasRol as $array){
                                if($i == count($arrayFilasRol)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen rol inicial</p> <br>";
                        }

                    }

                    if($num_cols > 6){

                        $count1 = 1;
                        $contadorCURP = 0;
                        $arrayFilasCURP =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && !preg_match('/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/', trim($campo[6])) && trim($campo[6]) != ''){
                                $arrayFilasCURP[$contadorCURP] = $count1;
                                ++$contadorCURP;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasCURP)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasCURP as $array){
                                if($i == count($arrayFilasCURP)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen CURP inválida</p> <br>";
                        }

                    }

                    if($num_cols > 7){

                        $count22 = 1;
                        $contadorRFCInvalido = 0;
                        $arrayFilasRFCInvalido = array();
                        $aceptarGenerico = true;

                        foreach ($xls->rows() as $campo) {
                            if($count22 > 1 && !preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[7]))) && trim($campo[7]) != ''){//Coincide con el formato general del regex?
                                $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                ++$contadorRFCInvalido;
                            }elseif ($count22 > 1 && preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[7]))) && trim($campo[7]) != '') {
                                $rfc = strtoupper(trim($campo[7]));
                                //Separar el dígito verificador del resto del RFC
                                $arrayRfc = str_split($rfc, 1);
                                $digitoVerificador = array_pop($arrayRfc);
                                $len = count($arrayRfc);
                                //Obtener el digito esperado
                                $diccionario = '0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ';
                                $arrayDiccionario = str_split($diccionario, 1);
                                $indice = $len + 1;
                                $suma = 0;
                                $digitoEsperado = 0;

                                ($len == 12) ? $suma = 0 : $suma = 481; //Ajuste para persona moral

                                for($i = 0; $i < $len; $i++){
                                    $suma += array_search($arrayRfc[$i], $arrayDiccionario) * ($indice - $i);
                                }
                                $digitoEsperado = 11 - ($suma % 11);
                                if($digitoEsperado == 11) $digitoEsperado = 0;
                                elseif ($digitoEsperado == 10) $digitoEsperado = 'A';

                                //El dígito verificador coincide con el esperado?
                                // o es un RFC Genérico (ventas a público general)?

                                if(
                                    $digitoVerificador != $digitoEsperado &&
                                    (!$aceptarGenerico || $arrayRfc + $digitoVerificador != 'XAXX010101000')
                                ){
                                    $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                    ++$contadorRFCInvalido;
                                }
                                else if(
                                    !$aceptarGenerico &&
                                    $arrayRfc + $digitoVerificador == 'XEXX010101000'
                                ){
                                    $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                    ++$contadorRFCInvalido;
                                }
                            }
                            ++$count22;
                        }

                        if(!empty($arrayFilasRFCInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los RFCs de las filas: ";
                            foreach($arrayFilasRFCInvalido as $array){
                                if($i == count($arrayFilasRFCInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 8){

                        $count5 = 1;
                        $contadorFechaNacimiento = 0;
                        $arrayFilasFechaNacimiento = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && date_parse(trim($campo[8]))["year"] == false && trim($campo[8]) != ''){
                                $arrayFilasFechaNacimiento[$contadorFechaNacimiento] = $count5;
                                ++$contadorFechaNacimiento;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasFechaNacimiento)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las fechas de nacimiento de las filas: <br>";
                            foreach($arrayFilasFechaNacimiento as $array){
                                if($i == count($arrayFilasFechaNacimiento)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 9){

                        $count1 = 1;
                        $contadorTelefono = 0;
                        $arrayFilasTelefono =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[9]) != '' && (!ctype_digit(trim($campo[9])) || strlen(trim($campo[9])) != 10)){
                                $arrayFilasTelefono[$contadorTelefono] = $count1;
                                ++$contadorTelefono;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTelefono)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los teléfonos de las filas: ";
                            foreach($arrayFilasTelefono as $array){
                                if($i == count($arrayFilasTelefono)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 10){

                        $count21 = 1;
                        $contadorCorreo = 0;
                        $arrayFilasCorreo = array();

                        foreach ($xls->rows() as $campo) {
                            if($count21 > 1 && !preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', trim($campo[10])) && trim($campo[10]) != ''){
                                $arrayFilasCorreo[$contadorCorreo] = $count21;
                                ++$contadorCorreo;
                            }
                            ++$count21;
                        }

                        if(!empty($arrayFilasCorreo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los correos de las filas: ";
                            foreach($arrayFilasCorreo as $array){
                                if($i == count($arrayFilasCorreo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 15){
                        
                        $count23 = 1;
                        $contadorCPInvalido = 0;
                        $arrayFilasCPInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && !preg_match('/(^([0-9]{5})|^)$/', trim($campo[15])) && trim($campo[15]) != ''){
                                $arrayFilasCPInvalido[$contadorCPInvalido] = $count23;
                                ++$contadorCPInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasCPInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los códigos postales de las filas: ";
                            foreach($arrayFilasCPInvalido as $array){
                                if($i == count($arrayFilasCPInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 17){

                        $count20 = 1;
                        $indexEstado = 0;
                        $contadorEstadoBD = 0;
                        $arrayFilasEstadoBD =  array();
                        $arrayEstadoBD = array();
                        $datosEstado = estados();
                        foreach($datosEstado as $estados){
                            $arrayEstadoBD[$indexEstado] = trim($estados['Estado']);
                            ++$indexEstado;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[17])), array_map('strtolower', $arrayEstadoBD)) && trim($campo[17]) != ''){
                                $arrayFilasEstadoBD[$contadorEstadoBD] = $count20;
                                ++$contadorEstadoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasEstadoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los estados de las filas: <br>";
                            foreach($arrayFilasEstadoBD as $array){
                                if($i == count($arrayFilasEstadoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }

                    }

                    if($num_cols > 18){

                        $count1 = 1;
                        $contadorFechaIngreso = 0;
                        $arrayFilasFechaIngreso =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[18]) == '' && (trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasFechaIngreso[$contadorFechaIngreso] = $count1;
                                ++$contadorFechaIngreso;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasFechaIngreso)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasFechaIngreso as $array){
                                if($i == count($arrayFilasFechaIngreso)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen fecha de ingreso</p> <br>";
                        }

                        $count5 = 1;
                        $contadorFechaIngresoInv = 0;
                        $arrayFilasFechaIngresoInv = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && date_parse(trim($campo[18]))["year"] == false && trim($campo[18]) !=''){
                                $arrayFilasFechaIngresoInv[$contadorFechaIngresoInv] = $count5;
                                ++$contadorFechaIngresoInv;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasFechaIngresoInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las fechas de ingreso de las filas: <br>";
                            foreach($arrayFilasFechaIngresoInv as $array){
                                if($i == count($arrayFilasFechaIngresoInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 19){

                        $count1 = 1;
                        $contadorTipoContrato = 0;
                        $arrayFilasTipoContrato =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[19]) == '' && (trim($campo[18]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasTipoContrato[$contadorTipoContrato] = $count1;
                                ++$contadorTipoContrato;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTipoContrato)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasTipoContrato as $array){
                                if($i == count($arrayFilasTipoContrato)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen tipo de contrato</p> <br>";
                        }

                    }

                    if($num_cols > 20){

                        $count1 = 1;
                        $contadorPuesto = 0;
                        $arrayFilasPuesto =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[20]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasPuesto[$contadorPuesto] = $count1;
                                ++$contadorPuesto;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasPuesto)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasPuesto as $array){
                                if($i == count($arrayFilasPuesto)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen puesto</p> <br>";
                        }

                    }

                    if($num_cols > 21){

                        $count1 = 1;
                        $contadorRiesgo = 0;
                        $arrayFilasRiesgo =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[21]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasRiesgo[$contadorRiesgo] = $count1;
                                ++$contadorRiesgo;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasRiesgo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasRiesgo as $array){
                                if($i == count($arrayFilasRiesgo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen riesgo del puesto</p> <br>";
                        }

                    }

                    if($num_cols > 22){

                        $count1 = 1;
                        $contadorRégimen = 0;
                        $arrayFilasRégimen =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[22]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasRégimen[$contadorRégimen] = $count1;
                                ++$contadorRégimen;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasRégimen)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasRégimen as $array){
                                if($i == count($arrayFilasRégimen)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen régimen</p> <br>";
                        }

                    }

                    if($num_cols > 23){

                        $count1 = 1;
                        $contadorTurno = 0;
                        $arrayFilasTurno =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[23]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasTurno[$contadorTurno] = $count1;
                                ++$contadorTurno;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTurno)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasTurno as $array){
                                if($i == count($arrayFilasTurno)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen turno</p> <br>";
                        }

                        $count20 = 1;
                        $indexTurno = 0;
                        $contadorTurnoBD = 0;
                        $arrayFilasTurnoBD =  array();
                        $arrayTurnoBD = array();
                        $datosTurno = turnos();
                        foreach($datosTurno as $turnos){
                            $arrayTurnoBD[$indexTurno] = $turnos['Turno'];
                            ++$indexTurno;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[23])), array_map('strtolower', $arrayTurnoBD)) && trim($campo[23]) != ''){
                                $arrayFilasTurnoBD[$contadorTurnoBD] = $count20;
                                ++$contadorTurnoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasTurnoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los turnos de las filas: <br>";
                            foreach($arrayFilasTurnoBD as $array){
                                if($i == count($arrayFilasTurnoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }


                    }

                    if($num_cols > 24){

                        $count1 = 1;
                        $contadorSucursal = 0;
                        $arrayFilasSucursal =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[24]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasSucursal[$contadorSucursal] = $count1;
                                ++$contadorSucursal;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasSucursal)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasSucursal as $array){
                                if($i == count($arrayFilasSucursal)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen sucursal</p> <br>";
                        }

                        $count20 = 1;
                        $indexSucursal = 0;
                        $contadorSucursalBD = 0;
                        $arrayFilasSucursalBD =  array();
                        $arraySucursalBD = array();
                        $datosSucursal = sucursales();
                        foreach($datosSucursal as $sucursales){
                            $arraySucursalBD[$indexSucursal] = $sucursales['sucursal'];
                            ++$indexSucursal;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[24])), array_map('strtolower', $arraySucursalBD)) && trim($campo[24]) != ''){
                                $arrayFilasSucursalBD[$contadorSucursalBD] = $count20;
                                ++$contadorSucursalBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasSucursalBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las sucursales de las filas: <br>";
                            foreach($arrayFilasSucursalBD as $array){
                                if($i == count($arrayFilasSucursalBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }


                    }

                    if($num_cols > 25){

                        $count1 = 1;
                        $contadorSueldo = 0;
                        $arrayFilasSueldo =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[25]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[26]) != '')){
                                $arrayFilasSueldo[$contadorSueldo] = $count1;
                                ++$contadorSueldo;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasSueldo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasSueldo as $array){
                                if($i == count($arrayFilasSueldo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen sueldo</p> <br>";
                        }

                        $count20 = 1;
                        $contadorSueldoBD = 0;
                        $arrayFilasSueldoBD =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !preg_match('/^\d+(\.\d{1,2})?$/', trim($campo[25])) && trim($campo[25]) != ''){
                                $arrayFilasSueldoBD[$contadorSueldoBD] = $count20;
                                ++$contadorSueldoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasSueldoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los sueldos de las filas: <br>";
                            foreach($arrayFilasSueldoBD as $array){
                                if($i == count($arrayFilasSueldoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }


                    }

                    if($num_cols > 26){

                        $count1 = 1;
                        $contadorPeriodoPago = 0;
                        $arrayFilasPeriodoPago =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[26]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '')){
                                $arrayFilasPeriodoPago[$contadorPeriodoPago] = $count1;
                                ++$contadorPeriodoPago;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasPeriodoPago)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasPeriodoPago as $array){
                                if($i == count($arrayFilasPeriodoPago)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen período de pago</p> <br>";
                        }

                        $count20 = 1;
                        $contadorSueldoInv = 0;
                        $arrayFilasSueldoInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && trim($campo[26]) != 'Semanal' && trim($campo[26]) != 'semanal' && trim($campo[26]) != 'Catorcenal' && trim($campo[26]) != 'catorcenal' && trim($campo[26]) != 'Quincenal' && trim($campo[26]) != 'quincenal' && trim($campo[26]) != 'Mensual' && trim($campo[26]) != 'mensual' && trim($campo[26]) != ''){
                                $arrayFilasSueldoInv[$contadorSueldoInv] = $count20;
                                ++$contadorSueldoInv;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasSueldoInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los períodos de pago de las filas: <br>";
                            foreach($arrayFilasSueldoInv as $array){
                                if($i == count($arrayFilasSueldoInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }


                    }

                    if($num_cols > 27){

                        $count20 = 1;
                        $contadorInfonavit = 0;
                        $arrayFilasInfonavit =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !preg_match('/^\d+(\.\d{1,2})?$/', trim($campo[27])) && trim($campo[27]) != ''){
                                $arrayFilasInfonavit[$contadorInfonavit] = $count20;
                                ++$contadorInfonavit;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasInfonavit)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>El monto INFONAVIT de las filas: <br>";
                            foreach($arrayFilasInfonavit as $array){
                                if($i == count($arrayFilasInfonavit)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " es inválido</p> <br>";
                        }


                    }

                    if($num_cols > 28){

                        $count20 = 1;
                        $contadorDeudaInterna = 0;
                        $arrayFilasDeudaInterna =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !preg_match('/^\d+(\.\d{1,2})?$/', trim($campo[28])) && trim($campo[28]) != ''){
                                $arrayFilasDeudaInterna[$contadorDeudaInterna] = $count20;
                                ++$contadorDeudaInterna;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasDeudaInterna)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>El monto de la deuda interna de las filas: <br>";
                            foreach($arrayFilasDeudaInterna as $array){
                                if($i == count($arrayFilasDeudaInterna)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " es inválido</p> <br>";
                        }


                    }

                    if($num_cols > 29){

                        $count20 = 1;
                        $contadorNSS = 0;
                        $arrayFilasNSS =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && trim($campo[29]) == '' && trim($campo[30]) != ''){
                                $arrayFilasNSS[$contadorNSS] = $count20;
                                ++$contadorNSS;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasNSS)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasNSS as $array){
                                if($i == count($arrayFilasNSS)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen número de seguro social</p> <br>";
                        }

                        $count1 = 1;
                        $contadorNSSInv = 0;
                        $arrayFilasNSSInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && !preg_match('/^[0-9]{11}$/', trim($campo[29])) && trim($campo[29]) != ''){
                                $arrayFilasNSSInv[$contadorNSSInv] = $count1;
                                ++$contadorNSSInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNSSInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de seguro social de las filas: <br>";
                            foreach($arrayFilasNSSInv as $array){
                                if($i == count($arrayFilasNSSInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 30){

                        $count20 = 1;
                        $contadorTipoSangre = 0;
                        $arrayFilasTipoSangre =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && trim($campo[30]) == '' && trim($campo[29]) != ''){
                                $arrayFilasTipoSangre[$contadorTipoSangre] = $count20;
                                ++$contadorTipoSangre;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasTipoSangre)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasTipoSangre as $array){
                                if($i == count($arrayFilasTipoSangre)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen tipo de sangre</p> <br>";
                        }

                        $count1 = 1;
                        $contadorTipoSangreInv = 0;
                        $arrayFilasTipoSangreInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[30]) != '' && trim($campo[30]) != 'A+' && trim($campo[30]) != 'A-' && trim($campo[30]) != 'B+' && trim($campo[30]) != 'B-' && trim($campo[30]) != 'AB+' && trim($campo[30]) != 'AB-' && trim($campo[30]) != 'O+' && trim($campo[30]) != 'O-'){
                                $arrayFilasTipoSangreInv[$contadorTipoSangreInv] = $count1;
                                ++$contadorTipoSangreInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasTipoSangreInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los tipos de sangre de las filas: <br>";
                            foreach($arrayFilasTipoSangreInv as $array){
                                if($i == count($arrayFilasTipoSangreInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 31){

                        $count1 = 1;
                        $contadorContactoEmergencia = 0;
                        $arrayFilasContactoEmergencia =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[31]) != '' && (!preg_match('/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/', trim($campo[31])) || strlen(trim($campo[31])) > 25)){
                                $arrayFilasContactoEmergencia[$contadorContactoEmergencia] = $count1;
                                ++$contadorContactoEmergencia;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasContactoEmergencia)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los contactos de emergencia de las filas: <br>";
                            foreach($arrayFilasContactoEmergencia as $array){
                                if($i == count($arrayFilasContactoEmergencia)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 32){

                        $count1 = 1;
                        $contadorNumeroEmergencia = 0;
                        $arrayFilasNumeroEmergencia =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[32]) != '' && !preg_match('/^[0-9]{10}$/', trim($campo[32]))){
                                $arrayFilasNumeroEmergencia[$contadorNumeroEmergencia] = $count1;
                                ++$contadorNumeroEmergencia;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNumeroEmergencia)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de emergencia de las filas: <br>";
                            foreach($arrayFilasNumeroEmergencia as $array){
                                if($i == count($arrayFilasNumeroEmergencia)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 33){

                        $count1 = 1;
                        $contadorAlergias = 0;
                        $arrayFilasAlergias =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[33]) != '' && strlen(trim($campo[33])) > 100){
                                $arrayFilasAlergias[$contadorAlergias] = $count1;
                                ++$contadorAlergias;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasAlergias)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las descripciones de alergias de las filas: <br>";
                            foreach($arrayFilasAlergias as $array){
                                if($i == count($arrayFilasAlergias)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son muy largas</p> <br>";
                        }

                    }

                    if($num_cols > 34){

                        $count1 = 1;
                        $contadorNotas = 0;
                        $arrayFilasNotas =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[34]) != '' && strlen(trim($campo[34])) > 70){
                                $arrayFilasNotas[$contadorNotas] = $count1;
                                ++$contadorNotas;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNotas)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las notas de las filas: <br>";
                            foreach($arrayFilasNotas as $array){
                                if($i == count($arrayFilasNotas)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son muy largas</p> <br>";
                        }

                    }
                    
                    if($num_cols > 35){

                        $count1 = 1;
                        $contadorDonador = 0;
                        $arrayFilasDonador =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[35]) != '' && trim($campo[35]) != 'si' && trim($campo[35]) != 'Si' && trim($campo[35]) != 'No' && trim($campo[35]) != 'no'){
                                $arrayFilasDonador[$contadorDonador] = $count1;
                                ++$contadorDonador;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasDonador)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las indicaciones de donador de órganos de las filas: <br>";
                            foreach($arrayFilasDonador as $array){
                                if($i == count($arrayFilasDonador)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 36){

                        $count2 = 1;
                        $contadorBanco = 0;
                        $arrayFilasBanco = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[36]) == '' && (trim($campo[37]) != '' || trim($campo[38]) != '' || trim($campo[39]) != '')){
                                $arrayFilasBanco[$contadorBanco] = $count2;
                                ++$contadorBanco;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasBanco)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasBanco as $array){
                                if($i == count($arrayFilasBanco)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen banco</p> <br>";
                        }

                        $count20 = 1;
                        $indexBanco = 0;
                        $contadorBancoBD = 0;
                        $arrayFilasBancoBD =  array();
                        $arrayBancoBD = array();
                        $datosBanco = bancos();
                        foreach($datosBanco as $bancos){
                            $arrayBancoBD[$indexBanco] = trim($bancos['Banco']);
                            ++$indexBanco;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[36])), array_map('strtolower', $arrayBancoBD)) && trim($campo[36]) != ''){
                                $arrayFilasBancoBD[$contadorBancoBD] = $count20;
                                ++$contadorBancoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasBancoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los bancos de las filas: <br>";
                            foreach($arrayFilasBancoBD as $array){
                                if($i == count($arrayFilasBancoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }

                    }

                    if($num_cols > 37){

                        $count2 = 1;
                        $contadorCuentaBancaria = 0;
                        $arrayFilasCuentaBancaria = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[37]) == '' && (trim($campo[36]) != '' || trim($campo[38]) != '' || trim($campo[39]) != '')){
                                $arrayFilasCuentaBancaria[$contadorCuentaBancaria] = $count2;
                                ++$contadorCuentaBancaria;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasCuentaBancaria)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasCuentaBancaria as $array){
                                if($i == count($arrayFilasCuentaBancaria)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen cuenta bancaria</p> <br>";
                        }

                        $count1 = 1;
                        $contadorCuentaBancariaInv = 0;
                        $arrayFilasCuentaBancariaInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[34]) != '' && !preg_match('/^[0-9]{20}$/', trim($campo[34]))){
                                $arrayFilasCuentaBancariaInv[$contadorCuentaBancariaInv] = $count1;
                                ++$contadorCuentaBancariaInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasCuentaBancariaInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las cuentas bancarias de las filas: <br>";
                            foreach($arrayFilasCuentaBancariaInv as $array){
                                if($i == count($arrayFilasCuentaBancariaInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 38){

                        $count2 = 1;
                        $contadorCLABE = 0;
                        $arrayFilasCLABE = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[38]) == '' && (trim($campo[36]) != '' || trim($campo[37]) != '' || trim($campo[39]) != '')){
                                $arrayFilasCLABE[$contadorCLABE] = $count2;
                                ++$contadorCLABE;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasCLABE)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasCLABE as $array){
                                if($i == count($arrayFilasCLABE)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen CLABE</p> <br>";
                        }

                        $count1 = 1;
                        $contadorCLABEInv = 0;
                        $arrayFilasCLABEInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[38]) != '' && !preg_match('/^[0-9]{18}$/', trim($campo[38]))){
                                $arrayFilasCLABEInv[$contadorCLABEInv] = $count1;
                                ++$contadorCLABEInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasCLABEInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las CLABEs de las filas: <br>";
                            foreach($arrayFilasCLABEInv as $array){
                                if($i == count($arrayFilasCLABEInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 39){

                        $count2 = 1;
                        $contadorNumeroTarjeta = 0;
                        $arrayFilasNumeroTarjeta = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[39]) == '' && (trim($campo[36]) != '' || trim($campo[37]) != '' || trim($campo[38]) != '')){
                                $arrayFilasNumeroTarjeta[$contadorNumeroTarjeta] = $count2;
                                ++$contadorNumeroTarjeta;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasNumeroTarjeta)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasNumeroTarjeta as $array){
                                if($i == count($arrayFilasNumeroTarjeta)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen número de tarjeta</p> <br>";
                        }

                        $count1 = 1;
                        $contadorNumeroTarjetaInv = 0;
                        $arrayFilasNumeroTarjetaInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[39]) != '' && !preg_match('/(?<!\d)\d{16}(?!\d)|(?<!\d[ _-])(?<!\d)\d{4}(?:[_ -]\d{4}){3}(?![_ -]?\d)/', trim($campo[39]))){
                                $arrayFilasNumeroTarjetaInv[$contadorNumeroTarjetaInv] = $count1;
                                ++$contadorNumeroTarjetaInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNumeroTarjetaInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de tarjetas de las filas: <br>";
                            foreach($arrayFilasNumeroTarjetaInv as $array){
                                if($i == count($arrayFilasNumeroTarjetaInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                } else {
                    echo "error" . SimpleXLS::parseError();
                }
                

            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../lib/SimpleXLS/SimpleXLSX.php';

                if($xls = SimpleXLSX::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name'])){
                    //echo json_encode($xls->rows());

                    $num_cols = 0;

                    foreach ($xls->rows() as $r){
                        $num_cols = count($r);
                        break;
                    }
                    
                    if($num_cols == 0){
                        echo "<p style='font-size: 15px'>Formato incorrecto</p>";
                    }
                    
                    if($num_cols < 18){
                        echo "<p style='font-size: 15px'>Formato incompleto</p>";
                    }

                    if($num_cols > 0){

                        $count1 = 1;
                        $contadorNombre = 0;
                        $arrayFilasNombre =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[0]) == ''){
                                $arrayFilasNombre[$contadorNombre] = $count1;
                                ++$contadorNombre;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasNombre)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasNombre as $array){
                                if($i == count($arrayFilasNombre)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen nombre</p> <br>";
                        }

                        $count23 = 1;
                        $contadorNombreInvalido = 0;
                        $arrayFilasNombreInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', trim($campo[0])) || strlen(trim($campo[0])) > 55) && trim($campo[0]) != ''){
                                $arrayFilasNombreInvalido[$contadorNombreInvalido] = $count23;
                                ++$contadorNombreInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasNombreInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los nombres de las filas: ";
                            foreach($arrayFilasNombreInvalido as $array){
                                if($i == count($arrayFilasNombreInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 1){

                        $count2 = 1;
                        $contadorApPaterno = 0;
                        $arrayFilasApPaterno = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[1]) == ''){
                                $arrayFilasApPaterno[$contadorApPaterno] = $count2;
                                ++$contadorApPaterno;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasApPaterno)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasApPaterno as $array){
                                if($i == count($arrayFilasApPaterno)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen apellido paterno</p> <br>";
                        }
                        
                        $count23 = 1;
                        $contadorApPaternoInvalido = 0;
                        $arrayFilasApPaternoInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', trim($campo[1])) || strlen(trim($campo[0])) > 25) && trim($campo[1]) != ''){
                                $arrayFilasApPaternoInvalido[$contadorApPaternoInvalido] = $count23;
                                ++$contadorApPaternoInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasApPaternoInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los apellidos paternos de las filas: ";
                            foreach($arrayFilasApPaternoInvalido as $array){
                                if($i == count($arrayFilasApPaternoInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 2){

                        $count23 = 1;
                        $contadorApMaternoInvalido = 0;
                        $arrayFilasApMaternoInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', trim($campo[2])) || strlen(trim($campo[2])) > 25) && trim($campo[2]) != ''){
                                $arrayFilasApMaternoInvalido[$contadorApMaternoInvalido] = $count23;
                                ++$contadorApMaternoInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasApMaternoInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los apellidos maternos de las filas: ";
                            foreach($arrayFilasApMaternoInvalido as $array){
                                if($i == count($arrayFilasApMaternoInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 3){

                        $count5 = 1;
                        $contadorEC = 0;
                        $arrayFilasEC = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && trim($campo[3]) != '' && trim($campo[3]) != 'Soltero' && trim($campo[3]) != 'soltero' && trim($campo[3]) != 'Casado' && trim($campo[3]) != 'casado' && trim($campo[3]) != 'Divorciado' && trim($campo[3]) != 'divorciado' && trim($campo[3]) != 'Viudo' && trim($campo[3]) != 'viudo' && trim($campo[3]) != 'Concubinato' && trim($campo[3]) != 'concubinato' && trim($campo[3]) != ''){
                                $arrayFilasEC[$contadorEC] = $count5;
                                ++$contadorEC;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasEC)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasEC as $array){
                                if($i == count($arrayFilasEC)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un estado civil inválido</p> <br>";
                        }

                    }

                    if($num_cols > 4){

                        $count4 = 1;
                        $contadorGenero = 0;
                        $arrayFilasGenero = array();

                        foreach ($xls->rows() as $campo) {
                            if($count4 > 1 && trim($campo[4]) == ''){
                                $arrayFilasGenero[$contadorGenero] = $count4;
                                ++$contadorGenero;
                            }
                            ++$count4;
                        }

                        if(!empty($arrayFilasGenero)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasGenero as $array){
                                if($i == count($arrayFilasGenero)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen género</p> <br>";
                        }

                        $count5 = 1;
                        $contadorGeneroVendedorInv = 0;
                        $arrayFilasGeneroVendedorInv = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && trim($campo[4]) != '' && trim($campo[4]) != 'Femenino' && trim($campo[4]) != 'femenino' && trim($campo[4]) != 'Masculino' && trim($campo[4]) != 'masculino' && trim($campo[4]) != ''){
                                $arrayFilasGeneroVendedorInv[$contadorGeneroVendedorInv] = $count5;
                                ++$contadorGeneroVendedorInv;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasGeneroVendedorInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasGeneroVendedorInv as $array){
                                if($i == count($arrayFilasGeneroVendedorInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un género inválido</p> <br>";
                        }

                    }

                    if($num_cols > 5){

                        $count2 = 1;
                        $contadorRol = 0;
                        $arrayFilasRol = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[5]) == ''){
                                $arrayFilasRol[$contadorRol] = $count2;
                                ++$contadorRol;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasRol)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasRol as $array){
                                if($i == count($arrayFilasRol)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen rol inicial</p> <br>";
                        }

                    }

                    if($num_cols > 6){

                        $count1 = 1;
                        $contadorCURP = 0;
                        $arrayFilasCURP =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && !preg_match('/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/', trim($campo[6])) && trim($campo[6]) != ''){
                                $arrayFilasCURP[$contadorCURP] = $count1;
                                ++$contadorCURP;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasCURP)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasCURP as $array){
                                if($i == count($arrayFilasCURP)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen CURP inválida</p> <br>";
                        }

                    }

                    if($num_cols > 7){

                        $count22 = 1;
                        $contadorRFCInvalido = 0;
                        $arrayFilasRFCInvalido = array();
                        $aceptarGenerico = true;

                        foreach ($xls->rows() as $campo) {
                            if($count22 > 1 && !preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[7]))) && trim($campo[7]) != ''){//Coincide con el formato general del regex?
                                $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                ++$contadorRFCInvalido;
                            }elseif ($count22 > 1 && preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[7]))) && trim($campo[7]) != '') {
                                $rfc = strtoupper(trim($campo[7]));
                                //Separar el dígito verificador del resto del RFC
                                $arrayRfc = str_split($rfc, 1);
                                $digitoVerificador = array_pop($arrayRfc);
                                $len = count($arrayRfc);
                                //Obtener el digito esperado
                                $diccionario = '0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ';
                                $arrayDiccionario = str_split($diccionario, 1);
                                $indice = $len + 1;
                                $suma = 0;
                                $digitoEsperado = 0;

                                ($len == 12) ? $suma = 0 : $suma = 481; //Ajuste para persona moral

                                for($i = 0; $i < $len; $i++){
                                    $suma += array_search($arrayRfc[$i], $arrayDiccionario) * ($indice - $i);
                                }
                                $digitoEsperado = 11 - ($suma % 11);
                                if($digitoEsperado == 11) $digitoEsperado = 0;
                                elseif ($digitoEsperado == 10) $digitoEsperado = 'A';

                                //El dígito verificador coincide con el esperado?
                                // o es un RFC Genérico (ventas a público general)?

                                if(
                                    $digitoVerificador != $digitoEsperado &&
                                    (!$aceptarGenerico || $arrayRfc + $digitoVerificador != 'XAXX010101000')
                                ){
                                    $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                    ++$contadorRFCInvalido;
                                }
                                else if(
                                    !$aceptarGenerico &&
                                    $arrayRfc + $digitoVerificador == 'XEXX010101000'
                                ){
                                    $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                    ++$contadorRFCInvalido;
                                }
                            }
                            ++$count22;
                        }

                        if(!empty($arrayFilasRFCInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los RFCs de las filas: ";
                            foreach($arrayFilasRFCInvalido as $array){
                                if($i == count($arrayFilasRFCInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 8){

                        $count5 = 1;
                        $contadorFechaNacimiento = 0;
                        $arrayFilasFechaNacimiento = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && date_parse(trim($campo[8]))["year"] == false && trim($campo[8]) != ''){
                                $arrayFilasFechaNacimiento[$contadorFechaNacimiento] = $count5;
                                ++$contadorFechaNacimiento;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasFechaNacimiento)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las fechas de nacimiento de las filas: <br>";
                            foreach($arrayFilasFechaNacimiento as $array){
                                if($i == count($arrayFilasFechaNacimiento)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 9){

                        $count1 = 1;
                        $contadorTelefono = 0;
                        $arrayFilasTelefono =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[9]) != '' && (!ctype_digit(trim($campo[9])) || strlen(trim($campo[9])) != 10)){
                                $arrayFilasTelefono[$contadorTelefono] = $count1;
                                ++$contadorTelefono;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTelefono)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los teléfonos de las filas: ";
                            foreach($arrayFilasTelefono as $array){
                                if($i == count($arrayFilasTelefono)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 10){

                        $count21 = 1;
                        $contadorCorreo = 0;
                        $arrayFilasCorreo = array();

                        foreach ($xls->rows() as $campo) {
                            if($count21 > 1 && !preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', trim($campo[10])) && trim($campo[10]) != ''){
                                $arrayFilasCorreo[$contadorCorreo] = $count21;
                                ++$contadorCorreo;
                            }
                            ++$count21;
                        }

                        if(!empty($arrayFilasCorreo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los correos de las filas: ";
                            foreach($arrayFilasCorreo as $array){
                                if($i == count($arrayFilasCorreo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 15){
                        
                        $count23 = 1;
                        $contadorCPInvalido = 0;
                        $arrayFilasCPInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && !preg_match('/(^([0-9]{5})|^)$/', trim($campo[15])) && trim($campo[15]) != ''){
                                $arrayFilasCPInvalido[$contadorCPInvalido] = $count23;
                                ++$contadorCPInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasCPInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los códigos postales de las filas: ";
                            foreach($arrayFilasCPInvalido as $array){
                                if($i == count($arrayFilasCPInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 17){

                        $count20 = 1;
                        $indexEstado = 0;
                        $contadorEstadoBD = 0;
                        $arrayFilasEstadoBD =  array();
                        $arrayEstadoBD = array();
                        $datosEstado = estados();
                        foreach($datosEstado as $estados){
                            $arrayEstadoBD[$indexEstado] = trim($estados['Estado']);
                            ++$indexEstado;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[17])), array_map('strtolower', $arrayEstadoBD)) && trim($campo[17]) != ''){
                                $arrayFilasEstadoBD[$contadorEstadoBD] = $count20;
                                ++$contadorEstadoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasEstadoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los estados de las filas: <br>";
                            foreach($arrayFilasEstadoBD as $array){
                                if($i == count($arrayFilasEstadoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }

                    }

                    if($num_cols > 18){

                        $count20 = 1;
                        $indexRegimenFiscal = 0;
                        $contadorRegimenFiscalBD = 0;
                        $arrayFilasRegimenFiscalBD =  array();
                        $arrayRegimenFiscalBD = array();
                        $datosRegimenFiscal = estados();
                        foreach($datosRegimenFiscal as $estados){
                            $arrayRegimenFiscalBD[$indexRegimenFiscal] = trim($estados['descripcion']);
                            ++$indexRegimenFiscal;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[17])), array_map('strtolower', $arrayRegimenFiscalBD)) && trim($campo[17]) != ''){
                                $arrayFilasRegimenFiscalBD[$contadorRegimenFiscalBD] = $count20;
                                ++$contadorRegimenFiscalBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasRegimenFiscalBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los estados de las filas: <br>";
                            foreach($arrayFilasRegimenFiscalBD as $array){
                                if($i == count($arrayFilasRegimenFiscalBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }

                    }

                    if($num_cols > 19){

                        $count1 = 1;
                        $contadorFechaIngreso = 0;
                        $arrayFilasFechaIngreso =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[19]) == '' && (trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[22]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '' || trim($campo[27]) != '')){
                                $arrayFilasFechaIngreso[$contadorFechaIngreso] = $count1;
                                ++$contadorFechaIngreso;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasFechaIngreso)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasFechaIngreso as $array){
                                if($i == count($arrayFilasFechaIngreso)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen fecha de ingreso</p> <br>";
                        }

                        $count5 = 1;
                        $contadorFechaIngresoInv = 0;
                        $arrayFilasFechaIngresoInv = array();

                        foreach ($xls->rows() as $campo) {
                            if($count5 > 1 && date_parse(trim($campo[19]))["year"] == false && trim($campo[18]) !=''){
                                $arrayFilasFechaIngresoInv[$contadorFechaIngresoInv] = $count5;
                                ++$contadorFechaIngresoInv;
                            }
                            ++$count5;
                        }

                        if(!empty($arrayFilasFechaIngresoInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las fechas de ingreso de las filas: <br>";
                            foreach($arrayFilasFechaIngresoInv as $array){
                                if($i == count($arrayFilasFechaIngresoInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 20){

                        $count1 = 1;
                        $contadorTipoContrato = 0;
                        $arrayFilasTipoContrato =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[20]) == '' && (trim($campo[19]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '' || trim($campo[27]) != '')){
                                $arrayFilasTipoContrato[$contadorTipoContrato] = $count1;
                                ++$contadorTipoContrato;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTipoContrato)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasTipoContrato as $array){
                                if($i == count($arrayFilasTipoContrato)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen tipo de contrato</p> <br>";
                        }

                    }

                    if($num_cols > 21){

                        $count1 = 1;
                        $contadorPuesto = 0;
                        $arrayFilasPuesto =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[21]) == '' && (trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '' || trim($campo[27]) != '')){
                                $arrayFilasPuesto[$contadorPuesto] = $count1;
                                ++$contadorPuesto;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasPuesto)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasPuesto as $array){
                                if($i == count($arrayFilasPuesto)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen puesto</p> <br>";
                        }

                    }

                    if($num_cols > 22){

                        $count1 = 1;
                        $contadorRiesgo = 0;
                        $arrayFilasRiesgo =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[22]) == '' && (trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '' || trim($campo[27]) != '')){
                                $arrayFilasRiesgo[$contadorRiesgo] = $count1;
                                ++$contadorRiesgo;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasRiesgo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasRiesgo as $array){
                                if($i == count($arrayFilasRiesgo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen riesgo del puesto</p> <br>";
                        }

                    }

                    if($num_cols > 22){

                        $count1 = 1;
                        $contadorRégimen = 0;
                        $arrayFilasRégimen =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[22]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasRégimen[$contadorRégimen] = $count1;
                                ++$contadorRégimen;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasRégimen)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasRégimen as $array){
                                if($i == count($arrayFilasRégimen)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen régimen</p> <br>";
                        }

                    }

                    if($num_cols > 23){

                        $count1 = 1;
                        $contadorTurno = 0;
                        $arrayFilasTurno =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[23]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasTurno[$contadorTurno] = $count1;
                                ++$contadorTurno;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTurno)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasTurno as $array){
                                if($i == count($arrayFilasTurno)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen turno</p> <br>";
                        }

                        $count20 = 1;
                        $indexTurno = 0;
                        $contadorTurnoBD = 0;
                        $arrayFilasTurnoBD =  array();
                        $arrayTurnoBD = array();
                        $datosTurno = turnos();
                        foreach($datosTurno as $turnos){
                            $arrayTurnoBD[$indexTurno] = $turnos['Turno'];
                            ++$indexTurno;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[23])), array_map('strtolower', $arrayTurnoBD)) && trim($campo[23]) != ''){
                                $arrayFilasTurnoBD[$contadorTurnoBD] = $count20;
                                ++$contadorTurnoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasTurnoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los turnos de las filas: <br>";
                            foreach($arrayFilasTurnoBD as $array){
                                if($i == count($arrayFilasTurnoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }


                    }

                    if($num_cols > 24){

                        $count1 = 1;
                        $contadorSucursal = 0;
                        $arrayFilasSucursal =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[24]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[25]) != '' || trim($campo[26]) != '')){
                                $arrayFilasSucursal[$contadorSucursal] = $count1;
                                ++$contadorSucursal;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasSucursal)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasSucursal as $array){
                                if($i == count($arrayFilasSucursal)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen sucursal</p> <br>";
                        }

                        $count20 = 1;
                        $indexSucursal = 0;
                        $contadorSucursalBD = 0;
                        $arrayFilasSucursalBD =  array();
                        $arraySucursalBD = array();
                        $datosSucursal = sucursales();
                        foreach($datosSucursal as $sucursales){
                            $arraySucursalBD[$indexSucursal] = $sucursales['sucursal'];
                            ++$indexSucursal;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[24])), array_map('strtolower', $arraySucursalBD)) && trim($campo[24]) != ''){
                                $arrayFilasSucursalBD[$contadorSucursalBD] = $count20;
                                ++$contadorSucursalBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasSucursalBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las sucursales de las filas: <br>";
                            foreach($arrayFilasSucursalBD as $array){
                                if($i == count($arrayFilasSucursalBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }


                    }

                    if($num_cols > 25){

                        $count1 = 1;
                        $contadorSueldo = 0;
                        $arrayFilasSueldo =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[25]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[26]) != '')){
                                $arrayFilasSueldo[$contadorSueldo] = $count1;
                                ++$contadorSueldo;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasSueldo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasSueldo as $array){
                                if($i == count($arrayFilasSueldo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen sueldo</p> <br>";
                        }

                        $count20 = 1;
                        $contadorSueldoBD = 0;
                        $arrayFilasSueldoBD =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !preg_match('/^\d+(\.\d{1,2})?$/', trim($campo[25])) && trim($campo[25]) != ''){
                                $arrayFilasSueldoBD[$contadorSueldoBD] = $count20;
                                ++$contadorSueldoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasSueldoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los sueldos de las filas: <br>";
                            foreach($arrayFilasSueldoBD as $array){
                                if($i == count($arrayFilasSueldoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }


                    }

                    if($num_cols > 26){

                        $count1 = 1;
                        $contadorPeriodoPago = 0;
                        $arrayFilasPeriodoPago =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[26]) == '' && (trim($campo[18]) != '' || trim($campo[19]) != '' || trim($campo[20]) != '' || trim($campo[21]) != '' || trim($campo[22]) != '' || trim($campo[23]) != '' || trim($campo[24]) != '' || trim($campo[25]) != '')){
                                $arrayFilasPeriodoPago[$contadorPeriodoPago] = $count1;
                                ++$contadorPeriodoPago;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasPeriodoPago)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: ";
                            foreach($arrayFilasPeriodoPago as $array){
                                if($i == count($arrayFilasPeriodoPago)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen período de pago</p> <br>";
                        }

                        $count20 = 1;
                        $contadorSueldoInv = 0;
                        $arrayFilasSueldoInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && trim($campo[26]) != 'Semanal' && trim($campo[26]) != 'semanal' && trim($campo[26]) != 'Catorcenal' && trim($campo[26]) != 'catorcenal' && trim($campo[26]) != 'Quincenal' && trim($campo[26]) != 'quincenal' && trim($campo[26]) != 'Mensual' && trim($campo[26]) != 'mensual' && trim($campo[26]) != ''){
                                $arrayFilasSueldoInv[$contadorSueldoInv] = $count20;
                                ++$contadorSueldoInv;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasSueldoInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los períodos de pago de las filas: <br>";
                            foreach($arrayFilasSueldoInv as $array){
                                if($i == count($arrayFilasSueldoInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }


                    }

                    if($num_cols > 27){

                        $count20 = 1;
                        $contadorInfonavit = 0;
                        $arrayFilasInfonavit =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !preg_match('/^\d+(\.\d{1,2})?$/', trim($campo[27])) && trim($campo[27]) != ''){
                                $arrayFilasInfonavit[$contadorInfonavit] = $count20;
                                ++$contadorInfonavit;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasInfonavit)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>El monto INFONAVIT de las filas: <br>";
                            foreach($arrayFilasInfonavit as $array){
                                if($i == count($arrayFilasInfonavit)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " es inválido</p> <br>";
                        }


                    }

                    if($num_cols > 28){

                        $count20 = 1;
                        $contadorDeudaInterna = 0;
                        $arrayFilasDeudaInterna =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !preg_match('/^\d+(\.\d{1,2})?$/', trim($campo[28])) && trim($campo[28]) != ''){
                                $arrayFilasDeudaInterna[$contadorDeudaInterna] = $count20;
                                ++$contadorDeudaInterna;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasDeudaInterna)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>El monto de la deuda interna de las filas: <br>";
                            foreach($arrayFilasDeudaInterna as $array){
                                if($i == count($arrayFilasDeudaInterna)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " es inválido</p> <br>";
                        }


                    }

                    if($num_cols > 29){

                        $count20 = 1;
                        $contadorNSS = 0;
                        $arrayFilasNSS =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && trim($campo[29]) == '' && trim($campo[30]) != ''){
                                $arrayFilasNSS[$contadorNSS] = $count20;
                                ++$contadorNSS;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasNSS)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasNSS as $array){
                                if($i == count($arrayFilasNSS)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen número de seguro social</p> <br>";
                        }

                        $count1 = 1;
                        $contadorNSSInv = 0;
                        $arrayFilasNSSInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && !preg_match('/^[0-9]{11}$/', trim($campo[29])) && trim($campo[29]) != ''){
                                $arrayFilasNSSInv[$contadorNSSInv] = $count1;
                                ++$contadorNSSInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNSSInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de seguro social de las filas: <br>";
                            foreach($arrayFilasNSSInv as $array){
                                if($i == count($arrayFilasNSSInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 30){

                        $count20 = 1;
                        $contadorTipoSangre = 0;
                        $arrayFilasTipoSangre =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && trim($campo[30]) == '' && trim($campo[29]) != ''){
                                $arrayFilasTipoSangre[$contadorTipoSangre] = $count20;
                                ++$contadorTipoSangre;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasTipoSangre)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasTipoSangre as $array){
                                if($i == count($arrayFilasTipoSangre)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen tipo de sangre</p> <br>";
                        }

                        $count1 = 1;
                        $contadorTipoSangreInv = 0;
                        $arrayFilasTipoSangreInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[30]) != '' && trim($campo[30]) != 'A+' && trim($campo[30]) != 'A-' && trim($campo[30]) != 'B+' && trim($campo[30]) != 'B-' && trim($campo[30]) != 'AB+' && trim($campo[30]) != 'AB-' && trim($campo[30]) != 'O+' && trim($campo[30]) != 'O-'){
                                $arrayFilasTipoSangreInv[$contadorTipoSangreInv] = $count1;
                                ++$contadorTipoSangreInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasTipoSangreInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los tipos de sangre de las filas: <br>";
                            foreach($arrayFilasTipoSangreInv as $array){
                                if($i == count($arrayFilasTipoSangreInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 31){

                        $count1 = 1;
                        $contadorContactoEmergencia = 0;
                        $arrayFilasContactoEmergencia =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[31]) != '' && (!preg_match('/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/', trim($campo[31])) || strlen(trim($campo[31])) > 25)){
                                $arrayFilasContactoEmergencia[$contadorContactoEmergencia] = $count1;
                                ++$contadorContactoEmergencia;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasContactoEmergencia)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los contactos de emergencia de las filas: <br>";
                            foreach($arrayFilasContactoEmergencia as $array){
                                if($i == count($arrayFilasContactoEmergencia)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 32){

                        $count1 = 1;
                        $contadorNumeroEmergencia = 0;
                        $arrayFilasNumeroEmergencia =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[32]) != '' && !preg_match('/^[0-9]{10}$/', trim($campo[32]))){
                                $arrayFilasNumeroEmergencia[$contadorNumeroEmergencia] = $count1;
                                ++$contadorNumeroEmergencia;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNumeroEmergencia)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de emergencia de las filas: <br>";
                            foreach($arrayFilasNumeroEmergencia as $array){
                                if($i == count($arrayFilasNumeroEmergencia)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidos</p> <br>";
                        }

                    }

                    if($num_cols > 33){

                        $count1 = 1;
                        $contadorAlergias = 0;
                        $arrayFilasAlergias =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[33]) != '' && strlen(trim($campo[33])) > 100){
                                $arrayFilasAlergias[$contadorAlergias] = $count1;
                                ++$contadorAlergias;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasAlergias)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las descripciones de alergias de las filas: <br>";
                            foreach($arrayFilasAlergias as $array){
                                if($i == count($arrayFilasAlergias)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son muy largas</p> <br>";
                        }

                    }

                    if($num_cols > 34){

                        $count1 = 1;
                        $contadorNotas = 0;
                        $arrayFilasNotas =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[34]) != '' && strlen(trim($campo[34])) > 70){
                                $arrayFilasNotas[$contadorNotas] = $count1;
                                ++$contadorNotas;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNotas)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las notas de las filas: <br>";
                            foreach($arrayFilasNotas as $array){
                                if($i == count($arrayFilasNotas)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son muy largas</p> <br>";
                        }

                    }
                    
                    if($num_cols > 35){

                        $count1 = 1;
                        $contadorDonador = 0;
                        $arrayFilasDonador =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[35]) != '' && trim($campo[35]) != 'si' && trim($campo[35]) != 'Si' && trim($campo[35]) != 'No' && trim($campo[35]) != 'no'){
                                $arrayFilasDonador[$contadorDonador] = $count1;
                                ++$contadorDonador;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasDonador)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las indicaciones de donador de órganos de las filas: <br>";
                            foreach($arrayFilasDonador as $array){
                                if($i == count($arrayFilasDonador)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 36){

                        $count2 = 1;
                        $contadorBanco = 0;
                        $arrayFilasBanco = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[36]) == '' && (trim($campo[37]) != '' || trim($campo[38]) != '' || trim($campo[39]) != '')){
                                $arrayFilasBanco[$contadorBanco] = $count2;
                                ++$contadorBanco;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasBanco)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasBanco as $array){
                                if($i == count($arrayFilasBanco)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen banco</p> <br>";
                        }

                        $count20 = 1;
                        $indexBanco = 0;
                        $contadorBancoBD = 0;
                        $arrayFilasBancoBD =  array();
                        $arrayBancoBD = array();
                        $datosBanco = bancos();
                        foreach($datosBanco as $bancos){
                            $arrayBancoBD[$indexBanco] = trim($bancos['Banco']);
                            ++$indexBanco;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count20 > 1 && !in_array(strtolower(trim($campo[36])), array_map('strtolower', $arrayBancoBD)) && trim($campo[36]) != ''){
                                $arrayFilasBancoBD[$contadorBancoBD] = $count20;
                                ++$contadorBancoBD;
                            }
                            ++$count20;
                        }
                        
                        if(!empty($arrayFilasBancoBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los bancos de las filas: <br>";
                            foreach($arrayFilasBancoBD as $array){
                                if($i == count($arrayFilasBancoBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }

                    }

                    if($num_cols > 37){

                        $count2 = 1;
                        $contadorCuentaBancaria = 0;
                        $arrayFilasCuentaBancaria = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[37]) == '' && (trim($campo[36]) != '' || trim($campo[38]) != '' || trim($campo[39]) != '')){
                                $arrayFilasCuentaBancaria[$contadorCuentaBancaria] = $count2;
                                ++$contadorCuentaBancaria;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasCuentaBancaria)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasCuentaBancaria as $array){
                                if($i == count($arrayFilasCuentaBancaria)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen cuenta bancaria</p> <br>";
                        }

                        $count1 = 1;
                        $contadorCuentaBancariaInv = 0;
                        $arrayFilasCuentaBancariaInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[34]) != '' && !preg_match('/^[0-9]{20}$/', trim($campo[34]))){
                                $arrayFilasCuentaBancariaInv[$contadorCuentaBancariaInv] = $count1;
                                ++$contadorCuentaBancariaInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasCuentaBancariaInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las cuentas bancarias de las filas: <br>";
                            foreach($arrayFilasCuentaBancariaInv as $array){
                                if($i == count($arrayFilasCuentaBancariaInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 38){

                        $count2 = 1;
                        $contadorCLABE = 0;
                        $arrayFilasCLABE = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[38]) == '' && (trim($campo[36]) != '' || trim($campo[37]) != '' || trim($campo[39]) != '')){
                                $arrayFilasCLABE[$contadorCLABE] = $count2;
                                ++$contadorCLABE;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasCLABE)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasCLABE as $array){
                                if($i == count($arrayFilasCLABE)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen CLABE</p> <br>";
                        }

                        $count1 = 1;
                        $contadorCLABEInv = 0;
                        $arrayFilasCLABEInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[38]) != '' && !preg_match('/^[0-9]{18}$/', trim($campo[38]))){
                                $arrayFilasCLABEInv[$contadorCLABEInv] = $count1;
                                ++$contadorCLABEInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasCLABEInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las CLABEs de las filas: <br>";
                            foreach($arrayFilasCLABEInv as $array){
                                if($i == count($arrayFilasCLABEInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
                        }

                    }

                    if($num_cols > 39){

                        $count2 = 1;
                        $contadorNumeroTarjeta = 0;
                        $arrayFilasNumeroTarjeta = array();

                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && trim($campo[39]) == '' && (trim($campo[36]) != '' || trim($campo[37]) != '' || trim($campo[38]) != '')){
                                $arrayFilasNumeroTarjeta[$contadorNumeroTarjeta] = $count2;
                                ++$contadorNumeroTarjeta;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayFilasNumeroTarjeta)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los empleados de las filas: <br>";
                            foreach($arrayFilasNumeroTarjeta as $array){
                                if($i == count($arrayFilasNumeroTarjeta)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen número de tarjeta</p> <br>";
                        }

                        $count1 = 1;
                        $contadorNumeroTarjetaInv = 0;
                        $arrayFilasNumeroTarjetaInv =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[39]) != '' && !preg_match('/(?<!\d)\d{16}(?!\d)|(?<!\d[ _-])(?<!\d)\d{4}(?:[_ -]\d{4}){3}(?![_ -]?\d)/', trim($campo[39]))){
                                $arrayFilasNumeroTarjetaInv[$contadorNumeroTarjetaInv] = $count1;
                                ++$contadorNumeroTarjetaInv;
                            }
                            ++$count1;
                        }
                        
                        if(!empty($arrayFilasNumeroTarjetaInv)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de tarjetas de las filas: <br>";
                            foreach($arrayFilasNumeroTarjetaInv as $array){
                                if($i == count($arrayFilasNumeroTarjetaInv)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " son inválidas</p> <br>";
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

function tiposEmpleados(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('SELECT tipo FROM tipo_empleado');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function estados(){
    $con = new conectar();
    $db = $con->getDb();
    try {
        $query = sprintf('SELECT Estado FROM estados_federativos');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function turnos(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('SELECT Turno FROM turnos WHERE empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function sucursales(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('SELECT sucursal FROM sucursales WHERE empresa_id  = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function bancos(){
    $con = new conectar();
    $db = $con->getDb();
    try {
        $query = sprintf('SELECT Banco FROM bancos');
        $stmt = $db->prepare($query);
        $stmt->execute(array());
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function remove_accents($string) {
    if ( !preg_match('/[\xcc\x81]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}

unlink($subir_archivo);
