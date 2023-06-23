<?php
  session_start();
  
  $user = $_SESSION["Usuario"];

  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
      include "../../../include/db-conn.php";
      return $conn;
    }
  }

  class get_data{
    
    function getCountriesCombo($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT PKPais AS PKData, Pais AS Data FROM paises');
      $stmt = $db->prepare($query);
      $stmt->execute();
      
      return $stmt->fetchAll();
    }

    function getStatesCombo($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT PKEstado AS PKData, Estado AS Data FROM estados_federativos WHERE FKPais = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      
      return $stmt->fetchAll();
    }

    function getPaqueteria($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT * FROM proveedores p
                        LEFT JOIN domicilio_fiscal_proveedor dfp ON p.PKProveedor = dfp.FKProveedor
                        WHERE p.PKProveedor = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
  }

  class save_data{
    function savePaqueteria($value,$empresa,$usuario){
      $con = new conectar();
      $db = $con->getDb();
      date_default_timezone_set('America/Mexico_City');
      $ban = 0;
      $aux = json_decode($value,true);

      $now = date("Y-m-d H:i:s");

      if($aux[2]['value'] == ""){
        $email = "sin email";
      }else{
        $email = $aux[2]['value'];
      }
      
      try{

        $query = sprintf('SELECT * FROM domicilio_fiscal_proveedor dfp
				                                INNER JOIN proveedores p ON dfp.FKProveedor = p.PKProveedor 
                                        WHERE dfp.RFC = :rfc AND p.estatus = 1 AND p.empresa_id = :empresa');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':rfc',$aux[3]['value'],PDO::PARAM_STR);
        $stmt->bindValue(':empresa',$empresa,PDO::PARAM_INT);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if($rowCount === 0){
          $query = sprintf('INSERT INTO proveedores (NombreComercial,Telefono,Email,estatus,tipo,empresa_id,usuario_creacion_id,usuario_edicion_id,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)');
          $stmt = $db->prepare($query);
          $ban += $stmt->execute(array($aux[1]['value'],$aux[12]['value'],$email,1,2,$empresa,$usuario,$usuario,$now,$now));
          $pk = $db->lastInsertId();

          $query = sprintf('INSERT INTO domicilio_fiscal_proveedor (Razon_Social,RFC,Calle,Numero_exterior,Numero_interior,Pais,Estado,Municipio,Colonia,CP,Email,FKProveedor) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
          $stmt = $db->prepare($query);
          $ban += $stmt->execute(array($aux[0]['value'],$aux[3]['value'],$aux[4]['value'],$aux[5]['value'],$aux[6]['value'],$aux[7]['value'],$aux[8]['value'],$aux[9]['value'],$aux[11]['value'],$aux[10]['value'],$email,$pk));
        }else{
          $ban = "La paqueteria ya está registrada.";
        }
        
      }catch (PDOException $e){
        $ban = "Error en Consulta: ".$e->getMessage();
      }
      
      return $ban;
    }
  }

  class edit_data{

    function editPaqueteria($value,$usuario){
      $con = new conectar();
      $db = $con->getDb();
      date_default_timezone_set('America/Mexico_City');
      $ban = "";

      $array = json_decode($value,true);

      $now = date("Y-m-d H:i:s");
      
      try{

        $query = sprintf('UPDATE proveedores SET NombreComercial = ?,Telefono =?,Email =?,usuario_edicion_id=?,updated_at=? WHERE PKProveedor = ?');

        $stmt = $db->prepare($query);

        $stmt->execute(array($array[2]['value'],$array[13]['value'],$array[3]['value'],$usuario,$now,$array[0]['value']));

        $rowCount = $stmt->rowCount();

        $query1 = sprintf('UPDATE domicilio_fiscal_proveedor SET Razon_Social = ?,RFC =?,Calle=?,Numero_exterior=?,Numero_interior=?,Pais=?,Estado=?,Municipio=?,Colonia=?,CP=?,Email=? WHERE FKProveedor =?');

        $stmt1 = $db->prepare($query1);

        $stmt1->execute(array($array[1]['value'],$array[4]['value'],$array[5]['value'],$array[6]['value'],$array[7]['value'],$array[8]['value'],$array[9]['value'],$array[10]['value'],$array[12]['value'],$array[11]['value'],$array[3]['value'],$array[0]['value']));

        $rowCount1 = $stmt1->rowCount();

        if($rowCount !== 0 || $rowCount1 !== 0){
          $ban = 1;
        }
      }catch (PDOException $e){
        $ban = "Error en Consulta: ".$e->getMessage();
      }

      return $ban;
    }

  }

  class delete_data{

    function deletePaqueteria($value,$usuario){
      $con = new conectar();
      $db = $con->getDb();
      date_default_timezone_set('America/Mexico_City');
      $now = date("Y-m-d H:i:s");
      try{
        $query = sprintf('UPDATE proveedores SET estatus = 0, updated_at = :update_date, usuario_edicion_id = :usuario WHERE PKProveedor = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':usuario',$usuario,PDO::PARAM_INT);
        $stmt->bindValue(':id',$value,PDO::PARAM_INT);
        $stmt->bindvalue(':update_date',$now,PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        /*$query = sprintf('DELETE FROM proveedores WHERE PKProveedor =?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $rowCount = $stmt->rowCount();*/

        /*$query1 = sprintf('DELETE FROM domicilio_fiscal_proveedor WHERE FKProveedor =?');
        $stmt1 = $db->prepare($query1);
        $stmt1->execute(array($value));
        $rowCount1 = $stmt1->rowCount();*/

        if($rowCount === 0){
          $ban = "No se pudo eliminar la paqueteria ".$rowCount;
        }
        /*if($rowCount1 === 0){
          $ban = "No se pudo eliminar los datos fiscales de paqueteria ".$rowCount1;
        }
        */
        if($rowCount !== 0){
          $ban = 1;
        }
      }catch(PDOException $e){
        $ban = "Error en Consulta: ".$e->getMessage();
      }

      return $ban;
    }

  }

?>