<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera todos los cálculos*/ 

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

$envVariables = GetEvn();
$appUrl = $envVariables['server'];
$empresa = $_SESSION["IDEmpresa"];
$id = $_SESSION["PKUsuario"];

$stmt = $conn->prepare('SELECT com.id as idComision, 
                                com.folio as folio, 
                                com.fecha_registro as fecha_creacion, 
                                e.PKEmpleado as idVendedor, 
                                concat(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) as nombre_vendedor, 
                                com.monto_calculado as monto_calculado, 
                                com.monto_ingresado as monto_ingresado, 
                                com.porcentaje_comision as porcentaje_comision, com.saldo_insoluto as saldo_insoluto, com.estatus as estatus
                        from empleados e 
                            inner join comisiones com on e.PKEmpleado=com.id_empleado 
                            inner join usuarios u on com.id_usuario_registro=u.id
                        where e.empresa_id=:empresa');
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();

$table="";

while (($row = $stmt->fetch()) !== false) {

    $row['folio']='<a style=\"cursor:pointer\" href=\"../comisiones/detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$row['idVendedor'].'\">'.$row['folio'].'</a>';

    $acciones="";
    if($row['estatus']==1){
        $row['estatus']= '<span class=\"left-dot red-dot\">Pendiente de pago</span>';
        $acciones=$acciones.'<a class=\"edit-tabs-371\" href=\"../comisiones/detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$row['idVendedor'].'\" id=\"btnVerCalculo\"><img src=\"../../img/timdesk/ver.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Ver cálculo\"></a>';
        $acciones=$acciones.'<a class=\"edit-tabs-371\" href=\"../comisiones/edit_detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$row['idVendedor'].'\" id=\"btnEditarCalculo\"><img src=\"../../img/timdesk/edit.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Editar cálculo\"></a>';
        $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"btnEliminaCalculo\"><img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Eliminar\" onclick=\"eliminaCalculo('.$row['idComision'].')\"/></a>';
    }else if($row['estatus']==2){
        $row['estatus']= '<span class=\"left-dot green-dot\">Pagado</span>';
        $acciones=$acciones.'<a class=\"edit-tabs-371\" href=\"../comisiones/detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$row['idVendedor'].'\" id=\"btnVerCalculo\"><img src=\"../../img/timdesk/ver.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Ver cálculo\"></a>';
    }else if($row['estatus']==3){
        $acciones=$acciones.'<a class=\"edit-tabs-371\" href=\"../comisiones/detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$row['idVendedor'].'\" id=\"btnVerCalculo\"><img src=\"../../img/timdesk/ver.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Ver cálculo\"></a>';
        $acciones=$acciones.'<a class=\"edit-tabs-371\" href=\"../comisiones/edit_detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$row['idVendedor'].'\" id=\"btnEditarCalculo\"><img src=\"../../img/timdesk/edit.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Editar cálculo\"></a>';
        $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"btnEliminaCalculo\"><img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Eliminar\" onclick=\"eliminaCalculo(\''.$row['idComision'].'\')\"/></a>';
        $row['estatus']= '<span class=\"left-dot gray-dot\">Parcialmente pagado</span>';
    }

    $row['monto_calculado'] = number_format($row['monto_calculado'], 2, '.', ' ');
    $row['monto_ingresado'] = number_format($row['monto_ingresado'], 2, '.', ' ');
    $row['porcentaje_comision'] = number_format($row['porcentaje_comision'] * 100, 2, '.', ' ');
    $row['saldo_insoluto'] = number_format($row['saldo_insoluto'], 2, '.', ' ');

    //llena la tabla en formato json
    $table.= '{"Folio":"'.$row['folio'].'",
        "Fecha":"'.date("d-m-Y", strtotime($row['fecha_creacion'])).'",
        "Vendedor":"'.$row['nombre_vendedor'].'",
        "Monto calculado":"$'.$row['monto_calculado'].'",
        "Monto ingresado":"$'.$row['monto_ingresado'].'",
        "Porcentaje de comision":"'.$row['porcentaje_comision'].'%",
        "Saldo insoluto":"$'.$row['saldo_insoluto'].'",
        "Estado":"'.$row['estatus'].'"},'; 

}

$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}'; 
?>