<?php
session_start();
  //if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../../include/db-conn.php');
        $razon = $_POST['txtRazon'];
        $nombre = $_POST['txtNombre'];
        $rfc = $_POST['txtRFC'];
        $calle = $_POST['txtCalle'];
        $numEx = $_POST['txtNumeroEx'];
        $numInt = $_POST['txtNumeroInt'];
        $colonia = $_POST['txtColonia'];
        $municipio = $_POST['txtMunicipio'];
        $pais = $_POST['txtPais'];
        $estados = $_POST['cmbEstados'];
        $cp = $_POST['txtCP'];
        $diasCredito = $_POST['txtDiasCredito'];
        $limiteCredito = $_POST['txtLimiteCredito'];
        $contacto = $_POST['txtContacto'];
        $apellido = $_POST['txtApellido'];
        $telefono = $_POST['txtTelefono'];
        $celular = $_POST['txtCelular'];
        $email = $_POST['txtEmail'];

        try{
          $stmt = $conn->prepare('INSERT INTO proveedores (Razon_Social,Nombre_comercial,RFC,Calle,Numero_exterior,Numero_Interior,Colonia,Municipio,FKPais,FKEstado,CP,Dias_Credito,Limite_Credito)VALUES(:razon,:nombre,:rfc,:calle,:numEx,:numInt,:colonia,:municipio,:pais,:estado,:cp,:dias_credito,:limite_credito)');
          $stmt->bindValue(':razon',$razon);
          $stmt->bindValue(':nombre',$nombre);
          $stmt->bindValue(':rfc',$rfc);
          $stmt->bindValue(':calle',$calle);
          $stmt->bindValue(':numEx',$numEx);
          $stmt->bindValue(':numInt',$numInt);
          $stmt->bindValue(':colonia',$colonia);
          $stmt->bindValue(':municipio',$municipio);
          $stmt->bindValue(':pais',$pais);
          $stmt->bindValue(':estado',$estados);
          $stmt->bindValue(':cp',$cp);
          $stmt->bindValue(':dias_credito',$diasCredito);
          $stmt->bindValue(':limite_credito',$limiteCredito);
          if($stmt->execute()==true){
            $exito = 1;
          }
          $id = $conn->lastInsertId();
          $stmt = $conn->prepare('INSERT INTO datos_contacto_proveedores (Nombre,Apellido_Paterno,Puesto,Telefono,Celular,Email,FKProveedor) VALUES (:nombre,:apellido,:puesto,:telefono,:celular,:email,:id)');
          $stmt->bindValue(':nombre',$contacto);
          $stmt->bindValue(':apellido',$apellido);
          $stmt->bindValue(':puesto','Vendedor');
          $stmt->bindValue(':telefono',$telefono);
          $stmt->bindValue(':celular',$celular);
          $stmt->bindValue(':email',$email);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          if ($stmt->execute()==true){
            $exito2 = 1;
          }

          if ($exito && $exito2 == 1){
            echo "exito";
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
  }else {
    header("location:../../../../dashboard.php");
  }
 ?>
