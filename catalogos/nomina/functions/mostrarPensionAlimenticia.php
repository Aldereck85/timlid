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

$stmt = $conn->prepare("SELECT pa.*, rcd.concepto_nomina, rtd.clave FROM pension_alimenticia as pa LEFT JOIN relacion_concepto_deduccion as rcd ON rcd.id = pa.relacion_concepto_deduccion_id LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = rcd.tipo_deduccion_id WHERE pa.empleado_id = :empleado_id AND pa.empresa_id = :empresa_id AND estado = 1");
$stmt->bindValue(":empleado_id", $idEmpleado);
$stmt->bindValue(":empresa_id", $idEmpresa);
$stmt->execute();
$existePension = $stmt->rowCount();



if($existePension < 1){

        $stmt = $conn->prepare("SELECT id, clave FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 7 AND empresa_id = ".$idEmpresa);
        $stmt->execute();
        $existeClave = $stmt->rowCount();

        if($existeClave < 1){
            $json->clave = '  <label for="usr">Clave:</label>
                                  <input type="text" class="form-control" name="txtClavePensionUnica" id="txtClavePensionUnica" value="" maxlength="15" required onkeypress="return isNumber(event)">
                               </div>';
            $json->claveValor = 1;
        }
        else{
            $rowClave = $stmt->fetch();
            $json->clave = "<label for='usr'>Clave:</label>
                                  <input type='text' class='form-control' name='txtClavePensionUnica' id='txtClavePensionUnica' value='".$rowClave['clave']."' readonly>
                               </div>";
            $json->claveValor = 0;
        }


        $stmt = $conn->prepare("SELECT id, concepto_nomina FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 7 AND empresa_id = ".$idEmpresa);
        $stmt->execute();
        $existeConcepto = $stmt->rowCount();

        if($existeConcepto < 1){
            $json->concepto = '<label>Concepto:</label>
                               <input type="text" value="" name="txtClaveSATPension" id="txtClaveSATPension" class="form-control" maxlength="100">';
            $json->conceptoValor = 1;    
        }
        else{
            $rowConcepto = $stmt->fetchAll();
            $option = '<label>Concepto:</label><select name="cmbConceptoPension" id="cmbConceptoPension" class="form-control">';

            foreach($rowConcepto as $c){
                $option .= '<option value="'.$c['id'].'" >'.$c['concepto_nomina'].'</option>'; 
            }
            $option .= "</select>";
            $json->concepto = $option;
            $json->conceptoValor = 0;
            
        }

        $json->botonPension = '<center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarPension">Agregar</button>
              </center>';
        $json->existePension = 0;
}
else{
    $row = $stmt->fetch();
    $json->tipoPension = $row['tipo_importe'];
    $json->tasa_pension =$row['tasa_pension'];  
    $json->fecha_apli = $row['fecha_aplicacion'];
    $json->clave = "<label for='usr'>Clave:</label>
                                  <input type='text' class='form-control' name='txtClavePensionUnicaEdit' id='txtClavePensionUnicaEdit' value='".$row['clave']."' readonly>
                               </div>";
    $json->concepto = '<label>Concepto:</label>
                               <input type="text" value="'.$row['concepto_nomina'].'" name="txtClaveSATPensionEdit" id="txtClaveSATPensionEdit" class="form-control" readonly>';

    $json->botonPension = '<input type="hidden" id="idPensionAlimenticia" value="'.$row['id'].'" /><center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="eliminarPension">Cancelar</button>
                <button type="button" class="btn btn-custom btn-custom--blue" id="completarPension">Finalizado</button>
                <button type="button" class="btn btn-custom btn-custom--blue" id="modificarPension">Modificar</button>
              </center>';
    $json->existePension = 1;
}

$json = json_encode($json);
echo $json;

?>