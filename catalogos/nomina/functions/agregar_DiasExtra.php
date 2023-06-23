<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

require_once '../../../include/db-conn.php';

date_default_timezone_set('America/Mexico_City');

$diasExtra = $_POST['diasExtra'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$domingo = $_POST['domingo'];
$tipoAgregar = $_POST['tipoAgregar']; //1 agregar dia extra(percepcion), 2 quitar dia extra(deducción)
$tipo_concepto = 4;// tipo 4 es para dias extras
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];

$idEmpresa = $_SESSION['IDEmpresa'];

if($diasExtra > 1){
    $concepto = $diasExtra." días extra";
}
else{
    $concepto = $diasExtra." día extra";
}

$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$salarioDiario = bcdiv($datosEmpleado['Sueldo']/$datosEmpleado['DiasPago'],1,2);

try {
    $stmt = $conn->prepare('INSERT INTO detalle_nomina_empleado (concepto, tipo, tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :tipo, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    $stmt->bindValue(':concepto', $concepto);
    $stmt->bindValue(':tipo', $tipoAgregar);
    $stmt->bindValue(':tipo_concepto', $tipo_concepto);
    $stmt->bindValue(':importe', $importe);
    $stmt->bindValue(':exento', 0);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->bindValue(':fecha_alta', $fecha);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':usuario_edicion', $idUsuario);

    if($stmt->execute()){

      $stmt = $conn->prepare('SELECT tipo, tipo_concepto, importe, exento FROM detalle_nomina_empleado WHERE empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id');
      $stmt->bindValue(':empleado_id', $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
      $stmt->execute();
      $conceptos = $stmt->fetchAll();

      $totalBase = 0;
      $totalExento = 0;
      $base_imss_general = 0;

//1 Salario normal\\n2 Percepciones/Deducciones\\n3 Horas extras \n4 Agregar Dias extras 5 Restar dias extra 6 Prima dominical

      foreach ($conceptos as $c) {

          //calculo base salario
          if($c['tipo_concepto'] == 1){
            $base_imss_general = $base_imss_general + $c['importe'];
          }
          if($c['tipo_concepto'] == 2){
            if($c['exento'] == 1){
                //percepcion
                if($c['tipo'] == 1){
                    $base_imss_general = $base_imss_general + $c['importe'];
                }
                  //deduccion
                if($c['tipo'] == 2){
                    $base_imss_general = $base_imss_general - $c['importe'];
                }
              }

            $base_imss_general = $base_imss_general + $c['importe'];
          }


          //FIN calculo base salario
          
          if($c['exento'] == 1){
            //percepcion
            if($c['tipo'] == 1){
                $totalExento = $totalExento + $c['importe'];
            }
              //deduccion
            if($c['tipo'] == 2){
                $totalExento = $totalExento - $c['importe'];
            }
          }
          else{
              //percepcion
              if($c['tipo'] == 1){
                $totalBase = $totalBase + $c['importe'];
              }
              //deduccion
              if($c['tipo'] == 2){
                $totalBase = $totalBase - $c['importe'];
              }
          }

      }

      $stmt = $conn->prepare("SELECT FKSucursal FROM datos_laborales_empleado WHERE FKEmpleado = :idEmpleado");
      $stmt->execute(array(':idEmpleado'=>$idEmpleado));
      $row_s = $stmt->fetch();
      $idSucursal = $row_s['FKSucursal'];

      $id = $idEmpleado;
      $diasTrabajadosImpuestos = 7;
      $sueldoTotal = $totalBase;
      $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
      require_once("calculoImpuestos.php");

      $stmt = $conn->prepare('SELECT SUM(TotalNeto) as total, COUNT(PKNomina) as num_empleados FROM nomina_empleado WHERE FKNomina = '. $idNomina);
      $stmt->execute();
      $row_cont = $stmt->fetch();
      $num_empleados = $row_cont['num_empleados'];
      $total = $row_cont['total'];

      $stmt = $conn->prepare('UPDATE nomina SET no_empleados = :no_empleados, total = :total WHERE id = :idNomina ');
      $stmt->bindValue(':total', $total);
      $stmt->bindValue(':no_empleados', $num_empleados);
      $stmt->bindValue(':idNomina', $idNomina);
      $stmt->execute();

      echo "exito";
    }else{
      echo "fallo";
    }
    
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>
