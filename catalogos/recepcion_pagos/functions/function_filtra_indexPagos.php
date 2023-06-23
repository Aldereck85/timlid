<?php
require_once('../../../include/db-conn.php');
session_start();

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');
//funcion para cambiar estatus del pago con complemento en proceso de cancelacion cuando sea cancelado.
require_once('function_estatus_complementos.php');

$envVariables = GetEvn();
$appUrl = $envVariables['server'];

$empresa = $_SESSION["IDEmpresa"];
$id = $_SESSION["PKUsuario"];
$seleccion=$_REQUEST['seleccion'];
$fecha_desde=$_REQUEST['fecha_desde'];
$fecha_hasta=$_REQUEST['fecha_hasta'];
$cliente="";
$between="";

//actualizamos los estatus de los complementos pendientes de cancelar cuando ya fueron cancelados
$return = update_Status_Complement($empresa);

if($seleccion!="f"){
    $cliente='and tabla.PKCliente = '.$seleccion;
}

if($fecha_desde!="no"){
    if($fecha_hasta=="no"){
        $fecha_hasta=date("Y-m-d");
    }
    $between='and f_pago between "'.$fecha_desde.'" and "'.$fecha_hasta.' 23:59:59"';
}else{
    if($fecha_hasta!="no"){
        $between='and f_pago <= "'.$fecha_hasta.' 23:59:59"';
    }
}

//recuperacion de permisos para el usuario
$query=("select fp.funcion_ver, fp.funcion_editar, fp.funcion_eliminar from funciones_permisos fp 
inner join usuarios u on u.perfil_id=fp.perfil_id where u.id=:id and u.empresa_id=:empresa and fp.pantalla_id = 31;");
$res= $conn->prepare($query);
$res->bindValue(":id",$id);
$res->bindValue(":empresa",$empresa);
$res->execute();

while (($row = $res->fetch()) !== false) {
    $fn_ver=$row['funcion_ver'];
    $fn_editar=$row['funcion_editar'];
    $fn_eliminar=$row['funcion_eliminar'];
}

$res->closeCursor(); 

//recupera datos del os pagos para index
$stmt = $conn->prepare("SELECT * from (
                            SELECT f.id, m.tipo_CuentaCobrar, p.fecha_registro, c.PKCliente, p.identificador_pago as folio, ff.foliosFacturas, c.razon_social as cliente, p.fecha_pago as f_pago, fp.descripcion as forma_pago, f.metodo_pago, cb.nombre as cuenta, p.total, m.Referencia, u.nombre as responsable, facp.estatus as estatus from 
                            pagos p inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
                            inner join cuentas_bancarias_empresa cb on cb.PKCuenta=m.cuenta_destino_id
                            inner join usuarios u on m.FKResponsable = u.id
                            inner join facturacion f on f.id=m.id_factura
                            inner join formas_pago_sat fp on p.forma_pago=fp.id
                            inner join clientes c on f.cliente_id=c.PKCliente
                            inner join (select pp.idpagos, group_concat(' ',fac.serie, fac.folio) as foliosFacturas from facturacion as fac 
                                        inner join movimientos_cuentas_bancarias_empresa mov on mov.id_factura=fac.id
                                        inner join pagos pp on mov.id_pago = pp.idpagos
                                        where pp.estatus=1 and pp.empresa_id=:empresa and mov.tipo_CuentaCobrar = 2 group by pp.idpagos) as ff on ff.idpagos=p.idpagos
                            left join (select * from facturas_pagos where empresa_id=:empresa2 and estatus != 0) as facp on p.identificador_pago=facp.folio_pago 
                            where p.tipo_movimiento=0 and f.empresa_id=:empresa3 and p.estatus=1 and m.tipo_CuentaCobrar = 2 and f.prefactura = 0 group by p.identificador_pago
                            
                            union
                            
                            SELECT vd.PKVentaDirecta as id, m.tipo_CuentaCobrar, p.fecha_registro, c.PKCliente, p.identificador_pago as folio, ff.foliosFacturas, c.razon_social as cliente, p.fecha_pago as f_pago, fp.descripcion as forma_pago, 0, cb.nombre as cuenta, p.total, m.Referencia, u.nombre as responsable, 0 as estatus from 
                            pagos p inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
                            inner join cuentas_bancarias_empresa cb on cb.PKCuenta=m.cuenta_destino_id
                            inner join usuarios u on m.FKResponsable = u.id
                            inner join ventas_directas vd on vd.PKVentaDirecta=m.id_factura and vd.empresa_id !=6
                            inner join formas_pago_sat fp on p.forma_pago=fp.id
                            inner join clientes c on vd.FKCliente=c.PKCliente
                            inner join (select pp.idpagos, group_concat(' ',vd.Referencia) as foliosFacturas from ventas_directas as vd 
                                        inner join movimientos_cuentas_bancarias_empresa mov on mov.id_factura=vd.PKVentaDirecta
                                        inner join pagos pp on mov.id_pago = pp.idpagos
                                        where pp.estatus=1 and pp.empresa_id=:empresa4 and vd.empresa_id !=6 and mov.tipo_CuentaCobrar = 1 group by pp.idpagos) as ff on ff.idpagos=p.idpagos
                            where p.tipo_movimiento=0 and vd.empresa_id=:empresa5 and p.estatus=1 and m.tipo_CuentaCobrar = 1 group by p.identificador_pago
                            ) as tabla where id != 0 $cliente $between order by fecha_registro desc;");

$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":empresa2",$empresa);
$stmt->bindValue(":empresa3", $empresa);
$stmt->bindValue(":empresa4", $empresa);
$stmt->bindValue(":empresa5", $empresa);
$stmt->execute();
$table="";

