<?php
date_default_timezone_set('America/Mexico_City');

//echo "idnOmina ".$idNomina;

$stmt = $conn->prepare("SELECT n.no_nomina, np.sucursal_id, s.sucursal, np.periodo_id, pp.Periodo, pp.DiasPago, np.tipo_id, tn.tipo, n.no_empleados, DATE_FORMAT(n.fecha_pago, '%d/%m/%Y') as fecha_pago, n.fecha_pago as fecha_pago_or, n.fecha_inicio as fecha_inicio_or, DATE_FORMAT(n.fecha_inicio, '%d/%m/%Y') as fecha_inicio,n.fecha_fin as fecha_fin_or, DATE_FORMAT(n.fecha_fin, '%d/%m/%Y') as fecha_fin, n.total, n.estatus, n.ultima_nomina, n.autorizada, np.confidencial FROM nomina as n INNER JOIN nomina_principal as np ON np.id = n.fk_nomina_principal LEFT JOIN sucursales as s ON s.id = np.sucursal_id LEFT JOIN periodo_pago as pp  ON pp.PKPeriodo_pago = np.periodo_id LEFT JOIN tipo_nomina as tn ON tn.id = np.tipo_id WHERE n.id = :id ");
$stmt->execute(array(':id'=>$idNomina));
$row_datos_nomina = $stmt->fetch();
$fechaPago  = $row_datos_nomina['fecha_pago_or'];
//print_r($row_datos_nomina);


