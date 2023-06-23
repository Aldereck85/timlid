<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

/*
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}
*/
require_once '../../../include/db-conn.php';

$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" OR descripcion = "Salario_Minimo_Norte" ORDER BY PKParametros Asc');
$stmt->execute();
$row_parametros = $stmt->fetchAll();
$UMA = $row_parametros[0]['cantidad'];
$factor_mes = $row_parametros[3]['cantidad'];
$salario_minimo_nacional = $row_parametros[1]['cantidad'];
$salario_minimo_norte = $row_parametros[2]['cantidad'];

$salarioNeto = 6617.37;
$diasTrabajados = 14;

//salrio bruto   $salarioNeto + ISR

//5760.57  + 856.80  = 6617.37

$salarioBrutoPeriodo = 6617.37;

$salarioBruto = ($salarioBrutoPeriodo / $diasTrabajados) * 30.4;  echo $salarioBruto;

$stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
$stmt->bindValue(':impuestogravablemin',$salarioBruto);
$stmt->bindValue(':impuestogravablesup',$salarioBruto);
$stmt->execute();
$row_limite = $stmt->fetch();

print_r($row_limite);

$Limite_inferior = $row_limite['Limite_inferior'];
$excedente_limite_inferior = number_format($salarioBruto - $Limite_inferior, 2, '.', '');
$porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
$impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
$cuota_fija = $row_limite['Cuota_fija'];
$ISRDeterminado = $impuesto_marginal + $cuota_fija;

$stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo ");
$stmt->bindValue(':ingresominimo',$salarioBruto);
$stmt->bindValue(':ingresomaximo',$salarioBruto);
$stmt->execute();
$row_subsidio = $stmt->fetch();
$subsidioMensual = $row_subsidio['SubsidioMensual'];
$subsidioAplicable = number_format(($subsidioMensual / $factor_mes) * $diasTrabajados, 2, '.', '');
//echo "sinsidio ".$subsidioMensual;

$ISRRetenido = number_format($ISRDeterminado, 2, '.', '');
$ISRDiario = number_format((($ISRRetenido / $factor_mes) * $diasTrabajados) , 2, '.', '');


$SAEPagar = 0;
if($ISRDiario >= $subsidioAplicable){
  $ISRDiario = bcdiv($ISRDiario - $subsidioAplicable,1,2);
}
else{
  $SAEPagar = bcdiv(($ISRDiario - $subsidioAplicable) * -1,1,2);
}


echo "ISR: ".$ISRDiario;

$salarioNeto = $ISRDiario + $salarioBrutoPeriodo;
echo " salarioNeto ".$salarioNeto;

echo "///////////////////////////////////////primera ronda ";

?>
