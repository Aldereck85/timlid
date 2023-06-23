<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];

$stmt = $conn->prepare("SELECT dnpe.id,rcp.concepto_nomina as concepto, rtp.clave, tp.codigo ,1 as tipo, dnpe.importe, dnpe.importe_exento, dnpe.exento, dnpe.tipo_concepto, ne.estadoTimbrado, n.autorizada FROM nomina_empleado as ne
INNER JOIN nomina as n ON n.id = ne.FKNomina 
INNER JOIN detalle_nomina_percepcion_empleado as dnpe ON dnpe.nomina_empleado_id = ne.PKNomina 
INNER JOIN tipo_percepcion as tp ON tp.id = dnpe.relacion_tipo_percepcion_id
INNER JOIN relacion_concepto_percepcion as rcp ON dnpe.relacion_concepto_percepcion_id = rcp.id AND rcp.empresa_id = ".$_SESSION['IDEmpresa']."
INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." WHERE ne.FKEmpleado = :idEmpleado AND ne.FKNomina = :idNomina");
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->bindValue(":idNomina", $idNomina);
$stmt->execute();
$nominaPercepciones = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT dnde.id,rcd.concepto_nomina as concepto, rtd.clave, td.codigo ,2 as tipo, dnde.importe, dnde.importe_exento, dnde.exento, dnde.tipo_concepto, ne.estadoTimbrado, n.autorizada FROM nomina_empleado as ne
INNER JOIN nomina as n ON n.id = ne.FKNomina 
INNER JOIN detalle_nomina_deduccion_empleado as dnde ON dnde.nomina_empleado_id = ne.PKNomina 
INNER JOIN tipo_deduccion as td ON td.id = dnde.relacion_tipo_deduccion_id
INNER JOIN relacion_concepto_deduccion as rcd ON dnde.relacion_concepto_deduccion_id = rcd.id AND rcd.empresa_id = ".$_SESSION['IDEmpresa']."
INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE ne.FKEmpleado = :idEmpleado2 AND ne.FKNomina = :idNomina2 ");
$stmt->bindValue(":idEmpleado2", $idEmpleado);
$stmt->bindValue(":idNomina2", $idNomina);
$stmt->execute();
$nominaDeducciones = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT dopne.otros_pagos_id, dopne.id, rcop.concepto_nomina as concepto, 'A' as clave, op.codigo ,3 as tipo, dopne.importe, '0' as importe_exento, '0' as exento, '110' as tipo_concepto, ne.estadoTimbrado, dopne.edicion, n.autorizada FROM nomina_empleado as ne 
INNER JOIN nomina as n ON n.id = ne.FKNomina 
INNER JOIN detalle_otros_pagos_nomina_empleado as dopne ON dopne.nomina_empleado_id = ne.PKNomina 
INNER JOIN otros_pagos as op ON op.id = dopne.otros_pagos_id 
INNER JOIN relacion_concepto_otros_pagos as rcop ON dopne.relacion_concepto_otros_pagos_id = rcop.id AND rcop.empresa_id = ".$_SESSION['IDEmpresa']."
WHERE ne.FKEmpleado = :idEmpleado3 AND ne.FKNomina = :idNomina3  ");
$stmt->bindValue(":idEmpleado3", $idEmpleado);
$stmt->bindValue(":idNomina3", $idNomina);
$stmt->execute();
$nominaOtrosPagos = $stmt->fetchAll();

$table = "";

//PERCEPCIONES
$table .= '{"concepto":"' . ''.'","importe gravado":"'.'<b>PERCEPCIONES</b>'.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';

