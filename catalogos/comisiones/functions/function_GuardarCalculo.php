<?php
session_start();
require_once('../../../include/db-conn.php');
/*Guarda un nuevo calculo de comisiones*/ 

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
$vendedor = $_REQUEST["vendedor"];
$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$porcentaje = $_REQUEST["porcentaje"];
$porcentaje = $porcentaje/100;
$monto_calculado = $_REQUEST["monto_calculado"];
$monto_ingresado = $_REQUEST["monto_ingresado"];
$facturas_seleccionadas = $_REQUEST["facturas_seleccionadas"];


try{
    $conn->beginTransaction();
    //inserciones a la base de datos
    
   /*  $query=sprintf('CALL spc_Tabla_Comisiones(?,?,?,?,?,?,?,?)');
    $stmt = $conn->prepare($query);
    $stmt->execute(array($fecha_desde,$fecha_hasta,$id,$vendedor, $porcentaje,$monto_calculado,$monto_ingresado,$empresa));
    $ultimoID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $idComision=$ultimoID[0]['ultimoID']; */

    $query=sprintf('SELECT CONVERT(SUBSTRING(MAX(c.folio),3,6), UNSIGNED INTEGER) as folioMax
                    FROM comisiones c 
                        inner join empleados e on e.PKEmpleado=c.id_empleado 
                    WHERE e.empresa_id=? ORDER BY c.folio;');
    $stmt = $conn->prepare($query);
    $stmt->execute(array($empresa));
    $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(intval($respuesta[0]['folioMax']) > 0){
        $aux = $respuesta[0]['folioMax'];
        $aux ++;
        $nuevoCodigo = str_pad(strval($aux), 6, '0', STR_PAD_LEFT);
        $nuevoCodigo = str_pad($nuevoCodigo, 8, 'PC', STR_PAD_LEFT);
    }else{
        $nuevoCodigo = "PC000001";
    }

    //inserción de cabecera de comisión
    $query=sprintf('INSERT INTO comisiones 
                        (folio, 
                        fecha_ini, 
                        fecha_fin, 
                        estatus, 
                        id_usuario_registro, 
                        id_empleado, 
                        porcentaje_comision, 
                        monto_calculado, 
                        monto_ingresado, 
                        id_ultimo_usuario_modificacion, 
                        saldo_insoluto, 
                        id_empresa)
                    VALUES (:nuevo_folio, 
                        :_fecha_ini, 
                        :_fecha_fin, 
                        1, 
                        :_id_usuario, 
                        :_id_empleado, 
                        :_porcentaje_comision, 
                        :_monto_calculado, 
                        :_monto_ingresado, 
                        :_id_usuario2, 
                        :_monto_ingresado2, 
                        :_id_empresa);');
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':nuevo_folio',$nuevoCodigo);
    $stmt->bindValue(':_fecha_ini',$fecha_desde);
    $stmt->bindValue(':_fecha_fin',$fecha_hasta);
    $stmt->bindValue(':_id_usuario',$id);
    $stmt->bindValue(':_id_empleado',$vendedor);
    $stmt->bindValue(':_porcentaje_comision',$porcentaje);
    $stmt->bindValue(':_monto_calculado',$monto_calculado);
    $stmt->bindValue(':_monto_ingresado',$monto_ingresado);
    $stmt->bindValue(':_id_usuario2',$id);
    $stmt->bindValue(':_monto_ingresado2',$monto_ingresado);
    $stmt->bindValue(':_id_empresa',$empresa);
    $stmt->execute();

    $idComision = $conn->lastInsertId();

    //inserción en la tabla detalle
    foreach ($facturas_seleccionadas as $fac => $f){

        //inserta detalle de factura o venta según sea el caso
        if($f['tipoDoc'] == "1"){
            $stmt2 = $conn->prepare('INSERT INTO detalle_comision_factura (id_comision,id_factura,monto_comisionado) 
            VALUES (:idcomision,:idfac,:montoCal);');
            $stmt2->execute(array(":idcomision" => $idComision,":idfac" => $f['idFactura'],":montoCal" => $f['MontoCom']));
            $aux = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else if($f['tipoDoc'] == "2"){
            $stmt2 = $conn->prepare('INSERT INTO detalle_comision_venta (id_comision,id_venta,monto_comisionado) 
            VALUES (:idcomision,:idVenta,:montoCal);');
            $stmt2->execute(array(":idcomision" => $idComision,":idVenta" => $f['idFactura'],":montoCal" => $f['MontoCom']));
            $aux = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    $conn->commit();
    $data['result']=1;
}catch(Exception $e){
    //$data['estatus']='err';
    $data['result']=$e;
    $conn->rollBack();
    }

echo json_encode($data); 
?>