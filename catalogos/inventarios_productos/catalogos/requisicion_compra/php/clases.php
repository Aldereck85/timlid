<?php
session_start();
date_default_timezone_set('America/Mexico_City');
class conectar
{ //Llamado al archivo de la conexión.
  function getDb()
  {
    include "../../../../../include/db-conn.php";
    return $conn;
  }
  
  function formatoCantidad($valor){
    //borra los 0 de más
    $valor=floatval($valor);

    //si tiene menos de 2 decimales le añade el/los 0
    $aux = explode('.', $valor);
    if(count($aux) > 0){
        if(count($aux) == 1 || strlen($aux[1]) <= 2){
            $valor=number_format($valor,2);
        }else{
        $valor=number_format($valor,strlen($aux[1]));
        }
    }
    return $valor;
  }
}

class get_data
{
  function getRequisicionesTable($isPermissionsEdit,$isPermissionsDelete){
    $con = new conectar();
    $db = $con->getDb();

    $table = "";
    $PKEmpresa = $_SESSION["IDEmpresa"];

    $stmt = $db->prepare('call spc_Tabla_Requisiciones_Consulta(?)');
    $stmt->execute(array($PKEmpresa));
    $array = $stmt->fetchAll();

    $acciones = '';

    foreach ($array as $r) {

        $Id = $r['PKRequisicion'];
        $folio = $r['folio'];
        $fechaEmision = $r['fecha_registro'];
        $fechaEstimada = $r['fecha_estimada_entrega'];
        $comprador = $r['NombreComprador'];
        $aplicado = $r['NombreEmpleado'];
        $area = $r['area'];

        //añade una etiqueta segun el estado de la factura
        if($r['estatus']==1){
          $r['estatus']= '<span class=\"left-dot turquoise-dot\">Pendiente</span>';
        }elseif($r['estatus']==2){
            $r['estatus']= '<span class=\"left-dot yellow-dot\">Parcialmente colocada</span>';
        }elseif($r['estatus']==3){
          $r['estatus']= '<span class=\"left-dot green-dot\">Colocada completa</span>';
        }elseif($r['estatus']==4){
            $r['estatus']= '<span class=\"left-dot red-dot\">Cerrada</span>';
        }elseif($r['estatus']==0){
          $r['estatus']= '<span class=\"left-dot red-dot\">Cancelada</span>';
        }

        $estatus = $r['estatus'];

        if ($isPermissionsEdit == '1'){
            $folio = '<a href=\"../requisicion_compra/detalleRequisicion.php?requisicion='.$Id.'\">'.$folio.'</a>';
            $acciones = '<a href=\"../requisicion_compra/detalleRequisicion.php?requisicion='.$Id.'\"><i class=\"fas fa-clipboard-list pointer\"></i></a>';
        }

        $etiquetaI = '<span class=\"textTable\">';
        $etiquetaF = '</span>';

        $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
              "folio":"'  . $folio  . '",
              "f emision":"' . $etiquetaI . date("d-m-Y", strtotime($fechaEmision)) . $etiquetaF . '",
              "f estimada":"' . $etiquetaI . date("d-m-Y", strtotime($fechaEstimada)) . $etiquetaF . '",
              "comprador":"' . $etiquetaI . $comprador . $etiquetaF . '",
              "aplicado por":"' . $etiquetaI . $aplicado . $etiquetaF . '",
              "area":"' . $etiquetaI . $area . $etiquetaF . '",
              "estado":"' . $etiquetaI . $estatus . $etiquetaF . '",
              "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
    }
    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';
  }

