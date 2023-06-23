<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
require_once '../../../include/db-conn.php';

  $file = $_FILES['file'];

  $PKEmpresa = $_SESSION["IDEmpresa"];
  $time = time();
  $ruta_t = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';

  $target_dir = $ruta_t;
  $target_file = $target_dir.basename($file["name"]);
  $newName = $target_dir.basename($time.'.xml');
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if($fileType == "xml"){
    move_uploaded_file($file["tmp_name"], $target_dir.basename($time.'.xml'));

    $contentXML  = new SimpleXMLElement ($newName,null,true);
    $namespaces = $contentXML->getDocNamespaces();

    $ns = $contentXML->getDocNamespaces(true);
    $contentXML->registerXPathNamespace('c', $ns['cfdi']);
    $contentXML->registerXPathNamespace('t', $ns['tfd']);

    foreach ($contentXML->xpath('//c:Comprobante') as $r) {
      $tipoComprobante = $r['TipoDeComprobante'];
      $folio = $r['Folio'];
      $serie = $r['Serie'];
      $importe = $r['Total'];
      $subtotal = $r['SubTotal'];
      $fecha = $r['Fecha'];
    }

    $iva = 0;
    $ieps = 0;
    foreach ($contentXML->xpath('//c:Comprobante//c:Impuestos//c:Traslados//c:Traslado') as $r) {
      if ($r['Impuesto'] == '002'){
        $iva = $iva + $r['Importe'];
      }else if ($r['Impuesto'] == '0003'){
        $ieps = $ieps + $r['Importe'];
      }
    }

    foreach ($contentXML->xpath('//c:Comprobante//c:Emisor') as $r) {
      $rfc = $r['Rfc'];
      $nombre = $r['Nombre'];
    }

    foreach ($contentXML->xpath('//t:TimbreFiscalDigital') as $r) {
      $folioFiscal = $r['UUID'];
    }

    $query = sprintf("SELECT pr.PKProveedor as id
                    FROM proveedores pr 
                    inner join domicilio_fiscal_proveedor dfp on pr.PKProveedor = dfp.FKProveedor
                    WHERE dfp.RFC = ? 
                    and pr.empresa_id = ?
                    limit 1
                    ;");
    $stmt = $conn->prepare($query);
    $stmt->execute(array($rfc,$PKEmpresa));

    if($stmt->rowCount() >0){
      $idProveedor = $stmt->fetchAll();
    }else{
      $idProveedor = 0;
    }


    $arrayXML = array(
        'Valido' => 'Si',
        'TipoArchivo' => $fileType,
        'TipoComprobante' => $tipoComprobante,
        'Folio' => $folio,
        'Serie' => $serie,
        'Importe' => $importe,
        'Subtotal' => $subtotal,
        'Fecha' => $fecha,
        'Iva' => number_format($iva, 2, '.', ''),
        'Ieps' => number_format($ieps, 2, '.', ''),
        'RFCProveedor' => $rfc,
        'NombreProveedor' => $nombre,
        'IdProveedor' => $idProveedor,
        'FolioFiscal' => $folioFiscal,
        'Ruta' => $newName,
        'NameFile' => $time.'.xml'
      );
    echo json_encode($arrayXML);

  }else{
    $arrayXML = array(
      'Valido' => 'No',
      'TipoArchivo' => $fileType,
      'TipoComprobante' => '',
      'Folio' => '',
      'Serie' => '',
      'Importe' => '',
      'Subtotal' => '',
      'Fecha' => '',
      'Iva' => '',
      'Ieps' => '',
      'RFCProveedor' => '',
      'NombreProveedor' => '',
      'IdProveedor' => '',
      'FolioFiscal' => '',
      'Ruta' => '',
      'NameFile' => ''
    );
    echo json_encode($arrayXML);
  }

  //Eliminar archivos con mas de un día en el temporal
	$dir = opendir($ruta_t);
	while($f = readdir($dir))
	{
		if((time()-filemtime($ruta_t.$f) > 3600*24) and !(is_dir($ruta_t.$f)))
		unlink($ruta_t.$f);
	}
	closedir($dir);
?>
