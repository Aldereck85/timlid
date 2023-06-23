<?php
/////////
///Consulta las Notas de credito para el index.php
/////////
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
$botonCancelar;
$table="";
    $all = $conn->prepare("SELECT ncpc.folion_nota,ncpc.id,ncpc.num_serie_nota,FORMAT(ncpc.importe, 2) as importe,ncpc.fecha_captura, ncpc.estatus, ncpc.cliente_id,ncpc.id_Nota_Facturapi,c.NombreComercial,c.PKCliente, ncpc.tipo_nc from notas_cuentas_por_cobrar as ncpc
	inner join clientes as c on c.PKCliente = ncpc.cliente_id where (ncpc.empresa_id = $empresa) and (ncpc.estatus = 1 OR  ncpc.estatus = 2) order by fecha_captura desc;");
    $all->execute();
    $array = $all->fetchAll();

    
    //while (($row = $all->fetch()) !== false) {
    foreach($array as $row){
        $relateuuids ="";
        $unaRelacionada ="";
        if($row["tipo_nc"] == 1){
            $UUIDR = $conn->prepare("SELECT f.serie, f.folio from facturacion as f inner join notas_cuentas_por_cobrar_has_facturacion as ncf on ncf.facturacion_id = f.id where notas_cuentas_por_cobrar_id =". $row["id"].";");
        }else{
            $UUIDR = $conn->prepare("SELECT vd.Referencia from ventas_directas as vd inner join notas_cuentas_por_cobrar_has_ventas as ncv on ncv.venta_id = vd.PKVentaDirecta where ncv.notaCredito_id =". $row["id"].";");
        }
        $UUIDR->execute();
        if($all->rowCount()<2){
            $posicion="left";
        }else{
            $posicion="top";
        }
        $contaRelates= 0;
        while(($row2 = $UUIDR->fetch()) !== false){
        /* print_r($row2); */
            $contaRelates++;
            if($row["tipo_nc"] == 1){
                $relateuuids .= $row2["serie"] .$row2["folio"]. " / ";
            }else{
                $relateuuids .= $row2["Referencia"]. " / ";
            }
        }
            $relateuuids = substr($relateuuids, 0,-2);
        ///Si el comentario es largo, mas de 30: Corta el comentario y pone ...
        $relateuuids2 = (strlen($relateuuids)>37)? (substr($relateuuids,0,37).". . ."):$relateuuids;

        $relateuuids2 = '<a disable data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"'.$relateuuids.'\">'.$relateuuids2."</a>";
        
        $botonCancelar = '<i class=\"fas fa-ban pointer\" style=\"cursor:pointer; padding-right: 5px;\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" title=\"Cancelar Nota\" onclick=\"showModal('.$row['id'].','.$row['PKCliente'].')\"></i>';
        $botonDescXML = '<i class=\"fas fa-cloud-download-alt\" style=\"cursor:pointer; padding-right: 5px;\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Descargar XML\" onclick=\"Descarga_xml(\''.$row['id_Nota_Facturapi'].'\')\"></i>';
        if($row['estatus']==1){
            $estado = '<span class=\"left-dot green-dot\">Activa</span>';
            //$bDescargar = '<i class=\"fas fa-file-pdf pointer\" style=\"cursor:pointer; padding-right: 5px;\"  width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" title=\"Descargar PDF\" onclick=\"descargarF(\''.$row['id_Nota_Facturapi'].'\')\"></i>'.$botonDescXML.$botonCancelar;

        }else{
            $estado = '<span class=\"left-dot orange-dot\">Cancelada</span>';
            //$bDescargar = '<i class=\"fas fa-file-pdf pointer\" style=\"cursor:pointer; padding-right: 5px;\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" title=\"Descargar PDF\" onclick=\"descargarF(\''.$row['id_Nota_Facturapi'].'\')\"></i>'.$botonDescXML;
        }
        
        if($row["tipo_nc"] == 1){
            $link = '<a id =\"detalle_nota\" href=\"detalle_nota.php?idNota='.$row['id'].'\" data-id=\"'.$row['id'].'\">'.$row['num_serie_nota']." ".$row['folion_nota'].'</a>';
        }else{
            $link = '<a id =\"detalle_nota\" href=\"detalle_notaVenta.php?idNota='.$row['id'].'\" data-id=\"'.$row['id'].'\">'.$row['num_serie_nota']." ".$row['folion_nota'].'</a>';
        }
        
        $row['fecha_captura'] = date("Y-m-d", strtotime($row['fecha_captura']));

        //link para detalle del cliente
        $row['NombreComercial'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['NombreComercial'].'</a>';

        $table.='{"Id":"'.$row['id'].
                '","Folio":"'.$link.
                '","UUID":"'.$relateuuids2.
                '","Cliente":"'.$row['NombreComercial'].
                '","Importe":"'.'$'.$row['importe'].
                '","F_Creacion":"'.$row['fecha_captura'].
                '","Estado":"'.$estado.'"},';
    }
    //print_r($Id_ultimas);
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';

?>