  function get_cmbProductos(){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query =("SELECT distinct PKData, Data from (
                SELECT p.PKProducto AS PKData,
                    CONCAT(p.ClaveInterna,' - ',p.Nombre) AS Data 
                FROM productos AS p
                  left join operaciones_producto op on p.PKProducto = op.FKProducto
                WHERE p.estatus = 1
                  AND CONCAT(p.ClaveInterna,' - ',p.Nombre) <> ' - '
                  AND p.empresa_id = :empresa
                )as productos 
                group by PKData
                order by PKData
                ;");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa",$PKEmpresa);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getProductosTable($IDprods, $provee){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $table="";
    //recupera los id del producto y consulta los datos de cada producto cargado
    foreach ($IDprods as $producto)
    {
      $idProducto = $producto['id'];
      $cantidad = $producto['cantidad'];
      $nombre = $producto['nombre'];
      $clave = $producto['clave'];
      $pkProveedor=-0;
      if($provee != "no" && $provee != "" && $provee != null){
        $pkProveedor = $provee;
      }

      //recupera la unidad de medida del los productos
      $query =('SELECT p.PKProducto, 
      if(dpp.Clave is null, p.ClaveInterna, dpp.Clave) as clave_dpp, 
      p.ClaveInterna as clave_p, 
      if(dpp.NombreProducto is null, p.Nombre, dpp.NombreProducto) as nombre_dpp, 
      p.Nombre AS nombre_p, 
      Case dpp.UnidadMedida 
        when null then csu.Descripcion 
          when "" then csu.Descripcion
          else if(dpp.UnidadMedida is null, ifnull(csu.Descripcion, "Sin Clave"), dpp.UnidadMedida) end as unidad_dpp, 
      ifnull(csu.Descripcion, "Sin Clave") as unidad_p from
      productos as p
      left join info_fiscal_productos as ifp on ifp.FKProducto = p.PKProducto
      left join claves_sat_unidades as csu on csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
      LEFT join (select * from datos_producto_proveedores where FKProveedor=:condProve) as dpp on dpp.FKProducto=p.PKProducto
      where p.PKProducto = :idProd and p.empresa_id= :empresa;');

      $stmt = $db->prepare($query);
      $stmt->bindValue(':idProd',$idProducto,PDO::PARAM_INT);
      $stmt->bindValue(':empresa',$PKEmpresa,PDO::PARAM_INT);
      $stmt->bindValue(':condProve',$pkProveedor,PDO::PARAM_INT);
      $stmt->execute();
      $numResult = $stmt->rowCount(); 
      $r = $stmt->fetch();
      if($numResult == 1){
        if($pkProveedor != ""){
          //si traen proveedor, entonces verifica si se ingresaron los datos de nombre y clave para el producto
          if($nombre != "no"){
            $r['nombre_dpp'] = $nombre;
          }
          if($clave != "no"){
            $r['clave_dpp'] = $clave;
          }
          $claveProd = $r['clave_dpp']." - ".$r['nombre_dpp'];
          $unidadMedida = $r['unidad_dpp'];
        }else{
          $claveProd = $r['clave_p']." - ".$r['nombre_p'];
          $unidadMedida = $r['unidad_p'];
        }
  
        $acciones='<a class=\"edit-tabs-371\" id=\"btnEliminaProducto\"><img src=\"../../../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" onclick=\"eliminaProducto('.$r['PKProducto'].')\"/></a>';
        $cantidad='<input class=\"form-control numeric-only\" Style=\"width : 50%\" onchange=\"validaCantidad(this)\" type=\"text\" name=\"inputs_cantidades\" value=\"'.$cantidad.'\" id=\"input-'.$r['PKProducto'].'\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
  
  
        $table .= '{"Id":"' . $r['PKProducto'] . '",
          "Clave_Producto":"' . $claveProd . '",
          "Cantidad":"' . $cantidad . '",
          "Unidad medida":"' . $unidadMedida . '",
          "Acciones":"' .$acciones. '"},';
      }
    }

    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';
  }

