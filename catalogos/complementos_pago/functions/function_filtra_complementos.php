<?php
require_once('../../../include/db-conn.php');
session_start();

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

$envVariables = GetEvn();
$appUrl = $envVariables['server'];

$empresa = $_SESSION["IDEmpresa"];
$seleccion=$_REQUEST['seleccion'];
$fecha_desde=$_REQUEST['fecha_desde'];
$fecha_hasta=$_REQUEST['fecha_hasta'];
$cliente="";
$between="";

if($seleccion!="f"){
    $cliente='and c.PKCliente = '.$seleccion;
}

if($fecha_desde!="no"){
    if($fecha_hasta=="no"){
        $fecha_hasta=date("Y-m-d");
    }
    $between='and fp.fecha_timbrado between "'.$fecha_desde.'" and "'.$fecha_hasta.' 23:59:59"';
}else{
    if($fecha_hasta!="no"){
        $between='and fp.fecha_timbrado <= "'.$fecha_hasta.' 23:59:59"';
    }
}

$stmt = $conn->prepare("SELECT c.PKCliente, c.NombreComercial as cliente, fpa.folio_pago, ffe.foliosFacturas, fpa.folio_complemento, fpa.fecha_timbrado, u.nombre, fpa.estatus, fpa.total_facturado FROM facturas_pagos as fpa
inner join clientes as c on c.PKCliente=fpa.cliente_id
inner join usuarios as u on u.id=fpa.usuario_timbro
inner join (select pp.identificador_pago,fac.empresa_id, group_concat(' ',fac.serie, fac.folio) as foliosFacturas from facturacion as fac 
            inner join movimientos_cuentas_bancarias_empresa mov on mov.id_factura=fac.id
            inner join pagos pp on mov.id_pago = pp.idpagos
            where pp.estatus=1 group by pp.idpagos) as ffe on ffe.empresa_id=fpa.empresa_id
where fpa.empresa_id=$empresa and fpa.estatus=1 and ffe.identificador_pago=fpa.folio_pago $cliente $between order by fecha_timbrado desc;");


$stmt->execute();

//condicion: que para definir la posición del tooltip en "ver"
if($stmt->rowCount()<2){
    $posicion="left";
}else{
    $posicion="auto";
}

$table="";

 //se llena la tabla según la consulta realizada
    
 while (($row = $stmt->fetch()) !== false) {
    $acciones="";
    
            //añade una etiqueta segun el estado de la factura
            if($row['estatus']==1){
                //Ver 
                $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"edit_btn_000\"><i class=\"fas fa-clipboard-list pointer\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" onclick=\"verComplemento(\''.$row['folio_pago'].'\')\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Ver detalle\"></i></a>';
                //descargar
                $acciones=$acciones.' <a class=\"edit-tabs-371\" id=\"pdf\" ><i class=\"fas fa-file-pdf\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Descargar PDF\" onclick=\"Descarga_pdf(\''.$row['folio_pago'].'\')\"></i></a>';
                $acciones=$acciones.' <a class=\"edit-tabs-371\" id=\"xml\" ><i class=\"fas fa-cloud-download-alt\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Descargar XML\" onclick=\"Descarga_xml(\''.$row['folio_pago'].'\')\"></i></a>';
                //Cancelar 
/*                 $acciones=$acciones.'<img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"CandelaComplemento('.$row['folio_pago'].')\"/>';
 */                //Estado
                $row['estatus']= '<div style=\"background:#1cc88a;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>Timbrada</center></div>';
            }elseif($row['estatus']==0){
                $row['estatus']= '<div style=\"background:#6c757d;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>Cancelada</center></div>';
            }
            $row['total_facturado']=number_format($row['total_facturado'],2);
    
                $row['fecha_timbrado']=date("Y-m-d", strtotime($row['fecha_timbrado']));
            
            //link para detalle del cliente
            $row['cliente'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['cliente'].'</a>';

            //llena la tabla en formato json
            $table.= '{"Cliente":"'.$row['cliente'].
                '","Folio pago":"'.$row['folio_pago'].
                '","Folio facturas":"'.$row['foliosFacturas'].
                '","Folio complemento":"'.$row['folio_complemento'].
                '","F de timbrado":"'.$row['fecha_timbrado'].
                '","Responsable":"'.$row['nombre'].
                '","Total":"$'.$row['total_facturado'].
                '","Acciones":"'.$acciones.'"},';
        } 
    
$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';

?>