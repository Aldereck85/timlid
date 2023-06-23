<?php
require_once('../../../include/db-conn.php');
session_start();
    //Optener los datos para la pantalla de editar
    $empresa = $_SESSION["IDEmpresa"];
    if(isset($_POST['funcion'],$_POST['idpagos']) && !empty($_POST['funcion']) && !empty($_POST['idpagos'])) {
        $funcion = $_POST['funcion'];
        $idpagos = $_POST['idpagos'];
        //En función del parámetro que nos llegue ejecutamos una función u otra
        switch($funcion) {
            case '1': 
                $stmt = $conn->prepare("SELECT idpagos,fecha_pago,tipo_pago,pg.comentarios,total,Referencia,pv.PKProveedor,mcbe.cuenta_origen_id,fecha_pago, mcbe.parcialidad, cat.Nombre categoria, subcat.Nombre subcategoria
                from pagos as pg 
                left join movimientos_cuentas_bancarias_empresa as mcbe on pg.idpagos = mcbe.id_pago
                left join cuentas_por_pagar as cpp on  mcbe.cuenta_pagar_id = cpp.id
                left join proveedores as pv on pv.PKProveedor = mcbe.FKProveedor
                left join categoria_gastos cat on pg.categoria_id = cat.PKCategoria
                left join subcategorias_gastos subcat on pg.subcategoria_id = subcat.PKSubcategoria
                where (pg.estatus = 1) and pg.tipo_movimiento = 1 and (pv.empresa_id=$empresa) and pg.idpagos = $idpagos group by pg.idpagos");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    /* Convertir a numero y agregar signo $ */
                    $row['total'] = number_format($row['total'],2);
                    /* La linea de abajo quita lo que recien se agrego para que paresca monesa, (los comas y el signo $) */
                    /* $row['importe'] = filter_var($row['importe'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); */

                    if(count($row) > 0){

                        $pkuser = $_SESSION["PKUsuario"];
                        $permisos = $conn->prepare("Select funcion_editar, funcion_eliminar, 
                        pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
                        on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 28");
                        $permisos->execute();
                        $rowper = $permisos->fetch();
                        $editarB ='';
                        $EliminarB='';
                        //botones editar y eliminar
                        if(($row['parcialidad'] == 0)){
                            //Si tiene permiso de ver pinta el boton
                            if($rowper['funcion_editar']==1){
                              $editarB = '<a class="edit-tabs-371" style="margin-right:25px;" href="editar.php?id='.$row['idpagos'].'"><i class="fas fa-edit"></i> Editar</a>';
                            }
                            //Si tiene permiso de Eliminar pinta el boton
                            if($rowper['funcion_eliminar']==1){
                              $EliminarB = '<span class="btn-table-custom btn-table-custom--red" name="btnCancelarOC" onclick="modalShow('.$row['idpagos'].',1)"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg">Eliminar</span>'; 
                            }
                        }else{
                            if($rowper['funcion_editar']==1){
                              $editarB = '<a class="edit-tabs-371" style="margin-right:25px;" href="editar_anticipo.php?id='.$row['idpagos'].'"><i class="fas fa-edit"></i> Editar</a>';
                            }
                            if($rowper['funcion_eliminar']==1){
                                $EliminarB = '<span class="btn-table-custom btn-table-custom--red" name="btnCancelarOC" onclick="modalShow('.$row['idpagos'].',1)"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg">Eliminar</span>'; 
                            }
                          }

                        $userData = $row;
                        $data['status'] = 'ok';
                        $data['result'] = $userData;
                        $data['btnEdit']= $editarB;
                        $data['btnDelete']= $EliminarB;
                    }else{
                        $data['status'] = 'err';
                        $data['result'] = '';
                    }
                    
                    //returns data as JSON format
                    echo json_encode($data);
                /* $a -> accion1(); */
                break;
            case 'funcion2': 
                $b -> accion2();
                break;
        }
    }
 ?>