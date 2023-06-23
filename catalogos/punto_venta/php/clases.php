<?php

  session_start();
  
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];
  require '../../../vendor/autoload.php';
  require_once('../../../lib/TCPDF/tcpdf.php');

  use Mike42\Escpos\Printer;
  use Mike42\Escpos\EscposImage;
  use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
  use Mike42\Escpos\CapabilityProfile;
  
  class conection
  { //Llamado al archivo de la conexión.
  
    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
    
  }

  class get_data
  {
    function getCountCashRegisterAccounts()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select * from cuentas_bancarias_empresa cbe
                            inner join cuentas_punto_venta cpv on cbe.PKCuenta = cpv.cuenta_empresa_id
                            where 
                            tipo_cuenta = 5 and 
                            empresa_id = :empresa");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':empresa',$_SESSION['IDEmpresa'],PDO::PARAM_INT);
        $stmt->execute();

        $rowCount = $stmt->rowCount();

        $stmt = null;
        $db = null;

        return $rowCount;

    }

    function getBranchOffices()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select id,sucursal texto from sucursales where empresa_id= {$_SESSION['IDEmpresa']}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getResponsible()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select e.PKEmpleado id,concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) texto from empleados e
                            inner join relacion_tipo_empleado re on e.PKEmpleado = re.empleado_id
                            where empresa_id= {$_SESSION['IDEmpresa']} and re.tipo_empleado_id = 2");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getMoney()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select PKMoneda id, Clave texto from monedas where Estatus = 1");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCashRegister($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $id = $value !== null && $value !== "" ? " and cpv.id = {$value}" : "";

        $query = sprintf("select 
                            cpv.descripcion nombre, 
                            cpv.id caja_id,
                            s.id sucursal_id,
                            s.sucursal,
                            if(s.activar_inventario = 0,'Sin inventario','Con inventario') activar_inventario,
                            cpv.nombre_impresora 
                            from cuentas_punto_venta cpv
                            inner join sucursales s on cpv.sucursal_id = s.id
                            inner join cuentas_bancarias_empresa cbe on cpv.cuenta_empresa_id = cbe.PKCuenta
                            where cbe.empresa_id = {$_SESSION['IDEmpresa']}{$id}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCashRegisters($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            id, 
                            descripcion texto 
                            from cuentas_punto_venta cpv
                            inner join cuentas_bancarias_empresa cbe on cpv.cuenta_empresa_id = cbe.PKCuenta
                            where cbe.empresa_id = {$_SESSION['IDEmpresa']} and cpv.sucursal_id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProduct($value,$value1,$value2)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $arr = [];
        $query = "";
        
        if($value !== "")
        {
            if($value2 !== "")
            {
                $inventory = (int)$get_data->getCashRegisterHasInventory($value2)[0]->activar_inventario;
                
                if($inventory === 1)
                {
                    $query = sprintf("select
                                        p.ClaveInterna clave,
                                        p.PKProducto id,
                                        p.Nombre nombre,
                                        ifnull(sum(e.existencia),0) existencia,
                                        ifnull(sum(e.existencia_minima),0) stock_minimo,
                                        ifnull(sum(e.existencia_maxima),0) stock_maxima,
                                        ifnull(sum(e.punto_reorden),0) punto_reorden,
                                        ifnull(p.precio_venta1,0) precio_venta1,
                                        ifnull(p.precio_venta2,0) precio_venta2,
                                        ifnull(p.precio_venta3,0) precio_venta3,
                                        ifnull(p.precio_venta4,0) precio_venta4,
                                        ifnull(p.precio_compra_sin_impuesto,0) precio_compra_sin_impuesto,
                                        p.Imagen imagen,
                                        p.Descripcion descripcion,
                                        p.CodigoBarras codigo_barras,
                                        p.FKCategoriaProducto categoria_id,
                                        p.FKTipoProducto tipo_id,
                                        p.FKMarcaProducto marca_id,
                                        p.serie,
                                        p.lote,
                                        p.fecha_caducidad,
                                        p.precio_compra,
                                        p.precio_compra_neto,
                                        p.utilidad1,
                                        p.utilidad2,
                                        p.utilidad3,
                                        p.utilidad4,
                                        ifnull(p.precio_venta_neto1,0) precio_venta_neto1,
                                        ifnull(p.precio_venta_neto2,0) precio_venta_neto2,
                                        ifnull(p.precio_venta_neto3,0) precio_venta_neto3,
                                        ifnull(p.precio_venta_neto4,0) precio_venta_neto4,
                                        cls.PKClaveSAT clave_sat_id,
                                        concat(cls.Clave,' - ',cls.Descripcion) clave_sat,
                                        csu.PKClaveSATUnidad clave_sat_unidad_id,
                                        concat(csu.Clave,' - ',csu.Descripcion) clave_sat_unidad,
                                        p.receta
                                    from productos p
                                        left join existencia_por_productos e on p.PKProducto = e.producto_id
                                        left join costo_venta_producto c on p.PKProducto = c.FKProducto
                                        left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                        left join claves_sat cls on ifp.FKClaveSAT = cls.PKClaveSAT
                                        left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                        left join operaciones_producto op on p.PKProducto = op.FKProducto
                                    where 
                                        e.sucursal_id = {$value2} and
                                        op.Venta = 1 and
                                    (
                                        p.CodigoBarras like '%%{$value}%%' or 
                                        p.ClaveInterna like '%%{$value}%%' or
                                        p.Nombre like '%%{$value}%%'
                                    )
                                    group by p.PKProducto");
                } else {
                    if($value2 !== ""){
                        
                        $query = sprintf("select
                                            p.ClaveInterna clave,
                                            p.PKProducto id,
                                            p.Nombre nombre,
                                            ifnull(sum(e.existencia),0) existencia,
                                            ifnull(sum(e.existencia_minima),0) stock_minimo,
                                            ifnull(sum(e.existencia_maxima),0) stock_maxima,
                                            ifnull(sum(e.punto_reorden),0) punto_reorden,
                                            ifnull(p.precio_venta1,0) precio_venta1,
                                            ifnull(p.precio_venta2,0) precio_venta2,
                                            ifnull(p.precio_venta3,0) precio_venta3,
                                            ifnull(p.precio_venta4,0) precio_venta4,
                                            ifnull(p.precio_compra_sin_impuesto,0) precio_compra_sin_impuesto,
                                            p.Imagen imagen,
                                            p.Descripcion descripcion,
                                            p.CodigoBarras codigo_barras,
                                            p.FKCategoriaProducto categoria_id,
                                            p.FKTipoProducto tipo_id,
                                            p.FKMarcaProducto marca_id,
                                            p.serie,
                                            p.lote,
                                            p.fecha_caducidad,
                                            p.precio_compra,
                                            p.precio_compra_neto,
                                            p.utilidad1,
                                            p.utilidad2,
                                            p.utilidad3,
                                            p.utilidad4,
                                            ifnull(p.precio_venta_neto1,0) precio_venta_neto1,
                                            ifnull(p.precio_venta_neto2,0) precio_venta_neto2,
                                            ifnull(p.precio_venta_neto3,0) precio_venta_neto3,
                                            ifnull(p.precio_venta_neto4,0) precio_venta_neto4,
                                            cls.PKClaveSAT clave_sat_id,
                                            concat(cls.Clave,' - ',cls.Descripcion) clave_sat,
                                            csu.PKClaveSATUnidad clave_sat_unidad_id,
                                            concat(csu.Clave,' - ',csu.Descripcion) clave_sat_unidad,
                                            p.receta
                                        from productos p
                                            left join existencia_por_productos e on p.PKProducto = e.producto_id
                                            left join costo_venta_producto c on p.PKProducto = c.FKProducto
                                            left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                            left join claves_sat cls on ifp.FKClaveSAT = cls.PKClaveSAT
                                            left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                            left join operaciones_producto op on p.PKProducto = op.FKProducto
                                        where 
                                            p.empresa_id = {$_SESSION['IDEmpresa']} and
                                            op.Venta = 1 and
                                        (
                                            p.CodigoBarras like '%%{$value}%%' or 
                                            p.ClaveInterna like '%%{$value}%%' or
                                            p.Nombre like '%%{$value}%%'
                                        )
                                        group by p.PKProducto");
                    } else {
                        
                        $query = sprintf("select
                                p.ClaveInterna clave,
                                p.PKProducto id,
                                p.Nombre nombre,
                                ifnull(sum(e.existencia),0) existencia,
                                ifnull(sum(e.existencia_minima),0) stock_minimo,
                                ifnull(sum(e.existencia_maxima),0) stock_maxima,
                                ifnull(sum(e.punto_reorden),0) punto_reorden,
                                ifnull(p.precio_venta1,0) precio_venta1,
                                ifnull(p.precio_venta2,0) precio_venta2,
                                ifnull(p.precio_venta3,0) precio_venta3,
                                ifnull(p.precio_venta4,0) precio_venta4,
                                ifnull(p.precio_compra_sin_impuesto,0) precio_compra_sin_impuesto,
                                p.Imagen imagen,
                                p.Descripcion descripcion,
                                p.CodigoBarras codigo_barras,
                                p.FKCategoriaProducto categoria_id,
                                p.FKTipoProducto tipo_id,
                                p.FKMarcaProducto marca_id,
                                p.serie,
                                p.lote,
                                p.fecha_caducidad,
                                p.precio_compra,
                                p.precio_compra_neto,
                                p.utilidad1,
                                p.utilidad2,
                                p.utilidad3,
                                p.utilidad4,
                                ifnull(p.precio_venta_neto1,0) precio_venta_neto1,
                                ifnull(p.precio_venta_neto2,0) precio_venta_neto2,
                                ifnull(p.precio_venta_neto3,0) precio_venta_neto3,
                                ifnull(p.precio_venta_neto4,0) precio_venta_neto4,
                                cls.PKClaveSAT clave_sat_id,
                                concat(cls.Clave,' - ',cls.Descripcion) clave_sat,
                                csu.PKClaveSATUnidad clave_sat_unidad_id,
                                concat(csu.Clave,' - ',csu.Descripcion) clave_sat_unidad,
                                p.receta
                                from productos p
                                left join existencia_por_productos e on p.PKProducto = e.producto_id
                                left join costo_venta_producto c on p.PKProducto = c.FKProducto
                                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                left join claves_sat cls on ifp.FKClaveSAT = cls.PKClaveSAT
                                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                left join operaciones_producto op on p.PKProducto = op.FKProducto
                                where p.PKProducto = {$value1} and op.Venta = 1");
                    }

                    
                }
            }
        } else {
            if($value2 !== "")
            {
            $query = sprintf("select
                                p.ClaveInterna clave,
                                p.PKProducto id,
                                p.Nombre nombre,
                                ifnull(sum(e.existencia),0) existencia,
                                ifnull(sum(e.existencia_minima),0) stock_minimo,
                                ifnull(sum(e.existencia_maxima),0) stock_maxima,
                                ifnull(sum(e.punto_reorden),0) punto_reorden,
                                ifnull(p.precio_venta1,0) precio_venta1,
                                ifnull(p.precio_venta2,0) precio_venta2,
                                ifnull(p.precio_venta3,0) precio_venta3,
                                ifnull(p.precio_venta4,0) precio_venta4,
                                ifnull(p.precio_compra_sin_impuesto,0) precio_compra_sin_impuesto,
                                p.Imagen imagen,
                                p.Descripcion descripcion,
                                p.CodigoBarras codigo_barras,
                                p.FKCategoriaProducto categoria_id,
                                p.FKTipoProducto tipo_id,
                                p.FKMarcaProducto marca_id,
                                p.serie,
                                p.lote,
                                p.fecha_caducidad,
                                p.precio_compra,
                                p.precio_compra_neto,
                                p.utilidad1,
                                p.utilidad2,
                                p.utilidad3,
                                p.utilidad4,
                                ifnull(p.precio_venta_neto1,0) precio_venta_neto1,
                                ifnull(p.precio_venta_neto2,0) precio_venta_neto2,
                                ifnull(p.precio_venta_neto3,0) precio_venta_neto3,
                                ifnull(p.precio_venta_neto4,0) precio_venta_neto4,
                                cls.PKClaveSAT clave_sat_id,
                                concat(cls.Clave,' - ',cls.Descripcion) clave_sat,
                                csu.PKClaveSATUnidad clave_sat_unidad_id,
                                concat(csu.Clave,' - ',csu.Descripcion) clave_sat_unidad,
                                p.receta
                                from productos p
                                left join existencia_por_productos e on p.PKProducto = e.producto_id
                                left join costo_venta_producto c on p.PKProducto = c.FKProducto
                                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                left join claves_sat cls on ifp.FKClaveSAT = cls.PKClaveSAT
                                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                left join operaciones_producto op on p.PKProducto = op.FKProducto
                                where p.PKProducto = {$value1} and op.Venta = 1 and e.sucursal_id = {$value2}");
            } else {
            
            $query = sprintf("select
                                p.ClaveInterna clave,
                                p.PKProducto id,
                                p.Nombre nombre,
                                ifnull(sum(e.existencia),0) existencia,
                                ifnull(sum(e.existencia_minima),0) stock_minimo,
                                ifnull(sum(e.existencia_maxima),0) stock_maxima,
                                ifnull(sum(e.punto_reorden),0) punto_reorden,
                                ifnull(p.precio_venta1,0) precio_venta1,
                                ifnull(p.precio_venta2,0) precio_venta2,
                                ifnull(p.precio_venta3,0) precio_venta3,
                                ifnull(p.precio_venta4,0) precio_venta4,
                                ifnull(p.precio_compra_sin_impuesto,0) precio_compra_sin_impuesto,
                                p.Imagen imagen,
                                p.Descripcion descripcion,
                                p.CodigoBarras codigo_barras,
                                p.FKCategoriaProducto categoria_id,
                                p.FKTipoProducto tipo_id,
                                p.FKMarcaProducto marca_id,
                                p.serie,
                                p.lote,
                                p.fecha_caducidad,
                                p.precio_compra,
                                p.precio_compra_neto,
                                p.utilidad1,
                                p.utilidad2,
                                p.utilidad3,
                                p.utilidad4,
                                ifnull(p.precio_venta_neto1,0) precio_venta_neto1,
                                ifnull(p.precio_venta_neto2,0) precio_venta_neto2,
                                ifnull(p.precio_venta_neto3,0) precio_venta_neto3,
                                ifnull(p.precio_venta_neto4,0) precio_venta_neto4,
                                cls.PKClaveSAT clave_sat_id,
                                concat(cls.Clave,' - ',cls.Descripcion) clave_sat,
                                csu.PKClaveSATUnidad clave_sat_unidad_id,
                                concat(csu.Clave,' - ',csu.Descripcion) clave_sat_unidad,
                                p.receta
                                from productos p
                                left join existencia_por_productos e on p.PKProducto = e.producto_id
                                left join costo_venta_producto c on p.PKProducto = c.FKProducto
                                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                left join claves_sat cls on ifp.FKClaveSAT = cls.PKClaveSAT
                                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                left join operaciones_producto op on p.PKProducto = op.FKProducto
                                where p.PKProducto = {$value1} op.Venta = 1");
            }
        }

        $stmt = $db->prepare($query);
        $stmt->execute();
                
        $aux = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;
        
        foreach($aux as $r => $v){
            
            $imagen = $v->imagen !== null && $v->imagen !== "" && $v->imagen ? $_ENV['RUTA_ARCHIVOS_READ'].$_SESSION['IDEmpresa']."/img/".$v->imagen : "";
            $codigo_barras = $v->codigo_barras !== "" && $v->codigo_barras !== null ? $v->codigo_barras : "N/A";
            array_push($arr,[
            "clave" => str_replace('"','\"',str_replace(['\r','\n'],"",$v->clave)),
            "id" => $v->id,
            "nombre" => str_replace('"','\"',str_replace(['\r','\n'],"",$v->nombre)),
            "existencia"=> $v->existencia,
            "stock_minimo" => $v->stock_minimo,
            "stock_maxima" => $v->stock_maxima,
            "punto_reorden" => $v->punto_reorden,
            "precio_venta1" => $v->precio_venta1,
            "precio_venta2" => $v->precio_venta2,
            "precio_venta3" => $v->precio_venta3,
            "precio_venta4" => $v->precio_venta4,
            "precio_compra_sin_impuesto" => $v->precio_compra_sin_impuesto,
            "imagen" => $imagen,
            "descripcion" => str_replace('"', '\"', $v->descripcion),
            "codigo_barras"=>$codigo_barras,
            "categoria_id"=>$v->categoria_id,
            "tipo_id"=>$v->tipo_id,
            "marca_id"=>$v->marca_id,
            "serie"=>$v->serie,
            "lote"=>$v->lote,
            "fecha_caducidad"=>$v->fecha_caducidad,
            "precio_compra"=>$v->precio_compra,
            "precio_compra_neto"=>$v->precio_compra_neto,
            "utilidad1" =>$v->utilidad1,
            "utilidad2" =>$v->utilidad2,
            "utilidad3" =>$v->utilidad3,
            "utilidad4" =>$v->utilidad4,
            "precio_venta_neto1" => $v->precio_venta_neto1,
            "precio_venta_neto2" => $v->precio_venta_neto2,
            "precio_venta_neto3" => $v->precio_venta_neto3,
            "precio_venta_neto4" => $v->precio_venta_neto4,
            "clave_sat_id" => $v->clave_sat_id,
            "clave_sat" => $v->clave_sat,
            "clave_sat_unidad_id" => $v->clave_sat_unidad_id,
            "clave_sat_unidad" => $v->clave_sat_unidad,
            "sucursal" => $value2,
            "receta" => $v->receta,
            "functions" => ""
            ]);

        }
        
        return $arr;
    }

    function getProducts($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        

        if($value !== "" && $value !== null){
            $inventory = (int)$get_data->getCashRegisterHasInventory($value)[0]->activar_inventario;

            if($inventory === 1){
            $query = sprintf("select
                                p.PKProducto id,
                                p.Nombre nombre,
                                p.ClaveInterna clave,
                                p.CodigoBarras codigo_barras,
                                if(sum(e.existencia) is null or sum(e.existencia) = '',0,sum(e.existencia)) existencia,
                                p.precio_venta_neto1 precio_u,
                                p.precio_venta1 precio_n,
                                s.sucursal
                            from productos p
                                left join existencia_por_productos e on p.PKProducto = e.producto_id
                                left join costo_venta_producto c on p.PKProducto = c.FKProducto
                                left join sucursales s on e.sucursal_id = s.id
                            where e.sucursal_id = {$value}
                            group by p.PKProducto
                            order by p.Nombre asc
                            ");
            } else {
            $query = sprintf("select
                            p.PKProducto id,
                            p.Nombre nombre,
                            p.ClaveInterna clave,
                            p.CodigoBarras codigo_barras,
                            if(sum(e.existencia) is null or sum(e.existencia) = '',0,sum(e.existencia)) existencia,
                            p.precio_venta_neto1 precio_u,
                            p.precio_venta1 precio_n,
                            s.sucursal
                            from productos p
                            left join existencia_por_productos e on p.PKProducto = e.producto_id
                            left join costo_venta_producto c on p.PKProducto = c.FKProducto
                            left join sucursales s on e.sucursal_id = s.id
                            where p.empresa_id = {$_SESSION['IDEmpresa']}
                            group by p.PKProducto
                            order by p.Nombre asc
                        ");
            }
        } else {
            $query = sprintf("select
                            p.PKProducto id,
                            p.Nombre nombre,
                            p.ClaveInterna clave,
                            p.CodigoBarras codigo_barras,
                            if(sum(e.existencia) is null or sum(e.existencia) = '',0,sum(e.existencia)) existencia,
                            p.precio_venta_neto1 precio_u,
                            p.precio_venta1 precio_n,
                            s.sucursal
                            from productos p
                            left join existencia_por_productos e on p.PKProducto = e.producto_id
                            left join costo_venta_producto c on p.PKProducto = c.FKProducto
                            left join sucursales s on e.sucursal_id = s.id
                            where p.empresa_id = {$_SESSION['IDEmpresa']}
                            group by p.PKProducto
                            order by p.Nombre asc
                        ");
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
      
    }

    function getClients()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select * clientes from empresa_id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClientsSelect()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            PKCliente id,
                            razon_social texto 
                            from clientes where empresa_id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFormatDatatableSearchProducts($value)
    {
        $get_data = new get_data();
        $array = $get_data->getProducts($value);
        $data_format = "";

        foreach($array as $r => $value){
            $clave = "<a href='#' data-id='". $value->id ."' data-toggle='modal' data-target='#modal_update_product'>".$value->clave."</i></a>";
            $data_format .= '{
                "id" : "' . $value->id . '",
                "nombre" : "' . str_replace('"','\"',str_replace(['\r','\n'],"",$value->nombre)) . '",
                "clave" : "' . str_replace('"','\"',str_replace(['\r','\n'],"",$clave)) . '",
                "codigo_barras" : "' . $value->codigo_barras . '",
                "existencia" : "' . number_format($value->existencia,2) . '",
                "precio_u" : "' . number_format($value->precio_u,2) . '",
                "precio_n" : "' . number_format($value->precio_n,2) . '",
                "existencia_noFormat" : "' . $value->existencia . '",
                "precio_u_noFormat" : "' . $value->precio_u . '",
                "precio_n_noFormat" : "' . $value->precio_n . '",
                "sucursal" : "' . str_replace('"','\"',str_replace(['\r','\n'],"",$value->sucursal)) . '",
                "funciones" : ""
            },';
        }

        $data_format = substr($data_format, 0, strlen($data_format) - 1);

        return '{"data":[' . $data_format . ']}';
    }

    function getTaxSelect()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select PKImpuesto id,Nombre texto from impuesto where PKImpuesto <> 13 and PKImpuesto <> 14 and PKImpuesto <> 15 and PKImpuesto <> 17");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getRateOrFeeSelect($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select Tasa id, Tasa texto from impuesto_tasas where FKImpuesto = :value");
        $stmt = $db->prepare($query);
        $stmt->execute([":value"=>$value]);

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductCategories()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select PKCategoriaProducto id, CategoriaProductos texto from categorias_productos where empresa_id = {$_SESSION['IDEmpresa']} and estatus = 1
                            union
                            select PKCategoriaProducto id, CategoriaProductos texto from categorias_productos where PKCategoriaProducto = 1
                            order by id asc");
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductTradeMark()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select PKMarcaProducto id, MarcaProducto texto from marcas_productos where empresa_id = {$_SESSION['IDEmpresa']} and estatus = 1
                            union
                            select PKMarcaProducto id, MarcaProducto texto from marcas_productos where PKMarcaProducto = 1
                            order by id asc;");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClvProductServ()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select PKClaveSAT id, Clave clave, Descripcion descripcion  from claves_sat limit 100");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClvProductServSearch($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("SELECT PKClaveSAT id,Clave clave, Descripcion descripcion FROM claves_sat WHERE Clave LIKE :q OR Descripcion LIKE :q1 LIMIT 100");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":q", "%" . $value . "%");
        $stmt->bindValue(":q1", "%" . $value . "%");
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClvProductUnit()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("SELECT PKClaveSATUnidad id, Clave clave, Descripcion descripcion from claves_sat_unidades order by orden desc limit 100");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClvProductUnidSearch($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("SELECT PKClaveSATUnidad id,Clave clave, Descripcion descripcion FROM claves_sat_unidades WHERE Clave LIKE :q OR Descripcion LIKE :q1 order by orden desc LIMIT 100");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":q", "%" . $value . "%");
        $stmt->bindValue(":q1", "%" . $value . "%");
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductTaxes($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select i.PKImpuesto id, i.Nombre nombre,ip.Tasa tasa from impuestos_productos ip
                            inner join info_fiscal_productos f on ip.FKInfoFiscalProducto = f.PKInfoFiscalProducto
                            inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
                            where f.FKProducto = :id;
                        ");
        $stmt = $db->prepare($query);
        $stmt->execute([":id"=>$value]);

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductTaxesTable($value)
    {
        $get_data = new get_data();
        $arr = $get_data->getProductTaxes($value);
        $table = "";
        $edit = "<a href='#' id='delete_taxUpdate'><i class='fas fa-times-circle' style='color:red'></i></a>";

        foreach($arr as $r => $v){
            $table .= '
            {
            "id" : "' . $v->id . '",
            "nombre" : "' . str_replace('"','\"',str_replace(['\r','\n'],"",$v->nombre))  . '",
            "tasa" : "' . $v->tasa . '",
            "funciones" : "' . $edit . '"
            },';
        }

        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    function getProductIsTable($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        
        $query = sprintf("select * from productos_ticket_temp where producto_id = {$value} and caja_id = {$value1} and usuario_id = {$_SESSION['PKUsuario']}");
       
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductsFormatTable($value,$value1,$value2,$value3,$value4)
    {
        $get_data = new get_data();
        $save_data = new save_data();
        $update_data = new update_data();
        $inventory = (int)$get_data->getCashRegisterHasInventory($value1)[0]->activar_inventario;
        if($inventory === 1) {
            $products_general_data = $get_data->getProduct("",$value,$value1);
        } else {
            $products_general_data = $get_data->getProduct("",$value,"");
        }
        
        $taxes_product = $get_data->getProductTaxes($products_general_data[0]["id"]);
        $subtotal = (double)$products_general_data[0]["precio_venta1"] * $value2;
        $format_taxes = $get_data->getCalculatingTaxes($taxes_product,$subtotal,$value2,$products_general_data[0]["precio_compra_neto"]);

        $total = $subtotal + $format_taxes['total'];

        $data_format = [
            "producto_id" => $value,
            "cantidad" => $value2,
            "precio_u" => (double)$products_general_data[0]["precio_venta1"],
            "subtotal" => $subtotal,
            "tasa_iva" => $format_taxes['tasa_iva'],
            "importe_iva" => $format_taxes['importe_iva'],
            "ieps_tasa" => $format_taxes['ieps_tasa'],
            "importe_ieps" => $format_taxes['importe_ieps'],
            "ieps_monto_fijo" => $format_taxes['ieps_monto_fijo'],
            "importe_ieps_monto_fijo" => $format_taxes['importe_ieps_monto_fijo'],
            "ish_tasa" => $format_taxes['ish_tasa'],
            "importe_ish" => $format_taxes['importe_ish'],
            "iva_exento" => $format_taxes['iva_exento'],
            "iva_retenido_tasa" => $format_taxes['iva_retenido_tasa'],
            "importe_iva_retenido" => $format_taxes['importe_iva_retenido'],
            "isr_tasa" => $format_taxes['isr_tasa'],
            "importe_isr" => $format_taxes['importe_isr'],
            "isn_tasa" => $format_taxes['isn_tasa'],
            "importe_isn" => $format_taxes['importe_isn'],
            "cedular_tasa" => $format_taxes['cedular_tasa'],
            "importe_cedular" => $format_taxes['importe_cedular'],
            "cinco_al_millar" => $format_taxes['cinco_al_millar'],
            "importe_5_al_millar" => $format_taxes['importe_5_al_millar'],
            "funcion_publica_tasa" => $format_taxes['funcion_publica_tasa'],
            "importe_funcion_publica" => $format_taxes['importe_funcion_publica'],
            "ieps_retenido_tasa" => $format_taxes['ieps_retenido_tasa'],
            "importe_ieps_retenido" => $format_taxes['importe_ieps_retenido'],
            "ieps_exento" => $format_taxes['ieps_exento'],
            "ieps_retenido_monto_fijo" => $format_taxes['ieps_retenido_monto_fijo'],
            "importe_ieps_retenido_monto_fijo" => $format_taxes['importe_ieps_retenido_monto_fijo'],
            "total" => $total,
            "caja_id" => $value3
        ];
        
        
        if(count($get_data->getProductIsTable($value,$value3)) === 0){
            $ban = $save_data->saveProductsTblTemp($data_format);
        } else {
            $ban = $update_data->updateProductsTableTemp($data_format);
        }
        
        return $ban;

    }

    function getTaxesForSale($id)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select * from detalle_ticket_punto_venta where ticket_id = :id order by producto_id asc");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$id);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
        $aux = [];
        foreach ($arr as $r) {
            if ($r->iva_tasa !== null && $r->iva_tasa !== '') {
                array_push($aux, [
                    "id" => 1,
                    "nombre" => "IVA",
                    "tasa" => $r->iva_tasa,
                    "total" => $r->importe_iva,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->ieps_tasa  !== null && $r->ieps_tasa !== '') {
                array_push($aux, [
                    "id" => 2,
                    "nombre" => "IEPS",
                    "tasa" => $r->ieps_tasa,
                    "total" => $r->importe_ieps,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->ieps_monto_fijo !== null && $r->ieps_monto_fijo !== '') {
                array_push($aux, [
                    "id" => 3,
                    "nombre" => "IEPS (Monto fijo)",
                    "tasa" => $r->ieps_monto_fijo,
                    "total" => $r->importe_ieps_monto_fijo,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->ish_tasa !== null && $r->ish_tasa !== '') {
                array_push($aux, [
                    "id" => 4,
                    "nombre" => "ISH",
                    "tasa" => $r->ish_tasa,
                    "total" => $r->importe_ish,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->iva_retenido_tasa !== null && $r->iva_retenido_tasa !== '') {
                array_push($aux, [
                    "id" => 6,
                    "nombre" => "IVA Retenido",
                    "tasa" => $r->iva_retenido_tasa,
                    "total" => $r->importe_iva_retenido,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->isr_tasa !== null && $r->isr_tasa !== '') {
                array_push($aux, [
                    "id" => 7,
                    "nombre" => "ISR",
                    "tasa" => $r->isr_tasa,
                    "total" => $r->importe_isr,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->isn_tasa !== null && $r->isn_tasa !== '') {
                array_push($aux, [
                    "id" => 8,
                    "nombre" => "ISN (Local)",
                    "tasa" => $r->isn_tasa,
                    "total" => $r->importe_isn,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->cedular_tasa !== null && $r->cedular_tasa !== '') {
                array_push($aux, [
                    "id" => 9,
                    "nombre" => "Cedular",
                    "tasa" => $r->cedular_tasa,
                    "total" => $r->importe_cedular,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->cinco_al_millar !== null && $r->cinco_al_millar !== '') {
                array_push($aux, [
                    "id" => 10,
                    "nombre" => "5 al millar (Local)",
                    "tasa" => $r->cinco_al_millar,
                    "total" => $r->importe_5_al_millar,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->funcion_publica_tasa !== null && $r->funcion_publica_tasa !== '') {
                array_push($aux, [
                    "id" => 11,
                    "nombre" => "Función pública",
                    "tasa" => $r->funcion_publica_tasa,
                    "total" => $r->importe_funcion_publica,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->ieps_retenido_tasa !== null && $r->ieps_retenido_tasa !== '') {
                array_push($aux, [
                    "id" => 15,
                    "nombre" => "IEPS Retenido",
                    "tasa" => $r->ieps_retenido_tasa,
                    "total" => $r->importe_ieps_retenido,
                    "producto_id" => $r->producto_id
                ]);
            }
            if ($r->ieps_retenido_monto_fijo !== null && $r->ieps_retenido_monto_fijo !== '') {
                array_push($aux, [
                    "id" => 18,
                    "nombre" => "IEPS Retenido (Monto fijo)",
                    "tasa" => $r->ieps_retenido_monto_fijo,
                    "total" => $r->importe_ieps_retenido_monto_fijo,
                    "producto_id" => $r->producto_id
                ]);
            }
        }

        return json_encode($aux);
    }

    function getCalculatingTaxes($value,$value1,$value2,$value3)
    {
        $tasa_iva = null;
        $importe_iva = null;
        $ieps_tasa = null;
        $importe_ieps = null;
        $ieps_monto_fijo = null;
        $importe_ieps_monto_fijo = null;
        $ish_tasa = null;
        $importe_ish = null;
        $iva_exento = null;
        $iva_retenido_tasa = null;
        $importe_iva_retenido = null;
        $isr_tasa = null;
        $importe_isr = null;
        $isn_tasa = null;
        $importe_isn = null;
        $cedular_tasa = null;
        $importe_cedular = null;
        $cinco_al_millar = null;
        $importe_5_al_millar = null;
        $funcion_publica_tasa = null;
        $importe_funcion_publica = null;
        $ieps_retenido_tasa = null;
        $importe_ieps_retenido = null;
        $ieps_exento = null;
        $ieps_retenido_monto_fijo = null;
        $importe_ieps_retenido_monto_fijo = null;

        $subtotal = $value1;
        $total_impuesto = 0;

        foreach($value as $r => $v){

            switch((int)$v->id){
            case 1:
                $tasa_iva = $v->tasa;
                $importe_iva = (double)$subtotal * ($v->tasa/100);
                if($ieps_tasa !== null){
                $importe_iva += (double)$importe_ieps * ($v->tasa/100);
                }
                if($ieps_monto_fijo !== null){
                $importe_iva += (double)$importe_ieps_monto_fijo * ($v->tasa/100);
                }
                $total_impuesto += $importe_iva;
            break;
            case 2:
                $ieps_tasa = $v->tasa;
                $importe_ieps = (double)$subtotal * ($v->tasa/100);
                if($tasa_iva !== null){
                $importe_iva += (double)$importe_ieps * ($tasa_iva/100);
                $total_impuesto += (double)$importe_ieps * ($tasa_iva/100);
                }
                $total_impuesto += $importe_ieps;
            break;
            case 3:
                $ieps_monto_fijo = $v->tasa;
                $importe_ieps_monto_fijo = $value2 * $v->tasa;
                if($tasa_iva !== null){
                $importe_iva += (double)$importe_ieps_monto_fijo * ($tasa_iva/100);
                $total_impuesto += (double)$importe_ieps_monto_fijo * ($tasa_iva/100);
                }
                $total_impuesto += $importe_ieps_monto_fijo;
            break;
            case 4:
                $ish_tasa = $v->tasa;
                $importe_ish = (double)$subtotal * ($v->tasa/100);
                $total_impuesto += $importe_ish;
            break;
            case 5:
                $iva_exento = 1;
            break;
            case 6:
                $iva_retenido_tasa = $v->tasa;
                $importe_iva_retenido = (double)$subtotal * ($v->tasa/100);
                $total_impuesto -= $importe_iva_retenido;
            break;
            case 7:
                $isr_tasa = $v->tasa;
                $importe_isr = (double)$subtotal * ($v->tasa/100);
                $total_impuesto -= $importe_isr;
            break;
            case 8:
                $isn_tasa = $v->tasa;
                $importe_isn = (double)$subtotal * ($v->tasa/100);
                $total_impuesto += $importe_isn;
            break;
            case 9:
                $cedular_tasa = $v->tasa;
                $importe_cedular = (double)$subtotal * ($v->tasa/100);
                $total_impuesto += $importe_cedular;
            break;
            case 10:
                $cinco_al_millar = $v->tasa;
                $importe_5_al_millar = (double)$subtotal * ($v->tasa/100);
                $total_impuesto += $importe_5_al_millar;
            break;
            case 11:
                $funcion_publica_tasa = $v->tasa;
                $importe_funcion_publica = (double)$subtotal * ($v->tasa/100);
                $total_impuesto += $importe_funcion_publica;
            break;
            case 12:
                $ieps_retenido_tasa = $v->tasa;
                $importe_ieps_retenido = (double)$subtotal * ($v->tasa/100);
                $total_impuesto -= $importe_ieps_retenido;
            break;
            case 16:
                $ieps_exento = 1;
            break;
            case 18:
                $ieps_retenido_monto_fijo = $v->tasa;
                $importe_ieps_retenido_monto_fijo = $value2 * $v->tasa;
                $total_impuesto -= $importe_ieps_retenido_monto_fijo;
            break;
            }
        
        }

        $arr_taxes = [
            "tasa_iva" => $tasa_iva,
            "importe_iva" => $importe_iva,
            "ieps_tasa" => $ieps_tasa,
            "importe_ieps" => $importe_ieps,
            "ieps_monto_fijo" => $ieps_monto_fijo,
            "importe_ieps_monto_fijo" => $importe_ieps_monto_fijo,
            "ish_tasa" => $ish_tasa,
            "importe_ish" => $importe_ish,
            "iva_exento" => $iva_exento,
            "iva_retenido_tasa" => $iva_retenido_tasa,
            "importe_iva_retenido" => $importe_iva_retenido,
            "isr_tasa" => $isr_tasa,
            "importe_isr" => $importe_isr,
            "isn_tasa" => $isn_tasa,
            "importe_isn" => $importe_isn,
            "cedular_tasa" => $cedular_tasa,
            "importe_cedular" => $importe_cedular,
            "cinco_al_millar" => $cinco_al_millar,
            "importe_5_al_millar" => $importe_5_al_millar,
            "funcion_publica_tasa" => $funcion_publica_tasa,
            "importe_funcion_publica" => $importe_funcion_publica,
            "ieps_retenido_tasa" => $ieps_retenido_tasa,
            "importe_ieps_retenido" => $importe_ieps_retenido,
            "ieps_exento" => $ieps_exento,
            "ieps_retenido_monto_fijo" => $ieps_retenido_monto_fijo,
            "importe_ieps_retenido_monto_fijo" => $importe_ieps_retenido_monto_fijo,
            "total" => $total_impuesto
        ];

        return $arr_taxes;
    }

    function getSubtotalsTicket($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $subtotals = [];
        $descuento = 0;
        $subtotal = 0;
        $total = 0;

        $impuestos = [];
        $iva_data = [];
        $ieps_data = [];
        $ieps_monto_fijo_data = [];
        $ish_data = [];
        $iva_retenido_data = [];
        $isr_data = [];
        $isn_data = [];
        $cedular_data = [];
        $cinco_al_millar_data = [];
        $funcion_publica_data = [];
        $ieps_retenido_data = [];
        $ieps_retenido_monto_fijo_data = [];

        $query = sprintf("
                            select 
                            subtotal, 
                            total,
                            descuento,
                            iva_tasa,
                            importe_iva,
                            ieps_tasa,
                            importe_ieps,
                            ieps_monto_fijo,
                            importe_ieps_monto_fijo,
                            ish_tasa,
                            importe_ish,
                            iva_exento,
                            iva_retenido_tasa,
                            importe_iva_retenido,
                            isr_tasa,
                            importe_isr,
                            isn_tasa,
                            importe_isn,
                            cedular_tasa,
                            importe_cedular,
                            cinco_al_millar,
                            importe_5_al_millar,
                            funcion_publica_tasa,
                            importe_funcion_publica,
                            ieps_retenido_tasa,
                            importe_ieps_retenido,
                            ieps_exento,
                            ieps_retenido_monto_fijo,
                            importe_ieps_retenido_monto_fijo
                        from productos_ticket_temp 
                        where 
                            caja_id = {$value} and 
                            usuario_id = {$_SESSION['PKUsuario']}
                        ");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        foreach($arr as $r => $v){
            $subtotal += $v->subtotal;
            $total += $v->total;
            $descuento += $v->descuento;

            if($v->iva_tasa !== null)
            {
            if(!isset($iva_data['IVA_'.$v->iva_tasa]))
            {
                $iva_data['IVA_'.$v->iva_tasa] = array(
                'tasa' => $v->iva_tasa,
                'importe' => (float)$v->importe_iva
                );
            } else {
                $iva_data['IVA_'.$v->iva_tasa]['importe'] += (float)$v->importe_iva;
            }
            
            }

            if($v->ieps_tasa !== null)
            {
            if(!isset($ieps_data['IEPS_'.$v->ieps_tasa]))
            {
                $ieps_data['IEPS_'.$v->ieps_tasa] = array(
                'tasa' => $v->ieps_tasa,
                'importe' => (float)$v->importe_ieps
                );
            } else {
                $ieps_data['IEPS_'.$v->ieps_tasa]['importe'] += (float)$v->importe_ieps;
            }
            }

            if($v->ieps_monto_fijo !== null)
            {
            if(!isset($ieps_monto_fijo_data['IEPS_Monto_fijo_'.$v->ieps_monto_fijo]))
            {
                $ieps_monto_fijo_data['IEPS_Monto_fijo_'.$v->ieps_monto_fijo] = array(
                'tasa' => $v->ieps_monto_fijo,
                'importe' => (float)$v->importe_ieps_monto_fijo
                );
            } else {
                $ieps_monto_fijo_data['IEPS_Monto_fijo_'.$v->ieps_monto_fijo]['importe'] += (float)$v->importe_ieps_monto_fijo;
            }
            }

            if($v->ish_tasa !== null)
            {
            if(!isset($ish_data['ISH_'.$v->ish_tasa]))
            {
                $ish_data['ISH_'.$v->ish_tasa] = array(
                'tasa' => $v->ish_tasa,
                'importe' => (float)$v->importe_ish
                );
            } else {
                $ish_data['ISH_'.$v->ish_tasa]['importe'] += (float)$v->importe_ish;
            }
            }        

            if($v->iva_retenido_tasa !== null)
            {
            if(!isset($iva_retenido_data['IVA_Retenido_'.$v->iva_retenido_tasa]))
            {
                $iva_retenido_data['IVA_Retenido_'.$v->iva_retenido_tasa] = array(
                'tasa' => $v->iva_retenido_tasa,
                'importe' => $v->importe_iva_retenido
                );
            } else {
                $iva_retenido_data['IVA_Retenido_'.$v->iva_retenido_tasa]['importe'] += $v->importe_iva_retenido;
            }
            }

            if($v->isr_tasa !== null)
            {
            if(!isset($isr_data['ISR_'.$v->iva_retenido_tasa]))
            {
                $isr_data['ISR_'.$v->isr_tasa] = array(
                'tasa' => $v->isr_tasa,
                'importe' => $v->importe_isr
                );
            } else {
                $isr_data['ISR_'.$v->isr_tasa]['importe'] += $v->importe_isr;
            }
            }

            if($v->isn_tasa !== null)
            {
            if(!isset($isn_data['ISN_'.$v->isn_tasa]))
            {
                $isn_data['ISN_'.$v->isn_tasa] = array(
                'tasa' => $v->isn_tasa,
                'importe' => $v->importe_isn
                );
            } else {
                $isn_data['ISN_'.$v->isn_tasa]['importe'] += $v->importe_isn;
            }
            }
            
            if($v->cedular_tasa !== null)
            {
            if(!isset($cedular_data['Cedular_'.$v->cedular_tasa]))
            {
                $cedular_data['Cedular_'.$v->cedular_tasa] = array(
                'tasa' => $v->cedular_tasa,
                'importe' => $v->importe_cedular
                );
            } else {
                $cedular_data['Cedular_'.$v->cedular_tasa]['importe'] += $v->importe_cedular;
            }
            }
            
            if($v->cinco_al_millar !== null)
            {
            if(!isset($cinco_al_millar_data['5_al_millar_'.$v->cinco_al_millar]))
            {
                $cinco_al_millar_data['5_al_millar_'.$v->cinco_al_millar] = array(
                'tasa' => $v->cinco_al_millar,
                'importe' => $v->importe_5_al_millar
                );
            } else {
                $cinco_al_millar_data['5_al_millar_'.$v->cinco_al_millar]['importe'] += $v->importe_5_al_millar;
            }
            }

            if($v->funcion_publica_tasa !== null)
            {
            if(!isset($funcion_publica_data['funcion_publica_'.$v->funcion_publica_tasa]))
            {
                $funcion_publica_data['funcion_publica_'.$v->funcion_publica_tasa] = array(
                'tasa' => $v->funcion_publica_tasa,
                'importe' => $v->importe_funcion_publica
                );
            } else {
                $funcion_publica_data['funcion_publica_'.$v->funcion_publica_tasa]['importe'] += $v->importe_funcion_publica;
            }
            }

            if($v->ieps_retenido_tasa !== null)
            {
            if(!isset($ieps_retenido_data['IEPS_Retenido_'.$v->ieps_retenido_tasa]))
            {
                $ieps_retenido_data['IEPS_Retenido_'.$v->ieps_retenido_tasa] = array(
                'tasa' => $v->ieps_retenido_tasa,
                'importe' => $v->importe_ieps_retenido
                );
            } else {
                $ieps_retenido_data['IEPS_Retenido_'.$v->ieps_retenido_tasa]['importe'] += $v->importe_ieps_retenido;
            }
            }

            if($v->ieps_retenido_monto_fijo !== null)
            {
            if(!isset($ieps_retenido_monto_fijo_data['IEPS_Retenido_Monto_fijo_'.$v->ieps_retenido_monto_fijo]))
            {
                $ieps_retenido_monto_fijo_data['IEPS_Retenido_Monto_fijo_'.$v->ieps_retenido_monto_fijo] = array(
                'tasa' => $v->ieps_retenido_monto_fijo,
                'importe' => $v->importe_ieps_retenido_monto_fijo
                );
            } else {
                $ieps_retenido_monto_fijo_data['IEPS_Retenido_Monto_fijo_'.$v->ieps_retenido_monto_fijo]['importe'] += $v->importe_ieps_retenido_monto_fijo;
            }
            }
        } 

        $impuestos = array_merge(
            $iva_data,$ieps_data,
            $ieps_monto_fijo_data,
            $ish_data,
            $iva_retenido_data,
            $isr_data,
            $isn_data,
            $cedular_data,
            $cinco_al_millar_data,
            $funcion_publica_data,
            $ieps_retenido_data,
            $ieps_retenido_monto_fijo_data
        );


        $subtotals = [
            "subtotal" => $subtotal,
            "descuento" => $descuento,
            "total" => $total,
            "impuestos" => $impuestos
        ];

        return $subtotals;
    }

    function getInfoFiscalProductosId($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select PKInfoFiscalProducto id from info_fiscal_productos where FKProducto = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFolioTblTemp($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select distinct max(folio) folio from productos_pendientes_ticket where caja_id = {$value} and usuario_id = {$_SESSION['PKUsuario']}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getPeddingSales($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();

        $query = sprintf("select 
                            folio, 
                            count(*) total_productos, 
                            sum(total) total 
                            from productos_pendientes_ticket 
                            where caja_id = {$value} and usuario_id = {$_SESSION['PKUsuario']} group by folio");

        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $get_data->getFormatTablePeddingSales($arr);
      
    }

    function getFormatTablePeddingSales($value)
    {
        $table = "";

        foreach ($value as $r) {
            $table .= '{
            "folio" : "' . $r->folio . '",
            "total_productos" : "' . $r->total_productos . '",
            "costo" : "' . $r->total . '",
            "functions" : ""
            },';
        }

        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    function getPeddingProductData($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $arr = [];
        $query = sprintf("select 
                            pt.producto_id id,
                            p.Nombre,
                            ifnull(sum(e.existencia),0) existencia,
                            pt.cantidad,
                            pt.lote,
                            pt.serie,
                            pt.caducidad,
                            pt.precio_unitario,
                            pt.subtotal,
                            pt.iva_tasa,
                            pt.importe_iva,
                            pt.ieps_tasa,
                            pt.importe_ieps,
                            pt.ieps_monto_fijo,
                            pt.importe_ieps_monto_fijo,
                            pt.ish_tasa,
                            pt.importe_ish,
                            pt.iva_exento,
                            pt.iva_retenido_tasa,
                            pt.importe_iva_retenido,
                            pt.isr_tasa,
                            pt.importe_isr,
                            pt.isn_tasa,
                            pt.importe_isn,
                            pt.cedular_tasa,
                            pt.importe_cedular,
                            pt.cinco_al_millar,
                            pt.importe_5_al_millar,
                            pt.funcion_publica_tasa,
                            pt.importe_funcion_publica,
                            pt.ieps_retenido_tasa,
                            pt.importe_ieps_retenido,
                            pt.ieps_exento,
                            pt.ieps_retenido_monto_fijo,
                            pt.importe_ieps_retenido_monto_fijo,
                            pt.total
                            from productos_ticket_temp pt
                            inner join productos p on pt.producto_id = p.PKProducto
                            left join existencia_por_productos e on p.PKProducto = e.producto_id
                            where 
                            caja_id = {$value} and 
                            usuario_id = {$_SESSION['PKUsuario']}
                            group by e.producto_id
                            ");
        $stmt = $db->prepare($query);
        
        $stmt->execute();
                
        $aux = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;
        
        foreach($aux as $r => $v){

            array_push($arr,[
            "id" => $v->id,
            "nombre" => str_replace('"','\"',str_replace(['\r','\n'],"",$v->Nombre)),
            "existencia" => $v->existencia,
            "cantidad" => $v->cantidad,
            "lote" => $v->lote,
            "serie" => $v->serie,
            "caducidad" => $v->caducidad,
            "precio_unitario" => $v->precio_unitario,
            "subtotal" => $v->subtotal,
            "iva_tasa" => $v->iva_tasa,
            "importe_iva" => $v->importe_iva,
            "ieps_tasa" => $v->ieps_tasa,
            "importe_ieps" => $v->importe_ieps,
            "ieps_monto_fijo" => $v->ieps_monto_fijo,
            "importe_ieps_monto_fijo" => $v->importe_ieps_monto_fijo,
            "ish_tasa" => $v->ish_tasa,
            "importe_ish" => $v->importe_ish,
            "iva_exento" => $v->iva_exento,
            "iva_retenido_tasa" => $v->iva_retenido_tasa,
            "importe_iva_retenido" => $v->importe_iva_retenido,
            "isr_tasa" => $v->isr_tasa,
            "importe_isr" => $v->importe_isr,
            "isn_tasa" => $v->isn_tasa,
            "importe_isn" => $v->importe_isn,
            "cedular_tasa" => $v->cedular_tasa,
            "importe_cedular" => $v->importe_cedular,
            "cinco_al_millar" => $v->cinco_al_millar,
            "importe_5_al_millar" => $v->importe_5_al_millar,
            "funcion_publica_tasa" => $v->funcion_publica_tasa,
            "importe_funcion_publica" => $v->importe_funcion_publica,
            "ieps_retenido_tasa" => $v->ieps_retenido_tasa,
            "importe_ieps_retenido" => $v->importe_ieps_retenido,
            "ieps_exento" => $v->ieps_exento,
            "ieps_retenido_monto_fijo" => $v->ieps_retenido_monto_fijo,
            "importe_ieps_retenido_monto_fijo" => $v->importe_ieps_retenido_monto_fijo,
            "total" => $v->total
            ]);

        }

        return $arr;
    }

    function getProductPrice($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $arr = [];

        $query = sprintf("select 
                            precio_venta1, 
                            precio_venta2, 
                            precio_venta3, 
                            precio_venta4 
                            from productos 
                            where empresa_id = {$_SESSION['IDEmpresa']} and 
                            PKProducto = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $aux = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        foreach($aux as $r){
            if($r->precio_venta1 !== null && $r->precio_venta1 !== ""){
            array_push($arr,$r->precio_venta1);
            }
            if($r->precio_venta2 !== null && $r->precio_venta2 !== ""){
            array_push($arr,$r->precio_venta2);
            }
            if($r->precio_venta3 !== null && $r->precio_venta3 !== ""){
            array_push($arr,$r->precio_venta3);
            }
            if($r->precio_venta4 !== null && $r->precio_venta4 !== ""){
            array_push($arr,$r->precio_venta4);
            }
        }
        return $arr;
    }

    function getFolioTicket($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select max(folio) folio from ticket_punto_venta where empresa_id = {$_SESSION['IDEmpresa']} and caja_id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getTotalTicketActive($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select sum(saldo) total from detalle_cuenta_punto_venta where cuenta_punto_venta_id = {$value} and estatus = 1");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCountCashRegisterAccountsStatus($value)
    {
      $con = new conection();
      $db = $con->getDb();
      $data = json_decode($value);

      $query = sprintf("select count(*) count from detalle_cuenta_punto_venta where estatus = 1 and cuenta_punto_venta_id = {$data->caja_id} and empresa_id = {$_SESSION['IDEmpresa']}");
      $stmt = $db->prepare($query);
      $stmt->execute();

      $arr = $stmt->fetch()['count'];

      $stmt = null;
      $db = null;

      return $arr;
    }

    function getGeneralDataCashRegisterCut($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            sum(efectivo_contado) efectivo_contado,
                            sum(credito_contado) credito_contado,
                            sum(transferencia_contado) transferencia_contado,
                            sum(efectivo_calculado) efectivo_calculado,
                            sum(credito_calculado) credito_calculado,
                            sum(transferencia_calculado) transferencia_calculado,
                            sum(efectivo_diferencia) efectivo_diferencia,
                            sum(credito_diferencia) credito_diferencia,
                            sum(transferencia_diferencia) transferencia_diferencia,
                            sum(efectivo_retirado) efectivo_retirado,
                            sum(credito_retirado) credito_retirado,
                            sum(transferencia_retirado) transferencia_retirado,
                            sum(total_contado) total_contado,
                            sum(total_calculado) total_calculado,
                            sum(total_diferencia) total_diferencia,
                            sum(total_retirado) total_retirado,
                            sum(total_neto) total_neto
                            from corte_caja_punto_venta  
                            where 
                            cuentas_punto_venta_id = {$value} and 
                            empresa_id = {$_SESSION['IDEmpresa']}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getBalacenPerPeriodDataCashRegisterCut($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            sum(efectivo_contado) efectivo_contado,
                            sum(credito_contado) credito_contado,
                            sum(transferencia_contado) transferencia_contado,
                            sum(efectivo_calculado) efectivo_calculado,
                            sum(credito_calculado) credito_calculado,
                            sum(transferencia_calculado) transferencia_calculado,
                            sum(efectivo_diferencia) efectivo_diferencia,
                            sum(credito_diferencia) credito_diferencia,
                            sum(transferencia_diferencia) transferencia_diferencia,
                            sum(efectivo_retirado) efectivo_retirado,
                            sum(credito_retirado) credito_retirado,
                            sum(transferencia_retirado) transferencia_retirado,
                            sum(total_contado) total_contado,
                            sum(total_calculado) total_calculado,
                            sum(total_diferencia) total_diferencia,
                            sum(total_retirado) total_retirado,
                            sum(total_neto) total_neto
                            from corte_caja_punto_venta  
                            where 
                            cuentas_punto_venta_id = {$value} and 
                            empresa_id = {$_SESSION['IDEmpresa']} and
                            id = {$value1}
                            ");

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getTotalsCountCashClosing($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $data = json_decode($value);
        $query = sprintf("
                            select
                                dpv.tipo_pago,
                                sum(dpv.saldo) total
                            from detalle_cuenta_punto_venta dpv
                            inner join ticket_punto_venta t on dpv.ticket_id = t.id
                            where
                                dpv.estatus = 1 and
                                t.estatus = 1 and
                                dpv.cuenta_punto_venta_id = {$data->caja_id} and
                                dpv.empresa_id = {$_SESSION['IDEmpresa']}
                            group by tipo_pago
        ");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCashRegisterClosing($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select id, fecha texto from corte_caja_punto_venta where cuentas_punto_venta_id = {$value} and empresa_id = {$_SESSION['IDEmpresa']}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCashRegisterHasInventory($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
            select 
            s.activar_inventario
            from cuentas_punto_venta cpv
            inner join sucursales s on cpv.sucursal_id = s.id
            where
            cpv.sucursal_id = {$value}
        ");

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getIfProductoKeyExist($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select * from productos where empresa_id = {$_SESSION['IDEmpresa']} and ClaveInterna like '%%{$value}%%'");

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->rowCount();

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClaveReferencia()
    {
        $con = new conection();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Producto_ReferenciaClaveInterna(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $ProductoReferencia = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();

        $stmt = null;
        $db = null;
        
        if ($cantidadRegistros > 0) {
            $numReferencia = $ProductoReferencia['PKProducto'];
            $Referencia = "-" . str_pad($numReferencia, 6, "0", STR_PAD_LEFT);
        } else {
            $Referencia = "-000001";
        }
        return $Referencia;
    }

    function getIdProductInTableTemp($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select
                            p.PKProducto id,
                            p.fecha_caducidad,
                            p.lote,
                            p.serie,
                            t.cantidad,
                            c.sucursal_id
                            from 
                            productos p
                            inner join productos_ticket_temp t on p.PKProducto = t.producto_id
                            inner join cuentas_punto_venta c on t.caja_id = c.id
                            where
                            t.usuario_id = {$_SESSION['PKUsuario']} and
                            t.caja_id = {$value}    
                        ");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
      
    }

    function getIfProductoPrescription($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $data = "";
        $query = sprintf("select
                            *
                            from 
                            productos p
                            inner join productos_ticket_temp t on p.PKProducto = t.producto_id
                            where
                            t.usuario_id = {$_SESSION['PKUsuario']} and
                            t.caja_id = {$value} and
                            p.receta = 1
                        ");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->rowCount();

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCmbRegimen()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf('select id, concat(clave," - ",descripcion) texto from claves_regimen_fiscal');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCmbVendedor()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            e.PKEmpleado as id, 
                            concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as texto 
                            from empleados e
                            inner join relacion_tipo_empleado rte on e.PKEmpleado = rte.empleado_id
                            where 
                            rte.tipo_empleado_id = 1 and 
                            e.empresa_id = {$_SESSION['IDEmpresa']} and 
                            e.is_generic = 0
                            order by concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) asc
                        ");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCmbMedioContacto()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            PKMedioContactoCliente id, 
                            MedioContactoCliente texto
                            from medios_contacto_clientes 
                            where empresa_id = 1 OR 
                            empresa_id = {$_SESSION['IDEmpresa']}
                            order by empresa_id asc");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    public function getCmbEstados($PKPais)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("SELECT PKEstado, Estado FROM estados_federativos WHERE FKPais = {$PKPais}");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }
    
    function getCheckedIfExistNameCashRegister($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);
        $cash_register_name = str_replace(" ","",strtolower($data->nombre_caja));
        
        $query = sprintf("select count(*) count from cuentas_punto_venta where replace(lower(descripcion),' ','') = '{$cash_register_name}' and sucursal_id = {$data->sucursal_id}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCurrentBalance($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select saldo_actual from cuentas_punto_venta where id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getAllTickets($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();

        $query = sprintf("
            select
            tpv.id,
            tpv.folio,
            DATE_FORMAT(tpv.fecha,'%%d/%%m/%%Y') date,
            format(tpv.total,2) amount, 
            tpv.estatus,
            ft.id fact_id,
            concat(ft.serie,' ',ft.folio) serie_fact,
            ft.id_api
            from 
            ticket_punto_venta tpv
            left join relacion_tickets_facturacion rtf on tpv.id = rtf.ticket_id
                left join facturacion ft on rtf.factura_id = ft.id
            where 
            tpv.caja_id = {$value} and 
            tpv.empresa_id = {$_SESSION['IDEmpresa']}
            group by tpv.id");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $get_data->getFormatTblTickets($arr);
    }

    function getDetailsTicket($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();

        $query = sprintf("
            select 
            cantidad,
            precio_unitario,
            subtotal,
            descuento,
            iva_tasa,
            importe_iva,
            ieps_tasa,
            importe_ieps,
            ieps_monto_fijo,
            importe_ieps_monto_fijo,
            ish_tasa,
            importe_ish,
            iva_exento,
            iva_retenido_tasa,
            importe_iva_retenido,
            isr_tasa,
            importe_isr,
            isn_tasa,
            importe_isn,
            cedular_tasa,
            importe_cedular,
            cinco_al_millar,
            importe_5_al_millar,
            funcion_publica_tasa,
            importe_funcion_publica,
            ieps_retenido_tasa,
            importe_ieps_retenido,
            ieps_exento,
            ieps_retenido_monto_fijo,
            importe_ieps_retenido_monto_fijo,
            p.Nombre nombre
            from 
            detalle_ticket_punto_venta dtpv
            inner join productos p on dtpv.producto_id = p.PKProducto
            where ticket_id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $get_data->getFormatTblDetailsTickets($arr);
    }

    function getFormatTblTickets($value)
    {
      $table = "";
      $estatus = "";
      $cancel = "";
      $redirect = "";
      
      foreach($value as $r)
      {
        $estatus = "";
        $cancel = "";
        $redirect = "";
        $pdf = "";
        $xml = "";
        
        $print = "<div data-toggle='tooltip' data-placement='top' title='Imprimir'><a href='#' data-id='{$r->id}'><img src='../../img/punto_venta/imprimir_azul.svg' width='20' data-id='".$r->id."' id='print_ticket'></a></div>";
        $folio = "<a href='#' id='folio_ticket' data-id='{$r->id}' data-toggle='modal' data-target='#modal_details_tickets_view'>{$r->folio}</a>";

        switch ((int)$r->estatus) {
          case 1:
           
           $estatus = '<div class=\"left-dot green-dot text-center\"><span> Activo</span></div>';
           $cancel = '<a style=\"margin:2px\" href=\"#\" ><img src=\"../../img/punto_venta/cancelar_azul.svg\" width=\"20\" data-id=\"'.$r->id.'\" id=\"cancel\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Cancelar\"></a>';
            break;
          case 2:
            $estatus = '<div class=\"left-dot red-dot\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\"> Cancelado</div>';
            break;
          case 3: 
            $estatus = '<div class=\"left-dot green-dot\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\"> Facturada</div>';
            $redirect = '<a style=\"margin:2px\" id=\"detalle_factura\" href=\"#\" data-id=\"' . $r->fact_id . '\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ver factura\" > ' . $r->serie_fact . ' <img src=\"../../img/punto_venta/facturar_azul.svg\" width=\"20\"> </a>';

            $pdf = '<a href=\"php/download_pdf.php?value='.$r->id_api.'&value1='.$r->serie_fact.'\" style=\"margin:2px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ver PDF\" target=\"_blank\" id=\"pdf_factura\"> <i style=\"color:#006dd9\" class=\"far fa-file-pdf \" id=\"pdf_factura_i\"></i> </a>';

            $xml = '<a href=\"php/download_xml.php?value='.$r->id_api.'&value1='.$r->serie_fact.'\" style=\"margin:2px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ver XML\" id=\"xml_factura\"> <i style=\"color:#006dd9\" class=\"far fa-file-code \" d=\"xml_factura_i\"></i> </a>';
            // <i class="fas fa-file-code"></i>
          break;
          case 4:
            $estatus = '<div class=\"left-dot green-dot\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\"> En corte de caja</div>';
            break;
          case 5:
            $estatus = '<div class=\"left-dot green-dot\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\"> Facturada global</div>';
            $redirect = '<a style=\"margin:2px\" id=\"detalle_factura\" href=\"#\" data-id=\"' . $r->fact_id . '\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ver factura\" > ' . $r->serie_fact . ' <i class=\"fas fa-external-link-alt\"></i> </a>';

            
            $pdf = '<a href=\"php/download_pdf.php?value='.$r->id_api.'&value1='.$r->serie_fact.'\" style=\"margin:2px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ver PDF\" target=\"_blank\" id=\"pdf_factura\"> <i style=\"color:#006dd9\" class=\"far fa-file-pdf \" id=\"pdf_factura_i\"></i> </a>';

            $xml = '<a href=\"php/download_xml.php?value='.$r->id_api.'&value1='.$r->serie_fact.'\" style=\"margin:2px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ver XML\" id=\"xml_factura\"> <i style=\"color:#006dd9\" class=\"far fa-file-code \" d=\"xml_factura_i\"></i> </a>';
            break;
          
        }
        $functions = $cancel . $pdf . $xml . $print;
        $table .= '{
          "id" : "' . $r->id . '",
          "folio": "' . $folio . '",
          "date" : "' . $r->date . '",
          "amount" : "' . $r->amount . '",
          "status" : "' . $estatus . '",
          "invoice" : "' . $redirect . '",
          "functions" : "' . $functions . '"
        },';
      }
      $table = substr($table, 0, strlen($table) - 1);

      return '{"data":[' . $table . ']}';
    }

    function getFormatTblDetailsTickets($value)
    {
      $table = "";

      foreach ($value as $r) {
        $table .= 
        '{
          "cantidad": "'.$r->cantidad.'",
          "descripcion":"'.str_replace('"','\"',str_replace(['\r','\n'],"",$r->nombre)).'",
          "precio_unitario":"'.number_format($r->precio_unitario,2).'",
          "subtotal":"'.number_format($r->subtotal,2).'"
        },'
        ;
      }

      $table = substr($table, 0, strlen($table) - 1);

      return '{"data":[' . $table . ']}';
    }

    function getCountBranchOffice()
    {
        $query = sprintf("select * from sucursales where empresa_id = {$_SESSION['IDEmpresa']}");

        $con = new conection();
        $db = $con->getDb();

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->rowCount();

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getDataTicketCancel($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            p.PKProducto id,
                            p.lote chkLote,
                            p.serie chkSerie,
                            dtpv.lote,
                            dtpv.serie,
                            dtpv.cantidad,
                            cpv.sucursal_id,
                            tpv.folio
                        from 
                            detalle_ticket_punto_venta dtpv
                            inner join ticket_punto_venta tpv on dtpv.ticket_id = tpv.id
                            inner join cuentas_punto_venta cpv on tpv.caja_id = cpv.id
                            inner join productos p on dtpv.producto_id = p.PKProducto
                        where 
                            ticket_id = {$value}");
        $stmt = $db->prepare($query);                    
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getDataEnterprise()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select e.*,ef.Estado from empresas e
                            inner join estados_federativos ef on e.estado_id = ef.PKEstado
                            where e.PKEmpresa = {$_SESSION['IDEmpresa']}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getDataTicket($value,$value1,$value2)
    {
        $con = new conection();
        $db = $con->getDb();
       
        if($value !== "" && $value !== null){
            $query = sprintf("select
                                tpv.folio,
                                cpv.descripcion,
                                u.nombre,
                                tpv.subtotal,
                                tpv.total,
                                tpv.fecha,
                                ft.id,
                                concat(ft.serie,' ',ft.folio) seriefolio,
                                cl.razon_social,
                                cl.codigo_postal,
                                cl.rfc,
                                cl.Email
                            from 
                                ticket_punto_venta tpv
                            inner join cuentas_punto_venta cpv on tpv.caja_id = cpv.id
                            inner join usuarios u on tpv.usuario_id = u.id
                            left join facturacion ft on tpv.id = ft.referencia
                            inner join clientes cl on tpv.cliente_id = cl.PKCliente
                            where 
                                tpv.id = {$value}");
        } else if($value1 !== null && $value1 !== "" && $value2 !== null && $value2 !== ""){
            $get_data = new get_data();
            $client = $get_data->getClientDefault();
            $initial_date = date("Y-m-d H:i:s",strtotime($value1." 00:00:00"));
            $final_date = date("Y-m-d H:i:s",strtotime($value2." 23:59:59"));
            $query = sprintf("select
                                tpv.id,
                                tpv.folio,
                                tpv.subtotal,
                                tpv.total,
                                tpv.fecha
                            from 
                                ticket_punto_venta tpv
                            where
                                tpv.cliente_id = {$client[0]->id} and
                                tpv.estatus = 4 and
                                tpv.empresa_id = {$_SESSION['IDEmpresa']} and
                                tpv.tipo = 1 and
                                tpv.fecha between '{$initial_date}' and '{$final_date}'");
           
        }
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getTotalGeneralInvoice($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $get_data = new get_data();
        $client = $get_data->getClientDefault();
        $initial_date = date("Y-m-d H:i:s",strtotime($value." 00:00:00"));
            $final_date = date("Y-m-d H:i:s",strtotime($value1." 23:59:59"));

        $query = sprintf("select
                            sum(tpv.subtotal) subtotal_neto,
                            sum(tpv.total) total_neto
                            from 
                            ticket_punto_venta tpv
                            where
                            tpv.cliente_id = {$client[0]->id} and
                            tpv.estatus = 4 and
                            tpv.tipo = 1 and
                            tpv.fecha between '{$initial_date}' and '{$final_date}'");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductsTicket($value,$value1,$value2)
    {
        $con = new conection();
        $db = $con->getDb();
        
        if($value !== null && $value !== ""){
            $query = sprintf("select 
                                p.PKProducto id,
                                p.Nombre nombre,
                                p.ClaveInterna sku,
                                dtpv.cantidad,
                                dtpv.precio_unitario,
                                dtpv.subtotal,
                                dtpv.total,
                                dtpv.descuento,
                                dtpv.iva_tasa,
                                dtpv.importe_iva,
                                dtpv.ieps_tasa,
                                dtpv.importe_ieps,
                                dtpv.ieps_monto_fijo,
                                dtpv.importe_ieps_monto_fijo,
                                dtpv.ish_tasa,
                                dtpv.importe_ish,
                                dtpv.iva_exento,
                                dtpv.iva_retenido_tasa,
                                dtpv.importe_iva_retenido,
                                dtpv.isr_tasa,
                                dtpv.importe_isr,
                                dtpv.isn_tasa,
                                dtpv.importe_isn,
                                dtpv.cedular_tasa,
                                dtpv.importe_cedular,
                                dtpv.cinco_al_millar,
                                dtpv.importe_5_al_millar,
                                dtpv.funcion_publica_tasa,
                                dtpv.importe_funcion_publica,
                                dtpv.ieps_retenido_tasa,
                                dtpv.importe_ieps_retenido,
                                dtpv.ieps_exento,
                                dtpv.ieps_retenido_monto_fijo,
                                dtpv.importe_ieps_retenido_monto_fijo,
                                cst.Clave clave_sat,
                                cstu.Clave clave_sat_unidad
                            from 
                                detalle_ticket_punto_venta dtpv
                            inner join productos p on dtpv.producto_id = p.PKProducto
                            left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                            left join claves_sat cst on ifp.FKClaveSAT = cst.PKClaveSAT
                            left join claves_sat_unidades cstu on ifp.FKClaveSATUnidad = cstu.PKClaveSATUnidad
                            where 
                                ticket_id = {$value}");
        } else if($value1 !== null && $value1 !== "" && $value2 !== null && $value2 !== ""){
            $get_data = new get_data();
            $client = $get_data->getClientDefault();

            $initial_date = date("Y-m-d",strtotime($value1));
            $final_date = date("Y-m-d",strtotime($value2));

            $query = sprintf("select
                                p.PKProducto id, 
                                p.Nombre nombre,
                                p.ClaveInterna sku,
                                dtpv.cantidad,
                                dtpv.precio_unitario,
                                dtpv.subtotal,
                                dtpv.descuento,
                                dtpv.iva_tasa,
                                dtpv.importe_iva,
                                dtpv.ieps_tasa,
                                dtpv.importe_ieps,
                                dtpv.ieps_monto_fijo,
                                dtpv.importe_ieps_monto_fijo,
                                dtpv.ish_tasa,
                                dtpv.importe_ish,
                                dtpv.iva_exento,
                                dtpv.iva_retenido_tasa,
                                dtpv.importe_iva_retenido,
                                dtpv.isr_tasa,
                                dtpv.importe_isr,
                                dtpv.isn_tasa,
                                dtpv.importe_isn,
                                dtpv.cedular_tasa,
                                dtpv.importe_cedular,
                                dtpv.cinco_al_millar,
                                dtpv.importe_5_al_millar,
                                dtpv.funcion_publica_tasa,
                                dtpv.importe_funcion_publica,
                                dtpv.ieps_retenido_tasa,
                                dtpv.importe_ieps_retenido,
                                dtpv.ieps_exento,
                                dtpv.ieps_retenido_monto_fijo,
                                dtpv.importe_ieps_retenido_monto_fijo,
                                cst.Clave clave_sat,
                                cstu.Clave clave_sat_unidad,
                                dtpv.descuento,
                                dtpv.total
                            from 
                                detalle_ticket_punto_venta dtpv
                            inner join ticket_punto_venta tpv on dtpv.ticket_id = tpv.id
                            inner join productos p on dtpv.producto_id = p.PKProducto
                            left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                            left join claves_sat cst on ifp.FKClaveSAT = cst.PKClaveSAT
                            left join claves_sat_unidades cstu on ifp.FKClaveSATUnidad = cstu.PKClaveSATUnidad
                            where 
                                tpv.cliente_id = {$client[0]->id} and
                                tpv.estatus = 4 and
                                tpv.tipo = 1 and
                                tpv.fecha between '{$initial_date}' and '{$final_date}'");
        }
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductsTicketTax($value,$date,$date1)
    {
        $con = new conection();
        $db = $con->getDb();
        $data = [];

        if($value !== "" && $value !== null){
            $query = sprintf("select
                                precio_unitario, 
                                iva_tasa,
                                importe_iva,
                                ieps_tasa,
                                importe_ieps,
                                ieps_monto_fijo,
                                importe_ieps_monto_fijo,
                                ish_tasa,
                                importe_ish,
                                iva_retenido_tasa,
                                importe_iva_retenido,
                                isr_tasa,
                                importe_isr,
                                isn_tasa,
                                importe_isn,
                                cedular_tasa,
                                importe_cedular,
                                cinco_al_millar,
                                importe_5_al_millar,
                                funcion_publica_tasa,
                                importe_funcion_publica,
                                ieps_retenido_tasa,
                                importe_ieps_retenido,
                                ieps_retenido_monto_fijo,
                                importe_ieps_retenido_monto_fijo,
                                iva_exento,
                                ieps_exento,
                                ticket_id
                            from 
                                detalle_ticket_punto_venta
                            where 
                                ticket_id = {$value}");
        } else if($date !== "" && $date !== null && $date1 !== "" && $date1 !== null){
        $get_data = new get_data();
        $client = $get_data->getClientDefault();
        $initial_date = date("Y-m-d H:i:s",strtotime($date." 00:00:00"));
        $final_date = date("Y-m-d H:i:s",strtotime($date1." 23:59:59"));
        $query = sprintf("select
                            tpv.subtotal subtotal_ticket,
                            tpv.total total_ticket,
                            producto_id,
                            precio_unitario, 
                            iva_tasa,
                            importe_iva,
                            ieps_tasa,
                            importe_ieps,
                            ieps_monto_fijo,
                            importe_ieps_monto_fijo,
                            ish_tasa,
                            importe_ish,
                            iva_retenido_tasa,
                            importe_iva_retenido,
                            isr_tasa,
                            importe_isr,
                            isn_tasa,
                            importe_isn,
                            cedular_tasa,
                            importe_cedular,
                            cinco_al_millar,
                            importe_5_al_millar,
                            funcion_publica_tasa,
                            importe_funcion_publica,
                            ieps_retenido_tasa,
                            importe_ieps_retenido,
                            ieps_retenido_monto_fijo,
                            importe_ieps_retenido_monto_fijo,
                            iva_exento,
                            ieps_exento,
                            ticket_id
                            from 
                            detalle_ticket_punto_venta dtpv
                            inner join ticket_punto_venta tpv on dtpv.ticket_id = tpv.id
                            where 
                            tpv.cliente_id = {$client[0]->id} and
                            tpv.estatus = 4 and
                            tpv.tipo = 1 and
                            tpv.fecha between '{$initial_date}' and '{$final_date}'
                        ");
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;

    }

    function getProductsTicketTaxUnique($value,$date,$date1)
    {
        $get_data = new get_data();
        $impuestos_aux = $get_data->getProductsTicketTax($value,$date,$date1);
        $data = [];
        $impuestos_aux1 = [];

        foreach ($impuestos_aux as $r) {
            if ($r->iva_tasa !== "" && $r->iva_tasa !== null && $r->iva_tasa > 0) {
            
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 1,
                "tasa" => $r->iva_tasa,
                "importe" => (float)$r->importe_iva,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->ieps_tasa !== "" && $r->ieps_tasa !== null && $r->ieps_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 2,
                "tasa" => $r->ieps_tasa,
                "importe" => (float)$r->importe_ieps,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->ieps_monto_fijo !== "" && $r->ieps_monto_fijo !== null && $r->ieps_monto_fijo > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 3,
                "tasa" => $r->ieps_monto_fijo,
                "importe" => (float)$r->importe_ieps_monto_fijo,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->ish_tasa !== "" && $r->ish_tasa !== null && $r->ish_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 4,
                "tasa" => $r->ish_tasa,
                "importe" => (float)$r->importe_ish,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            // if ($r->iva_exento !== "" && $r->iva_exento !== null) {
            //   array_push(
            //     $impuestos_aux1,
            //     array(
            //       "impuesto" => 5,
            //       "tasa" => $r->iva_exento,
            //       "importe" => (float)$r->importe_iva_exento
            //     )
            //   );
            // }
            if ($r->iva_retenido_tasa !== "" && $r->iva_retenido_tasa !== null && $r->iva_retenido_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 6,
                "tasa" => $r->iva_retenido_tasa,
                "importe" => (float)$r->importe_iva_retenido,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->isr_tasa !== "" && $r->isr_tasa !== null && $r->isr_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 7,
                "tasa" => $r->isr_tasa,
                "importe" => (float)$r->importe_isr,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->isn_tasa !== "" && $r->isn_tasa !== null && $r->isn_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 8,
                "tasa" => $r->isn_tasa,
                "importe" => (float)$r->importe_isn,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->cedular_tasa !== "" && $r->cedular_tasa !== null && $r->cedular_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 9,
                "tasa" => $r->cedular_tasa,
                "importe" => (float)$r->importe_cedular,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->cinco_al_millar !== "" && $r->cinco_al_millar !== null && $r->cinco_al_millar > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 10,
                "tasa" => $r->cinco_al_millar,
                "importe" => (float)$r->importe_5_al_millar,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->funcion_publica_tasa !== "" && $r->funcion_publica_tasa !== null && $r->funcion_publica_tasa > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 11,
                "tasa" => $r->funcion_publica_tasa,
                "importe" => (float)$r->importe_funcion_publica,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            if ($r->ieps_retenido_tasa !== "" && $r->ieps_retenido_tasa !== null && $r->ieps_retenido_tasa >= 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 12,
                "tasa" => $r->ieps_retenido_tasa,
                "importe" => (float)$r->importe_ieps_retenido,
                "ticket_id" => $r->ticket_id
                )
            );
            }
            // if ($r->isr_exento !== "" && $r->isr_exento !== null) {
            //   array_push(
            //     $impuestos_aux1,
            //     array(
            //       "impuesto" => 13,
            //       "tasa" => $r->isr_exento,
            //       "importe" => (float)$r->importe_isr_exento
            //     )
            //   );
            // }
            
            // if ($r->isr_retenido_monto_fijo !== "" && $r->isr_retenido_monto_fijo !== null) {
            //   array_push(
            //     $impuestos_aux1,
            //     array(
            //       "impuesto" => 17,
            //       "tasa" => $r->isr_retenido_monto_fijo,
            //       "importe" => (float)$r->isr_retenido_monto_fijo
            //     )
            //   );
            // }
            if ($r->ieps_retenido_monto_fijo !== "" && $r->ieps_retenido_monto_fijo !== null && $r->ieps_retenido_monto_fijo > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 18,
                "tasa" => $r->ieps_retenido_monto_fijo,
                "importe" => (float)$r->importe_ieps_retenido_monto_fijo,
                "ticket_id" => $r->ticket_id
                )
            );
            }
        }

        for ($i = 0; $i < count($impuestos_aux1); $i++) {
            for ($j = $i + 1; $j < count($impuestos_aux1); $j++) {
            if (
                $impuestos_aux1[$i]['tasa'] == $impuestos_aux1[$j]['tasa'] && 
                $impuestos_aux1[$i]['tasa'] !== 0 && $impuestos_aux1[$i]['tasa'] !== "0" && 
                $impuestos_aux1[$i]['impuesto'] == $impuestos_aux1[$j]['impuesto']
            ) {
                $impuestos_aux1[$i]['importe'] = $impuestos_aux1[$i]['importe'] + $impuestos_aux1[$j]['importe'];
                $impuestos_aux1[$j]['importe'] = 0;
            }
            }
        }
    
        $impuestos_aux2 = [];
    
        foreach ($impuestos_aux1 as $r) {
            if ($r['importe'] !== 0) {
            array_push(
                $impuestos_aux2,
                array(
                "impuesto" => $r['impuesto'],
                "tasa" => $r["tasa"],
                "importe" => $r["importe"]
                )
            );
            }
        }
        
        return $impuestos_aux2;
      
    }

    function getFormatTaxGeneralInvoice($value,$date,$date1)
    {
        $get_data = new get_data();
        $impuestos_aux = $get_data->getProductsTicketTax($value,$date,$date1);
        $data = [];
        $impuestos_aux1 = [];

        
        for ($i=0; $i < count($impuestos_aux); $i++) { 
            if(in_array($impuestos_aux[$i]->ticket_id,array_column($impuestos_aux1,"ticket_id"))){

            if(in_array($impuestos_aux[$i]->iva_tasa,array_column($impuestos_aux1,"iva_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_iva'] += (double)$impuestos_aux[$i]->importe_iva;
            }
            
            if(in_array($impuestos_aux[$i]->ieps_tasa,array_column($impuestos_aux1,"ieps_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_ieps'] += (double)$impuestos_aux[$i]->importe_ieps;
            }

            if(in_array($impuestos_aux[$i]->ieps_monto_fijo,array_column($impuestos_aux1,"ieps_monto_fijo"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_ieps_monto_fijo'] += (double)$impuestos_aux[$i]->importe_ieps_monto_fijo;
            }

            if(in_array($impuestos_aux[$i]->ish_tasa,array_column($impuestos_aux1,"ish_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_ish'] += (double)$impuestos_aux[$i]->importe_ish;
            }

            if(in_array($impuestos_aux[$i]->iva_retenido_tasa,array_column($impuestos_aux1,"iva_retenido_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_iva_retenido'] += (double)$impuestos_aux[$i]->importe_iva_retenido;
            }

            if(in_array($impuestos_aux[$i]->isr_tasa,array_column($impuestos_aux1,"isr_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_isr'] += (double)$impuestos_aux[$i]->importe_isr;
            }

            if(in_array($impuestos_aux[$i]->isn_tasa,array_column($impuestos_aux1,"isn_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_isn'] += (double)$impuestos_aux[$i]->importe_isn;
            }

            if(in_array($impuestos_aux[$i]->cedular_tasa,array_column($impuestos_aux1,"cedular_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_cedular'] += (double)$impuestos_aux[$i]->importe_cedular;
            }

            if(in_array($impuestos_aux[$i]->cinco_al_millar,array_column($impuestos_aux1,"cinco_al_millar"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_5_al_millar'] += (double)$impuestos_aux[$i]->importe_5_al_millar;
            }

            if(in_array($impuestos_aux[$i]->funcion_publica_tasa,array_column($impuestos_aux1,"funcion_publica_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_funcion_publica'] += (double)$impuestos_aux[$i]->importe_funcion_publica;
            }

            if(in_array($impuestos_aux[$i]->ieps_retenido_tasa,array_column($impuestos_aux1,"ieps_retenido_tasa"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_ieps_retenido'] += (double)$impuestos_aux[$i]->importe_ieps_retenido;
            }

            if(in_array($impuestos_aux[$i]->ieps_retenido_monto_fijo,array_column($impuestos_aux1,"ieps_retenido_monto_fijo"))){
                $impuestos_aux1[array_search($impuestos_aux[$i]->ticket_id, array_column($impuestos_aux1, 'ticket_id'))]['importe_ieps_retenido_monto_fijo'] += (double)$impuestos_aux[$i]->importe_ieps_retenido_monto_fijo;
            }

            } else {
            array_push($impuestos_aux1,[
                "ticket_id"=>$impuestos_aux[$i]->ticket_id,
                "subtotal_ticket"=>	$impuestos_aux[$i]->subtotal_ticket,
                "total_ticket"=>	$impuestos_aux[$i]->total_ticket,
                "producto_id"=>	$impuestos_aux[$i]->producto_id,
                "iva_tasa"=>	(double)$impuestos_aux[$i]->iva_tasa,
                "importe_iva"=>	(double)$impuestos_aux[$i]->importe_iva,
                "ieps_tasa"=>	(double)$impuestos_aux[$i]->ieps_tasa,
                "importe_ieps"=>	(double)$impuestos_aux[$i]->importe_ieps,
                "ieps_monto_fijo"=>	$impuestos_aux[$i]->ieps_monto_fijo,
                "importe_ieps_monto_fijo"=>	$impuestos_aux[$i]->importe_ieps_monto_fijo,
                "ish_tasa"=>	$impuestos_aux[$i]->ish_tasa,
                "importe_ish"=>	$impuestos_aux[$i]->importe_ish,
                "iva_retenido_tasa"=>	$impuestos_aux[$i]->iva_retenido_tasa,
                "importe_iva_retenido"=>	$impuestos_aux[$i]->importe_iva_retenido,
                "isr_tasa"=>	$impuestos_aux[$i]->isr_tasa,
                "importe_isr"=>	$impuestos_aux[$i]->importe_isr,
                "isn_tasa"=>	$impuestos_aux[$i]->isn_tasa,
                "importe_isn"=>	$impuestos_aux[$i]->importe_isn,
                "cedular_tasa"=>	$impuestos_aux[$i]->cedular_tasa,
                "importe_cedular"=>	$impuestos_aux[$i]->importe_cedular,
                "cinco_al_millar"=>	$impuestos_aux[$i]->cinco_al_millar,
                "importe_5_al_millar"=>	$impuestos_aux[$i]->importe_5_al_millar,
                "funcion_publica_tasa"=>	$impuestos_aux[$i]->funcion_publica_tasa,
                "importe_funcion_publica"=>	$impuestos_aux[$i]->importe_funcion_publica,
                "ieps_retenido_tasa"=>	$impuestos_aux[$i]->ieps_retenido_tasa,
                "importe_ieps_retenido"=>	$impuestos_aux[$i]->importe_ieps_retenido,
                "ieps_retenido_monto_fijo"=>	$impuestos_aux[$i]->ieps_retenido_monto_fijo,
                "importe_ieps_retenido_monto_fijo"=>	$impuestos_aux[$i]->importe_ieps_retenido_monto_fijo,
                "iva_exento"=>	$impuestos_aux[$i]->iva_exento,
                "ieps_exento"=>	$impuestos_aux[$i]->ieps_exento
            ]);
            }
        }
        
        return $impuestos_aux1;
    }

    function getGeneralInvoiceTaxUnique($value,$date,$date1)
    {
        $get_data = new get_data();
        $impuestos_aux = $get_data->getFormatTaxGeneralInvoice($value,$date,$date1);
        $data = [];
        $impuestos_aux1 = [];

        foreach ($impuestos_aux as $r) {
            if ($r['iva_tasa'] !== "" && $r['iva_tasa'] !== null && $r['iva_tasa'] > 0) {
            
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 1,
                "tasa" => $r['iva_tasa'],
                "importe" => (float)$r['importe_iva']
                )
            );
            }
            if ($r['ieps_tasa'] !== "" && $r['ieps_tasa'] !== null && $r['ieps_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 2,
                "tasa" => $r['ieps_tasa'],
                "importe" => (float)$r['importe_ieps']
                )
            );
            }
            if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null && $$r['ieps_monto_fijo'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 3,
                "tasa" => $r['ieps_monto_fijo'],
                "importe" => (float)$r['importe_ieps_monto_fijo']
                )
            );
            }
            if ($r['ish_tasa'] !== "" && $r['ish_tasa'] !== null && $r['ish_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 4,
                "tasa" => $r['ish_tasa'],
                "importe" => (float)$r['importe_ish']
                )
            );
            }
            // if ($r->iva_exento !== "" && $r->iva_exento !== null) {
            //   array_push(
            //     $impuestos_aux1,
            //     array(
            //       "impuesto" => 5,
            //       "tasa" => $r->iva_exento,
            //       "importe" => (float)$r->importe_iva_exento
            //     )
            //   );
            // }

            if ($r['iva_retenido_tasa'] !== "" && $r['iva_retenido_tasa'] !== null && $r['iva_retenido_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 6,
                "tasa" => $r['iva_retenido_tasa'],
                "importe" => (float)$r['importe_iva_retenido']
                )
            );
            }
            if ($r['isr_tasa'] !== "" && $r['isr_tasa'] !== null && $r['isr_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 7,
                "tasa" => $r['isr_tasa'],
                "importe" => (float)$r['importe_isr']
                )
            );
            }
            if ($r['isn_tasa'] !== "" && $r['isn_tasa'] !== null && $r['isn_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 8,
                "tasa" => $r['isn_tasa'],
                "importe" => (float)$r['importe_isn']
                )
            );
            }
            if ($r['cedular_tasa'] !== "" && $r['cedular_tasa'] !== null && $r['cedular_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 9,
                "tasa" => $r['cedular_tasa'],
                "importe" => (float)$r['importe_cedular']
                )
            );
            }
            if ($r['cinco_al_millar'] !== "" && $r['cinco_al_millar'] !== null && $r['cinco_al_millar'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 10,
                "tasa" => $r['cinco_al_millar'],
                "importe" => (float)$r['importe_5_al_millar']
                )
            );
            }
            if ($r['funcion_publica_tasa'] !== "" && $r['funcion_publica_tasa'] !== null && $r['funcion_publica_tasa'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 11,
                "tasa" => $r['funcion_publica_tasa'],
                "importe" => (float)$r['importe_funcion_publica']
                )
            );
            }
            if ($r['ieps_retenido_tasa'] !== "" && $r['ieps_retenido_tasa'] !== null && $r['ieps_retenido_tasa'] >= 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 12,
                "tasa" => $r['ieps_retenido_tasa'],
                "importe" => (float)$r['importe_ieps_retenido']
                )
            );
            }
            // if ($r->isr_exento !== "" && $r->isr_exento !== null) {
            //   array_push(
            //     $impuestos_aux1,
            //     array(
            //       "impuesto" => 13,
            //       "tasa" => $r->isr_exento,
            //       "importe" => (float)$r->importe_isr_exento
            //     )
            //   );
            // }
            
            // if ($r->isr_retenido_monto_fijo !== "" && $r->isr_retenido_monto_fijo !== null) {
            //   array_push(
            //     $impuestos_aux1,
            //     array(
            //       "impuesto" => 17,
            //       "tasa" => $r->isr_retenido_monto_fijo,
            //       "importe" => (float)$r->isr_retenido_monto_fijo
            //     )
            //   );
            // }
            if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null && $r['ieps_retenido_monto_fijo'] > 0) {
            array_push(
                $impuestos_aux1,
                array(
                "impuesto" => 18,
                "tasa" => $r['ieps_retenido_monto_fijo'],
                "importe" => (float)$r['importe_ieps_retenido_monto_fijo']
                )
            );
            }
        }

        for ($i = 0; $i < count($impuestos_aux1); $i++) {
            for ($j = $i + 1; $j < count($impuestos_aux1); $j++) {
            if (
                $impuestos_aux1[$i]['tasa'] == $impuestos_aux1[$j]['tasa'] && 
                $impuestos_aux1[$i]['tasa'] !== 0 && $impuestos_aux1[$i]['tasa'] !== "0" && 
                $impuestos_aux1[$i]['impuesto'] == $impuestos_aux1[$j]['impuesto']
            ) {
                $impuestos_aux1[$i]['importe'] = $impuestos_aux1[$i]['importe'] + $impuestos_aux1[$j]['importe'];
                $impuestos_aux1[$j]['importe'] = 0;
            }
            }
        }
    
        $impuestos_aux2 = [];
    
        foreach ($impuestos_aux1 as $r) {
            if ($r['importe'] !== 0) {
            array_push(
                $impuestos_aux2,
                array(
                "impuesto" => $r['impuesto'],
                "tasa" => $r["tasa"],
                "importe" => $r["importe"]
                )
            );
            }
        }
        
        return $impuestos_aux2;
      
    }

    function getDataClient($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
                            select
                            c.razon_social,
                            c.rfc,
                            crf.clave,
                            c.Email,
                            c.codigo_postal
                            from 
                            clientes c
                            inner join ticket_punto_venta t on c.PKCliente = t.cliente_id
                            inner join claves_regimen_fiscal crf on c.regimen_fiscal_id = crf.id
                            where 
                            t.empresa_id = {$_SESSION['IDEmpresa']} and t.id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getClientDefault()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.Email,
                            cl.codigo_postal,
                            rf.clave,
                            cl.rfc
                            from 
                            clientes cl
                            inner join 
                            claves_regimen_fiscal rf 
                            on 
                            cl.regimen_fiscal_id = rf.id
                            where 
                            empresa_id = {$_SESSION['IDEmpresa']} and 
                            Predeterminado = 1");

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getProductoDefault()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select 
                            p.PKProducto id,
                            ifp.FKClaveSAT clave_sat,
                                ifp.FKClaveSATUnidad uni_sat
                            from 
                            productos p
                            inner join 
                            info_fiscal_productos ifp
                            on 
                            p.PKProducto = ifp.FKProducto
                            where 
                            empresa_id = {$_SESSION['IDEmpresa']} and 
                            Predeterminado = 1");

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getLastTicket()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select id from ticket_punto_venta where empresa_id = {$_SESSION['IDEmpresa']} order by id desc limit 1");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFormatClientInvoice($value)
    {
        $get_data = new get_data();
        $data = $get_data->getDataClient($value);
        // $email = "omar.garcia@ghasistencia.com";
        // $data[0]->Email
        $client = [
            "legal_name" => $data[0]->razon_social,
            "email" => $data[0]->Email,
            "tax_id" => $data[0]->rfc,
            "tax_system" => $data[0]->clave,
            "address" => [
            "zip" => $data[0]->codigo_postal
            ]
        ];

        return $client;

    }

    function getFormatProductsInvoice($value)
    {
        $get_data = new get_data();
        $data = $get_data->getProductsTicket($value,"","");
        $products = [];
        foreach ($data as $r) {
            
            $impuestos = [];
            $impuestos_local = [];
            if ($r->iva_tasa !== null && $r->iva_tasa !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Tasa",
                "type" => "IVA",
                "rate" => ($r->iva_tasa / 100)
                ]
            );
            }
            if ($r->ieps_tasa !== null && $r->ieps_tasa !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Tasa",
                "type" => "IEPS",
                "rate" => ($r->ieps_tasa / 100)
                ]
            );
            }
            if ($r->ieps_monto_fijo !== null && $r->ieps_monto_fijo !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Cuota",
                "type" => "IEPS",
                "rate" => $r->ieps_monto_fijo
                ]
            );
            }
        
            if ((int)$r->iva_exento !== null && (int)$r->iva_exento !== "" && (int)$r->iva_exento !== 0) {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Exento",
                "type" => "IVA"
                ]
            );
            }
            if ($r->iva_retenido_tasa !== null && $r->iva_retenido_tasa !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Tasa",
                "type" => "IVA",
                "rate" => ($r->iva_retenido_tasa / 100)
                ]
            );
            }
            if ($r->isr_tasa !== null && $r->isr_tasa !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Tasa",
                "type" => "ISR",
                "rate" => ($r->isr_tasa / 100)
                ]
            );
            }
            if ($r->ieps_retenido_tasa !== null && $r->ieps_retenido_tasa !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Tasa",
                "type" => "IEPS",
                "rate" => ($r->ieps_retenido_tasa / 100)
                ]
            );
            }
        
            if ((int)$r->ieps_exento !== null && (int)$r->ieps_exento !== "" && (int)$r->ieps_exento !== 0) {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Exento",
                "type" => "IEPS",
                "rate" => ($r->ieps_exento / 100)
                ]
            );
            }
    
            if ($r->ieps_retenido_monto_fijo !== null && $r->ieps_retenido_monto_fijo !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Cuota",
                "type" => "IEPS",
                "rate" => $r->ieps_retenido_monto_fijo
                ]
            );
            }
            if ($r->ish_tasa !== null && $r->ish_tasa !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "ISH",
                "rate" => ($r->ish_tasa / 100)
                ]
            );
            }
            if ($r->isn_tasa !== null && $r->isn_tasa !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "ISN",
                "rate" => ($r->isn_tasa / 100)
                ]
            );
            }
            if ($r->cedular_tasa !== null && $r->cedular_tasa !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "Cedular",
                "rate" => ($r->cedular_tasa / 100)
                ]
            );
            }
            if ($r->cinco_al_millar !== null && $r->cinco_al_millar !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "5 al millar",
                "rate" => ($r->cinco_al_millar / 100)
                ]
            );
            }
            if ($r->funcion_publica_tasa !== null && $r->funcion_publica_tasa !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "Funcion publica",
                "rate" => ($r->funcion_publica_tasa / 100)
                ]
            );
            }

            array_push($products,[
            "quantity" => $r->cantidad,
            "product" => [
                "tax_included" => false,
                "description" => str_replace('"','\"',str_replace(['\r','\n'],"",$r->nombre)),
                "product_key" => $r->clave_sat,
                "price" => $r->precio_unitario,
                "unit_key" => $r->clave_sat_unidad,
                "sku" => $r->sku,
                "taxes" => $impuestos,
                "local_taxes" => $impuestos_local
            ]
            ]);
        }

        return $products;
    }

    function getFormatInvoiceGeneral($value,$value1)
    {
        $get_data = new get_data();
        $data = $get_data->getDataTicket("",$value,$value1);
        $data1 = $get_data->getFormatTaxGeneralInvoice("",$value,$value1);
        // $data1 = $get_data->getProductsTicketTax("",$value,$value1);
        $products = [];

        foreach ($data1 as $r) {
            $impuestos = [];
            $impuestos_local = [];
            if ($r['iva_tasa'] !== null && $r['iva_tasa'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Tasa",
                "type" => "IVA",
                "rate" => ($r['iva_tasa'] / 100)
                ]
            );
            }
            if ($r['ieps_tasa'] !== null && $r['ieps_tasa'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Tasa",
                "type" => "IEPS",
                "rate" => ($r['ieps_tasa'] / 100)
                ]
            );
            }
            if ($r['ieps_monto_fijo'] !== null && $r['ieps_monto_fijo'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Cuota",
                "type" => "IEPS",
                "rate" => $r['ieps_monto_fijo']
                ]
            );
            }
        
            if ((int)$r['iva_exento'] !== null && (int)$r['iva_exento'] !== "" && (int)$r['iva_exento'] !== 0) {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Exento",
                "type" => "IVA"
                ]
            );
            }
            if ($r['iva_retenido_tasa'] !== null && $r['iva_retenido_tasa'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Tasa",
                "type" => "IVA",
                "rate" => round(($r['iva_retenido_tasa'] / 100),6)
                ]
            );
            }
            if ($r['isr_tasa'] !== null && $r['isr_tasa'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Tasa",
                "type" => "ISR",
                "rate" => ($r['isr_tasa'] / 100)
                ]
            );
            }
            if ($r['ieps_retenido_tasa'] !== null && $r['ieps_retenido_tasa'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Tasa",
                "type" => "IEPS",
                "rate" => ($r['ieps_retenido_tasa'] / 100)
                ]
            );
            }
        
            if ((int)$r['ieps_exento'] !== null && (int)$r['ieps_exento'] !== "" && (int)$r['ieps_exento'] !== 0) {
            array_push(
                $impuestos,
                [
                "withholding" => false,
                "factor" => "Exento",
                "type" => "IEPS",
                "rate" => ($r['ieps_exento'] / 100)
                ]
            );
            }
    
            if ($r['ieps_retenido_monto_fijo'] !== null && $r['ieps_retenido_monto_fijo'] !== "") {
            array_push(
                $impuestos,
                [
                "withholding" => true,
                "factor" => "Cuota",
                "type" => "IEPS",
                "rate" => $r['ieps_retenido_monto_fijo']
                ]
            );
            }
            if ($r['ish_tasa'] !== null && $r['ish_tasa'] !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "ISH",
                "rate" => ($r['ish_tasa'] / 100)
                ]
            );
            }
            if ($r['isn_tasa'] !== null && $r['isn_tasa'] !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "ISN",
                "rate" => ($r['isn_tasa'] / 100)
                ]
            );
            }
            if ($r['cedular_tasa'] !== null && $r['cedular_tasa'] !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "Cedular",
                "rate" => ($r['cedular_tasa'] / 100)
                ]
            );
            }
            if ($r['cinco_al_millar'] !== null && $r['cinco_al_millar'] !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "5 al millar",
                "rate" => ($r['cinco_al_millar'] / 100)
                ]
            );
            }
            if ($r['funcion_publica_tasa'] !== null && $r['funcion_publica_tasa'] !== "") {
            array_push(
                $impuestos_local,
                [
                "withholding" => false,
                
                "type" => "Funcion publica",
                "rate" => ($r['funcion_publica_tasa'] / 100)
                ]
            );
            }

            array_push($products,[
            "quantity" => 1,
            "product" => [
                "tax_included" => false,
                "description" => "Venta",
                "product_key" => "01010101",
                "price" => $r['subtotal_ticket'],
                "unit_key" => "ACT",
                "taxes" => $impuestos,
                "local_taxes" => $impuestos_local
            ]
            ]);
        }
        
        return $products;
    }

    function getFormatClientGeneralInvoice()
    {
        $get_data = new get_data();

        $client = [
            "legal_name" => $get_data->getClientDefault()[0]->razon_social,
            "email" => $get_data->getClientDefault()[0]->Email,
            "tax_id" => $get_data->getClientDefault()[0]->rfc,
            "tax_system" => $get_data->getClientDefault()[0]->clave,
            "address" => [
            "zip" => $get_data->getClientDefault()[0]->codigo_postal
            ]
        ];

        return $client;
    }

    function getKeyPaymentForm($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select clave from formas_pago_sat where id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getKeyCfdiUse($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select clave from uso_cfdi where id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getKeyCurrency($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select Clave clave from monedas where PKMoneda = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFormatInvoice($value,$value1)
    {
        $get_data = new get_data();
        $invoice = [];
        $folio_serie = $get_data->getFolioSerie();
        $fechaEmision = date("c");
        $data = json_decode($value1);

        $paidType = $get_data->getKeyPaymentForm($data->paidType)[0]->clave;
        $cfdiUse = $get_data->getKeyCfdiUse($data->cfdiUse)[0]->clave;
        $currency = $get_data->getKeyCurrency($data->currency)[0]->clave;
        

        $invoice = [
            "customer" => $get_data->getFormatClientInvoice($value),
            "items" => $get_data->getFormatProductsInvoice($value),
            "payment_form"=> $paidType,
            "type" => "I",
            "use" => $cfdiUse,
            "payment_method" => $data->paidMethod,
            "currency" => $currency,
            "folio_number" => $folio_serie['folio'],
            "date" => $fechaEmision,
            "series" => $folio_serie['serie']
        ];

        return $invoice;
    }

    function getFormatGeneralInvoice($value,$value1,$value2)
    {
        $get_data = new get_data();
        $invoice = [];
        $folio_serie = $get_data->getFolioSerie();
        $fechaEmision = date("c");
        $data = json_decode($value);

        $paidType = $get_data->getKeyPaymentForm($data->paidType)[0]->clave;
        $cfdiUse = $get_data->getKeyCfdiUse($data->cfdiUse)[0]->clave;
        $currency = $get_data->getKeyCurrency($data->currency)[0]->clave;
        
        $invoice = [
            "customer" => $get_data->getFormatClientGeneralInvoice(),
            "items" => $get_data->getFormatInvoiceGeneral($value1,$value2),
            "payment_form"=> $paidType,
            "type" => "I",
            "use" => $cfdiUse,
            "payment_method" => $data->paidMethod,
            "currency" => $currency,
            "folio_number" => $folio_serie['folio'],
            "date" => $fechaEmision,
            "series" => $folio_serie['serie'],
            "global" => [
                "periodicity" => $data->periodicity,
                "months" => $data->month,
                "year" => $data->year
            ]
        ];

        return $invoice;
    }

    function getKeyCompanyApi()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select key_company_api api_key from empresas where PKEmpresa = {$_SESSION['IDEmpresa']}");

        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getGeneralInvoice($value,$value1)
    {
        $get_data = new get_data();
        $arr = $get_data->getDataTicket("",$value,$value1);
        $table = "";
        $no = 1;
        foreach($arr as $r)
        {
            $table .= '{
            "no" : "' . $no . '",
            "folio" : "' . $r->folio . '",
            "date" : "' . date("d-m-Y",strtotime($r->fecha)) . '",
            "amount" : "$' . number_format($r->subtotal,2) . '"
            },';

            $no++;
        }

        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    function getTaxGeneralInvoice($value,$value1)
    {
        $get_print = new get_print();
        return $get_print->getFormatTicketTax("",$value,$value1);
    }

    function getProductsGeneralInvoice($value,$value1)
    {
        $get_data = new get_data();
        $arr = $get_data->getProductsTicket("",$value,$value1);
        $table = "";

        foreach($arr as $r)
        {
            $table .= '{
            "quantity" : "' . $r->cantidad . '",
            "description" : "' . str_replace('"','\"',str_replace(['\r','\n'],"",$r->nombre)) . '",
            "discount" : "' . $r->descuento . '",
            "unit_price" : "' . $r->precio_unitario . '",
            "amount" : "' . $r->total . '"
            },';
        }

        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    function getFolioSerie()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select serie_inicial serie, folio_inicial folio from empresas where PKEmpresa = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
        $stmt->execute();

        $serieFolio = $stmt->fetchAll();

        $query = sprintf("select id, serie, folio from facturacion where empresa_id = :id and serie <> '' order by folio desc LIMIT 1");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
        $stmt->execute();

        $arr = $stmt->fetchAll();

        $stmt = null;
        $db = null;

        if (count($arr) > 0) 
        {
            $serie = $arr[0]['serie'];
            $folio = str_pad(($arr[0]['folio'] + 1), 5, "0", STR_PAD_LEFT);
        } else 
        {
            $serie =  $serieFolio[0]['serie'];
            $folio = str_pad($serieFolio[0]['folio'], 5, "0", STR_PAD_LEFT);
        }

        $seriefolio = [
            "serie" => $serie,
            "folio" => $folio
        ];

        return $seriefolio;
    }

    function getPaidType()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select id, concat(clave,' - ',descripcion) texto from formas_pago_sat");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getCfdiUse()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select id, concat(clave,' - ',descripcion) texto from uso_cfdi");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getIdTicketGeneralInvoice($value,$value1)
    {
        $get_data = new get_data();
        $arr = $get_data->getDataTicket("",$value,$value1);
        $aux = "";
        foreach($arr as $r)
        {
            $aux .= '"'.$r->id.'",';
        }

        $aux = substr($aux, 0, strlen($aux) - 1);

        return '[' . $aux . ']';
    }

    function getFormatSaveDetailGeneralInvoice($value,$value1)
    {
        $get_data = new get_data();
        $arr = $get_data->getDataTicket("",$value,$value1);
        $aux = [];

        foreach ($arr as $r) {
            array_push($aux,[
            "subtotal" => $r->subtotal,
            "total" => $r->total
            ]);
        }
        return $aux;
    }

    function getCountSalesTicket()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select count(*) from inventario_salida_por_sucursales where tipo_salida = 5");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchColumn();

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFolioSaleTicket()
    {
        $get_data = new get_data();
        $rowCount = $get_data->getCountSalesTicket();
        $text = "";
        if($rowCount > 0){
            $aux = $rowCount + 1;
            $text = "TPV-{$aux}";
        } else {
            $text = "TPV-1";
        }
        return $text;
    }

    function getIfPrinterNameExist($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select * from cuentas_punto_venta where id = :id and (nombre_impresora <> '' or nombre_impresora <> null)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        $arr = $stmt->rowCount();

        $stmt = null;
        $db = null;

        return $arr;

    }

    function getPrinterName($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select nombre_impresora from cuentas_punto_venta where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;

    }

    function getStockIdUpdateCancel($value1,$value2)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
                        select 
                            id 
                        from 
                            existencia_por_productos 
                        where 
                            producto_id = :producto_id and 
                            sucursal_id = :sucursal_id and 
                            existencia >= 0
                        
                        order by id asc limit 1");
        $stmt =  $db->prepare($query);
        $stmt->bindValue(":producto_id",$value1);
        $stmt->bindValue(":sucursal_id",$value2);

        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function checkPriceZero($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select * from productos_ticket_temp where precio_unitario = 0 and caja_id = :caja_id and usuario_id = :usuario_id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":caja_id",$value);
        $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
        $stmt->execute();

        return $stmt->rowCount();
    }

    function getPasswordAdmin($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select password_admin from cuentas_punto_venta where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    function getTotalTicket($value){
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select total from ticket_punto_venta where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getStockProductAll($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select id, existencia, numero_lote,clave_producto from existencia_por_productos where producto_id = :id and sucursal_id = :sucursal_id order by id asc");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->bindValue(":sucursal_id",$value1);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getFolioVentaDirecta()
    {
        $con = new conection();
        $db = $con->getDb();
        $query = sprintf("select referencia from ventas_directas where empresa_id = :empresa_id order by PKVentaDirecta desc limit 1");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        $folio = $stmt->fetch()['referencia'];

        $arr = explode("VD", $folio);

        return (int)$arr[1];
    }

    function getFolioTicketById($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("select folio from ticket_punto_venta where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getCajeros()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
            select 
                e.PKEmpleado id, 
                concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) texto 
            from 
                empleados e
            inner join
                relacion_tipo_empleado t
            on 
                e.PKEmpleado = t.empleado_id
            where 
                e.empresa_id = :empresa_id
            and 
                t.tipo_empleado_id = 10
            ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getUserNameFounder()
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
            select 
                lower(replace(nombre,' ','')) nombre
            from 
                usuarios 
            where 
                empresa_id = :empresa_id and founder = 1");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    function getEmployerNameFounder()
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $name = $get_data->getUserNameFounder()[0]->nombre;
        $query = sprintf("
            select 
                e.PKEmpleado 
            from 
                empleados e 
            where 
                lower(replace(concat(e.Nombres,e.PrimerApellido,e.SegundoApellido),' ','')) = :name and empresa_id = :empresa_id");
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(":name",$name);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getIfCategoryExist($value)
    {
      $con = new conection();
      $db = $con->getDb();
      $aux = str_replace(" ","",strtolower($value));
      $query = sprintf("select * from categorias_productos where empresa_id = :empresa_id and lower(replace(CategoriaProductos,' ','')) = :nombre");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
      $stmt->bindValue(":nombre",$aux);
      $stmt->execute();

      return $stmt->rowCount();
    }

    function getIfMarkExist($value)
    {
      $con = new conection();
      $db = $con->getDb();
      $aux = str_replace(" ","",strtolower($value));
      $query = sprintf("select * from marcas_productos where empresa_id = :empresa_id and lower(replace(MarcaProducto,' ','')) = :nombre");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
      $stmt->bindValue(":nombre",$aux);
      $stmt->execute();

      return $stmt->rowCount();
    }
  }

  class update_data
  {

    function updateProductsTableTemp($data)
    {
        $con = new conection();
        $db = $con->getDb();

        $tasa_iva = !empty($data['tasa_iva']) ? $data['tasa_iva'] : 'NULL';
        $importe_iva = !empty($data['importe_iva']) ? $data['importe_iva'] : 'NULL';
        $ieps_tasa = !empty($data['ieps_tasa']) ? $data['ieps_tasa'] : 'NULL';
        $importe_ieps = !empty($data['importe_ieps']) ? $data['importe_ieps'] : 'NULL';
        $ieps_monto_fijo = !empty($data['ieps_monto_fijo']) ? $data['ieps_monto_fijo'] : 'NULL';
        $importe_ieps_monto_fijo = !empty($data['importe_ieps_monto_fijo']) ? $data['importe_ieps_monto_fijo'] : 'NULL';
        $ish_tasa = !empty($data['ish_tasa']) ? $data['ish_tasa'] : 'NULL';
        $importe_ish = !empty($data['importe_ish']) ? $data['importe_ish'] : 'NULL';
        $iva_exento = !empty($data['iva_exento']) ? $data['iva_exento'] : 0;
        $iva_retenido_tasa = !empty($data['iva_retenido_tasa']) ? $data['iva_retenido_tasa'] : 'NULL';
        $importe_iva_retenido = !empty($data['importe_iva_retenido']) ? $data['importe_iva_retenido'] : 'NULL';
        $isr_tasa = !empty($data['isr_tasa']) ? $data['isr_tasa'] : 'NULL';
        $importe_isr = !empty($data['importe_isr']) ? $data['importe_isr'] : 'NULL';
        $isn_tasa = !empty($data['isn_tasa']) ? $data['isn_tasa'] : 'NULL';
        $importe_isn = !empty($data['importe_isn']) ? $data['importe_isn'] : 'NULL';
        $cedular_tasa = !empty($data['cedular_tasa']) ? $data['cedular_tasa'] : 'NULL';
        $importe_cedular = !empty($data['importe_cedular']) ? $data['importe_cedular'] : 'NULL';
        $cinco_al_millar = !empty($data['cinco_al_millar']) ? $data['cinco_al_millar'] : 'NULL';
        $importe_5_al_millar = !empty($data['importe_5_al_millar']) ? $data['importe_5_al_millar'] : 'NULL';
        $funcion_publica_tasa = !empty($data['funcion_publica_tasa']) ? $data['funcion_publica_tasa'] : 'NULL';
        $importe_funcion_publica = !empty($data['importe_funcion_publica']) ? $data['importe_funcion_publica'] : 'NULL';
        $ieps_retenido_tasa = !empty($data['ieps_retenido_tasa']) ? $data['ieps_retenido_tasa'] : 'NULL';
        $importe_ieps_retenido = !empty($data['importe_ieps_retenido']) ? $data['importe_ieps_retenido'] : 'NULL';
        $ieps_exento = !empty($data['ieps_exento']) ? $data['ieps_exento'] : 'NULL';
        $ieps_retenido_monto_fijo = !empty($data['ieps_retenido_monto_fijo']) ? $data['ieps_retenido_monto_fijo'] : 'NULL';
        $importe_ieps_retenido_monto_fijo = !empty($data['importe_ieps_retenido_monto_fijo']) ? $data['importe_ieps_retenido_monto_fijo'] : 'NULL';

        $query = sprintf("update 
                            productos_ticket_temp 
                            set 
                            cantidad = {$data['cantidad']},
                            precio_unitario = {$data['precio_u']},
                            subtotal = {$data['subtotal']},
                            iva_tasa = {$tasa_iva},
                            importe_iva = {$importe_iva},
                            ieps_tasa = {$ieps_tasa},
                            importe_ieps = {$importe_ieps},
                            ieps_monto_fijo = {$ieps_monto_fijo},
                            importe_ieps_monto_fijo = {$importe_ieps_monto_fijo},
                            ish_tasa = {$ish_tasa},
                            importe_ish = {$importe_ish},
                            iva_exento = {$iva_exento},
                            iva_retenido_tasa = {$iva_retenido_tasa},
                            importe_iva_retenido = {$importe_iva_retenido},
                            isr_tasa = {$isr_tasa},
                            importe_isr = {$importe_isr},
                            isn_tasa = {$isn_tasa},
                            importe_isn = {$importe_isn},
                            cedular_tasa = {$cedular_tasa},
                            importe_cedular = {$importe_cedular},
                            cinco_al_millar = {$cinco_al_millar},
                            importe_5_al_millar = {$importe_5_al_millar},
                            funcion_publica_tasa = {$funcion_publica_tasa},
                            importe_funcion_publica = {$importe_funcion_publica},
                            ieps_retenido_tasa = {$ieps_retenido_tasa},
                            importe_ieps_retenido = {$importe_ieps_retenido},
                            ieps_exento = {$ieps_exento},
                            ieps_retenido_monto_fijo = {$ieps_retenido_monto_fijo},
                            importe_ieps_retenido_monto_fijo = {$importe_ieps_retenido_monto_fijo},
                            total = {$data['total']}
                            where 
                            producto_id = {$data['producto_id']} and
                            caja_id = {$data['caja_id']} and
                            usuario_id = {$_SESSION['PKUsuario']}
                        ");
        $stmt = $db->prepare($query);

        $arr = $stmt->execute();


        $stmt = null;
        $db = null;


        return $arr;
    }

    function updateGeneralDataProduct($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);
        
        try{
            $db->beginTransaction();
            
            $query = sprintf("
                            update productos set
                                ClaveInterna = :clave,
                                CodigoBarras = :codigo_barras,
                                Nombre = :nombre,
                                Descripcion = :descripcion,
                                FKCategoriaProducto = :categoria,
                                FKTipoProducto = :tipo,
                                FKMarcaProducto = :marca,
                                usuario_edicion_id = :usuario_edicion_id,
                                empresa_id = :empresa_id,
                                updated_at = :updated_at,
                                serie = :serie,
                                lote = :lote,
                                fecha_caducidad = :fecha_caducidad,
                                precio_compra = :precio_compra,
                                precio_compra_sin_impuesto = :precio_compra_sin_impuesto,
                                precio_compra_neto = :precio_compra_neto,
                                utilidad1 = :utilidad1,
                                utilidad2 = :utilidad2,
                                utilidad3 = :utilidad3,
                                utilidad4 = :utilidad4,
                                precio_venta1 = :precio_venta1,
                                precio_venta2 = :precio_venta2,
                                precio_venta3 = :precio_venta3,
                                precio_venta4 = :precio_venta4,
                                precio_venta_neto1 = :precio_venta_neto1,
                                precio_venta_neto2 = :precio_venta_neto2,
                                precio_venta_neto3 = :precio_venta_neto3,
                                precio_venta_neto4 = :precio_venta_neto4,
                                Imagen = :imagen,
                                receta = :receta
                            where PKProducto = :id
                            ");

            $stmt = $db->prepare($query);
            $stmt->bindValue(":clave",$data[0]->clave);
            $stmt->bindValue(":codigo_barras",$data[0]->codigo_barras);
            $stmt->bindValue(":nombre",$data[0]->nombre);
            $stmt->bindValue(":descripcion",$data[0]->descripcion);
            $stmt->bindValue(":categoria",$data[0]->categoria);
            $stmt->bindValue(":tipo",$data[0]->tipo_producto);
            $stmt->bindValue(":marca",$data[0]->marca);
            $stmt->bindValue(":usuario_edicion_id",$_SESSION['PKUsuario']);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":updated_at",date("Y-m-d H:i:s"));
            $stmt->bindValue(":serie",$data[0]->chkSerie);
            $stmt->bindValue(":lote",$data[0]->chkLote);
            $stmt->bindValue(":fecha_caducidad",$data[0]->chk_fecha_caducidad);
            $stmt->bindValue(":precio_compra",$data[0]->precio_compra);
            $stmt->bindValue(":precio_compra_sin_impuesto",$data[0]->precio_compra_sin_impuesto);
            $stmt->bindValue(":precio_compra_neto",$data[0]->precio_compra_neto);
            $stmt->bindValue(":utilidad1",$data[0]->utilidad1);
            $stmt->bindValue(":utilidad2",$data[0]->utilidad2);
            $stmt->bindValue(":utilidad3",$data[0]->utilidad3);
            $stmt->bindValue(":utilidad4",$data[0]->utilidad4);
            $stmt->bindValue(":precio_venta1",$data[0]->precio_venta1);
            $stmt->bindValue(":precio_venta2",$data[0]->precio_venta2);
            $stmt->bindValue(":precio_venta3",$data[0]->precio_venta3);
            $stmt->bindValue(":precio_venta4",$data[0]->precio_venta4);
            $stmt->bindValue(":precio_venta_neto1",$data[0]->precio_venta_neto1);
            $stmt->bindValue(":precio_venta_neto2",$data[0]->precio_venta_neto2);
            $stmt->bindValue(":precio_venta_neto3",$data[0]->precio_venta_neto3);
            $stmt->bindValue(":precio_venta_neto4",$data[0]->precio_venta_neto4);
            $stmt->bindValue(":imagen","agregar.svg");
            $stmt->bindValue(":receta" ,$data[0]->chkReceta);
            $stmt->bindValue(":id",$value1);
           
            $stmt->execute();
            
            return $db->commit();

        } catch(PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function updateFiscalDataProduct($value,$id)
    {
        $con = new conection();
        $db = $con->getDb();
        
        $data = json_decode($value);

        try{
            $db->beginTransaction();

            $query = sprintf("update info_fiscal_productos set
                                FKClaveSAT = :clave_sat,
                                FKClaveSATUnidad = :clave_unidad
                            where FKProducto = :producto_id
                            ");
            $stmt = $db->prepare($query);
            $stmt->execute([
            ":producto_id" => (int)$id,
            ":clave_sat" => $data[0]->clave_sat,
            ":clave_unidad" => $data[0]->clave_unidad
            ]);

            return $db->commit();
            
        } catch(PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        } 
    }

    function updateTaxProduct($value,$id)
    {
        $delete_data = new delete_data();
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);

        $delete_data->deleteTaxProduct($id);
        
        $values_sql = "";

        foreach($data as $r => $val){
            $values_sql .= "({$id},{$val->tax_id},{$val->rate}),";
        }

        $values_sql = rtrim($values_sql, ',');

        try {
            $db->beginTransaction();

            $query = sprintf("insert into impuestos_productos 
                            (
                                FKInfoFiscalProducto,
                                FKImpuesto,
                                Tasa
                            ) values
                            ".$values_sql."
                            ");
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $db->commit();

        } catch (PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function updateSellCost($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);

        $query = sprintf("update costo_venta_producto set CostoGeneral = :CostoGeneral, CostoCompra = :CostoCompra where FKProducto = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':CostoGeneral',$data[0]->precio_compra);
        $stmt->bindValue(':CostoCompra',$data[0]->precio_venta1);
        $stmt->bindValue(':id',$value1);
        return $stmt->execute();
    }

    function updateProduct($value,$value1)
    {
        $update_data = new update_data();
        $get_data = new get_data();
        $data = json_decode($value);
        $ban = false;

        $data_general = $data->data[0]->data_general;
        $data_fiscal = $data->data[2]->fiscal_data;
        $data_tax = $data->data[1]->tax_product;

        $ban = $update_data->updateGeneralDataProduct(json_encode($data_general),$value1);
        if($ban){
            $update_data->updateSellCost(json_encode($data_general),$value1);
            $ban = $update_data->updateFiscalDataProduct(json_encode($data_fiscal),$value1);
            if($ban){
            $idInfoFiscal = $get_data->getInfoFiscalProductosId($value1);
            if(count($idInfoFiscal) > 0){
                $ban = $update_data->updateTaxProduct(json_encode($data_tax),$idInfoFiscal[0]->id);
            }
            }
        }
        return $ban;
    }

    function updatePendingSale($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("update 
                            productos_ticket_temp 
                            set 
                            venta_pendiente = (venta_pendiente + 1) 
                            where 
                            caja_id = {$value} and 
                            usuario_id = {$_SESSION['PKUsuario']}");
        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function updateProductTicket($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
       
        $data = json_decode($value);
        $taxes_product = $get_data->getProductTaxes($data->producto_id);
        $format_taxes = $get_data->getCalculatingTaxes($taxes_product,$data->subtotal,$data->cantidad,$data->precio_unitario);

        $total = (double)$data->subtotal + (double)$format_taxes['total'];
        $tasa_iva = $format_taxes['tasa_iva'] !== null && $format_taxes['tasa_iva'] !== "" ? $format_taxes['tasa_iva'] : "null";
        $importe_iva = $format_taxes['importe_iva'] !==null && $format_taxes['importe_iva'] !== "" ? $format_taxes['importe_iva'] : "null";
        $ieps_tasa = $format_taxes['ieps_tasa'] !== null && $format_taxes['ieps_tasa'] !== "" ? $format_taxes['ieps_tasa'] : "null";
        $importe_ieps = $format_taxes['importe_ieps'] !== null && $format_taxes['importe_ieps'] !== "" ? $format_taxes['importe_ieps'] : "null";
        $ieps_monto_fijo = $format_taxes['ieps_monto_fijo'] !== null && $format_taxes['ieps_monto_fijo'] !== "" ? $format_taxes['ieps_monto_fijo'] : "null";
        $importe_ieps_monto_fijo = $format_taxes['importe_ieps_monto_fijo'] !== null && $format_taxes['importe_ieps_monto_fijo'] !== "" ? $format_taxes['importe_ieps_monto_fijo'] : "null";
        $ish_tasa = $format_taxes['ish_tasa'] !== null && $format_taxes['ish_tasa'] !== "" ? $format_taxes['ish_tasa'] : "null";
        $importe_ish = $format_taxes['importe_ish'] !== null && $format_taxes['importe_ish'] !== "" ? $format_taxes['importe_ish'] : "null";
        $iva_exento = $format_taxes['iva_exento'] !== null && $format_taxes['iva_exento'] !== "" ? $format_taxes['iva_exento'] : "null";
        $iva_retenido_tasa = $format_taxes['iva_retenido_tasa'] !== null && $format_taxes['iva_retenido_tasa'] !== "" ? $format_taxes['iva_retenido_tasa'] : "null";
        $importe_iva_retenido = $format_taxes['importe_iva_retenido'] !== null && $format_taxes['importe_iva_retenido'] !== "" ? $format_taxes['importe_iva_retenido'] : "null";
        $isr_tasa = $format_taxes['isr_tasa'] !== null && $format_taxes['isr_tasa'] !== "" ? $format_taxes['isr_tasa'] : "null";
        $importe_isr = $format_taxes['importe_isr'] !== null && $format_taxes['importe_isr'] !== "" ? $format_taxes['importe_isr'] : "null";
        $isn_tasa = $format_taxes['isn_tasa'] !== null && $format_taxes['isn_tasa'] !== "" ? $format_taxes['isn_tasa'] : "null";
        $importe_isn = $format_taxes['importe_isn'] !== null && $format_taxes['importe_isn'] !== "" ? $format_taxes['importe_isn'] : "null";
        $cedular_tasa = $format_taxes['cedular_tasa'] !== null && $format_taxes['cedular_tasa'] !== "" ? $format_taxes['cedular_tasa'] : "null";
        $importe_cedular = $format_taxes['importe_cedular'] !== null && $format_taxes['importe_cedular'] !== "" ?$format_taxes['importe_cedular'] : "null";
        $cinco_al_millar = $format_taxes['cinco_al_millar'] !== null && $format_taxes['cinco_al_millar'] !== "" ? $format_taxes['cinco_al_millar'] : "null";
        $importe_5_al_millar = $format_taxes['importe_5_al_millar'] !== null && $format_taxes['importe_5_al_millar'] !== "" ? $format_taxes['importe_5_al_millar'] : "null";
        $funcion_publica_tasa = $format_taxes['funcion_publica_tasa'] !== null && $format_taxes['funcion_publica_tasa'] !== "" ? $format_taxes['funcion_publica_tasa'] : "null";
        $importe_funcion_publica = $format_taxes['importe_funcion_publica'] !== null && $format_taxes['importe_funcion_publica'] !== "" ? $format_taxes['importe_funcion_publica'] : "null";
        $ieps_retenido_tasa = $format_taxes['ieps_retenido_tasa'] !== null && $format_taxes['ieps_retenido_tasa'] !== "" ? $format_taxes['ieps_retenido_tasa'] : "null";
        $importe_ieps_retenido = $format_taxes['importe_ieps_retenido'] !== null && $format_taxes['importe_ieps_retenido'] !== "" ? $format_taxes['importe_ieps_retenido'] : "null";
        $ieps_exento = $format_taxes['ieps_exento'] !== null && $format_taxes['ieps_exento'] !== "" ? $format_taxes['ieps_exento'] : "null";
        $ieps_retenido_monto_fijo = $format_taxes['ieps_retenido_monto_fijo'] !== null && $format_taxes['ieps_retenido_monto_fijo'] !== "" ?$format_taxes['ieps_retenido_monto_fijo'] : "null";
        $importe_ieps_retenido_monto_fijo = $format_taxes['importe_ieps_retenido_monto_fijo'] !== null && $format_taxes['importe_ieps_retenido_monto_fijo'] !== "" ? $format_taxes['importe_ieps_retenido_monto_fijo'] : "null";
        
        $query = sprintf("update 
                            productos_ticket_temp 
                            set 
                            cantidad = {$data->cantidad}, 
                            descuento = {$data->descuento},
                            precio_unitario = {$data->precio_unitario},
                            subtotal = {$data->subtotal},
                            iva_tasa = {$tasa_iva},
                            importe_iva = {$importe_iva},
                            ieps_tasa = {$ieps_tasa},
                            importe_ieps = {$importe_ieps},
                            ieps_monto_fijo = {$ieps_monto_fijo},
                            importe_ieps_monto_fijo = {$importe_ieps_monto_fijo},
                            ish_tasa = {$ish_tasa},
                            importe_ish = {$importe_ish},
                            iva_exento = {$iva_exento},
                            iva_retenido_tasa = {$iva_retenido_tasa},
                            importe_iva_retenido = {$importe_iva_retenido},
                            isr_tasa = {$isr_tasa},
                            importe_isr = {$importe_isr},
                            isn_tasa = {$isn_tasa},
                            importe_isn = {$importe_isn},
                            cedular_tasa = {$cedular_tasa},
                            importe_cedular = {$importe_cedular},
                            cinco_al_millar = {$cinco_al_millar},
                            importe_5_al_millar = {$importe_5_al_millar},
                            funcion_publica_tasa = {$funcion_publica_tasa},
                            importe_funcion_publica = {$importe_funcion_publica},
                            ieps_retenido_tasa = {$ieps_retenido_tasa},
                            importe_ieps_retenido = {$importe_ieps_retenido},
                            ieps_exento = {$ieps_exento},
                            ieps_retenido_monto_fijo = {$ieps_retenido_monto_fijo},
                            importe_ieps_retenido_monto_fijo = {$importe_ieps_retenido_monto_fijo},
                            total = {$total}
                            where
                            producto_id = {$data->producto_id} and
                            caja_id = {$data->caja_id} and
                            usuario_id = {$_SESSION['PKUsuario']}
                        ");
        $stmt = $db->prepare($query);
        return $stmt->execute();
      
    }

    function updateCurrentBalance($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $query = null;
        $data = json_decode($value);

        if((int)$data->tipo_movimiento === 1)
        {
            $query = sprintf("update cuentas_punto_venta set saldo_actual = (saldo_actual - {$data->total}) where id = $data->caja_id and saldo_actual >= {$data->total}");
        } else if((int)$data->tipo_movimiento === 2)
        {
            $query = sprintf("update cuentas_punto_venta set saldo_actual = (saldo_actual + {$data->total}) where id = $data->caja_id");
        }

        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function updateStatusSales($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);

        $query = sprintf("update 
                            detalle_cuenta_punto_venta 
                            set 
                            estatus = 2 
                            where 
                            estatus = 1 and 
                            cuenta_punto_venta_id = {$data->caja_id} 
                            and empresa_id = {$_SESSION['IDEmpresa']}");
        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function updateStatusTicketCashRegisterCut($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);

        $query = sprintf("update 
                            ticket_punto_venta t
                            left join 
                            detalle_cuenta_punto_venta dc 
                            on 
                            t.id = dc.ticket_id
                            set 
                            t.estatus = 4 
                            where
                            dc.estatus = 2 and 
                            t.estatus = 1 and
                            dc.cuenta_punto_venta_id = {$data->caja_id} and 
                            dc.empresa_id = {$_SESSION['IDEmpresa']}");
        $stmt = $db->prepare($query);

        return $stmt->execute();
    }

    function updateProductStock($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("update existencia_por_productos set existencia = :stock where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":stock",(double)$value);
        $stmt->bindValue(":id",$value1);
        return $stmt->execute();
        // $con = new conection();
        // $db = $con->getDb();
        // $get_data = new get_data();

        // $arr = $get_data->getIdProductInTableTemp($value);
        // $query = "";
        // foreach($arr as $r)
        // {

        //     $query = sprintf("update 
        //                         existencia_por_productos e
        //                         set 
        //                         e.existencia = (e.existencia - {$r->cantidad})
        //                         where 
        //                         e.producto_id = {$r->id} and 
        //                         e.sucursal_id = {$r->sucursal_id}
        //                         order by e.id asc
        //                         limit 1");
        //     $stmt = $db->prepare($query);
        //     $stmt->execute();
            // switch ((int)$r->fecha_caducidad) {
            //   case 1:
            //     $query = sprintf("update 
            //                       existencia_por_productos e
            //                     set 
            //                       e.existencia = (e.existencia - {$r->cantidad})
            //                     where 
            //                       e.producto_id = {$r->id} and 
            //                       e.sucursal_id = {$r->sucursal_id} and 
            //                       e.caducidad < now()
            //                     order by e.caducidad asc
            //                     limit 1");
            //     $stmt = $db->prepare($query);
            //     $stmt->execute();
            //     break;
            
            //   case 0:
            //     $query = sprintf("update 
            //                       existencia_por_productos e
            //                     set 
            //                       e.existencia = (e.existencia - {$r->cantidad})
            //                     where 
            //                       e.producto_id = {$r->id} and 
            //                       e.sucursal_id = {$r->sucursal_id} 
            //                     order by e.id asc
            //                     limit 1");
            //     $stmt = $db->prepare($query);
            //     $stmt->execute();
            //     break;
            // }

        // }


    }

    function updateStockCancel($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $arr = $get_data->getDataTicketCancel($value,"","");
        

        if(count($arr) > 0){
            foreach($arr as $r)
            {
                $id = $get_data->getStockIdUpdateCancel($r->id,$r->sucursal_id)[0]->id;
            //   if($r->chkLote === 1){
                $query = sprintf("update 
                                    existencia_por_productos 
                                set 
                                    existencia = (existencia + :cantidad)
                                where 
                                    id = :id");
            //   } else if($r->chkSerie === 1){
            //     $query = sprintf("update 
            //                   existencia_por_productos 
            //                 set 
            //                   existencia = (existencia + {$r->cantidad})
            //                 where 
            //                   producto_id = {$r->id} and
            //                   numero_serie = '{$r->serie}' and
            //                   sucursal_id = {$r->sucursal_id} and
            //                   existencia > 0");
            //   }

            $stmt = $db->prepare($query);
            $stmt->bindValue(":cantidad",$r->cantidad);
            $stmt->bindValue(":id",$id);
            
            $stmt->execute();
            }
        }
      
    }

    function updateCancelCurrencyTicketData($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        
        $total = (double)$get_data->getTotalTicket($value)[0]->total;
        $query = sprintf("update cuentas_punto_venta set saldo_actual = (saldo_actual - :total) where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":total",$total);
        $stmt->bindValue(":id",$value1);
        return $stmt->execute();
    }

    function updateCancelTicketData($value,$value1,$value2)
    {
        $update_data = new update_data();
        $update_data->updateStatusTicket($value,$value1,1);
        $update_data->updateStatusCancelSale($value);
        return $update_data->updateCancelCurrencyTicketData($value,$value2);
    }

    function updateStatusCancelSale($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("update 
                        ventas_directas v
                    inner join 
                        relacion_tickets_ventas rtv ON v.PKVentaDirecta = rtv.venta_id
                    set 
                        v.estatus_cuentaCobrar = 1
                    where rtv.ticket_id = :id ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        return $stmt->execute();
    }

    function updateStatusTicket($value,$value1,$value2)
    {
        $con = new conection();
        $db = $con->getDb();
        $update_data = new update_data();

        if((int)$value1 === 2){
            $query = sprintf("
            UPDATE 
            ticket_punto_venta
            SET
            estatus = {$value1},
            fecha_cancelacion = NOW()
            WHERE
            id = {$value}
        ");
        } else {
            $query = sprintf("
            UPDATE 
                ticket_punto_venta
            SET
                estatus = {$value1}
            WHERE
                id = {$value}
        ");
        }
        
        $stmt = $db->prepare($query);

        $ban = $stmt->execute();

        if($ban && $value2 !== "" && $value2 !== 0){
            $update_data->updateStockCancel($value);
        }
        return $ban;
    }

    function updatePrinter($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);

        $query = sprintf("update cuentas_punto_venta set nombre_impresora = '{$data->printerName}' where id = {$data->cash_register_id}");
        $stmt = $db->prepare($query);

        return $stmt->execute();
    }

    function updateSaleEncryptId($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("update ventas_directas set id_encriptado = :id_encrypt where PKVentaDirecta = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id_encrypt",MD5($value));
        $stmt->bindValue(":id",$value);
        $stmt->execute();
    }

    function updateStatusSalesByTicket($id)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
            update 
                ventas_directas v
            inner join 
                relacion_tickets_ventas rtv ON v.PKVentaDirecta = rtv.venta_id
            set 
                v.estatus_factura_id = 1, 
                v.FKEstatusVenta = 2
            where rtv.ticket_id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
    }
  }

  class save_data
  {
    function saveCompanyBankAccount($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $id = "";

        $data = json_decode($value);
        
        try {
            $db->beginTransaction();

            $query = sprintf("insert into cuentas_bancarias_empresa (tipo_cuenta,Nombre,estatus,empresa_id) values (5,'{$data->name}',1,{$_SESSION['IDEmpresa']})");
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            $id = $db->lastInsertId();

            $db->commit();
            
            return $id;
        } catch (PDOException $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveAccountCashRegisterAccountDataGeneral($value,$id)
    {
        $con = new conection();
        $db = $con->getDb();
        $response = "";
        $data = json_decode($value);

        try{
            $db->beginTransaction();
            $query = sprintf("insert into cuentas_punto_venta (descripcion,saldo_inicial,usuario_created_at,cuenta_empresa_id,moneda_id,sucursal_id,password_admin) values ('{$data->description}',{$data->initial_balance},{$_SESSION['PKUsuario']},{$id},{$data->type_money},{$data->branch_office},'{$data->pass_admin}')");
            $stmt = $db->prepare($query);
            $response = $stmt->execute();

            $db->commit();
            return $response;
        } catch (PDOException $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveCashRegisterAccount($value)
    {
        $save_data = new save_data();
        $id = $save_data->saveCompanyBankAccount($value);
        if(isset($id))
            return $save_data->saveAccountCashRegisterAccountDataGeneral($value,$id);

    }

    function saveProductCategory($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $id = "";
        if((int)$get_data->getIfCategoryExist($value) === 0){
          try {

              $db->beginTransaction();

              $query = sprintf("insert into categorias_productos (CategoriaProductos,estatus,empresa_id) values (:cat,:status,:company)");
              $stmt = $db->prepare($query);
              $stmt->execute([":cat"=>$value,":status"=>1,":company"=>$_SESSION['IDEmpresa']]);
              $id = $db->lastInsertId();
              
              $db->commit();

              return ['id'=>$id];
          } catch (PDOException $e) {
              $db->rollBack();
              return $e->getMessage();
          }
        } else {
            return ['message'=>'Ya hay una categoria registrada con el mismo nombre'];
          }
    }

    function saveProductTradeMark($value)
    {
      $con = new conection();
      $db = $con->getDb();
      $get_data = new get_data();
      $id = "";
      
      if((int)$get_data->getIfMarkExist($value) === 0){
        try {
            $db->beginTransaction();

            $query = sprintf("insert into marcas_productos (MarcaProducto,empresa_id,estatus) values (:marca,:empresa,:status)");
            $stmt = $db->prepare($query);
            $stmt->execute([":marca"=>$value,":empresa"=>$_SESSION['IDEmpresa'],":status"=>1]);
            $id = $db->lastInsertId();
            
            $db->commit();
            
            return ['id'=>$id];
            
        } catch (PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
      } else {
        return ['message'=>'Ya hay una marca registrada con el mismo nombre'];
      }
    }

    function saveGeneralDataProduct($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $id = "";
        $data = json_decode($value);

        try{
            $db->beginTransaction();

            $query = sprintf("insert into productos 
                            (
                                ClaveInterna,
                                CodigoBarras,
                                Nombre,
                                Descripcion,
                                FKCategoriaProducto,
                                FKTipoProducto,
                                FKMarcaProducto,
                                usuario_creacion_id,
                                usuario_edicion_id,
                                empresa_id,
                                created_at,
                                updated_at,
                                estatus,
                                serie,
                                lote,
                                fecha_caducidad,
                                precio_compra,
                                precio_compra_neto,
                                utilidad1,
                                utilidad2,
                                utilidad3,
                                utilidad4,
                                precio_venta1,
                                precio_venta2,
                                precio_venta3,
                                precio_venta4,
                                precio_venta_neto1,
                                precio_venta_neto2,
                                precio_venta_neto3,
                                precio_venta_neto4,
                                Imagen,
                                receta
                            ) values 
                            (
                                :clave,
                                :codigo_barras,
                                :nombre,
                                :descripcion,
                                :categoria,
                                :tipo,
                                :marca,
                                :usuario_creacion_id,
                                :usuario_edicion_id,
                                :empresa_id,
                                :created_at,
                                :updated_at,
                                :estatus,
                                :serie,
                                :lote,
                                :fecha_caducidad,
                                :precio_compra,
                                :precio_compra_neto,
                                :utilidad1,
                                :utilidad2,
                                :utilidad3,
                                :utilidad4,
                                :precio_venta1,
                                :precio_venta2,
                                :precio_venta3,
                                :precio_venta4,
                                :precio_venta_neto1,
                                :precio_venta_neto2,
                                :precio_venta_neto3,
                                :precio_venta_neto4,
                                :imagen,
                                :receta
                            )");
            $stmt = $db->prepare($query);
            $stmt->execute([
            ":clave" => $data[0]->clave,
            ":codigo_barras" => $data[0]->codigo_barras,
            ":nombre" => $data[0]->nombre,
            ":descripcion" => $data[0]->descripcion,
            ":categoria" => $data[0]->categoria,
            ":tipo" => $data[0]->tipo_producto,
            ":marca" => $data[0]->marca,
            ":usuario_creacion_id" => $_SESSION['PKUsuario'],
            ":usuario_edicion_id" => $_SESSION['PKUsuario'],
            ":empresa_id" => $_SESSION['IDEmpresa'],
            ":created_at" => date("Y-m-d H:i:s"),
            ":updated_at" => date("Y-m-d H:i:s"),
            ":estatus" => 1,
            ":serie" => $data[0]->chkSerie,
            ":lote" => $data[0]->chkLote,
            ":fecha_caducidad" => $data[0]->chk_fecha_caducidad,
            ":precio_compra" => $data[0]->precio_compra,
            ":precio_compra_neto" => $data[0]->precio_compra_neto,
            ":utilidad1" => $data[0]->utilidad1,
            ":utilidad2" => $data[0]->utilidad2,
            ":utilidad3" => $data[0]->utilidad3,
            ":utilidad4" => $data[0]->utilidad4,
            ":precio_venta1" => $data[0]->precio_venta_neto1,
            ":precio_venta2" => $data[0]->precio_venta_neto2,
            ":precio_venta3" => $data[0]->precio_venta_neto3,
            ":precio_venta4" => $data[0]->precio_venta_neto4,
            ":precio_venta_neto1" => $data[0]->precio_venta_neto1,
            ":precio_venta_neto2" => $data[0]->precio_venta_neto2,
            ":precio_venta_neto3" => $data[0]->precio_venta_neto3,
            ":precio_venta_neto4" => $data[0]->precio_venta_neto4,
            ":imagen" => "agregar.svg",
            ":receta" => $data[0]->chkReceta
            ]);
            $id = $db->lastInsertId();
            
            $db->commit();

            return $id;
        } catch(PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveFiscalDataProduct($value,$id)
    {
        $con = new conection();
        $db = $con->getDb();
        $id_info = "";
        $data = json_decode($value);
        
        try{
            $db->beginTransaction();

            $query = sprintf("insert into info_fiscal_productos 
                            (
                                FKProducto,
                                FKClaveSAT,
                                FKClaveSATUnidad
                            ) values 
                            (
                                :producto_id,
                                :clave_sat,
                                :clave_unidad
                            )
                            ");
            $stmt = $db->prepare($query);
            $stmt->execute([
            ":producto_id" => (int)$id,
            ":clave_sat" => $data[0]->clave_sat,
            ":clave_unidad" => $data[0]->clave_unidad
            ]);
            $id_info = $db->lastInsertId();

            $db->commit();
            return $id_info;
        } catch(PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveTaxProduct($value,$id)
    {
        $con = new conection();
        $db = $con->getDb();

        $data = json_decode($value);
        
        $values_sql = "";

        foreach($data as $r => $val){
            $values_sql .= "({$id},{$val->tax_id},{$val->rate}),";
        }

        $values_sql = rtrim($values_sql, ',');

        try {
            $db->beginTransaction();

            $query = sprintf("insert into impuestos_productos 
                            (
                                FKInfoFiscalProducto,
                                FKImpuesto,
                                Tasa
                            ) values
                            ".$values_sql."
                            ");
            $stmt = $db->prepare($query);
            $stmt->execute();

            $db->commit();

        } catch (PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveStockDataProducto($value,$id,$clave)
    {
        $save_data = new save_data();
        $data = json_decode($value);
        $ban = "";

        $stock_minimo = $data[0]->stock_minimo;
        $stock_maximo = $data[0]->stock_maximo;
        $punto_reorden = $data[0]->punto_reorden;
        $sucursal = $data[0]->sucursal;
        
        if(count($data[0]->stocks_data) > 0){
            $numero_serie = '';
            $numero_lote = '';
            foreach($data[0]->stocks_data as $r)
            {
            $fecha_caducidad = $r->expired_date !== "" && $r->expired_date !== null ? $r->expired_date : "0000-00-00";
            if($data[0]->chkLote === 1){
                $numero_lote = $r->lot;
                $numero_serie = '';
            } else if($data[0]->chkSerie === 1){
                $numero_lote = '';
                $numero_serie = $r->lot;
            }
            $stock = $data[0]->stocks_data[0]->quantity;
            $ban = $save_data->saveStocksProducts($id,$clave,$stock_minimo,$stock_maximo,$punto_reorden,$stock,$sucursal,$numero_lote, $numero_serie,$fecha_caducidad);
            }

        } else {
            $fecha_caducidad = "0000-00-00";
            $stock = 0;
            $numero_serie = '';
            $numero_lote = '';
            $ban = $save_data->saveStocksProducts($id,$clave,$stock_minimo,$stock_maximo,$punto_reorden,$stock,$sucursal,$numero_lote,$numero_serie,$fecha_caducidad);
        }
        return $ban;
    }

    function saveStocksProducts(
      $producto_id,
      $clave,
      $stock_minimo,
      $stock_maximo,
      $punto_reorden,
      $stock,
      $sucursal,
      $numero_lote,
      $numero_serie,
      $fecha_caducidad
      )
    {
        $con = new conection();
        $db = $con->getDb();
        
        try {
            $db->beginTransaction();
        
            $query = sprintf("insert into existencia_por_productos 
                                (
                                existencia_minima,
                                existencia_maxima,
                                punto_reorden,
                                numero_lote,
                                numero_serie,
                                caducidad,
                                existencia,
                                sucursal_id,
                                producto_id,
                                clave_producto,
                                apartado_produccion
                                ) values 
                                (
                                :existencia_minima,
                                :existencia_maxima,
                                :punto_reorden,
                                :numero_lote,
                                :numero_serie,
                                :caducidad,
                                :existencia,
                                :sucursal_id,
                                :producto_id,
                                :clave_producto,
                                :apartado_produccion
                                )");
            $stmt = $db->prepare($query);
            $stmt->execute([
            ":existencia_minima" => $stock_minimo,
            ":existencia_maxima" => $stock_maximo,
            ":punto_reorden" => $punto_reorden,
            ":numero_lote" => $numero_lote,
            ":numero_serie" => $numero_serie,
            ":caducidad" => $fecha_caducidad,
            ":existencia" => $stock,
            ":sucursal_id" => $sucursal,
            ":producto_id" => $producto_id,
            ":clave_producto" => $clave,
            ":apartado_produccion" => 0
            ]);
            return $db->commit();

        } catch (PDOException $e)  {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveSellCost($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        $data = json_decode($value1);
        try {
            $db->beginTransaction();
            
            $query = sprintf("insert into costo_venta_producto (CostoGeneral,FKTipoMoneda,FKProducto,CostoCompra,FKTipoMonedaCompra,CostoFabricacion,FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values (:CostoGeneral,:FKTipoMoneda,:FKProducto,:CostoCompra,:FKTipoMonedaCompra,:CostoFabricacion,:FKTipoMonedaFabricacion, 0, 100)");

            $stmt = $db->prepare($query);
           
            $stmt->bindValue('CostoGeneral',$data[0]->precio_venta1);
            $stmt->bindValue('FKTipoMoneda',100);
            $stmt->bindValue('FKProducto',$value);
            $stmt->bindValue('CostoCompra', $data[0]->precio_compra);
            $stmt->bindValue('FKTipoMonedaCompra',100);
            $stmt->bindValue('CostoFabricacion',0);
            $stmt->bindValue('FKTipoMonedaFabricacion',100);

            $stmt->execute();
            return $db->commit();

        } catch (PDOException $e) {
            $db->rollBack();
            return "error: ". $e->getMessage();
        }
    }

    function saveProduct($value)
    {
        $save_data = new save_data();
        $get_data = new get_data();
        $data = json_decode($value);
        $ban = "";
        $ban1 = "";

        $data_general = $data->data[0]->data_general;
        $data_fiscal = $data->data[2]->fiscal_data;
        $data_tax = $data->data[1]->tax_product;
        $stock_data = $data->data[3]->stock_data;
        $clave = $data_general[0]->clave;
        
        $sucursal = (int)$get_data->getCashRegisterHasInventory($stock_data[0]->sucursal)[0]->activar_inventario;
        $id = $save_data->saveGeneralDataProduct(json_encode($data_general));
        
        if(isset($id)){
            $save_data->saveSellCost($id,json_encode($data_general));
            $id_data_fiscal = $save_data->saveFiscalDataProduct(json_encode($data_fiscal),$id);
            $save_data->saveProductOperations((int)$id);
            if(isset($id_data_fiscal)){
                if(count($data_tax) > 0){
                    $ban = $save_data->saveTaxProduct(json_encode($data_tax),$id_data_fiscal);
                }
            }
            if((int)$sucursal === 1){
                $ban1 = $save_data->saveStockDataProducto(json_encode($stock_data),$id,$clave);
            }
            
        }

        return [
            "message_tax" => $ban,
            "message_stock" => $ban1,
            "id" => $id
        ];
    }

    function saveProductsTblTemp($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $sql_query = "";
        $ban = "";
        $data = $value;

        // $tasa_iva = !empty($data['tasa_iva']) ? $data['tasa_iva'] : 'NULL';
        // $importe_iva = !empty($data['importe_iva']) ? $data['importe_iva'] : 'NULL';
        // $ieps_tasa = !empty($data['ieps_tasa']) ? $data['ieps_tasa'] : 'NULL';
        // $importe_ieps = !empty($data['importe_ieps']) ? $data['importe_ieps'] : 'NULL';
        // $ieps_monto_fijo = !empty($data['ieps_monto_fijo']) ? $data['ieps_monto_fijo'] : 'NULL';
        // $importe_ieps_monto_fijo = !empty($data['importe_ieps_monto_fijo']) ? $data['importe_ieps_monto_fijo'] : 'NULL';
        // $ish_tasa = !empty($data['ish_tasa']) ? $data['ish_tasa'] : 'NULL';
        // $importe_ish = !empty($data['importe_ish']) ? $data['importe_ish'] : 'NULL';
        // $iva_exento = !empty($data['iva_exento']) ? $data['iva_exento'] : 0;
        // $iva_retenido_tasa = !empty($data['iva_retenido_tasa']) ? $data['iva_retenido_tasa'] : 'NULL';
        // $importe_iva_retenido = !empty($data['importe_iva_retenido']) ? $data['importe_iva_retenido'] : 'NULL';
        // $isr_tasa = !empty($data['isr_tasa']) ? $data['isr_tasa'] : 'NULL';
        // $importe_isr = !empty($data['importe_isr']) ? $data['importe_isr'] : 'NULL';
        // $isn_tasa = !empty($data['isn_tasa']) ? $data['isn_tasa'] : 'NULL';
        // $importe_isn = !empty($data['importe_isn']) ? $data['importe_isn'] : 'NULL';
        // $cedular_tasa = !empty($data['cedular_tasa']) ? $data['cedular_tasa'] : 'NULL';
        // $importe_cedular = !empty($data['importe_cedular']) ? $data['importe_cedular'] : 'NULL';
        // $cinco_al_millar = !empty($data['cinco_al_millar']) ? $data['cinco_al_millar'] : 'NULL';
        // $importe_5_al_millar = !empty($data['importe_5_al_millar']) ? $data['importe_5_al_millar'] : 'NULL';
        // $funcion_publica_tasa = !empty($data['funcion_publica_tasa']) ? $data['funcion_publica_tasa'] : 'NULL';
        // $importe_funcion_publica = !empty($data['importe_funcion_publica']) ? $data['importe_funcion_publica'] : 'NULL';
        // $ieps_retenido_tasa = !empty($data['ieps_retenido_tasa']) ? $data['ieps_retenido_tasa'] : 'NULL';
        // $importe_ieps_retenido = !empty($data['importe_ieps_retenido']) ? $data['importe_ieps_retenido'] : 'NULL';
        // $ieps_exento = !empty($data['ieps_exento']) ? $data['ieps_exento'] : 'NULL';
        // $ieps_retenido_monto_fijo = !empty($data['ieps_retenido_monto_fijo']) ? $data['ieps_retenido_monto_fijo'] : 'NULL';
        // $importe_ieps_retenido_monto_fijo = !empty($data['importe_ieps_retenido_monto_fijo']) ? $data['importe_ieps_retenido_monto_fijo'] : 'NULL';
        
        // $sql_query .= "(
        //                 {$data['producto_id']},
        //                 {$data['cantidad']},
        //                 {$data['precio_u']},
        //                 {$data['subtotal']},
        //                 {$tasa_iva},
        //                 {$importe_iva},
        //                 {$ieps_tasa},
        //                 {$importe_ieps},
        //                 {$ieps_monto_fijo},
        //                 {$importe_ieps_monto_fijo},
        //                 {$ish_tasa},
        //                 {$importe_ish},
        //                 {$iva_exento},
        //                 {$iva_retenido_tasa},
        //                 {$importe_iva_retenido},
        //                 {$isr_tasa},
        //                 {$importe_isr},
        //                 {$isn_tasa},
        //                 {$importe_isn},
        //                 {$cedular_tasa},
        //                 {$importe_cedular},
        //                 {$cinco_al_millar},
        //                 {$importe_5_al_millar},
        //                 {$funcion_publica_tasa},
        //                 {$importe_funcion_publica},
        //                 {$ieps_retenido_tasa},
        //                 {$importe_ieps_retenido},
        //                 {$ieps_exento},
        //                 {$ieps_retenido_monto_fijo},
        //                 {$importe_ieps_retenido_monto_fijo},
        //                 {$data['total']},
        //                 {$data['caja_id']},
        //                 {$_SESSION['PKUsuario']}
        //                 )";

        try{
            $db->beginTransaction();
            
            $query = sprintf(
            "insert into productos_ticket_temp (
                producto_id,
                cantidad,
                precio_unitario,
                subtotal,
                iva_tasa,
                importe_iva,
                ieps_tasa,
                importe_ieps,
                ieps_monto_fijo,
                importe_ieps_monto_fijo,
                ish_tasa,
                importe_ish,
                iva_exento,
                iva_retenido_tasa,
                importe_iva_retenido,
                isr_tasa,
                importe_isr,
                isn_tasa,
                importe_isn,
                cedular_tasa,
                importe_cedular,
                cinco_al_millar,
                importe_5_al_millar,
                funcion_publica_tasa,
                importe_funcion_publica,
                ieps_retenido_tasa,
                importe_ieps_retenido,
                ieps_exento,
                ieps_retenido_monto_fijo,
                importe_ieps_retenido_monto_fijo,
                total,
                caja_id,
                usuario_id
            ) values (
                :producto_id,
                :cantidad,
                :precio_unitario,
                :subtotal,
                :tasa_iva,
                :importe_iva,
                :ieps_tasa,
                :importe_ieps,
                :ieps_monto_fijo,
                :importe_ieps_monto_fijo,
                :ish_tasa,
                :importe_ish,
                :iva_exento,
                :iva_retenido_tasa,
                :importe_iva_retenido,
                :isr_tasa,
                :importe_isr,
                :isn_tasa,
                :importe_isn,
                :cedular_tasa,
                :importe_cedular,
                :cinco_al_millar,
                :importe_5_al_millar,
                :funcion_publica_tasa,
                :importe_funcion_publica,
                :ieps_retenido_tasa,
                :importe_ieps_retenido,
                :ieps_exento,
                :ieps_retenido_monto_fijo,
                :importe_ieps_retenido_monto_fijo,
                :total,
                :caja_id,
                :usuario_id
            )");
            
            $stmt = $db->prepare($query);
            
            $stmt->bindValue(":producto_id",$data['producto_id']);
            $stmt->bindValue(":cantidad",$data['cantidad']);
            $stmt->bindValue(":precio_unitario",$data['precio_u']);
            $stmt->bindValue(":subtotal",$data['subtotal']);
            $stmt->bindValue(":tasa_iva",$data['tasa_iva']);
            $stmt->bindValue(":importe_iva",$data['importe_iva']);
            $stmt->bindValue(":ieps_tasa",$data['ieps_tasa']);
            $stmt->bindValue(":importe_ieps",$data['importe_ieps']);
            $stmt->bindValue(":ieps_monto_fijo",$data['ieps_monto_fijo']);
            $stmt->bindValue(":importe_ieps_monto_fijo",$data['importe_ieps_monto_fijo']);
            $stmt->bindValue(":ish_tasa",$data['ish_tasa']);
            $stmt->bindValue(":importe_ish",$data['importe_ish']);
            $stmt->bindValue(":iva_exento",$data['iva_exento']);
            $stmt->bindValue(":iva_retenido_tasa",$data['iva_retenido_tasa']);
            $stmt->bindValue(":importe_iva_retenido",$data['importe_iva_retenido']);
            $stmt->bindValue(":isr_tasa",$data['isr_tasa']);
            $stmt->bindValue(":importe_isr",$data['importe_isr']);
            $stmt->bindValue(":isn_tasa",$data['isn_tasa']);
            $stmt->bindValue(":importe_isn",$data['importe_isn']);
            $stmt->bindValue(":cedular_tasa",$data['cedular_tasa']);
            $stmt->bindValue(":importe_cedular",$data['importe_cedular']);
            $stmt->bindValue(":cinco_al_millar",$data['cinco_al_millar']);
            $stmt->bindValue(":importe_5_al_millar",$data['importe_5_al_millar']);
            $stmt->bindValue(":funcion_publica_tasa",$data['funcion_publica_tasa']);
            $stmt->bindValue(":importe_funcion_publica",$data['importe_funcion_publica']);
            $stmt->bindValue(":ieps_retenido_tasa",$data['ieps_retenido_tasa']);
            $stmt->bindValue(":importe_ieps_retenido",$data['importe_ieps_retenido']);
            $stmt->bindValue(":ieps_exento",$data['ieps_exento']);
            $stmt->bindValue(":ieps_retenido_monto_fijo",$data['ieps_retenido_monto_fijo']);
            $stmt->bindValue(":importe_ieps_retenido_monto_fijo",$data['importe_ieps_retenido_monto_fijo']);
            $stmt->bindValue(":total",$data['total']);
            $stmt->bindValue(":caja_id",$data['caja_id']);
            $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
            $stmt->execute();

            return $db->commit();

        } catch(PDOException $e) {
            $db->rollBack();
            return "error: ".$e->getMessage();
        }
    }

    function saveProductsPedding($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $delete_data = new delete_data();
        $arr = $get_data->getFolioTblTemp($value);

        if(count($arr) > 0){
            $folio = (int)$arr[0]->folio + 1;
        } else {
            $folio = 1;
        }

        $query = sprintf("
        insert into productos_pendientes_ticket (
            folio,
            producto_id,
            cantidad,
            lote,
            serie,
            caducidad,
            precio_unitario,
            subtotal,
            iva_tasa,
            importe_iva,
            ieps_tasa,
            importe_ieps,
            ieps_monto_fijo,
            importe_ieps_monto_fijo,
            ish_tasa,
            importe_ish,
            iva_exento,
            iva_retenido_tasa,
            importe_iva_retenido,
            isr_tasa,
            importe_isr,
            isn_tasa,
            importe_isn,
            cedular_tasa,
            importe_cedular,
            cinco_al_millar,
            importe_5_al_millar,
            funcion_publica_tasa,
            importe_funcion_publica,
            ieps_retenido_tasa,
            importe_ieps_retenido,
            ieps_exento,
            ieps_retenido_monto_fijo,
            importe_ieps_retenido_monto_fijo,
            total,
            caja_id,
            usuario_id
        ) select 
            {$folio},
            producto_id,
            cantidad,
            lote,
            serie,
            caducidad,
            precio_unitario,
            subtotal,
            iva_tasa,
            importe_iva,
            ieps_tasa,
            importe_ieps,
            ieps_monto_fijo,
            importe_ieps_monto_fijo,
            ish_tasa,
            importe_ish,
            iva_exento,
            iva_retenido_tasa,
            importe_iva_retenido,
            isr_tasa,
            importe_isr,
            isn_tasa,
            importe_isn,
            cedular_tasa,
            importe_cedular,
            cinco_al_millar,
            importe_5_al_millar,
            funcion_publica_tasa,
            importe_funcion_publica,
            ieps_retenido_tasa,
            importe_ieps_retenido,
            ieps_exento,
            ieps_retenido_monto_fijo,
            importe_ieps_retenido_monto_fijo,
            total,
            caja_id,
            usuario_id
            from productos_ticket_temp where
            caja_id = {$value} and
            usuario_id = {$_SESSION['PKUsuario']}
        ");
        
        $stmt = $db->prepare($query);

        
        $ban = $stmt->execute();

        $delete_data->deleteAllProductsTableTemp($value);

        return $ban;

    }

    function savePeddingProductData($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        $delete_data = new delete_data();
        $get_data = new get_data();

        $query = sprintf("insert into productos_ticket_temp (
                            producto_id,
                            cantidad,
                            lote,
                            serie,
                            caducidad,
                            precio_unitario,
                            subtotal,
                            iva_tasa,
                            importe_iva,
                            ieps_tasa,
                            importe_ieps,
                            ieps_monto_fijo,
                            importe_ieps_monto_fijo,
                            ish_tasa,
                            importe_ish,
                            iva_exento,
                            iva_retenido_tasa,
                            importe_iva_retenido,
                            isr_tasa,
                            importe_isr,
                            isn_tasa,
                            importe_isn,
                            cedular_tasa,
                            importe_cedular,
                            cinco_al_millar,
                            importe_5_al_millar,
                            funcion_publica_tasa,
                            importe_funcion_publica,
                            ieps_retenido_tasa,
                            importe_ieps_retenido,
                            ieps_exento,
                            ieps_retenido_monto_fijo,
                            importe_ieps_retenido_monto_fijo,
                            total,
                            caja_id,
                            usuario_id
                            ) select
                            producto_id,
                            cantidad,
                            lote,
                            serie,
                            caducidad,
                            precio_unitario,
                            subtotal,
                            iva_tasa,
                            importe_iva,
                            ieps_tasa,
                            importe_ieps,
                            ieps_monto_fijo,
                            importe_ieps_monto_fijo,
                            ish_tasa,
                            importe_ish,
                            iva_exento,
                            iva_retenido_tasa,
                            importe_iva_retenido,
                            isr_tasa,
                            importe_isr,
                            isn_tasa,
                            importe_isn,
                            cedular_tasa,
                            importe_cedular,
                            cinco_al_millar,
                            importe_5_al_millar,
                            funcion_publica_tasa,
                            importe_funcion_publica,
                            ieps_retenido_tasa,
                            importe_ieps_retenido,
                            ieps_exento,
                            ieps_retenido_monto_fijo,
                            importe_ieps_retenido_monto_fijo,
                            total,
                            caja_id,
                            usuario_id 
                            from productos_pendientes_ticket pp
                            where pp.folio = {$value}");
        $stmt = $db->prepare($query);
        $ban = $stmt->execute();

        if($ban){
            $delete_data->deleteAllProductsTablePedding($value,$value1);
            return $get_data->getPeddingProductData($value1);
        } else {
            return $ban;
        }
      
    }

    function saveTicketData($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $id = null;
        
        $data = json_decode($value);
        $get_data = new get_data();
        $folio_arr = $get_data->getFolioTicket($data->caja_id);

        $cliente = 
            $data->cliente_id !== 0 && 
            $data->cliente_id !== "0" &&
            $data->cliente_id !== null &&
            $data->cliente_id !== "" ?
            $data->cliente_id : $get_data->getClientDefault()[0]->id;

        $folio = $folio_arr[0]->folio !== null && $folio_arr[0]->folio !== "" ? (int)$folio_arr[0]->folio + 1 : 1;
        try {
            $db->beginTransaction();
        

            $query = sprintf("
            insert into ticket_punto_venta 
            (
                tipo,
                folio,
                fecha,
                subtotal,
                total,
                tipo_pago,
                usuario_id,
                caja_id,
                empresa_id,
                cliente_id
            ) 
            values
            (
                :tipo_doc,
                :folio,
                now(),
                :subtotal,
                :total,
                :tipo_pago,
                :usuario_id,
                :caja_id,
                :empresa_id,
                :cliente
            )
            ");
            
            $stmt = $db->prepare($query);
            $stmt->bindValue(":tipo_doc",$data->tipo_documento);
            $stmt->bindValue(":folio",$folio);
            $stmt->bindValue(":subtotal",$data->subtotal);
            $stmt->bindValue(":total",$data->total);
            $stmt->bindValue(":tipo_pago",$data->tipo_pago);
            $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
            $stmt->bindValue(":caja_id",$data->caja_id);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":cliente",$cliente);
            $stmt->execute();

            $id = $db->lastInsertId();
            
            $db->commit();

            return $id;

        } catch (PDOException $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveTicketDetails($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        
        $query = sprintf(
            "
            insert into detalle_ticket_punto_venta
            (
                producto_id,
                cantidad,
                lote,
                serie,
                caducidad,
                precio_unitario,
                subtotal,
                descuento,
                iva_tasa,
                importe_iva,
                ieps_tasa,
                importe_ieps,
                ieps_monto_fijo,
                importe_ieps_monto_fijo,
                ish_tasa,
                importe_ish,
                iva_exento,
                iva_retenido_tasa,
                importe_iva_retenido,
                isr_tasa,
                importe_isr,
                isn_tasa,
                importe_isn,
                cedular_tasa,
                importe_cedular,
                cinco_al_millar,
                importe_5_al_millar,
                funcion_publica_tasa,
                importe_funcion_publica,
                ieps_retenido_tasa,
                importe_ieps_retenido,
                ieps_exento,
                ieps_retenido_monto_fijo,
                importe_ieps_retenido_monto_fijo,
                total,
                caja_id,
                usuario_id,
                ticket_id
            )
            select 
                producto_id,
                cantidad,
                lote,
                serie,
                caducidad,
                precio_unitario,
                subtotal,
                descuento,
                iva_tasa,
                importe_iva,
                ieps_tasa,
                importe_ieps,
                ieps_monto_fijo,
                importe_ieps_monto_fijo,
                ish_tasa,
                importe_ish,
                iva_exento,
                iva_retenido_tasa,
                importe_iva_retenido,
                isr_tasa,
                importe_isr,
                isn_tasa,
                importe_isn,
                cedular_tasa,
                importe_cedular,
                cinco_al_millar,
                importe_5_al_millar,
                funcion_publica_tasa,
                importe_funcion_publica,
                ieps_retenido_tasa,
                importe_ieps_retenido,
                ieps_exento,
                ieps_retenido_monto_fijo,
                importe_ieps_retenido_monto_fijo,
                total,
                caja_id,
                usuario_id,
                {$value}
            from productos_ticket_temp
            where
                caja_id = :caja_id and
                usuario_id = :usuario_id
            "
        );
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(":caja_id",$value1);
        $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
        return $stmt->execute();
    }

    function saveAccountMovementData($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        $data = json_decode($value);
        $comentario = $data->comentario !== null && $data->comentario !== "" ? $data->comentario : "NULL";
        $id = $value1 !== null && $value1 !== "" ? $value1 : 'null';
        $query = sprintf("
            insert into detalle_cuenta_punto_venta
            (
                fecha,
                tipo_movimiento,
                saldo,
                tipo_venta,
                estatus,
                cuenta_punto_venta_id,
                usuario_id,
                comentario,
                tipo_pago,
                empresa_id,
                ticket_id
            ) 
            values 
            (
                now(),
                {$data->tipo_movimiento},
                {$data->total},
                {$data->tipo_documento},
                {$data->estatus},
                {$data->caja_id},
                {$_SESSION['PKUsuario']},
                '{$comentario}',
                {$data->tipo_pago},
                {$_SESSION['IDEmpresa']},
                {$id})
        ");
        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function saveTicketInvoice($value,$value1,$value2,$value3)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();

        $data = json_decode($value2);
        $data1 = json_decode($value3);

        $cliente = 
            $data->cliente_id !== 0 && 
            $data->cliente_id !== "0" &&
            $data->cliente_id !== null &&
            $data->cliente_id !== "" ?
            $data->cliente_id : $get_data->getClientDefault()[0]->id;

        $mp = $data1->paidMethod === "PUE" ? 1 : 3;

        $query = sprintf("
            insert into facturacion 
            (
            id_api,
            serie,
            folio,
            referencia,
            tipo,
            uuid,
            fecha_timbrado,
            cliente_id,
            usuario_timbro_id,
            estatus,
            estatus_old,
            total_facturado,
            empresa_id,
            forma_pago_id,
            metodo_pago,
            uso_cfdi_id,
            moneda_id,
            version_factura,
            prefactura
            ) values ( 
            '{$value1->id}',
            '{$value1->series}',
            '{$value1->folio_number}',
            '{$value}',
            '5',
            '{$value1->uuid}',
            NOW(),
            '{$cliente}',
            '{$_SESSION['PKUsuario']}',
            '3',
            '3',
            '{$value1->total}',
            '{$_SESSION['IDEmpresa']}',
            '{$data1->paidType}',
            '{$mp}',
            '{$data1->cfdiUse}',
            '{$data1->currency}',
            '4.0',
            '0'
            )
        ");

        $stmt = $db->prepare($query);
        $stmt->execute();

        return $db->lastInsertId();

    }

    function saveGeneralInvoice($value,$value1,$value2,$value3)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();

        $data1 = json_decode($value);
        
        $ticket_id = $get_data->getIdTicketGeneralInvoice($value2,$value3);
        $cliente = $get_data->getClientDefault()[0]->id;

        $mp = $data1->paidMethod === "PUE" ? 1 : 3;

        $query = sprintf("
            insert into facturacion 
            (
            id_api,
            serie,
            folio,
            referencia,
            tipo,
            uuid,
            fecha_timbrado,
            cliente_id,
            usuario_timbro_id,
            estatus,
            estatus_old,
            total_facturado,
            empresa_id,
            forma_pago_id,
            metodo_pago,
            uso_cfdi_id,
            moneda_id,
            version_factura,
            prefactura
            ) values ( 
            '{$value1->id}',
            '{$value1->series}',
            '{$value1->folio_number}',
            '{$ticket_id}',
            '5',
            '{$value1->uuid}',
            NOW(),
            '{$cliente}',
            '{$_SESSION['PKUsuario']}',
            '3',
            '3',
            '{$value1->total}',
            '{$_SESSION['IDEmpresa']}',
            '{$data1->paidType}',
            '{$mp}',
            '{$data1->cfdiUse}',
            '{$data1->currency}',
            '4.0',
            '0'
            )
        ");

        $stmt = $db->prepare($query);
        $stmt->execute();

        return $db->lastInsertId();

    }

    function saveDetailsGeneralInvoice($id,$value,$value1)
    {
      
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $product_default = $get_data->getProductoDefault()[0]->id;
        $clave_sat = $get_data->getProductoDefault()[0]->clave_sat;
        $uni_sat = $get_data->getProductoDefault()[0]->uni_sat;
        $data = $get_data->getFormatTaxGeneralInvoice("",$value,$value1);
        $text = "";
        
        foreach($data as $r)
        {
            $iva_tasa = $r['iva_tasa'] !== null && $r['iva_tasa'] !==  '' && $r['iva_tasa'] > 0 ? $r['iva_tasa'] : 'null';
            $importe_iva = $r['importe_iva'] !== null && $r['importe_iva'] !== '' && $r['importe_iva'] > 0 ? $r['importe_iva'] : 'null';
            $ieps_tasa = $r['ieps_tasa'] !== null &&  $r['ieps_tasa'] !== '' &&  $r['ieps_tasa'] > 0 ? $r['ieps_tasa'] : 'null';
            $importe_ieps = $r['importe_ieps'] !== null && $r['importe_ieps'] !== '' && $r['importe_ieps'] > 0 ? $r['importe_ieps'] : 'null';
            $ieps_monto_fijo = $r['ieps_monto_fijo'] !== null && $r['ieps_monto_fijo'] !== '' && $r['ieps_monto_fijo'] >0 ? $r['ieps_monto_fijo'] : 'null';
            $ish_tasa = $r['ish_tasa'] !== null && $r['ish_tasa'] !== '' && $r['ish_tasa'] > 0 ? $r['ish_tasa'] : 'null';
            $importe_ish = $r['importe_ish'] !== null && $r['importe_ish'] !== '' && (double)$r['importe_ish'] > 0 ? $r['importe_ish'] : 'null';
            $iva_retenido_tasa = $r['iva_retenido_tasa'] !== null && $r['iva_retenido_tasa'] !== '' && $r['iva_retenido_tasa'] > 0 ? $r['iva_retenido_tasa'] : 'null';
            $importe_iva_retenido = $r['importe_iva_retenido'] !== null && $r['importe_iva_retenido'] !== '' && $r['importe_iva_retenido'] > 0 ? $r['importe_iva_retenido'] : 'null';
            $isr_tasa = $r['isr_tasa'] !== null && $r['isr_tasa'] !== '' && $r['isr_tasa'] > 0 ? $r['isr_tasa'] : 'null';
            $importe_isr = $r['importe_isr'] !== null && $r['importe_isr'] !== '' && $r['importe_isr'] > 0 ? $r['importe_isr'] : 'null';
            $isn_tasa = $r['isn_tasa'] !== null && $r['isn_tasa'] !== '' && $r['isn_tasa'] > 0 ? $r['isn_tasa'] : 'null';
            $importe_isn = $r['importe_isn'] !== null && $r['importe_isn'] !== '' && $r['importe_isn'] > 0 ? $r['importe_isn'] : 'null';
            $cedular_tasa = $r['cedular_tasa'] !== null && $r['cedular_tasa'] !== '' && $r['cedular_tasa'] > 0 ? $r['cedular_tasa'] : 'null';
            $importe_cedular = $r['importe_cedular'] !== null && $r['importe_cedular'] !== '' && $r['importe_cedular'] > 0 ? $r['importe_cedular'] : 'null';
            $cinco_al_millar = $r['cinco_al_millar'] !== null && $r['cinco_al_millar'] !== '' && $r['cinco_al_millar'] > 0 ? $r['cinco_al_millar'] : 'null';
            $importe_5_al_millar = $r['importe_5_al_millar'] !== null && $r['importe_5_al_millar'] !== '' && $r['importe_5_al_millar'] > 0 ? $r['importe_5_al_millar'] : 'null';
            $funcion_publica_tasa = $r['funcion_publica_tasa'] !== null && $r['funcion_publica_tasa'] !== '' && $r['funcion_publica_tasa'] > 0 ? $r['funcion_publica_tasa'] : 'null';
            $importe_funcion_publica = $r['importe_funcion_publica'] !== null && $r['importe_funcion_publica'] !== '' && $r['importe_funcion_publica'] > 0 ? $r['importe_funcion_publica'] : 'null';
            $ieps_retenido_tasa = $r['ieps_retenido_tasa'] !== null && $r['ieps_retenido_tasa'] !== '' && $r['ieps_retenido_tasa'] > 0 ? $r['ieps_retenido_tasa'] : 'null';
            $importe_ieps_retenido = $r['importe_ieps_retenido'] !== null && $r['importe_ieps_retenido'] !== '' && $r['importe_ieps_retenido'] > 0 ? $r['importe_ieps_retenido'] : 'null';
            $ieps_retenido_monto_fijo = $r['ieps_retenido_monto_fijo'] !== null && $r['ieps_retenido_monto_fijo'] !== '' && $r['ieps_retenido_monto_fijo'] > 0 ? $r['ieps_retenido_monto_fijo'] : 'null';

            $text .= "(" .
            "1," .
            "{$r['subtotal_ticket']}," .
            "{$r['subtotal_ticket']}," .
            "{$uni_sat}," .
            "{$clave_sat}," .
            "{$product_default}," .
            "{$iva_tasa}," .
            "{$importe_iva}," .
            "{$ieps_tasa}," .
            "{$importe_ieps}," .
            "{$ieps_monto_fijo}," .
            "{$ish_tasa}," .
            "{$importe_ish}," .
            "{$iva_retenido_tasa}," .
            "{$importe_iva_retenido}," .
            "{$isr_tasa}," .
            "{$importe_isr}," .
            "{$isn_tasa}," .
            "{$importe_isn}," .
            "{$cedular_tasa}," .
            "{$importe_cedular}," .
            "{$cinco_al_millar}," .
            "{$importe_5_al_millar}," .
            "{$funcion_publica_tasa}," .
            "{$importe_funcion_publica}," .
            "{$ieps_retenido_tasa}," .
            "{$importe_ieps_retenido}," .
            "{$ieps_retenido_monto_fijo}," .
            "{$r['total_ticket']}," . 
            "{$id}" .
            "),"
            ;
        }

        $text = substr($text, 0, strlen($text) - 1);

        $query = sprintf("
            insert into detalle_facturacion
            (
            cantidad,
            precio,
            subtotal,
            unidad_medida_id,
            clave_prod_serv_id,
            producto_id,
            iva,
            importe_iva,
            ieps,
            importe_ieps,
            ieps_monto_fijo,
            ish,
            importe_ish,
            iva_retenido,
            importe_iva_retenido,
            isr_retenido,
            importe_isr_retenido,
            isn_local,
            importe_isn_local,
            cedular,
            importe_cedular,
            al_millar,
            importe_al_millar,
            funcion_publica,
            importe_funcion_publica,
            ieps_retenido,
            importe_ieps_retenido,
            ieps_retenido_monto_fijo,
            importe_total,
            factura_id
            ) values {$text}
            
        ");
        
        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function saveAllDataGeneralInvoice($value,$value1,$value2)
    {
        $get_data = new get_data();
        $save_data = new save_data();
        $get_invoice = new get_invoice();
        $update_data = new update_data();
        $ban = "";
        $id = "";
        $ban = $msj = $get_invoice->createGeneralInvoice($value,$value1,$value2);
        if(isset($msj->id)){
            $ban = $id = $save_data->saveGeneralInvoice($value,$msj,$value1,$value2);
            if(isset($id)){
            $ban=$save_data->saveDetailsGeneralInvoice($id,$value1,$value2);
            $arr = json_decode($get_data ->getIdTicketGeneralInvoice($value1,$value2));
            for ($i=0; $i < count($arr); $i++) { 
                $ban=$update_data->updateStatusTicket($arr[$i],5,0);
                $update_data->updateStatusSalesByTicket($arr[$i]);
            }
            $save_data->saveRelationTicketInvoice($arr,$id,2);
            }
        }
        return ["message"=>$ban,"id"=>$id];
    }

    function saveTicketProductsInvoice($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("
            insert into detalle_facturacion
            (
            cantidad,
            precio,
            subtotal,
            importe_descuento,
            numero_lote,
            numero_serie,
            caducidad,
            unidad_medida_id,
            clave_prod_serv_id,
            producto_id,
            iva,
            importe_iva,
            ieps,
            importe_ieps,
            ieps_monto_fijo,
            ish,
            importe_ish,
            iva_retenido,
            importe_iva_retenido,
            isr_retenido,
            importe_isr_retenido,
            isn_local,
            importe_isn_local,
            cedular,
            importe_cedular,
            al_millar,
            importe_al_millar,
            funcion_publica,
            importe_funcion_publica,
            ieps_retenido,
            importe_ieps_retenido,
            ieps_retenido_monto_fijo,
            importe_total,
            factura_id
            ) 
            select 
            cantidad,
            precio_unitario,
            subtotal,
            descuento,
            lote,
            serie,
            caducidad,
            ifp.FKClaveSATUnidad,
            ifp.FKClaveSAT,
            producto_id,
            iva_tasa,
            importe_iva,
            ieps_tasa,
            importe_ieps,
            importe_ieps_monto_fijo,
            ish_tasa,
            importe_ish,
            iva_retenido_tasa,
            importe_iva_retenido,
            isr_tasa,
            importe_isr,
            isn_tasa,
            importe_isn,
            cedular_tasa,
            importe_cedular,
            cinco_al_millar,
            importe_5_al_millar,
            funcion_publica_tasa,
            importe_funcion_publica,
            ieps_retenido_tasa,
            importe_ieps_retenido,
            importe_ieps_retenido_monto_fijo,
            total,
            '{$value}'
            from 
            detalle_ticket_punto_venta dt
            inner join info_fiscal_productos ifp on dt.producto_id = ifp.FKProducto
            where dt.ticket_id = '{$value1}';
        ");

        $stmt = $db->prepare($query);
        return $stmt->execute();

    }

    function saveDataSale($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $folio = $get_data->getFolioSaleTicket();
        
        if($value1 !== 2){
            $query = sprintf("
            insert into 
                inventario_salida_por_sucursales (
                clave,
                numero_lote,
                numero_serie,
                cantidad,
                fecha_salida,
                folio_salida,
                cantidad_entrada,
                cantidad_facturada,
                cantidad_remisionada,
                observaciones,
                tipo_salida,
                orden_pedido_id,
                usuario_creo_id,
                sucursal_id,
                ajuste_id,
                devolucion_id,
                surtidor_id,
                caducidad,
                is_movimiento,
                estatus,
                cambio_lote_id,
                ticket_id
                ) 
                select
                p.ClaveInterna,
                d.lote,
                d.serie,
                d.cantidad,
                t.fecha,
                '{$folio}',
                0,
                0,
                0,
                null,
                5,
                null,
                {$_SESSION['PKUsuario']},
                c.sucursal_id,
                null,
                null,
                {$_SESSION['PKUsuario']},
                d.caducidad,
                null,
                0,
                null,
                {$value}
                from 
                detalle_ticket_punto_venta d
                inner join productos p on d.producto_id = p.PKProducto
                inner join ticket_punto_venta t on d.ticket_id = t.id
                inner join cuentas_punto_venta c on t.caja_id = c.id
                where
                d.ticket_id = {$value}
            ");
        } else {
            $query = sprintf("
            insert into 
                inventario_salida_por_sucursales (
                clave,
                numero_lote,
                numero_serie,
                cantidad,
                fecha_salida,
                folio_salida,
                cantidad_entrada,
                cantidad_facturada,
                cantidad_remisionada,
                observaciones,
                tipo_salida,
                orden_pedido_id,
                usuario_creo_id,
                sucursal_id,
                ajuste_id,
                devolucion_id,
                surtidor_id,
                caducidad,
                is_movimiento,
                estatus,
                cambio_lote_id,
                ticket_id
                ) 
                select
                p.ClaveInterna,
                d.lote,
                d.serie,
                d.cantidad,
                t.fecha,
                '{$folio}',
                0,
                d.cantidad,
                0,
                null,
                5,
                null,
                {$_SESSION['PKUsuario']},
                c.sucursal_id,
                null,
                null,
                {$_SESSION['PKUsuario']},
                d.caducidad,
                null,
                1,
                null,
                {$value}
                from 
                detalle_ticket_punto_venta d
                inner join productos p on d.producto_id = p.PKProducto
                inner join ticket_punto_venta t on d.ticket_id = t.id
                inner join cuentas_punto_venta c on t.caja_id = c.id
                where
                d.ticket_id = {$value}
            ");
        }

        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function saveAllDataTicket($value,$value1,$value2)
    {
        $get_data = new get_data();
        $save_data = new save_data();
        $delete_data = new delete_data();
        $update_data = new update_data();
        $get_invoice = new get_invoice();
        
        $ban = false;
        try{
            $id = $save_data->saveTicketData($value);
            $data = json_decode($value);
            $fac_id = null;
            $estatus_factura = 3;
            if(isset($id))
            {        

                $ban = $save_data->saveTicketDetails($id,$data->caja_id);
                $save_data->saveStocksProductsTicket($get_data->getProductsTicket($id,"",""),$value2);
                
                $save_data->saveAccountMovementData($value,$id);
                $update_data->updateCurrentBalance($value);

                if((int)$data->tipo_documento === 2){
                $arr = [];
                $mensaje = $get_invoice->createInvoice($id,$value1);
                if (isset($mensaje->id)) {
                    $fac_id = $save_data->saveTicketInvoice($id,$mensaje,$value,$value1);
                    $save_data->saveTicketProductsInvoice($fac_id,$id);
                    $update_data->updateStatusTicket($id,3,0);
                    array_push($arr,$id);
                    $save_data->saveRelationTicketInvoice($arr,$fac_id,1);
                    $estatus_factura = 4;
                }
                
                }
                $save_data->saveDataSale($id,(int)$data->tipo_documento);
                $id_venta = $save_data->saveDataVenta($data->total,$data->subtotal,$data->sucursal,$data->cliente_id,$data->cajero,$id,$estatus_factura);
                $save_data->saveRelationTicketSale($id,$id_venta);
                $save_data->saveDetailsSale($id_venta,$id);
                $save_data->saveTaxesSale($id,$id_venta);
                $delete_data->deleteAllProductsTableTemp($data->caja_id);

            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return ['id'=>$id,'status' =>$ban,'fac_id' => $fac_id];
    }

    function saveStocksProductsTicket($value,$value1)
    {
        $get_data = new get_data();
        $update_data = new update_data();

        $resto = 0;
        $suma = 0;
        $arr = [];
        $arr1 = [];
        $lotes = [];
        $cont = 0;
        
        for($i = 0; $i < count($value); $i++){
            $arr = $get_data->getStockProductAll($value[$i]->id,$value1);
            $arr1 = $get_data->getStockProductAll($value[$i]->id,$value1);
            
            (double)$cantidad = (double)$value[$i]->cantidad;
            
            for($j = 0; $j < count($arr); $j++){
            
                if($cantidad > 0){
                
                    if((double)$arr[$j]->existencia >= (double)$cantidad){
                        (double)$resto += (double)$cantidad -(double) $resto;
                        
                        (double)$arr[$j]->existencia = (double)$arr[$j]->existencia - (double)$resto;
                        (double)$cantidad = (double)$cantidad - (double)$resto;
                    } else {
                        (double)$resto += (double)$arr[$j]->existencia - (double)$resto;
                        (double)$cantidad = (double)$cantidad - (double)$arr[$j]->existencia;
                        (double)$arr[$j]->existencia = (double)$arr[$j]->existencia - (double)$resto;
                    
                    }
                }
            
            }
            for ($j=0; $j < count($arr); $j++) { 
                if((double)$arr[$j]->existencia !== (double)$arr1[$j]->existencia)
                {
                    (double)$cantidad = (double)$arr1[$j]->existencia - (double)$arr[$j]->existencia;
                    $lotes[] = ["clave_producto"=>$arr[$j]->clave_producto,"lote"=>$arr[$j]->numero_lote,"cantidad"=>(double)$cantidad];
                }
                    $ban = $update_data->updateProductStock((double)$arr[$j]->existencia,$arr[$j]->id);
            }
        }
        
        
        return ["estatus"=>$ban,"lotes"=>$lotes];
    }

    function saveCashRegisterCut($value)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $data = json_decode($value);
        $total = $get_data->getTotalTicketActive($data->caja_id);
        $id = null;

        try{
            $db->beginTransaction();

            $query = sprintf("
            insert into corte_caja_punto_venta
                (
                fecha,
                efectivo_contado,
                credito_contado,
                transferencia_contado,
                total_contado,
                efectivo_retirado,
                credito_retirado,
                transferencia_retirado,
                total_retirado,
                efectivo_calculado,
                credito_calculado,
                transferencia_calculado,
                total_calculado,
                efectivo_diferencia,
                credito_diferencia,
                transferencia_diferencia,
                total_diferencia,
                total_neto,
                empresa_id,
                cuentas_punto_venta_id
                )
            values
                (now(),
                :efectivo_contado,
                :credito_contado,
                :transferencia_contado,
                :total_contado,
                :efectivo_retirado,
                :credito_retirado,
                :transferencia_retirada,
                :total_retirado,
                :efectivo_calculado,
                :credito_calculado,
                :transferencia_calculado,
                :total_calculado,
                :efectivo_diferencia,
                :credito_diferencia,
                :transferencia_diferencia,
                :total_diferencia,
                :total,
                :empresa_id,
                :caja_id
                )
            ");

            $stmt = $db->prepare($query);
            $stmt->bindValue(":efectivo_contado",$data->efectivo_contado);
            $stmt->bindValue(":credito_contado",$data->credito_contado);
            $stmt->bindValue(":transferencia_contado",$data->transferencia_contado);
            $stmt->bindValue(":total_contado",$data->total_contado);
            $stmt->bindValue(":efectivo_retirado",$data->efectivo_retirado);
            $stmt->bindValue(":credito_retirado",$data->credito_retirado);
            $stmt->bindValue(":transferencia_retirada",$data->transferencia_retirada);
            $stmt->bindValue(":total_retirado",$data->total_retirado);
            $stmt->bindValue(":efectivo_calculado",$data->efectivo_calculado);
            $stmt->bindValue(":credito_calculado",$data->credito_calculado);
            $stmt->bindValue(":transferencia_calculado",$data->transferencia_calculado);
            $stmt->bindValue(":total_calculado",$data->total_calculado);
            $stmt->bindValue(":efectivo_diferencia",$data->efectivo_diferencia);
            $stmt->bindValue(":credito_diferencia",$data->credito_diferencia);
            $stmt->bindValue(":transferencia_diferencia",$data->transferencia_diferencia);
            $stmt->bindValue(":total_diferencia",$data->total_diferencia);
            $stmt->bindValue(":total",$total[0]->total);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":caja_id",$data->caja_id);
            $stmt->execute();

            $id = $db->lastInsertId();
            
            $db->commit();

            return $id;
        } catch(PDOException $e){
            $db->rollBack();
            return $e->getMessage();
        }
      
    }

    function saveDetailsCashRegisterCut($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();
        
        try{
            $db->beginTransaction();

            $query = sprintf("
            insert into detalle_corte_caja_punto_venta
                (
                    detalle_cuenta_punto_venta_id,
                corte_caja_punto_venta_id
                )
            select
                dcpv.id,
                :corte_id
            from detalle_cuenta_punto_venta dcpv
            where dcpv.estatus = 1 and dcpv.cuenta_punto_venta_id = :caja_id
            ");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":corte_id",$value);
            $stmt->bindValue(":caja_id",$value1);
            $stmt->execute();

            return $db->commit();

        } catch(PDOException $e){
            $db->rollBack();
            return $e->getMessage();
        }
    }

    function saveAllDataCashRegisterCut($value)
    {
        $save_data = new save_data();
        $update_data = new update_data();
        $data = json_decode($value);
        $ban = false;

        
        $id = $save_data->saveCashRegisterCut($value);
        if($id){
            $ban = $save_data->saveDetailsCashRegisterCut($id, $data->caja_id);
            $ban1 = $update_data->updateStatusSales($value);
            $ban2 = $update_data->updateStatusTicketCashRegisterCut($value);
        }
        if($ban && $ban1 && $ban2){
            return $ban;
        } else{
            return false;
        }
    }

    function saveDataVenta($total,$subtotal,$sucursal,$cliente,$empleado,$id,$estatus_factura){
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $update_data = new update_data();
        $folio = "VD" . str_pad((int)($get_data->getFolioVentaDirecta()+1),6,"0",STR_PAD_LEFT);
        $cliente_id = 
            $cliente !== 0 && 
            $cliente !== "0" &&
            $cliente !== null &&
            $cliente !== "" ?
            $cliente : $get_data->getClientDefault()[0]->id;
        
        $query = sprintf(" 
                        insert into ventas_directas (
                            Referencia,
                            Importe,
                            FechaVencimiento,
                            created_at,
                            updated_at,
                            FKCliente,
                            FKEstatusVenta,
                            FKUsuarioCreacion,
                            FKUsuarioEdicion,
                            empresa_id,
                            FKSucursal,
                            empleado_id,
                            Subtotal,
                            estatus_factura_id,
                            condicion_Pago,
                            estatus_factura_id_old,
                            estatus_venta_old,
                            FkMoneda_id,
                            afecta_inventario,
                            id_encriptado,
                            estatus_cuentaCobrar,
                            estatus_cuentaCobrar_old,
                            saldo_insoluto_venta
                        ) values (
                            :Referencia,
                            :Importe,
                            :FechaVencimiento,
                            :created_at,
                            :updated_at,
                            :FKCliente,
                            :FKEstatusVenta,
                            :FKUsuarioCreacion,
                            :FKUsuarioEdicion,
                            :empresa_id,
                            :FKSucursal,
                            :empleado_id,
                            :Subtotal,
                            :estatus_factura_id,
                            :condicion_Pago,
                            :estatus_factura_id_old,
                            :estatus_venta_old,
                            :FkMoneda_id,
                            :afecta_inventario,
                            :id_encriptado,
                            :estatus_cuentaCobrar,
                            :estatus_cuentaCobrar_old,
                            :saldo_insoluto_venta
                        )
        ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":Referencia",$folio);
        $stmt->bindValue(":Importe",$total);//falta checar viene de punto de venta
        $stmt->bindValue(":FechaVencimiento",date("Y-m-d"));
        $stmt->bindValue(":created_at",date("Y-m-d H:i:s"));
        $stmt->bindValue(":updated_at",date("Y-m-d H:i:s"));
        $stmt->bindValue(":FKCliente",$cliente_id);//falta checar viene de punto de venta
        $stmt->bindValue(":FKEstatusVenta",6);
        $stmt->bindValue(":FKUsuarioCreacion",$_SESSION['PKUsuario']);
        $stmt->bindValue(":FKUsuarioEdicion",$_SESSION['PKUsuario']);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->bindValue(":FKSucursal",$sucursal);//falta checar viene de punto de venta
        $stmt->bindValue(":empleado_id",$empleado);//falta checar problema
        $stmt->bindValue(":Subtotal",$subtotal);//falta checar viene de punto de venta
        $stmt->bindValue(":estatus_factura_id",$estatus_factura);//falta checar
        $stmt->bindValue(":condicion_Pago",1);
        $stmt->bindValue(":estatus_factura_id_old",$estatus_factura);//falta checar
        $stmt->bindValue(":estatus_venta_old",1);//falta checar
        $stmt->bindValue(":FkMoneda_id",100);
        $stmt->bindValue(":afecta_inventario",1);
        $stmt->bindValue(":id_encriptado",1);
        $stmt->bindValue(":estatus_cuentaCobrar",3);
        $stmt->bindValue(":estatus_cuentaCobrar_old",3);
        $stmt->bindValue(":saldo_insoluto_venta",$total);
        $stmt->execute();
        $id_last_insert = $db->lastInsertId();
        $update_data->updateSaleEncryptId($id_last_insert);
        return $id_last_insert;
    }

    function saveRelationTicketSale($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("insert into relacion_tickets_ventas (ticket_id,venta_id) values (:ticket_id,:venta_id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":ticket_id",$value);
        $stmt->bindValue(":venta_id",$value1);
        return $stmt->execute();
    }

    function saveDetailsSale($venta_id,$id)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("insert into 
                            detalle_venta_directa (Precio,Cantidad,FKProducto,FKVentaDirecta,estatus)
                            select precio_unitario,cantidad,producto_id,:venta_id,1 from detalle_ticket_punto_venta where ticket_id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":venta_id",$venta_id);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
    }

    function saveTaxesSale($id,$venta)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();
        $arr = json_decode($get_data->getTaxesForSale($id));

        foreach ($arr as $r) {
           $query = sprintf("insert into impuestos_venta_directa (Impuesto,Tasa,TotalImpuesto,FKImpuesto,FKVentaDirecta,FKProducto) values (:impuesto,:tasa,:total,:impuesto_id,:venta_id,:producto_id)");
           $stmt = $db->prepare($query);
           $stmt->bindValue(":impuesto",$r->nombre);
           $stmt->bindValue(":tasa",$r->tasa);
           $stmt->bindValue(":total",$r->total);
           $stmt->bindValue(":impuesto_id",$r->id);
           $stmt->bindValue(":venta_id",$venta);
           $stmt->bindValue(":producto_id",$r->producto_id);
           $stmt->execute();
        }
    }
    

    function saveDataGenerealInvoice($value,$date,$date1)
    {
        $get_invoice = new get_invoice();
        $save_data = new save_data();

        $msj = $get_invoice->createGeneralInvoice($value,$date,$date1);

        if (isset($msj->id)) {
            $id = $save_data->saveGeneralInvoice($value,$msj,$date,$date1);
        }      
    }

    function saveRelationTicketInvoice($value,$value1,$value2)
    {
        $con = new conection();
        $db = $con->getDb();
        
        for ($i=0; $i < count($value); $i++) { 
            $query = sprintf("
            insert into 
                relacion_tickets_facturacion 
            (
                ticket_id,
                factura_id,
                tipo
            ) values 
            (
                $value[$i],
                $value1,
                $value2
            ) ");

            $stmt = $db->prepare($query);
            $stmt->execute();
        }
    }

    function saveProductOperations($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("insert into operaciones_producto (Compra,Venta,Fabricacion,Gasto_fijo,FKProducto) values (0,1,0,0,:id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",(int)$value);
        $stmt->execute();
    }
    
  }

  class delete_data
  {

    function deleteAllProductsTableTemp($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("delete from productos_ticket_temp where usuario_id = {$_SESSION['PKUsuario']} and caja_id= {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();
    }

    function deleteAllProductsTablePedding($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("delete from productos_pendientes_ticket where usuario_id = {$_SESSION['PKUsuario']} and caja_id= {$value1} and folio= {$value}");

        $stmt = $db->prepare($query);
        $stmt->execute();
    }

    function deleteProductTableTemp($value,$value1)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("delete from productos_ticket_temp where producto_id = {$value} and usuario_id = {$_SESSION['PKUsuario']} and caja_id= {$value1}");
        $stmt = $db->prepare($query);
        return $stmt->execute();
    }

    function deleteTaxProduct($value)
    {
        $con = new conection();
        $db = $con->getDb();

        $query = sprintf("delete from impuestos_productos where FKInfoFiscalProducto = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();
    }

  }

  class get_print
  {
   
    function getPrintTicket($value,$value1,$value2,$date,$date1,$value3)
    {
        
        $get_data = new get_data();
        $get_print = new get_print();

        $data_enterpise = $get_data->getDataEnterprise();
        $path = $_ENV['RUTA_ARCHIVOS_READ'] . $data_enterpise[0]->PKEmpresa . "/fiscales/";
        $path_logo = $path . $data_enterpise[0]->logo;

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Timlid');
        $pdf->SetTitle('Prefactura');
        $pdf->SetSubject('Prefactura');
        $pdf->SetKeywords('Prefactura');
        // $pdf->logo = $path_logo;
        // $pdf->folio = 1;

        ob_end_clean();

        $pdf->Output("Prefactura " . 1 . '.pdf', 'I');
        
        // $nombre_impresora = $get_data->getPrinterName($value3)[0]->nombre_impresora; 
        
        // try{
        //     $profile = CapabilityProfile::load("simple");
        //     $connector = new WindowsPrintConnector("smb://DESKTOP-4FT2VCJ/".$nombre_impresora);
        //     $printer = new Printer($connector,$profile); 

        //     $printer->setJustification(Printer::JUSTIFY_CENTER);

        //     $data_enterpise = $get_data->getDataEnterprise();
        //     $path = $_ENV['RUTA_ARCHIVOS_READ'] . $data_enterpise[0]->PKEmpresa . "/fiscales/";
        //     $path_logo = $path . $data_enterpise[0]->logo;
        //     try{
        //         $logo = EscposImage::load($path_logo, false);
        //         $printer->bitImage($logo);
        //     }catch(Exception $e){}

        //     //encabezado
        //     //$printer->text("Yo voy en el encabezado" . "\n");
        //     $num_ext = $data_enterpise[0]->numero_interior !== null && $data_enterpise[0]->numero_interior !== "" ? $data_enterpise[0]->numero_interior : "";

        //     $domi_fiscal = 
        //     $data_enterpise[0]->calle !== null && $data_enterpise[0]->calle !== "" ?
        //         $data_enterpise[0]->calle .
        //         $data_enterpise[0]->numero_exterior .
        //         $num_ext .
        //         $data_enterpise[0]->codigo_postal . "<br>" .
        //         $data_enterpise[0]->colonia .
        //         $data_enterpise[0]->ciudad . 
        //         $data_enterpise[0]->Estado . "<br>" .
        //         $data_enterpise[0]->telefono : "";

        //     $printer->text($data_enterpise[0]->RazonSocial . "\n");
            
        //     $dom_fiscal = $data_enterpise[0]->domicilio_fiscal !== null && $data_enterpise[0]->domicilio_fiscal !== "" ? $data_enterpise[0]->domicilio_fiscal : $domi_fiscal;

        //     $printer->text($dom_fiscal . "\n");

        //     $printer->textRaw(str_repeat(chr(196), 45).PHP_EOL);

        //     $client = $get_data->getDataClient($value);

        //     $printer->text("Datos del cliente" . "\n");

        //     $printer->text("Razon social:" . $client[0]->razon_social . "\n");

        //     $printer->text("RFC:" . $client[0]->rfc . "\n");

        //     $printer->text("C.P.:" . $client[0]->codigo_postal . "\n");

        //     $printer->textRaw(str_repeat(chr(196), 45).PHP_EOL);

        //     $data_cashRegister = $get_data->getDataTicket($value,"","");

        //     $printer->setJustification(Printer::JUSTIFY_LEFT);
        //     $printer->text("Folio: " . $data_cashRegister[0]->folio . "\n");

        //     $printer->text("Fecha: ",date("Y-m-d H:i:s",strtotime($data_cashRegister[0]->fecha)) . "\n");

        //     $printer->text($data_cashRegister[0]->descripcion . "\n");
            
        //     $printer->text("Atendió: " . $data_cashRegister[0]->nombre . "\n");

        //     $printer->setJustification(Printer::JUSTIFY_CENTER);
        //     $printer->textRaw(str_repeat(chr(196), 45).PHP_EOL);

        //     // productos
        //     $data_products = $get_data->getProductsTicket($value,"","");

        //     $printer->setJustification(Printer::JUSTIFY_LEFT);

        //     $printer->text("Cantidad");
        //     $printer->text(str_pad("Concepto",30," ",STR_PAD_BOTH));
        //     $printer->text("Importe" . "\n\n");
            

        //     foreach ($data_products as $r) {

        //         $printer->text(str_pad($r->cantidad,5," "));
        //         $printer->text(str_pad($r->nombre,30," ",STR_PAD_BOTH));
        //         $printer->text(str_pad('$'.number_format($r->cantidad * $r->precio_unitario, 2) ."\n",10," ",STR_PAD_LEFT));
                

        //         // $leftCol = $r->cantidad . " x " . $r->nombre;
        //         // $rightCol = ' $' . number_format($r->cantidad * $r->precio_unitario, 2);
        //         // $printer->text($get_print->columnify($leftCol, $rightCol, 22, 22, 15));

        //     }
            
        //     $printer->text("\n\n");
        //     // $printer->text("TOTAL: $\n");

        //     $printer->setJustification(Printer::JUSTIFY_CENTER);

        //     $printer->textRaw(str_repeat(chr(196), 45).PHP_EOL);

        //     $printer->setJustification(Printer::JUSTIFY_RIGHT);

        //     $printer->text("Subtotal: $" . number_format($data_cashRegister[0]->subtotal,2) . "\n");

        //     $data_tax = $get_print->getFormatTicketTax($value,$date,$date1);

        //     for ($i=0; $i < count($data_tax); $i++) { 
                
        //         $printer->text($data_tax[$i] . "\n");
        //     }

        //     $printer->setJustification(Printer::JUSTIFY_RIGHT);

        //     $value1 !== null && $value1 !== "" ? $printer->text("Monto recibido: $" . number_format($value1,2) . "\n") : "";

        //     $value2 !== null && $value2 !== "" ? $printer->text("Cambio: $" . number_format($value2,2) . "\n") : "";

        //     $printer->text("Total: $" . number_format($data_cashRegister[0]->total,2) . "\n");

        //     $printer->setJustification(Printer::JUSTIFY_CENTER);

        //     $printer->textRaw(str_repeat(chr(196), 45).PHP_EOL);

        //     $printer->text("Muchas gracias por su compra" ."\n\n");

        //     // ../../img/header/timlidAzul.png
            
        //     $printer->text("By Timlid");
        //     // try{
        //     //   $logo_footer = EscposImage::load("../img/timBlue.png", false);
        //     //   $printer->bitImage($logo_footer);
        //     // }catch(Exception $e){}

        //     $printer->text("\n\n");

        //     $printer->feed();

        //     $printer->cut();

        //     $printer->pulse();

        //    return $printer->close();
        // }catch(Exception $e){
        //     return $e->getMessage();
        // }
    }

    function columnify($leftCol, $rightCol, $leftWidth, $rightWidth, $space)
    {
        $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
        $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

        $leftLines = explode("\n", $leftWrapped);
        $rightLines = explode("\n", $rightWrapped);
        $allLines = array();

        for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i ++) {
            $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
            $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
            $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
        }
        
        return implode("\n", $allLines) . "\n";
    }

    function getFormatTicketTax($value,$date,$date1)
    {
        $get_data = new get_data();

        $data = $get_data->getProductsTicketTaxUnique($value,$date,$date1);
        $aux_data = [];
    
        foreach($data as $r){
            
            switch ($r['impuesto']) {
            case 1:
                array_push($aux_data,"IVA " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 2:
                array_push($aux_data,"IEPS " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 3:
                array_push($aux_data,"IEPS $" . number_format($r['tasa'],2) . ": $" . number_format($r['importe'],2));
            break;
            case 4:
                array_push($aux_data,"ISH " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 5:
            break;
            case 6:
                array_push($aux_data,"IVA Retenido " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 7:
                array_push($aux_data,"ISR " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 8:
                array_push($aux_data,"ISN " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 9:
                array_push($aux_data,"Cedular " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 10:
                array_push($aux_data,"5 al millar " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 11:
                array_push($aux_data,"Función Pública " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 12:
                array_push($aux_data,"IEPS Retenido " . $r['tasa'] . "%: $" . number_format($r['importe'],2));
            break;
            case 18:
                array_push($aux_data,"IEPS Retenido $" . $r['tasa'] . ": $" . number_format($r['importe'],2));
            break;

            }

        }

        return $aux_data;
    }
  }

  class get_invoice
  {
    function createInvoice($value,$value1)
    {
        include_once "../../../include/functions_api_facturation.php";
        $get_data = new get_data();
        $api = new API();
        $keyCompany = $get_data->getKeyCompanyApi();
        $data = $get_data->getFormatInvoice($value,$value1);

        return $api->createInvoice($keyCompany[0]->api_key,$data);
    }

    function createGeneralInvoice($value,$value1,$value2)
    {
        include_once "../../../include/functions_api_facturation.php";
        $get_data = new get_data();
        $api = new API();
        $keyCompany = $get_data->getKeyCompanyApi();
        $data = $get_data->getFormatGeneralInvoice($value,$value1,$value2);

        return $api->createInvoice($keyCompany[0]->api_key,$data);
    }

    function getPdfInvoice($value,$value1)
    {
        include_once "../../../include/functions_api_facturation.php";
        $get_data = new get_data();
        $api = new API();
        $keyCompany = $get_data->getKeyCompanyApi();

        $pdf = $api->downloadPdfInvoice($keyCompany[0]->api_key,$value);

        header('Content-type: application/pdf');

        header('Content-Disposition: attachment; filename="' . $pdf . '.pdf"');
            
        header('Content-Transfer-Encoding: binary');
            
        // header('Accept-Ranges: bytes');

        readfile($value1 .".pdf");
        exit;

        // return $pdf ;
    }
  }
