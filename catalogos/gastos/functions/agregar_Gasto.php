<?php
session_start();
$idempresa = $_SESSION["IDEmpresa"];
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    $id = (int) $_POST['idCuenta'];
    $responsable = $_POST['cmbResponsableGasto'];
    $importe = $_POST['txtImporteGasto'];
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
                    echo insertRetiroCaja($conn, $id, $importe, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo, $nombrefinal);
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
        echo insertRetiroCaja($conn, $id, $importe, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo);
    }
} else {
    header("location:../../../../dashboard.php");
}

function insertRetiroCaja($conn, $id, $importe, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo , $nombrefinal = NULL)
{
    try {
        $stmt = $conn->prepare("SELECT 
                                mcbe.PKMovimiento,
                                mcbe.Fecha,
                                mcbe.Descripcion,
                                mcbe.Retiro,
                                mcbe.Saldo,
                                mcbe.Referencia,
                                CONCAT(emp.Nombres, ' ', emp.PrimerApellido, ' ', emp.SegundoApellido) AS Responsable,
                                mcbe.Comprobado,
                                mcbe.cuenta_origen_id,
                                cbe.Nombre,
                                mcbe.folio
                            FROM movimientos_cuentas_bancarias_empresa mcbe
                                INNER JOIN cuentas_bancarias_empresa cbe
                                ON mcbe.cuenta_origen_id=cbe.PKCuenta
                                AND cbe.empresa_id=?
                                LEFT JOIN empleados emp
                                ON mcbe.FKResponsable = emp.PKEmpleado
                                INNER JOIN relacion_tipo_empleado rte
                                ON emp.PKEmpleado = rte.empleado_id
                            WHERE emp.empresa_id = ? 
                            AND rte.tipo_empleado_id = 2
                            AND mcbe.tipo_movimiento_id=2
                            AND cbe.tipo_cuenta!=2
                            ORDER BY mcbe.PKMovimiento DESC");
        $stmt->bindParam(1, $_SESSION["IDEmpresa"], PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION["IDEmpresa"], PDO::PARAM_INT);
        $stmt->execute();
        $numGastos = $stmt->rowCount();
        if($numGastos == 0){
            $folio = '00000000001';
        }else{
            $stmt = $conn->prepare("SELECT 
                                MAX(mcbe.folio) as ultimoFolio
                            FROM movimientos_cuentas_bancarias_empresa mcbe
                                INNER JOIN cuentas_bancarias_empresa cbe
                                ON mcbe.cuenta_origen_id=cbe.PKCuenta
                                AND cbe.empresa_id=?
                                LEFT JOIN empleados emp
                                ON mcbe.FKResponsable = emp.PKEmpleado
                                INNER JOIN relacion_tipo_empleado rte
                                ON emp.PKEmpleado = rte.empleado_id
                            WHERE emp.empresa_id = ? 
                            AND rte.tipo_empleado_id = 2
                            AND mcbe.tipo_movimiento_id=2
                            AND cbe.tipo_cuenta!=2
                            ORDER BY mcbe.PKMovimiento DESC");
            $stmt->bindParam(1, $_SESSION["IDEmpresa"], PDO::PARAM_INT);
            $stmt->bindParam(2, $_SESSION["IDEmpresa"], PDO::PARAM_INT);
            $stmt->execute();
            $ultimoFolio = $stmt->fetch(PDO::FETCH_ASSOC);
            $uFolio = intval($ultimoFolio['ultimoFolio']) + 1;
            $strFolio = strval($uFolio);
            $folio = str_pad($strFolio, 11, '0', STR_PAD_LEFT);

        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    try {
        if ($categoria && $categoria != NULL && $categoria != '') {
            if ($subcategoria && $subcategoria != NULL && $subcategoria != '') {
                $query = 'INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,FKResponsable,Fecha,FKProveedor,tipo_movimiento_id,Descripcion,FKCategoria,FKSubcategoria,Retiro,Referencia,Comprobado,Saldo,folio) VALUES (:fkcuenta,:responsable,:fecha,:proveedor,:tipo,:descripcion,:categoria,:subcategoria,:retiro,:referencia,:comprobado,0,:folio)';
                $datos = [
                    'fkcuenta' => $id,
                    'responsable' => $responsable,
                    'fecha' => $fechaGasto,
                    'proveedor' => $proveedor,
                    'tipo' => 2,
                    'descripcion' => $observaciones,
                    'categoria' => $categoria,
                    'subcategoria' => $subcategoria,
                    'retiro' => $importe,
                    'referencia' => $nombrefinal,
                    'comprobado' => $hayArchivo,
                    'folio' => $folio];
            } else {
                $query = 'INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,FKResponsable,Fecha,FKProveedor,tipo_movimiento_id,Descripcion,FKCategoria,Retiro,Referencia,Comprobado,Saldo,folio) VALUES (:fkcuenta,:responsable,:fecha,:proveedor,:tipo,:descripcion,:categoria,:retiro,:referencia,:comprobado,0,:folio)';
                $datos = [
                    'fkcuenta' => $id,
                    'responsable' => $responsable,
                    'fecha' => $fechaGasto,
                    'proveedor' => $proveedor,
                    'tipo' => 2,
                    'descripcion' => $observaciones,
                    'categoria' => $categoria,
                    'retiro' => $importe,
                    'referencia' => $nombrefinal,
                    'comprobado' => $hayArchivo,
                    'folio' => $folio];
            }
        } else {
            $query = 'INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,FKResponsable,Fecha,FKProveedor,tipo_movimiento_id,Descripcion,Retiro,Referencia,Comprobado,Saldo,folio) VALUES (:fkcuenta,:responsable,:fecha,:proveedor,:tipo,:descripcion,:retiro,:referencia,:comprobado,0,:folio)';
            $datos = [
                'fkcuenta' => $id,
                'responsable' => $responsable,
                'fecha' => $fechaGasto,
                'proveedor' => $proveedor,
                'tipo' => 2,
                'descripcion' => $observaciones,
                'retiro' => $importe,
                'referencia' => $nombrefinal,
                'comprobado' => $hayArchivo,
                'folio' => $folio];
        }
        //Seleccionar el tipo de la cuenta caja chica para hacer la resta
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa WHERE PKCuenta = :fkcuenta');
        $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
        $stmt->execute();
        $rowC = $stmt->fetch();
        $tipoCuenta = $rowC['tipo_cuenta'];

        switch($tipoCuenta){
            case 1:
                //Seleccionar el saldo de la cuenta caja chica para hacer la resta
                $stmt = $conn->prepare('SELECT * FROM cuentas_cheques WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->execute();
                $rowC = $stmt->fetch();
                $saldoInicial = $rowC['Saldo_Inicial'];

                $saldoFinalSiArchivo = $saldoInicial - $importe;
                //UPDATE DE LA CUNETA CAJA CHICA
                $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial = :saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                if ($stmt->execute()) {
                    try {
                        $stmtI = $conn->prepare($query);
                        if ($stmtI->execute($datos)) {
                            $idLast = $conn->lastInsertId();
                            //UPDATE del movimiento
                            $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento = :idLast');
                            $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
                            $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                            if ($stmt->execute()) {
                                return "exito";
                            } else {
                                return "mal funcion";
                            }
                        } else {
                            return "mal funcion 2";
                        }
                    } catch (PDOException $ex) {
                        echo $ex->getMessage();
                    }
                }
            break;
            case 3:
                //Seleccionar el saldo de la cuenta caja chica para hacer la resta
                $stmt = $conn->prepare('SELECT * FROM cuentas_otras WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->execute();
                $rowC = $stmt->fetch();
                $saldoInicial = $rowC['Saldo_Inicial'];

                $saldoFinalSiArchivo = $saldoInicial - $importe;
                //UPDATE DE LA CUNETA CAJA CHICA
                $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial = :saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                if ($stmt->execute()) {
                    try {
                        $stmtI = $conn->prepare($query);
                        if ($stmtI->execute($datos)) {
                            $idLast = $conn->lastInsertId();
                            //UPDATE del movimiento
                            $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento = :idLast');
                            $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
                            $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                            if ($stmt->execute()) {
                                return "exito";
                            } else {
                                return "mal funcion";
                            }
                        } else {
                            return "mal funcion 2";
                        }
                    } catch (PDOException $ex) {
                        echo $ex->getMessage();
                    }
                }
            break;
            case 4:
                //Seleccionar el saldo de la cuenta caja chica para hacer la resta
                $stmt = $conn->prepare('SELECT * FROM cuenta_caja_chica WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->execute();
                $rowC = $stmt->fetch();
                $saldoInicial = $rowC['SaldoInicialCaja'];

                $saldoFinalSiArchivo = $saldoInicial - $importe;
                //UPDATE DE LA CUNETA CAJA CHICA
                $stmt = $conn->prepare('UPDATE cuenta_caja_chica SET SaldoInicialCaja = :saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                if ($stmt->execute()) {
                    try {
                        $stmtI = $conn->prepare($query);
                        if ($stmtI->execute($datos)) {
                            $idLast = $conn->lastInsertId();
                            //UPDATE del movimiento
                            $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento = :idLast');
                            $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
                            $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                            if ($stmt->execute()) {
                                return "exito";
                            } else {
                                return "mal funcion";
                            }
                        } else {
                            return "mal funcion 2";
                        }
                    } catch (PDOException $ex) {
                        echo $ex->getMessage();
                    }
                }
            break;
        }
    } catch (\Throwable $th) {
        echo $th;
    }
}