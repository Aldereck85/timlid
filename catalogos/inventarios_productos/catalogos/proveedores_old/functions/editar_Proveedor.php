<?php
session_start();
if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
        $id = (int) $_POST['txtId'];
        $razon = $_POST['txtRazon'];
        $nombre = $_POST['txtNombre'];
        $rfc = $_POST['txtRFC'];
        $calle = $_POST['txtCalle'];
        $numEx = $_POST['txtNumeroEx'];
        $numInt = $_POST['txtNumeroInt'];
        $colonia = $_POST['txtColonia'];
        $municipio = $_POST['txtMunicipio'];
        $pais = $_POST['txtPais'];
        $estado = $_POST['cmbEstados'];
        $cp = $_POST['txtCP'];
        $diasCredito = $_POST['txtDiasCredito'];
        $limiteCredito = $_POST['txtLimiteCredito'];

        $contacto = $_POST['txtContacto'];
        $apellido = $_POST['txtApellido'];
        $telefono = $_POST['txtTelefono'];
        $celular = $_POST['txtCelular'];
        $email = $_POST['txtEmail'];


        try{
          $stmt = $conn->prepare('UPDATE proveedores set Razon_Social= :razon, Nombre_comercial= :nombre,RFC= :rfc,Calle =:calle,Numero_exterior =:numEx,Numero_Interior =:numInt,Colonia =:colonia,Municipio =:municipio,FKPais = :pais, FKEstado =:estado,CP =:cp,Dias_Credito= :dias_credito,Limite_Credito= :limite_credito WHERE PKProveedor = :id');
          $stmt->bindValue(':razon',$razon);
          $stmt->bindValue(':nombre',$nombre);
          $stmt->bindValue(':rfc',$rfc);
          $stmt->bindValue(':calle',$calle);
          $stmt->bindValue(':numEx',$numEx);
          $stmt->bindValue(':numInt',$numInt);
          $stmt->bindValue(':colonia',$colonia);
          $stmt->bindValue(':municipio',$municipio);
          $stmt->bindValue(':pais',$pais);
          $stmt->bindValue(':estado',$estado);
          $stmt->bindValue(':cp',$cp);
          $stmt->bindValue(':dias_credito',$diasCredito);
          $stmt->bindValue(':limite_credito',$limiteCredito);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          if($stmt->execute()==true){
            $exito = 1;
          }
          
          $stmt = $conn->prepare('UPDATE datos_contacto_proveedores SET Nombre = :nombre ,Apellido_Paterno = :apellido, Puesto = :puesto,Telefono = :telefono, Celular = :celular, Email = :email   WHERE FKProveedor = :id');
          $stmt->bindValue(':nombre',$contacto);
          $stmt->bindValue(':apellido',$apellido);
          $stmt->bindValue(':puesto','Vendedor');
          $stmt->bindValue(':telefono',$telefono);
          $stmt->bindValue(':celular',$celular);
          $stmt->bindValue(':email',$email);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          if($stmt->execute()==true){
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
}
?>

