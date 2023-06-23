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
                $ref = '<a target=\"_blank\" href=\"functions/Documentos/' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia'])) . '\">' . "" . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia'])) . '</a>';
            } else {
                $ref = str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia']));
            }
            //$idDestino = $row['idDestino'];

            $table .= '{"Id":"' . $idMov . '",
						"Fecha":"' . $row['Fecha'] . '",
			"Descripción":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Descripcion'])) . '",
			"Retiro/cargo":"' . $retiro . '",
			"Deposito/Abono":"' . $deposito . '",
			"Saldo":"' . $saldo . '",
			"Referencia":"' . $ref . '",
			"Comprobar":"' . $comprobar . '"},';
            $no++;
        }
        $table = substr($table, 0, strlen($table) - 1);
        return '{"data":[' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $table)) . ']}';
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
			"Saldo":"' . $saldo . '",
			"Referencia":"' . $ref . '",
			"Comprobar":"' . $comprobar . '" },';
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
        mov.Comprobado,
        ch.PKCuentasCheque,
        mo.PKMoneda
        FROM movimientos_cuentas_bancarias_empresa as mov
        inner JOIN cuentas_cheques as ch ON (ch.FKCuenta=mov.cuenta_origen_id and mov.cuenta_origen_id !=300) or (ch.FKCuenta=mov.cuenta_destino_id)
        inner JOIN monedas as mo ON mo.PKMoneda=ch.FKMOneda
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
    public function getCmbSubcategoriaG($categoria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT PKSubcategoria, Nombre FROM subcategorias_gastos WHERE FKCategoria = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($categoria));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getCategoriesTotals(){
        $con = new conectar();
        $db = $con->getDb();
        $request = [];
        $anioActual = date('Y');
        $query = sprintf("SELECT 
                            cat.PKCategoria id,
                            cat.Nombre nombre,
                            sum(if(m.estatus=1,m.Retiro,0)) total_neto
                        from movimientos_cuentas_bancarias_empresa m
                            inner join cuentas_bancarias_empresa c on m.cuenta_origen_id = c.PKCuenta
                            inner join categoria_gastos cat on m.FKCategoria = cat.PKCategoria
                            left join pagos p on m.id_pago = p.idpagos
                        where 
                            c.empresa_id = :empresa_id and 
                            c.tipo_cuenta != 2 and 
                            (m.tipo_movimiento_id=2 or (m.tipo_movimiento_id=5 and p.tipo_movimiento = 1)) and
                            year(m.Fecha) = :actual
                        group by 
                            m.FKCategoria");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
        $stmt->bindValue(':actual',$anioActual);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
        
    }

    function getListCategorySubcategory()
    {
        $get_data = new get_data();
        $cat = $get_data->getCategoriesTotals();
        $subcat = $get_data->getSubcategoriesTotals();
        $cat_merge = [];
        for($i=0;$i<count($cat);$i++)
        {
            if((float)$cat[$i]->total_neto > 0){
                $cat_merge[$i]['cat_id'] = (int)$cat[$i]->id;
                $cat_merge[$i]['categoria'] = $cat[$i]->nombre;
                $cat_merge[$i]['total_neto'] = $cat[$i]->total_neto;
            }
        }
        $cat_merge = array_values($cat_merge);

        for($i=0;$i<count($subcat);$i++)
        {
           
            if((float)$subcat[$i]->total>0){
                if (in_array((int)$subcat[$i]->id, array_column($cat_merge, 'cat_id'))) {
                    $cat_merge[array_search((int)$subcat[$i]->id,array_column($cat_merge, 'cat_id'))]['subcategorias'][] = 
                    [
                        "nombre"=>$subcat[$i]->nombre,
                        "total"=>number_format($subcat[$i]->total,2)
                    ];
                }
            }
        }
       
        return $cat_merge;
    }

    public function getSubcategoriesTotals(){
        $con = new conectar();
        $db = $con->getDb();
        $request = [];
        $anioActual = date('Y');
        $query = sprintf("SELECT
                            subcat.nombre,
                            sum(if(m.estatus=1,m.Retiro,0)) total,
                            subcat.FKCategoria id,
                            cat.Nombre nombre_cat
                        from movimientos_cuentas_bancarias_empresa m
                            inner join cuentas_bancarias_empresa c on m.cuenta_origen_id = c.PKCuenta
                            inner join subcategorias_gastos subcat on m.FKSubcategoria = subcat.PKSubcategoria
                            inner join categoria_gastos cat on subcat.FKCategoria = cat.PKCategoria
                            left join pagos p on m.id_pago = p.idpagos
                        where 
                            c.empresa_id = :empresa_id and 
                            c.tipo_cuenta != 2 and 
                            (m.tipo_movimiento_id=2 or (m.tipo_movimiento_id=5 and p.tipo_movimiento = 1)) and 
                            year(m.Fecha) = :actual
                        group by 
                            m.FKSubcategoria
                    ;");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
        $stmt->bindValue(':actual',$anioActual);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); 
    }

    public function getCategoriesTotalsFilter($cuenta,$categoria,$subcategoria,$fecha_inicial,$fecha_final)
    {
        $con = new conectar();
        $db = $con->getDb();
        $request = [];
        $anioActual = date('Y');
        $fechasql = "";
        $fecha_inicial_filtro = '';
        $cuenta_filtro = ($cuenta !== "0" && $cuenta !== "" && $cuenta !== null) ? ' and m.cuenta_origen_id = :cuenta' : '';
        $categoria_filtro = ($categoria !== "0" && $categoria !== "" && $categoria !== null) ? ' and m.FKCategoria = :categoria' : '';
        $subcategoria_filtro = ($subcategoria !== "0" && $subcategoria !== "" && $subcategoria !== null) ? ' and m.FKSubcategoria = :subcategoria' : '';
        if(
            ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
            ($fecha_final !== "0" && $fecha_final !== "" && $fecha_final !== null))
            {
                $fecha_inicial_filtro = ' and m.Fecha between :fecha_inicial and :fecha_final ';
            }else if(
                ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
                ($fecha_final === "0" || $fecha_final === "" || $fecha_final === null))
            {
                $fecha_inicial_filtro = ' and m.Fecha between :fecha_inicial and now()';
                
            } else if(
                ($fecha_inicial === "0" && $fecha_inicial === "" && $fecha_inicial === null) && 
                ($fecha_final !== "0" || $fecha_final !== "" || $fecha_final !== null))
            {
                $fecha_inicial_filtro = ' and m.Fecha between "1900-01-01" and :fecha_final';
            }
        
        if($cuenta_filtro === '' &&
        $categoria_filtro === '' &&
        $subcategoria_filtro === '' &&
        $fecha_inicial_filtro === '' ){
            $fechasql = ' and year(m.Fecha) = :fecha';
        }

        $query = sprintf("SELECT 
                            cat.PKCategoria id,
                            cat.Nombre nombre,
                            sum(if(m.estatus=1,m.Retiro,0)) total_neto
                        from movimientos_cuentas_bancarias_empresa m
                            inner join cuentas_bancarias_empresa c on m.cuenta_origen_id = c.PKCuenta
                            inner join categoria_gastos cat on m.FKCategoria = cat.PKCategoria
                            left join pagos p on m.id_pago = p.idpagos
                        where 
                            c.empresa_id = :empresa_id and 
                            c.tipo_cuenta != 2 and 
                            (m.tipo_movimiento_id=2 or (m.tipo_movimiento_id=5 and p.tipo_movimiento = 1))
                            $cuenta_filtro
                            $categoria_filtro
                            $subcategoria_filtro
                            $fecha_inicial_filtro
                            $fechasql
                        group by 
                            m.FKCategoria");                                                      
        $stmt = $db->prepare($query);
        $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
        if($cuenta !== "0" && $cuenta !== "" && $cuenta !== null)
        {
            $stmt->bindValue(':cuenta',$cuenta);
        }
        if($categoria !== "0" && $categoria !== "" && $categoria !== null){
            $stmt->bindValue(':categoria',$categoria);
        }
        if($subcategoria !== "0" && $subcategoria !== "" && $subcategoria !== null)
        {  
            $stmt->bindValue(':subcategoria',$subcategoria);
        }
        if(
            ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
            ($fecha_final !== "0" && $fecha_final !== "" && $fecha_final !== null))
            {
                $stmt->bindValue(':fecha_inicial',$fecha_inicial);
                $stmt->bindValue(':fecha_final',$fecha_final);
                
            }else if(
                ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
                ($fecha_final === "0" || $fecha_final === "" || $fecha_final === null))
            {
                $stmt->bindValue(':fecha_inicial',$fecha_inicial);
                
            } else if(
                ($fecha_inicial === "0" && $fecha_inicial === "" && $fecha_inicial === null) && 
                ($fecha_final !== "0" || $fecha_final !== "" || $fecha_final !== null))
            {
                $stmt->bindValue(':fecha_final',$fecha_final);
            } 
            
            if($cuenta_filtro === '' &&
                $categoria_filtro === '' &&
                $subcategoria_filtro === '' &&
                $fecha_inicial_filtro=== '' ){
                    $stmt->bindValue(':fecha',$anioActual);
            }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
        
    }

    public function getSubcategoriesTotalsFilter($cuenta,$categoria,$subcategoria,$fecha_inicial,$fecha_final)
    {
        $con = new conectar();
        $db = $con->getDb();
        $request = [];
        $anioActual = date('Y');
        $fechasql = "";
        $fecha_inicial_filtro = '';

        $cuenta_filtro = ($cuenta !== "0" && $cuenta !== "" && $cuenta !== null) ? ' and m.cuenta_origen_id = :cuenta' : '';
        $categoria_filtro = ($categoria !== "0" && $categoria !== "" && $categoria !== null) ? ' and m.FKCategoria = :categoria' : '';
        $subcategoria_filtro = ($subcategoria !== "0" && $subcategoria !== "" && $subcategoria !== null) ? ' and m.FKSubcategoria = :subcategoria' : '';
        if(
            ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
            ($fecha_final !== "0" && $fecha_final !== "" && $fecha_final !== null))
            {
                $fecha_inicial_filtro = ' and m.Fecha between :fecha_inicial and :fecha_final ';
            }else if(
                ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
                ($fecha_final === "0" || $fecha_final === "" || $fecha_final === null))
            {
                $fecha_inicial_filtro = ' and m.Fecha between :fecha_inicial and now()';
                
            } else if(
                ($fecha_inicial === "0" && $fecha_inicial === "" && $fecha_inicial === null) && 
                ($fecha_final !== "0" || $fecha_final !== "" || $fecha_final !== null))
            {
                $fecha_inicial_filtro = ' and m.Fecha between "1900-01-01" and :fecha_final';
            }
            
            if($cuenta_filtro === '' &&
                $categoria_filtro === '' &&
                $subcategoria_filtro === '' &&
                $fecha_inicial_filtro === '' ){
                    $fechasql = ' and year(m.Fecha) = :fecha';
            }
           
        $query = sprintf("SELECT
                            subcat.nombre,
                            sum(if(m.estatus=1,m.Retiro,0)) total,
                            subcat.FKCategoria id,
                            cat.Nombre nombre_cat
                        from movimientos_cuentas_bancarias_empresa m
                            inner join cuentas_bancarias_empresa c on m.cuenta_origen_id = c.PKCuenta
                            inner join subcategorias_gastos subcat on m.FKSubcategoria = subcat.PKSubcategoria
                            inner join categoria_gastos cat on subcat.FKCategoria = cat.PKCategoria
                            left join pagos p on m.id_pago = p.idpagos
                        where 
                            c.empresa_id = :empresa_id and 
                            c.tipo_cuenta != 2 and 
                            (m.tipo_movimiento_id=2 or (m.tipo_movimiento_id=5 and p.tipo_movimiento = 1)) 
                            $cuenta_filtro
                            $categoria_filtro
                            $subcategoria_filtro
                            $fecha_inicial_filtro
                            $fechasql
                        group by 
                            m.FKSubcategoria
                    ");
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
        if($cuenta !== "0" && $cuenta !== "" && $cuenta !== null)
        {
            $stmt->bindValue(':cuenta',$cuenta);
        }
        if($categoria !== "0" && $categoria !== "" && $categoria !== null){
            $stmt->bindValue(':categoria',$categoria);
        }
        if($subcategoria !== "0" && $subcategoria !== "" && $subcategoria !== null)
        {  
            $stmt->bindValue(':subcategoria',$subcategoria);
        }
        if(
            ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
            ($fecha_final !== "0" && $fecha_final !== "" && $fecha_final !== null))
            {
                $stmt->bindValue(':fecha_inicial',$fecha_inicial);
                $stmt->bindValue(':fecha_final',$fecha_final);
                
            }else if(
                ($fecha_inicial !== "0" && $fecha_inicial !== "" && $fecha_inicial !== null) && 
                ($fecha_final === "0" || $fecha_final === "" || $fecha_final === null))
            {
                $stmt->bindValue(':fecha_inicial',$fecha_inicial);
                
            } else if(
                ($fecha_inicial === "0" && $fecha_inicial === "" && $fecha_inicial === null) && 
                ($fecha_final !== "0" || $fecha_final !== "" || $fecha_final !== null))
            {
                $stmt->bindValue(':fecha_final',$fecha_final);
            } 
            
            
            if($cuenta_filtro === '' &&
                $categoria_filtro === '' &&
                $subcategoria_filtro === '' &&
                $fecha_inicial_filtro === '' ){
                    $stmt->bindValue(':fecha',$anioActual);
            }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); 
    }

    function getListCategorySubcategoryFilter($cuenta,$categoria,$subcategoria,$fecha_inicial,$fecha_final)
    {
        $get_data = new get_data();
        
        $cat = $get_data->getCategoriesTotalsFilter($cuenta,$categoria,$subcategoria,$fecha_inicial,$fecha_final);
        $subcat = $get_data->getSubcategoriesTotalsFilter($cuenta,$categoria,$subcategoria,$fecha_inicial,$fecha_final);
        $cat_merge = [];
       
        for($i=0;$i<count($cat);$i++)
        {   if($cat[$i]->total_neto>0){
                $cat_merge[$i]['categoria'] = $cat[$i]->nombre;
                $cat_merge[$i]['total_neto'] = $cat[$i]->total_neto;
            }
        }
       
        $cat_merge = array_values($cat_merge);
        for($i=0;$i<count($subcat);$i++)
        {
            if (in_array($subcat[$i]->nombre_cat, array_column($cat_merge, 'categoria'))) {
                
                $cat_merge[array_search($subcat[$i]->nombre_cat,array_column($cat_merge, 'categoria'))]['subcategorias'][] = 
                [
                    "nombre"=>$subcat[$i]->nombre,
                    "total"=>number_format($subcat[$i]->total,2)
                ];
            }
        }

        return $cat_merge;
    }

    public function loadCmbCategorias()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("SELECT * from (select 
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where empresa_id = :idEmpresa and estatus = 1
                        
                        union 
                        
                        select
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where PKCategoria = 1) as cat ORDER BY cat.PKCategoria");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':idEmpresa', $PKEmpresa);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function loadCmbSubcategorias($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select PKSubcategoria, Nombre from subcategorias_gastos where FKCategoria = :categoria");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':categoria', $value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
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