<?php
  session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        $id =  $_POST['idLocacionU'];
        $locacion = $_POST['txtLocacionU'];
        $calle = $_POST['txtCalleU'];
        $nuext = $_POST['txtNeU'];
        $prefijo = $_POST['prefijo'];
        $nuint = $_POST['txtNiU'];
        $col = $_POST['txtColoniaU'];
        $muni = $_POST['txtMunicipioU'];
        $edo = $_POST['cmbEstadosU'];
        $pais = $_POST['cmbPaisU'];
        $telefono = $_POST['telefono'];


        try{
          $stmt = $conn->prepare('UPDATE locaciones set Locacion= :locacion, Calle=:calle, NumExt=:nuext, Prefijo=:prefijo, NumInt=:nuint, Colonia=:col, Municipio=:mun, FKEstado=:edo, FKPais=:pais, Telefono=:telefono  WHERE PKLocacion = :id');
          $stmt->bindValue(':locacion',$locacion);
          $stmt->bindValue(':calle',$calle);
          $stmt->bindValue(':nuext',$nuext);
          $stmt->bindValue(':prefijo',$prefijo);
          $stmt->bindValue(':nuint',$nuint);
          $stmt->bindValue(':col',$col);
          $stmt->bindValue(':mun',$muni);
          $stmt->bindValue(':edo',$edo);
          $stmt->bindValue(':pais',$pais);
          $stmt->bindValue(':id', $id);
          $stmt->bindValue(':telefono', $telefono);
          if($stmt->execute()){
            echo "exito";
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
  }
?>

