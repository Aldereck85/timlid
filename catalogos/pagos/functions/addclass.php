<?php
session_start();
date_default_timezone_set('America/Mexico_City');
class conectar
{ //Llamado al archivo de la conexión.


    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}
class Update_datas{
    public function updateDetails($_idpagos,$_cobroOpago,$_tipopagoid,$_txtTotal, $_txtfecha, $_proveedorid,$_txtreferencia,$_PKREsponsable,$_stringToInsert, $_count_cadena_insert, $_stringToDelete, $_count_cadena_delete,$_textareaCoemtarios,$_cuentaid, $_cuentaDest,$_tipo_movimiento){
        $con = new conectar();
        $db = $con->getDb();       
        $_txtTotal =str_replace(' ', '', $_txtTotal);
        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spu_tablaDetalle_pagos(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_idpagos,$_cobroOpago,$_tipopagoid,$_txtTotal, $_txtfecha, $_proveedorid,$_txtreferencia,$_PKREsponsable,$_stringToInsert, $_count_cadena_insert, $_stringToDelete, $_count_cadena_delete,$_textareaCoemtarios,$_cuentaid, $_cuentaDest,$_tipo_movimiento));
        $data[0] = ['status' => $status];
        //Se actualizo un pago
        $_SESSION["mensaje"] = "2";
            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
    public function Anticipo_updateDetails($_idpagos,$_cobroOpago,$_tipopagoid,$_txtTotal, $_txtfecha,$_txtreferencia,$_PKREsponsable,$_stringToInsert, $_count_cadena_insert, $_stringToDelete, $_count_cadena_delete,$_stringToUpdate,$_count_cadena_update,$_textareaCoemtarios,$_cuentaid, $_cuentaDest,$_tipo_movimiento){
        $con = new conectar();
        $db = $con->getDb();       
        $_txtTotal =str_replace(' ', '', $_txtTotal);
        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spu_tablaDetalle_anticipos_pagos(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_idpagos,$_cobroOpago,$_tipopagoid,$_txtTotal, $_txtfecha,$_txtreferencia,$_PKREsponsable,$_stringToInsert, $_count_cadena_insert, $_stringToDelete, $_count_cadena_delete,$_stringToUpdate,$_count_cadena_update,$_textareaCoemtarios,$_cuentaid, $_cuentaDest,$_tipo_movimiento));
        $data[0] = ['status' => $status];
        $_SESSION["mensaje"] = "3";
            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
    public function validateUpdate($_idpagos,$_stringToDelete,$_stringToUpdate){

        $con = new conectar();
        $db = $con->getDb();  
        
        $db2 = $con->getDb();
        $Id_ultimas=array();
        $idMaxDate = array();
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $flag = true;
            //Trae solo los Ids de las cuentas que se pagaron en el pago
            $idsM = $db2->prepare("SELECT  mv.cuenta_pagar_id 
            FROM movimientos_cuentas_bancarias_empresa as mv
            where (mv.id_pago = $_idpagos);");
            $idsM->execute();
    
            //Consulta los movimientos del pago
            $all = $db2->prepare("SELECT  mv.PKMovimiento, pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, mv.Retiro,
            subtotal,importe,cp.saldo_insoluto, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
            FROM cuentas_por_pagar as cp 
                inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
                inner join estatus_factura as stat on cp.estatus_factura = stat.id
                inner join movimientos_cuentas_bancarias_empresa as mv on mv.cuenta_pagar_id = cp.id
                inner join pagos as pg on pg.idpagos = mv.id_pago
                where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5) and (pr.empresa_id = $PKEmpresa) and (mv.id_pago = $_idpagos);");
            $all->execute();
    
            $MaxDate="";
            //Consulta la fecha maxima de cada cuenta por pagar afectada por el pago
            //Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
            while (($row1 = $idsM->fetch()) !== false) {
                $MaxDate = $db2-> prepare("SELECT * FROM movimientos_cuentas_bancarias_empresa WHERE cuenta_pagar_id = ( ".$row1['cuenta_pagar_id']." ) ORDER by Fecha DESC  limit 1;");
                $MaxDate->execute();
                $Row_idMaxDate= $MaxDate->fetch();
                /////Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
                array_push($idMaxDate,$Row_idMaxDate['PKMovimiento']);
            }

            while (($row = $all->fetch()) !== false) {
                if($row["saldo_insoluto"]<=0){
                    //Si el id del movimiento esta en el array de los ultimos continua si hacer nada especifico
                    if(in_array ($row['PKMovimiento'],$idMaxDate )){
                      
                      ////Si algun movimiento del pago a eliminar no es el ultimo registrado para la cuenta por pagar no deja eliminarlo
                    }else{
                        return $data[0] = "0";
                    }
                  }
            }
    }
}
class Get_datas{
        public function loadCmbProviders(){
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf('call spc_Combo_Proveedores_OrdenCompra(?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKEmpresa));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function loadCmbCuentas(){
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf('call spc_Combo_Cuentas(?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKEmpresa));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function loadCmbCuentasCheques(){
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf('call spc_Combo_Cuentas_cheques(?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKEmpresa));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function loadCmbCuentasOtras(){
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf('call spc_Combo_Cuentas_otras(?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKEmpresa));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
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
                                where empresa_id = :idEmpresa and estatus= 1
                                
                                union 
                                
                                select
                                    PKCategoria, 
                                    Nombre 
                                from 
                                    categoria_gastos 
                                where PKCategoria = 1) as cat ORDER BY cat.PKCategoria;
                            ");
            $stmt = $db->prepare($query);
            $stmt->bindValue(':idEmpresa',$PKEmpresa);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function loadCmbSubcategorias($value)
        {
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf("select PKSubcategoria, Nombre from subcategorias_gastos where FKCategoria = :categoria");
            $stmt = $db->prepare($query);
            $stmt->bindValue(':categoria',$value);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function validateimportes($id,$importe){
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf('call spc_ValidarInsoluto_pagos(?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($id,$importe));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function Update_validateimportes($id,$importe,$pago){
            $con = new conectar();
            $db = $con->getDb();

            $PKEmpresa = $_SESSION["IDEmpresa"];

            $query = sprintf('call spc_ValidarInsoluto_pagos_edit(?,?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($id,$importe,$pago));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function Add_Validate_importe($ids,$_origenCE,$_cadena_CP,$counta_cadena){
            $con = new conectar();
            $db = $con->getDb();
            $query = sprintf('call spc_Add_Validate_importePago(?,?,?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($ids,$_origenCE,$_cadena_CP,$counta_cadena));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function Add_Validate_importeAnticipos($_origenCE,$_cadena_CP,$_cadena_CP_insolutos,$counta_cadena,$_idpagos){
            $con = new conectar();
            $db = $con->getDb();
            $query = sprintf('call spc_Update_Validate_importeAnticipo(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($_origenCE,$_cadena_CP,$_cadena_CP_insolutos,$counta_cadena,$_idpagos));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function Validate_importeAnticipos($_origenCE,$_cadena_CP,$_cadena_CP_insolutos,$counta_cadena){
            $con = new conectar();
            $db = $con->getDb();
            $query = sprintf('call spc_Add_Validate_importeAnticipo(?,?,?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($_origenCE,$_cadena_CP,$_cadena_CP_insolutos,$counta_cadena));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public function Validate_importePagoLibre($_origenCE,$total){
            $con = new conectar();
            $db = $con->getDb();
            $query = sprintf('call spc_Add_Validate_importePagoLibre(?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($_origenCE,$total));

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        function getExpenseCategory($value)
        {
            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf("
                    select 
                        cat.PKCategoria categoria_id,
                        subcat.PKSubcategoria subcategoria_id 
                    from cuentas_por_pagar c
                    inner join categoria_gastos cat on c.categoria_id = cat.PKCategoria
                    inner join subcategorias_gastos subcat on c.subcategoria_id = subcat.PKSubcategoria
                    where c.id = :id
                    ");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id",$value);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
class save_datas{
    public function insertPago($tipoPago, $Comentarios, $total, $tipoMovimiento,$ramdon_relacion)
    {
        $con = new conectar();
        $db = $con->getDb();
        $_total =str_replace(' ', '', $total);

        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spi_pagos_AgregarPago(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($tipoPago,$Comentarios, $_total,$ramdon_relacion, $tipoMovimiento ));
        if (!$status) {
            echo "\nPDO::errorInfo():\n";
            print_r($stmt->errorCode());
            $data[0] = ['status' => $status];
        }else{
            $data[0] = ['status' => $status];
        }

            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } 
    public function insertmovi( $_Descripcion, $_Retiro, $_Referencia,$_cuenta_origen_id,$_cuenta_pagar_id,$_ramdonstring)
    {
        $con = new conectar();
        $db = $con->getDb();
        $_Retiro =str_replace(' ', '', $_Retiro);
        

        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spi_pago_agregarmovimiento(?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_Descripcion, $_Retiro, $_Referencia,$_cuenta_origen_id,$_cuenta_pagar_id,$_ramdonstring ));
        $data[0] = ['status' => $status];

            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

    } 
    public function insertPagoMovimi( $_PKuser,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP, $count_cadena,$_tipoPago,$_comentarios,$_total, $tipo_movi, $_origenCE, $_cuentaDest,$_fecha_pago,$_categoria,$_subcategoria){
        $con = new conectar();
        $db = $con->getDb();       
        $_total =str_replace(' ', '', $_total);
        //echo($_fecha_pago);
        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spc_tablaDetalle_cuentasCobrar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_PKuser,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP, $count_cadena,$_tipoPago,$_comentarios,$_total,$tipo_movi,$_origenCE,$_cuentaDest,$_fecha_pago,$PKEmpresa,0,"",$_categoria,$_subcategoria));
        
        $data[0] = ['status' => $status];
            /// Se ingreso pago 
            $_SESSION["mensaje"] = "1";
            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    } 
    public function insertPagoLibre( $_PKuser,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP,$_tipoPago,$_comentarios,$_total, $tipo_movi, $_origenCE, $_cuentaDest,$_fecha_pago,$_categoria,$_subcategoria){
        $con = new conectar();
        $db = $con->getDb();       
        $_total =str_replace(' ', '', $_total);
        //echo($_fecha_pago);
        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spi_PagoLibre(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_PKuser,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP,$_tipoPago,$_comentarios,$_total,$tipo_movi,$_origenCE,$_cuentaDest,$_fecha_pago,$PKEmpresa,0,"",$_categoria,$_subcategoria));
        
        $data[0] = ['status' => $status];
            /// Se ingreso pago 
            $_SESSION["mensaje"] = "1";
            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
}

class Delete_data{
    ////////Eliminar si el movimiento de todas las facturas afectadas es el ultimo/////////
    ////Elimina el pago y sus movimientos (estatus = 0) cuando los movimientos son los ultimos registrados para esa cuenta por pagar.
    //////////
    public function deletePago( $_idpagos,$_origen)
    {
        $con = new conectar();
        $db = $con->getDb();
        $db2 = $con->getDb();
        $Id_ultimas=array();
        $idMaxDate = array();
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $flag = true;
            //Trae solo los Ids de las cuentas que se pagaron en el pago
            $idsM = $db2->prepare("SELECT  mv.cuenta_pagar_id 
            FROM movimientos_cuentas_bancarias_empresa as mv
            where (mv.id_pago = $_idpagos);");
            $idsM->execute();
    
            //Consulta los movimientos del pago
            $all = $db2->prepare("SELECT  mv.PKMovimiento, pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, mv.Retiro,
            subtotal,importe,cp.saldo_insoluto, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
            FROM cuentas_por_pagar as cp 
                inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
                inner join estatus_factura as stat on cp.estatus_factura = stat.id
                inner join movimientos_cuentas_bancarias_empresa as mv on mv.cuenta_pagar_id = cp.id
                inner join pagos as pg on pg.idpagos = mv.id_pago
                where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5) and (pr.empresa_id = $PKEmpresa) and (mv.id_pago = $_idpagos);");
            $all->execute();

            if($all->rowCount() == 0){
                $continuar = false;
                $stmt = $db2->prepare("SELECT mcbe.cuenta_origen_id, pg.total 
                FROM pagos as pg
                  inner join movimientos_cuentas_bancarias_empresa as mcbe on pg.idpagos = mcbe.id_pago
                  where (pg.idpagos =$_idpagos);");
                $stmt->execute();
                $row = $stmt->fetch();
                $cuenta_origen = $row['cuenta_origen_id'];
                $total = $row['total'];

                $stmt = $db2->prepare("DELETE FROM pagos where (idpagos =$_idpagos);");
                $stmt->execute();

                $stmt = $db2->prepare("DELETE FROM movimientos_cuentas_bancarias_empresa 
                  where (id_pago =$_idpagos);");
                $stmt->execute();

                $stmt = $db2->prepare("SELECT tipo_cuenta from cuentas_bancarias_empresa where (PKCuenta = $cuenta_origen);");
                $stmt->execute();
                $tipo_cuenta = $stmt->fetch();
                
                switch($tipo_cuenta['tipo_cuenta']){
                    case 1:
                        $stmt = $db2->prepare("UPDATE cuentas_cheques SET Saldo_Inicial = (Saldo_Inicial + $total) where (FKCuenta = $cuenta_origen);");
                        $stmt->execute();
                        return $data[0] = "1";
                    break;
                    case 2:
                        $stmt = $db2->prepare("UPDATE cuentas_credito SET Credito_Utilizado = (Credito_Utilizado + $total) where (FKCuenta = $cuenta_origen);");
                        $stmt->execute();
                        return $data[0] = "1";
                    break;
                    case 3:
                        $stmt = $db2->prepare("UPDATE cuentas_otras SET Saldo_Inicial = (Saldo_Inicial + $total) where (FKCuenta = $cuenta_origen);");
                        $stmt->execute();
                        return $data[0] = "1";
                    break;
                    case 4:
                        $stmt = $db2->prepare("UPDATE cuenta_caja_chica SET SaldoInicialCaja = (SaldoInicialCaja + $total) where (FKCuenta = $cuenta_origen);");
                        $stmt->execute();
                        return $data[0] = "1";
                    break;
                }
            }else{
                $continuar = true;
            }
            if($continuar){
                $MaxDate="";
                //Consulta la fecha maxima de cada cuenta por pagar afectada por el pago
                //Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
                while (($row1 = $idsM->fetch()) !== false) {
                    $MaxDate = $db2-> prepare("SELECT * FROM movimientos_cuentas_bancarias_empresa WHERE cuenta_pagar_id = ( ".$row1['cuenta_pagar_id']." ) ORDER by Fecha DESC  limit 1;");
                    $MaxDate->execute();
                    $Row_idMaxDate= $MaxDate->fetch();
                    /////Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
                    array_push($idMaxDate,$Row_idMaxDate['PKMovimiento']);
                }
        
                while (($row = $all->fetch()) !== false) {
                    if($row["saldo_insoluto"]<=0){
                        //Si el id del movimiento esta en el array de los ultimos continua si hacer nada especifico
                        if(in_array ($row['PKMovimiento'],$idMaxDate )){
                        
                        ////Si algun movimiento del pago a eliminar no es el ultimo registrado para la cuenta por pagar no deja eliminarlo
                        }else{
                        $flag =false;
                        }
                    }
                }
                ///Si flag es true(Todos los movimientos de la cuenta por pagar afectada por el pago son los ultimos respectivamente).
                    ////Elimina el pago y sus movimientos poniendo estatus en 0
                if($flag){
                    try{
                        $query = sprintf('call spd_EliminarPago(?)');
                        $stmt = $db->prepare($query);
                        $status = $stmt->execute(array($_idpagos));
                        $data[0] = ['status' => $status];
                        // Se elimino un registro.
                        if($_origen!=1){
                            $_SESSION["mensaje"] = "4";
                        }
                            return $data;
                        
                        } catch (PDOException $e) {
                            return "Error en Consulta: " . $e->getMessage();
                        }
                    ///Si no, retorna 0 para mostrar alerta.
                }else{
                    return $data[0] = "0";
                }
            }
            


    }
}
?>