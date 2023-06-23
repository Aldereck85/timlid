<?php

function update_Status_Complement($empresa){
    require('../../../include/db-conn.php');
    require_once('../../../include/functions_api_facturation.php');
    require_once ('../../../vendor/facturapi/facturapi-php/src/Facturapi.php');
    $api = new API();

    //recupera los pagos que tienen complemento en estatus pendiente de cancelar
    $query = sprintf('SELECT fp.id_api, fp.folio_pago  from facturas_pagos fp
                        inner join pagos p on p.identificador_pago = fp.folio_pago and fp.empresa_id = p.empresa_id
                    where p.estatus = 1 and fp.estatus = 2 and fp.empresa_id = :empresa;');
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();
    $res=$stmt->rowCount();
    $result=$stmt->fetchAll();
    $stmt->closeCursor();
    $return = 0;
    if($res > 0){
        //recuperación de los datos necesarios para facturapi
        //se recupera la key de la empresa
        $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":id",$empresa);
        $stmt->execute();

        $key_company_api = $stmt->fetchAll();
        $stmt->closeCursor();
        foreach ($result as $r) {
            $complemento_Facturapi = $api->searchInvoice($key_company_api[0]['key_company'],$r['id_api']);
            
            //si el estatus cambió a cancelada, se libera el pago para edición.
            if(isset($complemento_Facturapi->id) && $complemento_Facturapi->id !== "" && $complemento_Facturapi->id !== null){
                if($complemento_Facturapi->status == 'canceled'){
                    $query = sprintf('UPDATE facturas_pagos as f 
                                set f.estatus = 0 
                                where f.folio_pago = :folio 
                                    and f.empresa_id = :empresa;');
                    $stmt = $conn->prepare($query);
                    $stmt->bindValue(":folio",$r['folio_pago']);
                    $stmt->bindValue(":id",$empresa);
                    $stmt->execute();
                    $return = 1;
                }
            }
        } 
    }
    return $return;
}

?>