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
    public function loadCmbsucursal(){
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call `spc_Combo_Sucursales_Origen`(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function validate_seriefolio($idProveedor,$serie,$folio){
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call `spc_ValidarClave-Serie_CuentasPagar`(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idProveedor,$folio,$serie));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function validate_seriefolio_toUpdate($idProveedor,$serie,$folio,$cuenta){
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call `spc_ValidarClave-Serie_CuentasPagar_ToUpdate`(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idProveedor,$folio,$serie,$cuenta));

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
                        where empresa_id = :idEmpresa and estatus = 1
                        
                        union 
                        
                        select
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where PKCategoria = 1) as cat ORDER BY cat.PKCategoria");
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
}
class Save_data{
    function insertcuenta($_PKUsuario,$_proveedor,$_sucursal,$_txtNoDocumento,$_txtSerie,$_txtSubtotal,$_txtIva,$_txtIEPS,$_txtImporte,$_txtDescuento,$_fecha,$_fechavenci,$_radiodoc,$_cat,$_subcat,$_comentarios){
        $con = new conectar();
        $db = $con->getDb();       
        $PKEmpresa = $_SESSION["IDEmpresa"];
        try{
        $query = sprintf('call spi_Add_CuentaPagar_Manual(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_PKUsuario,$_proveedor,$_sucursal,$_txtNoDocumento,$_txtSerie,$_txtSubtotal,$_txtIva,$_txtIEPS,$_txtImporte,$_txtDescuento,$_fecha,$_fechavenci,$_radiodoc,$_cat,$_subcat,$_comentarios));
        
        $data[0] = ['status' => $status];

            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
    function updatecuenta($_cuenta,$_PKUsuario,$_txtSerie,$_txtSubtotal,$_txtIva,$_txtIEPS,$_txtImporte,$_txtDescuento,$_fecha,$_radiodoc){
        $con = new conectar();
        $db = $con->getDb();       
        try{
        $query = sprintf('call spu_update_CuentaPagar_Manual(?,?,?,?,?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($_cuenta,$_PKUsuario,$_txtSerie,$_txtSubtotal,$_txtIva,$_txtIEPS,$_txtImporte,$_txtDescuento,$_fecha,$_radiodoc));
        
        $data[0] = ['status' => $status];

            return $data;
        
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
}

class update_data
{
    function updateCategory($id,$value,$value1)
    {
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf("update cuentas_por_pagar set categoria_id = :cat,subcategoria_id = :subcat where id= :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":cat",$value);
        $stmt->bindValue(":subcat",$value1);
        $stmt->bindValue(":id",$id);
        return $stmt->execute();
    }

    function updateSubcategory($id,$value)
    {
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf("update cuentas_por_pagar set subcategoria_id = :subcat where id= :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":subcat",$value);
        $stmt->bindValue(":id",$id);
        return $stmt->execute();
    }
}

class delete_data
{
    function deleteCuentaPagar($id)
    {
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf("call spd_EliminarCuentaPagar(:id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$id);
        return $stmt->execute();
    }
}