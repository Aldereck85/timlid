<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['razonSocial'])){
    $html = "";
    $razonSocial = $_POST['razonSocial'];

    $stmt = $conn->prepare('SELECT c.PKCotizacion,c.Referencia,c.ImporteTotal,df.PKDomicilioFiscal FROM cotizacion AS c
                            LEFT JOIN domicilio_fiscal AS df ON c.FKCliente = df.FKCliente
                            WHERE df.PKDomicilioFiscal = :cliente AND c.Facturado = :f');
    $stmt->bindValue(':cliente',$razonSocial);
    $stmt->bindValue(':f',0);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    $html = '<option value="">Seleccione una cotizacion...</option>';

    while($row = $stmt->fetch()){
      if($rowCount > 0){
        $html .= '<option value="'.$row['PKCotizacion'].'">'.$row['Referencia'].' - $'.number_format($row['ImporteTotal'],2).'</option>';
      }
    }
    if($rowCount <= 0){
      $html .= '<option style="background:red;color:white" value="">No hay cotizaciones para este cliente.</option>';
    }
    echo $html;
  }


?>
