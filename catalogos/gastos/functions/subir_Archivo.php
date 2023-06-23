<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
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
      switch($partesruta['extension']){
        case "jpg":
        case "jpeg":
        case "png":
          $location = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/img'.'/';
        break;
        case "pdf":
        case "xlsx":
        case "xml":
          $location = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/archivos'.'/';
        break;
        default:
          $location = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/archivos'.'/';
        break;
      }
       
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
          if(move_uploaded_file($_FILES['file-input']['tmp_name'],$location . $nombrefinal)){
            try{
              //UPDATE del movimiento ACTUAL
              $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Referencia =:referencia, Comprobado = 1 WHERE PKMovimiento =:idMov');
              $stmt->bindValue(':idMov', $idMov, PDO::PARAM_INT);
              $stmt->bindValue(':referencia', $nombrefinal);
              if($stmt->execute()==true){
                echo "exito";
              }else{
                echo "fallo";
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