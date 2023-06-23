<?php
  session_start();
  $ruta_api = "../../../";
  require_once $ruta_api . "include/db-conn.php";
  require_once $ruta_api . "include/functions_api_facturation.php";
  require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';

  if($_SERVER['REQUEST_METHOD'] == "POST"){

    $api = new API();

    $query = sprintf("SELECT
                            e.PKEmpresa id, 
                            e.id_api,
                            e.RazonSocial,
                            e.RFC,
                            e.giro_comercial,
                            e.calle,
                            e.numero_exterior,
                            e.numero_interior,
                            e.colonia,
                            e.codigo_postal,
                            e.ciudad,
                            ef.Estado,
                            e.registro_patronal,
                            crf.clave,
                            e.propietario_certificado,
                            e.sello_cfdi,
                            e.inicio_vencimiento_sello_cfdi,
                            e.termino_vencimiento_sello_cfdi,
                            e.logo,
                            e.certificado_archivo,
                            e.llave_certificado_archivo,
                            e.password_certificado,
                            e.key_company_api,
                            e.telefono,
                            e.serie_inicial,
                            e.folio_inicial
                      FROM empresas e
                      LEFT JOIN estados_federativos ef ON e.estado_id = ef.PKEstado
                      LEFT JOIN claves_regimen_fiscal crf ON e.regimen_fiscal_id = crf.id
                      WHERE PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();
    $arr = $stmt->fetchAll();
    $rfc = $arr[0]['RFC'];
    $razonSocial = $arr[0]['RazonSocial'];
    $api_id_company = $arr[0]['id_api'];
    $key_api_company =  $arr[0]['key_company_api'];
    $id_company = $arr[0]['id'];

    $query = sprintf("SELECT key_user_company_api FROM empresas WHERE PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();

    $key_user = $_ENV['KEY_API'] ?? "sk_user_2PjVqdXnKBaMWbz4djNVgOm0AgL9N7Qr";

    if(($_POST['giro_comercial'] !== "" && isset($_POST['giro_comercial'])) ? $giro = $_POST['giro_comercial'] : $giro = $arr[0]['giro_comercial']);

    if(($_POST['domicilio'] !== "" && isset($_POST['domicilio'])) ? $domicilio = $_POST['domicilio'] : $domicilio = $arr[0]['domicilio_fiscal']);
    
    if(($_POST['imss'] !== "" && isset($_POST['imss'])) ? $imss = $_POST['imss'] : $imss = $arr[0]['registro_patronal']); 

    if(($_POST['regimen_fiscal'] !== "" && isset($_POST['regimen_fiscal'])) ? $regimen_fiscal = $_POST['regimen_fiscal'] : $regimen_fiscal = $arr[0]['regimen_fiscal']);

    if(($_POST['password_cert'] !== "" && isset($_POST['password_cert'])) ? $pass = $_POST['password_cert'] : $pass = $arr[0]['password_certificado']);

    if(($_POST['street'] !== "" && isset($_POST['street'])) ? $calle = $_POST['street'] : $calle = $arr[0]['calle']);

    if(($_POST['exterior'] !== "" && isset($_POST['exterior'])) ? $ext = $_POST['exterior'] : $ext = $arr[0]['numero_exterior']);

    if(($_POST['interior'] !== "" && isset($_POST['interior'])) ? $int = $_POST['interior'] : $int = $arr[0]['numero_interior']);

    if(($_POST['neighborhood'] !== "" && isset($_POST['neighborhood'])) ? $col = $_POST['neighborhood'] : $col = $arr[0]['colonia']);

    if(($_POST['zip'] !== "" && isset($_POST['zip'])) ? $cp = $_POST['zip'] : $cp = $arr[0]['codigo_postal']);

    if(($_POST['city'] !== "" && isset($_POST['city'])) ? $ciudad = $_POST['city'] : $ciudad = $arr[0]['ciudad']);

    if(($_POST['state'] !== "" && isset($_POST['state'])) ? $estado = $_POST['state'] : $estado = $arr[0]['estado_id']);

    if(($_POST['phone'] !== "" && isset($_POST['phone'])) ? $telefono = $_POST['phone'] : $telefono = $arr[0]['telefono']);

    if(($_POST['serie'] !== "" && isset($_POST['serie'])) ? $serie = $_POST['serie'] : $serie = $arr[0]['serie_inicial']);

    if(($_POST['folio'] !== "" && isset($_POST['folio'])) ? $folio = $_POST['folio'] : $folio = $arr[0]['folio_inicial']);
    
    $query1 = sprintf("SELECT Estado estado FROM estados_federativos WHERE PKEstado = :id");
    $stmt1 = $conn->prepare($query1);
    $stmt1->bindValue(":id",$estado);
    $stmt1->execute();
    $arr1 = $stmt1->fetchAll();

    $arrEstado = $arr1[0]['estado'];

    $query1 = sprintf("SELECT clave FROM claves_regimen_fiscal WHERE id = :id");
    $stmt1 = $conn->prepare($query1);
    $stmt1->bindValue(":id",$regimen_fiscal);
    $stmt1->execute();
    $arr1 = $stmt1->fetchAll();

    $arrRegimeFiscal = $arr1[0]['clave'];
    
    $target_dir = isset($_ENV['RUTA_ARCHIVOS_WRITE']) ? $_ENV['RUTA_ARCHIVOS_WRITE'] . $id_company . "/fiscales/" : "/home/timlid/public_html/app-tim/file_server/" . $id_company . "/fiscales/";
    $carpeta = $target_dir;

    if(isset($_FILES['cert']['type'])){
      $cert = basename($_FILES['cert']['name']);
    } else {
      $cert = "";
    }
    if(isset($_FILES['key']['type'])){
      $key = basename($_FILES['key']['name']);
    } else {
      $key = "";
    }
    
    if(isset($_FILES['logo']['type'])){
      $logo = basename($_FILES['logo']['name']);
    } else {
      $logo = "";
    }
    
    if(!file_exists($carpeta)){
      mkdir($carpeta, 0777, true);
    }

    $targe_fileCert = $carpeta . $cert;
    $targe_fileKey = $carpeta . $key;
    $targe_fileLogo = $carpeta . $logo;

    $certFileType = pathinfo($targe_fileCert,PATHINFO_EXTENSION);
    $keyFileType = pathinfo($targe_fileKey,PATHINFO_EXTENSION);
    $logoFileType = pathinfo($targe_fileLogo,PATHINFO_EXTENSION);

    if($cert !== ""){
      if($certFileType === "cer"){
        move_uploaded_file($_FILES["cert"]["tmp_name"], $targe_fileCert);

        $file = GetCertPEM($targe_fileCert);

        $data = openssl_x509_parse($file);
        $name = $data['subject']['CN'];
        $cer = $data['subject']['serialNumber'];
        $validFrom = date('Y-m-d H:i:s', $data['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $data['validTo_time_t']);


      } 
    } else {
      $cert = $arr[0]['certificado_archivo'];
      $name = $arr[0]['propietario_certificado'];
      $cer = $arr[0]['sello_cfdi'];
      $validFrom = $arr[0]['inicio_vencimiento_sello_cfdi'];
      $validTo = $arr[0]['termino_vencimiento_sello_cfdi'];
      $targe_fileCert = $carpeta . $cert;
    }

    if($key !== ""){
      if($keyFileType === "key"){
        move_uploaded_file($_FILES["key"]["tmp_name"], $targe_fileKey);
      }
    } else {
      $key = $arr[0]['llave_certificado_archivo'];
      $targe_fileCert = $carpeta . $cert;
      $targe_fileKey = $carpeta . $key;
    }
    
    if($logo !== ""){
      if($logoFileType === "jpg" || $logoFileType === "jpeg" || $logoFileType === "png" || $logoFileType === "svg"){
        move_uploaded_file($_FILES["logo"]["tmp_name"], $targe_fileLogo);
      }
    } else {
      $logo = $arr[0]['logo'];
      $targe_fileLogo = $carpeta . $logo;
    }
    
    
    $api->uploadFileFiscal($key_user,$api_id_company,$targe_fileCert,$targe_fileKey,$pass);

    $api->uploadLogo($key_user,$api_id_company,$targe_fileLogo);

    $arrFiscalData = array(
      "name" => $razonSocial,
      "legal_name" => $razonSocial,
      "tax_system" => $arrRegimeFiscal,
      "address" => array(
        "zip" => $cp,
        "street" => $calle,
        "exterior" => $ext,
        "interior" => $int,
        "neighborhood" => $col,
        "city" => $ciudad,
        "municipality" => $ciudad,
        "state" => $arrEstado
      )
    );

    $api->updateFiscalData($key_user,$api_id_company,$arrFiscalData);

    $query = sprintf("UPDATE empresas SET
                            giro_comercial = :giro_comercial,
                            calle = :calle,
                            numero_exterior = :numero_exterior,
                            numero_interior = :numero_interior,
                            colonia = :colonia,
                            codigo_postal = :codigo_postal,
                            ciudad = :ciudad,
                            estado_id = :estado,
                            registro_patronal = :registro_patronal,
                            regimen_fiscal_id = :regimen_fiscal, 
                            propietario_certificado = :propietario_certificado, 
                            sello_cfdi = :sello_cfdi, 
                            inicio_vencimiento_sello_cfdi=:inicio_vencimiento_sello_cfdi,
                            termino_vencimiento_sello_cfdi=:termino_vencimiento_sello_cfdi,
                            logo=:logo,
                            certificado_archivo=:certificado_archivo,
                            password_certificado=:password_certificado,
                            llave_certificado_archivo=:llave_certificado_archivo,
                            telefono = :telefono,
                            serie_inicial = :serie,
                            folio_inicial = :folio
                    WHERE PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":giro_comercial",$giro);
    $stmt->bindValue(":calle",$calle);
    $stmt->bindValue(":numero_exterior",$ext);
    $stmt->bindValue(":numero_interior",$int);
    $stmt->bindValue(":colonia",$col);
    $stmt->bindValue(":codigo_postal",$cp);
    $stmt->bindValue(":ciudad",$ciudad);
    $stmt->bindValue(":estado",$estado);
    $stmt->bindValue(":registro_patronal",$imss);
    $stmt->bindValue(":regimen_fiscal",$regimen_fiscal);
    $stmt->bindValue(":propietario_certificado",$name);
    $stmt->bindValue(":sello_cfdi",$cer);
    $stmt->bindValue(":inicio_vencimiento_sello_cfdi",$validFrom);
    $stmt->bindValue(":termino_vencimiento_sello_cfdi",$validTo);
    $stmt->bindValue(":logo",$logo);
    $stmt->bindValue(":certificado_archivo",$cert);
    $stmt->bindValue(":llave_certificado_archivo",$key);
    $stmt->bindValue(":password_certificado",$pass);
    $stmt->bindValue(":telefono",$telefono);
    $stmt->bindValue(":serie",$serie);
    $stmt->bindValue(":folio",$folio);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    echo $stmt->execute();

  }

  function GetCertPEM($fileName) {
    $cerContent = file_get_contents($fileName);
    /* Convert .cer to .pem, cURL uses .pem */
    $cerPEM = '-----BEGIN CERTIFICATE-----' . PHP_EOL . chunk_split(base64_encode($cerContent), 64, PHP_EOL) . '-----END CERTIFICATE-----' . PHP_EOL;
    return $cerPEM;
  }
  
?>