$total_percepciones = 0.00; $total_percepciones_gravado = 0.00; $total_percepciones_exento = 0.00;
foreach ($nominaPercepciones as $np) {

    if($np['estadoTimbrado'] == 0 || $np['estadoTimbrado'] == 2){

        if($np['tipo_concepto'] == '110' || $np['tipo_concepto'] == 9){
            $editar = '';
        }
        elseif($np['tipo_concepto'] == 6){
            $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $np['id'] .',' . $np['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> ';
        }
        else{
            $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $np['id'] .',' . $np['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> <img class=\"btnEdit\"  onclick=\"cargarDetalleNominaEmpleado(' . $np['id'] .',' . $np['tipo'] .');\" src=\"../../img/icons/editar.svg\" title=\"Nomina\" alt=\"Nomina\" >';
        }
    }
    else{
        $editar = '';
    }

    if($np['autorizada'] == 1){
        $editar = '';
    }


    if($np['tipo_concepto'] == 2){
        if($np['tipo'] == 1){
            if($np['exento'] == 1){
                $exento = "<input type='checkbox' class='cbxCambio' id='cbxCambio_".$np['id']."' value='".$np['id']."' checked>";
            }
            if($np['exento'] == 0){
                $exento = "<input type='checkbox' class='cbxCambio' id='cbxCambio_".$np['id']."' value='".$np['id']."'>";
            }
        }
        else{
            $exento = "";
        }
    }
    else{
        $exento = "";
    }


    if($np['tipo_concepto'] == '110'){
        $concepto = $np['codigo'] . " - " . $np['concepto']; 
    }
    elseif($np['tipo_concepto'] == 3){
        $concepto = $np['clave'] . " - " . $np['codigo'] . " - " . $np['concepto'].' (Sencillas)'; 
    }
    elseif($np['tipo_concepto']== 7){
        $concepto = $np['clave'] . " - " . $np['codigo'] . " - " . $np['concepto'].' (Dobles)'; 
    }
    elseif($np['tipo_concepto'] == 8){
        $concepto = $np['clave'] . " - " . $np['codigo'] . " - " . $np['concepto'].' (Triples)'; 
    }
    else{
        $concepto = $np['clave'] . " - " . $np['codigo'] . " - " . $np['concepto']; 
    }
    

    $table .= '{"concepto":"' . $concepto .$editar.'","importe gravado":"' . number_format($np['importe'],2) . '","importe exento":"' . number_format($np['importe_exento'],2) . '","exento":"' . $exento . '","total":"' . "" .'"},';

    $total_percepciones = $total_percepciones + $np['importe'] + $np['importe_exento']; 
    $total_percepciones_gravado = $total_percepciones_gravado + $np['importe']; 
    $total_percepciones_exento = $total_percepciones_exento + $np['importe_exento'];

}
    $table .= '{"concepto":"' . '<b>TOTAL PERCEPCIONES</b>'.'","importe gravado":"'.'<b>'.number_format($total_percepciones_gravado,2).'</b>'.'","importe exento":"'.'<b>'.number_format($total_percepciones_exento,2).'</b>'.'","exento":"'.''.'","total":"'.'<b>'.number_format($total_percepciones,2).'</b>'.'"},';

//DEDUCCIONES
$table .= '{"concepto":"' . ''.'","importe gravado":"'.''.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';
$table .= '{"concepto":"' . ''.'","importe gravado":"'.'<b>DEDUCCIONES</b>'.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';

$total_deducciones = 0.00; $total_deducciones_gravado = 0.00; $total_deducciones_exento = 0.00;

if(count($nominaDeducciones) > 0){

    foreach ($nominaDeducciones as $nd) {

        if($nd['estadoTimbrado'] == 0 || $nd['estadoTimbrado'] == 2){


            if($nd['tipo_concepto'] == '110' || $nd['tipo_concepto'] == 9){
                $editar = '';
            }
            elseif($nd['tipo_concepto'] == 6){
                $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $nd['id'] .',' . $nd['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> ';
            }
            else{

                //para fonacot e infonavit
                if($nd['tipo_concepto'] == 12 || $nd['tipo_concepto'] == 13 || $nd['tipo_concepto'] == 14){

                    $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $nd['id'] .',' . $nd['tipo_concepto'] .');\" src=\"../../img/timdesk/delete.svg\"> <img class=\"btnEdit\"  onclick=\"cargarDetalleNominaEmpleado(' . $nd['id'] .',' . $nd['tipo'] .');\" src=\"../../img/icons/editar.svg\" title=\"Nomina\" alt=\"Nomina\" >';
                }
                else{
                    $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $nd['id'] .',' . $nd['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> <img class=\"btnEdit\"  onclick=\"cargarDetalleNominaEmpleado(' . $nd['id'] .',' . $nd['tipo'] .');\" src=\"../../img/icons/editar.svg\" title=\"Nomina\" alt=\"Nomina\" >';
                }
                
            }
        }
        else{
            $editar = '';
        }

        if($nd['autorizada'] == 1){
            $editar = '';
        }

        if($nd['tipo_concepto'] == 2){
            if($nd['tipo'] == 1){
                if($nd['exento'] == 1){
                    $exento = "<input type='checkbox' class='cbxCambio' id='cbxCambio_".$nd['id']."' value='".$nd['id']."' checked>";
                }
                if($nd['exento'] == 0){
                    $exento = "<input type='checkbox' class='cbxCambio' id='cbxCambio_".$nd['id']."' value='".$nd['id']."'>";
                }
            }
            else{
                $exento = "";
            }
        }
        else{
            $exento = "";
        }


        if($nd['tipo_concepto'] == '110'){
            $concepto = $nd['codigo'] . " - " . $nd['concepto']; 
        }
        elseif($nd['tipo_concepto'] == 3){
            $concepto = $nd['clave'] . " - " . $nd['codigo'] . " - " . $nd['concepto'].' (Sencillas)'; 
        }
        elseif($nd['tipo_concepto']== 7){
            $concepto = $nd['clave'] . " - " . $nd['codigo'] . " - " . $nd['concepto'].' (Dobles)'; 
        }
        elseif($nd['tipo_concepto'] == 8){
            $concepto = $nd['clave'] . " - " . $nd['codigo'] . " - " . $nd['concepto'].' (Triples)'; 
        }
        else{
            $concepto = $nd['clave'] . " - " . $nd['codigo'] . " - " . $nd['concepto']; 
        }
        

        $table .= '{"concepto":"' . $concepto .$editar.'","importe gravado":"' . number_format($nd['importe'],2) . '","importe exento":"' . number_format($nd['importe_exento'],2) . '","exento":"' . $exento . '","total":"' . "" .'"},';

         $total_deducciones = $total_deducciones + $nd['importe'] + $nd['importe_exento']; 
         $total_deducciones_gravado = $total_deducciones_gravado + $nd['importe']; 
         $total_deducciones_exento = $total_deducciones_exento + $nd['importe_exento'];

    }

    $table .= '{"concepto":"' . '<b>TOTAL DEDUCCIONES</b>'.'","importe gravado":"'.'<b>'.number_format($total_deducciones_gravado,2).'</b>'.'","importe exento":"'.'<b>'.number_format($total_deducciones_exento,2).'</b>'.'","exento":"'.''.'","total":"'.'<b>'.number_format($total_deducciones,2).'</b>'.'"},';

}
else{
     $table .= '{"concepto":"' . ''.'","importe gravado":"'.'Sin deducciones'.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';
}

//OTROS PAGOS
$table .= '{"concepto":"' . ''.'","importe gravado":"'.''.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';
$table .= '{"concepto":"' . ''.'","importe gravado":"'.'<b>OTROS PAGOS</b>'.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';

$total_otros_pagos = 0.00;

if(count($nominaOtrosPagos) > 0){

    foreach ($nominaOtrosPagos as $nop) {

        if($nop['estadoTimbrado'] == 0 || $nop['estadoTimbrado'] == 2){

            if($nop['tipo_concepto'] == '110' || $nop['tipo_concepto'] == 9){

                if($nop['edicion'] == 1){
                    $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $nop['id'] .',' . $nop['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> <img class=\"btnEdit\"  onclick=\"cargarDetalleNominaEmpleado(' . $nop['id'] .',' . $nop['tipo'] .');\" src=\"../../img/icons/editar.svg\" title=\"Nomina\" alt=\"Nomina\" >';
                }
                else{
                    $editar = '';
                }
            }
            elseif($nop['tipo_concepto'] == 6){
                $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $nop['id'] .',' . $nop['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> ';
            }
            else{
                $editar = '<img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_DetalleNomina\" onclick=\"eliminarDetalleNomina(' . $nop['id'] .',' . $nop['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> <img class=\"btnEdit\"  onclick=\"cargarDetalleNominaEmpleado(' . $nop['id'] .',' . $nop['tipo'] .');\" src=\"../../img/icons/editar.svg\" title=\"Nomina\" alt=\"Nomina\" >';
            }
        }
        else{
            $editar = '';
        }

        if($nop['autorizada'] == 1){
            $editar = '';
        }


        $exento = "";

        if($nop['tipo_concepto'] == '110'){
            $concepto = $nop['codigo'] . " - " . $nop['concepto']; 
        }
        elseif($nop['tipo_concepto'] == 3){
            $concepto = $nop['clave'] . " - " . $nop['codigo'] . " - " . $nop['concepto'].' (Sencillas)'; 
        }
        elseif($nop['tipo_concepto']== 7){
            $concepto = $nop['clave'] . " - " . $nop['codigo'] . " - " . $nop['concepto'].' (Dobles)'; 
        }
        elseif($nop['tipo_concepto'] == 8){
            $concepto = $nop['clave'] . " - " . $nop['codigo'] . " - " . $nop['concepto'].' (Triples)'; 
        }
        else{
            $concepto = $nop['clave'] . " - " . $nop['codigo'] . " - " . $nop['concepto']; 
        }
        
        
        

         if($nop['otros_pagos_id'] != 3){

            $table .= '{"concepto":"' . $concepto .$editar.'","importe gravado":"' . number_format($nop['importe'],2) . '","importe exento":"' . number_format($nop['importe_exento'],2) . '","exento":"' . $exento . '","total":"' . "" .'"},';

            $total_otros_pagos = $total_otros_pagos + $nop['importe'];
         }
         else{
            $table .= '{"concepto":"' . $concepto .$editar.'","importe gravado":"0.00","importe exento":"' . number_format($nop['importe'],2) . '","exento":"' . $exento . '","total":"' . "" .'"},';
         }

    }

    $table .= '{"concepto":"' . '<b>TOTAL OTROS PAGOS</b>'.'","importe gravado":"'.''.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.'<b>'.number_format($total_otros_pagos,2).'</b>'.'"},';

}
else{
     $table .= '{"concepto":"' . ''.'","importe gravado":"'.'Sin otros pagos'.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';
}



$table .= '{"concepto":"' . ''.'","importe gravado":"'.''.'","importe exento":"'.''.'","exento":"'.''.'","total":"'.''.'"},';

$stmt = $conn->prepare("SELECT ISR, SAE, cuotaIMSS, DescuentoInfonavit, Total, TotalNeto FROM nomina_empleado WHERE FKEmpleado = :idEmpleado AND FKNomina = :fknomina");
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->bindValue(":fknomina", $idNomina);
$stmt->execute();
$impTotal = $stmt->fetch();

$table .= '{"concepto":"'."<b>SUBTOTAL</b>".'","importe gravado":"'."".'","importe exento":"'."".'","exento":"'."".'","total":"'.'<b>'.number_format($impTotal['Total'],2).'<b>'.'"},';


if($impTotal['ISR'] == 0.00){
    $table .= '{"concepto":"'."<b>SAE a pagar <button type='button' id='mostrarISR' class='circuloImpuestos' title='Mostrar el calculo del ISR'>?</button></b>".'","importe gravado":"'."".'","importe exento":"'."".'","exento":"'."".'","total":"'.number_format($impTotal['SAE'],2).'"},';
}
else{
    $table .= '{"concepto":"'."<b>ISR <button type='button' id='mostrarISR' class='circuloImpuestos' title='Mostrar el calculo del ISR'>?</button></b>".'","importe gravado":"'."".'","importe exento":"'."".'","exento":"'."".'","total":"'.number_format($impTotal['ISR'],2).'"},';
}

$table .= '{"concepto":"'."<b>Cuota IMSS <button type='button' id='mostrarIMSS'  class='circuloImpuestos' title='Mostrar el calculo del ISR'>?</button></b>".'","importe gravado":"'."".'","importe exento":"'."".'","exento":"'."".'","total":"'.number_format($impTotal['cuotaIMSS'],2).'"},';

$table .= '{"concepto":"'."<b>Descuento Infonavit</b>".'","importe gravado":"'."".'","importe exento":"'."".'","exento":"'."".'","total":"'.number_format($impTotal['DescuentoInfonavit'],2).'"},';

$table .= '{"concepto":"'."<b>Total neto</b>".'","importe gravado":"'."".'","importe exento":"'."".'","exento":"'."".'","total":"'.'<b>'.number_format($impTotal['TotalNeto'],2).'<b>'.'"},';


$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';