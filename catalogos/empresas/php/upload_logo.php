<?php
  session_start();
  $ruta_api = "../../../";
  require_once $ruta_api . "include/db-conn.php";
  require_once $ruta_api . "include/functions_api_facturation.php";
  require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
  $ban = "";
  $ban1 = "";
  $logo = "";

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    $api = new API();

    $query = sprintf("select e.PKEmpresa id, e.id_api, e.key_user_company_api,e.logo from empresas e where e.PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

    $key_user = $_ENV['KEY_API'] ?? "sk_user_2PjVqdXnKBaMWbz4djNVgOm0AgL9N7Qr";

    $target_dir = $_ENV['RUTA_ARCHIVOS_WRITE'] . $arr[0]->id . "/fiscales/";

    $target_dir_read =  $_ENV['RUTA_ARCHIVOS_READ'] . $arr[0]->id . "/fiscales/";

    $carpeta = $target_dir;
    $carpeta_read = $target_dir_read;

    if(isset($_FILES['file']['type'])){
      $logo = basename($_FILES['file']['name']);
      $logo_temp = $_FILES['file']['tmp_name'];
    } else {
      $logo = "";
    }

    $targe_fileLogo = $carpeta . $logo;
    $targe_fileLogo_read = $carpeta_read . $logo;
    $logoFileType = pathinfo($targe_fileLogo,PATHINFO_EXTENSION);

    if(!file_exists($carpeta)){
        mkdir($carpeta, 0777, true);
      }

    if($logo !== ""){
      if($logoFileType === "jpg" || $logoFileType === "jpeg" || $logoFileType === "png" || $logoFileType === "gif"){
        $width_max = 400;
        $height_max = 150;
        $img_original = null;
        $image_copy = null;

        switch ($logoFileType) 
        {
            case 'jpg':
                $img_original = imagecreatefromjpeg($logo_temp);
            break;
            case 'jpeg':
                $img_original = imagecreatefromjpeg($logo_temp);
            break;
            case 'png':
                $img_original = imagecreatefrompng($logo_temp);
            break;
            case 'gif':
                $img_original = imagecreatefromgif($logo_temp);
            break;
            
        }

        $width_original = imagesx($img_original);
        $height_original = imagesy($img_original);
        

        $tmp = imagecreatetruecolor($width_max,$height_max);
        $image_copy = imagecopyresampled($tmp,$img_original,0,0,0,0,$width_max,$height_max,$width_original,$height_original);

        // $targe_fileLogo = $carpeta . $image_copy;
        switch ($logoFileType) 
        {
            case 'jpg':
                $ban = imagejpeg($tmp,$targe_fileLogo,100);
            break;
            case 'jpeg':
                $ban = imagejpeg($tmp,$targe_fileLogo,100);
            break;
            case 'png':
                $ban = imagepng($tmp,$targe_fileLogo,9);
            break;
            case 'gif':
                $ban = imagegif($tmp,$targe_fileLogo);
            break;
            
        }
        
      }
    } else {
      $logo = $arr[0]->logo;
      $targe_fileLogo = $carpeta . $logo;
    }

    $msj = $api->uploadLogo($key_user,$arr[0]->id_api,$targe_fileLogo);

    
    if($ban){
        $query = sprintf("UPDATE empresas SET logo=:logo WHERE PKEmpresa = :id");

        $stmt = $conn->prepare($query);
        $stmt->bindValue(":logo",$logo);
        $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
        $ban1 = $stmt->execute();
    }
    

  }
  $res = [
    "response"=>$ban1,
    "empresa_id"=>$_SESSION['IDEmpresa'],
    "logo"=>$targe_fileLogo_read
  ];
  echo json_encode($res);



?>