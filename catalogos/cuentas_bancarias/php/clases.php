<?php

date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];

class conectar
{ //Llamado al archivo de la conexión.
    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}
class get_data
{
    //BOX ACCOUNT
    public function getCajaTableMovimeintos($id)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $fila = 0;
        $no = 1;

        $query = sprintf('SELECT mov.PKMovimiento as idMov,
				mov.cuenta_origen_id as idCuenta,
				mov.Fecha,
				mov.Descripcion,
				mov.Retiro,
				mov.Deposito,
				mov.Saldo,
				mov.Referencia,
				mov.Comprobado,
				mov.cuenta_destino_id as cdid,
				cc.PKCuentaCajaChica
				FROM movimientos_cuentas_bancarias_empresa as mov 
                INNER JOIN cuenta_caja_chica as cc ON (cc.FKCuenta=mov.cuenta_origen_id and mov.cuenta_origen_id !=300) or (cc.FKCuenta=mov.cuenta_destino_id)
				WHERE mov.cuenta_origen_id = '.$id.' or mov.cuenta_destino_id = '.$id.' and mov.estatus=1  ORDER BY PKMovimiento DESC');

        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $row) {
            $id = $row['idCuenta'];
            $idMov = $row['idMov'];

            if ($row['Retiro'] == null) {
                $retiro = $row['Retiro'];
            } else {
                $retiro = "$" . number_format($row['Retiro'], 2);
            }
            if ($row['Deposito'] == null) {
                $deposito = $row['Deposito'];
            } else {
                $deposito = "$" . number_format($row['Deposito'], 2);
            }
            if ($row['Saldo'] == null) {
                $saldo = $row['Saldo'];
            } else {
                $saldo = "$" . number_format($row['Saldo'], 2);
            }
            $fila++;
            //COMPROBAR
            if ($row['Comprobado'] == "0") {
                $comprobar = '<label for=\"file-input\" class=\"pointer\">Sin comprobar <i class=\"fas fa-cloud-upload-alt\"></i></label><input accept=\"image/*, .pdf, .xlsx, .xml\" id=\"file-input\" name=\"file-input\" type=\"file\" onchange=\"subirReferencia(' . $row['idMov'] . ',' . $row['idCuenta'] . ');\" style=\"display:none\"/><input  class=\"btnEdit\" type=\"hidden\" id=\"' . $fila . '\">';
            } else {
                $comprobar = '<i class=\"fas fa-check-circle\"></i>';
            }
            //SI HAY UNA REFERENCIA
            if ($row['Referencia'] != "-") {
                $ref = '<a target=\"_blank\" href=\"functions/Documentos/' . $row['Referencia'] . '\">' . "" . $row['Referencia'] . '</a>';
            } else {
                $ref = $row['Referencia'];
            }
            //$idDestino = $row['idDestino'];

            $table .= '{"Id":"' . $idMov . '",
						"Fecha":"' . $row['Fecha'] . '",
			"Descripción":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Descripcion'])) . '",
			"Retiro/cargo":"' . $retiro . '",
			"Deposito/Abono":"' . $deposito . '",
			"Acciones":"",
			"Referencia":"' . $ref . '"},';
            $no++;
        }
        $table = substr($table, 0, strlen($table) - 1);
        return '{"data":[' . $table . ']}';
    }
    // OTHER ACCOUNT
    public function getOtherTableMovements($id)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $fila = 0;
        $no = 1;

        $query = sprintf('SELECT mov.PKMovimiento as idMov,
				mov.cuenta_origen_id as idCuenta,
				mov.Fecha,
				mov.Descripcion,
				mov.Retiro,
				mov.Deposito,
				mov.Saldo,
				mov.Referencia,
				mov.Comprobado,
				o.PKCuentaOtra
				FROM movimientos_cuentas_bancarias_empresa as mov 
                INNER JOIN cuentas_otras as o ON (o.FKCuenta=mov.cuenta_origen_id and mov.cuenta_origen_id !=300) or (o.FKCuenta=mov.cuenta_destino_id)
				WHERE mov.cuenta_origen_id = '.$id.' or mov.cuenta_destino_id = '.$id.' and mov.estatus=1  ORDER BY PKMovimiento DESC');

        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $row) {

            $idMov = $row['idMov'];

            if ($row['Retiro'] == null) {
                $retiro = $row['Retiro'];
            } else {
                $retiro = "$" . number_format($row['Retiro'], 2);
            }
            if ($row['Deposito'] == null) {
                $deposito = $row['Deposito'];
            } else {
                $deposito = "$" . number_format($row['Deposito'], 2);
            }

            if ($row['Saldo'] == null) {
                $saldo = $row['Saldo'];
            } else {
                $saldo = "$" . number_format($row['Saldo'], 2);
            }
            $fila++;
            if ($row['Comprobado'] == "0") {
                $comprobar = '<label for=\"file-input\" class=\"pointer\">Sin comprobar <i class=\"fas fa-cloud-upload-alt\"></i></label><input accept=\"image/*, .pdf, .xlsx, .xml\" id=\"file-input\" id=\"file-input\" name=\"file-input\" type=\"file\" onchange=\"subirReferencia(' . $row['idMov'] . ',' . $row['idCuenta'] . ');\" style=\"display:none\"/><input  class=\"btnEdit\" type=\"hidden\" id=\"' . $fila . '\">';

            } else {
                $comprobar = '<i class=\"fas fa-check-circle\"></i>';
            }
            //SI HAY UNA REFERENCIA
            if ($row['Referencia'] != "-") {
                $ref = ' <div id=\"contenedor-centrado\" > <a target=\"_blank\" href=\"functions/Documentos/' . $row['Referencia'] . '\">' . "" . $row['Referencia'] . '</a> </div>';

            } else {
                $ref = $row['Referencia'];
            }
            $table .= '{"Id":"' . $idMov . '",
					"Fecha":"' . $row['Fecha'] . '",
			"Descripción":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Descripcion'])) . '",
			"Retiro/cargo":"' . $retiro . '",
			"Deposito/Abono":"' . $deposito . '",
			"Acciones":"" },';
            $no++;
        }
        $table = substr($table, 0, strlen($table) - 1);
        return '{"data":[' . $table . ']}';
    }
    // CREDIT ACCOUNT
    public function getCreditTableMovements($id)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $fila = 0;
        $no = 1;
        $query = sprintf('SELECT
				mov.PKMovimiento as idMov,
				mov.Fecha,
				mov.Descripcion,
				mov.Retiro,
				mov.Deposito,
				mov.Saldo,
				mov.Referencia,
				mov.Comprobado,
				cr.PKCuentaCredito
				FROM movimientos_cuentas_bancarias_empresa as mov 
                INNER JOIN cuentas_credito as cr ON (cr.FKCuenta=mov.cuenta_origen_id and mov.cuenta_origen_id !=300) or (cr.FKCuenta=mov.cuenta_destino_id)
				WHERE mov.cuenta_origen_id = '.$id.' or mov.cuenta_destino_id = '.$id.' and mov.estatus=1 ORDER BY PKMovimiento DESC');

        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $row) {
            $idMov = $row['idMov'];
            if ($row['Retiro'] == null) {
                $retiro = $row['Retiro'];
            } else {
                $retiro = "$" . number_format($row['Retiro'], 2);
            }
            if ($row['Deposito'] == null) {
                $deposito = $row['Deposito'];
            } else {
                $deposito = "$" . number_format($row['Deposito'], 2);
            }
            if ($row['Saldo'] == null) {
                $saldo = $row['Saldo'];
            } else {
                $saldo = "$" . number_format($row['Saldo'], 2);
            }
            $table .= '{"Id":"' . $idMov . '",
					"Fecha":"' . $row['Fecha'] . '",
			"Descripción":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Descripcion'])) . '",
			"Retiro/cargo":"' . $retiro . '",
			"Acciones":"",
			"Deposito/Abono":"' . $deposito . '"},';
            $no++;
        }
        $table = substr($table, 0, strlen($table) - 1);
        return '{"data":[' . $table . ']}';
    }
    //CHECKS
    public function getChecksTableMovements($id)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $fila = 0;
        $no = 1;
        $query = sprintf('SELECT mov.PKMovimiento as idMov,
        mov.Fecha,
        mov.Descripcion,
        mov.Retiro,
        mov.Deposito,
        mov.Saldo,
        mov.Referencia,
        p.tipo_movimiento,
        p.identificador_pago,
        p.idpagos,
        mov.Comprobado,
        ch.PKCuentasCheque,
        mo.PKMoneda
        FROM movimientos_cuentas_bancarias_empresa as mov
        inner JOIN cuentas_cheques as ch ON (ch.FKCuenta=mov.cuenta_origen_id and mov.cuenta_origen_id !=300 and mov.cuenta_origen_id = :cuenta) or (ch.FKCuenta=mov.cuenta_destino_id and mov.cuenta_destino_id = :cuenta2)
        left join pagos p on p.idpagos=mov.id_pago
        left join cuentas_por_pagar as cpp on  mov.cuenta_pagar_id = cpp.id
        inner JOIN monedas as mo ON mo.PKMoneda=ch.FKMOneda
        WHERE mov.cuenta_origen_id = :cuenta3 or mov.cuenta_destino_id = :cuenta4 and mov.estatus=1 ORDER BY PKMovimiento DESC');

        $stmt = $db->prepare($query);
        $stmt->bindValue(":cuenta",$id);
        $stmt->bindValue(":cuenta2",$id);
        $stmt->bindValue(":cuenta3",$id);
        $stmt->bindValue(":cuenta4",$id);
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $row) {
            $idMov = $row['idMov'];

            if ($row['Retiro'] == null) {
                $retiro = $row['Retiro'];
            } else {
                $retiro = "$" . number_format($row['Retiro'], 2);
            }
            if ($row['Deposito'] == null) {
                $deposito = $row['Deposito'];
            } else {
                $deposito = "$" . number_format($row['Deposito'], 2);
            }
            if ($row['Saldo'] == null) {
                $saldo = $row['Saldo'];
            } else {
                $saldo = "$" . number_format($row['Saldo'], 2);
            }
            if($row['tipo_movimiento'] == 1){
                $url = '../pagos/ver.php?id=';
                $valor = $row['idpagos'];
            }else{
                $url = '../recepcion_pagos/detalle_pago.php?idPago=';
                $valor = $row['identificador_pago'];
            }

            $table .= '{"Id":"' . $idMov . '",
					"Fecha":"' . $row['Fecha'] . '",
			"Descripción":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Descripcion'])) . ' - ' . '<a href=\"' . $url . $valor . '\" target=\"_blank\">' . $row['identificador_pago'] . '</a>' . '",
			"Retiro/cargo":"' . $retiro . '",
			"Acciones":"",
			"Deposito/Abono":"' . $deposito . '"},';
            $no++;
        }
        $table = substr($table, 0, strlen($table) - 1);
        return '{"data":[' . $table . ']}';
    }

    public function validarClabe($clabe, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicaClabeCheques(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($clabe, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarClabeU($clabe, $idcuenta, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicaClabeChequesU(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($clabe, $idcuenta, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarNoCuenta($cuenta, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicaNoCuentaCheques(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($cuenta, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarNoCuentaU($nocuenta, $idCuenta, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicaNoCuentaChequesU(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($nocuenta, $idCuenta, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarNoCredito($valor, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoNoCredito(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($valor, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarNoCreditoU($valor, $idCuenta, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoNoCreditoU(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($valor, $idCuenta, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarIdentificador($valor, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoIdentificador(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($valor, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarIdentificadorU($valor, $idCuenta, $fkempresa)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoIdentificadorU(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($valor, $idCuenta, $fkempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function getCmbCategoriaG($idemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_CategoriaGasto(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idemp));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
class save_data
{
    public function saveChekingAccount($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            $query = sprintf('call spi_Cuenta_Cheques(?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
    public function saveCreditAccount($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            $query = sprintf('call spi_Cuenta_Credito(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, 0));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

    }
    public function saveOtherAccount($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            $query = sprintf('call spi_Cuenta_Otras(?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

    }
    public function saveBoxAccount($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $data10)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            $query = sprintf('call spi_Cuenta_CajaC(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $data10));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

    }

    public function editChekingAccount($data, $data2, $data3, $data4)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            $query = sprintf('call spu_Cuenta_ChequesBs(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($data, $data2, $data3, $data4));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
}

class edit_data
{
    public function editChekingAccount($data, $data2, $data3, $data4)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            $query = sprintf('call spu_Cuenta_ChequesB(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($data, $data2, $data3, $data4));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
}