<?php

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

    function getDataEnterprise()
    {
        $con = new conectar();
        $db = $con->getDb();
        $data = [];
    
        $query = sprintf("select * from empresas where PKEmpresa = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
        $stmt->execute();
    
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $target_dir = isset($_ENV['RUTA_ARCHIVOS_READ']) ? $_ENV['RUTA_ARCHIVOS_READ'] . $arr[0]->PKEmpresa . "/fiscales/" : "/home/timlid/public_html/app-tim/file_server/" . $arr[0]->PKEmpresa . "/fiscales/";
        $targe_fileLogo = $target_dir . $arr[0]->logo;

        $con = null;
        $db = null;
        $stmt = null;

        array_push($data,array(
            "logo"=>$targe_fileLogo,
            "termino_vencimiento_sello_cfdi"=>$arr[0]->termino_vencimiento_sello_cfdi,
            "nombre_comercial"=>$arr[0]->nombre_comercial,
            "RazonSocial"=>$arr[0]->RazonSocial,
            "RFC"=>$arr[0]->RFC,
            "regimen_fiscal_id"=>$arr[0]->regimen_fiscal_id,
            "telefono"=>$arr[0]->telefono,
            "calle"=>$arr[0]->calle,
            "numero_exterior"=>$arr[0]->numero_exterior,
            "numero_interior"=>$arr[0]->numero_interior,
            "codigo_postal"=>$arr[0]->codigo_postal,
            "colonia"=>$arr[0]->colonia,
            "ciudad"=>$arr[0]->ciudad,
            "estado_id"=>$arr[0]->estado_id,
            "certificado_archivo"=>$arr[0]->certificado_archivo,
            "certificado_archivo"=>$arr[0]->certificado_archivo,
            "llave_certificado_archivo"=>$arr[0]->llave_certificado_archivo,
            "llave_certificado_archivo"=>$arr[0]->llave_certificado_archivo
          ));

        return $data;
    }

    function getProductionOrderTable()
    {
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("SELECT 
                        op.id,
                        op.folio_orden,
                        p.Nombre,
                        p.ClaveInterna,
                        op.create_at,
                        op.fecha_prevista,
                        op.fecha_termino,
                        op.estatus,
                        s.sucursal,
                        concat(e.nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) responsable
                        FROM ordenes_produccion op
                          left JOIN sucursales s ON op.sucursal_id = s.id
                          inner join empleados e on op.responsable_id = e.PKEmpleado
                          inner join productos p on op.producto_id = p.PKProducto
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
            $estatus = "<span class='left-dot green-dot'>En proceso</span>";
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
            $estatus = "<span class='left-dot yellow-dot'>En proceso atrasada</span>";
          break;
          case '7':
            $estatus = "<span class='left-dot red-dot'>Cerrada parcial</span>";
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
          "Producto" : "' . $r['ClaveInterna'] . ' - ' . $r['Nombre']  . '",
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

    function getDetailsDataProductionOrder($value)
    {
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
                          pr.ClaveInterna clave,
                          op.responsable_id id_responsable,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) responsable,
                          op.cantidad_producir,
                          op.grupo_trabajo_id grupo_trabajo,
                          count(sop.id) existencia_seguimiento,
                          op.notas
                         
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
        "clave" => $aux[0]['clave'],
        "producto" => $aux[0]['Nombre'],
        "producto_id" => $aux[0]['producto_id'],
        "cantidad" => $aux[0]['cantidad_producir'],
        "id_responsable" => $aux[0]['id_responsable'],
        "responsable" => $aux[0]['responsable'],
        "grupo_trabajo" => $aux[0]['grupo_trabajo'],
        "estatus" => $aux[0]['estatus'],
        "existencia_seguimiento" => $aux[0]['existencia_seguimiento'],
        "notas" => $aux[0]['notas']        
      ];
      
      return $arr;
    }

    function getWorkGroupNames($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $data = json_decode($value);
        $names = "";

        for ($i=0; $i < count($data); $i++) { 

            $query = sprintf("select nombre from grupo_trabajo_produccion where id = :id");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id",$data[$i]->id);
            $stmt->execute();
            $arr = $stmt->fetchAll();

            $names .= $arr[0]['nombre'] . ", ";
        }
        $names = substr($names,0,strlen($names)-2); 

        return $names;


    }

    function getExistenceInTracing($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from seguimiento_ordenes_produccion where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      return $stmt->rowCount();

    }

    function getDetailProducts($value)
    {
      $con = new conectar();
      $db = $con->getDb();
      $aux = [];

      $query = sprintf("select op.detalle_orden_produccion,estatus,sucursal_id from ordenes_produccion op where op.id = {$value}");
      $stmt = $db->prepare($query);
      // $stmt->bindValue(':id',$value);
      $stmt->execute();
      $json = "";
      $lotes = "";
      $txtLote = "";
      
      $arr = $stmt->fetchAll();
      //print_r($arr);
      $data = json_decode($arr[0]['detalle_orden_produccion']);
      $estatus = $arr[0]['estatus'];
      $sucursal_id = $arr[0]['sucursal_id'];
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
                            sum(epp.existencia) existencia
                          from productos pr
                            left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                            left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
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
        
        //$index = array_search($aux[$i],array_column($data, 'id'));
        $index = count($data);
        
        for($x = 0; $x < $index; $x++){
          if($data[$x]->id === $aux[$i]){
            //echo "Lote html:" . gettype($data[$index]->lote) . "<br>";
            $txtLote .= $data[$x]->lote !== "null" && $data[$x]->lote !== "" && $data[$x]->lote !== null ? 'Lote: ' . $data[$x]->lote . ' - Cantidad: ' . $data[$x]->cantidad . '<br>' : "Sin lote";
          }
        }
        
        $json .= '"a_consumir" : "' . $data[$i]->a_consumir . '",
                "lote":"' . $txtLote . '"},';
        
        $txtLote = "";
      }
      $json = substr($json,0,strlen($json)-1); 
      $json = '"data":[' . $json . ']';

      $json .= ',"lotes":[' . $lotes . ']';
      $json = '[{' . $json . '}]';

      return $json;

    }

    function getDetailProductsDatatables($value)
    {
      $getData = new get_data();
      $table = "";

      $prod = json_decode($getData->getDetailProducts($value));
      
      foreach($prod[0]->data as $r){
        
        $table .= '{' .
                  '"clave":"' . $r->clave . '",' .
                  '"descripcion":"' . $r->nombre . '",' .
                  '"unidad_medida":"' . $r->unidad_medida . '",' .
                  '"a_consumir":"' . $r->a_consumir . '",' .
                  '"stock":"' . number_format($r->existencia,2,".",",") . '",' .
                  '"lote":"' . $r->lote . '",' .
                  '"funciones":""' .
                '},';
      }

      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';

    }

    function getSucursales()
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select id, sucursal texto from sucursales where empresa_id = :id and activar_inventario = 1");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$GLOBALS['company']);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getProductos($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select distinct
                          pr.PKProducto id, 
                          concat(pr.ClaveInterna, ' - ',pr.Nombre) texto 
                        from compuesto_tipo_producto cpr
                          inner join productos pr on cpr.FKPRoducto = pr.PKPRoducto
                        where pr.empresa_id = {$_SESSION['IDEmpresa']}");
     
      $stmt = $db->prepare($query);
      
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getResponsable()
    {
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

    function getGrupoTrabajo()
    {
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

    function getProductCompounds($value,$rowCount)
    {
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

    function getProductCompoundsTable($value,$id)
    {
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("select distinct
                          cpr.FKProductoCompuesto id,
                          pr.ClaveInterna clave,
                          pr.Nombre descripcion,
                          concat(csu.Clave,' - ',csu.Descripcion) unidad_medida,
                          cpr.Cantidad cantidad,
                          ifnull(sum(epp.existencia),0) stock,
                          cpr.colectivos
                        from compuesto_tipo_producto cpr
                          left join productos pr on cpr.FKProductoCompuesto = pr.PKProducto
                          left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                          left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          left join existencia_por_productos epp on cpr.FKProductoCompuesto = epp.producto_id and epp.sucursal_id=:suc
                        where cpr.FKProducto = :prod
                        group by cpr.FKProductoCompuesto
                        order by cpr.FKProductoCompuesto asc");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":prod",$value);
      $stmt->bindValue(":suc",$id);
      
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
            "funciones" : "' . $add_lote . '",
            "colectivos" : "' . $r['colectivos'] . '"
        },';

        $rowCount++;
      }

      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';

      
    }

    function getGrupoTrabajoOrdenProduccion($value)
    {
      $con = new conectar();
      $db = $con->getDb();
      $query_option = "";
      
      $query = sprintf("select grupo_trabajo_id from ordenes_produccion where id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll();
      // print_r($arr);
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
      //echo $query;
      $stmt = $db->prepare($query);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    function getLotes($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select distinct lote id, lote texto from seguimiento_ordenes_produccion where orden_produccion_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

     return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getManufacturingHistory($value)
    {
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("select 
                            sop.id,
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
        $link = "<a href='#' id='btn_download_pdf1'><i class='far fa-file-pdf' data-id='".$r->id."'></i></a>";
        $table .= '{
                    "fecha_captura":"' . date("d-m-Y",strtotime($r->fecha_captura)) . '",
                    "grupo_trabajo":"' . $r->grupo_trabajo . '",
                    "fecha_termino":"' . date("d-m-Y",strtotime($r->fecha_termino)) . '",
                    "cantidad_termina":"' . $r->cantidad_terminada . '",
                    "lote":"' . $r->lote . '",
                    "usuario_registro":"' . $r->nombre . '",
                    "function":"'.$link.'"
                  },';
      }
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getManufacturingHistoryById($value)
    {
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf("select 
                            sop.id,
                            sop.fecha_captura, 
                            gtp.nombre grupo_trabajo,
                            sop.fecha_termino,
                            sop.cantidad_terminada,
                            sop.lote,
                            u.nombre
                        from seguimiento_ordenes_produccion sop
                            left join grupo_trabajo_produccion gtp on sop.grupo_trabajo_produccion_id = gtp.id
                            inner join usuarios u on sop.usuario_id = u.id
                        where sop.id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

     $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach($arr as $r){
        $link = "<a href='#' id='btn_download_pdf1'><i class='far fa-file-pdf' data-id='".$r->id."'></i></a>";
        $table .= '{
                    "fecha_captura":"' . date("d-m-Y",strtotime($r->fecha_captura)) . '",
                    "grupo_trabajo":"' . $r->grupo_trabajo . '",
                    "fecha_termino":"' . date("d-m-Y",strtotime($r->fecha_termino)) . '",
                    "cantidad_termina":"' . $r->cantidad_terminada . '",
                    "lote":"' . $r->lote . '",
                    "usuario_registro":"' . $r->nombre . '",
                    "function":"'.$link.'"
                  },';
      }
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getDataProductionOrderTracking($value)
    {
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

    function getCompoundsData($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select concat(pr.ClaveInterna,' - ',pr.Nombre) producto from productos as pr where PKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getLots($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $data = json_decode($value);

      $query = sprintf("select distinct
                          concat(cpr.FKProductoCompuesto,',',epp.numero_lote,',',epp.existencia) id,
                          concat('Lote: ',epp.numero_lote,' - Cantidad: ',epp.existencia,' en existencia') texto  
                        from compuesto_tipo_producto cpr
                          inner join productos pr on cpr.FKProductoCompuesto = pr.PKProducto
                          inner join existencia_por_productos epp on cpr.FKProductoCompuesto = epp.producto_id
                        where cpr.FKProductoCompuesto = :prod and epp.existencia > 0 and epp.sucursal_id=:suc");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":prod",$data->material_id);
        $stmt->bindValue(":suc",$data->sucursal_id);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getSocksGeneral($value)
    {
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

    function getStocksPerLot($value, $sucursal)
    {
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
                          where producto_id = :id and numero_lote = :lote and sucursal_id = :suc");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$r->id);
        $stmt->bindValue(":lote",$r->lote);
        $stmt->bindValue(":suc",$sucursal);

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

    function getCorrectQuantitryPerLot($value)
    {
      $con = new conectar();
      $db = $con->getDb();
      $ban = 0;
      
      $a_consumir = 0;
      $sum_val = 0;

      $query = "SELECT detalle_orden_produccion, sum(cantidad) as cantidad from (
        select detalle_orden_produccion, cast(ifnull(sum(ctp.Cantidad * op.cantidad_producir),0)as decimal(12,3)) as cantidad from ordenes_produccion op
                                  inner join compuesto_tipo_producto ctp on op.producto_id = ctp.FKProducto
                                where op.id= :id and ctp.colectivos is null
                                
                                union
                                
        select detalle_orden_produccion, cast(ifnull(sum((op.cantidad_producir / ctp.colectivos) * ctp.Cantidad),0)as decimal(12,3)) as cantidad from ordenes_produccion op inner join compuesto_tipo_producto ctp on op.producto_id = ctp.FKProducto
                                where op.id= :id2 and ctp.colectivos > 0
                                )as cantidadPorLote";
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->bindValue(":id2",$value);
      $stmt->execute();

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
      $a_consumir = (double)$arr[0]->cantidad;
      $data = json_decode($arr[0]->detalle_orden_produccion);
      //$a_consumir = $arr[0]->cantidad;
      
      for($i=0; $i < count($data); $i++){
        //$sum_val += (double)$r->cantidad;
        $sum_val = $sum_val + (double)$data[$i]->cantidad;
      }

      if(number_format($sum_val,3,'.','') == number_format($a_consumir,3,'.','')){
        $ban = 1;
      }
      
      return $ban;

    }
  }

  class update_data{
    function updateStatusProductionOrder($value,$id,$sucursal)
    {
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
            $query = sprintf("select detalle_orden_produccion from ordenes_produccion where id = {$id}");
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

            $json_extract = json_decode($arr[0]->detalle_orden_produccion);

            foreach($json_extract as $r){
              $query = sprintf("select FKTipoProducto from productos where PKProducto={$r->id}");
              $stmt = $db->prepare($query);
              $stmt->execute();
              $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

              if($arr[0]->FKTipoProducto == 9){
                $cantidad=ceil($r->a_consumir);
                $query = sprintf("update existencia_por_productos set  
                                  existencia = (existencia - {$cantidad}),
                                  existencia_empaque=(existencia_empaque - {$r->cantidad}),
                                  apartado_produccion = (apartado_produccion + {$r->cantidad})
                                where 
                                  producto_id = {$r->id} and 
                                  sucursal_id = {$sucursal} and 
                                  numero_lote = '{$r->lote}'");
                $stmt = $db->prepare($query);
                $ban1 = $stmt->execute();
              }else{
                $query = sprintf("update existencia_por_productos set  
                                  existencia = (existencia - {$r->cantidad}),
                                  apartado_produccion = (apartado_produccion + {$r->cantidad})
                                where 
                                  producto_id = {$r->id} and 
                                  sucursal_id = {$sucursal} and 
                                  numero_lote = '{$r->lote}'");
                $stmt = $db->prepare($query);
                $ban1 = $stmt->execute();
              }
            }

            
          }
        } else if((int)$value === 5){
          $query = sprintf("select 
                              op.detalle_orden_produccion,
                              ctp.FKProductoCompuesto compuesto,
                              (op.cantidad_producir * ctp.Cantidad) cantidad_restante,
                              ((op.cantidad_producir / ctp.colectivos) * ctp.Cantidad) cantidad_restante2,
                              op.estatus
                            from 
                              ordenes_produccion op
                            left join 
                              compuesto_tipo_producto ctp on op.producto_id = ctp.FKProducto
                            where 
                            id = {$id}");
          $stmt = $db->prepare($query);
          $stmt->execute();

          $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

          if($arr[0]->estatus !== 1){
            $json_extract = json_decode($arr[0]->detalle_orden_produccion);

            $arr_aux = [];

            foreach($arr as $r){
              foreach($json_extract as $r2){
                if((int)$r->compuesto === (int)$r2->id){
                  if($r->cantidad_restante2 != null){
                    array_push($arr_aux,[
                      "id"=> $r2->id,
                      "cantidad_restante" => $r->cantidad_restante2,
                      "lote" => $r2->lote
                    ]);
                  }else{
                    array_push($arr_aux,[
                      "id"=> $r2->id,
                      "cantidad_restante" => $r->cantidad_restante,
                      "lote" => $r2->lote
                    ]);
                  }
                }
              }
            }

            foreach($arr_aux as $r){
              $query = sprintf("select FKTipoProducto from productos where PKProducto={$r['id']}");
              $stmt = $db->prepare($query);
              $stmt->execute();
              $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

              if($arr[0]->FKTipoProducto == 9){
                $cantidad_restante = ceil($r['cantidad_restante']);
                $query = sprintf("update
                                    existencia_por_productos
                                  set
                                    existencia = existencia + {$cantidad_restante},
                                    apartado_produccion = apartado_produccion - {$r['cantidad_restante']},
                                    existencia_empaque = existencia_empaque + {$r['cantidad_restante']}
                                  where 
                                    producto_id = {$r['id']} and 
                                    sucursal_id = {$sucursal} and 
                                    numero_lote = '{$r['lote']}'");
                $stmt = $db->prepare($query);
                $stmt->execute();
              }else{
                $query = sprintf("update
                                    existencia_por_productos
                                  set
                                    existencia = existencia + {$r['cantidad_restante']},
                                    apartado_produccion = apartado_produccion - {$r['cantidad_restante']}
                                  where 
                                    producto_id = {$r['id']} and 
                                    sucursal_id = {$sucursal} and 
                                    numero_lote = '{$r['lote']}'");
                $stmt = $db->prepare($query);
                $stmt->execute();                
              }
            }
          }
        } else {
          $query = sprintf("select 
                              op.detalle_orden_produccion,
                              ctp.FKProductoCompuesto compuesto,
                              ((op.cantidad_producir - sum(sop.cantidad_terminada)) * ctp.Cantidad) cantidad_restante,
                              (((op.cantidad_producir - sum(sop.cantidad_terminada)) / ctp.colectivos) * ctp.Cantidad) cantidad_restante2
                            from 
                              seguimiento_ordenes_produccion sop
                            left join ordenes_produccion op on sop.orden_produccion_id = op.id
                            left join compuesto_tipo_producto ctp on op.producto_id = ctp.FKProducto
                            where 
                            orden_produccion_id = {$id}
                            group by compuesto");
          $stmt = $db->prepare($query);
          $stmt->execute();

          $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

          $json_extract = json_decode($arr[0]->detalle_orden_produccion);

          $arr_aux = [];

          foreach($arr as $r){
            foreach($json_extract as $r2){
              if((int)$r->compuesto === (int)$r2->id){
                if($r->cantidad_restante2 != null){
                  array_push($arr_aux,[
                    "id"=> $r2->id,
                    "cantidad_restante" => $r->cantidad_restante2,
                    "lote" => $r2->lote
                  ]);
                }else{
                  array_push($arr_aux,[
                    "id"=> $r2->id,
                    "cantidad_restante" => $r->cantidad_restante,
                    "lote" => $r2->lote
                  ]);
                }
              }
            }
          }
          
          foreach($arr_aux as $r){
            $query = sprintf("select FKTipoProducto from productos where PKProducto={$r['id']}");
            $stmt = $db->prepare($query);
            $stmt->execute();
            $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            if($arr[0]->FKTipoProducto == 9){
              $cantidad_restante = ceil($r['cantidad_restante']);
              $query = sprintf("update
                                  existencia_por_productos
                                set
                                  existencia = existencia + {$cantidad_restante},
                                  apartado_produccion = apartado_produccion - {$r['cantidad_restante']},
                                  existencia_empaque = existencia_empaque + {$r['cantidad_restante']}                                  
                                where 
                                  producto_id = {$r['id']} and 
                                  sucursal_id = {$sucursal} and 
                                  numero_lote = '{$r['lote']}'");
              $stmt = $db->prepare($query);
              $stmt->execute();
            }else{
              $query = sprintf("update
                                  existencia_por_productos
                                set
                                  existencia = existencia + {$r['cantidad_restante']},
                                  apartado_produccion = apartado_produccion - {$r['cantidad_restante']}
                                where 
                                  producto_id = {$r['id']} and 
                                  sucursal_id = {$sucursal} and 
                                  numero_lote = '{$r['lote']}'");
              $stmt = $db->prepare($query);
              $stmt->execute();
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

    function updateExpectedDate($value,$id)
    {
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("update ordenes_produccion set fecha_prevista = :exptect_date where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":exptect_date",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateQuantity($value,$id)
    {
      $con = new conectar();
      $db = $con->getDb();
      $lotes = "";
      $query = sprintf("select 
                          ctp.FKProductoCompuesto id,
                          (:cantidad * ctp.Cantidad) cantidad_consumir,
                          ((:cantidad2 / ctp.colectivos) * ctp.Cantidad) cantidad_consumi2
                        from 
                          ordenes_produccion op
                        left join compuesto_tipo_producto ctp on op.producto_id = ctp.FKProducto
                        where 
                          op.id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindParam(":cantidad",$value, PDO::PARAM_INT);
      $stmt->bindParam(":cantidad2",$value, PDO::PARAM_INT);
      $stmt->bindParam(":id",$id, PDO::PARAM_INT);
      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_OBJ);
      //print_r($data);
      foreach($data as $r){
        if($r->cantidad_consumi2 != null){
          $lotes .= '{"id":"' . $r->id .'","lote":null,"cantidad":null,"a_consumir":"' . $r->cantidad_consumi2 . '"},';
        }else{
          $lotes .= '{"id":"' . $r->id .'","lote":null,"cantidad":null,"a_consumir":"' . $r->cantidad_consumir . '"},';
        }

      }
      $lotes = substr($lotes,0,strlen($lotes)-1); 
      $lotes = "[" . $lotes . "]";
      
      $query = sprintf("update 
                          ordenes_produccion 
                        set 
                          cantidad_producir = :quantity,
                          detalle_orden_produccion = :lots
                        where 
                          id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":quantity",$value);
      $stmt->bindValue(":lots",$lotes);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateResponsable($value,$id)
    {
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("update ordenes_produccion set responsable_id = :responsable where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":responsable",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateWorkgroup($value,$id)
    {
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("update ordenes_produccion set grupo_trabajo_id = :workgroup where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":workgroup",$value);
      $stmt->bindValue(":id",$id);
      return $stmt->execute();
    }

    function updateLots($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $data = json_decode($value);
      $json='[';
      //$lotes = json_encode($data->detalle_lotes);
      $counter=1;
      foreach($data->detalle_lotes as $lotes){
        if($counter!=count($data->detalle_lotes)){
          $json .= '{"id":"'.$lotes->id.'","lote":"'.$lotes->lote.'","cantidad":'.number_format($lotes->cantidad,3,'.','').',"a_consumir":"'.$lotes->a_consumir.'"},';
        }else{          
          $json .= '{"id":"'.$lotes->id.'","lote":"'.$lotes->lote.'","cantidad":'.number_format($lotes->cantidad,3,'.','').',"a_consumir":"'.$lotes->a_consumir.'"}';
        }
        $counter++;
      }
      $json .= ']';
      //[{"id":"1141","lote":"HHHHJJJ","cantidad":2557.44,"texto":"Lote: null - Cantidad: null","a_consumir":"2557.440"}]
      print_r($json);
      //return $data->orden_produccion_id;

      $query = sprintf("update ordenes_produccion set detalle_orden_produccion = :detalle where id =:id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":detalle",$json);
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

    function save_productCompounds($value)
    {
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

    function save_productsCompounds($value)
    {
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

    function save_produccionOrder($value)
    {
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

    function saveWorkgroup($value)
    {
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

    function saveProductionOrderTracking($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $json = json_decode($value, false, JSON_NUMERIC_CHECK);
      
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

          $query = sprintf("update ordenes_produccion set estatus = 3 where id = {$json->orden_produccion_id} and estatus = 2");
          $stmt = $db->prepare($query);
          $stmt->execute();

          // $query = sprintf("select dop.material_id,dop.lote from seguimiento_ordenes_produccion sop
          //                     inner join detalle_ordenes_produccion dop on sop.orden_produccion_id = dop.orden_produccion_id
          //                   where sop.orden_produccion_id = :orden_produccion_id");
          $query = sprintf("select detalle_orden_produccion,producto_id from ordenes_produccion where id = {$json->orden_produccion_id}");
          $stmt = $db->prepare($query);
          $stmt->execute();

          $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

          $json_extract = json_decode($arr[0]->detalle_orden_produccion);
          $product_id = $arr[0]->producto_id;

          foreach($json_extract as $r){

            $query0 = sprintf("select cantidad, colectivos from compuesto_tipo_producto where FKProducto = {$product_id} and FKProductoCompuesto = {$r->id}");
            $stmt0 = $db->prepare($query0);
            $stmt0->execute();
            $arr_easd = $stmt0->fetchAll(PDO::FETCH_OBJ);

            $query = sprintf("select FKTipoProducto from productos where PKProducto={$r->id}");
            $stmt = $db->prepare($query);
            $stmt->execute();
            $arr_typeProduct = $stmt->fetchAll(PDO::FETCH_OBJ);
            $cantidad_terminada_json = intval($json->cantidad_terminada);
            $cantidad_consumir = floatval(number_format($arr_easd[0]->cantidad,3,'.',''));
            echo $cantidad_consumir."\n";
            echo $cantidad_terminada_json."\n";
            
            if($arr_typeProduct[0]->FKTipoProducto == 9){
              echo '9'."\n";
              $query = sprintf("update 
                                existencia_por_productos epp 
                              set 
                                epp.apartado_produccion = (epp.apartado_produccion - (({$cantidad_terminada_json} / {$arr_easd[0]->colectivos}) * {$arr_easd[0]->cantidad})) 
                              where 
                                epp.producto_id = {$r->id} and 
                                epp.numero_lote = '{$r->lote}' and 
                                epp.sucursal_id = {$json->sucursal_id}");
            
              $stmt = $db->prepare($query);
              $stmt->execute();
            }else{
              echo 'Otro numero'."\n";
              $query = sprintf("update 
                                existencia_por_productos epp 
                              set 
                                epp.apartado_produccion = (epp.apartado_produccion - (:cantidad_consumir * :cantidad_terminada)) 
                              where 
                                epp.producto_id = {$r->id} and 
                                epp.numero_lote = '{$r->lote}' and 
                                epp.sucursal_id = {$json->sucursal_id}");
            
              $stmt = $db->prepare($query);
              $stmt->bindValue(':cantidad_consumir', $cantidad_consumir);
              $stmt->bindValue(':cantidad_terminada', $cantidad_terminada_json, PDO::PARAM_INT);              
              $stmt->execute();
            }            
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
                                numero_lote,
                                clave_producto) 
                              values (
                                :exs,
                                :sucursal_id,
                                :producto_id,
                                :lote,
                                :clave
                                )");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":exs",$json->cantidad_terminada);
            $stmt->bindValue(":sucursal_id",$json->sucursal_id);
            $stmt->bindValue(":producto_id",$json->producto_id);
            $stmt->bindValue(":lote",$json->lote);
            $stmt->bindValue(":clave",$json->clave_producto);
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
    function delete_lots($value,$id,$lote)
    {
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