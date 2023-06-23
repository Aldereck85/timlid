<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];
	include "../../../../../include/db-conn.php";
   
  $id = $_GET['id'];
  $ordenCompra = $_GET['txtId'];
  $notas = $_GET['txtNotas'];

  if($_GET['estatus'] == 0){
    $asunto = "Nueva orden de compra de Gh Medic, S.A. de C.V.";
    $mensaje = "Gh Medic, S.A. de C.V. te ha enviado una nueva orden de compra";
  }else{
    $asunto = "Modificación orden de compra de Gh Medic, S.A. de C.V.";
    $mensaje = "Gh Medic, S.A. de C.V. te ha enviado una modificacion de orden de compra";
  }
 ?>
<!-- Modal Datos envio -->
<div id="datos_envio" class="modal fade">
<!--<button class="btn btn-success" type="" name="btnEnviar" id="btnEnviar"><i class="fas fa-envelope"></i> Enviar</button>-->
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="txtIdProveedor" id="txtIdProveedor" value="<?=$id; ?>">
        <input type="hidden" name="txtId" id="txtId" value="<?=$ordenCompra; ?>">
        <input type="hidden" name="txtNotas" id="txtNotas" value="<?=$notas; ?>">
        <div class="modal-header">
          <h4 class="modal-title">Datos de envio</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">De: </label>
              <input class="form-control" type="text" name="txtOrigen" id="txtOrigen" value="<?= $user ?>">
            </div>
          </div>
          <div class="row">
            <?php
              $stmt = $conn->prepare('call spc_Datos_Proveedor_Emails(:PKProveedor)');
              $stmt->bindValue(':PKProveedor',$id);
              $stmt->execute();
              $row = $stmt->fetch();
            ?>
            <div class="form-group col-lg-12">
              <label for="txtEmail">Para: </label>
              <input class="form-control" type="text" name="txtDestino" id="txtDestino" value="<?=$row['Email']; ?>">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Asunto: </label>
              <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="<?=$asunto;?>">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Mensaje: </label>
              <textarea class="form-control" name="txaMensaje" id="txaMensaje" rows="5" cols="80"><?=$mensaje; ?></textarea>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button"><i class="fas fa-times"></i> Cancelar</button>
          <button class="btn btn-success" type="button" name="btnEnviar" id="btnEnviar"><i class="fas fa-envelope"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
var contadorEnviar = 0;
  $("#btnEnviar").click(function() {
      var id = $("#txtId").val();
      var idProveedor = $("#txtIdProveedor").val();  
      var notas = $("#txtNotas").val();  
      var emailOrigen = $("#txtOrigen").val();
      var emailDestino = $("#txtDestino").val();
      var asunto = $("#txtAsunto").val();
      var mensaje = $("#txaMensaje").val();


      /*if (contadorEnviar == 0) {

        $("#txtOrigen")[0].reportValidity();
        $("#txtOrigen")[0].setCustomValidity('Ingresa el correo electrónico de origen.');

        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa un correo electrónico válido.');

        $("#txtAsunto")[0].reportValidity();
        $("#txtAsunto")[0].setCustomValidity('Ingresa el asunto del correo.');

        $("#txaMensaje")[0].reportValidity();
        $("#txaMensaje")[0].setCustomValidity('Ingresa un mensaje del correo.');
        contadorEnviar = 1;
      }

      if (emailOrigen.trim() == "") {
        $("#txtOrigen")[0].reportValidity();
        $("#txtOrigen")[0].setCustomValidity('Ingresa el correo electrónico de origen.');
        return;
      }
      var validarEmailOrigen = isEmail(emailOrigen);
      if (validarEmailOrigen == false) {
        $("#txtOrigen")[0].reportValidity();
        $("#txtOrigen")[0].setCustomValidity('Ingresa un correo electrónico válido.');
        return;
      }

      if (emailDestino.trim() == "") {
        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa el correo electrónico de destino.');
        return;
      }

      var validarEmailDestino = isEmail(emailDestino);
      if (validarEmailDestino == false) {
        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa un correo electrónico válido.');
        return;
      }

      if (asunto.trim() == "") {
        $("#txtAsunto")[0].reportValidity();
        $("#txtAsunto")[0].setCustomValidity('Ingresa el asunto del correo.');
        return;
      }

      if (mensaje.trim() == "") {
        $("#txaMensaje")[0].reportValidity();
        $("#txaMensaje")[0].setCustomValidity('Ingresa un mensaje del correo.');
        return;
      }*/

      $.ajax({
        type: 'POST',
        url: 'functions/enviar_OrdenCompra.php',
        data: {
          txtId: id,
          txtIdProveedor: idProveedor,
          txtOrigen: emailOrigen,
          txtDestino: emailDestino,
          txtAsunto: asunto,
          txaMensaje: mensaje,
          txtNotas:notas
        },
        success: function(data) {
          if (data == "exito") {
            Swal.fire({
                title: "Envío exitoso",
                text: "Se realizó el envío del correo con la orden de compra al proveedor",
                type: "success"
            }).then (function() {
              $("#txaMensaje").val("");
              $("#datos_envio").modal('toggle');
              window.location.href = "agregarOrdenCompra";
            });
          } else {
            Swal.fire("Error", 
              "No se realizó el envío del correo con la orden de compra al proveedor, ¡Favor de intentarlo más tarde!", 
              "warning"
            );
          }
        }
      });


    });
</script>