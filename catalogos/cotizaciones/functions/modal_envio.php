<?php
  session_start();

  $jwt_ruta = "../../../";
  require_once '../../jwt.php';

  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];
	include "../../../include/db-conn.php";
   
  $id = $_GET['idCotizacion'];

  if($_GET['estatus'] == 0){
    $asunto = "Nueva cotización";
    $mensaje = "Nueva cotización";
  }else{
    $asunto = "Modificación cotización";
    $mensaje = "Modificación cotización";
  }

  $idCliente = $_GET['idCliente'];

  $token = $_SESSION['token_ld10d'];

  $stmt = $conn->prepare('SELECT Email FROM clientes as c WHERE PKCliente = :id');
        $stmt->bindValue(':id', $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        $rowC = $stmt->fetch();
        $emailDestino = $rowC['Email'];
 ?>
<!-- Modal Datos envio -->
<div id="datos_envio" class="modal fade">
  <!--<button class="btn btn-success" type="" name="btnEnviar" id="btnEnviar"><i class="fas fa-envelope"></i> Enviar</button>-->
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="txtId" id="txtId" value="<?=$id; ?>">
        <div class="modal-header">
          <h4 class="modal-title">Datos de envio</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">De: </label>
              <input class="form-control" type="text" name="txtOrigen" id="txtOrigen" value="<?= $user ?>" required>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="txtEmail">Para: </label>
                <select name="txtDestino" id="txtDestino" required multiple>
                  <option data-placeholder="true"></option>
                  <option value="<?=
                    $emailDestino
                    ?>"><?= 
                    $emailDestino
                    ?></option>
                </select>
                <div class="invalid-feedback" id="invalid-emailDestino">Ingresa el correo electrónico de destino.</div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Asunto: </label>
              <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="<?=$asunto;?>" required>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Mensaje: </label>
              <textarea class="form-control" name="txaMensaje" id="txaMensaje" rows="5" cols="80" required><?=$mensaje; ?></textarea>
            </div>
          </div>
          <div align="center">
            <img src="../../img/chat/loading.gif" id="loading" width="30px"
              style="position: absolute; bottom: 5px;left:  45%;text-align: center;display: none;">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button"
                  id="btnCerrar" onclick="cerrarCorreo()"><i class="fas fa-times"></i> Cancelar</button>
          <button class="btn-custom btn-custom--blue" type="button" name="btnEnviar" id="btnEnviar"><i
              class="fas fa-envelope"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script> 
var contadorEnviar = 0;
let estatus = <?php echo $_GET['estatus']; ?>;
  function cerrarCorreo(){
    var id = $("#txtId").val();
    if(estatus == 0){
      $(location).attr('href', './');
    }
    else{
      $(location).attr('href', 'detalleCotizacion.php?id=' + id);
    }
    
  }
</script>