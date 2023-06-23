<?php

use delete_data as GlobalDelete_data;

  session_start();
  date_default_timezone_set('America/Mexico_City');
  $GLOBALS['user'] = $_SESSION["PKUsuario"];
  $GLOBALS['company'] = $_SESSION['IDEmpresa'];

  class conectar{ //Llamado al archivo de la conexión.
    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
  }
  class get_data{
    function getProductionOrderTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("SELECT 
                        op.id,
                        op.folio_orden,
                        op.create_at,
                        op.fecha_prevista,
                        op.fecha_termino,
                        op.estatus,
                        s.sucursal,
                        concat(e.nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) responsable
                        FROM ordenes_produccion op
                          left JOIN sucursales s ON op.sucursal_id = s.id
                          inner join empleados e on op.responsable_id = e.PKEmpleado
                        WHERE op.empresa_id = :id
                      ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      $stmt->execute();
      
      $arr = $stmt->fetchAll();

      foreach($arr as $r){

        switch ($r['estatus']) {
          case '1':
            //$estatus = "Pendiente";
            $estatus = "<span class='left-dot yellow-dot'>Pendiente</span>";
          break;
          case '2':
            //$estatus = "Aceptada";
            $estatus = "<span class='left-dot green-dot'>Aceptada</span>";
          break;
          case '3':
            //$estatus = "En progreso";
            $estatus = "<span class='left-dot green-dot'>En progreso</span>";
          break;
          case '4':
            //$estatus = "Terminada";
            $estatus = "<span class='left-dot turquoise-dot'>Terminada</span>";
          break;
          case '5':
            //$estatus = "Cancelada";
            $estatus = "<span class='left-dot red-dot'>Cancelada</span>";
          break;
          case '6':
            //$estatus = "En progreso atrasada";
            $estatus = "<span class='left-dot yellow-dot'>En progreso atrasada</span>";
          break;
          default:
          //$estatus = "Pendiente";
            $estatus = "<span class='left-dot yellow-dot'>Pendiente</span>";
          break;
        }

        $fecha_termino = $r['fecha_termino'] !== null && $r['fecha_termino'] !== "" ? date("d-m-Y",strtotime($r['fecha_termino'])) : "Sin fecha de término";

        $folio = "OP" . str_pad($r['folio_orden'],5,0,STR_PAD_LEFT);

        $sucursal = $r['sucursal'] !== "" && $r['sucursal'] !== null ? $r['sucursal'] : "Sin sucursal";
        
        $table .= '{
          "Folio" : "<a href=\"#\" data-id=\"' . $r['id'] . '\" id=\"detalle_ordenProducion\">' . $folio . '</a>",
          "Sucursal" : "' . $sucursal . '",
          "Fecha inicio" : "' . date("d-m-Y",strtotime($r['create_at'])) . '",
          "Fecha estimada" : "' . date("d-m-Y",strtotime($r['fecha_prevista'])) . '",
          "Fecha termino" : "' . $fecha_termino . '",
          "Encargado" : "'.$r['responsable'].'",
          "Estatus" : "' . $estatus . '"
        },';
      }
      
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getDetailsDataProductionOrder($value){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("SELECT 
                          op.id,
                          op.folio_orden,
                          date_format(op.create_at,'%%Y-%%m-%%d') fecha_creacion,
                          date_format(op.fecha_prevista,'%%Y-%%m-%%d') fecha_prevista,
                          date_format(op.fecha_termino,'%%Y-%%m-%%d') fecha_termino,
                          op.estatus,
                          op.sucursal_id,
                          s.sucursal,
                          op.producto_id,
                          pr.Nombre,
                          op.responsable_id id_responsable,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) responsable,
                          op.cantidad_producir,
                          op.grupo_trabajo_id grupo_trabajo,
                          count(sop.id) existencia_seguimiento
                         
                        FROM ordenes_produccion op
                          LEFT JOIN sucursales s ON op.sucursal_id = s.id
                          INNER JOIN productos pr on op.producto_id = pr.PKProducto
                          INNER JOIN empleados e on op.responsable_id = e.PKEmpleado
                          /*LEFT JOIN grupo_trabajo_produccion gt on op.grupo_trabajo_id = gt.id*/
                          LEFT JOIN seguimiento_ordenes_produccion sop on op.id = sop.orden_produccion_id
                        WHERE op.id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $aux = $stmt->fetchAll();

      $arr = [
        "id" => $aux[0]['id'],
        "folio" => $aux[0]['folio_orden'],
        "fecha_creacion" => $aux[0]['fecha_creacion'],
        "fecha_prevista" => $aux[0]['fecha_prevista'],
        "fecha_termino" => $aux[0]['fecha_termino'],
        "sucursal_id" => $aux[0]['sucursal_id'],
        "sucursal" => $aux[0]['sucursal'],
        "producto" => $aux[0]['Nombre'],
        "producto_id" => $aux[0]['producto_id'],
        "cantidad" => $aux[0]['cantidad_producir'],
        "id_responsable" => $aux[0]['id_responsable'],
        "responsable" => $aux[0]['responsable'],
        "grupo_trabajo" => $aux[0]['grupo_trabajo'],
        "estatus" => $aux[0]['estatus'],
        "existencia_seguimiento" => $aux[0]['existencia_seguimiento']
      ];
      
      return $arr;
    }

    function getExistenceInTracing($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from seguimiento_ordenes_produccion where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      return $stmt->rowCount();

    }

    function getDetailProducts($value){
      $con = new conectar();
      $db = $con->getDb();
      $aux = [];

      $query = sprintf("select op.detalle_orden_produccion,estatus from ordenes_produccion op where op.id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(':id',$value);
      $stmt->execute();
      $json = "";
      $lotes = "";
      $txtLote = "";

      $arr = $stmt->fetchAll();
      
      $data = json_decode($arr[0]['detalle_orden_produccion']);
      $estatus = $arr[0]['estatus'];
      $aux = [];
  
      foreach($data as $r){
       
        array_push($aux,$r->id);
        
        $lotes .= '{
                    "id" : "' . $r->id .'",
                    "lote" : "' . $r->lote . '",
                    "cantidad" : "' . $r->cantidad . '",
                    "a_consumir":"' . $r->a_consumir . '"
                  },';

      }
      $lotes = substr($lotes,0,strlen($lotes)-1); 

      $aux = array_unique($aux);
      $aux = array_values($aux);

      for($i = 0 ; $i < count($aux) ; $i++){
        //echo $aux[$i] . "<br>";
        $query = sprintf("select 
                            pr.PKProducto id,
                            pr.ClaveInterna clave,
                            pr.Nombre nombre,
                            concat(csu.Clave,' - ', csu.Descripcion) unidad_medida,
                            sum(distinct (epp.existencia - epp.apartado_produccion)) existencia
                          from productos pr
                            inner join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                            inner join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                            inner join compuesto_tipo_producto ctp on pr.PKProducto = ctp.FKProductoCompuesto
                            left join existencia_por_productos epp on pr.PKProducto = epp.producto_id
                          where pr.PKProducto = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id',$aux[$i]);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $json .= '{
          "id" : "' . $arr[0]->id . '",
          "clave" : "' . $arr[0]->clave . '",
          "nombre" : "' . $arr[0]->nombre . '",
          "unidad_medida" : "' . $arr[0]->unidad_medida . '",
          "existencia" : "' . $arr[0]->existencia . '",
          "estatus" : "' . $estatus . '",
          ';
        
        $index = array_search($aux[$i],array_column($data, 'id'));
        foreach($data as $r){
          if($r->id === $aux[$i]){
            $txtLote .= 'Lote: ' . $data[$index]->lote . ' - Cantidad: ' . $data[$index]->cantidad . '<br>';
          }
        }
        
        $json .= '"a_consumir" : "' . $data[$index]->a_consumir . '",
                "lote":"' . $txtLote . '"},';
        
        $txtLote = "";
      }
      $json = substr($json,0,strlen($json)-1); 
      $json = '"data":[' . $json . ']';

      $json .= ',"lotes":[' . $lotes . ']';
      $json = '[{' . $json . '}]';

      return $json;
      /*
      $query = sprintf("select 
                          pr.PKProducto id,
                          pr.ClaveInterna clave,
                          pr.Nombre nombre,
                          concat(csu.Clave,' - ', csu.Descripcion) unidad_medida,
                          (op.cantidad_producir * ctp.Cantidad) a_consumir,
                          sum(distinct (epp.existencia - epp.apartado_produccion)) existencia,
                          group_concat(distinct 'Lote: ',dop.lote,'- Cantidad: ',dop.cantidad separator '<br>') lote,
                          group_concat(distinct ctp.FKProductoCompuesto,':',dop.lote,':',dop.cantidad separator ';') lote_aux,
                          op.estatus
                        from productos pr
                          inner join ordenes_produccion op on dop.orden_produccion_id = op.id
                          inner join compuesto_tipo_producto ctp on dop.material_id = ctp.FKProductoCompuesto
                          inner join claves_sat_unidades csu on ctp.unidad_medida_id = csu.PKClaveSATUnidad
                          left join existencia_por_productos epp on ctp.FKProductoCompuesto = epp.producto_id
                        where dop.orden_produccion_id = :id
                        group by dop.material_id
                        order by dop.material_id asc");
      $stmt = $db->prepare($query);
      $stmt->bindValue(':id',$value);
      $stmt->execute();

      return json_encode($stmt->fetchAll(PDO::FETCH_OBJ));*/

    }

    function getDetailProductsDatatables($value){
      $getData = new get_data();
      $table = "";

      $prod = json_decode($getData->getDetailProducts($value));

      foreach($prod as $r){
        
        $table .= '{' .
                  '"clave":"' . $r->clave . '",' .
                  '"descripcion":"' . $r->nombre . '",' .
                  '"unidad_medida":"' . $r->unidad_medida . '",' .
                  '"a_consumir":"' . $r->a_consumir . '",' .
                  '"stock":"' . $r->existencia . '",' .
                  '"lote":"' . $r->lote . '",' .
                  '"funciones":""' .
                '},';
      }

      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';

    }

    function getSucursales(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select id, sucursal texto from sucursales where empresa_id = :id and activar_inventario = 1");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getProductos($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select distinct
                          pr.PKProducto id, 
                          concat(pr.ClaveInterna, ' - ',pr.Nombre) texto 
                        from compuesto_tipo_producto cpr
                          inner join productos pr on cpr.FKPRoducto = pr.PKPRoducto
                        where pr.empresa_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getResponsable(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select distinct
                          PKEmpleado id,
                          concat(Nombres,' ',PrimerApellido,' ',SegundoApellido) texto
                        from empleados e
                          inner join relacion_tipo_empleado rte on e.PKEmpleado = rte.empleado_id
                        where empresa_id = :id and tipo_empleado_id = 9
                      ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(':id',$GLOBALS['company']);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getGrupoTrabajo(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select distinct
                          id,
                          nombre texto
                        from grupo_trabajo_produccion
                        where empresa_id = :id
                      ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getProductCompounds($value,$rowCount){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      $stock = 0;

      $query = sprintf("select
                          pr.PKProducto id,
                          pr.ClaveInterna clave,
                          pr.Nombre descripcion,
                          concat(csu.Clave,' - ',csu.Descripcion) unidad_medida
                        from productos pr
                          left join claves_sat_unidades csu on pr.unidad_medida_id = csu.PKClaveSATUnidad
                          left join existencia_por_productos epp on pr.PKProducto = epp.producto_id
                        where pr.empresa_id = :id and pr.PKProducto = :prod");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      $stmt->bindValue(":prod",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll();
      
      foreach($arr as $r){

        /*
        $existencia = "<select class='stock-select' multiple id='comboLote".$rowCount."' disabled>";

        $query = sprintf("select
                          epp.numero_lote lote,
                          epp.existencia stock,
                          sum(epp.existencia) stock_total
                        from productos pr
                          inner join existencia_por_productos epp on pr.PKProducto = epp.producto_id
                        where pr.PKProducto = :prod");
        $stmt = $db->prepare($query);
        //$stmt->bindValue(":id",$GLOBALS['company']);
        $stmt->bindValue(":prod",$r['id']);
        $stmt->execute();

        $arr1 = $stmt->fetchAll();
        if(count($arr1) > 0){
          
          foreach($arr1 as $r1){
            $stock += $r1['stock'];
            if($stock > 0){
              $existencia .= "<option value=' ". $r['id'] . "," . $r1['lote'] ."," . $r1['stock'] . " '>" . $r1['lote'] ." - " . $r1['stock'] . "</option>";
            } else {
              $existencia .= "<option value=' ". $r['id'] . "," . $r1['lote'] ."," . $r1['stock'] . " '>Sin existencias</option>";
            }
          }
          
        } else {
          $stock = 0;
          $existencia .= "<option value=''>No hay registros</option>";
        }
        $existencia .= "</select>";
        */
        $cantidad = "<input type='number' name='txtCantidad" . $r['id'] . "' id='txtCantidad" . $r['id'] . "' min='1'>";
        $table .= '{
            "clave" : "' . $r['clave'] . '",
            "descripcion" : "' . $r['descripcion'] . '",
            "unidad_medida" : "' . $r['unidad_medida'] . '",
            "a_consumir" : "' . $cantidad . '",
            "stock" : "' . $stock . '",
            "lote" : ""
        },';

        $rowCount++;
      }

      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';

    }

    function getProductCompoundsTable($value,$id){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("select distinct
                          cpr.FKProductoCompuesto id,
                          pr.ClaveInterna clave,
                          pr.Nombre descripcion,
                          concat(csu.Clave,' - ',csu.Descripcion) unidad_medida,
                          cpr.Cantidad cantidad,
                          sum(distinct (epp.existencia - epp.apartado_produccion)) stock
                        from compuesto_tipo_producto cpr
                          inner join productos pr on cpr.FKProductoCompuesto = pr.PKProducto
                          left join claves_sat_unidades csu on cpr.unidad_medida_id = csu.PKClaveSATUnidad
                          left join existencia_por_productos epp on cpr.FKProductoCompuesto = epp.producto_id
                        where pr.empresa_id = :id and cpr.FKProducto = :prod
                        group by cpr.FKProductoCompuesto
                        order by cpr.FKProductoCompuesto asc");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      $stmt->bindValue(":prod",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll();
      $rowCount = 0;
      foreach($arr as $r){
        $add_lote = "<a href='#' class='btn-table-custom--blue add_lot' data-id='" .$r['id'] ."'><i class='fas fa-plus-square'></i> Añadir lote</a>";
        
        $table .= '{
            "id" : "' . $r['id'] . '",
            "clave" : "' . $r['clave'] . '",
            "descripcion" : "' . $r['descripcion'] . '",
            "unidad_medida" : "' . $r['unidad_medida'] . '",
            "a_consumir" : "' . $r['cantidad'] . '",
            "stock" : "' . $r['stock'] . '",
            "lote" : "Sin lote",
            "funciones" : "' . $add_lote . '"
        },';

        $rowCount++;
      }

      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';

      
    }

    function getGrupoTrabajoOrdenProduccion($value){
      $con = new conectar();
      $db = $con->getDb();
      $query_option = "";

      $query = sprintf("select grupo_trabajo_id from ordenes_produccion where id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll();

      if(count($arr) > 0){
        $json_arr = json_decode($arr[0]["grupo_trabajo_id"]);
        foreach($json_arr as $r){
          $query_option .= "id = " . $r->id . " or ";
        }
        $query_option = substr($query_option,0,strlen($query_option)-4);
        $query_option = "where (" . $query_option . ")";
      } else {
        $query_option = "";
      }
      
      $query = sprintf("select id, nombre texto from grupo_trabajo_produccion " . $query_option);
      $stmt = $db->prepare($query);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    function getLotes($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select distinct lote id, lote texto from seguimiento_ordenes_produccion where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

     return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getManufacturingHistory($value){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("select 
                          sop.fecha_captura, 
                          gtp.nombre grupo_trabajo,
                          sop.fecha_termino,
                          sop.cantidad_terminada,
                          sop.lote,
                          u.nombre
                        from seguimiento_ordenes_produccion sop
                          left join grupo_trabajo_produccion gtp on sop.grupo_trabajo_produccion_id = gtp.id
                          inner join usuarios u on sop.usuario_id = u.id
                        where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

     $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach($arr as $r){
        $table .= '{
                    "fecha_captura":"' . date("d-m-Y",strtotime($r->fecha_captura)) . '",
                    "grupo_trabajo":"' . $r->grupo_trabajo . '",
                    "fecha_termino":"' . date("d-m-Y",strtotime($r->fecha_termino)) . '",
                    "cantidad_termina":"' . $r->cantidad_terminada . '",
                    "lote":"' . $r->lote . '",
                    "usuario_registro":"' . $r->nombre . '"
                  },';
      }
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getDataProductionOrderTracking($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select sum(sop.cantidad_terminada) cantidad_terminada, (op.cantidad_producir - sum(sop.cantidad_terminada)) cantidad_faltante from seguimiento_ordenes_produccion sop
                        inner join ordenes_produccion op on sop.orden_produccion_id = op.id
                        where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getCompoundsData($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select concat(pr.ClaveInterna,' - ',pr.Nombre) producto from productos as pr where PKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getLots($value){
      $con = new conectar();
      $db = $con->getDb();

      $data = json_decode($value);

      $query = sprintf("select
                          concat(cpr.FKProductoCompuesto,',',epp.numero_lote,',',(epp.existencia - epp.apartado_produccion)) id,
                          concat('Lote: ',epp.numero_lote,' - Cantidad: ',(epp.existencia - epp.apartado_produccion),' en existencia') texto  
                        from compuesto_tipo_producto cpr
                          inner join productos pr on cpr.FKProductoCompuesto = pr.PKProducto
                          inner join existencia_por_productos epp on cpr.FKProductoCompuesto = epp.producto_id
                        where cpr.FKProductoCompuesto = :prod and epp.sucursal_id = :suc and (epp.existencia - epp.apartado_produccion) > 0");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":prod",$data->material_id);
        $stmt->bindValue(":suc",$data->sucursal_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getSocksGeneral($value){
      $con = new conectar();
      $db = $con->getDb();
      $ban = 0;

      $query = sprintf("select detalle_orden_produccion from ordenes_produccion where id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
      $arr = json_decode($arr[0]->detalle_orden_produccion);
      $aux = [];
      $txtQuery = "";
      
      foreach($arr as $r){
        array_push($aux,$r->id);
      }
      
      $aux = array_unique($aux);

      foreach($aux as $r){
        if(count($aux) > 0){
          $txtQuery .= "epp.producto_id = ". $r . " or ";
        } else {
          $txtQuery .= $r;
        }
        
      }
      if(count($aux) > 0){
        $txtQuery = substr($txtQuery,0,strlen($txtQuery)-4);
      }

      $query = sprintf("select sum(epp.existencia) existencia from existencia_por_productos as epp
                                where " . $txtQuery . "");
      $stmt = $db->prepare($query);
     
      $stmt->execute();

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach($arr as $r){
        if($r->existencia > 0){
          $ban = 1;
        } else {
          $ban = 0;
          break;
        }
      }
      return $ban;
    }

    function getStocksPerLot($value){
      $con = new conectar();
      $db = $con->getDb();
      $ban = 0;

      $query = sprintf("select detalle_orden_produccion from ordenes_produccion where id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
      $arr = json_decode($arr[0]->detalle_orden_produccion);
      
      foreach($arr as $r){
        $query = sprintf("select epp.existencia existencia from existencia_por_productos as epp
                          where producto_id = :id and numero_lote = :lote");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$r->id);
        $stmt->bindValue(":lote",$r->lote);

        $stmt->execute();

        $array =  $stmt->fetchAll(PDO::FETCH_OBJ);
      }

      foreach($array as $r){
        if($r->existencia > 0){
          $ban = 1;
        } else {
          $ban = 0;
          break;
        }
      }
      return $ban;
    }

    function getCorrectQuantitryPerLot($value){
      $con = new conectar();
      $db = $con->getDb();
      $ban = 0;
      
      $a_consumir = 0;
      $sum_val = 0;

      $query = sprintf("select detalle_orden_produccion, sum(ctp.Cantidad * op.cantidad_producir) cantidad from ordenes_produccion op
                          inner join compuesto_tipo_producto ctp on op.producto_id = ctp.FKProducto
                        where op.id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
      $a_consumir = (int)$arr[0]->cantidad;
      $data = json_decode($arr[0]->detalle_orden_produccion);
      //$a_consumir = $arr[0]->cantidad;
      
      foreach($data as $r){
        $sum_val += $r->cantidad;
      }
      
      if($sum_val !== $a_consumir){
        $ban = 0;
      } else {
        $ban = 1;
      }

      return $ban;

    }
  }

  class update_data{
    function updateStatusProductionOrder($value,$id){
      $con = new conectar();
      $db = $con->getDb();
      
      try{
        $db->beginTransaction();
        //$ban1 = false;

        $query = sprintf("update ordenes_produccion set estatus = :status where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":status",$value);
        $stmt->bindValue(":id",$id);
        $ban = $stmt->execute();

        if((int)$value === 2){
          if($ban){
            $query = sprintf("select epp.id, dop.cantidad from existencia_por_productos as epp
                                inner join detalle_ordenes_produccion dop on epp.producto_id = dop.material_id
                                inner join ordenes_produccion op on dop.orden_produccion_id = op.id
                                inner join sucursales s on epp.sucursal_id = s.id
                                where op.id = :id and epp.numero_lote = dop.lote");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id",$id);
            $stmt->execute();

            $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach($arr as $r){
              $query = sprintf("update existencia_por_productos epp set  
                                  epp.apartado_produccion = (epp.apartado_produccion + :cantidad)
                                where epp.id = :id");
              $stmt = $db->prepare($query);
              $stmt->bindValue(":id",$r->id);
              $stmt->bindValue(":cantidad",$r->cantidad);
              $ban1 = $stmt->execute();
            }

            
          }
        }
          $db->commit();
          return $ban;
      }catch(PDOException $e){
        $db->rollBack();
        return $e;
      }
    }

    function updateExpectedDate($value,$id){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("update ordenes_produccion set fecha_prevista = :exptect_date where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":exptect_date",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateQuantity($value,$id){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select cantidad from detalle_ordenes_produccion where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindVlue(":id");

      
      $query = sprintf("update ordenes_produccion set cantidad_producir = :quantity where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":quantity",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateResponsable($value,$id){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("update ordenes_produccion set responsable_id = :responsable where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":responsable",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateWorkgroup($value,$id){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("update ordenes_produccion set grupo_trabajo_id = :workgroup where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":workgroup",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateLots($value){
      $con = new conectar();
      $db = $con->getDb();
      $delete_data = new delete_data();

      $data = json_decode($value);

      $lotes = json_encode($data->detalle_lotes);

      //return $data->orden_produccion_id;

      $query = sprintf("update ordenes_produccion set detalle_orden_produccion = :detalle where id =:id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":detalle",$lotes);
      $stmt->bindValue(":id",$data->orden_produccion_id);
      return $stmt->execute();
      
      /*
      $query = sprintf("insert into detalle_ordenes_produccion (
                          material_id,
                          cantidad,
                          lote,
                          orden_produccion_id
                        ) values (
                            :material_id,
                            :cantidad,
                            :lote,
                            :orden_produccion_id
                        )
                        ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":material_id",$data->detalle_lotes[$i]->id);
      $stmt->bindValue(":cantidad",$data->detalle_lotes[$i]->cantidad);
      $stmt->bindValue(":lote",$data->detalle_lotes[$i]->lote);
      $stmt->bindValue(":orden_produccion_id",$data->orden_produccion_id);
      return $stmt->execute();
      }*/
    }
  }

  class save_data{

    function save_productCompounds($value){
      $con = new conectar();
      $db = $con->getDb();

      $data = json_decode($value);

      $query = sprintf("insert into materiales_orden_produccion_temp 
                          (cantidad, lote, producto_id, usuario_id, empresa_id) 
                        values 
                          (:cantidad, :lote, :producto_id, :usuario_id, :empresa_id)");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":cantidad",$data['cantidad']);
      $stmt->bindValue(":lote",$data['lote']);
      $stmt->bindValue(":producto_id",$data['producto_id']);
      $stmt->bindValue(":usuario_id",$data['usuario_id']);
      $stmt->bindValue(":empresa_id",$data['empresa_id']);
      return $stmt->execute();

    }

    function save_productsCompounds($value){
      $con = new conectar();
      $db = $con->getDb();

      $data = json_decode($value);

      for ($i=0; $i < count($data); $i++) { 
      
        $query = sprintf("insert into materiales_orden_produccion_temp 
                            (cantidad, lote, producto_id, costo, usuario_id, empresa_id) 
                          values 
                            (:cantidad, :lote, :producto_id, :usuario_id, :empresa_id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":cantidad",$data[$i]['cantidad']);
        $stmt->bindValue(":lote",$data[$i]['lote']);
        $stmt->bindValue(":producto_id",$data[$i]['producto_id']);
        $stmt->bindValue(":usuario_id",$data[$i]['usuario_id']);
        $stmt->bindValue(":empresa_id",$data[$i]['empresa_id']);
        $stmt->execute();
      }
    }

    function save_produccionOrder($value){
      $con = new conectar();
      $db = $con->getDb();

      $data = json_decode($value);
      
      try{  
        $db->beginTransaction();

        $query = sprintf("select folio_orden from ordenes_produccion where empresa_id = :id order by id desc limit 1");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$GLOBALS['company']);
        $stmt->execute();
        $aux = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $folio_orden = count($aux) > 0 ? ($aux[0]->folio_orden + 1) : 1;

       

        $grupo_trabajo = $data->grupo_trabajo !== "" && $data->grupo_trabajo !== null ? json_encode($data->grupo_trabajo) : null;
        
        $query = sprintf("insert into ordenes_produccion 
                            (
                              folio_orden, 
                              notas, 
                              create_at, 
                              user_created_id, 
                              fecha_prevista, 
                              empresa_id, 
                              grupo_trabajo_id, 
                              responsable_id, 
                              estatus,
                              sucursal_id, 
                              producto_id, 
                              cantidad_producir,
                              detalle_orden_produccion
                            )
                          values
                            (
                              :folio_orden, 
                              :notas, 
                              :create_at, 
                              :user_created_id, 
                              :fecha_prevista,
                              :empresa_id, 
                              :grupo_trabajo_id, 
                              :responsable_id, 
                              :estatus,
                              :sucursal_id, 
                              :producto_id, 
                              :cantidad_producir,
                              :detalle_orden_produccion
                            )");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":folio_orden", $folio_orden);
        $stmt->bindValue(":notas",$data->notas);
        $stmt->bindValue(":create_at",date("Y-m-d H:i:s"));
        $stmt->bindValue(":user_created_id",$GLOBALS['user']);
        $stmt->bindValue(":fecha_prevista",$data->fecha_prevista);
        $stmt->bindValue(":empresa_id",$GLOBALS['company']);
        $stmt->bindValue(":grupo_trabajo_id",$grupo_trabajo);
        $stmt->bindValue(":responsable_id",$data->responsable);
        $stmt->bindValue(":estatus",1);
        $stmt->bindValue(":sucursal_id",$data->sucursal);
        $stmt->bindValue(":producto_id",$data->producto_id);
        $stmt->bindValue(":cantidad_producir",$data->cantidad);
        $stmt->bindValue(":detalle_orden_produccion",json_encode($data->data_materiales));
        $res = $stmt->execute();
        
        /*$idLastInsert = $db->lastInsertId();

        foreach($data->data_materiales as $r){
          $query = sprintf("insert into detalle_ordenes_produccion 
                            (
                              material_id,
                              cantidad,
                              lote,
                              orden_produccion_id
                            )
                            values
                            (
                              :material_id,
                              :cantidad,
                              :lote,
                              :orden_produccion_id
                            )");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":material_id",$r->id);
          $stmt->bindValue(":cantidad",$r->cantidad);
          $stmt->bindValue(":lote",$r->lote);
          $stmt->bindValue(":orden_produccion_id",$idLastInsert);
          $res = $stmt->execute();
        }*/
        $db->commit();
        return $res;
      }catch(PDOException $e){
        $db->rollBack();
        return $e;
      }
      
    }

    function saveWorkgroup($value){
      $con = new conectar();
      $db = $con->getDb();

      try{
        $query = sprintf("insert into grupo_trabajo_produccion (nombre,empresa_id) values (:name,:company_id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":name",$value);
        $stmt->bindValue(":company_id",$GLOBALS['company']);
        $stmt->execute();
       
        return $db->lastInsertId();
      }catch(PDOException $e){
        return $e->getMessage();
      }
    }

    function saveProductionOrderTracking($value){
      $con = new conectar();
      $db = $con->getDb();

      $json = json_decode($value);
      
      try{
        $db->beginTransaction();
        $query = sprintf("insert into seguimiento_ordenes_produccion (
                            fecha_captura,
                            fecha_termino,
                            cantidad_terminada,
                            lote,
                            orden_produccion_id,
                            grupo_trabajo_produccion_id,
                            usuario_id,
                            producto_id
                          ) values (
                            :fecha_captura,
                            :fecha_termino,
                            :cantidad_terminada,
                            :lote,
                            :orden_produccion_id,
                            :grupo_trabajo_produccion_id,
                            :usuario_id,
                            :producto_id
                          )");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":fecha_captura",date("Y-m-d"));
        $stmt->bindValue(":fecha_termino",$json->fecha_fabricacion);
        $stmt->bindValue(":cantidad_terminada",$json->cantidad_terminada);
        $stmt->bindValue(":lote",$json->lote);
        $stmt->bindValue(":orden_produccion_id",$json->orden_produccion_id);
        $stmt->bindValue(":grupo_trabajo_produccion_id",$json->grupo_trabajo);
        $stmt->bindValue(":usuario_id",$GLOBALS['user']);
        $stmt->bindValue(":producto_id",$json->producto_id);
        $ban = $stmt->execute();

        if($ban){

          $query = sprintf("select dop.material_id,dop.lote from seguimiento_ordenes_produccion sop
                              inner join detalle_ordenes_produccion dop on sop.orden_produccion_id = dop.orden_produccion_id
                            where sop.orden_produccion_id = :orden_produccion_id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":orden_produccion_id",$json->orden_produccion_id);
          $stmt->execute();

          $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
          
          foreach($arr as $r){
            $query = sprintf("update existencia_por_productos epp 
                                inner join detalle_ordenes_produccion dop on epp.producto_id = dop.material_id
                                inner join compuesto_tipo_producto ctp on dop.material_id = ctp.FKProductoCompuesto
                              set 
                                epp.existencia = (epp.existencia - (ctp.Cantidad * :cantidad_terminada1)),
                                epp.apartado_produccion = (epp.apartado_produccion - (ctp.Cantidad * :cantidad_terminada2))
                              where 
                                dop.material_id = :material_id and 
                                dop.orden_produccion_id = :orden_produccion_id and 
                                epp.numero_lote = :lote and 
                                epp.sucursal_id = :sucursal_id");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":cantidad_terminada1",$json->cantidad_terminada);
            $stmt->bindValue(":cantidad_terminada2",$json->cantidad_terminada);
            $stmt->bindValue(":material_id",$r->material_id);
            $stmt->bindValue(":orden_produccion_id",$json->orden_produccion_id);
            $stmt->bindValue(":lote",$r->lote);
            $stmt->bindValue(":sucursal_id",$json->sucursal_id);
            $stmt->execute();
          }
          
          $query = sprintf("select * from existencia_por_productos epp 
                            inner join seguimiento_ordenes_produccion sop on epp.producto_id = sop.producto_id
                            where sop.producto_id = :id and epp.numero_lote = :lote");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id",$json->producto_id);
          $stmt->bindValue(":lote",$json->lote);
          $stmt->execute();

          $rowCount = $stmt->rowCount();
          
          if($rowCount > 0){
            $query = sprintf("update existencia_por_productos set existencia = (existencia + :exs) where producto_id = :producto_id and sucursal_id = :sucursal_id and numero_lote = :lote");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":exs",$json->cantidad_terminada);
            $stmt->bindValue(":producto_id",$json->producto_id);
            $stmt->bindValue(":sucursal_id",$json->sucursal_id);
            $stmt->bindValue(":lote",$json->lote);
            $ban1 = $stmt->execute();
          } else {
            $query = sprintf("insert into existencia_por_productos (
                                existencia,
                                sucursal_id,
                                producto_id,
                                numero_lote) 
                              values (
                                :exs,
                                :sucursal_id,
                                :producto_id,
                                :lote
                                )");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":exs",$json->cantidad_terminada);
            $stmt->bindValue(":sucursal_id",$json->sucursal_id);
            $stmt->bindValue(":producto_id",$json->producto_id);
            $stmt->bindValue(":lote",$json->lote);
            $ban1 = $stmt->execute();
          }
        }
        $db->commit();
        return $ban1;
      }catch(PDOException $e){
        return $e->getMessage();
      }
    }
  }

  class delete_data{
    function delete_lots($value,$id,$lote){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("delete from detalle_ordenes_produccion where orden_produccion_id =:id and material_id = :material_id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->bindValue(":material_id",$id);
      $stmt->execute();
    }
  }

?>