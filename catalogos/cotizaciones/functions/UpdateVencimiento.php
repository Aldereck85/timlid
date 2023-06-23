<?php
  class conectar
  { //Llamado al archivo de la conexión.
  
      public function getDb()
      {
        require_once('../../../include/db-conn.php');
          return $conn;
      }
  }
  $con = new conectar();
    $db = $con->getDb();        
if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];

    $fecha = $_POST['fecha'];
    $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)  )) ;
    //echo("Dentro");
    try {
        $query = sprintf('call spu_update_vencimiento_cotizacion(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($id,$fecha));

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$data[0] = ['status' => $status];
       // echo("dentro");
        echo(json_encode($data));
       // echo $status;
    } catch (PDOException $e) {
        echo ("Error en Consulta: " . $e->getMessage());
    }
    }
?>