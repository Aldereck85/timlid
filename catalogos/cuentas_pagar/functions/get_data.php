<?php
require_once('../../../include/db-conn.php');

// Optener los datos de tabla de la pantalla de editar
session_start();
$empresa = $_SESSION["IDEmpresa"];
$id = ($_GET['cuenta_id']);
$stmt = $conn->prepare("SELECT
                        dtcpp.id,
                        dtcpp.clave,
                        pd.Nombre,
                        dtcpp.cantidad,
                        dtcpp.precio,
                        dtcpp.descuento,
                        dtcpp.iva,
                        dtcpp.ieps,
                        dtcpp.ieps_monto_fijo,
                        pr.NombreComercial
                        FROM cuentas_por_pagar as cp
                        inner join detalle_cuentas_por_pagar as dtcpp on cp.id = dtcpp.cuenta_por_pagar_id
                        left join proveedores as pr on cp.proveedor_id = pr.PKProveedor
                        inner join productos as pd on dtcpp.clave = pd.ClaveInterna
                        WHERE cp.id= $id and pd.empresa_id = $empresa and cp.estatus_factura!=7;");
$stmt->execute();

/* SELECT cp.id, cp.folio_factura, cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura, dtcpp.clave,dtcpp.cantidad,dtcpp.precio,dtcpp.descuento,dtcpp.iva,dtcpp.ieps,
                    cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial,dtcpp.clave FROM proveedores as pr  
                    left JOIN  cuentas_por_pagar as cp
                    ON cp.proveedor_id = pr.PKProveedor
                    right join detalle_cuentas_por_pagar as dtcpp on cp.id = dtcpp.cuenta_por_pagar_id
                    WHERE cp.id= 115; */


$table="";
while (($row = $stmt->fetch()) !== false) {
        
    /* $enlace = '<a href="editar.php?id='.$row['id'].'>Editar</a>;'; */
    if($row['iva']==""){
        $row['iva']="$0.00";
    }else{
      $row['iva'] = "$" .number_format($row['iva'],2);
    }

    if($row['descuento']==""){
        $row['descuento']="$0.00";
    }else{
      $row['descuento'] = "$" .number_format($row['descuento'],2);
    }

    if($row['ieps']==""){
        $row['ieps']="$0.00";
    }else{
      $row['ieps'] = "$" .number_format($row['ieps'],2);
    }

    if($row['ieps_monto_fijo']==""){
      $row['ieps_monto_fijo']="$0.00";
    }else{
      $row['ieps_monto_fijo'] = "$" .number_format($row['ieps_monto_fijo'],2);
    }
  /* Formateo de campos */
  $row['precio'] = "$" .number_format($row['precio'],2);

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Key":"'.$row['id'].'",
      "Proveedor":"'.$row['NombreComercial'].'",
      "IEPS_Fijo":"'.$row['ieps_monto_fijo'].'",
      "Producto":"'.$row['Nombre'].'",
      "Clave":"'.$row['clave'].'",
      "Cantidad":"'.$row['cantidad'].'",
      "Precio":"'.$row['precio'].'",
      "Descuento":"'.$row['descuento'].'",
      "IVA":"'.$row['iva'].'",
      "IEPS":"'.$row['ieps'].'",
      "Editar":"'.'<div id=\"edit_btnn\"><a id=\"edit_btn\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#modaldcp\" href=\"editar.php?id='.$row['id'].'\" title=\"Editar datos\" > Editar </a></div>'./* '","Acciones":"'.'"<a href="editar.php?id='+ $row['id'].'>Editar</a>;"'. *//* ''.
      $funciones. */'"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>