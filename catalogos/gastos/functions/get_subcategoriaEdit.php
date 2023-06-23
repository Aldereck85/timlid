<?php
session_start();
//var_dump($_POST);
if(isset($_POST['idCat'])){
  require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $idCat = $_POST['idCat'];
    $idSubcat = $_POST['idSubcat'];

    $stmt = $conn->prepare('SELECT * FROM subcategorias_gastos WHERE FKCategoria = :id');
    $stmt->execute(array(':id'=>$idCat));
    $rowCount = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($rowCount == 0){
      $subCategorias = "<option value='0'>Seleccionar subcategoría</option>";
    }else{
      $subCategorias = "<option data-placeholder='true'></option>";
      foreach($row as $b){
          $subCategorias .= "<option value='".$b["PKSubcategoria"]."'";
          if($idSubcat == $b['PKSubcategoria']){
            $subCategorias .= " selected";
          }
          $subCategorias .= ">".$b['Nombre']."</option>";
      }
    }

    $json->subCategorias = $subCategorias;
    $json = json_encode($json);
    echo $json;
}
    
?>