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
    $all = $conn->prepare("SELECT ncpc.folion_nota,ncpc.id,ncpc.num_serie_nota,ncpc.importe,ncpc.fecha_captura, ncpc.estatus, ncpc.fecha_modifico as f_Cancelacion , ncpc.cliente_id,ncpc.id_Nota_Facturapi,c.NombreComercial,c.PKCliente from notas_cuentas_por_cobrar as ncpc
	inner join clientes as c on c.PKCliente = ncpc.cliente_id where (ncpc.empresa_id = $empresa) and (ncpc.estatus = 2) order by fecha_captura desc;");
    $all->execute();

    
    while (($row = $all->fetch()) !== false) {
        $relateuuids ="";
        $unaRelacionada ="";
        $UUIDR = $conn->prepare("SELECT f.serie, f.folio from facturacion as f inner join notas_cuentas_por_cobrar_has_facturacion as ncf on ncf.facturacion_id = f.id where notas_cuentas_por_cobrar_id =". $row["id"].";");
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
            $relateuuids .= $row2["serie"] .$row2["folio"]. " / ";
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
        $link = '<a id =\"detalle_nota\" href=\"#\" data-id=\"'.$row['id'].'\">'.$row['num_serie_nota']." ".$row['folion_nota'].'</a>';
        $row['fecha_captura'] = date("Y-m-d", strtotime($row['fecha_captura']));
        if($row['f_Cancelacion']){
            $row['f_Cancelacion'] = date("Y-m-d", strtotime($row['f_Cancelacion']));

        }else{
            $row['f_Cancelacion'] = "Indefinida";
        }

        //link para detalle del cliente
        $row['NombreComercial'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['NombreComercial'].'</a>';

        $table.='{"Id":"'.$row['id'].
                '","Folio":"'.$link.
                '","UUID":"'.$relateuuids2.
                '","Cliente":"'.$row['NombreComercial'].
                '","Importe":"'.$row['importe'].
                '","F_Creacion":"'.$row['fecha_captura'].
                '","F_Cancelacion":"'.$row['f_Cancelacion'].
                '","Estado":"'.$estado.
                '","Descargar":""},';
    }
    //print_r($Id_ultimas);
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';

?>