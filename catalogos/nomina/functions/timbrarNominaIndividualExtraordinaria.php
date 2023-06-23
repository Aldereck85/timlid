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

date_default_timezone_set('America/Mexico_City');
require_once '../../../include/db-conn.php';

$respuesta->nomina_completa = 0;
$respuesta->estatus_email = "";
//informacion que necesita recibir
$idNominaEmpleado = $_POST['idNominaEmpleado'];//id tabla nomina_empleado PKNomina  9
$idNomina = $_POST['idNomina'];//id tabla nomina_empleado  FKNomina, es el id PK de la tabla nomina  2
$idEmpleado = $_POST['idEmpleado']; //106
$idEmpresa = $_SESSION['IDEmpresa'];

if(isset($_POST['idRelacionados'])){
    $ids = $_POST['idRelacionados'];
}
else{
    $ids = "";
}

if($ids != ""){
    $ids_nomina = array();
    $cont1 = 0;
    foreach($ids as $i){
        $separacion = explode("_", $i);
        $ids_nomina[$cont1] = $separacion[0];
        $cont1++;
    }

}
else{
    $ids_nomina = "";
}

$stmt = $conn->prepare("SELECT e.Nombres, e.PrimerApellido, e.SegundoApellido, e.CURP, dme.NSS as num_seguridad_social, e.RFC, e.idtimbrado, e.email, CONCAT(dle.FechaIngreso,'T06:00:00.000Z') as fecha_inicio_rel_laboral, tc.codigo as tipo_contrato, tj.codigo as tipo_jornada, tr.codigo as tipo_regimen, e.id_empleado as num_empleado, p.puesto, rp.codigo as riesgo_puesto,  pp.Codigo as periodicidad_pago, ef.Codigo as clave_ent_fed, dle.Sueldo FROM empleados as e LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado LEFT JOIN tipo_contrato as tc ON tc.id = dle.FKTipoContrato LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN tipo_jornada as tj ON tj.id = t.tipo_jornada_id LEFT JOIN tipo_regimen as tr ON tr.id = dle.FKRegimen LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN riesgo_puesto as rp ON rp.id = dle.FKRiesgoPuesto LEFT JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN estados_federativos as ef ON ef.PKEstado = e.FKEstado WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = :empresa_id");
$stmt->bindValue(":idEmpleado",$idEmpleado);
$stmt->bindValue(":empresa_id",$idEmpresa);
$stmt->execute();
$datos_empleado = $stmt->fetch();

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

$stmt = $conn->prepare("SELECT exento, estadoTimbrado, idFactura, fechaTimbrado FROM nomina_empleado WHERE PKNomina = :idNominaEmpleado");
$stmt->bindValue(":idNominaEmpleado",$idNominaEmpleado);
$stmt->execute();
$datos_nomina_empleado = $stmt->fetch();

