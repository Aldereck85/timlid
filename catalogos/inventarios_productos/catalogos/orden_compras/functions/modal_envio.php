<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];
	include "../../../../../include/db-conn.php";
   
  $id = $_GET['id'];
  $ordenCompra = $_GET['txtId'];
  $notas = $_GET['txtNotas'];

  if($_GET['estatus'] == 0){
    $asunto = "Nueva orden de compra";
    $mensaje = "Se ha recibido una nueva orden de compra";
  }else{
    $asunto = "Modificación orden de compra";
    $mensaje = "Se ha recibido una modificacion de orden de compra";
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button" onclick="cancelar()"><i class="fas fa-times"></i> Cancelar</button>
          <button class="btn btn-success" type="button" name="btnEnviar" id="btnEnviar" onclick="enviarCorreo()"><i class="fas fa-envelope"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
var contadorEnviar = 0;

  function cancelar(){
    window.location.href = "../orden_compras/index.php";
  }

  function enviarCorreo() {
      var id = $("#txtId").val();
      var idProveedor = $("#txtIdProveedor").val();  
      var notas = $("#txtNotas").val();  
      var emailOrigen = $("#txtOrigen").val();
      var emailDestino = $("#txtDestino").val();
      var asunto = $("#txtAsunto").val();
      var mensaje = $("#txaMensaje").val();

      $.ajax({
        type: 'POST',
        url: '../orden_compras/functions/enviar_OrdenCompra.php',
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
              //window.location.href = "agregarOrdenCompra";
              window.location.href = "../orden_compras/index.php";
            });
          } else {
            Swal.fire("Error", 
              "No se realizó el envío del correo con la orden de compra al proveedor, ¡Favor de intentarlo más tarde!", 
              "warning"
            ).then (function(){
              window.location.href = "../orden_compras/index.php";
            });
          }
        }
      });


  };

  $(document).ready(function () {
    //Redireccionamos al Dash cuando se oculta el modal.
    $("#datos_envio").on("hidden.bs.modal", function (e) {
          window.location.href = "./";
    });
  });

</script>