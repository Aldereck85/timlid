<?php

  //if(isset($_POST['idCuenta'])){
    require_once('../../../include/db-conn.php');
    $idTipo= $_POST['idTipo'];
    
    $fkCuenta= $_POST['idCuenta'];
   
    $table = "";
    $no = 1;
    $fila = 0;
  
    $stmt = $conn->prepare('SELECT mov.PKMovimiento as idMov,
        mov.FKCuenta as idCuenta,
		    mov.Fecha,
		    mov.Descripcion,
        mov.Retiro, 
        mov.Deposito,
        mov.Saldo, 
        mov.Referencia,
        mov.Comprobado,
        mov.FKCuentaDestino as idDestino,
        cc.PKCuentaCajaChica
        FROM movimientos_cuentas_bancarias_empresa as mov INNER JOIN cuenta_caja_chica as cc ON cc.FKCuenta=mov.FKCuenta 
        WHERE mov.FKCuenta = :idCuenta  ORDER BY PKMovimiento DESC');
       $stmt->execute(array(':idCuenta'=>$fkCuenta));

    while($row = $stmt->fetch()){
     
        if($row['Retiro']==null){
          $retiro =$row['Retiro'];
        }else{
          $retiro = "$".number_format($row['Retiro'],2);
        }
        if($row['Deposito']==null){
          $deposito =$row['Deposito'];
        }else{
          $deposito = "$".number_format($row['Deposito'],2);
        }
        if($row['Saldo']==null){
          $saldo =$row['Saldo'];
        }else{
          $saldo = "$".number_format($row['Saldo'],2);
        }
       $fila++;
        //COMPROBAR
        if($row['Comprobado']=="0"){
          $comprobar = ' <div class=\"image-upload\"><label for=\"file-input\"><i ><img  class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Cuenta\" src=\"../../img/timdesk/folder.png\"></i></label><input accept=\"image/*, .pdf, .xlsx, .xml\" id=\"file-input\" name=\"file-input\" type=\"file\" onchange=\"subirReferencia('.$row['idMov'].','.$row['idCuenta'].');\"/></div> <p>Pendiente</p> <input  class=\"btnEdit\" type=\"hidden\" id=\"'.$fila.'\" style=\"width: 0px;\">';
        }else{
          $comprobar = '<div id=\"contenedor-centrado\"> <i><img src=\"../../img/timdesk/comprobado.png\" class=\"btnUpload\"></i>  </div>';
        }
           //SI HAY UNA REFERENCIA
        if($row['Referencia'] != "-"){
          $ref = '<div id=\"contenedor-centrado\" > <a target=\"_blank\" href=\"functions/Documentos/'.$row['Referencia'].'\">'."".$row['Referencia'].'</a> </div>';
          //$ref = '<a href=\"functions/Documentos/'.$row['Referencia'].'\">'."".$row['Referencia'].'</a>';
        }else{
          $ref = $row['Referencia'];
        }
        $idDestino = $row['idDestino'];

        $table.='{"Fecha":"'.$row['Fecha'].'",
          "Descripción":"'.$row['Descripcion'].'",
          "Retiro/cargo":"'.$retiro.'",
          "Deposito/Abono":"'.$deposito.'",
          "Saldo":"'.$saldo.'",
          "Referencia":"' .$ref .'",
          "Comprobar":"'. $comprobar . ' <i><img class=\"btnEdit\" src=\"../../img/timdesk/delete.svg\" onclick=\"eliminarMovimiento('.$row['idMov'].','.$row['idCuenta'].');\"> ' .'"},';
        $no++;
    }
    


    
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';
  //}

  
  
?>