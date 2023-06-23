<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $compra = $_GET['txtId'];
 ?>
<!-- Modal Datos envio -->
<div id="datos_envio" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="enviar_OrdenCompra.php" method="POST" target="_blank">
        <input type="hidden" name="txtId" value="<?=$id; ?>">
        <input type="hidden" name="txtCompra" value="<?=$compra; ?>">
        <div class="modal-header">
          <h4 class="modal-title">Datos de envio</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <?php
              $stmt = $conn->prepare('SELECT Email FROM datos_contacto_proveedores WHERE FKProveedor = :id');
              $stmt->bindValue(':id',1);
              $stmt->execute();
              $row = $stmt->fetch();
            ?>
            <div class="form-group col-lg-12">
              <label for="">De: </label>
              <input class="form-control" type="text" name="txtOrigen" value="<?=$row['Email']; ?>">
            </div>
          </div>
          <div class="row">
            <?php
              $stmt = $conn->prepare('SELECT Email FROM datos_contacto_proveedores WHERE FKProveedor = :id');
              $stmt->bindValue(':id',$id);
              $stmt->execute();
              $row = $stmt->fetch();
            ?>
            <div class="form-group col-lg-12">
              <label for="txtEmail">Para: </label>
              <input class="form-control" type="text" name="txtDestino" value="<?=$row['Email']; ?>">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Asunto: </label>
              <input class="form-control" type="text" name="txtAsunto" value="Orden de compra">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Mensaje: </label>
              <textarea class="form-control" name="txaMensaje" rows="5" cols="80"></textarea>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button"><i class="fas fa-times"></i> Cancelar</button>
          <button class="btn btn-success" type="submit" name="btnEnviar" id="btnEnviar" onclick="IrA();"><i class="fas fa-envelope"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function IrA(){
    window.location.href = "../index.php";
  }
</script>
