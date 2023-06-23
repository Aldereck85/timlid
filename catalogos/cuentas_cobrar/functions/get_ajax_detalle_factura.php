<?php
require_once('../../../include/db-conn.php');
session_start();

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$empresa = $_SESSION["IDEmpresa"];

    if(isset($_POST['funcion'], $_POST['idFactura'], $_POST['is_invoice']) && !empty($_POST['funcion']) && !empty($_POST['idFactura']) && !empty($_POST['is_invoice'])) {
        $funcion = $_POST['funcion'];
        $idFactura = $_POST['idFactura'];
        //En función del parámetro que nos llegue ejecutamos una función u otra
        switch($funcion) {
            case '1': 
                if($_POST['is_invoice'] == 'idFactura'){
                    $query = ("SELECT c.PKCliente id,razon_social, CONCAT(folio, serie) as folio, total_facturado, fecha_timbrado, 
                    if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento) as fechaVencimiento 
                    from clientes as c inner join facturacion as f on f.cliente_id=c.PKCliente where f.id=:idFactura and f.empresa_id=:empresa and f.prefactura = 0");
                }else{
                    $query = ("SELECT c.PKCliente id,
                                        c.razon_social, 
                                        vd.Referencia as folio, 
                                        vd.Importe as total_facturado, 
                                        vd.created_at as fecha_timbrado, 
                                        if(vd.FechaVencimiento is null, date_add(vd.created_at, interval c.Dias_credito day),vd.FechaVencimiento) as fechaVencimiento 
                                from clientes as c 
                                    inner join ventas_directas as vd on vd.FKCliente=c.PKCliente 
                                where vd.PKVentaDirecta=:idFactura and vd.empresa_id=:empresa and vd.empresa_id !=6;");
                }
                $stmt = $conn->prepare($query);
                $stmt->bindValue(":empresa",$empresa);
                $stmt->bindValue(":idFactura",$idFactura);    
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                    if(!empty($row)){
                        $row['fecha_timbrado']=date("d-m-Y", strtotime($row['fecha_timbrado']));
                        $row['fechaVencimiento']=date("d-m-Y", strtotime($row['fechaVencimiento']));
                        $row['total_facturado']=" ".formatoCantidad($row['total_facturado']);
                        $userData = $row;
                        $data['status'] = 'ok';
                        $data['result'] = $userData;
                    }else{
                        $data['status'] = 'err';
                        $data['result'] = '';
                    }
                    
                    //returns data as JSON format
                    echo json_encode($data);
                break;
            case 'funcion2': 
                $b -> accion2();
                break;
        }
    }
 ?>