if($datos_nomina_empleado['exento'] == 1){
    $respuesta->estatus = "fallo-exento";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

if($datos_nomina_empleado['estadoTimbrado'] == 1  && $datos_nomina_empleado['idFactura'] != ""){
    $respuesta->estatus = "fallo-estadotimbrado";
    $respuesta->idFactura = $datos_nomina_empleado['idFactura'];
    $respuesta->fechaTimbrado = $datos_nomina_empleado['fechaTimbrado'];
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

$stmt = $conn->prepare("SELECT if(n.tipo_id = 1, 'O','E') as tipo_nomina, CONCAT(n.fecha_pago,'T06:00:00.000Z') as fecha_pago, CONCAT(n.fecha_inicio,'T06:00:00.000Z') as fecha_inicial_pago, CONCAT(n.fecha_fin,'T06:00:00.000Z') as fecha_final_pago, pp.DiasPago as num_dias_pagados  FROM nomina as n INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = n.periodo_id WHERE n.id = :idNomina AND n.empresa_id = :empresa_id");
$stmt->bindValue(":idNomina",$idNomina);
$stmt->bindValue(":empresa_id",$idEmpresa);
$stmt->execute();
$datos_nomina = $stmt->fetch();

$query = sprintf("select key_company_api key_company,key_user_company_api key_user, RFC, registro_patronal, curp from empresas where PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$_SESSION['IDEmpresa']);
$stmt->execute();
$key_company_api = $stmt->fetchAll();

$facturapi = new Facturapi($key_company_api[0]['key_company']);

$emisor = new stdClass();
$emisor->registro_patronal = $key_company_api[0]['registro_patronal'];
$emisor->rfc = $key_company_api[0]['RFC'];

if(strlen(trim($key_company_api[0]['RFC'])) == 13){
    $emisor->curp =  $key_company_api[0]['curp'];
}

//echo "RFC ".$key_company_api[0]['RFC'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" OR descripcion = "Salario_Minimo_Norte" ORDER BY PKParametros Asc');
$stmt->execute();
$row_parametros = $stmt->fetchAll();
$UMA = $row_parametros[0]['cantidad'];
$factor_mes = $row_parametros[3]['cantidad'];
$salario_minimo_nacional = $row_parametros[1]['cantidad'];
$salario_minimo_norte = $row_parametros[2]['cantidad'];

//*********CALCULAR SDI*******////////
$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_aguinaldo = $stmt->fetch();
$dias_aguinaldo = $row_aguinaldo['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_prima_vacacional = $stmt->fetch();
$prima_vacacional_tasa = $row_prima_vacacional['cantidad'] / 100;

$diasPeriodoIMSS = bcdiv($datos_nomina['num_dias_pagados'], '1', 0);
$base_imss_general = $datos_empleado['Sueldo'];

$ruta = "../";
require_once($ruta.'../../functions/funcion_calculovacaciones.php');

$salarioBaseDiario = number_format($base_imss_general / $diasPeriodoIMSS,2, '.', '');// se calculo con lo dias del periodo, ya que no afecta los dias trabajados por el empleado, sino el periodo, al igual el salario, es su pago completo
$factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
$SDI = bcdiv($salarioBaseDiario * $factorSDI,1, 2);//Salario Base Cotizacion o Salario Diario Integrado, es igual
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
$receptor->periodicidad_pago = "99";//$datos_empleado['periodicidad_pago'];
$receptor->clave_ent_fed =  $datos_empleado['clave_ent_fed'];  
$receptor->antiguedad =  true; 
$receptor->salario_diario_integrado = $SDI;
$receptor->salario_base_cot_apor = "500.00"; //actualizar cuando se calculen el salario diario y el salario diario integrado
//echo " fcha ini ".$datos_empleado['fecha_inicio_rel_laboral'];
//echo " periodicidad_pago ".$datos_empleado['periodicidad_pago'];

//Calculo de conceptos para el total de calculo de impuestos
$stmt = $conn->prepare('SELECT dnpe.tipo_concepto, dnpe.importe, dnpe.importe_exento, dnpe.exento, dnpe.dias, dnpe.horas, tp.codigo, rtp.clave, dnpe.relacion_tipo_percepcion_id FROM detalle_nomina_percepcion_empleado as dnpe INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = dnpe.relacion_tipo_percepcion_id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id  WHERE dnpe.empleado_id = :empleado_id AND dnpe.nomina_empleado_id = :nomina_empleado_id');
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->execute();
$conceptos = $stmt->fetchAll();

if(count($conceptos) < 1){
    $respuesta->estatus = "fallo-general";
    $respuesta->mensaje = "Tienes que ingresar al menos un concepto.";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

$x = 0;
$cantidad_horas_extras = 0;
$activar_horas_extras = 0;
foreach ($conceptos as $c) {
    //echo "!!!! codigo: ".$c['codigo']." //--clave: ".$c['clave'];
    //tipo salario
    if($c['tipo_concepto'] == 1 || $c['tipo_concepto'] == 2 || $c['tipo_concepto'] == 4 ){

        //no exento
        if($c['exento'] == 0){
            $importe_gravado = $c['importe'];
            $importe_exento = 0;
        }
        else{
            $importe_gravado = 0;
            $importe_exento = $c['importe'];
        }
        
    }

    //prima dominical
    if($c['tipo_concepto'] == 6){

      if($c['importe'] > $UMA){
          $importe_exento = $UMA;
          $importe_gravado = $c['importe'] - $UMA;
      }
      else{
          $importe_exento = $c['importe'];
      }
      
    }

    //horas extras
    //simples
    if($c['tipo_concepto'] == 3){
        $tipo_horas = '03';
        $importe_gravado = $c['importe'];
        $importe_exento = 0;
        $importe_pagado = $c['importe'];
    }
    //dobles
    if($c['tipo_concepto'] == 7){
        $tipo_horas = '01';

        if(($c['importe']/2) > ($UMA * 5)){
            $importe_exento = $UMA * 5; 
            $importe_gravado = $c['importe'] - $importe_exento;
        }
        else{
            $importe_exento = $c['importe']/2;
            $importe_gravado = $c['importe']/2;
        }
        $importe_pagado = $importe_gravado + $importe_exento;

    }
    //triples
    if($c['tipo_concepto'] == 8){
        $tipo_horas = '02';
        $importe_gravado = $c['importe'];
        $importe_exento = 0;
        $importe_pagado = $c['importe'];
    }

    //aguinaldo
    if($c['tipo_concepto'] == 9){
        $importe_gravado = $c['importe'];
        $importe_exento = $c['importe_exento'];
        $importe_pagado = $c['importe'] + $c['importe_exento'];
    }


    //se agregar el concepto horas extra para horas extra
    if($c['relacion_tipo_percepcion_id'] == 14){
        
        $dias = round($c['horas'] / 3, 0, PHP_ROUND_HALF_DOWN);
        
        if($dias < 1){
            $dias = 1;
        }
        
        $horas_extras[0] = [
                    "dias" =>   $dias,
                    "tipo_horas" => $tipo_horas,
                    "horas_extra" => $c['horas'],
                    "importe_pagado" =>  $importe_pagado
                  ];
        //$cantidad_horas_extras++;

        $percepcion[$x] = [
                        "tipo_percepcion" =>   $c['codigo'],
                        "clave" => $c['clave'],
                        "importe_gravado" => $importe_gravado,
                        "importe_exento" =>  $importe_exento,
                        "horas_extra" => $horas_extras
                      ];
    }
    else{
        $percepcion[$x] = [
                        "tipo_percepcion" =>   $c['codigo'],
                        "clave" => $c['clave'],
                        "importe_gravado" => $importe_gravado,
                        "importe_exento" =>  $importe_exento
                      ];
    }
    $x++;

    //cuando son horas extras
   /* if($c['relacion_tipo_percepcion_id'] == 14){
        $activar_horas_extras = 1;
        

        
    }*/
}
//print_r($percepcion);
//return;
$percepciones = new stdClass();
$percepciones->percepcion =   $percepcion;
/*if($activar_horas_extras == 1){
    $percepciones->horas_extra =   $horas_extras;
}*/


$stmt = $conn->prepare('SELECT ISR, SAE, cuotaIMSS FROM nomina_empleado WHERE FKEmpleado = :empleado_id AND PKNomina = :nomina_empleado_id');
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->execute();
$conceptos_deducciones_base = $stmt->fetchAll();

//print_r($conceptos_deducciones_base);
$cont_deducciones = 0;
if($conceptos_deducciones_base[0]['ISR'] > 0){

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
                    "importe" => $conceptos_deducciones_base[0]['ISR']
                  ];
    $cont_deducciones++;
}

if($conceptos_deducciones_base[0]['cuotaIMSS'] > 0){

    $stmt = $conn->prepare('SELECT clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 1 AND empresa_id = :idEmpresa');
    $stmt->bindValue(':idEmpresa', $idEmpresa);
    $stmt->execute();
    $clave_imss= $stmt->fetch();

    if(trim($clave_imss['clave']) == ""){
        $respuesta->estatus = "fallo-imss";
        $respuesta = json_encode($respuesta);
        echo $respuesta;
        return;
    }
    //isr
    $deducciones[$cont_deducciones] = [
                    "tipo_deduccion" => "001",
                    "clave" => trim($clave_imss['clave']),
                    "importe" => $conceptos_deducciones_base[0]['cuotaIMSS']
                  ];
    $cont_deducciones++;
}

//Calculo de conceptos de deduccion para el calculo de impuestos
$stmt = $conn->prepare('SELECT dnde.relacion_tipo_deduccion_id ,dnde.tipo_concepto, dnde.importe, dnde.exento, dnde.dias, td.codigo, rtd.clave, ti.codigo as codigo_incapacidad FROM detalle_nomina_deduccion_empleado as dnde INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = dnde.relacion_tipo_deduccion_id AND rtd.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id LEFT JOIN tipo_incapacidad as ti ON ti.id = dnde.incapacidad WHERE dnde.empleado_id = :empleado_id AND dnde.nomina_empleado_id = :nomina_empleado_id');
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->execute();
$conceptos_deducciones = $stmt->fetchAll();

$activar_incapacidad = 0;
$cantidad_incapacidad = 0;
foreach ($conceptos_deducciones as $cd) {
    //tipo salario
    /*if($c['tipo_concepto'] == 2){

        //no exento
        if($c['exento'] == 0){
            $importe_gravado = ;
            $importe_exento = 0;
        }
        else{
            $importe_gravado = 0;
            $importe_exento = $c['importe'];
        }
        
    }*/

    $deducciones[$cont_deducciones] = [
                    "tipo_deduccion" => $cd['codigo'],
                    "clave" => $cd['clave'],
                    "importe" => $cd['importe']
                  ];
    $cont_deducciones++;

    if($cd['relacion_tipo_deduccion_id'] == 6){

        $incapacidades[$cantidad_incapacidad] = [
                        "dias_incapacidad" => $cd['dias'],
                        "tipo_incapacidad" => $cd['codigo_incapacidad'],
                        "importe_monetario" => $cd['importe']
                      ];//hay que seleccionar la incapacidad
        $activar_incapacidad = 1;
        $cantidad_incapacidad++;
    }

}

/*
echo "<br><br>";
print_r($deducciones);
return;*/

$otros_pagos[] = [
                    "tipo_otro_pago" => "002",
                    "clave" => "00000001",
                    "importe" => $conceptos_deducciones_base[0]['SAE'],
                    "subsidio_causado" => $conceptos_deducciones_base[0]['SAE']
                  ];

$data = new stdClass();
$data->tipo_nomina =  $datos_nomina['tipo_nomina'];
$data->fecha_pago =  $datos_nomina['fecha_pago'];
$data->fecha_inicial_pago =  $datos_nomina['fecha_inicial_pago'];
$data->fecha_final_pago =  $datos_nomina['fecha_final_pago'];
$data->num_dias_pagados =  $datos_nomina['num_dias_pagados'];
$data->emisor =  $emisor;  //opcional
$data->receptor =  $receptor;
$data->percepciones =  $percepciones;
if($cont_deducciones > 0){
    $data->deducciones =  $deducciones;
}
$data->otros_pagos =  $otros_pagos;
if($activar_incapacidad == 1){
    $data->incapacidades =  $incapacidades;
}

/* "departamento" => "Cobranza",  opcional

          opcional
                  "sub_contratacion" => array(
                    array(
                      "rfc_labora" => "RCBJ031210A13",
                      "porcentaje_tiempo" => 45
                    )
                  )

si agregar pero primero para pruebas sin este
                  "banco" => "002", opcional
                "cuenta_bancaria" => "002215911558451272", opcional
*/


/*$percepciones[] = [
                    "percepcion" => $percepcion
                  ];
*/



$complements[] = [
                    "type" => "nomina",
                    "data" => $data,
                    "rfc" => trim($datos_empleado['RFC'])
                 ];

$segundoApellido = "";
if(trim($datos_empleado['SegundoApellido']) != ""){
    $segundoApellido = " ".trim($datos_empleado['SegundoApellido']);
}
$nombre = trim($datos_empleado['Nombres'])." ".trim($datos_empleado['PrimerApellido']).$segundoApellido;

if(trim($datos_empleado['email']) == "" || trim($datos_empleado['email']) == NULL){
    $cliente_api = [
      "email" => trim($datos_empleado['email']),
      "legal_name" => $nombre,
      "tax_id" => trim($datos_empleado['RFC']),
      "address" => array(
        "country"=>"MEX"
      )
    ];
}
else{
    $cliente_api = [
       "legal_name" => $nombre,
       "tax_id" => trim($datos_empleado['RFC']),
       "address" => array(
         "country"=>"MEX"
       )
    ];
}



//type es N por nomina
// customer el id del empleado que se facturara.
//folio_number  se omitira para que se genere automaticamente
if($ids_nomina != ""){
    $documentos_relacionados = array();
    
    foreach($ids_nomina as $idn){
        $stmt = $conn->prepare('SELECT uuid FROM bitacora_cfdi_eliminado_nomina WHERE id ='.$idn);
        $stmt->execute();
        $uuid_nomina = $stmt->fetch();
        
        array_push($documentos_relacionados, $uuid_nomina['uuid']);
        
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


$datosFacturaTimbrada = $facturapi->Invoices->create( $invoice );

/*
echo "<br><br>";
var_dump($datosFacturaTimbrada);*/


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

        
        $idFactura = $datosFacturaTimbrada->id;
        $uuid = $datosFacturaTimbrada->uuid;
        $total_timbrado = $datosFacturaTimbrada->total;
        $fechaTimbrado = date("Y-m-d");
        $fechaTimbradoFormat = date("d/m/Y", strtotime($fechaTimbrado));  

        $stmt = $conn->prepare('UPDATE nomina_empleado SET idFactura = :idFactura, uuid = :uuid, total_timbrado = :total_timbrado, estadoTimbrado = 1, fechaTimbrado = :fechaTimbrado WHERE PKNomina = :idNominaEmpleado AND FKNomina = :idNomina AND FKEmpleado = :idEmpleado');
        $stmt->bindValue(':idFactura', $idFactura);
        $stmt->bindValue(':uuid', $uuid);
        $stmt->bindValue(':total_timbrado', $total_timbrado);
        $stmt->bindValue(':fechaTimbrado', $fechaTimbrado);
        $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->bindValue(':idEmpleado', $idEmpleado);

        if($stmt->execute()){
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

          $stmt = $conn->prepare(' SELECT SUM(estadoTimbrado) as total_timbrado, COUNT(PKNomina)  as total_empleados FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0');
          $stmt->bindValue(':idNomina', $idNomina);
          $stmt->execute();
          $nomina_completa = $stmt->fetch();

          if($nomina_completa['total_timbrado'] == $nomina_completa['total_empleados']){
            $stmt = $conn->prepare(' UPDATE nomina SET estatus = 2 WHERE id = :idNomina AND empresa_id = :idEmpresa');
            $stmt->bindValue(':idNomina', $idNomina);
            $stmt->bindValue(':idEmpresa', $idEmpresa);
            $stmt->execute();
            $respuesta->nomina_completa = 1;
          }

        }else{
          $respuesta->estatus = "fallo";
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

$respuesta = json_encode($respuesta);
echo $respuesta;

?>
