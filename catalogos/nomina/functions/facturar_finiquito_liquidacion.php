<?php
use Facturapi\Facturapi;
require_once '../../../vendor/facturapi/facturapi-php/src/Facturapi.php';

$ruta_api = "../../../";
include $ruta_api . "include/functions_api_facturation.php";
$api = new API();

session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];
$respuesta = new stdClass();

if(empty($_SESSION['token_ld10d'])) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

function calcularDiferencia($fechaIni, $fechaFin){
    $d1 = new DateTime($fechaIni);//new DateTime("2018-01-10 00:00:00");
    $d2 = new DateTime($fechaFin);//new DateTime("2019-05-18 01:23:45");
    $interval = $d1->diff($d2);
    /*$diffInSeconds = $interval->s; //45
    $diffInMinutes = $interval->i; //23
    $diffInHours   = $interval->h; //8*/
    $diffInDays    = $interval->d; //21
    $diffInMonths  = $interval->m; //4
    $diffInYears   = $interval->y; //1

    $diferencia = "P";

    if($diffInYears > 0){
        $diferencia = $diferencia.$diffInYears."Y";
    }
    /*else{
        $diferencia = $diferencia."Y";
    }*/
    if($diffInMonths > 0){
        $diferencia = $diferencia.$diffInMonths."M";
    } 
    if($diffInDays > 0){
        $diferencia = $diferencia.$diffInDays."D";
    }
    else{
        $diferencia = $diferencia."0"."D";
    }
    return $diferencia;
}

date_default_timezone_set('America/Mexico_City');
require_once '../../../include/db-conn.php';

$respuesta->nomina_completa = 0;
$respuesta->estatus_email = "";
//informacion que necesita recibir
$idFiniquito = $_POST['idFiniquito'];
$tipoMovimiento = $_POST['tipoMovimiento'];
$metodo_pago_id = $_POST['metodo_pago_id'];
$idEmpresa = $_SESSION['IDEmpresa'];

if(isset($_POST['idRelacionados'])){
    $ids = $_POST['idRelacionados'];
}
else{
    $ids = "";
}


if($ids != ""){
    $ids_finiquito = array();
    $cont1 = 0;
    foreach($ids as $i){

        $separacion = explode("_", $i);

        if($separacion[0] == 1){
            $ids_finiquito[$cont1] = $separacion[1];
            $cont1++;
        }

    }

}
else{
    $ids_finiquito = "";
}

if($ids != ""){
    $ids_liquidacion = array();
    $cont2 = 0;
    foreach($ids as $i){

        $separacion = explode("_", $i);

        if($separacion[0] == 2){
            $ids_liquidacion[$cont2] = $separacion[1];
            $cont2++;
        }

    }

}
else{
    $ids_liquidacion = "";
}

$stmt = $conn->prepare("SELECT f.*, l.indemnizacion, l.indemnizacion_exento, l.indemnizacion_gravado, l.anios_servicio, l.prima_antiguedad, l.isr_liquidacion, l.sae_liquidacion, l.estadoTimbradoLiquidacion, l.idFacturaLiquidacion, l.fechaTimbradoLiquidacion, e.PKEmpleado, e.Nombres, e.PrimerApellido, e.SegundoApellido, e.CURP, e.CP, dme.NSS as num_seguridad_social, e.RFC, e.idtimbrado, e.email, dle.FechaIngreso, CONCAT(dle.FechaIngreso,'T06:00:00.000Z') as fecha_inicio_rel_laboral, f.fecha_salida, tc.codigo as tipo_contrato, tj.codigo as tipo_jornada, tr.codigo as tipo_regimen, e.id_empleado as num_empleado, p.puesto, rp.codigo as riesgo_puesto,  pp.Codigo as periodicidad_pago, ef.Codigo as clave_ent_fed, dle.Sueldo, crf.clave as clave_regimen_fiscal, mp.descripcion as metodo_pago FROM finiquito as f INNER JOIN empleados as e ON e.PKEmpleado = f.empleado_id AND e.empresa_id = :empresa_id LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado LEFT JOIN tipo_contrato as tc ON tc.id = dle.FKTipoContrato LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN tipo_jornada as tj ON tj.id = t.tipo_jornada_id LEFT JOIN tipo_regimen as tr ON tr.id = dle.FKRegimen LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN riesgo_puesto as rp ON rp.id = dle.FKRiesgoPuesto LEFT JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN estados_federativos as ef ON ef.PKEstado = e.FKEstado LEFT JOIN liquidacion as l ON l.finiquito_id = f.id LEFT JOIN formas_pago_sat as mp ON mp.id = f.metodo_pago_id LEFT JOIN claves_regimen_fiscal as crf ON crf.id = e.claves_regimen_fiscal_id WHERE f.id = :idFiniquito");
$stmt->bindValue(":empresa_id",$idEmpresa);
$stmt->bindValue(":idFiniquito",$idFiniquito);
$stmt->execute();
$datos_empleado = $stmt->fetch();
$idEmpleado = $datos_empleado['PKEmpleado']; //106
$fecha_pago = $datos_empleado['fecha_salida'];  
$antiguedad = calcularDiferencia($datos_empleado['FechaIngreso']." 06:00:00", $fecha_pago." 06:00:00");
$respuesta->metodo_pago_act = $datos_empleado['metodo_pago'];

