<?php
use Facturapi\Facturapi;
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once '../../../vendor/facturapi/facturapi-php/src/Facturapi.php';

$ruta_api = "../../../";
include $ruta_api . "include/functions_api_facturation.php";
$api = new API();

session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];
$respuesta = new stdClass();

$array[0]['estatus'] = "";
$array[0]['estatus_token'] = "";
$array[0]['nomina_completa'] = 0;
$estatus_final = 0;

if(empty($_SESSION['token_ld10d'])) {
    $array[0]['estatus_token'] = "fallo";
    $respuesta->resultado = $array;
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $array[0]['estatus_token'] = "fallo";
    $respuesta->resultado = $array;
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

date_default_timezone_set('America/Mexico_City');
require_once '../../../include/db-conn.php';

$idEmpresa = $_SESSION['IDEmpresa'];
$stmt = $conn->prepare('SELECT clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 2 AND empresa_id = :idEmpresa');
$stmt->bindValue(':idEmpresa', $idEmpresa);
$stmt->execute();
$clave_isr = $stmt->fetch();

if(trim($clave_isr['clave']) == ""){
    $array[0]['estatus_token'] = "fallo-isr";
    $respuesta->resultado = $array;
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

$GLOBALS['rutaFuncion'] = '../';
require_once '../../../functions/funcionNomina.php';

//informacion que necesita recibir
$idNomina = $_POST['idNomina'];//id tabla nomina_empleado  FKNomina, es el id PK de la tabla nomina  2

$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" OR descripcion = "Salario_Minimo_Norte" ORDER BY PKParametros Asc');
$stmt->execute();
$row_parametros = $stmt->fetchAll();
$UMA = $row_parametros[0]['cantidad'];
$factor_mes = $row_parametros[3]['cantidad'];
$salario_minimo_nacional = $row_parametros[1]['cantidad'];
$salario_minimo_norte = $row_parametros[2]['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_aguinaldo = $stmt->fetch();
$dias_aguinaldo = $row_aguinaldo['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_prima_vacacional = $stmt->fetch();
$prima_vacacional_tasa = $row_prima_vacacional['cantidad'] / 100;


$stmt = $conn->prepare("SELECT PKNomina, FKEmpleado FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0 AND estadoTimbrado = 0 OR estadoTimbrado = 2");
$stmt->bindValue(":idNomina",$idNomina);
$stmt->execute();
$empleados = $stmt->fetchAll();

$cont = 0;

foreach ($empleados as $emp) {

  unset($emisor);
  unset($receptor);
  unset($percepciones);
  unset($data);

  unset($percepcion);
  unset($deducciones);
  unset($incapacidades);
  unset($otros_pagos);
  unset($complements);
  unset($cliente_api);

  $array[$cont]['estatus_curp'] = "";
  $array[$cont]['mensaje_curp'] = "";  
  $array[$cont]['estatus_rfc'] = "";
  $array[$cont]['mensaje_rfc'] = "";
  $array[$cont]['estatus_imss'] = "";
  $array[$cont]['mensaje_imss'] = "";
  $array[$cont]['estatus_timbrado'] = "";
  $array[$cont]['mensaje_timbrado'] = "";
  $array[$cont]['estatus_curp'] = "";
  $array[$cont]['mensaje_curp'] = "";
  $array[$cont]['estatus_ind'] = "";
  $array[$cont]['estatus_curpt'] = "";
  $array[$cont]['mensaje_curpt'] = "";  

  $stmt = $conn->prepare("SELECT e.PKEmpleado ,e.Nombres, e.PrimerApellido, e.SegundoApellido, e.CURP, e.CP, dme.NSS as num_seguridad_social, e.RFC, e.idtimbrado, e.email, CONCAT(dle.FechaIngreso,'T06:00:00.000Z') as fecha_inicio_rel_laboral, tc.codigo as tipo_contrato, tj.codigo as tipo_jornada, tr.codigo as tipo_regimen, e.id_empleado as num_empleado, p.puesto, rp.codigo as riesgo_puesto,  pp.Codigo as periodicidad_pago, ef.Codigo as clave_ent_fed, dle.Sueldo, crf.clave as clave_regimen_fiscal, dle.SalarioBaseCotizacionFijo, dle.SalarioBaseCotizacionVariable FROM empleados as e LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado LEFT JOIN tipo_contrato as tc ON tc.id = dle.FKTipoContrato LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN tipo_jornada as tj ON tj.id = t.tipo_jornada_id LEFT JOIN tipo_regimen as tr ON tr.id = dle.FKRegimen LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN riesgo_puesto as rp ON rp.id = dle.FKRiesgoPuesto LEFT JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN estados_federativos as ef ON ef.PKEstado = e.FKEstado LEFT JOIN claves_regimen_fiscal as crf ON crf.id = e.claves_regimen_fiscal_id WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = :empresa_id");
  $stmt->bindValue(":idEmpleado",$emp['FKEmpleado']);
  $stmt->bindValue(":empresa_id",$idEmpresa);
  $stmt->execute();
  $datos_empleado = $stmt->fetch();

  $array[$cont]['nombre'] = $datos_empleado['Nombres']." ".$datos_empleado['PrimerApellido']." ".$datos_empleado['SegundoApellido'];
  $array[$cont]['idempleado'] = $datos_empleado['PKEmpleado'];

  if(trim($datos_empleado['CURP']) == ""){
    $array[$cont]['estatus_curp'] = "fallo-general";
    $array[$cont]['mensaje_curp'] = "Ingresa un CURP al empleado para poder timbrar.";
    $array[$cont]['estatus_ind'] = "fallo";
    $cont++;
    $estatus_final++;
    continue;
  }

  if(trim($datos_empleado['RFC']) == ""){
    $array[$cont]['estatus_rfc'] = "fallo-general";
    $array[$cont]['mensaje_rfc'] = "Ingresa un RFC al empleado para poder timbrar.";
    $array[$cont]['estatus_ind'] = "fallo";
    $cont++;
    $estatus_final++;
    continue;
  }

  if(trim($datos_empleado['num_seguridad_social']) == ""){
      $array[$cont]['estatus_imss'] = "fallo-general";
      $array[$cont]['mensaje_imss'] = "Ingresa el número de seguridad social al empleado para poder timbrar.";
      $array[$cont]['estatus_ind'] = "fallo";
      $cont++;
      $estatus_final++;
      continue;
  }

  if(trim($datos_empleado['idtimbrado']) == ""){
      $array[$cont]['estatus_timbrado'] = "fallo-general";
      $array[$cont]['mensaje_timbrado'] = "El RFC del empleado no esta dado de alta, ingresalo en empleados.";
      $array[$cont]['estatus_ind'] = "fallo";
      $cont++;
      $estatus_final++;
      continue;
  }

  if(trim($datos_empleado['CP']) == ""){
      $array[$cont]['estatus_timbrado'] = "fallo-general";
      $array[$cont]['mensaje_timbrado'] = "Ingresa el código postal del empleado para poder timbrar.";
      $array[$cont]['estatus_ind'] = "fallo";
      $cont++;
      $estatus_final++;
      continue;
  }

  if(trim($datos_empleado['clave_regimen_fiscal']) == ""){
      $array[$cont]['estatus_timbrado'] = "fallo-general";
      $array[$cont]['mensaje_timbrado'] = "Ingresa la clave del regimen fiscal del empleado para poder timbrar.";
      $array[$cont]['estatus_ind'] = "fallo";
      $cont++;
      $estatus_final++;
      continue;
  }

  $stmt = $conn->prepare("SELECT if(np.tipo_id = 1, 'O','E') as tipo_nomina, CONCAT(n.fecha_pago,'T06:00:00.000Z') as fecha_pago, n.fecha_pago as fecha_pago_or, CONCAT(n.fecha_inicio,'T06:00:00.000Z') as fecha_inicial_pago, CONCAT(n.fecha_fin,'T06:00:00.000Z') as fecha_final_pago, n.fecha_fin, pp.DiasPago as num_dias_pagados  FROM nomina as n INNER JOIN nomina_principal as np ON np.id = n.fk_nomina_principal INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = np.periodo_id WHERE n.id = :idNomina AND n.empresa_id = :empresa_id");
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
  //$emisor->rfc = $key_company_api[0]['RFC'];

  if(strlen(trim($key_company_api[0]['RFC'])) == 13){
    $emisor->curp =  $key_company_api[0]['curp'];
  }

  //*********CALCULAR SDI*******////////

  $diasPeriodoIMSS = bcdiv($datos_nomina['num_dias_pagados'], '1', 0);
  $base_imss_general = $datos_empleado['Sueldo'];

  $ruta = "../";
  /*require_once($ruta.'../../functions/funcion_calculovacaciones.php');

  $salarioBaseDiario = number_format($base_imss_general / $diasPeriodoIMSS,2, '.', '');// se calculo con lo dias del periodo, ya que no afecta los dias trabajados por el empleado, sino el periodo, al igual el salario, es su pago completo
  $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
  $SDI = bcdiv($salarioBaseDiario * $factorSDI,1, 2);//Salario Base Cotizacion o Salario Diario Integrado, es igual*/
  //*********FIN CALCULAR SDI*******////////

  if($datos_empleado['SalarioBaseCotizacionFijo'] == null || $datos_empleado['SalarioBaseCotizacionFijo'] == NULL){
    $salarioBaseCotizacionFijo = 0.00;
  }
  else{
    $salarioBaseCotizacionFijo = $datos_empleado['SalarioBaseCotizacionFijo'];
  }

  if($datos_empleado['SalarioBaseCotizacionVariable'] == null || $datos_empleado['SalarioBaseCotizacionVariable'] == NULL){
    $salarioBaseCotizacionVariable = 0.00;
  }
  else{
    $salarioBaseCotizacionVariable = $datos_empleado['SalarioBaseCotizacionVariable'];
  }

  $anioUMA = calcularAnio($datos_nomina['fecha_pago_or'], 1); //obtiene el anio de la fecha de facturacion
  $UMA = getUMA($anioUMA);

  $SDI = $salarioBaseCotizacionFijo + $salarioBaseCotizacionVariable;
  $limiteUMA = $UMA * 25; 

  if($SDI > $limiteUMA){
      $SBC = $limiteUMA;
  }
  else{
      $SBC = $SDI;
  }

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
  $receptor->periodicidad_pago =  $datos_empleado['periodicidad_pago'];
  $receptor->clave_ent_fed =  $datos_empleado['clave_ent_fed'];  
  $receptor->antiguedad =  true; 
  $receptor->salario_diario_integrado = $SDI;
  $receptor->salario_base_cot_apor = $SBC;

  //Calculo de conceptos para el total de calculo de impuestos
  $stmt = $conn->prepare('SELECT dnpe.tipo_concepto, dnpe.importe, dnpe.importe_exento, dnpe.exento, dnpe.dias, dnpe.horas, tp.codigo, rtp.clave, dnpe.relacion_tipo_percepcion_id, rcp.concepto_nomina FROM detalle_nomina_percepcion_empleado as dnpe INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = dnpe.relacion_tipo_percepcion_id AND rtp.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN relacion_concepto_percepcion as rcp ON rcp.id = dnpe.relacion_concepto_percepcion_id AND rcp.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id  WHERE dnpe.empleado_id = :empleado_id AND dnpe.nomina_empleado_id = :nomina_empleado_id');
  $stmt->bindValue(':empleado_id', $emp['FKEmpleado']);
  $stmt->bindValue(':nomina_empleado_id', $emp['PKNomina']);
  $stmt->execute();
  $conceptos = $stmt->fetchAll();

  if(count($conceptos) < 1){
      $array[$cont]['estatus_timbrado'] = "fallo-general";
      $array[$cont]['mensaje_timbrado'] = "Tienes que ingresar al menos un concepto.";
      $array[$cont]['estatus_ind'] = "fallo";
      $cont++;
      $estatus_final++;
      continue;
      
  }

  //print_r($conceptos);
  //echo "<br><br>//////";
  $x = 0;
  foreach ($conceptos as $c) {
      //tipo salario
      $importe_gravado = $c['importe'];
      $importe_exento = $c['importe_exento'];

      //horas extras
      //simples
      if($c['tipo_concepto'] == 3){
          $tipo_horas = '03';
          $importe_pagado = $c['importe'] + $c['importe_exento'];
      }
      //dobles
      if($c['tipo_concepto'] == 7){
          $tipo_horas = '01';
          $importe_pagado = $c['importe'] + $c['importe_exento'];

      }
      //triples
      if($c['tipo_concepto'] == 8){
          $tipo_horas = '02';
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
                          "horas_extra" => $horas_extras,
                          "concepto" => $c['concepto_nomina']
                        ];
      }
      else{
          $percepcion[$x] = [
                          "tipo_percepcion" =>   $c['codigo'],
                          "clave" => $c['clave'],
                          "importe_gravado" => $importe_gravado,
                          "importe_exento" =>  $importe_exento,
                          "concepto" => $c['concepto_nomina']
                        ];
      }
      $x++;
  }
  //print_r($percepcion);
  //return;
  $percepciones = new stdClass();
  $percepciones->percepcion =   $percepcion;


  $stmt = $conn->prepare('SELECT ISR, SAE, cuotaIMSS FROM nomina_empleado WHERE FKEmpleado = :empleado_id AND PKNomina = :nomina_empleado_id');
  $stmt->bindValue(':empleado_id', $emp['FKEmpleado']);
  $stmt->bindValue(':nomina_empleado_id', $emp['PKNomina'] );
  $stmt->execute();
  $conceptos_deducciones_base = $stmt->fetchAll();

  //print_r($conceptos_deducciones_base);
  $cont_deducciones = 0;
  if($conceptos_deducciones_base[0]['ISR'] > 0){

      $stmt = $conn->prepare('SELECT clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 2 AND empresa_id = :idEmpresa');
      $stmt->bindValue(':idEmpresa', $idEmpresa);
      $stmt->execute();
      $clave_isr = $stmt->fetch();
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
      //isr
      $deducciones[$cont_deducciones] = [
                      "tipo_deduccion" => "001",
                      "clave" => trim($clave_imss['clave']),
                      "importe" => $conceptos_deducciones_base[0]['cuotaIMSS']
                    ];
      $cont_deducciones++;
  }


  //Calculo de conceptos de deduccion para el calculo de impuestos
  $stmt = $conn->prepare('SELECT dnde.relacion_tipo_deduccion_id ,dnde.tipo_concepto, dnde.importe, dnde.exento, dnde.dias, td.codigo, rtd.clave, ti.codigo as codigo_incapacidad, rcd.concepto_nomina FROM detalle_nomina_deduccion_empleado as dnde INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = dnde.relacion_tipo_deduccion_id AND rtd.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN relacion_concepto_deduccion as rcd ON rcd.id = dnde.relacion_concepto_deduccion_id AND rcd.empresa_id = '.$_SESSION['IDEmpresa'].' INNER JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id LEFT JOIN tipo_incapacidad as ti ON ti.id = dnde.incapacidad WHERE dnde.empleado_id = :empleado_id AND dnde.nomina_empleado_id = :nomina_empleado_id');
  $stmt->bindValue(':empleado_id', $emp['FKEmpleado']);
  $stmt->bindValue(':nomina_empleado_id', $emp['PKNomina']);
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
                      "importe" => $cd['importe'],
                      "concepto" => $cd['concepto_nomina']
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

  $stmt = $conn->prepare('SELECT concepto_nomina FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = 2 AND empresa_id = :empresa_id');
  $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
  $stmt->execute();
  $concepto_ajuste = $stmt->fetch();
  $concepto_ajuste_cant = $stmt->rowCount();

  if($concepto_ajuste_cant < 1){
      $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
                $stmt->bindValue(":concepto_nomina", "Subsidio para el empleo");
                $stmt->bindValue(":tipo_otros_pagos_id", 2);
                $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                $stmt->execute();
      $concepto_ajuste_otros_pagos = "Subsidio para el empleo";
  }
  else{
      $concepto_ajuste_otros_pagos = $concepto_ajuste['concepto_nomina'];
  }

  $otros_pagos[] = [
                      "tipo_otro_pago" => "002",
                      "clave" => "002",
                      "importe" => $conceptos_deducciones_base[0]['SAE'],
                      "subsidio_causado" => $conceptos_deducciones_base[0]['SAE'],
                      "concepto" => $concepto_ajuste_otros_pagos
                    ];
  $cont_otros_pagos = 1;
  //Calculo de conceptos de otros pagos
  //relacion_concepto_otros_pagos con detalle_otros_pagos_nomina_empleado se relaciona con el principal por lo que no es necesario el id de la empresa
  $stmt = $conn->prepare('SELECT dopne.importe, op.codigo, rcop.concepto_nomina FROM detalle_otros_pagos_nomina_empleado as dopne INNER JOIN otros_pagos as op ON op.id = dopne.otros_pagos_id INNER JOIN relacion_concepto_otros_pagos as rcop ON rcop.id = dopne.relacion_concepto_otros_pagos_id WHERE dopne.empleado_id = :empleado_id AND dopne.nomina_empleado_id = :nomina_empleado_id');
  $stmt->bindValue(':empleado_id', $emp['FKEmpleado']);
  $stmt->bindValue(':nomina_empleado_id', $emp['PKNomina']);
  $stmt->execute();
  $conceptos_otros_pagos = $stmt->fetchAll();
  $conceptos_otros_pagos_cant = $stmt->rowCount();

  if($conceptos_otros_pagos_cant > 0){

      foreach($conceptos_otros_pagos as $cop){

          if($cop['codigo'] == '002'){
              $otros_pagos[] = [
                                  "tipo_otro_pago" => "002",
                                  "clave" => "002",
                                  "importe" => $cop['importe'],
                                  "subsidio_causado" => $cop['importe'],
                                  "concepto" => $cop['concepto_nomina']
                                ];
          }
          else{
              $otros_pagos[] = [
                                  "tipo_otro_pago" => $cop['codigo'],
                                  "clave" => $cop['codigo'],
                                  "importe" => $cop['importe'],
                                  "concepto" => $cop['concepto_nomina']
                                ];
          }
      }
  }

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
  $invoice = array(
            "type" => "N",  
            "customer" => $cliente_api,
            "complements" => $complements
          );


  $datosFacturaTimbrada = $facturapi->Invoices->create( $invoice );

  /*echo "<br><br>";
  var_dump($datosFacturaTimbrada);*/

  if(isset($datosFacturaTimbrada->message)){

    if(strpos($datosFacturaTimbrada->message, "curp")){
        $array[$cont]['estatus_curpt'] = "fallo-general";
        $array[$cont]['mensaje_curpt'] = "La CURP no es válida.";
        $array[$cont]['estatus_ind'] = "fallo";
        $cont++;
        $estatus_final++;
        continue;
    }

  }

  if($datosFacturaTimbrada->status == 'valid'){

      
      $idFactura = $datosFacturaTimbrada->id;
      $uuid = $datosFacturaTimbrada->uuid;
      $total_timbrado = $datosFacturaTimbrada->total;
      $array[$cont]['idfactura'] = $idFactura;
      $fechaTimbrado = date("Y-m-d");
      $fechaTimbradoFormat = date("d/m/Y", strtotime($fechaTimbrado));  

      $stmt = $conn->prepare('UPDATE nomina_empleado SET idFactura = :idFactura, uuid = :uuid, total_timbrado = :total_timbrado, estadoTimbrado = 1, fechaTimbrado = :fechaTimbrado WHERE PKNomina = :idNominaEmpleado AND FKNomina = :idNomina AND FKEmpleado = :idEmpleado');
      $stmt->bindValue(':idFactura', $idFactura);
      $stmt->bindValue(':uuid', $uuid);
      $stmt->bindValue(':total_timbrado', $total_timbrado);
      $stmt->bindValue(':fechaTimbrado', $fechaTimbrado);
      $stmt->bindValue(':idNominaEmpleado', $emp['PKNomina']);
      $stmt->bindValue(':idNomina', $idNomina);
      $stmt->bindValue(':idEmpleado', $emp['FKEmpleado']);

      if($stmt->execute()){
        $respuesta->idFactura = $idFactura;
        $respuesta->fechaTimbrado = $fechaTimbradoFormat;
        $array[$cont]['estatus_ind'] = "exito";

        $mensaje = $api->sendEmailInvoice($key_company_api[0]['key_company'],$idFactura,$datos_empleado['email']);

        if($mensaje->ok){
          $array[$cont]['estatus_email'] = "exito";
        }
        else{
          $array[$cont]['estatus_email'] = "fallo";
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
          $array[0]['nomina_completa'] = 1;
        }

      }else{
        $array[$cont]['estatus_ind'] = "fallo";
        $estatus_final++;
      }
  }
  else{
      $array[$cont]['estatus_ind'] = "fallo";
      $cont++;
      $estatus_final++;
      continue;
  }
  $cont++;
}//fin foreach


if($estatus_final == 0){
  $array[0]['estatus'] = "exito";
}
else{
  $array[0]['estatus'] = "fallo";
}

  
$respuesta->resultado = $array;

$respuesta = json_encode($respuesta);
echo $respuesta;

?>
