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
                include '../../../../lib/SimpleXLS/SimpleXLS.php';

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
                    
                    if($num_cols < 21){
                        echo "<p style='font-size: 15px'>Formato incompleto</p>";
                    }

                    if($num_cols > 0){

                        $count1 = 1;
                        $contadorEstatus = 0;
                        $arrayFilasEstatus =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[0]) == ''){
                                $arrayFilasEstatus[$contadorEstatus] = $count1;
                                ++$contadorEstatus;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasEstatus)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasEstatus as $array){
                                if($i == count($arrayFilasEstatus)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen indicado el estatus</p> <br>";
                        }

                        $count2 = 1;
                        $contadorEstatus = 0;
                        $arrayEstatusInv = array();
                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && strtolower(trim($campo[0])) != 'activo' && strtolower(trim($campo[0])) != 'inactivo'){
                                $arrayFilasEstatus[$contadorEstatus] = $count2;
                                ++$contadorEstatus;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayEstatusInv)){
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            for ($i=0; $i < count($arrayEstatusInv) ; $i++) { 
                                if($i == count($arrayEstatusInv)){
                                    echo $arrayEstatusInv[$i];
                                }else {
                                    echo $arrayEstatusInv[$i] . ", ";
                                }
                            }
                            echo " tienen indicado un estatus inválido</p> <br><br>";
                        }

                    }

                    if($num_cols > 1){

                        $count1 = 1;
                        $contadorCredito = 0;
                        $arrayFilasCredito =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[1]) == ''){
                                $arrayFilasCredito[$contadorCredito] = $count1;
                                ++$contadorCredito;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasCredito)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasCredito as $array){
                                if($i == count($arrayFilasCredito)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen indicado el crédito</p> <br>";
                        }

                        $count2 = 1;
                        $contadorCredito = 0;
                        $arrayCreditoInv = array();
                        foreach ($xls->rows() as $campo) {
                            //Se divide la cadena en un arreglo para saber si el primer caracter es un dígito
                            $credito = str_split(trim($campo[1]), 1);
                            if($count2 > 1 && strtolower(trim($campo[1])) != 'contado' && !ctype_digit($credito[0])){
                                $arrayFilasCredito[$contadorCredito] = $count2;
                                ++$contadorCredito;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayCreditoInv)){
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            for ($i=0; $i < count($arrayCreditoInv) ; $i++) { 
                                if($i == count($arrayCreditoInv)){
                                    echo $arrayCreditoInv[$i];
                                }else {
                                    echo $arrayCreditoInv[$i] . ", ";
                                }
                            }
                            echo " tienen indicado un crédito inválido</p> <br><br>";
                        }

                    }

                    if($num_cols > 2){

                        $count1 = 1;
                        $contadorTipoPersona = 0;
                        $arrayFilasTipoPersona =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[2]) == ''){
                                $arrayFilasTipoPersona[$contadorTipoPersona] = $count1;
                                ++$contadorTipoPersona;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTipoPersona)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasTipoPersona as $array){
                                if($i == count($arrayFilasTipoPersona)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen tipo de persona</p> <br>";
                        }

                        $count2 = 1;
                        $contadorTipoPersona = 0;
                        $arrayTipoPersonaInv = array();
                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && strtolower(trim($campo[2])) != 'física' && strtolower(trim($campo[2])) != 'fisica' && strtolower(trim($campo[2])) != 'moral'){
                                $arrayFilasTipoPersona[$contadorTipoPersona] = $count2;
                                ++$contadorTipoPersona;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayTipoPersonaInv)){
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            for ($i=0; $i < count($arrayTipoPersonaInv) ; $i++) { 
                                if($i == count($arrayTipoPersonaInv)){
                                    echo $arrayTipoPersonaInv[$i];
                                }else {
                                    echo $arrayTipoPersonaInv[$i] . ", ";
                                }
                            }
                            echo " tienen un tipo de persona inválido</p> <br><br>";
                        }

                    }

                    if($num_cols > 3){

                        $count6 = 1;
                        $contadorRFC = 0;
                        $arrayFilasRFC = array();

                        foreach ($xls->rows() as $campo) {
                            if($count6 > 1 && trim($campo[3]) == ''){
                                $arrayFilasRFC[$contadorRFC] = $count6;
                                ++$contadorRFC;
                            }
                            ++$count6;
                        }

                        if(!empty($arrayFilasRFC)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasRFC as $array){
                                if($i == count($arrayFilasRFC)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen RFC</p> <br>";
                        }

                        $count14 = 1;
                        $count15 = 0;
                        $arrayRFC = array();

                        foreach ($xls->rows() as $campo) {
                            if($count14 > 1 && trim($campo[3]) != ''){
                                $arrayRFC[$count15] = trim($campo[3]);
                                ++$count15;
                            }
                            ++$count14;
                        }
                        
                        $arrayRFCDuplicado = array();

                        if(count(array_unique(array_map('strtoupper', $arrayRFC))) != count($arrayRFC)){

                            $contadorRFCDuplicado = 0;
                            
                            foreach(array_count_values(array_map('strtoupper', $arrayRFC)) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayRFCDuplicado[$contadorRFCDuplicado] = $arrayCount;
                                    ++$contadorRFCDuplicado;
                                }
                            }
                            
                        }

                        if(!empty($arrayRFCDuplicado)){
                            echo "<p style='font-size: 15px'>Los RFCs: <br>";
                            for ($i=0; $i < count($arrayRFCDuplicado) ; $i++) { 
                                if($i == count($arrayRFCDuplicado)){
                                    echo $arrayRFCDuplicado[$i];
                                }else {
                                    echo $arrayRFCDuplicado[$i] . ", ";
                                }
                            }
                            echo " están duplicados</p> <br><br>";
                        }

                        $count18 = 1;
                        $indexRFC = 0;
                        $contadorRFCBD = 0;
                        $arrayFilasRFCBD =  array();
                        $arrayRFCBD = array();
                        $datosRFC = rfc();
                        foreach($datosRFC as $rfcs){
                            $arrayRFCBD[$indexRFC] = $rfcs['RFC'];
                            ++$indexRFC;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count18 > 1 && in_array(strtoupper(trim($campo[3])), array_map('strtoupper', $arrayRFCBD))){
                                $arrayFilasRFCBD[$contadorRFCBD] = $count18;
                                ++$contadorRFCBD;
                            }
                            ++$count18;
                        }
                        
                        if(!empty($arrayFilasRFCBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los RFCs de las filas: <br>";
                            foreach($arrayFilasRFCBD as $array){
                                if($i == count($arrayFilasRFCBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                        $count22 = 1;
                        $contadorRFCInvalido = 0;
                        $arrayFilasRFCInvalido = array();
                        $aceptarGenerico = true;

                        foreach ($xls->rows() as $campo) {
                            if($count22 > 1 && !preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[3])))){//Coincide con el formato general del regex?
                                $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                ++$contadorRFCInvalido;
                            }elseif ($count22 > 1 && preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[3])))) {
                                $rfc = strtoupper(trim($campo[3]));
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
                                $strinArrayRfc = implode("", $arrayRfc);
                                if(
                                    $digitoVerificador != $digitoEsperado &&
                                    (!$aceptarGenerico || $strinArrayRfc . $digitoVerificador != 'XAXX010101000')
                                ){
                                    $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                    ++$contadorRFCInvalido;
                                }
                                else if(
                                    !$aceptarGenerico &&
                                    $strinArrayRfc . $digitoVerificador == 'XEXX010101000'
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

                    if($num_cols > 4){

                        $count1 = 1;
                        $contadorRazonSocial = 0;
                        $arrayFilasRazonSocial =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[4]) == ''){
                                $arrayFilasRazonSocial[$contadorRazonSocial] = $count1;
                                ++$contadorRazonSocial;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasRazonSocial)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasRazonSocial as $array){
                                if($i == count($arrayFilasRazonSocial)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen razón social</p> <br>";
                        }

                        $count10 = 1;
                        $count11 = 0;
                        $arrayRazonSocial = array();

                        foreach ($xls->rows() as $campo) {
                            if($count10 > 1 && trim($campo[4]) != ''){
                                $arrayRazonSocial[$count11] = trim($campo[4]);
                                ++$count11;
                            }
                            ++$count10;
                        }
                        
                        $arrayRazonSocialDuplicado = array();

                        if(count(array_unique(array_map('strtolower', $arrayRazonSocial))) != count($arrayRazonSocial)){

                            $contadorRazonSocialDuplicado = 0;
                            
                            foreach(array_count_values(array_map('strtolower', $arrayRazonSocial)) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayRazonSocialDuplicado[$contadorRazonSocialDuplicado] = $arrayCount;
                                    ++$contadorRazonSocialDuplicado;
                                }
                            }
                            
                        }

                        if(!empty($arrayRazonSocialDuplicado)){
                            echo "<p style='font-size: 15px'>Las razones sociales: <br>";
                            for ($i=0; $i < count($arrayRazonSocialDuplicado) ; $i++) { 
                                if($i == count($arrayRazonSocialDuplicado)){
                                    echo $arrayRazonSocialDuplicado[$i];
                                }else {
                                    echo $arrayRazonSocialDuplicado[$i] . ", ";
                                }
                            }
                            echo " están duplicadas</p> <br><br>";
                        }

                        $count16 = 1;
                        $indexRazonSocial = 0;
                        $contadorRazonSocialBD = 0;
                        $arrayFilasRazonSocialBD =  array();
                        $arrayRazonSocialBD = array();
                        $datosRazonSocial = razonesSociales();
                        foreach($datosRazonSocial as $razonesSociales){
                            $arrayRazonSocialBD[$indexRazonSocial] = $razonesSociales['Razon_Social'];
                            ++$indexRazonSocial;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count16 > 1 && in_array(strtolower(trim($campo[4])), array_map('strtolower', $arrayRazonSocialBD))){
                                $arrayFilasRazonSocialBD[$contadorRazonSocialBD] = $count16;
                                ++$contadorRazonSocialBD;
                            }
                            ++$count16;
                        }
                        
                        if(!empty($arrayFilasRazonSocialBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las razones sociales de las filas: <br>";
                            foreach($arrayFilasRazonSocialBD as $array){
                                if($i == count($arrayFilasRazonSocialBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                    }

                    if($num_cols > 5){

                        $count1 = 1;
                        $contadorNombreComercial = 0;
                        $arrayFilasNombreComercial =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[5]) == ''){
                                $arrayFilasNombreComercial[$contadorNombreComercial] = $count1;
                                ++$contadorNombreComercial;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasNombreComercial)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasNombreComercial as $array){
                                if($i == count($arrayFilasNombreComercial)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen nombre comercial</p> <br>";
                        }

                        $count10 = 1;
                        $count11 = 0;
                        $arrayNombreComercial = array();

                        foreach ($xls->rows() as $campo) {
                            if($count10 > 1 && trim($campo[5]) != ''){
                                $arrayNombreComercial[$count11] = trim($campo[5]);
                                ++$count11;
                            }
                            ++$count10;
                        }
                        
                        $arrayNombreComercialDuplicado = array();

                        if(count(array_unique(array_map('strtolower', $arrayNombreComercial))) != count($arrayNombreComercial)){

                            $contadorNombreComercialDuplicado = 0;
                            
                            foreach(array_count_values(array_map('strtolower', $arrayNombreComercial)) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayNombreComercialDuplicado[$contadorNombreComercialDuplicado] = $arrayCount;
                                    ++$contadorNombreComercialDuplicado;
                                }
                            }
                            
                        }

                        if(!empty($arrayNombreComercialDuplicado)){
                            echo "<p style='font-size: 15px'>Los nombres comerciales: <br>";
                            for ($i=0; $i < count($arrayNombreComercialDuplicado) ; $i++) { 
                                if($i == count($arrayNombreComercialDuplicado)){
                                    echo $arrayNombreComercialDuplicado[$i];
                                }else {
                                    echo $arrayNombreComercialDuplicado[$i] . ", ";
                                }
                            }
                            echo " están duplicados</p> <br><br>";
                        }

                        $count16 = 1;
                        $indexNombreComercial = 0;
                        $contadorNombreComercialBD = 0;
                        $arrayFilasNombreComercialBD =  array();
                        $arrayNombreComercialBD = array();
                        $datosNombreComercial = nombresComerciales();
                        foreach($datosNombreComercial as $nombresComerciales){
                            $arrayNombreComercialBD[$indexNombreComercial] = remove_accents($nombresComerciales['NombreComercial']);
                            ++$indexNombreComercial;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count16 > 1 && in_array(strtolower(remove_accents(trim($campo[5]))), array_map('strtolower', $arrayNombreComercialBD))){
                                $arrayFilasNombreComercialBD[$contadorNombreComercialBD] = $count16;
                                ++$contadorNombreComercialBD;
                            }
                            ++$count16;
                        }
                        
                        if(!empty($arrayFilasNombreComercialBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los nombres comerciales de las filas: <br>";
                            foreach($arrayFilasNombreComercialBD as $array){
                                if($i == count($arrayFilasNombreComercialBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                    }

                    if($num_cols > 8){

                        $count4 = 1;
                        $contadorEmail = 0;
                        $arrayFilasEmail = array();
    
                        foreach ($xls->rows() as $campo) {
                            if($count4 > 1 && trim($campo[8]) == ''){
                                $arrayFilasEmail[$contadorEmail] = $count4;
                                ++$contadorEmail;
                            }
                            ++$count4;
                        }
    
                        if(!empty($arrayFilasEmail)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasEmail as $array){
                                if($i == count($arrayFilasEmail)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen el primer correo electrónico</p> <br>";
                        }

                        $count21 = 1;
                        $contadorCorreo = 0;
                        $arrayFilasCorreo = array();

                        foreach ($xls->rows() as $campo) {
                            if($count21 > 1 && !filter_var(trim($campo[8]), FILTER_VALIDATE_EMAIL) && trim($campo[8]) != ''){
                                $arrayFilasCorreo[$contadorCorreo] = $count21;
                                ++$contadorCorreo;
                            }
                            ++$count21;
                        }

                        if(!empty($arrayFilasCorreo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasCorreo as $array){
                                if($i == count($arrayFilasCorreo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen el primer correo inválido</p> <br>";
                        }

                    }

                    if($num_cols > 9){

                        $count21 = 1;
                        $contadorCorreo = 0;
                        $arrayFilasCorreo = array();

                        foreach ($xls->rows() as $campo) {
                            if($count21 > 1 && !filter_var(trim($campo[9]), FILTER_VALIDATE_EMAIL) && trim($campo[9]) != ''){
                                $arrayFilasCorreo[$contadorCorreo] = $count21;
                                ++$contadorCorreo;
                            }
                            ++$count21;
                        }

                        if(!empty($arrayFilasCorreo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasCorreo as $array){
                                if($i == count($arrayFilasCorreo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen el segundo correo inválido</p> <br>";
                        }

                    }

                    if($num_cols > 10){

                        $count1 = 1;
                        $contadorTelefono = 0;
                        $arrayFilasTelefono =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[10]) != '' && (!ctype_digit(trim($campo[10])) || strlen(trim($campo[10])) > 10)){
                                $arrayFilasTelefono[$contadorTelefono] = $count1;
                                ++$contadorTelefono;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTelefono)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de teléfono fijo de las filas: ";
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

                    if($num_cols > 11){

                        $count1 = 1;
                        $contadorTelefono = 0;
                        $arrayFilasTelefono =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[11]) != '' && (!ctype_digit(trim($campo[11])) || strlen(trim($campo[11])) > 10)){
                                $arrayFilasTelefono[$contadorTelefono] = $count1;
                                ++$contadorTelefono;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTelefono)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de teléfono móvil de las filas: ";
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

                    if($num_cols > 13){
                        
                        $count23 = 1;
                        $contadorNoExteriorInvalido = 0;
                        $arrayFilasNoExteriorInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && strlen(trim($campo[13])) > 30){
                                $arrayFilasNoExteriorInvalido[$contadorNoExteriorInvalido] = $count23;
                                ++$contadorNoExteriorInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasNoExteriorInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasNoExteriorInvalido as $array){
                                if($i == count($arrayFilasNoExteriorInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un número exterior muy largo</p> <br>";
                        }

                    }

                    if($num_cols > 14){

                        $count23 = 1;
                        $contadorNoInteriorInvalido = 0;
                        $arrayFilasNoInteriorInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && strlen(trim($campo[14])) > 30){
                                $arrayFilasNoInteriorInvalido[$contadorNoInteriorInvalido] = $count23;
                                ++$contadorNoInteriorInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasNoInteriorInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasNoInteriorInvalido as $array){
                                if($i == count($arrayFilasNoInteriorInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un número interior muy largo</p> <br>";
                        }

                    }

                    if($num_cols > 17){

                        $count8 = 1;
                        $contadorEstado = 0;
                        $arrayFilasEstado = array();
    
                        foreach ($xls->rows() as $campo) {
                            if($count8 > 1 && trim($campo[17]) == ''){
                                $arrayFilasEstado[$contadorEstado] = $count8;
                                ++$contadorEstado;
                            }
                            ++$count8;
                        }
    
                        if(!empty($arrayFilasEstado)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasEstado as $array){
                                if($i == count($arrayFilasEstado)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen estado</p> <br>";
                        }

                        $count20 = 1;
                        $indexEstado = 0;
                        $contadorEstadoBD = 0;
                        $arrayFilasEstadoBD =  array();
                        $arrayEstadoBD = array();
                        $datosEstado = estados();
                        foreach($datosEstado as $estados){
                            $arrayEstadoBD[$indexEstado] = $estados['Estado'];
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

                    if($num_cols > 19){

                        $count9 = 1;
                        $contadorCP = 0;
                        $arrayFilasCP = array();

                        foreach ($xls->rows() as $campo) {
                            if($count9 > 1 && trim($campo[19]) == ''){
                                $arrayFilasCP[$contadorCP] = $count9;
                                ++$contadorCP;
                            }
                            ++$count9;
                        }

                        if(!empty($arrayFilasCP)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasCP as $array){
                                if($i == count($arrayFilasCP)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen código postal</p> <br>";
                        }
                        
                        $count23 = 1;
                        $contadorCPInvalido = 0;
                        $arrayFilasCPInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && !preg_match('/(^([0-9]{5,5})|^)$/', trim($campo[19]))){
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

                    if($num_cols > 21){

                        $count7 = 1;
                        $contadorPais = 0;
                        $arrayFilasPais = array();

                        foreach ($xls->rows() as $campo) {
                            if($count7 > 1 && trim($campo[21]) == ''){
                                $arrayFilasPais[$contadorPais] = $count7;
                                ++$contadorPais;
                            }
                            ++$count7;
                        }

                        if(!empty($arrayFilasPais)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasPais as $array){
                                if($i == count($arrayFilasPais)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen pais</p> <br>";
                        }

                        $count19 = 1;
                        $indexPais = 0;
                        $contadorPaisBD = 0;
                        $arrayFilasPaisBD =  array();
                        $arrayPaisBD = array();
                        $datosPais = paises();
                        foreach($datosPais as $paises){
                            $arrayPaisBD[$indexPais] = remove_accents($paises['Pais']);
                            ++$indexPais;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count19 > 1 && !in_array(strtolower(remove_accents(trim($campo[21]))), array_map('strtolower', $arrayPaisBD)) && trim($campo[21]) != ''){
                                $arrayFilasPaisBD[$contadorPaisBD] = $count19;
                                ++$contadorPaisBD;
                            }
                            ++$count19;
                        }
                        
                        if(!empty($arrayFilasPaisBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los paises de las filas: <br>";
                            foreach($arrayFilasPaisBD as $array){
                                if($i == count($arrayFilasPaisBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
                        }

                    }

                } else {
                    echo "error" . SimpleXLS::parseError();
                }
                

            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../../../lib/SimpleXLS/SimpleXLSX.php';

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
                    
                    if($num_cols < 21){
                        echo "<p style='font-size: 15px'>Formato incompleto</p>";
                    }

                    if($num_cols > 0){

                        $count1 = 1;
                        $contadorEstatus = 0;
                        $arrayFilasEstatus =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[0]) == ''){
                                $arrayFilasEstatus[$contadorEstatus] = $count1;
                                ++$contadorEstatus;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasEstatus)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasEstatus as $array){
                                if($i == count($arrayFilasEstatus)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen indicado el estatus</p> <br>";
                        }

                        $count2 = 1;
                        $contadorEstatus = 0;
                        $arrayEstatusInv = array();
                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && strtolower(trim($campo[0])) != 'activo' && strtolower(trim($campo[0])) != 'inactivo'){
                                $arrayFilasEstatus[$contadorEstatus] = $count2;
                                ++$contadorEstatus;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayEstatusInv)){
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            for ($i=0; $i < count($arrayEstatusInv) ; $i++) { 
                                if($i == count($arrayEstatusInv)){
                                    echo $arrayEstatusInv[$i];
                                }else {
                                    echo $arrayEstatusInv[$i] . ", ";
                                }
                            }
                            echo " tienen indicado un estatus inválido</p> <br><br>";
                        }

                    }

                    if($num_cols > 1){

                        $count1 = 1;
                        $contadorCredito = 0;
                        $arrayFilasCredito =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[1]) == ''){
                                $arrayFilasCredito[$contadorCredito] = $count1;
                                ++$contadorCredito;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasCredito)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasCredito as $array){
                                if($i == count($arrayFilasCredito)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen indicado el crédito</p> <br>";
                        }

                        $count2 = 1;
                        $contadorCredito = 0;
                        $arrayCreditoInv = array();
                        foreach ($xls->rows() as $campo) {
                            //Se divide la cadena en un arreglo para saber si el primer caracter es un dígito
                            $credito = str_split(trim($campo[1]), 1);
                            if($count2 > 1 && strtolower(trim($campo[1])) != 'contado' && !ctype_digit($credito[0])){
                                $arrayFilasCredito[$contadorCredito] = $count2;
                                ++$contadorCredito;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayCreditoInv)){
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            for ($i=0; $i < count($arrayCreditoInv) ; $i++) { 
                                if($i == count($arrayCreditoInv)){
                                    echo $arrayCreditoInv[$i];
                                }else {
                                    echo $arrayCreditoInv[$i] . ", ";
                                }
                            }
                            echo " tienen indicado un crédito inválido</p> <br><br>";
                        }

                    }

                    if($num_cols > 2){

                        $count1 = 1;
                        $contadorTipoPersona = 0;
                        $arrayFilasTipoPersona =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[2]) == ''){
                                $arrayFilasTipoPersona[$contadorTipoPersona] = $count1;
                                ++$contadorTipoPersona;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTipoPersona)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasTipoPersona as $array){
                                if($i == count($arrayFilasTipoPersona)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen tipo de persona</p> <br>";
                        }

                        $count2 = 1;
                        $contadorTipoPersona = 0;
                        $arrayTipoPersonaInv = array();
                        foreach ($xls->rows() as $campo) {
                            if($count2 > 1 && strtolower(trim($campo[2])) != 'física' && strtolower(trim($campo[2])) != 'fisica' && strtolower(trim($campo[2])) != 'moral'){
                                $arrayFilasTipoPersona[$contadorTipoPersona] = $count2;
                                ++$contadorTipoPersona;
                            }
                            ++$count2;
                        }

                        if(!empty($arrayTipoPersonaInv)){
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            for ($i=0; $i < count($arrayTipoPersonaInv) ; $i++) { 
                                if($i == count($arrayTipoPersonaInv)){
                                    echo $arrayTipoPersonaInv[$i];
                                }else {
                                    echo $arrayTipoPersonaInv[$i] . ", ";
                                }
                            }
                            echo " tienen un tipo de persona inválido</p> <br><br>";
                        }

                    }

                    if($num_cols > 3){

                        $count6 = 1;
                        $contadorRFC = 0;
                        $arrayFilasRFC = array();

                        foreach ($xls->rows() as $campo) {
                            if($count6 > 1 && trim($campo[3]) == ''){
                                $arrayFilasRFC[$contadorRFC] = $count6;
                                ++$contadorRFC;
                            }
                            ++$count6;
                        }

                        if(!empty($arrayFilasRFC)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasRFC as $array){
                                if($i == count($arrayFilasRFC)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen RFC</p> <br>";
                        }

                        $count14 = 1;
                        $count15 = 0;
                        $arrayRFC = array();

                        foreach ($xls->rows() as $campo) {
                            if($count14 > 1 && trim($campo[3]) != ''){
                                $arrayRFC[$count15] = trim($campo[3]);
                                ++$count15;
                            }
                            ++$count14;
                        }
                        
                        $arrayRFCDuplicado = array();

                        if(count(array_unique(array_map('strtoupper', $arrayRFC))) != count($arrayRFC)){

                            $contadorRFCDuplicado = 0;
                            
                            foreach(array_count_values(array_map('strtoupper', $arrayRFC)) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayRFCDuplicado[$contadorRFCDuplicado] = $arrayCount;
                                    ++$contadorRFCDuplicado;
                                }
                            }
                            
                        }

                        if(!empty($arrayRFCDuplicado)){
                            echo "<p style='font-size: 15px'>Los RFCs: <br>";
                            for ($i=0; $i < count($arrayRFCDuplicado) ; $i++) { 
                                if($i == count($arrayRFCDuplicado)){
                                    echo $arrayRFCDuplicado[$i];
                                }else {
                                    echo $arrayRFCDuplicado[$i] . ", ";
                                }
                            }
                            echo " están duplicados</p> <br><br>";
                        }

                        $count18 = 1;
                        $indexRFC = 0;
                        $contadorRFCBD = 0;
                        $arrayFilasRFCBD =  array();
                        $arrayRFCBD = array();
                        $datosRFC = rfc();
                        foreach($datosRFC as $rfcs){
                            $arrayRFCBD[$indexRFC] = $rfcs['RFC'];
                            ++$indexRFC;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count18 > 1 && in_array(strtoupper(trim($campo[3])), array_map('strtoupper', $arrayRFCBD))){
                                $arrayFilasRFCBD[$contadorRFCBD] = $count18;
                                ++$contadorRFCBD;
                            }
                            ++$count18;
                        }
                        
                        if(!empty($arrayFilasRFCBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los RFCs de las filas: <br>";
                            foreach($arrayFilasRFCBD as $array){
                                if($i == count($arrayFilasRFCBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                        $count22 = 1;
                        $contadorRFCInvalido = 0;
                        $arrayFilasRFCInvalido = array();
                        $aceptarGenerico = true;

                        foreach ($xls->rows() as $campo) {
                            if($count22 > 1 && !preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[3])))){//Coincide con el formato general del regex?
                                $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                ++$contadorRFCInvalido;
                            }elseif ($count22 > 1 && preg_match('/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/', strtoupper(trim($campo[3])))) {
                                $rfc = strtoupper(trim($campo[3]));
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
                                $strinArrayRfc = implode("", $arrayRfc);
                                if(
                                    $digitoVerificador != $digitoEsperado &&
                                    (!$aceptarGenerico || $strinArrayRfc . $digitoVerificador != 'XAXX010101000')
                                ){
                                    $arrayFilasRFCInvalido[$contadorRFCInvalido] = $count22;
                                    ++$contadorRFCInvalido;
                                }
                                else if(
                                    !$aceptarGenerico &&
                                    $strinArrayRfc . $digitoVerificador == 'XEXX010101000'
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

                    if($num_cols > 4){

                        $count1 = 1;
                        $contadorRazonSocial = 0;
                        $arrayFilasRazonSocial =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[4]) == ''){
                                $arrayFilasRazonSocial[$contadorRazonSocial] = $count1;
                                ++$contadorRazonSocial;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasRazonSocial)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasRazonSocial as $array){
                                if($i == count($arrayFilasRazonSocial)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen razón social</p> <br>";
                        }

                        $count10 = 1;
                        $count11 = 0;
                        $arrayRazonSocial = array();

                        foreach ($xls->rows() as $campo) {
                            if($count10 > 1 && trim($campo[4]) != ''){
                                $arrayRazonSocial[$count11] = trim($campo[4]);
                                ++$count11;
                            }
                            ++$count10;
                        }
                        
                        $arrayRazonSocialDuplicado = array();

                        if(count(array_unique(array_map('strtolower', $arrayRazonSocial))) != count($arrayRazonSocial)){

                            $contadorRazonSocialDuplicado = 0;
                            
                            foreach(array_count_values(array_map('strtolower', $arrayRazonSocial)) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayRazonSocialDuplicado[$contadorRazonSocialDuplicado] = $arrayCount;
                                    ++$contadorRazonSocialDuplicado;
                                }
                            }
                            
                        }

                        if(!empty($arrayRazonSocialDuplicado)){
                            echo "<p style='font-size: 15px'>Las razones sociales: <br>";
                            for ($i=0; $i < count($arrayRazonSocialDuplicado) ; $i++) { 
                                if($i == count($arrayRazonSocialDuplicado)){
                                    echo $arrayRazonSocialDuplicado[$i];
                                }else {
                                    echo $arrayRazonSocialDuplicado[$i] . ", ";
                                }
                            }
                            echo " están duplicadas</p> <br><br>";
                        }

                        $count16 = 1;
                        $indexRazonSocial = 0;
                        $contadorRazonSocialBD = 0;
                        $arrayFilasRazonSocialBD =  array();
                        $arrayRazonSocialBD = array();
                        $datosRazonSocial = razonesSociales();
                        foreach($datosRazonSocial as $razonesSociales){
                            $arrayRazonSocialBD[$indexRazonSocial] = $razonesSociales['Razon_Social'];
                            ++$indexRazonSocial;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count16 > 1 && in_array(strtolower(trim($campo[4])), array_map('strtolower', $arrayRazonSocialBD))){
                                $arrayFilasRazonSocialBD[$contadorRazonSocialBD] = $count16;
                                ++$contadorRazonSocialBD;
                            }
                            ++$count16;
                        }
                        
                        if(!empty($arrayFilasRazonSocialBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Las razones sociales de las filas: <br>";
                            foreach($arrayFilasRazonSocialBD as $array){
                                if($i == count($arrayFilasRazonSocialBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                    }

                    if($num_cols > 5){

                        $count1 = 1;
                        $contadorNombreComercial = 0;
                        $arrayFilasNombreComercial =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[5]) == ''){
                                $arrayFilasNombreComercial[$contadorNombreComercial] = $count1;
                                ++$contadorNombreComercial;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasNombreComercial)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasNombreComercial as $array){
                                if($i == count($arrayFilasNombreComercial)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen nombre comercial</p> <br>";
                        }

                        $count10 = 1;
                        $count11 = 0;
                        $arrayNombreComercial = array();

                        foreach ($xls->rows() as $campo) {
                            if($count10 > 1 && trim($campo[5]) != ''){
                                $arrayNombreComercial[$count11] = trim($campo[5]);
                                ++$count11;
                            }
                            ++$count10;
                        }
                        
                        $arrayNombreComercialDuplicado = array();

                        if(count(array_unique(array_map('strtolower', $arrayNombreComercial))) != count($arrayNombreComercial)){

                            $contadorNombreComercialDuplicado = 0;
                            
                            foreach(array_count_values(array_map('strtolower', $arrayNombreComercial)) as $arrayCount => $n){
                                if($n > 1){
                                    $arrayNombreComercialDuplicado[$contadorNombreComercialDuplicado] = $arrayCount;
                                    ++$contadorNombreComercialDuplicado;
                                }
                            }
                            
                        }

                        if(!empty($arrayNombreComercialDuplicado)){
                            echo "<p style='font-size: 15px'>Los nombres comerciales: <br>";
                            for ($i=0; $i < count($arrayNombreComercialDuplicado) ; $i++) { 
                                if($i == count($arrayNombreComercialDuplicado)){
                                    echo $arrayNombreComercialDuplicado[$i];
                                }else {
                                    echo $arrayNombreComercialDuplicado[$i] . ", ";
                                }
                            }
                            echo " están duplicados</p> <br><br>";
                        }

                        $count16 = 1;
                        $indexNombreComercial = 0;
                        $contadorNombreComercialBD = 0;
                        $arrayFilasNombreComercialBD =  array();
                        $arrayNombreComercialBD = array();
                        $datosNombreComercial = nombresComerciales();
                        foreach($datosNombreComercial as $nombresComerciales){
                            $arrayNombreComercialBD[$indexNombreComercial] = remove_accents($nombresComerciales['NombreComercial']);
                            ++$indexNombreComercial;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count16 > 1 && in_array(strtolower(remove_accents(trim($campo[5]))), array_map('strtolower', $arrayNombreComercialBD))){
                                $arrayFilasNombreComercialBD[$contadorNombreComercialBD] = $count16;
                                ++$contadorNombreComercialBD;
                            }
                            ++$count16;
                        }
                        
                        if(!empty($arrayFilasNombreComercialBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los nombres comerciales de las filas: <br>";
                            foreach($arrayFilasNombreComercialBD as $array){
                                if($i == count($arrayFilasNombreComercialBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " ya existen </p> <br>";
                        }

                    }

                    if($num_cols > 8){

                        $count4 = 1;
                        $contadorEmail = 0;
                        $arrayFilasEmail = array();
    
                        foreach ($xls->rows() as $campo) {
                            if($count4 > 1 && trim($campo[8]) == ''){
                                $arrayFilasEmail[$contadorEmail] = $count4;
                                ++$contadorEmail;
                            }
                            ++$count4;
                        }
    
                        if(!empty($arrayFilasEmail)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasEmail as $array){
                                if($i == count($arrayFilasEmail)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen el primer correo electrónico</p> <br>";
                        }

                        $count21 = 1;
                        $contadorCorreo = 0;
                        $arrayFilasCorreo = array();

                        foreach ($xls->rows() as $campo) {
                            if($count21 > 1 && !filter_var(trim($campo[8]), FILTER_VALIDATE_EMAIL) && trim($campo[8]) != ''){
                                $arrayFilasCorreo[$contadorCorreo] = $count21;
                                ++$contadorCorreo;
                            }
                            ++$count21;
                        }

                        if(!empty($arrayFilasCorreo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasCorreo as $array){
                                if($i == count($arrayFilasCorreo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen el primer correo inválido</p> <br>";
                        }

                    }

                    if($num_cols > 9){

                        $count21 = 1;
                        $contadorCorreo = 0;
                        $arrayFilasCorreo = array();

                        foreach ($xls->rows() as $campo) {
                            if($count21 > 1 && !filter_var(trim($campo[9]), FILTER_VALIDATE_EMAIL) && trim($campo[9]) != ''){
                                $arrayFilasCorreo[$contadorCorreo] = $count21;
                                ++$contadorCorreo;
                            }
                            ++$count21;
                        }

                        if(!empty($arrayFilasCorreo)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasCorreo as $array){
                                if($i == count($arrayFilasCorreo)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen el segundo correo inválido</p> <br>";
                        }

                    }

                    if($num_cols > 10){

                        $count1 = 1;
                        $contadorTelefono = 0;
                        $arrayFilasTelefono =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[10]) != '' && (!ctype_digit(trim($campo[10])) || strlen(trim($campo[10])) > 10)){
                                $arrayFilasTelefono[$contadorTelefono] = $count1;
                                ++$contadorTelefono;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTelefono)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de teléfono fijo de las filas: ";
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

                    if($num_cols > 11){

                        $count1 = 1;
                        $contadorTelefono = 0;
                        $arrayFilasTelefono =  array();
                        
                        foreach ($xls->rows() as $campo) {
                            if($count1 > 1 && trim($campo[11]) != '' && (!ctype_digit(trim($campo[11])) || strlen(trim($campo[11])) > 10)){
                                $arrayFilasTelefono[$contadorTelefono] = $count1;
                                ++$contadorTelefono;
                            }
                            ++$count1;
                        }

                        if(!empty($arrayFilasTelefono)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los números de teléfono móvil de las filas: ";
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

                    if($num_cols > 13){
                        
                        $count23 = 1;
                        $contadorNoExteriorInvalido = 0;
                        $arrayFilasNoExteriorInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && strlen(trim($campo[13])) > 30){
                                $arrayFilasNoExteriorInvalido[$contadorNoExteriorInvalido] = $count23;
                                ++$contadorNoExteriorInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasNoExteriorInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasNoExteriorInvalido as $array){
                                if($i == count($arrayFilasNoExteriorInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un número exterior muy largo</p> <br>";
                        }

                    }

                    if($num_cols > 14){

                        $count23 = 1;
                        $contadorNoInteriorInvalido = 0;
                        $arrayFilasNoInteriorInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && strlen(trim($campo[14])) > 30){
                                $arrayFilasNoInteriorInvalido[$contadorNoInteriorInvalido] = $count23;
                                ++$contadorNoInteriorInvalido;
                            }
                            ++$count23;
                        }

                        if(!empty($arrayFilasNoInteriorInvalido)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: ";
                            foreach($arrayFilasNoInteriorInvalido as $array){
                                if($i == count($arrayFilasNoInteriorInvalido)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " tienen un número interior muy largo</p> <br>";
                        }

                    }

                    if($num_cols > 17){

                        $count8 = 1;
                        $contadorEstado = 0;
                        $arrayFilasEstado = array();
    
                        foreach ($xls->rows() as $campo) {
                            if($count8 > 1 && trim($campo[17]) == ''){
                                $arrayFilasEstado[$contadorEstado] = $count8;
                                ++$contadorEstado;
                            }
                            ++$count8;
                        }
    
                        if(!empty($arrayFilasEstado)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasEstado as $array){
                                if($i == count($arrayFilasEstado)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen estado</p> <br>";
                        }

                        $count20 = 1;
                        $indexEstado = 0;
                        $contadorEstadoBD = 0;
                        $arrayFilasEstadoBD =  array();
                        $arrayEstadoBD = array();
                        $datosEstado = estados();
                        foreach($datosEstado as $estados){
                            $arrayEstadoBD[$indexEstado] = $estados['Estado'];
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

                    if($num_cols > 19){

                        $count9 = 1;
                        $contadorCP = 0;
                        $arrayFilasCP = array();

                        foreach ($xls->rows() as $campo) {
                            if($count9 > 1 && trim($campo[19]) == ''){
                                $arrayFilasCP[$contadorCP] = $count9;
                                ++$contadorCP;
                            }
                            ++$count9;
                        }

                        if(!empty($arrayFilasCP)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasCP as $array){
                                if($i == count($arrayFilasCP)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen código postal</p> <br>";
                        }
                        
                        $count23 = 1;
                        $contadorCPInvalido = 0;
                        $arrayFilasCPInvalido = array();

                        foreach ($xls->rows() as $campo) {
                            if($count23 > 1 && !preg_match('/(^([0-9]{5,5})|^)$/', trim($campo[19]))){
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

                    if($num_cols > 21){

                        $count7 = 1;
                        $contadorPais = 0;
                        $arrayFilasPais = array();

                        foreach ($xls->rows() as $campo) {
                            if($count7 > 1 && trim($campo[21]) == ''){
                                $arrayFilasPais[$contadorPais] = $count7;
                                ++$contadorPais;
                            }
                            ++$count7;
                        }

                        if(!empty($arrayFilasPais)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los proveedores de las filas: <br>";
                            foreach($arrayFilasPais as $array){
                                if($i == count($arrayFilasPais)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no tienen pais</p> <br>";
                        }

                        $count19 = 1;
                        $indexPais = 0;
                        $contadorPaisBD = 0;
                        $arrayFilasPaisBD =  array();
                        $arrayPaisBD = array();
                        $datosPais = paises();
                        foreach($datosPais as $paises){
                            $arrayPaisBD[$indexPais] = remove_accents($paises['Pais']);
                            ++$indexPais;
                        }
                        
                        foreach ($xls->rows() as $campo) {
                            if($count19 > 1 && !in_array(strtolower(remove_accents(trim($campo[21]))), array_map('strtolower', $arrayPaisBD)) && trim($campo[21]) != ''){
                                $arrayFilasPaisBD[$contadorPaisBD] = $count19;
                                ++$contadorPaisBD;
                            }
                            ++$count19;
                        }
                        
                        if(!empty($arrayFilasPaisBD)){
                            $i = 1;
                            echo "<p style='font-size: 15px'>Los paises de las filas: <br>";
                            foreach($arrayFilasPaisBD as $array){
                                if($i == count($arrayFilasPaisBD)){
                                    echo $array;
                                }else {
                                    echo $array . ", ";
                                }
                                ++$i;
                            }
                            echo " no existen </p> <br>";
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

function nombresComerciales(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('call spc_Nombre_Comercial_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function razonesSociales(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('SELECT dfp.Razon_Social 
                            FROM  domicilio_fiscal_proveedor dfp 
                            INNER JOIN proveedores p 
                            ON dfp.FKProveedor = p.PKProveedor 
                            WHERE p.empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}
function paises(){
    $con = new conectar();
    $db = $con->getDb();
    try {
        $query = sprintf('SELECT Pais FROM paises');
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
function rfc(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('SELECT RFC FROM domicilio_fiscal_proveedor dfp INNER JOIN proveedores p ON dfp.FKProveedor =p.PKProveedor WHERE p.empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
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