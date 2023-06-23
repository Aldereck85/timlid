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
                //echo json_encode($xls->rows());

                $PKEmpresa = $_SESSION["IDEmpresa"];

                $formatoIncorrecto = 0;

                foreach ($xls->rows() as $campo) {
                    if (trim($campo[0]) == '' && trim($campo[1]) == '' && trim($campo[2]) == '' && trim($campo[3]) == '' && trim($campo[4]) == '' && trim($campo[5]) == '') {
                        echo "<p style='font-size: 15px'>Formato incorrecto</p>";
                        $formatoIncorrecto = 1;
                    }
                }

                if($formatoIncorrecto == 0){
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

                    $count1 = 0;
                    $contadorCantidadesInvalid = 0;
                    $arrayClavesCantidadInvalid =  array();
                    
                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                            if($count1 > 1 && trim($campo[3]) != '' && is_integer($campo[3]) == false){
                                    $arrayClavesCantidadInvalid[$contadorCantidadesInvalid] = trim($campo[0]);
                                    ++$contadorCantidadesInvalid;
                            } 
                            break;
                            case [1, 0]:
                                if($count1 > 0 && trim($campo[3]) != '' && is_integer($campo[3]) == false){
                                    $arrayClavesCantidadInvalid[$contadorCantidadesInvalid] = trim($campo[0]);
                                    ++$contadorCantidadesInvalid;
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != '' && is_integer($campo[3]) == false){
                                    $arrayClavesCantidadInvalid[$contadorCantidadesInvalid] = trim($campo[0]);
                                    ++$contadorCantidadesInvalid;
                                }
                            break;
                        }
                        ++$count1;
                    }

                    $arrayClavesUnicasCantidadInvalid = array_unique($arrayClavesCantidadInvalid);

                    if(!empty($arrayClavesUnicasCantidadInvalid)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicasCantidadInvalid as $array){
                            echo $array . "<br>";
                        }
                        echo "tienen cantidad inválida</p> <br><br>";
                    }

                    $count11 = 0;
                    $contadorCantidades = 0;
                    $arrayClavesCantidad =  array();
                    
                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                            if($count11 > 1 && trim($campo[3]) == ''){
                                    $arrayClavesCantidad[$contadorCantidades] = trim($campo[0]);
                                    ++$contadorCantidades;
                            } 
                            break;
                            case [1, 0]:
                                if($count11 > 0 && trim($campo[3]) == ''){
                                    $arrayClavesCantidad[$contadorCantidades] = trim($campo[0]);
                                    ++$contadorCantidades;
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) == ''){
                                    $arrayClavesCantidad[$contadorCantidades] = trim($campo[0]);
                                    ++$contadorCantidades;
                                }
                            break;
                        }
                        ++$count11;
                    }

                    $arrayClavesUnicasCantidad = array_unique($arrayClavesCantidad);

                    if(!empty($arrayClavesUnicasCantidad)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicasCantidad as $array){
                            echo $array . "<br>";
                        }
                        echo "no tienen cantidad</p> <br><br>";
                    }

                    $count2 = 0;
                    $contadorClaves = 0;
                    $arrayClaves = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count2 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        //if (trim($campo[0]) == $data['ClaveInterna'] && $data['serie'] == 1 && trim($campo[4]) == '') {
                                        /* if (trim($campo[0]) == $data['ClaveInterna']  && trim($campo[4]) == '') {
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else  */if(trim($campo[0]) == $data['ClaveInterna'] && $data['lote'] == 1 && trim($campo[4]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && trim($campo[5]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        } 
                                    }
                                }
                            break;
                            case [1, 0]:
                                if($count2 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        //if (trim($campo[0]) == $data['ClaveInterna'] && $data['serie'] == 1 && trim($campo[4]) == '') {
                                        /* if (trim($campo[0]) == $data['ClaveInterna']  && trim($campo[4]) == '') {
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else  */if(trim($campo[0]) == $data['ClaveInterna'] && $data['lote'] == 1 && trim($campo[4]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && trim($campo[5]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }  
                                    }
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        //if (trim($campo[0]) == $data['ClaveInterna'] && $data['serie'] == 1 && trim($campo[4]) == '') {
                                        /* if (trim($campo[0]) == $data['ClaveInterna'] && trim($campo[4]) == '') {
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else  */if(trim($campo[0]) == $data['ClaveInterna'] && $data['lote'] == 1 && trim($campo[4]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && trim($campo[5]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        } 
                                    }
                                }
                            break;
                        }
                        ++$count2;                    
                    }

                    $arrayClavesUnicas = array_unique($arrayClaves);

                    if(!empty($arrayClavesUnicas)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicas as $array){
                            echo $array . "<br>";
                        }
                        echo "información requerida</p> <br><br>";
                    }

                    $count3 = 0;
                    $count4 = 0;
                    $array = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count3 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            //if($data['serie'] == 1 && trim($campo[4]) != ''){
                                            /* if(trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            }else */ if($data['lote'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            //}else if($data['serie'] == 0 && $data['lote'] == 0 && $data['fecha_caducidad'] == 0){
                                            }else if($data['lote'] == 0 /* && $data['fecha_caducidad'] == 0 */){
                                                $array[$count4] = trim($campo[0]) . "_";
                                            }
                                        } 
                                    }
                                }
                            break;
                            case [1, 0]:
                                if($count3 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            //if($data['serie'] == 1 && trim($campo[4]) != ''){
                                            /* if(trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            }else */ if($data['lote'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            //}else if($data['serie'] == 0 && $data['lote'] == 0 && $data['fecha_caducidad'] == 0){
                                            }else if($data['lote'] == 0 /* && $data['fecha_caducidad'] == 0 */){
                                                $array[$count4] = trim($campo[0]) . "_";
                                            }
                                        } 
                                    }
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            //if($data['serie'] == 1 && trim($campo[4]) != ''){
                                            /* if(trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            }else */ if($data['lote'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            //}else if($data['serie'] == 0 && $data['lote'] == 0 && $data['fecha_caducidad'] == 0){
                                            }else if($data['lote'] == 0 /* && $data['fecha_caducidad'] == 0 */){
                                                $array[$count4] = trim($campo[0]) . "_";
                                            }
                                        } 
                                    }
                                }
                            break;
                        }
                        ++$count3;
                        ++$count4;                    
                    }
                    
                    if(count(array_unique($array)) != count($array)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach(array_count_values($array) as $arrayCount => $n){
                            if($n > 1){
                                echo substr($arrayCount, 0, strpos($arrayCount, "_")) . "<br>";
                            }
                        }
                        echo "duplicados</p> <br><br>";
                    }

                    $count5 = 0;
                    $count6 = 0;
                    $arrayClavesFecha = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count5 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && date_parse(trim($campo[5]))["year"] == false && trim($campo[5]) != '') {
                                            $arrayClavesFecha[$count6] = trim($campo[0]);
                                            ++$count6;
                                        } 
                                    }
                                }
                            break;
                            case [1, 0]:
                                if($count5 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && date_parse(trim($campo[5]))["year"] == false && trim($campo[5]) != '') {
                                            $arrayClavesFecha[$count6] = trim($campo[0]);
                                            ++$count6;
                                        } 
                                    }
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && date_parse(trim($campo[5]))["year"] == false && trim($campo[5]) != '') {
                                            $arrayClavesFecha[$count6] = trim($campo[0]);
                                            ++$count6;
                                        }  
                                    }
                                }
                            break;
                        }
                        ++$count5;                    
                    }

                    $arrayClavesUnicasFecha = array_unique($arrayClavesFecha);

                    if(!empty($arrayClavesUnicasFecha)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicasFecha as $arrayFecha){
                            echo $arrayFecha . "<br>";
                        }
                        echo "tienen fecha inválida</p> <br><br>";
                    }

                    $count7 = 0;
                    $count8 = 0;
                    $claveNula = 1;
                    $arrayClavesNulas = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count7 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            $claveNula = 0;
                                        } 
                                    }
                                    if($claveNula == 1){
                                        $arrayClavesNulas[$count8] = trim($campo[0]);
                                        ++$count8;
                                    }
                                    $claveNula = 1;
                                }
                            break;
                            case [1, 0]:
                                if($count7 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            $claveNula = 0;
                                        } 
                                    }
                                    if($claveNula == 1){
                                        $arrayClavesNulas[$count8] = trim($campo[0]);
                                        ++$count8;
                                    }
                                    $claveNula = 1;
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            $claveNula = 0;
                                        } 
                                    }
                                    if($claveNula == 1){
                                        $arrayClavesNulas[$count8] = trim($campo[0]);
                                        ++$count8;
                                    }
                                    $claveNula = 1;
                                    
                                }
                            break;
                        }
                        ++$count7;                    
                    }

                    $arrayClavesUnicasNulas = array_unique($arrayClavesNulas);

                    if(!empty($arrayClavesUnicasNulas)){
                        echo "<p style='font-size: 15px'>Las claves: <br>";
                        foreach($arrayClavesUnicasNulas as $arrayNula){
                            echo $arrayNula . "<br>";
                        }
                        echo "no existen</p> <br><br>";
                    }
                }

            } elseif ($excelfile['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $extension[1] == "xlsx") {
                include '../../../../lib/SimpleXLS/SimpleXLSX.php';

                $xls = SimpleXLSX::parse($_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/' . $excelfile['name']);
                //echo json_encode($xls->rows());

                $PKEmpresa = $_SESSION["IDEmpresa"];

                $formatoIncorrecto = 0;

                foreach ($xls->rows() as $campo) {
                    if (trim($campo[0]) == '' && trim($campo[1]) == '' && trim($campo[2]) == '' && trim($campo[3]) == '' && trim($campo[4]) == '' && trim($campo[5]) == '') {
                        echo "<p style='font-size: 15px'>Formato incorrecto</p>";
                        $formatoIncorrecto = 1;
                    }
                }

                if($formatoIncorrecto == 0){
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

                    $count1 = 0;
                    $contadorCantidadesInvalid = 0;
                    $arrayClavesCantidadInvalid =  array();
                    
                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                            if($count1 > 1 && trim($campo[3]) != '' && is_integer($campo[3]) == false){
                                    $arrayClavesCantidadInvalid[$contadorCantidadesInvalid] = trim($campo[0]);
                                    ++$contadorCantidadesInvalid;
                            } 
                            break;
                            case [1, 0]:
                                if($count1 > 0 && trim($campo[3]) != '' && is_integer($campo[3]) == false){
                                    $arrayClavesCantidadInvalid[$contadorCantidadesInvalid] = trim($campo[0]);
                                    ++$contadorCantidadesInvalid;
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != '' && is_integer($campo[3]) == false){
                                    $arrayClavesCantidadInvalid[$contadorCantidadesInvalid] = trim($campo[0]);
                                    ++$contadorCantidadesInvalid;
                                }
                            break;
                        }
                        ++$count1;
                    }

                    $arrayClavesUnicasCantidadInvalid = array_unique($arrayClavesCantidadInvalid);

                    if(!empty($arrayClavesUnicasCantidadInvalid)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicasCantidadInvalid as $array){
                            echo $array . "<br>";
                        }
                        echo "tienen cantidad inválida</p> <br><br>";
                    }

                    $count11 = 0;
                    $contadorCantidades = 0;
                    $arrayClavesCantidad =  array();
                    
                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                            if($count11 > 1 && trim($campo[3]) == ''){
                                    $arrayClavesCantidad[$contadorCantidades] = trim($campo[0]);
                                    ++$contadorCantidades;
                            } 
                            break;
                            case [1, 0]:
                                if($count11 > 0 && trim($campo[3]) == ''){
                                    $arrayClavesCantidad[$contadorCantidades] = trim($campo[0]);
                                    ++$contadorCantidades;
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) == ''){
                                    $arrayClavesCantidad[$contadorCantidades] = trim($campo[0]);
                                    ++$contadorCantidades;
                                }
                            break;
                        }
                        ++$count11;
                    }

                    $arrayClavesUnicasCantidad = array_unique($arrayClavesCantidad);

                    if(!empty($arrayClavesUnicasCantidad)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicasCantidad as $array){
                            echo $array . "<br>";
                        }
                        echo "no tienen cantidad</p> <br><br>";
                    }

                    $count2 = 0;
                    $contadorClaves = 0;
                    $arrayClaves = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count2 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        //if (trim($campo[0]) == $data['ClaveInterna'] && $data['serie'] == 1 && trim($campo[4]) == '') {
                                        /* if (trim($campo[0]) == $data['ClaveInterna'] && trim($campo[4]) == '') {
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else  */if(trim($campo[0]) == $data['ClaveInterna'] && $data['lote'] == 1 && trim($campo[4]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && trim($campo[5]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        } 
                                    }
                                }
                            break;
                            case [1, 0]:
                                if($count2 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        //if (trim($campo[0]) == $data['ClaveInterna'] && $data['serie'] == 1 && trim($campo[4]) == '') {
                                        /* if (trim($campo[0]) == $data['ClaveInterna'] && trim($campo[4]) == '') {
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else  */if(trim($campo[0]) == $data['ClaveInterna'] && $data['lote'] == 1 && trim($campo[4]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && trim($campo[5]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }  
                                    }
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        /* if (trim($campo[0]) == $data['ClaveInterna'] && $data['serie'] == 1 && trim($campo[4]) == '') {
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else  */if(trim($campo[0]) == $data['ClaveInterna'] && $data['lote'] == 1 && trim($campo[4]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        }else if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && trim($campo[5]) == ''){
                                            $arrayClaves[$contadorClaves] = trim($campo[0]);
                                            ++$contadorClaves;
                                        } 
                                    }
                                }
                            break;
                        }
                        ++$count2;                    
                    }

                    $arrayClavesUnicas = array_unique($arrayClaves);

                    if(!empty($arrayClavesUnicas)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicas as $array){
                            echo $array . "<br>";
                        }
                        echo "información requerida</p> <br><br>";
                    }

                    $count3 = 0;
                    $count4 = 0;
                    $array = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count3 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            /*if($data['serie'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            }else */
                                            if($data['lote'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            //}else if($data['serie'] == 0 && $data['lote'] == 0 && $data['fecha_caducidad'] == 0){
                                            }else if($data['lote'] == 0/*  && $data['fecha_caducidad'] == 0 */){
                                                $array[$count4] = trim($campo[0]) . "_";
                                            }
                                        } 
                                    }
                                }
                            break;
                            case [1, 0]:
                                if($count3 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            /*if($data['serie'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            }else */
                                            if($data['lote'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            //}else if($data['serie'] == 0 && $data['lote'] == 0 && $data['fecha_caducidad'] == 0){
                                            }else if($data['lote'] == 0/*  && $data['fecha_caducidad'] == 0 */){
                                                $array[$count4] = trim($campo[0]) . "_";
                                            }
                                        } 
                                    }
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            /*if($data['serie'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            }else */
                                            if($data['lote'] == 1 && trim($campo[4]) != ''){
                                                $valor = trim($campo[0]) . "_" . trim($campo[4]);
                                                $array[$count4] = $valor;
                                            //}else if($data['serie'] == 0 && $data['lote'] == 0 && $data['fecha_caducidad'] == 0){
                                            }else if($data['lote'] == 0/*  && $data['fecha_caducidad'] == 0 */){
                                                $array[$count4] = trim($campo[0]) . "_";
                                            }
                                        } 
                                    }
                                }
                            break;
                        }
                        ++$count3;
                        ++$count4;                    
                    }
                    
                    if(count(array_unique($array)) != count($array)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach(array_count_values($array) as $arrayCount => $n){
                            if($n > 1){
                                echo substr($arrayCount, 0, strpos($arrayCount, "_")) . "<br>";
                            }
                        }
                        echo "duplicados</p> <br><br>";
                    }

                    $count5 = 0;
                    $count6 = 0;
                    $arrayClavesFecha = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count5 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && date_parse(trim($campo[5]))["year"] == false && trim($campo[5]) != '') {
                                            $arrayClavesFecha[$count6] = trim($campo[0]).$data['fecha_caducidad'];
                                            ++$count6;
                                        } 
                                    }
                                }
                            break;
                            case [1, 0]:
                                if($count5 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && date_parse(trim($campo[5]))["year"] == false && trim($campo[5]) != '') {
                                            $arrayClavesFecha[$count6] = trim($campo[0]);
                                            ++$count6;
                                        } 
                                    }
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if(trim($campo[0]) == $data['ClaveInterna'] && $data['fecha_caducidad'] == 1 && date_parse(trim($campo[5]))["year"] == false && trim($campo[5]) != '') {
                                            $arrayClavesFecha[$count6] = trim($campo[0]);
                                            ++$count6;
                                        }  
                                    }
                                }
                            break;
                        }
                        ++$count5;                    
                    }

                    $arrayClavesUnicasFecha = array_unique($arrayClavesFecha);

                    if(!empty($arrayClavesUnicasFecha)){
                        echo "<p style='font-size: 15px'>Productos con clave: <br>";
                        foreach($arrayClavesUnicasFecha as $arrayFecha){
                            echo $arrayFecha . "<br>";
                        }
                        echo "tienen fecha inválida</p> <br><br>";
                    }

                    $count7 = 0;
                    $count8 = 0;
                    $claveNula = 1;
                    $arrayClavesNulas = array();

                    foreach ($xls->rows() as $campo) {
                        switch([$ignorarFila1, $ignorarFila2]){
                            case [1, 1]:
                                if($count7 > 1 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            $claveNula = 0;
                                        } 
                                    }
                                    if($claveNula == 1){
                                        $arrayClavesNulas[$count8] = trim($campo[0]);
                                        ++$count8;
                                    }
                                    $claveNula = 1;
                                }
                            break;
                            case [1, 0]:
                                if($count7 > 0 && trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            $claveNula = 0;
                                        } 
                                    }
                                    if($claveNula == 1){
                                        $arrayClavesNulas[$count8] = trim($campo[0]);
                                        ++$count8;
                                    }
                                    $claveNula = 1;
                                }
                            break;
                            case [0, 0]:
                                if(trim($campo[3]) != ''){
                                    $datos = validar();
                                    foreach($datos as $data){
                                        if (trim($campo[0]) == $data['ClaveInterna']) {
                                            $claveNula = 0;
                                        } 
                                    }
                                    if($claveNula == 1){
                                        $arrayClavesNulas[$count8] = trim($campo[0]);
                                        ++$count8;
                                    }
                                    $claveNula = 1;
                                    
                                }
                            break;
                        }
                        ++$count7;                    
                    }

                    $arrayClavesUnicasNulas = array_unique($arrayClavesNulas);

                    if(!empty($arrayClavesUnicasNulas)){
                        echo "<p style='font-size: 15px'>Las claves: <br>";
                        foreach($arrayClavesUnicasNulas as $arrayNula){
                            echo $arrayNula . "<br>";
                        }
                        echo "no existen</p> <br><br>";
                    }
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
function validar(){
    $con = new conectar();
    $db = $con->getDb();
    $PKEmpresa = $_SESSION["IDEmpresa"];
    try {
        $query = sprintf('SELECT ClaveInterna, lote, fecha_caducidad FROM productos WHERE empresa_id = ?'); // serie,
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        return $array;
    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
}

unlink($subir_archivo);