while (($row = $stmt->fetch()) !== false) {
    //condicion: que para definir la posición del tooltip en "ver"
    if($stmt->rowCount()<2){
        $posicion="left";
    }else{
        $posicion="auto";
    }

    //segun los permisos, genera los botones de acciones
    $acciones="";
    $linkFolio="";
    if($fn_ver==1){
        //$acciones=$acciones.'<a href=\"../recepcion_pagos/detalle_pago.php?idPago='.$row['folio'].'\"><i class=\"fas fa-clipboard-list pointer\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Ver detalle\"></i></a>';
        $linkFolio='<a style=\"cursor:pointer\" href=\"../recepcion_pagos/detalle_pago.php?idPago='.$row['folio'].'\">'.$row['folio'].'</a>';
    }
    /* if($fn_editar==1){
        if($row['metodo_pago']==3){
            if($row['estatus']==1 || $row['estatus']==2){
                $acciones=$acciones.' <a class=\"edit-tabs-371\" id=\"pdf\" ><i class=\"fas fa-file-pdf\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Descargar PDF\" onclick=\"Descarga_pdf(\''.$row['folio'].'\')\"></i></a>';
                $acciones=$acciones.' <a class=\"edit-tabs-371\" id=\"xml\" ><i class=\"fas fa-cloud-download-alt\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Descargar XML\" onclick=\"Descarga_xml(\''.$row['folio'].'\')\"></i></a>';
                if($row['estatus']==1){
                    $acciones=$acciones.' <a class=\"edit-tabs-371\" id=\"cancelaComplemento\" ><i class=\"fas fa-solid fa-ban\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Cancelar Complemento\" onclick=\"cancelaComplemento(\''.$row['folio'].'\')\"></i></a>';
                }
            }else{
                $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"editarMP\" href=\"editarPago.php?folio='.$row['folio'].'\"><img src=\"../../img/timdesk/edit.svg\" width=\"20px\" height=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Editar\"/></a> ';
                $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"facturar\" ><img style=\"cursor:pointer\" src=\"../../img/icons/ICONO FACTURACION-01.svg\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Facturar\" onclick=\"facturaPago(\''.$row['folio'].'\')\"/></a>';
            }
        }else{
            $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"editarMP\" href=\"editarPago.php?folio='.$row['folio'].'\"><img src=\"../../img/timdesk/edit.svg\" width=\"20px\" height=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Editar\"/></a>';
        }
    }
    if($fn_eliminar==1){
        if($row['estatus']!=1 && $row['estatus']!=2){
            $acciones=$acciones.'<a class=\"edit-tabs-371\" id=\"btnEliminaPago\"><img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"eliminaPago(\''.$row['folio'].'\',1)\"/></a>';
        }
    } */

    if($row['estatus']==1){
        $row['estatus']= '<span class=\"left-dot blue-light-dot\">Timbrado</span>';
    }else if($row['estatus']==2){
        $row['estatus']= '<span class=\"left-dot red-light-dot\">En proceso de cancelación</span>';
    }else if($row['metodo_pago']==1 || $row['metodo_pago']==2 || $row['metodo_pago'] == 0){
        $row['estatus']= '<span class=\"left-dot green-dot\">Pagado</span>';
    }else{
        $row['estatus']= '<span class=\"left-dot gray-dot\">No Timbrado</span>';
    }

    //añade una etiqueta segun el estado de la factura
    if($row['metodo_pago']==1){
        $row['metodo_pago']= 'PUE - Pago en Una Exhibición';
    }elseif($row['metodo_pago']==2){
        $row['metodo_pago']= 'PIP - Pago Inicial y Parcialidades';
    }elseif($row['metodo_pago']==3){
        $row['metodo_pago']= 'PPD Pago en Parcialidades o Diferido';
    }elseif ($row['metodo_pago'] == 0) {
        $row['metodo_pago'] = 'Sin Método';
    }else{
        $row['metodo_pago']= 'error de relacion';
    }
    $row['total']=formatoCantidad($row['total'],2);

    //recorta la referencia
    if(strlen($row['Referencia'])>30){
        $row['Referencia']='<a style=\"text-decoration: none; cursor:pointer\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"'.$row['Referencia'].'\">'.substr($row['Referencia'], 0, 20).'</a>';
    }

    $row['cliente'] = str_replace('"', '\"', $row['cliente']);

    //link para detalle del cliente
    $row['cliente'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['cliente'].'</a>';

    //llena la tabla en formato json
    $table.= '{"Folio pago":"'.$linkFolio.
        '","Folio factura":"'.$row['foliosFacturas'].
        '","Cliente":"'.$row['cliente'].
        '","Fecha":"'.date("d-m-Y", strtotime($row['f_pago'])).
        '","Forma de pago":"'.$row['forma_pago'].
        '","Metodo de pago":"'.$row['metodo_pago'].//añadir en la base de datos la columna para registrar el metodo de pago seleccionado.
        '","Cuenta":"'.$row['cuenta'].
        '","Monto total":"$'.$row['total'].
        '","Referencia":"'.$row['Referencia'].
        '","Responsable":"'.$row['responsable'].
        '","Estado":"'.$row['estatus'].
        '","Acciones":""},'; 

} 
$table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';

 ?>