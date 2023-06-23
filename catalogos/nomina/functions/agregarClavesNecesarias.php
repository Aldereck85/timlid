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

$claveSalario = $_POST['claveSalario'];
$existeclaveSalario = $_POST['existeclaveSalario'];
$claveIMSS = $_POST['claveIMSS'];
$existeclaveIMSS = $_POST['existeclaveIMSS'];
$claveISR = $_POST['claveISR'];
$existeclaveISR = $_POST['existeclaveISR'];

$claveHorasExtra = $_POST['claveHorasExtra'];
$existeclaveHorasExtra = $_POST['existeclaveHorasExtra'];
$clavePrimaDominical = $_POST['clavePrimaDominical'];
$existeclavePrimaDominical = $_POST['existeclavePrimaDominical'];
$clavePrimaVacacional = $_POST['clavePrimaVacacional'];
$existeclavePrimaVacacional = $_POST['existeclavePrimaVacacional'];

$claveOtrosIngresos = $_POST['claveOtrosIngresos'];
$existeclaveIngresos = $_POST['existeclaveIngresos'];
$claveDescuentoIncapacidad = $_POST['claveDescuentoIncapacidad'];
$existeclaveDescuentoIncapacidad = $_POST['existeclaveDescuentoIncapacidad'];
$claveAusencia = $_POST['claveAusencia'];
$existeclaveAusencia = $_POST['existeclaveAusencia'];

$clave_071 = $_POST['clave_071'];
$existeClave071 = $_POST['existeClave071'];
$clave_107 = $_POST['clave_107'];
$existeClave107 = $_POST['existeClave107'];

$idEmpresa = $_SESSION['IDEmpresa'];

