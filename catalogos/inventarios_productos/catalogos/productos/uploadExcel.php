<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', '1');
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
//echo json_encode(['tipo' => $tipo, 'file' => $excelfile['name']]);

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

                    $count0 = 0;
                    $ignorarFila1 = 0;

                    foreach ($xls->rows() as $campo) {
                            
                        if ((trim($campo[0]) == 'Clave interna' || trim($campo[0]) == 'Clave Interna' || trim($campo[0]) == 'clave interna') && $count0 == 0) {
                            $ignorarFila1 = 1;
                        }
                        
                        ++$count0;

                    }

                    $count1 = 0;
                    $num_cols = 0;

                    foreach ($xls->rows() as $r){
                        $num_cols = count($r);
                        break;
                    }

                    if($tipo == 3) {

                        foreach ($xls->rows() as $campo) {

                            if($count1 > 0){


                                $Clave = trim($campo[0]);
                                $Nombre = trim($campo[1]);
                                if($num_cols > 2){
                                    if(trim($campo[2]) != ''){
                                      
                                        $Descripcion = remove_accents(trim($campo[2]));

                                    }else {
                                        $Descripcion = '';
                                    }
                                    
                                }else {
                                    $Descripcion = '';
                                }
                                if($num_cols > 3){
                                    if(trim($campo[3]) != ''){
                                        $CodigoBarras = trim($campo[3]);
                                    }else {
                                        $CodigoBarras = '';
                                    }
                                    
                                }else {
                                    $CodigoBarras = '';
                                }
                                if($num_cols > 4){
                                    if(trim($campo[4]) != ''){
                                        $Categoria = trim($campo[4]);
                                    }else {
                                        $Categoria = 'Sin categoría';
                                    }
                                    
                                }else {
                                    $Categoria = 'Sin categoría';
                                }
                                if($num_cols > 5){
                                    if(trim($campo[5]) != ''){
                                        $Marca = trim($campo[5]);
                                    }else {
                                        $Marca = 'Sin marca';
                                    }
                                    
                                }else {
                                    $Marca = 'Sin marca';
                                }
                                $CostoFabricacion = trim($campo[6]);
                                $TipoMonedaFabricacion = trim($campo[7]);
                                switch($num_cols){
                                    case 8:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 9:
                                        if (trim($campo[8]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[8]);
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 10:
                                        if (trim($campo[8]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[8]);
                                        if (trim($campo[9]) == '') {
                                            $ClaveSATUnidad = '';
                                        }
                                        $ClaveSATUnidad = trim($campo[9]);
                                    break;
                                    default:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                }

                                insertar($Clave, $Nombre, $Descripcion, $CodigoBarras, $Categoria, $Marca, $CostoFabricacion, $TipoMonedaFabricacion, $ClaveSAT, $ClaveSATUnidad, $tipo);
                            }
                            ++$count1;                    
                        }

                    } else {
                        foreach ($xls->rows() as $campo) {

                            if($count1 > 0){

                                $Clave = trim($campo[0]);
                                $Nombre = trim($campo[1]);
                                if($num_cols > 2){
                                    if(trim($campo[2]) != ''){

                                        $Descripcion = remove_accents(trim($campo[2]));

                                    }else {
                                        $Descripcion = '';
                                    }
                                    
                                }else {
                                    $Descripcion = '';
                                }
                                if($num_cols > 3){
                                    if(trim($campo[3]) != ''){
                                        $CodigoBarras = trim($campo[3]);
                                    }else {
                                        $CodigoBarras = '';
                                    }
                                    
                                }else {
                                    $CodigoBarras = '';
                                }
                                if($num_cols > 4){
                                    if(trim($campo[4]) != ''){
                                        $Categoria = trim($campo[4]);
                                    }else {
                                        $Categoria = 'Sin categoría';
                                    }
                                    
                                }else {
                                    $Categoria = 'Sin categoría';
                                }
                                if($num_cols > 5){
                                    if(trim($campo[5]) != ''){
                                        $Marca = trim($campo[5]);
                                    }else {
                                        $Marca = 'Sin marca';
                                    }
                                    
                                }else {
                                    $Marca = 'Sin marca';
                                }
                                switch($num_cols){
                                    case 6:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 7:
                                        if (trim($campo[6]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[6]);
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 8:
                                        if (trim($campo[6]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[6]);
                                        if (trim($campo[7]) == '') {
                                            $ClaveSATUnidad = '';
                                        }
                                        $ClaveSATUnidad = trim($campo[7]);
                                    break;
                                    default:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                }
    
                                insertar($Clave, $Nombre, $Descripcion, $CodigoBarras, $Categoria, $Marca, 0.00, '', $ClaveSAT, $ClaveSATUnidad, $tipo);
                            }
                            ++$count1;
                        }
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

                    if($tipo == 3) {

                        foreach ($xls->rows() as $campo) {

                            if($count1 > 0){

                                $Clave = trim($campo[0]);
                                $Nombre = trim($campo[1]);
                                if($num_cols > 2){
                                    if(trim($campo[2]) != ''){

                                        $Descripcion = remove_accents(trim($campo[2]));

                                    }else {
                                        $Descripcion = '';
                                    }
                                    
                                }else {
                                    $Descripcion = '';
                                }
                                if($num_cols > 3){
                                    if(trim($campo[3]) != ''){
                                        $CodigoBarras = trim($campo[3]);
                                    }else {
                                        $CodigoBarras = '';
                                    }
                                    
                                }else {
                                    $CodigoBarras = '';
                                }
                                if($num_cols > 4){
                                    if(trim($campo[4]) != ''){
                                        $Categoria = trim($campo[4]);
                                    }else {
                                        $Categoria = 'Sin categoría';
                                    }
                                    
                                }else {
                                    $Categoria = 'Sin categoría';
                                }
                                if($num_cols > 5){
                                    if(trim($campo[5]) != ''){
                                        $Marca = trim($campo[5]);
                                    }else {
                                        $Marca = 'Sin marca';
                                    }
                                    
                                }else {
                                    $Marca = 'Sin marca';
                                }
                                $CostoFabricacion = trim($campo[6]);
                                $TipoMonedaFabricacion = trim($campo[7]);
                                switch($num_cols){
                                    case 8:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 9:
                                        if (trim($campo[8]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[8]);
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 10:
                                        if (trim($campo[8]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[8]);
                                        if (trim($campo[9]) == '') {
                                            $ClaveSATUnidad = '';
                                        }
                                        $ClaveSATUnidad = trim($campo[9]);
                                    break;
                                    default:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                }

                                insertar($Clave, $Nombre, $Descripcion, $CodigoBarras, $Categoria, $Marca, $CostoFabricacion, $TipoMonedaFabricacion, $ClaveSAT, $ClaveSATUnidad, $tipo);
                            }
                            ++$count1;                    
                        }

                    } else {
                        foreach ($xls->rows() as $campo) {

                            if($count1 > 0){

                                $Clave = trim($campo[0]);
                                $Nombre = trim($campo[1]);
                                if($num_cols > 2){
                                    if(trim($campo[2]) != ''){

                                        $Descripcion = remove_accents(trim($campo[2]));

                                    }else {
                                        $Descripcion = '';
                                    }
                                    
                                }else {
                                    $Descripcion = '';
                                }
                                if($num_cols > 3){
                                    if(trim($campo[3]) != ''){
                                        $CodigoBarras = trim($campo[3]);
                                    }else {
                                        $CodigoBarras = '';
                                    }
                                    
                                }else {
                                    $CodigoBarras = '';
                                }
                                if($num_cols > 4){
                                    if(trim($campo[4]) != ''){
                                        $Categoria = trim($campo[4]);
                                    }else {
                                        $Categoria = 'Sin categoría';
                                    }
                                    
                                }else {
                                    $Categoria = 'Sin categoría';
                                }
                                if($num_cols > 5){
                                    if(trim($campo[5]) != ''){
                                        $Marca = trim($campo[5]);
                                    }else {
                                        $Marca = 'Sin marca';
                                    }
                                    
                                }else {
                                    $Marca = 'Sin marca';
                                }
                                switch($num_cols){
                                    case 6:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 7:
                                        if (trim($campo[6]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[6]);
                                        $ClaveSATUnidad = '';
                                    break;
                                    case 8:
                                        if (trim($campo[6]) == '') {
                                            $ClaveSAT = '';
                                        }
                                        $ClaveSAT = trim($campo[6]);
                                        if (trim($campo[7]) == '') {
                                            $ClaveSATUnidad = '';
                                        }
                                        $ClaveSATUnidad = trim($campo[7]);
                                    break;
                                    default:
                                        $ClaveSAT = '';
                                        $ClaveSATUnidad = '';
                                }
    
                                insertar($Clave, $Nombre, $Descripcion, $CodigoBarras, $Categoria, $Marca, 0.00, '', $ClaveSAT, $ClaveSATUnidad, $tipo);
                            }
                            ++$count1;
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
function insertar($Clave, $Nombre, $Descripcion, $CodigoBarras, $Categoria, $Marca, $CostoFabricacion, $TipoMonedaFabricacion, $ClaveSAT, $ClaveSATUnidad, $Tipo){
    $con = new conectar();
    $db = $con->getDb();
    $PKUsuario = $_SESSION["PKUsuario"];
    try {
        $query = sprintf('call spi_Productos_Excel(?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $Clave, $Nombre, $Descripcion, $CodigoBarras, $Categoria, $Marca, $CostoFabricacion, $TipoMonedaFabricacion, $ClaveSAT, $ClaveSATUnidad, $Tipo));

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
