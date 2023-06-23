<?php

use get_data as GlobalGet_data;

session_start();
date_default_timezone_set('America/Mexico_City');
class conectar
{ //Llamado al archivo de la conexión.
  function getDb()
  {
    include "../../../include/db-conn.php";
    return $conn;
  }
}

class get_data
{
  function getDataEnterprise(){
    $con = new conectar();
    $db = $con->getDb();
    $data = [];

    $query = sprintf("select * from empresas where PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();

    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

    $target_dir = isset($_ENV['RUTA_ARCHIVOS_READ']) ? $_ENV['RUTA_ARCHIVOS_READ'] . $arr[0]->PKEmpresa . "/fiscales/" : "/home/timlid/public_html/app-tim/file_server/" . $arr[0]->PKEmpresa . "/fiscales/";
    $targe_fileLogo = $target_dir . $arr[0]->logo;
    
    array_push($data,array(
      "logo"=>$targe_fileLogo,
      "termino_vencimiento_sello_cfdi"=>$arr[0]->termino_vencimiento_sello_cfdi,
      "nombre_comercial"=>$arr[0]->nombre_comercial,
      "RazonSocial"=>$arr[0]->RazonSocial,
      "RFC"=>$arr[0]->RFC,
      "regimen_fiscal_id"=>$arr[0]->regimen_fiscal_id,
      "telefono"=>$arr[0]->telefono,
      "calle"=>$arr[0]->calle,
      "numero_exterior"=>$arr[0]->numero_exterior,
      "numero_interior"=>$arr[0]->numero_interior,
      "codigo_postal"=>$arr[0]->codigo_postal,
      "colonia"=>$arr[0]->colonia,
      "ciudad"=>$arr[0]->ciudad,
      "estado_id"=>$arr[0]->estado_id,
      "certificado_archivo"=>$arr[0]->certificado_archivo,
      "certificado_archivo"=>$arr[0]->certificado_archivo,
      "llave_certificado_archivo"=>$arr[0]->llave_certificado_archivo,
      "llave_certificado_archivo"=>$arr[0]->llave_certificado_archivo
    ));

    return $data;
  }

  function getRegimenFiscal(){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT id, CONCAT(clave,' - ',descripcion) texto FROM claves_regimen_fiscal order by clave asc");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getEstados(){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKEstado id, Estado texto FROM estados_federativos where FKPais = 146");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getValidRazonSocial($valid){
    $ban = 0;
    $get_data = new get_data();
    if(
      $get_data->str_contains(strtolower(trim($valid)), 's.a. de c.v.') == true ||
      $get_data->str_contains(strtolower(trim($valid)), 'sa de cv') == true ||
      $get_data->str_contains(strtolower(trim($valid)), 's.a.') == true || 
      $get_data->str_contains(strtolower(trim($valid)), ' sa ') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 'sociedad anónima') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 'sociedad anonima') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 's. de r.l.') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 's de rl') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 'sociedad de responsabilidad limitada') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 's. en c') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 's en c') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 'sociedad en comandita') == true || 
      $get_data->str_contains(strtolower(trim($valid)), 'socidad civil') == true
      )
    {
      $ban = 0;
    } else {
      $ban = 1;
    }
    return $ban;
  }

  function str_contains (string $haystack, string $needle)
  {
    return empty($needle) || strpos($haystack, $needle) !== false;
  }

  function getTaxSystem($value){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT clave FROM claves_regimen_fiscal WHERE id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getFederalState($value){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT Estado estado FROM estados_federativos WHERE PKEstado = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getIdCompany(){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT id_api FROM empresas WHERE PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}

class update_data{
  function updateDataEnterprise($value){
    $ruta_api = "../../../";
    
    require_once $ruta_api . "include/functions_api_facturation.php"; 
    require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';

    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $api = new API();
    $ban = "";
   
    $key_user = $_ENV['KEY_API'] ?? "sk_user_2PjVqdXnKBaMWbz4djNVgOm0AgL9N7Qr";

    $key_company = $get_data->getIdCompany();
    
    $data = json_decode($value);

    $state = $get_data->getFederalState($data->estado);
    $tax_system = $get_data->getTaxSystem($data->regimen_fiscal);

    $fiscal_data = array(
      "name" => strtoupper($data->nombre_empresa),
      "legal_name" => strtoupper($data->razon_social),
      "tax_system" => $tax_system[0]->clave,
      "phone"=> $data->telefono,
      "address" => array(
        "zip" => $data->codigo_postal,
        "street" => $data->calle,
        "exterior" => $data->numero_exterior,
        "interior" => $data->numero_interior,
        "neighborhood" => $data->colonia,
        "city" => $data->ciudad,
        "municipality" => $data->ciudad,
        "state" => $state[0]->estado
      )
    );
    $mensaje = $api->updateFiscalData($key_user,$key_company[0]->id_api,$fiscal_data);
    //if($mensaje !== null && $mensaje !== ""){
      if(property_exists($mensaje,"id")){
        $query = sprintf("update empresas set 
                            RazonSocial = :razon_social,
                            nombre_comercial = :nombre_comercial,
                            regimen_fiscal_id = :regimen_fiscal_id,
                            calle = :calle,
                            numero_exterior = :numero_exterior,
                            numero_interior = :numero_interior,
                            colonia = :colonia,
                            ciudad = :ciudad,
                            estado_id = :estado_id,
                            telefono = :telefono,
                            codigo_postal = :codigo_postal
                          where PKEmpresa = :id
                        ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":razon_social", strtoupper($data->razon_social));
        $stmt->bindValue(":nombre_comercial", strtoupper($data->nombre_empresa));
        $stmt->bindValue(":regimen_fiscal_id", $data->regimen_fiscal);
        $stmt->bindValue(":calle", $data->calle);
        $stmt->bindValue(":numero_exterior", $data->numero_exterior);
        $stmt->bindValue(":numero_interior", $data->numero_interior);
        $stmt->bindValue(":colonia", $data->colonia);
        $stmt->bindValue(":ciudad", $data->ciudad);
        $stmt->bindValue(":estado_id", $data->estado);
        $stmt->bindValue(":telefono", $data->telefono);
        $stmt->bindValue(":codigo_postal", $data->codigo_postal);
        $stmt->bindValue(":id", $_SESSION['IDEmpresa']);

        $ban = $stmt->execute();

      } else {
        $ban = $mensaje;
      }
    // } else {
    //   $ban = $mensaje;
    // }
    return $ban;
    
  }
}

?>