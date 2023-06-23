<?php
session_start();
//var_dump($_POST);
if(isset($_POST['id'])){
  require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $htmlc = $_POST['id'];

    $stmt = $conn->prepare('SELECT * FROM subcategorias_gastos WHERE FKCategoria = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $cuentaR = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($cuentaR == 0){
      $subCategorias = "<option value='0'>Seleccionar subcategoría</option>";
    }else{
      $subCategorias = "<option data-placeholder='true'></option>";
      foreach($row as $b){
        $subCategorias .= "<option value='".$b["PKSubcategoria"]."'";
        if($b["Nombre"] == 'Sin subcategoria'){
          $subCategorias .= " selected";
        }
        $subCategorias .= ">".$b['Nombre']."</option>";
      }
    }
    
    $json->pkcategoria = $htmlc;
    $json->subCategorias = $subCategorias;
    $json = json_encode($json);
    echo $json;
}
    
?>