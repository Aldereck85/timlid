<?php
session_start();
if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa WHERE PKCuenta = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();

    $html = $row['PKCuenta'];
    $html1 = $row['Nombre'];
    $html2 = $row['tipo_cuenta'];
    $FKEmpresa = $row['empresa_id'];

    if($row['tipo_cuenta'] == 1){
      $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa INNER JOIN cuentas_cheques ON PKCuenta = FKCuenta WHERE PKCuenta = :id');
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $tipoC = $row['tipo_cuenta'];
      $nombre = $row['Nombre'];
      if($tipoC == 1){
        $tipoCuenta = "Cuentas de Cheques(Bancaria)";
      }else if($tipoC == 2){
        $tipoCuenta = "Crédito";
      }else if($tipoC == 3){
        $tipoCuenta = "Otro (No bancarias o control interno)";
      }else{
        $tipoCuenta = "Caja chica";
      }
        $empresa = $row['empresa_id'];
        $bancoCheques = $row['FKBanco'];
        $noCuenta = $row['Numero_Cuenta'];
        $clabe = $row['CLABE'];
        $saldoCheques = $row['Saldo_Inicial'];
        $moneda = $row['FKMoneda'];

        // SELECCIÓN DEL BANCO
          $stmt = $conn->prepare('SELECT * FROM bancos');
          $stmt->execute();
          $row = $stmt->fetchAll();
          $bancos = "<option value='0'>Seleccione una banco...</option>";
          foreach($row as $b){
            $bancos .= "<option value='".$b["PKBanco"]."'";
            if($bancoCheques == $b["PKBanco"]){
              $bancos .= " selected";
            }
            $bancos .= ">".$b['Banco']."</option>";
          }
         // SELECCIÓN DE LA EMPRESA
          $stmt = $conn->prepare('SELECT PKEmpresa,RazonSocial FROM empresas');
          $stmt->execute();
          $row = $stmt->fetchAll();
          $empresas = "<option value='0'>Seleccione una opción...</option>";
          foreach($row as $r){
            $empresas .= "<option value='".$r["PKEmpresa"]."'";
            if($FKEmpresa == $r["PKEmpresa"]){
              $empresas .= " selected";
            }
            $empresas .= ">".$r['RazonSocial']."</option>";
          }
          //SELECCION DE LA MONEDA

        $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1 ORDER BY Descripcion ASC');
        $stmt->execute();
        $row = $stmt->fetchAll();
      
        $monedas1 = "";
        foreach($row as $b){
          $monedas1 .= "<option value='".$b["PKMoneda"]."'";
          if($moneda == $b["PKMoneda"]){
            $monedas1 .= " selected";
          }
          $monedas1 .= ">".$b['Descripcion']."</option>";
        }
        
        $json->bancos = $bancos;
        $json->clabe = $clabe;
        $json->noCuentaCheques = $noCuenta;
        $json->saldoCheques = $saldoCheques;
        $json->monedas1 = $monedas1;
        $json->empresasCh = $empresas;
      
    }else if($row['tipo_cuenta'] == 2){
      $stmt = $conn->prepare('SELECT *FROM cuentas_bancarias_empresa INNER JOIN cuentas_credito ON PKCuenta = FKCuenta WHERE PKCuenta = :id');
        $stmt->bindValue(':id',$id);
        $stmt->execute();
        $row = $stmt->fetch();

        $tipoC = $row['tipo_cuenta'];
        if($tipoC == 1){
          $tipoCuenta = "Cuentas de Cheques(Bancaria)";
        }else if($tipoC == 2){
          $tipoCuenta = "Crédito";
        }else if($tipoC == 3){
          $tipoCuenta = "Otro (No bancarias o control interno)";
        }else{
          $tipoCuenta = "Caja chica";
        }
        $nombre = $row['Nombre'];
        $empresa = $row['empresa_id'];
        $bancoCredito = $row['FKBanco'];
        $credito = $row['Numero_Credito'];
        $referencia = $row['Referencia'];
        $limiteCredito = $row['Limite_Credito'];
        $moneda = $row['FKMoneda'];
        $creditoUtilizado = $row['Credito_Utilizado'];
  
        // SELECCIÓN DEL BANCO
       $stmt = $conn->prepare('SELECT * FROM bancos');
       $stmt->execute();
       $row = $stmt->fetchAll();
       $bancos = "<option value='0'>Seleccione una banco...</option>";
       foreach($row as $b){
         $bancos .= "<option value='".$b["PKBanco"]."'";
         if($bancoCredito == $b["PKBanco"]){
           $bancos .= " selected";
         }
         $bancos .= ">".$b['Banco']."</option>";
       }
      // SELECCIÓN DEL LA MONEDA
       $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1 ORDER BY Descripcion ASC');
       $stmt->execute();
       $row = $stmt->fetchAll();
       $monedas = "<option value='0'>Seleccione tipo de moneda...</option>";
       foreach($row as $b){
         $monedas .= "<option value='".$b["PKMoneda"]."'";
         if($moneda == $b["PKMoneda"]){
          $monedas .= " selected";
        }
        $monedas .= ">".$b['Descripcion']."</option>";
       }
         // SELECCIÓN DE LA EMPRESA
         $stmt = $conn->prepare('SELECT PKEmpresa,RazonSocial FROM empresas');
         $stmt->execute();
         $row = $stmt->fetchAll();
         $empresas = "<option value='0'>Seleccione una opción...</option>";
         foreach($row as $r){
           $empresas .= "<option value='".$r["PKEmpresa"]."'";
           if($FKEmpresa == $r["PKEmpresa"]){
             $empresas .= " selected";
           }
           $empresas .= ">".$r['RazonSocial']."</option>";
         }
        $json->bancosC = $bancos;
        $json->noCredito = $credito;
        $json->referencia = $referencia;
        $json->limiteCredito = $limiteCredito;
        $json->monedasC = $monedas;
        $json->empresasCr = $empresas;
        $json->creditoUtilizado = $creditoUtilizado;

    }else if($row['tipo_cuenta'] == 3){
      $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa INNER JOIN cuentas_otras ON PKCuenta = FKCuenta WHERE PKCuenta = :id');
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $rowss = $stmt->fetch();
      $tipoC = $rowss['tipo_cuenta'];
      
      if($tipoC == 1){
        $tipoCuenta = "Cuentas de Cheques(Bancaria)";
      }else if($tipoC == 2){
        $tipoCuenta = "Credito";
      }else if($tipoC == 3){
        $tipoCuenta = "Otro (No bancarias o control interno)";
      }else{
        $tipoCuenta = "Caja chica";
      }
      $nombre = $row['Nombre'];
      //$empresa = $row['FKEmpresa'];
      $idCuenta = $rowss['Cuenta'];
      $descripcion = $rowss['Descripcion'];
      $saldoInicial = $rowss['Saldo_Inicial'];
      $moneda = $rowss['FKMoneda'];
      // SELECCIÓN DEL LA MONEDA
      $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1 ORDER BY Descripcion ASC');
      $stmt->execute();
      $row = $stmt->fetchAll();
      $monedasO = "<option value='0'>Seleccione tipo de moneda...</option>";
      foreach($row as $b){
        $monedasO .= "<option value='".$b["PKMoneda"]."'";
        if($moneda == $b["PKMoneda"]){
          $monedasO .= " selected";
        }
        $monedasO .= ">".$b['Descripcion']."</option>";
      }
       // SELECCIÓN DE LA EMPRESA
       $stmt = $conn->prepare('SELECT PKEmpresa,RazonSocial FROM empresas');
       $stmt->execute();
       $row = $stmt->fetchAll();
       $empresasOtras = "<option value='0'>Seleccione una opción...</option>";
       foreach($row as $r){
         $empresasOtras .= "<option value='".$r["PKEmpresa"]."'";
         if($FKEmpresa == $r["PKEmpresa"]){
           $empresasOtras .= " selected";
         }
         $empresasOtras .= ">".$r['RazonSocial']."</option>";
       }
      $json->idCuenta = $idCuenta;
      $json->descripcion = $descripcion;
      $json->saldoInicial = $saldoInicial;
      $json->monedasO = $monedasO;
      $json->empresasOtras = $empresasOtras;
    }else if($row['tipo_cuenta'] == 4){
      
      $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa INNER JOIN cuenta_caja_chica ON PKCuenta = FKCuenta WHERE PKCuenta = :id');
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $tipoC = $row['tipo_cuenta'];
      $nombre = $row['Nombre'];
      $monedaCaja = $row['FKMoneda'];
      $idCuentaCaja = $row['PKCuenta'];
      $saldoIcaja = $row['SaldoInicialCaja'];
      //$saldoIcaja = "$ ".number_format($row['SaldoInicialCaja'],2);
      $descripcionCaja = $row['Descripcion'];
      $responsable = $row['FKResponsable'];

      if($tipoC == 1){
        $tipoCuenta = "Cuentas de Cheques(Bancaria)";
      }else if($tipoC == 2){
        $tipoCuenta = "Credito";
      }else if($tipoC == 3){
        $tipoCuenta = "Otro (No bancarias o control interno)";
      }else{
        $tipoCuenta = "Caja chica";
      }
      // SELECCIÓN DEL LA MONEDA
      $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1 ORDER BY Descripcion ASC');
      $stmt->execute();
      $row = $stmt->fetchAll();
      $monedasCaja = "<option value='0'>Seleccione tipo de moneda...</option>";
      foreach($row as $b){
        $monedasCaja .= "<option value='".$b["PKMoneda"]."'";
        if($monedaCaja == $b["PKMoneda"]){
          $monedasCaja .= " selected";
        }
        $monedasCaja .= ">".$b['Descripcion']."</option>";
      }
      
       // SELECCIÓN DEL RESPONSABLE
       $stmt = $conn->prepare('SELECT rg.PKResponsable as id, 
       rg.FKEmpleado, 
       e.Nombres as nom, 
       e.PrimerApellido as ape, 
       e.SegundoApellido 
       from responsable_gastos rg INNER JOIN empleados e on rg.FKEmpleado = e.PKEmpleado');
       $stmt->execute();
       $row = $stmt->fetchAll();
      
       $resp = "<option value='0'>Elige una opción...</option>";
      foreach($row as $r){
        $resp .= "<option value='".$r["id"]."'";
        if($responsable == $r["id"]){
          $resp .= " selected";
        }
        $resp .= ">".$r['nom']." ".$r['ape']."</option>";
      }
       $json->respuesta = $resp;
       $json->descrip = $descripcionCaja;
       $json->saldoIniciailC = $saldoIcaja;
       $json->monedasCajaCh = $monedasCaja;
       $json->idCuentaCaja = $idCuentaCaja;
       
    }else{
      
    }
    // SELECCIÓN DEL LA MONEDA
    $json->pkcuenta = $html;
    $json->nombre = $html1;
    $json->nombreTipoCuenta = $tipoCuenta;
    $json->tipo = $html2;
    //$json->empresas = $empresas;
    $json->tipoIdCuenta = $tipoC;
  
    $json = json_encode($json);
    echo $json;
}
?>