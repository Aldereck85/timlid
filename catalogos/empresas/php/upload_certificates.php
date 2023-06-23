<?php
  session_start();
  $ruta_api = "../../../";
  require_once $ruta_api . "include/db-conn.php";
  require_once $ruta_api . "include/functions_api_facturation.php";
  require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
  $ban = "";

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    $api = new API();
    $pass = $_POST['pass_cert'];

    $query = sprintf("select e.PKEmpresa id, e.id_api, e.key_user_company_api,e.logo from empresas e where e.PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

    $key_user = $_ENV['KEY_API'] ?? "sk_user_2PjVqdXnKBaMWbz4djNVgOm0AgL9N7Qr";

    $target_dir = isset($_ENV['RUTA_ARCHIVOS_WRITE']) ? $_ENV['RUTA_ARCHIVOS_WRITE'] . $arr[0]->id . "/fiscales/" : "/home/timlid/public_html/app-tim/file_server/" . $arr[0]->id . "/fiscales/";
    $carpeta = $target_dir;

    if(!file_exists($carpeta)){
      mkdir($carpeta, 0777, true);
    }

    if(isset($_FILES['file_cer']['type'])){
      $cert = basename($_FILES['file_cer']['name']);
    } else {
      $cert = "";
    }
    if(isset($_FILES['file_key']['type'])){
      $key = basename($_FILES['file_key']['name']);
    } else {
      $key = "";
    }

    $targe_fileCert = $carpeta . $cert;
    $targe_fileKey = $carpeta . $key;

    $certFileType = pathinfo($targe_fileCert,PATHINFO_EXTENSION);
    $keyFileType = pathinfo($targe_fileKey,PATHINFO_EXTENSION);

    if($cert !== ""){  
      if($certFileType === "cer"){
        move_uploaded_file($_FILES["file_cer"]["tmp_name"], $targe_fileCert);

        $file = GetCertPEM($targe_fileCert);

        $data = openssl_x509_parse($file);
        $name = $data['subject']['CN'];
        $cer = $data['subject']['serialNumber'];
        $validFrom = date('Y-m-d H:i:s', $data['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $data['validTo_time_t']);
        $aux = $data['subject']['x500UniqueIdentifier'];

        $rfc = "";
        if (strlen($aux) > 13) {
            for ($i = 0; $i < 13; $i++) {
                $rfc .= $aux[$i];
            }
        } else if (strlen($aux) <= 13) {
            $rfc = $aux;
        }
      }
    }

    if($key !== ""){
      if($keyFileType === "key"){
        move_uploaded_file($_FILES["file_key"]["tmp_name"], $targe_fileKey);
      }
    }

    $message = $api->uploadFileFiscal($key_user,$arr[0]->id_api,$targe_fileCert,$targe_fileKey,$pass);
    $ban = "";
    $response="";
    
    if(isset($message->id)){
      $query = sprintf("UPDATE empresas SET   
                          RFC = :rfc,
                          propietario_certificado = :propietario_certificado, 
                          sello_cfdi = :sello_cfdi, 
                          inicio_vencimiento_sello_cfdi=:inicio_vencimiento_sello_cfdi,
                          termino_vencimiento_sello_cfdi=:termino_vencimiento_sello_cfdi,
                          certificado_archivo=:certificado_archivo,
                          llave_certificado_archivo=:llave_certificado_archivo,
                          password_certificado=:password_certificado
                      WHERE PKEmpresa = :id");
      $stmt = $conn->prepare($query);
      $stmt->bindValue(":rfc",$rfc);                  
      $stmt->bindValue(":propietario_certificado",$name);
      $stmt->bindValue(":sello_cfdi",$cer);
      $stmt->bindValue(":inicio_vencimiento_sello_cfdi",$validFrom);
      $stmt->bindValue(":termino_vencimiento_sello_cfdi",$validTo);
      $stmt->bindValue(":certificado_archivo",$cert);
      $stmt->bindValue(":llave_certificado_archivo",$key);
      $stmt->bindValue(":password_certificado",$pass);
      $stmt->bindValue(":id",$_SESSION['IDEmpresa']);

      $ban =  $stmt->execute();
      $key_api = $api->renewLiveApiKey($key_user,$arr[0]->id_api);
      
      if(gettype($key) === "string"){
        $query1 = sprintf("update empresas set key_company_api = :key where PKEmpresa = :id");
        $stmt1 = $conn->prepare($query1);
        $stmt1->bindValue(":key",$key_api);
        $stmt1->bindValue(":id",$_SESSION['IDEmpresa']);
        $stmt1->execute();
        
      }
      $response = "Los certificados se guardaron con éxito";
    } else {
        if(isset($message->message)){
            $response = $message->message;
        } else {
            $response = "No se pudieron guardar los certtificados. Verifique que los certificados sean CSD.";
        }
     
    }
  }

  $res = [
    "response"=>$ban,
    "empresa_id"=>$_SESSION['IDEmpresa'],
    "fecha_vencimiento" => date("d-m-Y",strtotime($validTo)),
    "rfc"=>$rfc,
    "message" => $response
  ];
  echo json_encode($res);

  function GetCertPEM($fileName) {
    $cerContent = file_get_contents($fileName);
    /* Convert .cer to .pem, cURL uses .pem */
    $cerPEM = '-----BEGIN CERTIFICATE-----' . PHP_EOL . chunk_split(base64_encode($cerContent), 64, PHP_EOL) . '-----END CERTIFICATE-----' . PHP_EOL;
    return $cerPEM;
  }

?>