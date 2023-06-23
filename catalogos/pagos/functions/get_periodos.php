<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

$consulta = $_GET["toDo"];

$toggle;

$stmt;

//Cuantas Vencidas
if($consulta == 0){
    $toggle = 0;
    $stmt = $conn->prepare("SELECT NombreComercial,PKProveedor,DATEDIFF(SYSDATE(), fecha_vencimiento),SYSDATE(),fecha_vencimiento,
	#Suma el Importe cuando desde la fecha de vencimiento ha hoy han pasado menos de 30 dias
   SUM(CASE WHEN (DATEDIFF(SYSDATE(), fecha_vencimiento)) between 0 and 30 THEN importe END) AS '30',
   #Suma el Importe cuando desde la fecha de captura ha hoy han pasado de 31 1 60 dias 
   SUM(CASE WHEN (DATEDIFF(SYSDATE(), fecha_vencimiento)) between 31 and 60 THEN importe END) AS '60',
   SUM(CASE WHEN (DATEDIFF(SYSDATE(), fecha_vencimiento)) between 61 and 90 THEN importe END) AS '90',
   SUM(CASE WHEN (DATEDIFF(SYSDATE(), fecha_vencimiento)) > 90 THEN importe END) AS '+90'
   from cuentas_por_pagar as cp inner join proveedores as pr on cp.proveedor_id = pr.PKProveedor
   Where (DATEDIFF(SYSDATE(), fecha_vencimiento) >= 0) and pr.empresa_id = $empresa and cp.estatus_factura!=7 Group By proveedor_id;");
    
    //Cuentas Al corriente
}else{
    $toggle = 1;
    $stmt = $conn->prepare("SELECT NombreComercial, PKProveedor,DATEDIFF(SYSDATE(), fecha_vencimiento),SYSDATE(),fecha_vencimiento,
#Suma el Importe cuando desde la fecha de captura ha hoy han pasado menos de 30 dias
SUM(CASE WHEN (DATEDIFF(fecha_vencimiento, SYSDATE())) between 0 and 30 THEN importe END) AS '30',
#Suma el Importe cuando desde la fecha de captura ha hoy han pasado de 31 1 60 dias 
SUM(CASE WHEN (DATEDIFF(fecha_vencimiento, SYSDATE())) between 31 and 60 THEN importe END) AS '60',
SUM(CASE WHEN (DATEDIFF(fecha_vencimiento, SYSDATE())) between 61 and 90 THEN importe END) AS '90',
SUM(CASE WHEN (DATEDIFF(fecha_vencimiento, SYSDATE())) > 90 THEN importe END) AS '+90'
from cuentas_por_pagar as cp inner join proveedores as pr on cp.proveedor_id = pr.PKProveedor 
where DATEDIFF(SYSDATE(), fecha_vencimiento) <= 0 and pr.empresa_id = $empresa and cp.estatus_factura!=7 Group By proveedor_id");

}



/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */
$stmt->execute();


/* $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal, importe, fecha_factura, 
fecha_vencimiento,estatus_factura, NombreComercial 
FROM proveedores where Dias_credito between 0 and 30 and NombreComercial = Esteritam");
$stmt->execute(); */


$table="";
while (($row = $stmt->fetch()) !== false) {

    if($row['60']==""){
        $row['60']="$0.00";
        $enlace60 = '<a id=\"edit_btn_30\" class=\"disabled\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=60&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['60'].' </a>';

    }else{
        $row['60']="$".number_format($row['60'],2);
        $enlace60 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=60&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['60'].' </a>';

    }

    if($row['30']==""){
        $row['30']="$0.00";
        $enlace30 = '<a id=\"edit_btn_30\" class=\"disabled\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=30&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['30'].' </a>';
    }else{
        $row['30']="$".number_format($row['30'],2);
        $enlace30 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=30&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['30'].' </a>';

    }

    if($row['90']==""){
        $row['90']="$0.00";
        $enlace90 = '<a id=\"edit_btn_30\" class=\"disabled\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=90&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['90'].' </a>';
        
    }else{
        $row['90']="$".number_format($row['90'],2);
        $enlace90 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=90&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['90'].' </a>';

    }

    if($row['+90']==""){
        $row['+90']="$0.00";
        $enlace00 = '<a id=\"edit_btn_30\" class=\"disabled\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=00&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['+90'].' </a>';

    }else{
        $row['+90']="$".number_format($row['+90'],2);
        $enlace00 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_pagar/cuentas_Proveedor.php?av='.$toggle.'&periodo=00&id='.$row['PKProveedor'].'\" title=\"Editar datos\" > '.$row['+90'].' </a>';

    }

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
        '","Id":"'.$row['PKProveedor'].
        '","De 0-30 Dias":"'.$enlace30.
        '","De 31-60 Dias":"'.$enlace60.
        '","De 61-60 Dias":"'.$enlace90.
        '","Mas de 90 Dias":"'.$enlace00.'"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>