<?php
require_once('../../../include/db-conn.php');
session_start();

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

if(isset($_REQUEST['ids'])){
    $empresa = $_SESSION["IDEmpresa"];
    $ids = $_REQUEST['ids'];
    $tipoCuenta = $_REQUEST['tipoCuenta'];
    $idPago = $_REQUEST['idPago'];
    $isSubstitution = $_REQUEST['isSubstitution'];
    $pagadas=array();
    $facturasComplemento=(object)[];
    $flag = false;

    //recupera las facturas que tienen un complemento de pago para cumplir validacion
    $smtp=$conn->prepare('SELECT f.id,max(m.parcialidad) as parcialidad from facturacion f
    inner join movimientos_cuentas_bancarias_empresa m on m.id_factura=f.id
    inner join pagos p on p.idpagos=m.id_pago
    inner join facturas_pagos fp on fp.folio_pago=p.identificador_pago
    where fp.estatus != 0 and fp.empresa_id=:empresa and f.empresa_id=:empresa2 and f.prefactura = 0 group by f.id;');
    $smtp->bindValue(":empresa",$empresa);
    $smtp->bindValue(":empresa2",$empresa);
    $smtp->execute();

    while (($row = $smtp->fetch()) !== false){
      $facturasComplemento->{$row['id']} = $row['parcialidad'];
    }
    $smtp->closeCursor();

    //Consulta las que fueron pagadas por el pago actual
    $c = $conn->prepare('SELECT t.id_factura as id
                          FROM pagos as p 
                          inner join movimientos_cuentas_bancarias_empresa as t on p.idpagos = t.id_pago
                           where p.empresa_id=:empresa and p.identificador_pago=:idPago and p.tipo_movimiento=0 and p.estatus=1;');
    $c->bindValue(":empresa",$empresa);
    $c->bindValue(":idPago",$idPago);
    $c->execute();

    while (($row1 = $c->fetch()) !== false) {
      array_push($pagadas,$row1['id']);
    }
    $c->closeCursor();

    if($tipoCuenta == 2){
      $query='(SELECT f.id, 
                      p.identificador_pago, 
                      concat (f.serie, f.folio) as "Folio",  
                      c.razon_social AS "Nombre Comercial", 
                      f.fecha_timbrado as "Fecha de facturacion", 
                      if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento) as `Fecha de vencimiento`, 
                      f.total_facturado as "Monto factura", 
                      m.saldo_anterior, 
                      f.saldo_insoluto,
                      m.saldo_insoluto as saldo_insoluto_validar, 
                      m.parcialidad, 
                      m.Deposito, 
                      f.metodo_pago,
                      f.estatus,
                      m.tipo_CuentaCobrar 
                FROM facturacion as f
                  inner join clientes as c on f.cliente_id=c.PKCliente 
                  left join movimientos_cuentas_bancarias_empresa as m on f.id=m.id_factura and m.tipo_CuentaCobrar = 2
                  inner join pagos as p on p.idpagos=m.id_pago 
                where f.empresa_id=:empresa and m.estatus=1 and f.estatus not in (4,5) and f.id in('.$ids.') and p.identificador_pago=:idPago and p.estatus=1 and f.prefactura = 0 group by m.id_factura)
                      
                union
                      
                (SELECT f.id, 
                        null, 
                        concat (f.serie, f.folio) as "Folio",  
                        c.razon_social AS "Nombre Comercial", 
                        f.fecha_timbrado as "Fecha de facturacion", 
                        if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento) as `Fecha de vencimiento`, 
                        f.total_facturado as "Monto factura", 
                        m.saldo_anterior, 
                        f.saldo_insoluto, 
                        m.saldo_insoluto as saldo_insoluto_validar, 
                        m.parcialidad, 
                        m.Deposito, 
                        f.metodo_pago,
                        f.estatus,
                        m.tipo_CuentaCobrar 
                  FROM facturacion as f
                    inner join clientes as c on f.cliente_id=c.PKCliente 
                    left join (select m.id_pago, m.id_factura, m.saldo_anterior, m.saldo_insoluto, m.Deposito, m.parcialidad, m.tipo_CuentaCobrar from movimientos_cuentas_bancarias_empresa as m 
                                where m.estatus=1 and m.tipo_CuentaCobrar = 2 and m.parcialidad  = (select max(mm.parcialidad) from movimientos_cuentas_bancarias_empresa as mm where mm.id_factura=m.id_factura and mm.estatus=1 and m.tipo_CuentaCobrar = 2) group by m.id_factura) as m on m.id_factura=f.id 
                  where f.empresa_id=:empresa2 and f.estatus not in (4,3,5) and f.id in('.$ids.') and f.prefactura = 0 group by m.id_factura);';
    }else{
      $query='(SELECT vd.PKVentaDirecta as id, 
                      p.identificador_pago, 
                      vd.Referencia as "Folio",  
                      c.razon_social AS "Nombre Comercial", 
                      vd.created_at as "Fecha de facturacion", 
                      if(vd.FechaVencimiento is null, date_add(vd.created_at, interval c.Dias_credito day),vd.FechaVencimiento) as `Fecha de vencimiento`, 
                      vd.Importe as "Monto factura", 
                      m.saldo_anterior, 
                      vd.saldo_insoluto_venta as saldo_insoluto,
                      m.saldo_insoluto as saldo_insoluto_validar, 
                      m.parcialidad, 
                      m.Deposito, 
                      0 as metodo_pago,
                      vd.estatus_cuentaCobrar as estatus,
                      m.tipo_CuentaCobrar 
              FROM ventas_directas as vd
                  inner join clientes as c on vd.FKCliente=c.PKCliente 
                  left join movimientos_cuentas_bancarias_empresa as m on vd.PKVentaDirecta = m.id_factura and m.tipo_CuentaCobrar = 1
                  inner join pagos as p on p.idpagos=m.id_pago 
                  where p.empresa_id=:empresa and vd.empresa_id !=6 and m.estatus=1 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar not in (4,5) and vd.PKVentaDirecta in('.$ids.') and p.identificador_pago=:idPago and p.estatus=1 group by m.id_factura)
                  
              union
                  
              (SELECT vd.PKVentaDirecta as id, 
                      null, 
                      vd.Referencia as "Folio",  
                      c.razon_social AS "Nombre Comercial", 
                      vd.created_at as "Fecha de facturacion", 
                      if(vd.FechaVencimiento is null, date_add(vd.created_at, interval c.Dias_credito day),vd.FechaVencimiento) as `Fecha de vencimiento`, 
                      vd.importe as "Monto factura", 
                      m.saldo_anterior, 
                      vd.saldo_insoluto_venta as saldo_insoluto, 
                      m.saldo_insoluto as saldo_insoluto_validar,
                      m.parcialidad, 
                      m.Deposito, 
                      0 as metodo_pago,
                      vd.estatus_cuentaCobrar as estatus,
                      m.tipo_CuentaCobrar 
              FROM ventas_directas as vd
                inner join clientes as c on vd.FKCliente = c.PKCliente 
                left join (select m.id_pago, m.id_factura, m.saldo_anterior, m.saldo_insoluto, m.Deposito, m.parcialidad, m.tipo_CuentaCobrar from movimientos_cuentas_bancarias_empresa as m 
                      where m.estatus=1 and m.tipo_CuentaCobrar = 1 and m.parcialidad  = (select max(mm.parcialidad) from movimientos_cuentas_bancarias_empresa as mm where mm.id_factura=m.id_factura and mm.estatus=1 and mm.tipo_CuentaCobrar = 1) group by m.id_factura) as m on m.id_factura=vd.PKVentaDirecta 
              where vd.empresa_id=:empresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar not in (4,3,5) and vd.PKVentaDirecta in('.$ids.') group by m.id_factura);';

    }


    
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->bindValue(":idPago",$idPago);
    $stmt->bindValue(":empresa2",$empresa);

    $stmt->execute();
    $table="";
    $input="";
    $contador=0;
    //bandera para identificar que esta factura añadida es nueva para el pago, por lo tanto se puede editar
    $flagAñadir=false;

    while (($row = $stmt->fetch()) !== false) { 
      if(in_array ($row['id'],$pagadas) && $row['tipo_CuentaCobrar'] == $tipoCuenta){
        if($row['identificador_pago']==$idPago){
            $flag = true;
            $contador++;
        }else{
            $flag = false;
        }
      }else{
        $flagAñadir=true;
        $flag = true;
        $row['Deposito']="";
        $contador++;
      }

      if($flag){
      
        if($row['saldo_anterior']==""){
          $row['saldo_anterior']=$row['saldo_insoluto'];
        }

        if($row['parcialidad']==""){
          $row['parcialidad']="0";
        }

        if($row['Deposito']==""){
          $row['Deposito']=$row['saldo_insoluto'];
        }

        //cambiamos formato a las fechas
        $row['Fecha de facturacion']=date("d-m-Y", strtotime($row['Fecha de facturacion']));
        $row['Fecha de vencimiento']=date("d-m-Y", strtotime($row['Fecha de vencimiento']));

        $fechaVencimiento=date("Y-m-d", strtotime($row['Fecha de vencimiento']));
        $fechaActual=date("Y-m-d");
  
        if( $fechaActual > $fechaVencimiento){
          $row['Fecha de vencimiento']='<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha de vencimiento'].'</span>';
        }else{
          $row['Fecha de vencimiento']='<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha de vencimiento'].'</span>';
        }
        
        /* Guardamos en un JSON los datos de la consulta  */
        $row['saldo_anterior']=formatoCantidad($row['saldo_anterior']);
        $row['saldo_insoluto']=formatoCantidad($row['saldo_insoluto']);
        $row['Monto factura']=formatoCantidad($row['Monto factura']);

        $MF= $row['saldo_anterior'];
        $MP= formatoCantidad($row['Deposito']);
        //condicion: que para definir la posición del tooltip en "ver"
        if($contador<2){
          $posicion="left";
        }else{
          $posicion="auto";
        }

        //segun el metodo de pago, activa o desactiva el input para insertar el importe, si la factura ya esta pagada solo se podrá editar el ultimo pago y si la factura esta timbrada solo se podran editar las parcialidades apartir de la ultima timbrada y si se trata de una sustitucion de complemento, se podrá editar la ultima timbrada 
        if($row['metodo_pago']==1){
          $input='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MF.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
          $acciones='<img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"eliminaFactura('.$row['id'].')\"/>';
        }else if(isset($facturasComplemento->{$row['id']}) && $row['tipo_CuentaCobrar'] == 2){
          if($row['parcialidad']>$facturasComplemento->{$row['id']} || $flagAñadir==true ){
            if($row['estatus']==3 && $row['saldo_insoluto_validar']!=0){
              $input='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
              $acciones='<img class=\"disabled\" src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"alerta(1)\"/>';  
            }else{
              $flagAñadir=false;
              $input='<input class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
              $acciones='<img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"eliminaFactura('.$row['id'].')\"/>';  
            }
          }else if($isSubstitution == 1 && $row['parcialidad']==$facturasComplemento->{$row['id']}){
            $input='<input class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
            $acciones='<img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"eliminaFactura('.$row['id'].')\"/>';  
          }
          else{
          $input='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
          $acciones='<img class=\"disabled\" src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"alerta(2)\"/>';
          }
        }else if($row['estatus']==3 && $row['saldo_insoluto_validar']!=0){
        $input='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
        $acciones='<img class=\"disabled\" src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"alerta(1)\"/>';
        }else{
          $input='<input class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
          $acciones='<img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"eliminaFactura('.$row['id'].')\"/>';
        }

        $row['Nombre Comercial'] = str_replace('"', '', $row['Nombre Comercial']);

        $table.='{"Folio":"'.$row['Folio'].'",
          "Cliente":"'.$row['Nombre Comercial'].'",
          "Monto factura":"$'.$row['Monto factura'].'",
          "F Facturacion":"'.$row['Fecha de facturacion'].'",
          "F Vencimiento":"'.$row['Fecha de vencimiento'].'",
          "Saldo anterior":"$'.$row['saldo_anterior'].'",
          "Importe pago":"'.$input.'",
          "Saldo insoluto":"$'.$row['saldo_insoluto'].'",
          "Acciones":"'.$acciones.'",
          "No Parcialidad":"'.$row['parcialidad'].'"},'; 
      }
      
    }

       $table = substr($table,0,strlen($table)-1);
        echo '{"data":['.$table.']}';      
}

?>