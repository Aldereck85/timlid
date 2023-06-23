<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idImpuesto'];
  //print_r($_POST);

  if(isset($_POST['idImpuesto'])){
    try{
      $stmt = $conn->prepare("SELECT TipoImpuesto, TipoImporte, Operacion FROM impuesto WHERE PKImpuesto = :idImpuesto ");
      $stmt->bindValue(":idImpuesto" , $id);
      $stmt->execute();
      $row = $stmt->fetch();

      $stmt = $conn->prepare('SELECT Tasa FROM impuesto_tasas WHERE FKImpuesto = :idimpuesto ORDER BY Tasa ASC');
      $stmt->bindValue(':idimpuesto', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row_impuesto = $stmt->fetchAll();

      $TasasImpuestos = "";

      if($stmt->rowCount() > 0){
            $TasasImpuestos.= "<select name='txtImporteImpuesto' id='txtImporteImpuesto' class='form-control'>";
        foreach ($row_impuesto as $imp) {
            $TasasImpuestos.= "<option value='".$imp['Tasa']."'>".$imp['Tasa']."</option>";
        }
            $TasasImpuestos.= "</select>";
      }
      else{
        $TasasImpuestos.="<input type='number' value='' name='txtImporteImpuesto' id='txtImporteImpuesto' class='form-control'>";
      }

      $json = new \stdClass();
      $json->tipoImpuesto = $row['TipoImpuesto'];
      $json->tipoImporte = $row['TipoImporte'];
      $json->Operacion = $row['Operacion'];
      $json->TasasImpuestos = $TasasImpuestos;
      $json = json_encode($json);
      echo $json;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>