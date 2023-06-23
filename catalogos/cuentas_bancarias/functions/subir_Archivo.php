<?php
session_start();
  if(isset($_SESSION["Usuario"]) ){
    require_once('../../../include/db-conn.php');
  
    $hayArchivo = $_POST['hayArchivo'];
    $idMov = $_POST['idMovimiento'];
    $id = $_POST['idCuenta'];

    if($hayArchivo==1){
      $filename = $_FILES['file-input']['name'];
      $tmp = $_FILES['file-input']['tmp_name'];
      $partesruta = pathinfo($filename);
      $identificador = round(microtime(true));
      $json = new \stdClass();
      $json->res = -1;
    
      $nombrearchivo = str_replace(" ", "_", $partesruta['filename']);
      $nombrefinal = $id.'_REF_'.$identificador.'.'.$partesruta['extension'];
      /* Location */
      $location = "Documentos/$nombrefinal"; 
      $uploadOk = 1; 
      //echo " -- "."".$nombrefinal ."\n location. " .$location."\n TMP: ".  $tmp;
      if($partesruta['extension'] == "jpg" || $partesruta['extension'] == "jpeg" || $partesruta['extension'] == "png" || $partesruta['extension'] == "pdf" || $partesruta['extension'] == "xlsx" || $partesruta['extension'] == "xml")
      {
        if($_FILES['file-input']['size'] > 4000000){
          $json->res = 1;
        }      
      }
      else{
        if($_FILES['file-input']['size'] > 20000000){
          $json->res = 2;
        }  
      }
      if($json->res != 1 && $json->res != 2){
        if($uploadOk == 0){ 
          $json->res = 0; 
        }else{ 
          /* Upload file */
          if(move_uploaded_file($_FILES['file-input']['tmp_name'],$location)){ 
            try{
              //buscar movimientos vinculados
              //select datos del movimiento actual 
              $stmtM = $conn->prepare('SELECT * FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
              $stmtM->bindValue(':id',$idMov,PDO::PARAM_INT);
              $stmtM->execute();
              $row = $stmtM->fetch();
              $idCuentaMovActual = $row['cuenta_origen_id'];
              $archivo = $row['Referencia'];
              $idDestino = $row['cuenta_destino_id'];
              $tipo = $row['tipo_movimiento_id'];

              if($tipo == 6 || $tipo == 2 || $tipo == 1){ // si es ajuste o inyeccion de dinero solo actualiza un registro(actual).
                //UPDATE del movimiento ACTUAL
                $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Referencia =:referencia, Comprobado =:comprobado WHERE PKMovimiento =:idMov');
                $stmt->bindValue(':idMov', $idMov, PDO::PARAM_INT);
                $stmt->bindValue(':referencia', $nombrefinal);
                $stmt->bindValue(':comprobado', 1);
                if($stmt->execute()==true){
                  $exito = true;
                }else{
                  $exito = false;
                }
                // END
                if($exito === true){
                  echo "exito";
                }else{
                  echo "fallo";
                }
              }else{
                if($tipo == 7){ // si el movimiento esta vinculado a un movimiento anterior
                  //busca el mov ANTERIOR
                  $stmtR = $conn->prepare('SELECT MAX(PKMovimiento) as anterior from movimientos_cuentas_bancarias_empresa where PKMovimiento <:id' );
                  $stmtR->bindValue(':id',$idMov,PDO::PARAM_INT);
                  $stmtR->execute();
                  $row = $stmtR->fetch();
                  $movimientoAnterior = $row['anterior'];
  
                  //selct datos del mov anterior
                  $stmtM = $conn->prepare('SELECT 
                    Referencia as referencia,
                    cuenta_destino_id as cuentaDest,
                    cuenta_origen_id as cuentaOrigen,
                    tipo_movimiento_id as tipo,
                    Fecha as fecha  
                  FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
                  $stmtM->bindValue(':id',$movimientoAnterior,PDO::PARAM_INT);
                  $stmtM->execute();
                  $row = $stmtM->fetch();
  
                  $referenciaA = $row['referencia'];
                  $cuentaDestAnterior = $row['cuentaDest'];
                  $tipoAnterior = $row['tipo'];
                  if($idCuentaMovActual == $cuentaDestAnterior && $tipoAnterior == 3){
                    //si coincide, actualiza la referencia del mov anterior
                    //UPDATE movimiento ACTUAL
                    $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Referencia =:referencia, Comprobado =:comprobado WHERE PKMovimiento =:idMov');
                    $stmt->bindValue(':idMov', $idMov, PDO::PARAM_INT);
                    $stmt->bindValue(':referencia', $nombrefinal);
                    $stmt->bindValue(':comprobado', 1);
                    if($stmt->execute()==true){
                      $movActual = true;
                    }else{
                      $movActual = false;
                    }
                    $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Referencia =:referencia, Comprobado =:comprobado WHERE PKMovimiento =:idMov');
                    $stmt->bindValue(':idMov', $movimientoAnterior, PDO::PARAM_INT);
                    $stmt->bindValue(':referencia', $nombrefinal);
                    $stmt->bindValue(':comprobado', 1);
                    if($stmt->execute()==true){
                      $movAnterior = true;
                    }else{
                      $movAnterior = false;
                    }
                    if($movAnterior === true && $movAnterior ===true){
                      echo "exito";
                    }else{
                      echo "fallo";
                    }
                  }  
  
                }else{ //si el movimiento esta vinculado al siguiente
                  $stmtR = $conn->prepare('SELECT MIN(PKMovimiento) as siguiente from movimientos_cuentas_bancarias_empresa where PKMovimiento > :id' );
                  $stmtR->bindValue(':id',$idMov,PDO::PARAM_INT);
                  $stmtR->execute();
                  $row = $stmtR->fetch();
                  $movimientoSiguiente = $row['siguiente'];
                 
                  //selct de los datos del siguiente mov
                  $stmtM = $conn->prepare('SELECT
                      cuenta_origen_id as idCuentaSig, 
                      Referencia as referencia,
                      cuenta_destino_id as cuentaDest,
                      cuenta_origen_id as cuentaOrigen,
                      tipo_movimiento_id as tipo,
                      Fecha as fecha  
                  FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
                  $stmtM->bindValue(':id',$movimientoSiguiente,PDO::PARAM_INT);
                  $stmtM->execute();
                  $row = $stmtM->fetch();
                  $cuentaSiguiente = $row['idCuentaSig'];
                  $referenciaS = $row['referencia'];
                  $tipoSiguiente = $row['tipo'];

                
                  if($idDestino == $cuentaSiguiente && $tipoSiguiente == 7){
                    //si coincide, actualiza la referencia del mov anterior
                    $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Referencia =:referencia, Comprobado =:comprobado WHERE PKMovimiento =:idMov');
                    $stmt->bindValue(':idMov', $movimientoSiguiente, PDO::PARAM_INT);
                    $stmt->bindValue(':referencia', $nombrefinal);
                    $stmt->bindValue(':comprobado', 1);
                    if($stmt->execute()==true){
                      $movSiguiente = true;
                    }else{
                      $movSiguiente = false;
                    }
                    //UPDATE MOV ACTUAL
                    $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Referencia =:referencia, Comprobado =:comprobado WHERE PKMovimiento =:idMov');
                    $stmt->bindValue(':idMov', $idMov, PDO::PARAM_INT);
                    $stmt->bindValue(':referencia', $nombrefinal);
                    $stmt->bindValue(':comprobado', 1);
                    if($stmt->execute()==true){
                      $movActual = true;
                    }else{
                      $movActual = false;
                    }
                    // END
                    if($movSiguiente === true && $movActual === true){
                      echo "exito";
                    }else{
                      echo "fallo";
                    }
                  }
                }
                
              } 
                           
            }catch(PDOException $ex){
              echo $ex->getMessage();
            }
          } 
        } 
      }
      
    }else{
      
    }
         
  }else {
    header("location:../../../../dashboard.php");
  }
  
  
?>