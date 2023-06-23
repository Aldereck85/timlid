<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['cliente'])){
    $html = "";
    $cliente = $_POST['cliente'];

    $stmt = $conn->prepare('SELECT * FROM domicilio_fiscal AS df
                            LEFT JOIN clientes AS c ON df.FKCliente = c.PKCliente
                            WHERE df.FKCliente = :cliente AND df.UID IS NOT NULL');
    $stmt->bindValue(':cliente',$cliente);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    $html = '<option value="">Seleccione una razon social...</option>';

    while($row = $stmt->fetch()){
      if($rowCount > 0){
        $html .= '<option value="'.$row['PKDomicilioFiscal'].'">'.$row['Razon_Social'].'</option>';
      }
    }
    if($rowCount <= 0){
      $html .= '<option style="background:red;color:white" value="">No hay razones sociales acitvas para este cliente.</option>';
    }
    echo $html;
  }


?>
