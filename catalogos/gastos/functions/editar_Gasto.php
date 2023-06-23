<?php
session_start();
$idempresa = $_SESSION["IDEmpresa"];
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    $idMovimiento = $_POST['idMovimiento'];
    $id = (int) $_POST['idCuenta'];
    $responsable = $_POST['cmbResponsableGasto'];
    $fechaGasto = $_POST['txtFechaGasto'];
    $observaciones = $_POST['areaDescripcionGasto'];
    $proveedor = $_POST['cmbProvedoresGasto'];
    $categoria = $_POST['cmbCategoria'];
    $subcategoria = $_POST['cmbSubcategoria'];
    $check = $_POST['comprobado'];
    $hayArchivo = $_POST['hayArchivo'];

    if ($hayArchivo == 1) {
        $filename = $_FILES['inputFile']['name'];
        $tmp = $_FILES['inputFile']['tmp_name'];
        $partesruta = pathinfo($filename);
        $identificador = round(microtime(true));
        $nombrefinal = $id . '_REF_' . $identificador . '.' . $partesruta['extension'];
        /* Location */
        switch($partesruta['extension']){
            case "jpg":
            case "jpeg":
            case "png":
            $location = $_ENV['RUTA_ARCHIVOS_WRITE'].$idempresa.'/img'.'/';
            break;
            case "pdf":
            case "xlsx":
            case "xml":
            $location = $_ENV['RUTA_ARCHIVOS_WRITE'].$idempresa.'/archivos'.'/';
            break;
            default:
            $location = $_ENV['RUTA_ARCHIVOS_WRITE'].$idempresa.'/archivos'.'/';
            break;
        }
        if ($partesruta['extension'] == "jpg" || $partesruta['extension'] == "jpeg" || $partesruta['extension'] == "png" || $partesruta['extension'] == "pdf" || $partesruta['extension'] == "xlsx" || $partesruta['extension'] == "xml") {
            if ($_FILES['inputFile']['size'] < 4000000) {
                if (move_uploaded_file($_FILES['inputFile']['tmp_name'], $location.$nombrefinal)) {
                    echo updateRetiroCaja($conn, $idMovimiento, $id, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo, $nombrefinal);
                } else {
                    echo "mal 1";
                }
            } else {
                echo "mal 2";
            }
        } else {
            echo "mal 3";
        }
    } else {
        //llamar funcion
        echo updateRetiroCaja($conn, $idMovimiento, $id, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo);
    }
} else {
    header("location:../../../../dashboard.php");
}

function updateRetiroCaja($conn, $idMovimiento, $id, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo , $nombrefinal = NULL)
{
    //Seleccionar el tipo de la cuenta caja chica para hacer la resta
    $stmt = $conn->prepare('SELECT Retiro FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :fkmovimiento');
    $stmt->bindValue(':fkmovimiento', $idMovimiento, PDO::PARAM_INT);
    $stmt->execute();
    $rowC = $stmt->fetch();
    $retiro = $rowC['Retiro'];
    try {
        if ($categoria && $categoria != null && $categoria != '') {
            if ($subcategoria && $subcategoria != null && $subcategoria != '' && $subcategoria != 0) {
                $query = 'UPDATE movimientos_cuentas_bancarias_empresa SET FKResponsable=:responsable,Fecha=:fecha,FKProveedor=:proveedor,tipo_movimiento_id=:tipo,Descripcion=:descripcion,FKCategoria=:categoria,FKSubcategoria=:subcategoria,Referencia=:referencia,Comprobado=:comprobado WHERE PKMovimiento=:fkmovimiento';
                $datos = [
                    'fkmovimiento' => $idMovimiento,
                    'responsable' => $responsable,
                    'fecha' => $fechaGasto,
                    'proveedor' => $proveedor,
                    'tipo' => 2,
                    'descripcion' => $observaciones,
                    'categoria' => $categoria,
                    'subcategoria' => $subcategoria,
                    'referencia' => $nombrefinal,
                    'comprobado' => $hayArchivo];
            } else {
                $query = 'UPDATE movimientos_cuentas_bancarias_empresa SET FKResponsable=:responsable,Fecha=:fecha,FKProveedor=:proveedor,tipo_movimiento_id=:tipo,Descripcion=:descripcion,FKCategoria=:categoria,Referencia=:referencia,Comprobado=:comprobado WHERE PKMovimiento=:fkmovimiento';
                $datos = [
                    'fkmovimiento' => $idMovimiento,
                    'responsable' => $responsable,
                    'fecha' => $fechaGasto,
                    'proveedor' => $proveedor,
                    'tipo' => 2,
                    'descripcion' => $observaciones,
                    'categoria' => $categoria,
                    'referencia' => $nombrefinal,
                    'comprobado' => $hayArchivo];
            }
        } else {
            $query = 'UPDATE movimientos_cuentas_bancarias_empresa SET FKResponsable=:responsable,Fecha=:fecha,FKProveedor=:proveedor,tipo_movimiento_id=:tipo,Descripcion=:descripcion,Referencia=:referencia,Comprobado=:comprobado WHERE PKMovimiento=:fkmovimiento';
            $datos = [
                'fkmovimiento' => $idMovimiento,
                'responsable' => $responsable,
                'fecha' => $fechaGasto,
                'proveedor' => $proveedor,
                'tipo' => 2,
                'descripcion' => $observaciones,
                'referencia' => $nombrefinal,
                'comprobado' => $hayArchivo];
        }
        $stmtI = $conn->prepare($query);
        if($stmtI->execute($datos)){
            return 'exito';
        }
    } catch (\Throwable $th) {
        echo $th;
    }
}