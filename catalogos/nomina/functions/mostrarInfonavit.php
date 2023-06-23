<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$json = new \stdClass();

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->respuesta = "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->respuesta = "fallo";
    return;
}

require_once('../../../include/db-conn.php');

$idEmpresa = $_SESSION['IDEmpresa'];
$idEmpleado = $_POST['idEmpleado'];

$stmt = $conn->prepare("SELECT ci.*, rcd.concepto_nomina, rtd.clave FROM credito_infonavit as ci LEFT JOIN relacion_concepto_deduccion as rcd ON rcd.id = ci.relacion_concepto_deduccion_id LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = rcd.tipo_deduccion_id WHERE ci.empleado_id = :empleado_id AND ci.empresa_id = :empresa_id AND estado = 1");
$stmt->bindValue(":empleado_id", $idEmpleado);
$stmt->bindValue(":empresa_id", $idEmpresa);
$stmt->execute();
$existeCredito = $stmt->rowCount();



if($existeCredito < 1){

        $stmt = $conn->prepare("SELECT id, clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 9 AND empresa_id = ".$idEmpresa);
        $stmt->execute();
        $existeClave = $stmt->rowCount();

        if($existeClave < 1){
            $json->clave = '  <label for="usr">Clave:</label>
                                  <input type="text" class="form-control" name="txtClaveInfonavitUnica" id="txtClaveInfonavitUnica" value="" maxlength="15" required onkeypress="return isNumber(event)">
                               </div>';
            $json->claveValor = 1;
        }
        else{
            $rowClave = $stmt->fetch();
            $json->clave = "<label for='usr'>Clave:</label>
                                  <input type='text' class='form-control' name='txtClaveInfonavitUnica' id='txtClaveInfonavitUnica' value='".$rowClave['clave']."' readonly>
                               </div>";
            $json->claveValor = 0;
        }


        $stmt = $conn->prepare("SELECT id, concepto_nomina FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 9 AND empresa_id = ".$idEmpresa);
        $stmt->execute();
        $existeConcepto = $stmt->rowCount();

        if($existeConcepto < 1){
            $json->concepto = '<label>Concepto:</label>
                               <input type="text" value="" name="txtClaveSATInfonavit" id="txtClaveSATInfonavit" class="form-control" maxlength="100">';
            $json->conceptoValor = 1;    
        }
        else{
            $rowConcepto = $stmt->fetchAll();
            $option = '<label>Concepto:</label><select name="cmbConceptoInfonavit" id="cmbConceptoInfonavit" class="form-control">';

            foreach($rowConcepto as $c){
                $option .= '<option value="'.$c['id'].'" >'.$c['concepto_nomina'].'</option>'; 
            }
            $option .= "</select>";
            $json->concepto = $option;
            $json->conceptoValor = 0;
            
        }

        $json->botonInfonavit = '<center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarInfonavit">Agregar</button>
              </center>';
        $json->existeCredito = 0;
}
else{
    $row = $stmt->fetch();
    $json->num_credito = $row['numero_credito'];
    $json->fecha_apli = $row['fecha_aplicacion'];
    $json->fecha_sus = $row['fecha_suspension'];
    $json->fecha_reg = $row['fecha_alta'];
    $json->tipo_importe = $row['tipo_importe'];  
    $json->importe_fijo = number_format($row['importe_fijo'],3, '.', ',');  
    $json->importe_acum = number_format($row['importe_acumulado'],3, '.', ',');  
    $json->veces_apli = $row['veces_aplicadas'];  
    $json->clave = "<label for='usr'>Clave:</label>
                                  <input type='text' class='form-control' name='txtClaveInfonavitUnicaEdit' id='txtClaveInfonavitUnicaEdit' value='".$row['clave']."' readonly>
                               </div>";
    $json->concepto = '<label>Concepto:</label>
                               <input type="text" value="'.$row['concepto_nomina'].'" name="txtClaveSATInfonavitEdit" id="txtClaveSATInfonavitEdit" class="form-control" readonly>';

    $json->botonInfonavit = '<input type="hidden" id="idCreditoInfonavit" value="'.$row['id'].'" /><center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="modificarInfonavit">Modificar</button>
                <button type="button" class="btn btn-custom btn-custom--blue" id="eliminarInfonavit">Cancelar</button>
              </center>';
    $json->existeCredito = 1;
}

$json = json_encode($json);
echo $json;

?>