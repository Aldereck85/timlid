<?php
  require_once('../../../include/db-conn.php');
  echo ("hola");
  /*$resultData = [];


  if(isset($_FILES['xml_data']['name']) && $_FILES['xml_data']['name'] != ''){
    $accept_ext = array('xml');
    $file_data = explode('.',$_FILES['xml_data']['name']);
    $file_ext = end($file_data);

    if(in_array($file_ext,$accept_ext)){
      $xml_data = simplexml_load_file($_FILES['xml_data']['name']);

      echo $xml_data;

      $query = "INSERT INTO archivos (Facturas) VALUES(:factura)";
      $statement = $pdo_conn->prepare($query);
      for($i = 0;$i < count($xml_data);i++){
        $result = $statement->execute([
          ':factura' => $xml_data->post[$i]->Factura//pude ser Factura en vez de factura
        ]);

        if(!result){
          $resultData['status'] = '400';
          $resultData['message'] = 'XML file have invalid data on conntion error';
          echo json_encode($resultData);
          exit;
        }
      }
    }
    $resultData['status'] = '200';
    $resultData['message'] = 'XML Data imported successfully';
  }else{
    $resultData['status'] = '400';
    $resultData['message'] = 'Not valid file format';
  }

  echo json_encode($resultData);*/
?>