if(trim($datos_empleado['CURP']) == ""){
    $respuesta->estatus = "fallo-general";
    $respuesta->mensaje = "Ingresa un CURP al empleado para poder timbrar.";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

if(trim($datos_empleado['RFC']) == ""){
    $respuesta->estatus = "fallo-general";
    $respuesta->mensaje = "Ingresa un RFC al empleado para poder timbrar.";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

if(trim($datos_empleado['num_seguridad_social']) == ""){
    $respuesta->estatus = "fallo-general";
    $respuesta->mensaje = "Ingresa el número de seguridad social al empleado para poder timbrar.";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

if(trim($datos_empleado['idtimbrado']) == ""){
    $respuesta->estatus = "fallo-general";
    $respuesta->mensaje = "El RFC del empleado no esta dado de alta, ingresalo en empleados.";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

if(trim($datos_empleado['clave_regimen_fiscal']) == ""){
    $respuesta->estatus = "fallo-general";
    $respuesta->mensaje = "Ingresa la clave del regimen fiscal del empleado para poder timbrar.";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

$query = sprintf("select key_company_api key_company,key_user_company_api key_user, RFC, registro_patronal, curp from empresas where PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$_SESSION['IDEmpresa']);
$stmt->execute();
$key_company_api = $stmt->fetchAll();

$facturapi = new Facturapi($key_company_api[0]['key_company']);

$emisor = new stdClass();
$emisor->registro_patronal = $key_company_api[0]['registro_patronal'];
//$emisor->rfc = $key_company_api[0]['RFC'];

if(strlen(trim($key_company_api[0]['RFC'])) == 13){
    $emisor->curp =  $key_company_api[0]['curp'];
}

//echo "RFC ".$key_company_api[0]['RFC'];

//*********CALCULAR SDI*******////////
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");
$datosSBC = getSBCNomina($idEmpleado, $fecha_pago);
$SDI = $datosSBC[3];
$SBC = $datosSBC[2];
//*********FIN CALCULAR SDI*******////////

$receptor = new stdClass();
$receptor->curp =  $datos_empleado['CURP'];
$receptor->num_seguridad_social =  $datos_empleado['num_seguridad_social'];
$receptor->fecha_inicio_rel_laboral =  $datos_empleado['fecha_inicio_rel_laboral'];
$receptor->tipo_contrato =  $datos_empleado['tipo_contrato'];
$receptor->tipo_jornada =  $datos_empleado['tipo_jornada'];  //opcional
$receptor->tipo_regimen =  $datos_empleado['tipo_regimen'];
$receptor->num_empleado =  strval($datos_empleado['num_empleado']);
$receptor->puesto =  $datos_empleado['puesto']; //opcional
$receptor->riesgo_puesto =  $datos_empleado['riesgo_puesto'];
$receptor->clave_ent_fed =  $datos_empleado['clave_ent_fed'];  
$receptor->salario_diario_integrado = $SDI;
$receptor->salario_base_cot_apor = $SBC; //actualizar cuando se calculen el salario diario y el salario diario integrado
//$receptor->fecha_final_pago =  $fecha_pago.'T06:00:00.000Z';
$receptor->antiguedad = $antiguedad;
$receptor->periodicidad_pago = $datos_empleado['periodicidad_pago'];

//Calculo de conceptos para el total de calculo de impuestos
/*$stmt = $conn->prepare('SELECT dnpe.tipo_concepto, dnpe.importe, dnpe.importe_exento, dnpe.exento, dnpe.dias, dnpe.horas, tp.codigo, rtp.clave, dnpe.relacion_tipo_percepcion_id FROM detalle_nomina_percepcion_empleado as dnpe INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = dnpe.relacion_tipo_percepcion_id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id  WHERE dnpe.empleado_id = :empleado_id AND dnpe.nomina_empleado_id = :nomina_empleado_id');
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->execute();
$conceptos = $stmt->fetchAll();*/

//print_r($datos_empleado);
$num_percepciones = 0;

if($datos_empleado['aguinaldo_gravado'] > 0 || $datos_empleado['aguinaldo_exento'] > 0){
    //Aguinaldo
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 2');
    $stmt->execute();
    $concepto_aguinaldo = $stmt->fetch();
    $cantidad = $stmt->rowCount();
    if($cantidad < 1){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de aguinaldo.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    $percepcion[$num_percepciones] = [
                        "tipo_percepcion" =>  $concepto_aguinaldo['codigo'],
                        "clave" => $concepto_aguinaldo['clave'],
                        "importe_gravado" => $datos_empleado['aguinaldo_gravado'],
                        "importe_exento" =>  $datos_empleado['aguinaldo_exento']
                    ];
    $num_percepciones++;
}

$otros_ingresos_salario = $datos_empleado['vacaciones'] + $datos_empleado['otros'] + $datos_empleado['gratificacion'];
if($otros_ingresos_salario > 0){
    //Vacaciones, gratificacion y otros
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 33');
    $stmt->execute();
    $concepto_otros = $stmt->fetch();
    $cantidad = $stmt->rowCount();
    if($cantidad < 1){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de otros ingresos por salarios.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    $percepcion[$num_percepciones] = [
                        "tipo_percepcion" =>  $concepto_otros['codigo'],
                        "clave" => $concepto_otros['clave'],
                        "importe_gravado" => $otros_ingresos_salario,
                        "importe_exento" =>  0.00
                    ];
    $num_percepciones++;
}

if($datos_empleado['prima_vacacional_gravada'] > 0 || $datos_empleado['prima_vacacional_exenta'] > 0){
    //Prima vacacional
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 16');
    $stmt->execute();
    $concepto_p_vacacional = $stmt->fetch();
    $cantidad = $stmt->rowCount();
    if($cantidad < 1){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de prima vacacional.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    $percepcion[$num_percepciones] = [
                        "tipo_percepcion" =>  $concepto_p_vacacional['codigo'],
                        "clave" => $concepto_p_vacacional['clave'],
                        "importe_gravado" => $datos_empleado['prima_vacacional_gravada'],
                        "importe_exento" =>  $datos_empleado['prima_vacacional_exenta']
                    ];
    $num_percepciones++;
}

if($datos_empleado['salarios_devengados'] > 0){
    //Salarios devengados
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 1');
    $stmt->execute();
    $concepto_salarios = $stmt->fetch();
    $cantidad = $stmt->rowCount();
    if($cantidad < 1){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de prima vacacional.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    $percepcion[$num_percepciones] = [
                        "tipo_percepcion" =>  $concepto_salarios['codigo'],
                        "clave" => $concepto_salarios['clave'],
                        "importe_gravado" => $datos_empleado['salarios_devengados'],
                        "importe_exento" =>  0.00
                    ];
    $num_percepciones++;
}

if($datos_empleado['bonos_asistencia'] > 0){
    //Bono asistencia
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 40');
    $stmt->execute();
    $concepto_bono_asistencia = $stmt->fetch();
    $cantidad = $stmt->rowCount();
    if($cantidad < 1){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de premios por asistencia.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    $percepcion[$num_percepciones] = [
                        "tipo_percepcion" =>  $concepto_bono_asistencia['codigo'],
                        "clave" => $concepto_bono_asistencia['clave'],
                        "importe_gravado" => $datos_empleado['bonos_asistencia'],
                        "importe_exento" =>  0.00
                    ];
    $num_percepciones++;
}

if($datos_empleado['bonos_puntualidad'] > 0){
    //Bono puntualidad
        $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 8');
        $stmt->execute();
        $concepto_bono_puntualidad = $stmt->fetch();
        $cantidad = $stmt->rowCount();
        if($cantidad < 1){
            $respuesta->estatus = "fallo-general";
            $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de premios por puntualidad.";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        $percepcion[$num_percepciones] = [
                            "tipo_percepcion" =>  $concepto_bono_puntualidad['codigo'],
                            "clave" => $concepto_bono_puntualidad['clave'],
                            "importe_gravado" => $datos_empleado['bonos_puntualidad'],
                            "importe_exento" =>  0.00
                        ];
        $num_percepciones++;
}

$percepciones = new stdClass();
$percepciones->percepcion =   $percepcion;

$cont_deducciones = 0;

//cuando es finiquito
//if($tipoMovimiento == 1){
    $ISRTotal = bcdiv(0.00 + $datos_empleado['isr_vacaciones_salarios'] + $datos_empleado['isr_aguinaldo'] + $datos_empleado['isr_prima_vacacional'],1,2);
    $SAETotal =  bcdiv(0.00 + $datos_empleado['sae_vacaciones_salarios'] + $datos_empleado['sae_aguinaldo'] + $datos_empleado['sae_prima_vacacional'],1,2);
    //echo "// ".$datos_empleado['isr_vacaciones_salarios']." -- ".$datos_empleado['isr_aguinaldo'] ." -- ". $datos_empleado['isr_prima_vacacional']." -- ".$ISRTotal;
    if($ISRTotal > 0){

        $stmt = $conn->prepare('SELECT clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 2 AND empresa_id = :idEmpresa');
        $stmt->bindValue(':idEmpresa', $idEmpresa);
        $stmt->execute();
        $clave_isr = $stmt->fetch();
        if(trim($clave_isr['clave']) == ""){
            $respuesta->estatus = "fallo-isr";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        //isr
        $deducciones[$cont_deducciones] = [
                        "tipo_deduccion" => "002",
                        "clave" => trim($clave_isr['clave']),
                        "importe" => $ISRTotal
                      ];
        $cont_deducciones++;
    }


    //print_r($deducciones);
        $otros_pagos[] = [
                        "tipo_otro_pago" => "002",
                        "clave" => "00000001",
                        "importe" => $SAETotal,
                        "subsidio_causado" => $SAETotal
                      ];

//}  Solo era para finiquitos, pero ahora se ejecutara siempre

if($datos_empleado['infonavit'] > 0){
    //Infonavit
        $stmt = $conn->prepare('SELECT rtd.clave, td.codigo FROM tipo_deduccion as td INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE td.id = 5');
        $stmt->execute();
        $concepto_infonavit = $stmt->fetch();
        $cantidad = $stmt->rowCount();
        if($cantidad < 1){
            $respuesta->estatus = "fallo-general";
            $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave del fondo para la vivienda (INFONAVIT).";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        $deducciones[$cont_deducciones] = [
                            "tipo_deduccion" =>  $concepto_infonavit['codigo'],
                            "clave" => $concepto_infonavit['clave'],
                            "importe" => $datos_empleado['infonavit']
                        ];
        $cont_deducciones++;
}

if($datos_empleado['fonacot'] > 0){
    //Fonacot
        $stmt = $conn->prepare('SELECT rtd.clave, td.codigo FROM tipo_deduccion as td INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE td.id = 11');
        $stmt->execute();
        $concepto_fonacot = $stmt->fetch();
        $cantidad = $stmt->rowCount();
        if($cantidad < 1){
            $respuesta->estatus = "fallo-general";
            $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de pago de abonos INFONACOT.";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        $deducciones[$cont_deducciones] = [
                            "tipo_deduccion" =>  $concepto_fonacot['codigo'],
                            "clave" => $concepto_fonacot['clave'],
                            "importe" => $datos_empleado['fonacot']
                        ];
        $cont_deducciones++;
}

if($datos_empleado['pension_alimenticia_cantidad'] > 0){
    //Pension alimenticia
        $stmt = $conn->prepare('SELECT rtd.clave, td.codigo FROM tipo_deduccion as td INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE td.id = 7');
        $stmt->execute();
        $concepto_pension_alimenticia = $stmt->fetch();
        $cantidad = $stmt->rowCount();
        if($cantidad < 1){
            $respuesta->estatus = "fallo-general";
            $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de pensión alimenticia.";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        $deducciones[$cont_deducciones] = [
                            "tipo_deduccion" =>  $concepto_pension_alimenticia['codigo'],
                            "clave" => $concepto_pension_alimenticia['clave'],
                            "importe" => $datos_empleado['pension_alimenticia_cantidad']
                        ];
        $cont_deducciones++;
}


if($datos_empleado['imss_salarios'] > 0){
    //Infonavit
        $stmt = $conn->prepare('SELECT rtd.clave, td.codigo FROM tipo_deduccion as td INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE td.id = 1');
        $stmt->execute();
        $concepto_imss = $stmt->fetch();
        $cantidad = $stmt->rowCount();
        if($cantidad < 1){
            $respuesta->estatus = "fallo-general";
            $respuesta->mensaje = "No puedes timbrar la nómina, te falta la clave de Seguridad Social (IMSS)";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        $deducciones[$cont_deducciones] = [
                            "tipo_deduccion" =>  $concepto_imss['codigo'],
                            "clave" => $concepto_imss['clave'],
                            "importe" => $datos_empleado['imss_salarios']
                        ];
        $cont_deducciones++;
}

/*
echo "<br><br>";
print_r($deducciones);
return;*/


$data = new stdClass();
$data->tipo_nomina =  'O';
$data->fecha_pago = $fecha_pago.'T06:00:00.000Z';   //CONCAT(dle.FechaIngreso,'T06:00:00.000Z')
$data->fecha_inicial_pago =  $datos_empleado['fecha_inicio_rel_laboral'];//date("Y-m-d").'T06:00:00.000Z';
$data->fecha_final_pago =  $fecha_pago.'T06:00:00.000Z';
$data->num_dias_pagados =  1;
$data->emisor =  $emisor;  //opcional
$data->receptor =  $receptor;
$data->percepciones =  $percepciones;
$data->otros_pagos =  $otros_pagos;
/*$data->related = [ "5310D927-F17D-4CFD-848E-D7F110190188" ];
$data->relation = "04";*/
//print_r($data->percepciones);
if($cont_deducciones > 0){
    $data->deducciones =  $deducciones;
}


$complements[] = [
                    "type" => "nomina",
                    "data" => $data
                 ];

$segundoApellido = "";
if(trim($datos_empleado['SegundoApellido']) != ""){
    $segundoApellido = " ".trim($datos_empleado['SegundoApellido']);
}
$nombre = trim($datos_empleado['Nombres'])." ".trim($datos_empleado['PrimerApellido']).$segundoApellido;

$direccion = [
    "zip" => trim($datos_empleado['CP']),
    "country"=>"MEX"
];

if(trim($datos_empleado['email']) == "" || trim($datos_empleado['email']) == NULL){
    $cliente_api = [
      "email" => trim($datos_empleado['email']),
      "legal_name" => $nombre,
      "tax_id" => trim($datos_empleado['RFC']),
      "address" => $direccion,
      "tax_system" => trim($datos_empleado['clave_regimen_fiscal'])
    ];
}
else{
    $cliente_api = [
       "legal_name" => $nombre,
       "tax_id" => trim($datos_empleado['RFC']),
       "address" => $direccion,
      "tax_system" => trim($datos_empleado['clave_regimen_fiscal'])
    ];
}



//type es N por nomina
// customer el id del empleado que se facturara.
//folio_number  se omitira para que se genere automaticamente
//id del cliente que se guarda en la api, de moemento fijo con el de ADATA
if($ids_finiquito != ""){
    //$documentos_relacionados = [ 'FC8F5544-9906-4C36-897A-BE1AD1593F9E', '4EA7F8A1-2F3C-4AC3-8D4E-210694912FC2','8143BC2D-06C5-4FC4-8BB3-011392802816','24DB4471-BE47-408F-9AD5-A48763ECF465'];
    $documentos_relacionados = array();
    
    foreach($ids_finiquito as $idf){
        $stmt = $conn->prepare('SELECT uuid FROM bitacora_cfdi_eliminado_finiquito_liquidacion WHERE id ='.$idf);
        $stmt->execute();
        $uuid_finiquito = $stmt->fetch();
        
        array_push($documentos_relacionados, $uuid_finiquito['uuid']);
        
    }

    $invoice = array(
              "type" => "N",  
              "customer" => $cliente_api,
              "complements" => $complements,
              "related" => $documentos_relacionados,
              "relation" => "04",
            );

}
else{
    $invoice = array(
              "type" => "N",  
              "customer" => $cliente_api,
              "complements" => $complements
            );
}

//se timbra en caso de que no este timbrada el finiquito
if($datos_empleado['estadoTimbrado'] == 0  && $datos_empleado['idFactura'] == ""){
        $datosFacturaTimbrada = $facturapi->Invoices->create( $invoice );

        //echo "<br><br>";
        //echo "<pre>",print_r($datosFacturaTimbrada),"</pre>";
        
        if(isset($datosFacturaTimbrada->message)){

            if(strpos($datosFacturaTimbrada->message, "curp") || strpos($datosFacturaTimbrada->message, "Curp")){
                $respuesta->estatus = "fallo-general";
                $respuesta->mensaje = "La CURP no es válida.";
                $respuesta = json_encode($respuesta);
                echo $respuesta;
                return;
            }

        }

        if(isset($datosFacturaTimbrada->status)){
            if($datosFacturaTimbrada->status == 'valid'){

                try{    
                        $conn->beginTransaction(); 

                        $idFactura = $datosFacturaTimbrada->id;
                        $uuid = $datosFacturaTimbrada->uuid;
                        $total_timbrado = $datosFacturaTimbrada->total;
                        $fechaTimbrado = date("Y-m-d");
                        $fechaTimbradoFormat = date("d/m/Y", strtotime($fechaTimbrado));  

                        $stmt = $conn->prepare('UPDATE finiquito SET idFactura = :idFactura, uuid = :uuid, total_timbrado = :total_timbrado, estadoTimbrado = 1, fechaTimbrado = :fechaTimbrado, metodo_pago_id = :metodo_pago_id WHERE id = :idFiniquito');
                        $stmt->bindValue(':idFactura', $idFactura);
                        $stmt->bindValue(':uuid', $uuid);
                        $stmt->bindValue(':total_timbrado', $total_timbrado);
                        $stmt->bindValue(':fechaTimbrado', $fechaTimbrado);
                        $stmt->bindValue(':metodo_pago_id', $metodo_pago_id);
                        $stmt->bindValue(':idFiniquito', $idFiniquito);
                        $stmt->execute();

                        if($tipoMovimiento == 1){
                            $stmt = $conn->prepare('UPDATE empleados SET estatus = 0 WHERE PKEmpleado = :empleado_id');
                            $stmt->bindValue(':empleado_id', $idEmpleado);
                            $stmt->execute();
                        }

                        if($conn->commit()){
                          $respuesta->idFactura = $idFactura;
                          $respuesta->fechaTimbrado = $fechaTimbradoFormat;
                          $respuesta->estatus = "exito";

                          $mensaje = $api->sendEmailInvoice($key_company_api[0]['key_company'],$idFactura,$datos_empleado['email']);

                          if($mensaje->ok){
                            $respuesta->estatus_email = "exito";
                          }
                          else{
                             $respuesta->estatus_email = "fallo";
                          }

                        }else{
                          $respuesta->estatus = "fallo";
                        }
                }
                catch (PDOException $ex) {
                    $conn->rollBack(); //echo $ex;
                    $respuesta->estatus = "fallo";
                    $respuesta = json_encode($respuesta);
                    echo $respuesta;
                    return;
                }
            }
            else{
                $respuesta->estatus = "fallo";
                $respuesta = json_encode($respuesta);
                echo $respuesta;
                return;
            }
        }
        else{
            $respuesta->estatus = "fallo";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
}

if($datos_empleado['estadoTimbrado'] == 1  && $datos_empleado['idFactura'] != ""){
    $respuesta->estatus = "fallo-estadotimbrado";
    $respuesta->idFactura = $datos_empleado['idFactura'];
    $respuesta->fechaTimbrado = $datos_empleado['fechaTimbrado'];
}

/***FACTURAR LIQUIDACION***/
//percepciones de liquidacion
unset($percepcion);
unset($percepciones);
unset($deducciones);
unset($otros_pagos);
unset($receptor);
unset($data);
unset($invoice);
unset($complements);
$num_percepciones = 0;
if($tipoMovimiento == 2){

    $total_pagado = 0.00;
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 20');
    $stmt->execute();
    $concepto_indemnizacion = $stmt->fetch();

    if(!isset($concepto_indemnizacion['clave'])){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "Ingresa la clave del concepto indemnizaciones.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

                        /*    "TotalSeparacionIndemnizacion" => $datos_empleado['indemnizacion'],
                            "SeparacionIndemnizacion" => "11",*/
    //indemnizacion
    if($datos_empleado['indemnizacion'] > 0){


        $percepcion[$num_percepciones] = [
                            "tipo_percepcion" =>  $concepto_indemnizacion['codigo'],
                            "clave" => $concepto_indemnizacion['clave'],
                            "importe_gravado" => $datos_empleado['indemnizacion_gravado'],
                            "importe_exento" =>  $datos_empleado['indemnizacion_exento']
                        ];
        $num_percepciones++;
        $total_pagado = $total_pagado + $datos_empleado['indemnizacion'];
    }

    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 18');
    $stmt->execute();
    $conceptos_pago_separacion = $stmt->fetch();

    if(!isset($conceptos_pago_separacion['clave'])){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "Ingresa la clave del concepto pagos por separación.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    //20 días por año de servicio 
    if($datos_empleado['anios_servicio'] > 0){

        $percepcion[$num_percepciones] = [
                            "tipo_percepcion" =>  $conceptos_pago_separacion['codigo'],
                            "clave" => $conceptos_pago_separacion['clave'],
                            "importe_gravado" => $datos_empleado['anios_servicio'],
                            "importe_exento" =>  0.00
                        ];
        $num_percepciones++;
        $total_pagado = $total_pagado + $datos_empleado['anios_servicio'];
    }
    
    $stmt = $conn->prepare('SELECT rtp.clave, tp.codigo FROM tipo_percepcion as tp INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' WHERE tp.id = 17');
    $stmt->execute();
    $concepto_prima_antiguedad = $stmt->fetch();    

    if(!isset($concepto_prima_antiguedad['clave'])){
        $respuesta->estatus = "fallo-general";
        $respuesta->mensaje = "Ingresa la clave del concepto prima por antiguedad.";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }

    //20 días por año de servicio 
    if($datos_empleado['prima_antiguedad'] > 0){

        $percepcion[$num_percepciones] = [
                            "tipo_percepcion" =>  $concepto_prima_antiguedad['codigo'],
                            "clave" => $concepto_prima_antiguedad['clave'],
                            "importe_gravado" => $datos_empleado['prima_antiguedad'],
                            "importe_exento" =>  0.00
                        ];
        $num_percepciones++;
        $total_pagado = $total_pagado + $datos_empleado['prima_antiguedad'];
    }


    //DEDUCCIONES DE LIQUIDACION
    $ISRTotal = bcdiv(0.00 + $datos_empleado['isr_liquidacion'],1,2);
    $SAETotal = bcdiv(0.00 + $datos_empleado['sae_liquidacion'],1,2);
    $cont_deducciones = 0;

    if($ISRTotal > 0){

        $stmt = $conn->prepare('SELECT clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 2 AND empresa_id = :idEmpresa');
        $stmt->bindValue(':idEmpresa', $idEmpresa);
        $stmt->execute();
        $clave_isr = $stmt->fetch();
        if(trim($clave_isr['clave']) == ""){
            $respuesta->estatus = "fallo-isr";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
        }
        //isr
        $deducciones[0] = [
                        "tipo_deduccion" => "002",
                        "clave" => trim($clave_isr['clave']),
                        "importe" => $ISRTotal
                      ];
        $cont_deducciones = 1;
    }
    //print_r($deducciones);
    $otros_pagos[] = [
                    "tipo_otro_pago" => "002",
                    "clave" => "00000001",
                    "importe" => $SAETotal,
                    "subsidio_causado" => $SAETotal
                  ];

    $total_pagado = $total_pagado - $ISRTotal + $SAETotal;


    $receptor = new stdClass();
    $receptor->curp =  $datos_empleado['CURP'];
    $receptor->num_seguridad_social =  $datos_empleado['num_seguridad_social'];
    $receptor->fecha_inicio_rel_laboral =  $datos_empleado['fecha_inicio_rel_laboral'];
    $receptor->tipo_contrato =  $datos_empleado['tipo_contrato'];
    $receptor->tipo_jornada =  $datos_empleado['tipo_jornada'];  //opcional
    $receptor->tipo_regimen =  $datos_empleado['tipo_regimen'];
    $receptor->num_empleado =  strval($datos_empleado['num_empleado']);
    $receptor->puesto =  $datos_empleado['puesto']; //opcional
    $receptor->riesgo_puesto =  $datos_empleado['riesgo_puesto'];
    $receptor->clave_ent_fed =  $datos_empleado['clave_ent_fed'];  
    $receptor->salario_diario_integrado = $SDI;
    $receptor->salario_base_cot_apor = "500.00"; //actualizar cuando se calculen el salario diario y el salario diario integrado
    $receptor->antiguedad = $antiguedad;
    $receptor->periodicidad_pago = $datos_empleado['periodicidad_pago'];

    $percepciones = new stdClass();
    $percepciones->percepcion =   $percepcion;

    $datos_separacion = new stdClass();
    $datos_separacion->total_pagado = $total_pagado;
    if($datos_empleado['num_anios_servicio']  < 1){
        $anios_servicio = 1;
    }
    else{
        $anios_servicio = $datos_empleado['num_anios_servicio'];
    }
    $datos_separacion->num_anos_servicio = $anios_servicio;
    $datos_separacion->ultimo_sueldo_mens_ord =$datos_empleado['ultimo_sueldo_mensual_ord'];
    $datos_separacion->ingreso_acumulable = 0.00;
    $datos_separacion->ingreso_no_acumulable = 0.00;
    $percepciones->separacion_indemnizacion = $datos_separacion;


    $data = new stdClass();
    $data->fecha_pago = $fecha_pago.'T06:00:00.000Z';   //CONCAT(dle.FechaIngreso,'T06:00:00.000Z')
    $data->fecha_inicial_pago =  $datos_empleado['fecha_inicio_rel_laboral'];//date("Y-m-d").'T06:00:00.000Z';
    $data->fecha_final_pago =  $fecha_pago.'T06:00:00.000Z';
    $data->num_dias_pagados =  1;
    $data->emisor =  $emisor;  //opcional
    $data->receptor =  $receptor;
    $data->percepciones =  $percepciones;
    $data->tipo_nomina =  'E';
    if($cont_deducciones > 0){
        $data->deducciones =  $deducciones;
    }
    $data->otros_pagos =  $otros_pagos;
    $receptor->periodicidad_pago =  "99"; //Periodicidad para liquidacion, porque es anual

    $complements[] = [
                    "type" => "nomina",
                    "data" => $data
                 ];

    if($ids_finiquito != ""){
        $documentos_relacionados_liquidacion = array();
        
        foreach($ids_liquidacion as $idl){
            $stmt = $conn->prepare('SELECT uuid FROM bitacora_cfdi_eliminado_finiquito_liquidacion WHERE id ='.$idl);
            $stmt->execute();
            $uuid_liquidacion = $stmt->fetch();
            
            array_push($documentos_relacionados_liquidacion, $uuid_liquidacion['uuid']);
            
        }

        $invoice = array(
                  "type" => "N",  
                  "customer" => $cliente_api,
                  "complements" => $complements,
                  "related" => $documentos_relacionados_liquidacion,
                  "relation" => "04",
                );

    }
    else{
        $invoice = array(
                  "type" => "N",  
                  "customer" => $cliente_api,
                  "complements" => $complements
                );
    }
    
//var_dump($data->percepciones);
//echo "///////////////////////////////////////////";

//se timbra en caso de que no este timbrada la liquidacion
if($datos_empleado['estadoTimbradoLiquidacion'] == 0  && $datos_empleado['idFacturaLiquidacion'] == ""){

        $datosFacturaTimbradaLiquidacion = $facturapi->Invoices->create( $invoice );
        //echo "<br><br>";
        //echo "<pre>".print_r($datosFacturaTimbradaLiquidacion)."</pre>";

            if(isset($datosFacturaTimbradaLiquidacion->message)){

                if(strpos($datosFacturaTimbradaLiquidacion->message, "curp") || strpos($datosFacturaTimbradaLiquidacion->message, "Curp")){
                    $respuesta->estatus = "fallo-general";
                    $respuesta->mensaje = "La CURP no es válida.";
                    $respuesta = json_encode($respuesta);
                    echo $respuesta;
                    return;
                }

            }

            if(isset($datosFacturaTimbradaLiquidacion->status)){
                if($datosFacturaTimbradaLiquidacion->status == 'valid'){

                    try{    
                            $conn->beginTransaction(); 

                            $stmt = $conn->prepare('SELECT metodo_pago_id FROM finiquito WHERE id = :idFiniquito AND empresa_id = '.$_SESSION['IDEmpresa']);
                            $stmt->bindValue(':idFiniquito', $idFiniquito);
                            $stmt->execute();
                            $metodo_pago_array = $stmt->fetch();

                            $idFactura = $datosFacturaTimbradaLiquidacion->id;
                            $uuid = $datosFacturaTimbradaLiquidacion->uuid;
                            $total_timbrado = $datosFacturaTimbradaLiquidacion->total;
                            $fechaTimbrado = date("Y-m-d");
                            $fechaTimbradoFormat = date("d/m/Y", strtotime($fechaTimbrado));  

                            $stmt = $conn->prepare('UPDATE liquidacion SET idFacturaLiquidacion = :idFactura, uuidLiquidacion = :uuid, total_timbradoLiquidacion = :total_timbrado, estadoTimbradoLiquidacion = 1, fechaTimbradoLiquidacion = :fechaTimbrado, metodo_pago_id_liquidacion = :metodo_pago_id_liquidacion WHERE finiquito_id = :idFiniquito');
                            $stmt->bindValue(':idFactura', $idFactura);
                            $stmt->bindValue(':uuid', $uuid);
                            $stmt->bindValue(':total_timbrado', $total_timbrado);
                            $stmt->bindValue(':fechaTimbrado', $fechaTimbrado);
                            $stmt->bindValue(':metodo_pago_id_liquidacion', $metodo_pago_array['metodo_pago_id']);
                            $stmt->bindValue(':idFiniquito', $idFiniquito);
                            $stmt->execute();

                            $stmt = $conn->prepare('UPDATE empleados SET estatus = 0 WHERE PKEmpleado = :empleado_id');
                            $stmt->bindValue(':empleado_id', $idEmpleado);
                            $stmt->execute();

                            if($conn->commit()){
                              $respuesta->estatusLiquidacion = "exito";
                              $respuesta->idFacturaLiquidacion = $idFactura;
                              $respuesta->fechaTimbradoLiquidacion = $fechaTimbradoFormat;

                              $mensaje = $api->sendEmailInvoice($key_company_api[0]['key_company'],$idFactura,$datos_empleado['email']);

                              if($mensaje->ok){
                                $respuesta->estatus_email_liquidacion = "exito";
                              }
                              else{
                                 $respuesta->estatus_email_liquidacion = "fallo";
                              }

                            }else{
                              $respuesta->estatusLiquidacion = "fallo";
                            }
                    }
                    catch (PDOException $ex) {
                        $conn->rollBack(); 
                        $respuesta->estatusLiquidacion = "fallo";
                        $respuesta = json_encode($respuesta);
                        echo $respuesta;
                        return;
                    }
                }
                else{
                    $respuesta->estatusLiquidacion = "fallo";
                    $respuesta = json_encode($respuesta);
                    echo $respuesta;
                    return;
                }
            }
            else{
                $respuesta->estatusLiquidacion = "fallo";
                $respuesta = json_encode($respuesta);
                echo $respuesta;
                return;
            }

        }
}

if($datos_empleado['estadoTimbradoLiquidacion'] == 1  && $datos_empleado['idFacturaLiquidacion'] != ""){
    $respuesta->estatusLiquidacion = "fallo-estadotimbrado";
    $respuesta->idFacturaLiquidacion = $datos_empleado['idFacturaLiquidacion'];
    $respuesta->fechaTimbradoLiquidacion = $datos_empleado['fechaTimbradoLiquidacion'];
}

$respuesta = json_encode($respuesta);
echo $respuesta;

?>
