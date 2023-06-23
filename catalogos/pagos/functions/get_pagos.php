<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */
$toDo;
$stmt;
$permisos;
//echo($_GET["toDo"]);
/* $consulta = $_GET["toDo"]; */
$redFlag = false;
$redFlag2 = false;
$toggle;
if(isset($_GET["toDo"])){
  switch($_GET["toDo"]){
    case 0:
      $toDo = 0;
      $redFlag = true;
    break;
    case 1:
      $toDo = 1;
      $redFlag = true;
    break;
    case 3:
      $redFlag = false;
      $stmt = $conn->prepare("SELECT pg.idpagos,pg.identificador_pago,pv.NombreComercial,pg.fecha_registro,pg.comentarios, mcbe.parcialidad, cpp.saldo_insoluto, pg.total,us.nombre
      from pagos as pg
          inner join  movimientos_cuentas_bancarias_empresa as mcbe on mcbe.id_pago = pg.idpagos 
          left join cuentas_por_pagar as cpp on  mcbe.cuenta_pagar_id = cpp.id
          left join proveedores as pv on pv.PKProveedor = mcbe.FKProveedor
          left join usuarios as us on mcbe.FKResponsable = us.id
             where tipo_movimiento = 1 and (pg.empresa_id = $empresa) and (pg.estatus = 1) group by idpagos order by identificador_pago desc;");
    break;
    case 4:
      $redFlag2 = true;
    break;
  }
  if($redFlag){
    
  $stmt = $conn->prepare("SELECT pg.idpagos,pg.identificador_pago,pv.NombreComercial,pg.fecha_registro,pg.comentarios, mcbe.parcialidad, cpp.saldo_insoluto, pg.total,us.nombre
  from pagos as pg
          inner join  movimientos_cuentas_bancarias_empresa as mcbe on mcbe.id_pago = pg.idpagos 
          left join cuentas_por_pagar as cpp on  mcbe.cuenta_pagar_id = cpp.id
          left join proveedores as pv on pv.PKProveedor = mcbe.FKProveedor
          left join usuarios as us on mcbe.FKResponsable = us.id
              where tipo_movimiento = 1 and (pg.empresa_id = $empresa) and (pg.estatus = 1) and (mcbe.parcialidad= $toDo) group by idpagos order by identificador_pago desc;");
  }
  if($redFlag2){
    
    $stmt = $conn->prepare("SELECT pg.idpagos,pg.identificador_pago,pv.NombreComercial,pg.fecha_registro,pg.comentarios, mcbe.parcialidad, cpp.saldo_insoluto, pg.total,us.nombre
    from pagos as pg
            inner join  movimientos_cuentas_bancarias_empresa as mcbe on mcbe.id_pago = pg.idpagos 
            left join cuentas_por_pagar as cpp on  mcbe.cuenta_pagar_id = cpp.id
            left join proveedores as pv on pv.PKProveedor = mcbe.FKProveedor
            left join usuarios as us on mcbe.FKResponsable = us.id
                where tipo_movimiento = 1 and (pg.empresa_id = $empresa) and (pg.estatus = 1) and (mcbe.parcialidad IS NULL) group by idpagos order by identificador_pago desc;");
    }
}

$stmt->execute();

$pkuser = $_SESSION["PKUsuario"];
      $permisos = $conn->prepare("Select funcion_editar, funcion_eliminar, 
      pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
      on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 37");
         $permisos->execute();
         $rowper = $permisos->fetch();

$table="";
while (($row = $stmt->fetch()) !== false) {
  ///Si el comentario es largo, mas de 30: Corta el comentario y pone ...
  $row['comentarios'] = (strlen($row['comentarios'])>50)? (substr($row['comentarios'],0,55).". . ."):$row['comentarios'];
$EliminarB = '';
$editarB = '';
    $row['total'] = '<div style=\"text-align: right;\">$' .number_format($row['total'],2).'</div>';
    $row['fecha_registro'] = date("Y-m-d", strtotime($row['fecha_registro']));
    //Validacion de permisos
    //Si es parcialidad link de editar parcialidad si no link de editar pago
    if(($row['parcialidad'] == 0) && (is_numeric($row['parcialidad']) == true)){
      $tipo = '<span class=\"left-dot green-dot\">Completo</span>';
      //Si tiene permiso de ver pinta el boton
      if($rowper['funcion_editar']==1){
        $editarB = '<a class=\"edit-tabs-371\" href=\"editar.php?id='.$row['idpagos'].'\"><i class=\"fas fa-edit\"></i></a>';
      }
      //Si tiene permiso de Eliminar pinta el boton
      if($rowper['funcion_eliminar']==1){
        $EliminarB = '<a id=\"deletePago\"><i class=\"fas fa-trash-alt pointer\" onclick=\"modalShow('.$row['idpagos'].',1)\"></i></a>'; 
      }
      $html = $editarB.$EliminarB;
      $row['identificador_pago'] = '<a class=\"edit-tabs-371\" href=\"ver.php?id='.$row['idpagos'].'&pagoLibre=0\">'.$row['identificador_pago'].'</a>';
    }else if(($row['parcialidad'] > 0)){
      $tipo = '<span class=\"left-dot orange-dot\">Anticipo</span>';
      if($rowper['funcion_editar']==1){
        $editarB = '<a class=\"edit-tabs-371\" href=\"editar_anticipo.php?id='.$row['idpagos'].'\"><i class=\"fas fa-edit\"></i></a>';
      }
      if($rowper['funcion_eliminar']==1){
        $EliminarB = '<a id=\"deletePago\"><i class=\"fas fa-trash-alt pointer\" onclick=\"modalShow('.$row['idpagos'].',1)\"></a>'; 
      }
      $html = $editarB.$EliminarB;
      $row['identificador_pago'] = '<a class=\"edit-tabs-371\" href=\"ver.php?id='.$row['idpagos'].'&pagoLibre=0\">'.$row['identificador_pago'].'</a>';
    }else{
      $tipo = '<span class=\"left-dot yellow-dot\">Sin relación</span>';
      if($rowper['funcion_editar']==1){
        $editarB = '<a class=\"edit-tabs-371\" href=\"editar_anticipo.php?id='.$row['idpagos'].'\"><i class=\"fas fa-edit\"></i></a>';
      }
      if($rowper['funcion_eliminar']==1){
        $EliminarB = '<a id=\"deletePago\"><i class=\"fas fa-trash-alt pointer\" onclick=\"modalShow('.$row['idpagos'].',1)\"></a>'; 
      }
      $html = $editarB.$EliminarB;
      $row['identificador_pago'] = '<a class=\"edit-tabs-371\" href=\"ver.php?id='.$row['idpagos'].'&pagoLibre=1\">'.$row['identificador_pago'].'</a>';
    }
  
    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
        '","Folio":"'.$row['identificador_pago'].
        '","Fecha de registro":"'.$row['fecha_registro'].
        '","Comentarios":"'.$row['comentarios'].
        '","Total":"'.$row['total'].
        '","Responsable":"'.$row['nombre'].
        '","saldo_insoluto":"'.$row['saldo_insoluto'].
        '","parcialidad":"'.$row['parcialidad'].
        '","Tipo":"'.$tipo.
        '"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>