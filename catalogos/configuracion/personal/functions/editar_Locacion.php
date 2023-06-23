<?php
  session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        
        $id =  $_POST['id'];
        $sucursal = $_POST['sucursal'];
        $calle = $_POST['calle'];
        $nExterno = $_POST['nExterno'];
        $nInterior = $_POST['nInterior'];
        $colonia = $_POST['colonia'];
        $estado = $_POST['estado'];
        $municipio = $_POST['municipio'];
        $pais = $_POST['pais'];
        $prefijo = $_POST['prefijo'];
        $telefono = $_POST['telefono'];
        $actInventario = $_POST['actInventario'];
        $zonaSalarioMinimo = $_POST['zonaSalarioMinimo'];

        try{
          $stmt = $conn->prepare('UPDATE sucursales set sucursal= :sucursal, calle=:calle, numero_exterior=:nuext, prefijo=:prefijo, numero_interior=:nuint, colonia=:col, municipio=:mun, estado_id=:edo, pais_id=:pais, telefono=:telefono, activar_inventario=:actInventario, zona_salario_minimo = :zona_salario_minimo  WHERE id = :id');
          $stmt->bindValue(':sucursal',$sucursal);
          $stmt->bindValue(':calle',$calle);
          $stmt->bindValue(':nuext',$nExterno);
          $stmt->bindValue(':prefijo',$prefijo);
          $stmt->bindValue(':nuint',$nInterior);
          $stmt->bindValue(':col',$colonia);
          $stmt->bindValue(':mun',$municipio);
          $stmt->bindValue(':edo',$estado);
          $stmt->bindValue(':pais',$pais);
          $stmt->bindValue(':id', $id);
          $stmt->bindValue(':telefono', $telefono);
          $stmt->bindValue(':actInventario', $actInventario);
          $stmt->bindValue(':zona_salario_minimo', $zonaSalarioMinimo);

          if($stmt->execute()){
            echo "1";
          }else{
            echo "0";
          }
        }catch(PDOException $ex){
          return $ex->getMessage();
        }
      $con = null;
      $db = null;
      $stmt = null;
  }
?>

