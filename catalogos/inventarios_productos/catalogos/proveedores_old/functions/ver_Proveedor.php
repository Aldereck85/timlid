<?php
  session_start();

  if(isset($_POST['id'])){
      require_once('../../../../../include/db-conn.php');
      $json = new \stdClass();
      $id =  $_POST['id'];
      $stmt = $conn->prepare('SELECT * FROM proveedores WHERE PKProveedor= :id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();

      
      $razon = $row['Razon_Social'];
      $nombre = $row['Nombre_comercial'];
      $rfc = $row['RFC'];
      $calle = $row['Calle'];
      $numEx = $row['Numero_exterior'];
      $numInt = $row['Numero_Interior'];
      $colonia = $row['Colonia'];
      $municipio = $row['Municipio'];
      $pais = $row['FKPais'];
      $estado = $row['FKEstado'];
      $cp = $row['CP'];
      $dias = $row['Dias_Credito'];
      $limite = $row['Limite_Credito'];

      $json->razon = $razon;
      $json->nombre = $nombre;
      $json->rfc = $rfc;
      $json->calle = $calle;
      $json->numEx = $numEx;
      $json->numInt = $numInt;
      $json->colonia = $colonia;
      $json->municipio = $municipio;
      $json->pais = $pais;
      $json->estado = $estado;
      $json->cp = $cp;
      $json->dias = $dias;
      $json->limite = $limite;

      $stmt = $conn->prepare('SELECT * FROM datos_contacto_proveedores WHERE FKProveedor= :id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();

      $contacto = $row['Nombre'];
      $apellido = $row['Apellido_Paterno'];
      $telefono = $row['Telefono'];
      $celular = $row['Celular'];
      $email = $row['Email'];

      $json->contacto = $contacto;
      $json->apellido = $apellido;
      $json->telefono = $telefono;
      $json->celular = $celular;
      $json->email = $email;

      $json = json_encode($json);
      echo $json;
  }
?>
