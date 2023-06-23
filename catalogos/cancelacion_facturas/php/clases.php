<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
      include "../../../include/db-conn.php";
      return $conn;
    }
  }

  function GetEvn()
  {
      include "../../../include/db-conn.php";
      $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
      return ['server' => $appUrl];
  }

  class get_data{
    function getInvoicesTable(){
      $con = new conectar();
      $db = $con->getDb();
      $envVariables = GetEvn();
      $appUrl = $envVariables['server'];
      $table = "";
      $status = "";
      $query = sprintf("SELECT f.id,f.serie,f.folio, cl.PKCliente, cl.razon_social, f.total_facturado, f.estatus, f.fecha_timbrado,f.fecha_cancelacion,f.estatus_cancelacion_api FROM facturacion f
                        INNER JOIN clientes cl ON f.cliente_id = cl.PKCliente
                        WHERE f.empresa_id = :id and f.estatus = 4");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
      $stmt->execute();
      $array = $stmt->fetchAll();

      if(count($array) > 0){
        foreach ($array as $r) {
          
          if($r['fecha_timbrado'] !== "" && $r['fecha_cancelacion'] !== "0000-00-00 00:00:00" && $r['fecha_cancelacion'] !== null){
            $fecha_timbrado = date("d-m-Y H:i:s",strtotime($r['fecha_cancelacion']));
          } else {
            $fecha_timbrado = "";
          }

          if(($r['serie'] !== "" && $r['serie'] !== null) && ($r['folio'] !== "" && $r['folio'] !== null)){
            $folio = $r['serie'] . " " . $r['folio'];
          } else {
            $folio = "";
          }

          switch($r['estatus']){
            case 1:
              $status = "Timbrada";
            break;
            case 4:
              switch($r['estatus_cancelacion_api']){
                case 1:
                  $status = "Timbrada";
                break;
                case 2:
                  $status = "Cancelacion pendiente";
                break;
                case 3:
                  $status = "Cancelada";
                break;
                case 4:
                  $status = "Cancelacion rechazada";
                break;
                case 5:
                  $status = "Cancelada por exirar el tiempo límite de la solicitud";
                break;
              }
            break;
          }

          //link para detalle del cliente
          $r['razon_social'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$r['PKCliente'].'\">'.$r['razon_social'].'</a>';

          $html = "<a id='detalle_factura' href='#' data-id='". $r['id'] ."'> ".$folio." </a>";
          $table .= '{
            "id" : "' . $r['id'] . '",
            "Folio" : "' . $html . '",
            "Razon social" : "' . $r['razon_social'] . '",
            "Total facturado" : "' . number_format($r['total_facturado'],2) . '",
            "Estatus" : "' . $status . '",
            "Fecha de timbrado" : "' . $fecha_timbrado . '"
          },';
          
        
        }
      }
     
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }
  }
?>