  function getCmbEmpleado(){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query = sprintf('call spc_Combo_Empleados(?)');
    $stmt = $db->prepare($query);
    $stmt->execute(array($PKEmpresa));
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function getCmbArea(){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query = sprintf('call spc_Combo_areaDepartamento(?)');
    $stmt = $db->prepare($query);
    $stmt->execute(array($PKEmpresa));
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function getCabeceraRequisicion($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query = sprintf('call spc_Cabecera_Requisicion(?,?)');
    $stmt = $db->prepare($query);
    $stmt->execute(array($idRequisicion, $PKEmpresa));   
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function get_ProductosDetalle($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $table="";

    $query = sprintf('call spc_Requisicion_productosDetalle(?,?)');
    $stmt = $db->prepare($query);
    $stmt->execute(array($idRequisicion, $PKEmpresa));
    $r = $stmt->fetchAll();

    foreach($r as $producto){
      $table .= '{"Id":"' . $producto['FKProducto'] . '",
        "Clave":"' . $producto['claveProd'] . '",
        "Producto":"' . $producto['nombreProd'] . '",
        "Cantidad":"' . $producto['cantidad'] . '",
        "CantidadPedida":"' . $producto['cantidad_pedida'] . '",
        "Unidad medida":"' . $producto['unidad'] . '"},';
    }
    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';
  }

  function getDataRequisicionEdit($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];
    try{
    //recupera cabecera
    $stmt = $db->prepare('SELECT DATE_FORMAT(fecha_registro, "%Y-%m-%d") as fecha_registro, fecha_estimada_entrega, FKSucursal, FKProveedor, comprador, aplicado_por, area, notas_comprador, notas_internas 
    from requisiciones_compra 
    where PKRequisicion = :idRequisicion and empresa_id = :PKEmpresa;');
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt->closeCursor();

    //recupera cadena de productos añadidos
    $query = sprintf('SELECT claveProd, nombreProd, cantidad, FKProducto, FKRequisicion 
                      from detalle_requisicion_compra 
                      where FKRequisicion = :idRequisicion');
    $stmt = $db->prepare($query);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->execute();
    $r = $stmt->fetchAll();

    $cadena = "";
    foreach($r as $producto){
      $cadena .= $producto['FKProducto'].'|'.$producto['cantidad'].'|'.$producto['nombreProd'].'|'.$producto['claveProd'].',';
    }
    $cadena = substr($cadena, 0, strlen($cadena) - 1);
    
    $data["estatus"] = "ok";
    $data['cabecera'] = $row;
    $data['cadena'] = $cadena;
    }catch(Exception $e){
      $data["estatus"] = "err";
      $data['result'] = "Error al consultar datos";
      $data['error'] = $e;
    }
      echo json_encode($data);
  }

  function getCabeceraSeguimientoRequisicion ($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $stmt = $db->prepare('SELECT  rc.folio, 
                rc.fecha_estimada_entrega,
                DATE_FORMAT(rc.fecha_registro, "%Y-%m-%d") as fecha_registro, 
                rc.FKSucursal,  
                a.nombre as area, 
                (concat (emple.Nombres," ",emple.PrimerApellido," ",emple.SegundoApellido)) as NombreEmpleado,
                rc.estatus,
                rc.FKProveedor,
                rc.comprador,
                rc.notas_comprador
            from requisiciones_compra as rc 
            inner join empleados as emple on emple.PKEmpleado = rc.aplicado_por
            inner join areaDepartamento as a on a.id = rc.area
            where rc.empresa_id = :PKEmpresa and rc.PKRequisicion = :idRequisicion;');
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->execute();   
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function get_cmbProductosRequisicion($idRequisicion, $idProveedor){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];
    $table = "";

    //si trae proveedor indica un cambio de proveedor del combo, por lo que se deben actualizar los datos de los productos
    if($idProveedor !== 0){
      $query =('SELECT dr.FKProducto, 
                dr.cantidad,
                dr.cantidad_pedida as cantidad_pedida,
                (dr.cantidad - dr.cantidad_pedida) as restante, 
                dr.nombreProd as nombreSugerido, 
                dr.claveProd as claveSugerida,
                dpp.NombreProducto as nombreDPP,
                dpp.Clave as claveDPP,
                Case dpp.UnidadMedida 
                when null then csu.Descripcion 
                  when "" then csu.Descripcion
                  else if(dpp.UnidadMedida is null, ifnull(csu.Descripcion, "Sin Clave"), dpp.UnidadMedida) end as unidad_dpp,
                ifnull(dpp.NombreProducto, p.Nombre) as nombreProd,
                ifnull(dpp.Clave, p.ClaveInterna) as claveProd,
                ifnull(dpp.Precio, 1.00) as precio
                from detalle_requisicion_compra as dr
                inner join productos as p on p.PKProducto = dr.FKProducto
                left join info_fiscal_productos as ifp on ifp.FKProducto = p.PKProducto
                inner join requisiciones_compra as rc on rc.PKRequisicion = dr.FKRequisicion
                left join claves_sat_unidades as csu on csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
                LEFT join (select * from datos_producto_proveedores where FKProveedor = :idProveedor) as dpp on dpp.FKProducto=p.PKProducto
                where dr.FKRequisicion = :idRequisicion and rc.empresa_id = :PKEmpresa and (dr.cantidad - dr.cantidad_pedida) > 0');
          
      $stmt = $db->prepare($query);
      $stmt->bindValue(":idRequisicion",$idRequisicion);
      $stmt->bindValue(":idProveedor",$idProveedor);
      $stmt->bindValue(":PKEmpresa",$PKEmpresa);
      $stmt->execute();
    }else{
      $query =('SELECT dr.FKProducto, 
                dr.cantidad,
                dr.cantidad_pedida as cantidad_pedida,
                (dr.cantidad - dr.cantidad_pedida) as restante, 
                dr.nombreProd as nombreSugerido, 
                dr.claveProd as claveSugerida,
                dpp.NombreProducto as nombreDPP,
                dpp.Clave as claveDPP,
                Case dpp.UnidadMedida 
                when null then csu.Descripcion 
                  when "" then csu.Descripcion
                  else if(dpp.UnidadMedida is null, csu.Descripcion, dpp.UnidadMedida) end as unidad_dpp,
                ifnull(dpp.NombreProducto, p.Nombre) as nombreProd,
                ifnull(dpp.Clave, p.ClaveInterna) as claveProd,
                ifnull(dpp.Precio, 1.00) as precio
                from detalle_requisicion_compra as dr
                inner join productos as p on p.PKProducto = dr.FKProducto
                inner join info_fiscal_productos as ifp on ifp.FKProducto = p.PKProducto
                inner join requisiciones_compra as rc on rc.PKRequisicion = dr.FKRequisicion
                inner join claves_sat_unidades as csu on csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
                LEFT join (select * from datos_producto_proveedores where FKProveedor = (select rrc.FKProveedor from requisiciones_compra as rrc where rrc.PKRequisicion = :idRequisicion2)) as dpp on dpp.FKProducto=p.PKProducto
                where dr.FKRequisicion = :idRequisicion and rc.empresa_id = :PKEmpresa and (dr.cantidad - dr.cantidad_pedida) > 0');

      $stmt = $db->prepare($query);
      $stmt->bindValue(":idRequisicion",$idRequisicion);
      $stmt->bindValue(":idRequisicion2",$idRequisicion);
      $stmt->bindValue(":PKEmpresa",$PKEmpresa);
      $stmt->execute();
    }

    
    $array = $stmt->fetchAll();

    foreach ($array as $r) {
      $etiquetaI = '<span class=\"textTable\">';
      $etiquetaF = '</span>';

      $precio = '<input class=\"form-control numericDecimal-only\" type=\"text\" name=\"inpt_precio\" value=\"'.$con->formatoCantidad($r['precio']).'\" id=\"precio-'.$r['FKProducto'].'\" maxlength=\"19\" onchange=\"validaCantidad(this)\">';
      $cantidad = '<input class=\"form-control numeric-only\" type=\"text\" name=\"inpt_cantidad\" value=\"'.$r['restante'].'\" id=\"cantidad-'.$r['FKProducto'].'\" maxlength=\"8\" onchange=\"validaCantidad(this)\">';
      $importe = '<input class=\"textTable\" style=\"border-bottom: 0px; color: #858796\" type=\"text\" name=\"inpt_precio\" id=\"importe-'.$r['FKProducto'].'\" value=\"'.($r['precio']*$r['restante']).'\" onchange=\"validaCantidad(this, 1)\" disabled>';

      $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-'.$r['FKProducto'].'\" onclick=\"deleteProducto(this)\"></i>';
      //seleccion de clave y nombre
      if($r['nombreDPP'] != null && $r['claveDPP'] != null){
        $nombre = '<input class=\"form-control alphaNumeric-only\" type=\"text\" name=\"inpt_name\" value=\"'.$r['nombreDPP'].'\" id=\"nombre-'.$r['FKProducto'].'\" maxlength=\"255\" disabled>';
        $clave = '<input class=\"form-control alphaNumeric-only\" type=\"text\" name=\"inpt_clave\" value=\"'.$r['claveDPP'].'\" id=\"clave-'.$r['FKProducto'].'\" maxlength=\"255\" disabled>';
      }else if($r['nombreSugerido'] != "no" && $r['claveSugerida'] != "no"){
        $nombre = '<input class=\"form-control alphaNumeric-only\" type=\"text\" name=\"inpt_name\" value=\"'.$r['nombreSugerido'].'\" id=\"nombre-'.$r['FKProducto'].'\" maxlength=\"255\" onchange=\"validEmptyInput(this, true), CambiaClaveNombre(this)\">';
        $clave = '<input class=\"form-control alphaNumeric-only\" type=\"text\" name=\"inpt_clave\" value=\"'.$r['claveSugerida'].'\" id=\"clave-'.$r['FKProducto'].'\" maxlength=\"255\" onchange=\"validEmptyInput(this, true), CambiaClaveNombre(this)\">';
      }else{
        $nombre = '<input class=\"form-control alphaNumeric-only\" type=\"text\" name=\"inpt_name\" value=\"'.$r['nombreProd'].'\" id=\"nombre-'.$r['FKProducto'].'\" maxlength=\"255\" onchange=\"validEmptyInput(this, true), CambiaClaveNombre(this)\">';
        $clave = '<input class=\"form-control alphaNumeric-only\" type=\"text\" name=\"inpt_clave\" value=\"'.$r['claveProd'].'\" id=\"clave-'.$r['FKProducto'].'\" maxlength=\"255\" onchange=\"validEmptyInput(this, true), CambiaClaveNombre(this)\">';
      }

      $table .= '{"Id":"' . $etiquetaI . $r['FKProducto'] . $etiquetaF . '",
            "Clave":"'  . $clave  . '",
            "Producto":"' . $etiquetaI . $nombre . $etiquetaF . '",
            "Cantidad_r":"' . $etiquetaI . $r['cantidad'] . $etiquetaF . '",
            "Cantidad_p":"' . $etiquetaI . $r['cantidad_pedida'] . $etiquetaF . '",
            "Cantidad_f":"' . $etiquetaI . $r['restante'] . $etiquetaF . '",
            "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . '",
            "Precio":"' . $etiquetaI . $precio . $etiquetaF . '",
            "Unidad medida":"' . $etiquetaI . $r['unidad_dpp'] . $etiquetaF . '",
            "Importe":"' . $importe . '",
            "acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
    }
    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';
  }

  function get_dataProductosRequisicion($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query =("SELECT rc.FKProveedor, 
    dr.FKProducto, 
    (dr.cantidad - ifnull(dr.cantidad_pedida, 0)) as restante, 
    dr.claveProd, 
    dr.nombreProd, 
    ifnull(dpp.NombreProducto, p.Nombre) as nombreProd,
    ifnull(dpp.Clave, p.ClaveInterna) as claveProd,
    ifnull(dpp.precio,0) as precio
              from detalle_requisicion_compra dr 
              inner join requisiciones_compra rc on rc.PKRequisicion = dr.FKRequisicion
              inner join productos as p on p.PKProducto = dr.FKProducto
              LEFT join (select * from datos_producto_proveedores where FKProveedor = (select rrc.FKProveedor from requisiciones_compra as rrc where rrc.PKRequisicion = :idRequisicion2)) as dpp on dpp.FKProducto=p.PKProducto
              where FKRequisicion = :idRequisicion and rc.empresa_id = :PKEmpresa and (dr.cantidad - dr.cantidad_pedida) > 0");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->bindValue(":idRequisicion2",$idRequisicion);
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function get_taxesProductosRequisicion($idRequisicion, $onlyIeps = 0){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];
    if($onlyIeps == 1){
      $query =("SELECT ROW_NUMBER() OVER(ORDER BY i.Nombre ASC) as num,
      drc.FKRequisicion as pkRequi, 
            i.FKTipoImpuesto,
            i.FKTipoImporte,
            i.Nombre as nombre,
            ip.Tasa as tasa,
            i.PKImpuesto as pkImpuesto,
            drc.FKProducto as FKProducto
        from detalle_requisicion_compra drc
          inner join productos p on drc.FKProducto = p.PKProducto  
          inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
          inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
          inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
          inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
        where drc.FKRequisicion = :idRequisicion and p.empresa_id = :PKEmpresa and PKImpuesto in (2, 3) and (drc.cantidad - drc.cantidad_pedida) > 0;");
    }else{
      $query =("SELECT ROW_NUMBER() OVER(ORDER BY i.Nombre ASC) as num,
                    i.FKTipoImpuesto,
                    i.PKImpuesto as pkImpuesto,
                    i.FKTipoImporte,
                    i.Nombre as nombre,
                    ip.Tasa as tasa,
                    drc.FKProducto as FKProducto
                  from detalle_requisicion_compra drc
                    inner join productos p on drc.FKProducto = p.PKProducto  
                    inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                    inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
                    inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
                    inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
                  where drc.FKRequisicion = :idRequisicion and p.empresa_id = :PKEmpresa and (drc.cantidad - drc.cantidad_pedida) > 0;");
    }
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  
  function get_OrdenesGeneradas($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query =("SELECT ro.FKOrden, oc.Referencia as folio from Requisiciones_ordenesCompra as ro 
                inner join ordenes_compra as oc on oc.PKOrdenCompra = ro.FKOrden
                inner join requisiciones_compra as r on r.PKRequisicion = ro.FKRequisicion
              where ro.FKRequisicion = :idRequisicion and oc.FKEstatusOrden != 3 and r.empresa_id = :PKEmpresa; ");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}

class save_data
{
  function saveRequisicion($isPermissionsAdd,$datos,$_FechaEstimada,$_SucursalEntrega,$_Area,$_Empleado,$_Proveedor,$_Comprador,$_NotasComprador,$_NotasInternas){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];
    $greenFlag=true;

    if($isPermissionsAdd != 1){
      $greenFlag=false;
    }

    //validación de tamaño de inputs
    if(strlen($_NotasComprador)>255 || strlen($_NotasInternas)>255){
      $greenFlag=false;
      $data['estatus']='err-v';
      $data['result']="Tamaño de caracteres excedido";
    }

    //cadena que contiene los datos de los productos
    $string="";
    $countCadena=0;
    //generación de cadena para insertar datos a bd
    foreach ($datos as $producto){
      $countCadena++;
      if(strlen(trim($producto['nombre'])) > 255 || strlen(trim($producto['clave'])) > 255 ){
        $greenFlag=false;
        $data['estatus']='err-v';
        $data['result']="Tamaño de caracteres excedido";
        break 1;
      }
      $string.= $producto['id'] . '|' . $producto['cantidad'] . '|' . $producto['nombre'] . '|' . $producto['clave'] .',';
    }
    $string = substr($string, 0, -1);

    if($greenFlag){
  
      try{
        //inserciones a la base de datos
        $stmt = $db->prepare('call spi_RequisicionesCompra_Agregar(?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array($string, $countCadena, $_FechaEstimada, $_SucursalEntrega, $_Area, $_Empleado, $_Proveedor, $_Comprador, $_NotasComprador, $_NotasInternas, $PKEmpresa));
        $result = $stmt->fetch();
        $data['estatus']='ok';
        $data['result']= $result[0];
        $_SESSION["actualizadoRC"]=1;
      }catch(Exception $e){
        $data['estatus']='err';
        $data['result']="Error al intentar guardar datos";
        $data['error']=$e;

      }
    }
    echo json_encode($data);
  }

  function updateRequisicion($isPermissionsEdit, $idRequisicion,$insert,$update,$delete,$_FechaEstimada,$_SucursalEntrega,$_Area,$_Empleado,$_Proveedor,$_Comprador,$_NotasComprador,$_NotasInternas){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];
    $greenFlag=true;

    if($isPermissionsEdit != 1){
      $greenFlag=false;
    }

    //validación de tamaño de inputs
    if(strlen($_NotasComprador)>255 || strlen($_NotasInternas)>255){
      $greenFlag=false;
      $data['estatus']='err-v';
      $data['result']="Tamaño de caracteres excedido";
    }

    $stringInsert="";
    $countInsert=0;
    //generación de cadena para insertar datos a bd
    if($insert != 0){
      foreach ($insert as $producto){
        $countInsert++;
        if(strlen(trim($producto['nombre'])) > 255 || strlen(trim($producto['clave'])) > 255 ){
          $greenFlag=false;
          $data['estatus']='err-v';
          $data['result']="Tamaño de caracteres excedido";
          break 1;
        }
        $stringInsert.= $producto['id'] . '|' . $producto['cantidad'] . '|' . $producto['nombre'] . '|' . $producto['clave'] .',';
      }
      if($stringInsert != ""){
        $stringInsert = substr($stringInsert, 0, -1);
      }
    }
    
    $stringUpdate="";
    $countUpdate=0;
    //generación de cadena para actualizar datos en bd
    if($update != 0){
      foreach ($update as $producto){
        $countUpdate++;
        if(strlen(trim($producto['nombre'])) > 255 || strlen(trim($producto['clave'])) > 255 ){
          $greenFlag=false;
          $data['estatus']='err-v';
          $data['result']="Tamaño de caracteres excedido";
          break 1;
        }
        $stringUpdate.= $producto['id'] . '|' . $producto['cantidad'] . '|' . $producto['nombre'] . '|' . $producto['clave'] .',';
      }
      if($stringUpdate != ""){
        $stringUpdate = substr($stringUpdate, 0, -1);
      }
    }
    
    $stringDelete="";
    $countDelete=0;
    //generación de cadena para eliminar datos en bd
    if($delete != 0){
      foreach ($delete as $producto){
        $countDelete++;
        if(strlen(trim($producto['nombre'])) > 255 || strlen(trim($producto['clave'])) > 255 ){
          $greenFlag=false;
          $data['estatus']='err-v';
          $data['result']="Tamaño de caracteres excedido";
          break 1;
        }
        $stringDelete.= $producto['id'] . '|' . $producto['cantidad'] . '|' . $producto['nombre'] . '|' . $producto['clave'] .',';
      }
      if($stringDelete != ""){
        $stringDelete = substr($stringDelete, 0, -1);
      }
    }
    
    if($greenFlag){
  
      try{
        //inserciones a la base de datos
        $stmt = $db->prepare('call spu_RequisicionesCompra_Editar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array($idRequisicion,
                              $stringInsert, 
                              $countInsert, 
                              $stringUpdate, 
                              $countUpdate, 
                              $stringDelete, 
                              $countDelete, 
                              $_FechaEstimada, 
                              $_SucursalEntrega, 
                              $_Area, 
                              $_Empleado, 
                              $_Proveedor, 
                              $_Comprador, 
                              $_NotasComprador, 
                              $_NotasInternas, 
                              $PKEmpresa));
        $result = $stmt->fetch();
        $data['estatus']='ok';
        $data['result']= $result;
        $_SESSION["actualizadoRC"]=2;
      }catch(Exception $e){
        $data['estatus']='err';
        $data['result']="Error al actualizar requisición";
        $data['error']=$e;
      }
    }
    echo json_encode($data);
  }

  function saveSeguimiento($isPermissionsAdd, $datos, $_FechaEstimada, $_SucursalEntrega,$_Proveedor,$_Comprador,$_NotasProveedor,$_CondicionPago,$_Moneda,$idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    //recupera impuestos
    $get = new get_data();
    $taxes = $get->get_taxesProductosRequisicion($idRequisicion);
    $taxesIeps = $get->get_taxesProductosRequisicion($idRequisicion, 1);

    $PKEmpresa = $_SESSION["IDEmpresa"];
    $PKUsuario = $_SESSION['PKUsuario'];
    $greenFlag=true;

    if($isPermissionsAdd != 1){
      $greenFlag=false;
    }

    //validación de tamaño de inputs
    if(strlen($_NotasProveedor)>255){
      $greenFlag=false;
      $data['estatus']='err-v';
      $data['result']="Tamaño de caracteres excedido";
    }

    //cadena que contiene los datos de los productos
    $string="";
    $countCadena=0;
    //importe para el seguimiento
    $importe=0;
    //arreglo de pk de tipo iva
    $arrPkIva = array(1);

    //generación de cadena para insertar datos a bd
    foreach ($datos as $producto){
      $countCadena++;
      if(strlen(trim($producto['nombreSugerido'])) > 255 || strlen(trim($producto['claveSugerida'])) > 255 ){
        $greenFlag=false;
        $data['estatus']='err-v';
        $data['result']="Tamaño de caracteres excedido";
        break 1;
      }
      $string.= $producto['id'] . '|' . $producto['claveSugerida'] . '|' . $producto['nombreSugerido'] . '|' . $producto['cantidad'] . '|' . $producto['precio'] .',';
      
      //recorre los impuestos en busca de una coincidencia
      $ImpuestoProd = 0;
      foreach($taxes as $tax){
        if($tax->FKProducto == $producto['id']){

          $sumIepsProd = 0;
          //si el impuesto es iva y el producto tiene ieps calcula el subtotal + ieps del producto para el cálculo del iva
          if(in_array($tax->pkImpuesto, $arrPkIva)){
            foreach($taxesIeps as $ieps){
              if($ieps->FKProducto == $tax->FKProducto){
                $imp = 0;
                if($ieps->FKTipoImporte == 2){
                  $imp = ($producto['cantidad'] * $ieps->tasa);
                }else if($tax->FKTipoImporte == 1){
                  $imp = ($producto['cantidad'] * $producto['precio']) * ($ieps->tasa / 100);
                }
    
                //lo suma o lo resta, según el tipo de impuesto
                if($ieps->FKTipoImpuesto == 2){
                  $sumIepsProd = $sumIepsProd - $imp;
                }else{
                  $sumIepsProd += $imp;
                }
              }
            }
          }

          if($tax->FKTipoImpuesto == 2){
            if($tax->FKTipoImporte == 2){
              $ImpuestoProd = ($ImpuestoProd) - (($producto['cantidad'] * $tax->tasa) + $sumIepsProd);
            }else if($tax->FKTipoImporte == 1){
              $ImpuestoProd = (($ImpuestoProd) - ((($producto['cantidad'] * $producto['precio']) + $sumIepsProd) * ($tax->tasa / 100)));
            }
          }else if($tax->FKTipoImporte == 2){
            $ImpuestoProd += ($producto['cantidad'] * $tax->tasa) + $sumIepsProd;
          }else if($tax->FKTipoImporte == 1){
            $ImpuestoProd += ((($producto['cantidad'] * $producto['precio']) + $sumIepsProd) * ($tax->tasa / 100));
          }    
        }
      }
      $importe += ($producto['cantidad'] * $producto['precio']) + $ImpuestoProd;
    }
    $importe = number_format($importe, 6, '.', '');
    $string = substr($string, 0, -1);

    if($greenFlag){
      try{
        //inserciones a la base de datos
        $stmt = $db->prepare('call spi_saveSeguimiento_requisiciones(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array($string, $countCadena, $importe, $_FechaEstimada, $_SucursalEntrega, $_Proveedor, $_Comprador, $_NotasProveedor, $_CondicionPago, $_Moneda, $PKEmpresa, $PKUsuario, $idRequisicion));
        $result = $stmt->fetch();
        $data['estatus']='ok';
        $data['result']= $result;
        $_SESSION["actualizadoRC"]=4;
      }catch(Exception $e){
        $data['estatus']='err';
        $data['error']=$e;
      }
    }
    echo json_encode($data);
  }
}

class delete_data
{
  function cancelaRequisicion($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query =("UPDATE requisiciones_compra set estatus = 0 where PKRequisicion = :idRequisicion and empresa_id = :PKEmpresa");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function cerrarRequisicion($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $query =("UPDATE requisiciones_compra set estatus = 4 where PKRequisicion = :idRequisicion and empresa_id = :PKEmpresa");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->bindValue(":PKEmpresa",$PKEmpresa);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}

class validate_data
{
  function validaPermisosPantalla(){
    $con = new conectar();
    $db = $con->getDb();

    $id = $_SESSION["PKUsuario"];
    $empresa = $_SESSION["IDEmpresa"];

    $stmt = $db->prepare("SELECT fp.funcion_ver, fp.funcion_exportar, fp.funcion_editar, fp.funcion_eliminar, fp.funcion_agregar from funciones_permisos fp 
                            inner join usuarios u on u.perfil_id=fp.perfil_id where u.id=:id and u.empresa_id=:empresa and fp.pantalla_id = 68");
        $stmt->bindValue(":id",$id);
        $stmt->bindValue(":empresa",$empresa);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $rows = $stmt->rowCount();
        if($rows<=0){
            $row['funcion_ver']=0;
            $row['funcion_exportar']=0;
            $row['funcion_editar']=0;
            $row['funcion_eliminar']=0;
            $row['funcion_agregar']=0;
        }
        return $row;
  }

  function validateIsComprador(){
    $con = new conectar();
    $db = $con->getDb();

    $PKUsuario = $_SESSION["PKUsuario"];
    $PKEmpresa = $_SESSION["IDEmpresa"];

    $stmt = $db->prepare("(SELECT u.id as PKUsuario
                          from usuarios as u 
                          where u.role_id in (2, 5) and u.empresa_id = :idEmpresa and u.id = :idUsuario)");
    $stmt->bindValue(":idEmpresa",$PKEmpresa);
    $stmt->bindValue(":idUsuario",$PKUsuario);
    $stmt->execute();
    $rows = $stmt->rowCount();
    if($rows<=0){
      $row['seguimiento']=0;
    }else{
      $row['seguimiento']=1;
    }
    return $row;
  }

  function validateEstadoRequisicion($idRequisicion){
    $con = new conectar();
    $db = $con->getDb();

    $PKEmpresa = $_SESSION["IDEmpresa"];

    $stmt = $db->prepare("SELECT estatus from requisiciones_compra where empresa_id = :idEmpresa and PKRequisicion = :idRequisicion;");
    $stmt->bindValue(":idEmpresa",$PKEmpresa);
    $stmt->bindValue(":idRequisicion",$idRequisicion);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row;
  }
}

?>