<?php
session_start();
$idempresa = $_SESSION["IDEmpresa"];
if(isset($_POST['id'])){
    require_once('../../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM subcategorias_gastos WHERE PKSubcategoria= :id");
    $stmt->execute(array(':id' => $id));
    //$stmt->execute();
    $row = $stmt->fetch();
    $html = $row['Nombre'];
    $fkcategoria = $row['FKCategoria'];
    
    $json->html = $html;
    $json->html2 = $fkcategoria;

    $stmt2 = $conn->prepare('SELECT * FROM categoria_gastos WHERE empresa_id = :idempresa AND estatus = 1');
    $stmt2->bindValue(':idempresa', $idempresa);
    $stmt2->execute();
    $row = $stmt2->fetchAll();

    $lista = "";
      foreach($row as $d){
      $lista .= "<option value='".$d["PKCategoria"]."'";
      if($fkcategoria == $d["PKCategoria"]){
        $lista .= " selected";
      }
      $lista .= ">".$d['Nombre']."</option>";
      }
      
    $json->lista = $lista;
    
    $json = json_encode($json);
    echo $json;
    
    $con = null;
    $db = null;
    $stmt = null;
}
?>