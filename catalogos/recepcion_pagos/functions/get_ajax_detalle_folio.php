<?php
require_once('../../../include/db-conn.php');
session_start();
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$empresa = $_SESSION["IDEmpresa"];
    if(isset($_POST['folio']) && !empty($_POST['folio'])) {
        $folio = $_POST['folio'];
        
            $stmt = $conn->prepare("SELECT p.identificador_pago, f.cliente_id, p.fecha_registro, p.tipo_pago, m.cuenta_destino_id, p.total, m.Referencia, p.comentarios from 
            pagos p inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
            inner join facturacion f on f.pago_id=p.idpagos where p.tipo_movimiento=0 and f.empresa_id=:empresaID and p.identificador_pago=:folio and estatus=1 group by f.cliente_id;");
           
            $stmt->bindValue("empresaID", $empresa);
            $stmt->bindValue("folio", $folio);

            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!empty($row)){
                $row['total']=" ".formatoCantidad($row['total']);
                $userData = $row;
                $data['status'] = 'ok';
                $data['result'] = $userData;
            }else{
                $data['status'] = 'err';
                $data['result'] = '';
            }
            
            //returns data as JSON format
            echo json_encode($data);
        
    }
 ?>