try {

    $conn->beginTransaction();
    
    if($existeclaveSalario == 1){
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $claveSalario);
        $stmt->execute();
        $existe1 = $stmt->rowCount();

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $claveSalario);
        $stmt->execute();
        $existe2 = $stmt->rowCount();

        if($existe1 > 0 || $existe2 > 0){ 
            echo "existe-clave-salario";
            $conn->rollBack(); 
            return;
        }
        else{

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', 1);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                echo "existe-concepto-salario";
                $conn->rollBack(); 
                return;
            }
            else{
                //se agrega la clave del salario que es ID 1 con su empresa
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $claveSalario);
                $stmt->bindValue(':tipo_percepcion_id', 1);
                $stmt->bindValue(':empresa_id', $idEmpresa);
                $stmt->execute();
            }
        }
    }


    if($existeclaveIMSS == 1){

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveIMSS);
            $stmt->execute();
            $existe3 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveIMSS);
            $stmt->execute();
            $existe4 = $stmt->rowCount();
            
            if($existe3 > 0 || $existe4 > 0){ 
                echo "existe-clave-imss";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', 1);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-imss";
                    $conn->rollBack(); 
                    return;
                }
                else{

                        $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                        $stmt->bindValue(':clave', $claveIMSS);
                        $stmt->bindValue(':tipo_deduccion_id', 1);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->execute();

                }
            }

    }

    if($existeclaveISR == 1){

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveISR);
            $stmt->execute();
            $existe5 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveISR);
            $stmt->execute();
            $existe6 = $stmt->rowCount();
            
            if($existe5 > 0 || $existe6 > 0){ 
                echo "existe-clave-isr";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', 2);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-isr";
                    $conn->rollBack(); 
                    return;
                }
                else{

                        $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                        $stmt->bindValue(':clave', $claveISR);
                        $stmt->bindValue(':tipo_deduccion_id', 2);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->execute();

                }
            }

    }

    //horas extras
    if($existeclaveHorasExtra == 1){
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $claveHorasExtra);
        $stmt->execute();
        $existe1 = $stmt->rowCount();

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $claveHorasExtra);
        $stmt->execute();
        $existe2 = $stmt->rowCount();

        if($existe1 > 0 || $existe2 > 0){ 
            echo "existe-clave-HorasExtra";
            $conn->rollBack(); 
            return;
        }
        else{

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', 14);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                echo "existe-concepto-HorasExtra";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $claveHorasExtra);
                $stmt->bindValue(':tipo_percepcion_id', 14);
                $stmt->bindValue(':empresa_id', $idEmpresa);
                $stmt->execute();
            }
        }
    }


    //Prima dominical
    if($existeclavePrimaDominical == 1){
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clavePrimaDominical);
        $stmt->execute();
        $existe1 = $stmt->rowCount();

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clavePrimaDominical);
        $stmt->execute();
        $existe2 = $stmt->rowCount();

        if($existe1 > 0 || $existe2 > 0){ 
            echo "existe-clave-PrimaDominical";
            $conn->rollBack(); 
            return;
        }
        else{

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', 15);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                echo "existe-concepto-PrimaDominical";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clavePrimaDominical);
                $stmt->bindValue(':tipo_percepcion_id', 15);
                $stmt->bindValue(':empresa_id', $idEmpresa);
                $stmt->execute();
            }
        }
    }

    //Prima vacacional
    if($existeclavePrimaVacacional == 1){
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clavePrimaVacacional);
        $stmt->execute();
        $existe1 = $stmt->rowCount();

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clavePrimaVacacional);
        $stmt->execute();
        $existe2 = $stmt->rowCount();

        if($existe1 > 0 || $existe2 > 0){ 
            echo "existe-clave-PrimaVacacional";
            $conn->rollBack(); 
            return;
        }
        else{

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', 16);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                echo "existe-concepto-PrimaVacacional";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clavePrimaVacacional);
                $stmt->bindValue(':tipo_percepcion_id', 16);
                $stmt->bindValue(':empresa_id', $idEmpresa);
                $stmt->execute();
            }
        }
    }

    //Otros ingresos por salario
    if($existeclaveIngresos == 1){
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $claveOtrosIngresos);
        $stmt->execute();
        $existe1 = $stmt->rowCount();

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $claveOtrosIngresos);
        $stmt->execute();
        $existe2 = $stmt->rowCount();

        if($existe1 > 0 || $existe2 > 0){ 
            echo "existe-clave-OtrosIngresos";
            $conn->rollBack(); 
            return;
        }
        else{

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', 33);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                echo "existe-concepto-OtrosIngresos";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $claveOtrosIngresos);
                $stmt->bindValue(':tipo_percepcion_id', 33);
                $stmt->bindValue(':empresa_id', $idEmpresa);
                $stmt->execute();
            }
        }
    }

    //Descuento incapacidad
    if($existeclaveDescuentoIncapacidad == 1){

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveDescuentoIncapacidad);
            $stmt->execute();
            $existe3 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveDescuentoIncapacidad);
            $stmt->execute();
            $existe4 = $stmt->rowCount();
            
            if($existe3 > 0 || $existe4 > 0){ 
                echo "existe-clave-DescuentoIncapacidad";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', 6);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-DescuentoIncapacidad";
                    $conn->rollBack(); 
                    return;
                }
                else{

                        $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                        $stmt->bindValue(':clave', $claveDescuentoIncapacidad);
                        $stmt->bindValue(':tipo_deduccion_id', 6);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->execute();

                }
            }

    }

    //Ausencia
    if($existeclaveAusencia == 1){

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveAusencia);
            $stmt->execute();
            $existe3 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $claveAusencia);
            $stmt->execute();
            $existe4 = $stmt->rowCount();
            
            if($existe3 > 0 || $existe4 > 0){ 
                echo "existe-clave-Ausencia";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', 20);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-Ausencia";
                    $conn->rollBack(); 
                    return;
                }
                else{

                        $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                        $stmt->bindValue(':clave', $claveAusencia);
                        $stmt->bindValue(':tipo_deduccion_id', 20);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->execute();

                }
            }

    }

    //071 Ajuste en subsidio al empleo
    if($existeClave071 == 1){

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $clave_071);
            $stmt->execute();
            $existe3 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $clave_071);
            $stmt->execute();
            $existe4 = $stmt->rowCount();
            
            if($existe3 > 0 || $existe4 > 0){ 
                echo "existe-clave-071";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', 71);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-071";
                    $conn->rollBack(); 
                    return;
                }
                else{

                        $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                        $stmt->bindValue(':clave', $clave_071);
                        $stmt->bindValue(':tipo_deduccion_id', 71);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->execute();

                }
            }

    }

    //107 Ajuste al subsidio causado
    if($existeClave107 == 1){

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $clave_107);
            $stmt->execute();
            $existe3 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $clave_107);
            $stmt->execute();
            $existe4 = $stmt->rowCount();
            
            if($existe3 > 0 || $existe4 > 0){ 
                echo "existe-clave-107";
                $conn->rollBack(); 
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', 107);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-107";
                    $conn->rollBack(); 
                    return;
                }
                else{

                        $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                        $stmt->bindValue(':clave', $clave_107);
                        $stmt->bindValue(':tipo_deduccion_id', 107);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->execute();

                }
            }

    }


    if($conn->commit()){
        echo "exito";
    }   
    else{
        echo "fallo";
    }
    
    
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; //$ex->getMessage();
}

?>
