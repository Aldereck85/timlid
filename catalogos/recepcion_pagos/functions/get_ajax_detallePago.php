<?php
require_once('../../../include/db-conn.php');
session_start();
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');
//funcion para cambiar estatus del pago con complemento en proceso de cancelacion cuando sea cancelado.
require_once('function_estatus_complementos.php');

$empresa = $_SESSION["IDEmpresa"];
//actualizamos los estatus de los complementos pendientes de cancelar cuando ya fueron cancelados
$return = update_Status_Complement($empresa);

    if(isset($_POST['idPago']) && !empty($_POST['idPago'])) {
        $idPago = $_POST['idPago'];
        $id = $_SESSION["PKUsuario"];

        //recuperacion de permisos para el usuario
        $query=("select fp.funcion_ver, fp.funcion_editar, fp.funcion_eliminar from funciones_permisos fp 
        inner join usuarios u on u.perfil_id=fp.perfil_id where u.id=:id and u.empresa_id=:empresa and fp.pantalla_id = 31;");
        $res= $conn->prepare($query);
        $res->bindValue(":id",$id);
        $res->bindValue(":empresa",$empresa);
        $res->execute();

        while (($row = $res->fetch()) !== false) {
            $fn_ver=$row['funcion_ver'];
            $fn_editar=$row['funcion_editar'];
            $fn_eliminar=$row['funcion_eliminar'];
        }

        $res->closeCursor(); 


            $stmt = $conn->prepare('SELECT if(m.tipo_CuentaCobrar = 1, c2.PKCliente, c.PKCliente) as PKCliente, 
                                            if(m.tipo_CuentaCobrar = 1, c2.razon_social, c.razon_social)as NombreComercial, 
                                            p.fecha_pago, 
                                            if(m.tipo_CuentaCobrar = 1, 0, f.metodo_pago) as metodo_pago, 
                                            fp.id, 
                                            cu.PKCuenta, 
                                            cu.Nombre as "cuenta", 
                                            m.Referencia, 
                                            p.comentarios, 
                                            p.total, 
                                            p.forma_pago, 
                                            fp.descripcion, 
                                            if(m.tipo_CuentaCobrar = 1, 0, facp.estatus) as estatus, 
                                            m.tipo_CuentaCobrar as tipoDoc
                                    from pagos as p 
                                            inner join movimientos_cuentas_bancarias_empresa as m on m.id_pago=p.idpagos
                                            left join facturacion as f on f.id=m.id_factura and m.tipo_CuentaCobrar = 2 and f.prefactura = 0
                                            left join ventas_directas as vd on vd.PKVentaDirecta = m.id_factura and m.tipo_CuentaCobrar = 1 and vd.empresa_id !=6
                                            left join clientes as c on c.PKCliente=f.cliente_id 
                                            left join clientes as c2 on c2.PKCliente=vd.FKCliente 
                                            inner join formas_pago_sat as fp on fp.id = p.forma_pago
                                            inner join cuentas_bancarias_empresa as cu on m.cuenta_destino_id = cu.PKCuenta
                                            left join (select * from facturas_pagos where empresa_id=:empresa and estatus != 0) as facp on p.identificador_pago=facp.folio_pago 
                                    where p.identificador_pago=:idPago and  p.empresa_id = :empresa2 and p.estatus=1;');
                $stmt->bindValue(":idPago",$idPago);
                $stmt->bindValue(":empresa",$empresa);
                $stmt->bindValue(":empresa2",$empresa);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                //array de botones
                $arrAcciones=array();

                if($fn_editar==1){
                    if($row['metodo_pago']==3){
                        if($row['estatus']==1 || $row['estatus']==2){
                            array_push($arrAcciones,1);
                            array_push($arrAcciones,2);
                            if($row['estatus']==1){
                                array_push($arrAcciones,3);
                            }
                        }else{
                            array_push($arrAcciones,4);
                            array_push($arrAcciones,5);
                        }
                    }else{
                        array_push($arrAcciones,4);
                    }
                }
                if($fn_eliminar==1){
                    if($row['estatus']==0){
                        array_push($arrAcciones,6);
                    }
                }

                if(!empty($row))
                {
                    switch($row['metodo_pago']){
                        case "1":
                            $row['metodo_pago']= 'Pago en Una Exhibición';
                        break;
                        case "2":
                            $row['metodo_pago']= 'Pago Inicial y Parcialidades'; 
                        break;
                        case "3":
                            $row['metodo_pago']= 'Pago en Parcialidades o Diferido'; 
                        break;    
                        case "0":
                            $row['metodo_pago']= 'Sin Método'; 
                        break;              
                    }
                    $row['fecha_pago']=date("Y-m-d", strtotime($row['fecha_pago']));
                    $row['total']=" ".number_format($row['total'], 2, '.', ' ');
                    $userData = $row;
                    $data['status'] = 'ok';
                    $data['result'] = $userData;
                    $data['arrButtons'] = $arrAcciones;
                }else{
                    $data['status'] = 'err';
                    $data['result'] = '';
                }
                
                //returns data as JSON format
                echo json_encode($data);
            
        }
    
 ?>