//solo nominas no timbradas
if($row_datos_nomina['estatus'] == 1){

        $idSucursal = $row_datos_nomina['sucursal_id'];
        $tipo_nomina = $row_datos_nomina['tipo_id'];
        //echo $idSucursal." -- ".$row_datos_nomina['periodo_id']." -- ".$row_datos_nomina['fecha_fin_or'];
        $stmt = $conn->prepare('SELECT e.PKEmpleado, dle.Infonavit, dle.DeudaInterna, dle.Sueldo, t.Dias_de_trabajo FROM empleados as e INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado INNER JOIN turnos as t ON t.PKTurno = dle.FKTurno WHERE e.estatus = 1 AND dle.FKSucursal = :idsucursal AND dle.FKPeriodo = :idperiodo AND e.empresa_id = '.$_SESSION['IDEmpresa'].' AND dle.FechaIngreso <= :fecha_fin AND dle.Confidencial = :confidencial ');
        $stmt->bindValue(":idsucursal", $idSucursal);
        $stmt->bindValue(":idperiodo", $row_datos_nomina['periodo_id']);
        $stmt->bindValue(":fecha_fin", $row_datos_nomina['fecha_fin_or']); 
        $stmt->bindValue(":confidencial", $row_datos_nomina['confidencial']); 
        $stmt->execute();
        $empleados = $stmt->fetchAll();
        //var_dump($empleados);

        //obtienes las percepciones de la empresa y por sucursal
        $stmt = $conn->prepare('SELECT rbp.relacion_concepto_percepcion_id, rtp.tipo_percepcion_id, rbp.tipo_base, rbp.cantidad, rtp.clave FROM relacion_base_percepcion as rbp 
          LEFT JOIN relacion_concepto_percepcion as rcp ON rcp.id = rbp.relacion_concepto_percepcion_id AND rcp.empresa_id = :empresa_id1
          LEFT JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = rcp.tipo_percepcion_id AND rtp.empresa_id = :empresa_id2
          WHERE rbp.empresa_id = :empresa_id3');
        $stmt->bindValue(":empresa_id1", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":empresa_id2", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":empresa_id3", $_SESSION['IDEmpresa']);
        $stmt->execute();
        $cuenta_percepciones = $stmt->rowCount();
        $row_percepciones = $stmt->fetchAll();

        $stmt = $conn->prepare('SELECT rsp.relacion_concepto_percepcion_id, rcp.tipo_percepcion_id, rsp.tipo_base, rsp.cantidad, rtp.clave 
            FROM relacion_sucursal_percepcion as rsp 
            LEFT JOIN relacion_concepto_percepcion as rcp ON rcp.id = rsp.relacion_concepto_percepcion_id AND rcp.empresa_id = :empresa_id1 
            LEFT JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = rcp.tipo_percepcion_id AND rtp.empresa_id = :empresa_id2 
            WHERE rsp.sucursal_id = :sucursal_id AND rsp.empresa_id = :empresa_id3 ');
        $stmt->bindValue(":empresa_id1", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":sucursal_id", $idSucursal);
        $stmt->bindValue(":empresa_id2", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":empresa_id3", $_SESSION['IDEmpresa']);
        $stmt->execute();
        $cuenta_percepciones_x_sucursal = $stmt->rowCount();
        $row_percepciones_x_sucursal = $stmt->fetchAll();

        /*echo "<pre>",print_r($row_percepciones),"</pre>";
        echo "///////////////////////////";
        echo "<pre>",print_r($row_percepciones_x_sucursal),"</pre>";*/

        //obtienes las deducciones de la empresa y por sucursal
        $stmt = $conn->prepare('SELECT rbd.relacion_concepto_deduccion_id, rtd.tipo_deduccion_id, rbd.tipo_base, rbd.cantidad, rtd.clave FROM relacion_base_deduccion as rbd
          LEFT JOIN relacion_concepto_deduccion as rcd ON rcd.id = rbd.relacion_concepto_deduccion_id AND rcd.empresa_id = :empresa_id1
          LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = rcd.tipo_deduccion_id AND rtd.empresa_id = :empresa_id2 
          WHERE rbd.empresa_id = :empresa_id3');

        $stmt->bindValue(":empresa_id1", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":empresa_id2", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":empresa_id3", $_SESSION['IDEmpresa']);
        $stmt->execute();
        $cuenta_deducciones = $stmt->rowCount();
        $row_deduccion = $stmt->fetchAll();

        $stmt = $conn->prepare('SELECT rsd.relacion_concepto_deduccion_id, rcd.tipo_deduccion_id, rsd.tipo_base, rsd.cantidad, rtd.clave 
          FROM relacion_sucursal_deduccion as rsd 
          LEFT JOIN relacion_concepto_deduccion as rcd ON rcd.id = rsd.relacion_concepto_deduccion_id AND rcd.empresa_id = :empresa_id1
          LEFT JOIN relacion_tipo_deduccion as rtd ON rcd.tipo_deduccion_id = rtd.tipo_deduccion_id AND rtd.empresa_id = :empresa_id2
          WHERE rsd.sucursal_id = :sucursal_id AND rsd.empresa_id = :empresa_id3');
        $stmt->bindValue(":empresa_id1", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":empresa_id2", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":sucursal_id", $idSucursal);
        $stmt->bindValue(":empresa_id3", $_SESSION['IDEmpresa']);
        $stmt->execute();
        $cuenta_deducciones_x_sucursal = $stmt->rowCount();
        $row_deducciones_x_sucursal = $stmt->fetchAll();

        /*
        echo "<pre>",print_r($row_deduccion),"</pre>";
        echo "///////////////////////////";
        echo "<pre>",print_r($row_deducciones_x_sucursal),"</pre>";*/

        foreach ($empleados as $emp) {

            $stmt = $conn->prepare("SELECT PKNomina FROM nomina_empleado WHERE FKEmpleado = :FKEmpleado AND FKNomina = :FKNomina");
            $stmt->execute(array(':FKEmpleado'=>$emp['PKEmpleado'],':FKNomina'=>$idNomina));
            $existeNominaEmpleado = $stmt->rowCount();

            $diasTrabajoNombre = json_decode($emp['Dias_de_trabajo'], true);
            //var_dump($diasTrabajoNombre);

            $stmt = $conn->prepare("SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina");
            $stmt->execute(array(':idNomina'=>$idNomina));
            $periodo_trabajo = $stmt->fetch();
            //var_dump($periodo_trabajo);


            $startDate = new DateTime($periodo_trabajo['fecha_inicio']);
            $endDate = new DateTime($periodo_trabajo['fecha_fin']);

            //echo "start ".$startDate->format('w');
            //$totalDomingos

            $diasTrabajo = 0;
            $diasDescanso = 0;
            while ($startDate <= $endDate) {

                //Domingo
                if ($startDate->format('w') == 0) {
                    if($diasTrabajoNombre["domingo"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                if ($startDate->format('w') == 1) {
                    if($diasTrabajoNombre["lunes"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                if ($startDate->format('w') == 2) {
                    if($diasTrabajoNombre["martes"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                if ($startDate->format('w') == 3) {
                    if($diasTrabajoNombre["miercoles"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                if ($startDate->format('w') == 4) {
                    if($diasTrabajoNombre["jueves"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                if ($startDate->format('w') == 5) {
                    if($diasTrabajoNombre["viernes"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                if ($startDate->format('w') == 6) {
                    if($diasTrabajoNombre["sabado"] == 1){
                        $diasTrabajo++;
                    }
                    else{
                        $diasDescanso++;
                    }
                }
                
                $startDate->modify('+1 day');
            }
            $totalDias = $diasTrabajo + $diasDescanso; 
            //echo "dias ".$diasTrabajo." //".$diasDescanso;

            

            if($existeNominaEmpleado < 1){

                try{
                      $conn->beginTransaction();
                      $idEmpleado = $emp['PKEmpleado'];

                      if($tipo_nomina == 1){
                        $total_empleado_nomina = $emp['Sueldo'];

                        $nominaTrabajo = ($emp['Sueldo'] / $totalDias) * $diasTrabajo;
                        $nominaDescanso = ($emp['Sueldo'] / $totalDias) * $diasDescanso;

                        //echo " --- ".$nominaTrabajo." --- ".$nominaDescanso;
                      }
                      else{
                        $total_empleado_nomina = 0.00;
                      }

                      $stmt = $conn->prepare('INSERT INTO nomina_empleado (FKEmpleado, DescuentoInfonavit, DescuentoDeuda, ISR, cuotaIMSS, Total, TotalNeto, Exento, FKNomina) VALUES (:FKEmpleado, :DescuentoInfonavit,:DescuentoDeuda,0.00,0.00,:Salario,0.00, 0,:FKNomina)');
                      $stmt->bindValue(":FKEmpleado", $emp['PKEmpleado']);
                      $stmt->bindValue(":DescuentoInfonavit", $emp['Infonavit']);
                      $stmt->bindValue(":DescuentoDeuda", $emp['DeudaInterna']);
                      $stmt->bindValue(":Salario", $total_empleado_nomina);
                      $stmt->bindValue(":FKNomina", $idNomina);
                      $stmt->execute();

                      if($tipo_nomina == 1){
                          $idNominaEmpleado = $conn->lastInsertId();
                          
                          $stmt = $conn->prepare("SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 1 AND empresa_id = :empresa_id");
                          $stmt->execute(array(':empresa_id'=>$_SESSION['IDEmpresa']));
                          $concepto_sueldo = $stmt->fetch();
                          $existe_concepto_sueldo = $stmt->rowCount();

                          if($existe_concepto_sueldo > 0){
                            $tipo_percepcion_sueldo = $concepto_sueldo['id'];
                          }
                          else{
                            $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
                            $stmt->bindValue(":concepto_nomina", 'Sueldo');
                            $stmt->bindValue(":tipo_percepcion_id",1);
                            $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                            $stmt->execute();

                            $tipo_percepcion_sueldo = $conn->lastInsertId();
                          }

                          //Agregar septimo dia si no esta agregado
                          $stmt = $conn->prepare("SELECT id FROM relacion_concepto_percepcion WHERE concepto_nomina = 'Séptimo día' AND empresa_id = :empresa_id");
                          $stmt->execute(array(':empresa_id'=>$_SESSION['IDEmpresa']));
                          $concepto_septimo_dia = $stmt->fetch();
                          $existe_concepto_septimo_dia = $stmt->rowCount();

                          if($existe_concepto_septimo_dia > 0){
                            $tipo_percepcion_septimo_dia = $concepto_septimo_dia['id'];
                          }
                          else{
                            $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
                            $stmt->bindValue(":concepto_nomina", 'Séptimo día');
                            $stmt->bindValue(":tipo_percepcion_id",1);
                            $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                            $stmt->execute();

                            $tipo_percepcion_septimo_dia = $conn->lastInsertId();
                          }

                          //ingresa detalle del sueldo del empleado de los días trabajados
                          $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_percepcion_id, :relacion_concepto_percepcion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                          $stmt->bindValue(":relacion_tipo_percepcion_id", 1);
                          $stmt->bindValue(":relacion_concepto_percepcion_id", $tipo_percepcion_sueldo);
                          $stmt->bindValue(":tipo_concepto", 1);
                          $stmt->bindValue(":importe", $nominaTrabajo);
                          $stmt->bindValue(":exento", 0);
                          $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                          $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                          $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                          $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                          $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                          $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                          $stmt->execute();

                          //ingresa detalle del sueldo del empleado del septimo dia
                          $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_percepcion_id, :relacion_concepto_percepcion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                          $stmt->bindValue(":relacion_tipo_percepcion_id", 1);
                          $stmt->bindValue(":relacion_concepto_percepcion_id", $tipo_percepcion_septimo_dia);
                          $stmt->bindValue(":tipo_concepto", 11);
                          $stmt->bindValue(":importe", $nominaDescanso);
                          $stmt->bindValue(":exento", 0);
                          $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                          $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                          $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                          $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                          $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                          $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                          $stmt->execute();

                          //se ingresan las percepciones por sucursal
                          if($cuenta_percepciones_x_sucursal > 0){
                            
                            foreach($row_percepciones_x_sucursal as $rps){

                              //deben contar con clave para ingresarse
                              if(trim($rps['clave']) != ""){

                                  $id_percepcion = $rps['tipo_percepcion_id'];
                                  $relacion_concepto_percepcion_id = $rps['relacion_concepto_percepcion_id'];
                                  $cantidad_base = $rps['cantidad'];
                                  $tipo_base = $rps['tipo_base'];

                                  require("calculoPercepcionesIniciales.php");
                                  $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_percepcion_id, :relacion_concepto_percepcion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                                  $stmt->bindValue(":relacion_tipo_percepcion_id", $id_percepcion);
                                  $stmt->bindValue(":relacion_concepto_percepcion_id", $relacion_concepto_percepcion_id);
                                  $stmt->bindValue(":tipo_concepto", 2);//2 es para todas las percepciones
                                  $stmt->bindValue(":importe", $importe_p);
                                  $stmt->bindValue(":exento", $exento_p);
                                  $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                                  $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                                  $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                                  $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                                  $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                                  $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                                  $stmt->execute();
                                  //echo "x sucursal:  percpepcion: ".$id_percepcion." importe: ".$importe_p." <br>";
                              }
                            
                            }

                          }

                          //se ingresan las percepciones por empresa
                          if($cuenta_percepciones > 0){
                            
                            foreach($row_percepciones as $rp){

                              //deben contar con clave para ingresarse
                              if(trim($rp['clave']) != ""){

                                  $id_percepcion = $rp['tipo_percepcion_id'];
                                  $relacion_concepto_percepcion_id = $rp['relacion_concepto_percepcion_id'];
                                  $cantidad_base = $rp['cantidad'];
                                  $tipo_base = $rp['tipo_base'];
                                  $dias = 0;

                                  $clave = array_search($rp['relacion_concepto_percepcion_id'], array_column($row_percepciones_x_sucursal,0));
                                  //echo " asi es //".$clave."//";

                                  //si no encuentra la base si lo ingresa, si no se ingresara por sucursal
                                  if(trim($clave) == "" || $cuenta_percepciones_x_sucursal == 0){   

                                      require("calculoPercepcionesIniciales.php");
                                      $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, dias, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_percepcion_id, :relacion_concepto_percepcion_id, :tipo_concepto, :dias, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                                      $stmt->bindValue(":relacion_tipo_percepcion_id", $id_percepcion);
                                      $stmt->bindValue(":relacion_concepto_percepcion_id", $relacion_concepto_percepcion_id);
                                      $stmt->bindValue(":tipo_concepto", 2);//2 es para todas las percepciones
                                      $stmt->bindValue(":dias", $dias);
                                      $stmt->bindValue(":importe", $importe_p);
                                      $stmt->bindValue(":exento", $exento_p);
                                      $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                                      $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                                      $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                                      $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                                      $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                                      $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                                      $stmt->execute();
                                      //echo "x empresa:  percpepcion: ".$id_percepcion." importe: ".$importe_p." <br>";
                                    
                                  }
                              }
                              
                              
                            }

                          }
//return;

                          //se ingresan las deducciones por sucursal
                          if($cuenta_deducciones_x_sucursal > 0){
                            
                            foreach($row_deducciones_x_sucursal as $rds){

                              //deben contar con clave para ingresarse
                              if(trim($rds['clave']) != ""){
                                  $id_deduccion = $rds['tipo_deduccion_id'];
                                  $relacion_concepto_deduccion_id = $rds['relacion_concepto_deduccion_id'];
                                  $cantidad_base = $rds['cantidad'];
                                  $tipo_base = $rds['tipo_base'];

                                  require("calculoDeduccionesIniciales.php");
                                  $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                                  $stmt->bindValue(":relacion_tipo_deduccion_id", $id_deduccion);
                                  $stmt->bindValue(":relacion_concepto_deduccion_id", $relacion_concepto_deduccion_id);
                                  $stmt->bindValue(":tipo_concepto", 2);//2 es para todas las percepciones y deducciones
                                  $stmt->bindValue(":importe", $importe_p);
                                  $stmt->bindValue(":exento", $exento_p);
                                  $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                                  $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                                  $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                                  $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                                  $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                                  $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                                  $stmt->execute();
                                  //echo "x sucursal:  deduccion: ".$id_deduccion." importe: ".$importe_p." <br>";
                              }
                            
                            }

                          }

                          //se ingresan las deducciones por empresa
                          if($cuenta_deducciones > 0){
                            
                            foreach($row_deduccion as $rd){

                              //deben contar con clave para ingresarse
                              if(trim($rd['clave']) != ""){
                                  $id_deduccion = $rd['tipo_deduccion_id'];
                                  $relacion_concepto_deduccion_id = $rd['relacion_concepto_deduccion_id'];
                                  $cantidad_base = $rd['cantidad'];
                                  $tipo_base = $rd['tipo_base'];

                                  $clave = array_search($rd['relacion_concepto_deduccion_id'], array_column($row_deducciones_x_sucursal,0));
                                  //echo " asi es //".$clave."//";

                                  //si no encuentra la base si lo ingresa, si no se ingresara por sucursal
                                  if(trim($clave) == "" || $cuenta_deducciones_x_sucursal == 0){   

                                      require("calculoDeduccionesIniciales.php");
                                      $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                                      $stmt->bindValue(":relacion_tipo_deduccion_id", $id_deduccion);
                                      $stmt->bindValue(":relacion_concepto_deduccion_id", $relacion_concepto_deduccion_id);
                                      $stmt->bindValue(":tipo_concepto", 2);//2 es para todas las percepciones
                                      $stmt->bindValue(":importe", $importe_p);
                                      $stmt->bindValue(":exento", $exento_p);
                                      $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                                      $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                                      $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                                      $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                                      $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                                      $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                                      $stmt->execute();
                                      //echo "x empresa:  deduccion: ".$id_deduccion." importe: ".$importe_p." <br>";
                                    
                                  }
                              }

                              
                            }

                          }


                          /***********************INGRESAR CREDITOS INFONAVIT, FONACOT, PENSION ALIMENTICIA E INCAPACIDADES***********************************/
                          $idEmpresa = $_SESSION['IDEmpresa'];
                          ////////  INCAPACIDADES  ////////////
                          $stmt = $conn->prepare('SELECT id, dias_restantes, porcentaje_incapacidad, fecha_inicio, en_aplicacion, motivo_incapacidad FROM incapacidades WHERE empleado_id = :empleado_id AND empresa_id = :empresa_id AND estado = 1');
                          $stmt->bindValue(':empleado_id', $emp['PKEmpleado']);
                          $stmt->bindValue(':empresa_id', $idEmpresa);
                          $stmt->execute();
                          $existe_incapacidad = $stmt->rowCount();
//echo "existe incapacidad ".$existe_incapacidad."<br>";

                          if($existe_incapacidad > 0){ 
                            $rowInca = $stmt->fetch();
                            $idIncapacidad = $rowInca['id'];
                            $diasIncapacidad = $rowInca['dias_restantes'];
                            $incapacidadID = $rowInca['motivo_incapacidad'];
                            //print_r($rowInca);
                            //obtener fechas de inicio y fin de la nomina para ver si se aplicara
                            $aplicarIncapacidad = 0;

                            $stmt = $conn->prepare('SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina AND empresa_id = '.$idEmpresa);
                            $stmt->bindValue(':idNomina', $idNomina);
                            $stmt->execute();
                            $datosNomina = $stmt->fetch();
                            $fechaIniNomina = $datosNomina["fecha_inicio"];
                            $fechaFinNomina = $datosNomina["fecha_fin"];

                            $fechaIniIncapacidad = $rowInca['fecha_inicio'];

                            if((strtotime($fechaIniIncapacidad) >= strtotime($fechaIniNomina)  &&  strtotime($fechaIniIncapacidad) <= strtotime($fechaFinNomina))){
                                $aplicarIncapacidad = 1;
                            }

                            //echo "aplicarIncapacidad ".$aplicarIncapacidad."<br>";

                            if($aplicarIncapacidad == 1 || $rowInca['en_aplicacion'] == 1){


                                //FUNCIONES PARA EL CALCULO DE INCAPACIDADES
                                function getDiasDiferencia($fechaIni, $fechaFin){
                                    $fechaIniTiempo = strtotime($fechaIni); 
                                    $fechaFinTiempo = strtotime($fechaFin);
                                    $diferencia_dias_tiempo = $fechaIniTiempo - $fechaFinTiempo;
                                    $diferencia_dias_tiempo = ((round($diferencia_dias_tiempo / (60 * 60 * 24))) * -1) + 1;

                                    return $diferencia_dias_tiempo;
                                }

                                function getDiasTrabajoPorPeriodo($diasTrabajoNombre, $fechaIni, $fechaFin){

                                    /*print_r($diasTrabajoNombre);
                                    echo  "//-".$diasTrabajoNombre["sabado"]."-//<br>";*/
                                    
                                    $startDate = new DateTime($fechaIni);
                                    $endDate = new DateTime($fechaFin);

                                    //echo "start ".$startDate->format('w');
                                    //$totalDomingos

                                    $diasTrabajo = 0;
                                    while ($startDate <= $endDate) {

                                        //Domingo
                                        if ($startDate->format('w') == 0) {
                                            if($diasTrabajoNombre["domingo"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        if ($startDate->format('w') == 1) {
                                            if($diasTrabajoNombre["lunes"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        if ($startDate->format('w') == 2) {
                                            if($diasTrabajoNombre["martes"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        if ($startDate->format('w') == 3) {
                                            if($diasTrabajoNombre["miercoles"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        if ($startDate->format('w') == 4) {
                                            if($diasTrabajoNombre["jueves"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        if ($startDate->format('w') == 5) {
                                            if($diasTrabajoNombre["viernes"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        if ($startDate->format('w') == 6) {
                                            if($diasTrabajoNombre["sabado"] == 1){
                                                $diasTrabajo++;
                                            }
                                        }
                                        
                                        $startDate->modify('+1 day');
                                    }

                                    return $diasTrabajo;
                                }


                                $fecha_fin_incapacidad = date('Y-m-d', strtotime($fechaIniNomina. ' + '.($rowInca['dias_restantes'] - 1).' days'));

                                if(strtotime($fecha_fin_incapacidad) > strtotime($fechaFinNomina)){
                                    $fechaFinPeriodo = $fechaFinNomina;
                                }
                                else{
                                    $fechaFinPeriodo = $fecha_fin_incapacidad;
                                }

                                $diferencia_dias_tiempo = getDiasDiferencia($fechaIniNomina, $fechaFinPeriodo);
                                //echo "diferencia_dias_tiempo ".$diferencia_dias_tiempo."<br>";

                                $GLOBALS['rutaFuncion'] = "./";
                                require_once("../../functions/funcionNomina.php");

                                $resultDias = getDiasTrabajo($idEmpleado);

                                $stmt = $conn->prepare('SELECT t.Dias_de_trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
                                $stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
                                $stmt->bindValue(":idEmpleado", $idEmpleado);
                                $stmt->execute();
                                $resultDias = $stmt->fetch();
                                $diasTrabajoNombre = json_decode($resultDias['Dias_de_trabajo'], true);

                                $diasTrabajo = getDiasTrabajoPorPeriodo($diasTrabajoNombre, $fechaIniNomina, $fechaFinPeriodo);
                                //echo "diasTrabajo ".$diasTrabajo."<br>";

                                if($diasTrabajo < $diferencia_dias_tiempo){
                                    $diasAplicar = $diasTrabajo;
                                }
                                else{
                                    $diasAplicar = $diferencia_dias_tiempo;
                                }
                                //echo "diasAplicar ".$diasAplicar."<br>";

                                if($diasIncapacidad > $diasAplicar){

                                    $fecha_nueva_ini = date('Y-m-d', strtotime($fechaFinPeriodo. ' + 1 days'));
                                    //echo "fecha_nueva_ini ".$fecha_nueva_ini."<br>";

                                    $fecha_nueva_fin = $fechaFinNomina;
                                    //echo "fecha_nueva_fin ".$fecha_nueva_fin."<br>";

                                    $diasTrabajo = getDiasTrabajoPorPeriodo($diasTrabajoNombre, $fecha_nueva_ini, $fecha_nueva_fin);
                                    //echo "diasTrabajo v2 ".$diasTrabajo."<br>";

                                    $diasRestantes = $diasIncapacidad - $diasAplicar;
                                    //echo "diasRestantes v2 ".$diasRestantes."<br>";

                                    if($diasRestantes >= $diasTrabajo){
                                        $sumarDias = $diasTrabajo;
                                    }
                                    else{
                                        $sumarDias = $diasRestantes;
                                    }
                                    //echo "sumarDias v2 ".$sumarDias."<br>";
                                    $diasAplicar = $diasAplicar + $sumarDias;

                                    $fechaFinPeriodo = $fecha_nueva_fin;
                                }
                                

                                $ImporteDiasFalta = calcularSalarioFaltas($idEmpleado, $diasAplicar);
                                //echo "ImporteDiasFalta ".$ImporteDiasFalta."<br>";


                                $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND  empresa_id = '.$idEmpresa);
                                $stmt->bindValue(':tipo_deduccion_id', 6);
                                $stmt->execute();
                                $row_concepto = $stmt->fetch();
                                $idConceptoDeduccion = $row_concepto['id'];

                                $tipo_concepto = 5;// tipo 5 es para faltas e incapacidades
                                $stmt = $conn->prepare('INSERT INTO  detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, incapacidad, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_deduccion_id, :tipo_concepto, :dias, :incapacidad, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                                $stmt->bindValue(':concepto', 6);
                                $stmt->bindValue(':relacion_concepto_deduccion_id', $idConceptoDeduccion);
                                $stmt->bindValue(':tipo_concepto', $tipo_concepto);
                                $stmt->bindValue(':dias', $diasAplicar);
                                $stmt->bindValue(':incapacidad', $incapacidadID);
                                $stmt->bindValue(':importe', $ImporteDiasFalta);
                                $stmt->bindValue(':exento', 0);
                                $stmt->bindValue(':empleado_id', $idEmpleado);
                                $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
                                $stmt->bindValue(':fecha_alta', date("Y-m-d H:i:s"));
                                $stmt->bindValue(':fecha_edicion', date("Y-m-d H:i:s"));
                                $stmt->bindValue(':usuario_alta', $_SESSION['PKUsuario']);
                                $stmt->bindValue(':usuario_edicion', $_SESSION['PKUsuario']);
                                $stmt->execute();

                                $idDetalleNomina = $conn->lastInsertId();

                                $diasFaltantesAplicar = $diasIncapacidad - $diasAplicar;
                                if($diasFaltantesAplicar > 0){
                                    $estado = 1;
                                }
                                else{
                                    $estado = 3;
                                }

                                $stmt = $conn->prepare('UPDATE incapacidades SET dias_restantes = :dias_restantes , en_aplicacion = :en_aplicacion, estado = :estado WHERE id = :id');
                                $stmt->bindValue(':dias_restantes', $diasFaltantesAplicar);
                                $stmt->bindValue(':en_aplicacion', 1);
                                $stmt->bindValue(':estado', $estado);
                                $stmt->bindValue(':id', $idIncapacidad);
                                $stmt->execute();

                                $stmt = $conn->prepare('INSERT INTO incapacidades_registro (fecha_inicio, fecha_fin, dias_agregados, detalle_nomina_deduccion_empleado_id, nomina_empleado_id, usuario_alta, fecha_alta) VALUES (:fecha_inicio, :fecha_fin, :dias_agregados, :detalle_nomina_deduccion_empleado_id, :nomina_empleado_id, :usuario_alta, :fecha_alta)');
                                $stmt->bindValue(':fecha_inicio', $fechaIniNomina);
                                $stmt->bindValue(':fecha_fin', $fechaFinPeriodo);
                                $stmt->bindValue(':dias_agregados', $diasAplicar);
                                $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
                                $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
                                $stmt->bindValue(':usuario_alta', $_SESSION['PKUsuario']);
                                $stmt->bindValue(':fecha_alta', date("Y-m-d H:i:s"));
                                $stmt->execute();

                            }

                          }

                          ////////  CREDITO INFONAVIT  ////////////
                          $stmt = $conn->prepare('SELECT id, importe_fijo, relacion_concepto_deduccion_id, fecha_aplicacion, fecha_suspension, importe_acumulado, veces_aplicadas FROM credito_infonavit WHERE empleado_id = :empleado_id AND empresa_id = :empresa_id AND estado = 1');
                          $stmt->bindValue(':empleado_id', $emp['PKEmpleado']);
                          $stmt->bindValue(':empresa_id', $idEmpresa);
                          $stmt->execute();
                          $existe_credito_infonavit = $stmt->rowCount();

                          if($existe_credito_infonavit > 0){
                            $rowInfo = $stmt->fetch();

                            $idCreditoInfonavit = $rowInfo['id'];
                            $importeAcumulado = $rowInfo['importe_acumulado'];
                            $fechaAplicacion = $rowInfo['fecha_aplicacion'];
                            $fechaSuspension = $rowInfo['fecha_suspension'];
                            $CuotaFija = $rowInfo['importe_fijo'];
                            //echo "CuotaFija ".$CuotaFija."<br>";
                            require_once("calculoinfonavit.php");

                            if($aplicarCreditoInfonavit == 1){
                                $importe_infonavit = $valorCreditoInfonavitaAplicar;

                                //echo "importe infonavit ".$importe_infonavit."<br>";
                                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                                $stmt->bindValue(":relacion_tipo_deduccion_id", 9);
                                $stmt->bindValue(":relacion_concepto_deduccion_id", $rowInfo['relacion_concepto_deduccion_id']);
                                $stmt->bindValue(":tipo_concepto", 13);//13 para infonavit
                                $stmt->bindValue(":importe", $importe_infonavit);
                                $stmt->bindValue(":exento", 0.00);
                                $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                                $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                                $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                                $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                                $stmt->execute();

                                $idDetalleNominaDeduccion = $conn->lastInsertId();

                                $stmt = $conn->prepare('INSERT INTO credito_infonavit_registro (credito_infonavit_id, nomina_empleado_id, detalle_nomina_deduccion_empleado_id, importe_aplicado, usuario_alta, fecha_alta, estatus) VALUES (:credito_infonavit_id, :nomina_empleado_id, :detalle_nomina_deduccion_empleado_id, :importe_aplicado, :usuario_alta, :fecha_alta, :estatus)');
                                $stmt->bindValue(':credito_infonavit_id', $rowInfo['id']);
                                $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
                                $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNominaDeduccion);
                                $stmt->bindValue(':importe_aplicado', $importe_infonavit);
                                $stmt->bindValue(':usuario_alta', $_SESSION['PKUsuario']);
                                $stmt->bindValue(':fecha_alta', date("Y-m-d H:i:s"));
                                $stmt->bindValue(':estatus', 1);
                                $stmt->execute();


                                $stmt = $conn->prepare('SELECT n.fecha_inicio, n.fecha_fin FROM nomina_empleado as ne INNER JOIN nomina as n ON n.id = ne.FKNomina WHERE ne.PKNomina = :idNominaEmpleado');
                                $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado); 
                                $stmt->execute();

                                $rowDatosNominaEsp = $stmt->fetch();
                                $fechaInicioNomina = $rowDatosNominaEsp['fecha_inicio'];
                                $fechaFinNomina = $rowDatosNominaEsp['fecha_fin'];

                                $fechaInicioNominaTiempo = strtotime($fechaInicioNomina);
                                $fechaFinNominaTiempo = strtotime($fechaFinNomina);
                                $estadoInfonavit = 1;

                                if(($fechaSuspension != "" || $fechaSuspension != null) && $fechaSuspension != "0000-00-00"){
                                    //echo "MMMMMM ";
                                    $fechaSuspensionTiempo = strtotime($fechaSuspension);

                                    if($fechaInicioNominaTiempo > $fechaSuspensionTiempo){
                                        $estadoInfonavit = 3;
                                    }
                                    else{
                                        if($fechaFinNominaTiempo > $fechaSuspensionTiempo){
                                            $estadoInfonavit = 3;
                                        }
                                    }
                                }

                                $importeAcumuladoFinal = $importeAcumulado + $importe_infonavit;
                                $numVecesAplicadas = $rowInfo['veces_aplicadas'] + 1;

                                $stmt = $conn->prepare('UPDATE credito_infonavit SET importe_acumulado = :importe_acumulado, veces_aplicadas = :veces_aplicadas, estado = :estado WHERE id = :idCreditoInfonavit');
                                $stmt->bindValue(':importe_acumulado', $importeAcumuladoFinal);
                                $stmt->bindValue(':veces_aplicadas', $numVecesAplicadas);
                                $stmt->bindValue(':estado', $estadoInfonavit);
                                $stmt->bindValue(':idCreditoInfonavit', $idCreditoInfonavit);       
                                $stmt->execute();
                            }

                          }

                          ///////////// CREDITO FONACOT //////////////
                          $stmt = $conn->prepare('SELECT id, importe_periodo, relacion_concepto_deduccion_id, fecha_aplicacion, monto_acumulado_retenido, saldo, en_aplicacion FROM credito_fonacot WHERE empleado_id = :empleado_id AND empresa_id = :empresa_id AND estado = 1');
                          $stmt->bindValue(':empleado_id', $emp['PKEmpleado']);
                          $stmt->bindValue(':empresa_id', $idEmpresa);
                          $stmt->execute();
                          $existe_credito_fonacot = $stmt->rowCount();

                          if($existe_credito_fonacot > 0){
                            $rowFona = $stmt->fetch();

                            $fechaAplicacion = $rowFona['fecha_aplicacion'];
                            $en_aplicacion = $rowFona['en_aplicacion'];

                            $stmt = $conn->prepare('SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina AND empresa_id = :idEmpresa');
                            $stmt->bindValue(':idNomina', $idNomina);  
                            $stmt->bindValue(':idEmpresa', $idEmpresa);
                            $stmt->execute();

                            $rowDatosNominaEsp = $stmt->fetch();
                            $fechaInicioNomina = $rowDatosNominaEsp['fecha_inicio'];
                            $fechaFinNomina = $rowDatosNominaEsp['fecha_fin'];

                            $fechaAplicacionTiempo = strtotime($fechaAplicacion);
                            $fechaInicioNominaTiempo = strtotime($fechaInicioNomina);
                            $fechaFinNominaTiempo = strtotime($fechaFinNomina);
                            
                            //echo $fechaInicioNomina." -- ".$fechaFinNomina." -- ".$fechaAplicacion."<br>";
                            if(($fechaInicioNominaTiempo <= $fechaAplicacionTiempo && $fechaFinNominaTiempo >= $fechaAplicacionTiempo) || $en_aplicacion == 1){

                              $saldo = $rowFona['saldo'];
                              $importe_periodo = $rowFona['importe_periodo'];
                              $finalizar_credito = 0;
                              $saldoFinal = 0.00;

                              if($importe_periodo >= $saldo){
                                $importe_periodo = $saldo;
                                $finalizar_credito = 1;

                                $saldoFinal = 0.00;
                              }
                              else{
                                $saldoFinal = $saldo - $importe_periodo;
                              }

                              $montoAcumuladoRetenido = $rowFona['monto_acumulado_retenido'] + $importe_periodo;
                              
                              $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
                              $stmt->bindValue(":relacion_tipo_deduccion_id", 9);
                              $stmt->bindValue(":relacion_concepto_deduccion_id", $rowFona['relacion_concepto_deduccion_id']);
                              $stmt->bindValue(":tipo_concepto", 12);//12 para fonacot
                              $stmt->bindValue(":importe", $importe_periodo);
                              $stmt->bindValue(":exento", 0.00);
                              $stmt->bindValue(":empleado_id", $emp['PKEmpleado']);
                              $stmt->bindValue(":nomina_empleado_id", $idNominaEmpleado);
                              $stmt->bindValue(":fecha_alta", date("Y-m-d H:i:s"));
                              $stmt->bindValue(":fecha_edicion", date("Y-m-d H:i:s"));
                              $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                              $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                              $stmt->execute();

                              $idDetalleNominaDeduccion = $conn->lastInsertId();

                              $stmt = $conn->prepare('INSERT INTO credito_fonacot_registro (credito_fonacot_id, nomina_empleado_id, detalle_nomina_deduccion_empleado_id, importe_aplicado, usuario_alta, fecha_alta, estatus) VALUES (:credito_fonacot_id, :nomina_empleado_id, :detalle_nomina_deduccion_empleado_id, :importe_aplicado, :usuario_alta, :fecha_alta, :estatus)');
                              $stmt->bindValue(':credito_fonacot_id', $rowFona['id']);
                              $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
                              $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNominaDeduccion);
                              $stmt->bindValue(':importe_aplicado', $importe_periodo);
                              $stmt->bindValue(':usuario_alta', $_SESSION['PKUsuario']);
                              $stmt->bindValue(':fecha_alta', date("Y-m-d H:i:s"));
                              $stmt->bindValue(':estatus', 1);
                              $stmt->execute();

                              $estadoFonacot = 1;

                              if($finalizar_credito == 1){
                                $estadoFonacot = 3;
                                $en_aplicacion = 2;
                              }
                              else{
                                $en_aplicacion = 1;
                              }
                              //echo "aplic ".$en_aplicacion;
                              $stmt = $conn->prepare('UPDATE credito_fonacot SET monto_acumulado_retenido = :monto_acumulado_retenido, saldo = :saldo, estado = :estado, en_aplicacion = :en_aplicacion WHERE id = :idCreditoFonacot');
                              $stmt->bindValue(':monto_acumulado_retenido', $montoAcumuladoRetenido);
                              $stmt->bindValue(':saldo', $saldoFinal);
                              $stmt->bindValue(':estado', $estadoFonacot);
                              $stmt->bindValue(':en_aplicacion', $en_aplicacion);
                              $stmt->bindValue(':idCreditoFonacot', $rowFona['id']);
                              $stmt->execute();
                            }

                          }


                          ////////  PENSION ALIMENTICIA  ////////////
                          $stmt = $conn->prepare('SELECT id, tipo_importe, tasa_pension, relacion_concepto_deduccion_id, fecha_aplicacion, fecha_suspension FROM pension_alimenticia WHERE empleado_id = :empleado_id AND empresa_id = :empresa_id AND estado = 1');
                          $stmt->bindValue(':empleado_id', $emp['PKEmpleado']);
                          $stmt->bindValue(':empresa_id', $idEmpresa);
                          $stmt->execute();
                          $existe_pension_alimenticia = $stmt->rowCount();

                          if($existe_pension_alimenticia > 0){
                            $rowPension = $stmt->fetch();

                            $idPension = $rowPension['id'];
                            $pensionAlimenticiaTipo = $rowPension['tipo_importe'];
                            $PorcentajeAplicar = $rowPension['tasa_pension'];
                            $fechaAplicacion = $rowPension['fecha_aplicacion'];
                            $fechaSuspension = $rowPension['fecha_suspension'];
                            $idConceptoDeduccion = $rowPension['relacion_concepto_deduccion_id'];
                            $tipo_concepto = 14;
                            $fecha = date("Y-m-d");
                            $idUsuario = $_SESSION['PKUsuario'];

                            $stmt = $conn->prepare('SELECT n.fecha_inicio, n.fecha_fin FROM nomina_empleado as ne INNER JOIN nomina as n ON n.id = ne.FKNomina WHERE ne.PKNomina = :idNominaEmpleado');
                            $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado); 
                            $stmt->execute();

                            $rowDatosNominaPen = $stmt->fetch();
                            $fechaInicioNomina = $rowDatosNominaPen['fecha_inicio'];
                            $fechaFinNomina = $rowDatosNominaPen['fecha_fin'];

                            $fechaInicioNominaTiempo = strtotime($fechaInicioNomina);
                            $fechaFinNominaTiempo = strtotime($fechaFinNomina);
                            $fechaAplicacionTiempo = strtotime($fechaAplicacion);
                            $aplicarPension = 0;

                            if(($fechaSuspension != "" || $fechaSuspension != null) && $fechaSuspension != "0000-00-00"){
                                $fechaSuspensionTiempo = strtotime($fechaSuspension);

                                if($fechaInicioNominaTiempo > $fechaSuspensionTiempo){
                                    $aplicarPension = 1;
                                }
                                else{
                                    if($fechaFinNominaTiempo > $fechaSuspensionTiempo){
                                        $aplicarPension = 0;
                                    }
                                }
                            }
                            else{
                                if($fechaInicioNominaTiempo > $fechaAplicacionTiempo){
                                    $aplicarPension = 1;
                                }
                            }


                          }
                          else{
                            $aplicarPension = 0;
                          }
                          ////////  FIN PENSION ALIMENTICIA  ////////////

                          $modo = 1;//para agregar o restar cantidades adicionales al total de percepciones 
                          require("calculoImpuestos.php");


                      }
                      
                      $conn->commit();


                  }catch(PDOException $ex){
                    //echo $ex->getMessage();
                    $conn->rollBack();
                  }

                  
                  

                  


            }
        }

    }
?>
