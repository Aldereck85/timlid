<?php
session_start();
use Facturapi\Facturapi;
date_default_timezone_set('America/Mexico_City');
class conectar
{ //Llamado al archivo de la conexión.


    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}
class Insert_datas{
    public function name(){
        $con = new conectar();
    }

}
class Get_datasNC{
    public function getFacturas_Cliente($idCliente){
        $con = new conectar();
        $db = $con->getDb(); 
        
    }
    public function loadCmbFdPago(){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_Combo_Formas_Pago_Sat()');
        $stmt = $db->prepare($query);
        $stmt->execute(array());
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCabecera($idNC){
        $con = new conectar();
        $db = $con->getDb(); 
        $query = sprintf('call spc_NotaCredit_Cabecera(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idNC));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadtblConceptos($idNC){
        $con = new conectar();
        $db = $con->getDb(); 
        $query = sprintf('call spc_NotaCredit_tblConceptos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idNC));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCMBImpGen($factura){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_Combo_Impuestos_factura(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($factura));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCMBImpuestosPrd($producto){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_Combo_Impuestos_productos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($producto));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCmbClientes(){
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Cliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCmbRelacion_fact(){
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT * FROM relacion_facturas;');
        $stmt = $db->prepare($query);
        $stmt->execute(array());

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCMBdetallesFact($factura){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_Combo_Detalles_factura(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($factura));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadlistConcepto($word){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_Concepto_LIKE(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($word));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadlistClavesSat($word){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_ClaveSat_LIKE(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($word));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadlistClavesSat_unit($word){
        $con = new conectar();
        $db = $con->getDb(); 

        $query = sprintf('call spc_claves_sat_unidades_LIKE(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($word));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function load_DocsR($cliente){
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("SELECT id_Nota_Facturapi, folion_nota, num_serie_nota, cliente_id, importe  from notas_cuentas_por_cobrar where cliente_id = $cliente and (estatus = 2 or 1) and empresa_id = $PKEmpresa;");
        $stmt = $db->prepare($query);
        $stmt->execute(array());

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
class Update_datasNC{
    public function name(){
        $con = new conectar();
    }
}
class Delete_datas{
    public function delete_NC($idNC){
        $ruta_api = "../../../";
        $empresa = $_SESSION["IDEmpresa"];
        $userid = $_SESSION["PKUsuario"];
        $motive = $_REQUEST["motive"];
        $idsnc = $_REQUEST['idsnc'];
        require_once($ruta_api.'include/db-conn.php');
        require_once($ruta_api.'include/functions_api_facturation.php');
        require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
        $key_company_api;
        $UUIDnc;
        try{
        $con = new conectar();

          //se recupera la key de la empresa
        $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":id",$empresa);
        $stmt->execute();
        $key_company_api = $stmt->fetchAll();

        $query = sprintf("SELECT id_Nota_Facturapi from notas_cuentas_por_cobrar where (id = :idNC) and (estatus = 1 OR estatus = 2);");
        $stmt->closeCursor();
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":idNC",$idNC);
        $stmt->execute();
        $UUIDnc = $stmt->fetchAll();
        $UUIDnc = $UUIDnc[0]['id_Nota_Facturapi'];

        $date = date('Y-m-d H:i:s');
       $query = sprintf('UPDATE notas_cuentas_por_cobrar SET estatus = 2, fecha_modifico = "'.$date.'" where id = :id;');
       $stmt = $conn->prepare($query);
       $stmt->bindValue(":id",$idNC);
       $stmt->execute();

       $flag;
        $facturapi = new API();
        if(($idsnc != 0) && $motive == "01"){
            $flag = "01";

            $facturapi = new Facturapi($key_company_api[0]['key_company']);

            $facturapi->Invoices->cancel(
                $UUIDnc,
                [
                "motive" => $motive,
                "substitution" => $idsnc
                ]
            );
           // $facturapi->cancelInvoice($key_company_api[0]['key_company'],$UUIDnc,$motive,$idsnc);
        }else{
            $flag = $motive;
            $facturapi->cancelInvoice($key_company_api[0]['key_company'],$UUIDnc,$motive,"");
        }

            echo("1");
           // print_r($flag);
        }catch(Exception $e){
           // echo($e);
            echo("2");
        }
    }

    public function delete_NCV($idNCV){
        $ruta_api = "../../../";
        $empresa = $_SESSION["IDEmpresa"];
        require_once($ruta_api.'include/db-conn.php');

        try{
        $query = sprintf("SELECT tipo_nc from notas_cuentas_por_cobrar where (id = :idNCV) and estatus = 1 and empresa_id = :empresa;");
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":idNCV",$idNCV);
        $stmt->bindValue(":empresa",$empresa);
        $stmt->execute();
        $res = $stmt->fetch();
        $tipo = $res['tipo_nc'];

        if($tipo == 2){
            $date = date('Y-m-d H:i:s');
            $query = sprintf('UPDATE notas_cuentas_por_cobrar SET estatus = 2, fecha_modifico = "'.$date.'" where id = :id and empresa_id = :empresa;');
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":id",$idNCV);
            $stmt->bindValue(":empresa",$empresa);
            $stmt->execute();
            echo("1");
        }else{
            echo('error: no es tipo NCV');
        }

        $stmt = null;

        }catch(Exception $e){
           // echo($e);
            echo("2: ".$e);
        }
    }
}

class send_data{

    function sendEmail($value,$data){
      $con = new conectar();
      $db = $con->getDb();

      $ruta_api = "../../../";
      require_once $ruta_api . "include/functions_api_facturation.php";
      require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
      $api = new API();

      $query = sprintf("SELECT key_company_api from empresas where PKEmpresa = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
      $stmt->execute();
      $emp = $stmt->fetchAll();

      $query = sprintf("SELECT id_Nota_Facturapi from notas_cuentas_por_cobrar where id= :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();
      $fact = $stmt->fetchAll();
      

      if(count($data) < 2){
        $mensaje = $api->sendEmailInvoice($emp[0]['key_company_api'],$fact[0]['id_Nota_Facturapi'],$data[0]);
      } else{
        $destinos = [];
        for ($i=0; $i < count($data); $i++) { 
          array_push(
            $destinos,
            $data[$i]
          );
        }

        $mensaje = $api->sendMoreEmailInvoice($emp[0]['key_company_api'],$fact[0]['id_Nota_Facturapi'],$data);
      }

      return $mensaje;
    }

  }