<?php
  include "../../../include/db-rm.php";
  $file = $_FILES['file'];
  //$data = new SimpleXMLElement($file);
  //$rootDir = realpath($file);
  //print_r($data);

  $target_dir = "../catalogos/entradas_productos/documentos/";
  $target_file = $target_dir.basename($file["name"]);
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  move_uploaded_file($file["tmp_name"], $target_file);

  //$arrayXML = [];

  if($fileType == "xml"){
    $folio = "";
    $rfc = "";
    $idProveedor = "";
    $contentXML  = new SimpleXMLElement ($target_file,null,true);
    $namespaces = $contentXML->getDocNamespaces();
    foreach ($contentXML->xpath('//cfdi:Comprobante') as $r) {
      $folio = $r['Folio'];

    }
    foreach ($contentXML->xpath('//cfdi:Comprobante//cfdi:Emisor') as $r) {
      $rfc = $r['Rfc'];
    }

    $query = sprintf('SELECT PKProveedor FROM proveedores WHERE RFC = ?');
    $stmt = $conn->prepare($query);
    $stmt->execute(array($rfc));
    $idProveedor = $stmt->fetchAll();
    $arrayXML = array(
        'Folio' => $folio,
        'Rfc' => $rfc,
        'Id' => $idProveedor
      );
    echo json_encode($arrayXML);

  }else{
    echo "no es archivo xml ".$fileType;
  }

?>
