<?php
$idCotizacion = "79";
$nombreUsuario = "JULIO DELGADO";
$idUsuario = "25";
$telefono = "4432709315";
$codigoCotizacion = "HJclo0kNRh7j";

echo "
<style>
.btn {
    display: inline-block;
    font-weight: 400;
    color: #858796;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .35rem;
}
.btn-info {
    color: #fff;
    background-color: #36b9cc;
    border-color: #36b9cc;
  }
  
  .btn-info:hover {
    color: #fff;
    background-color: #2c9faf;
    border-color: #2a96a5;
  }
  .btn-success {
    color: #fff;
    background-color: #1cc88a;
    border-color: #1cc88a;
  }
  .btn-success:hover {
    color: #fff;
    background-color: #17a673;
    border-color: #169b6b;
  }
  .btn-primary {
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
  }
  
  .btn-primary:hover {
    color: #fff;
    background-color: #2e59d9;
    border-color: #2653d4;
  }  
  .box {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .btn-secondary {
    color: #fff;
    background-color: #858796;
    border-color: #858796;
  }
  .btn-secondary:hover {
    color: #fff;
    background-color: #717384;
    border-color: #6b6d7d;
  }
</style>
<h2 align='center'>COTIZACIÓN No. ".$idCotizacion."</h2>
<hr>
<p align='left'>Saludos, ".$nombreUsuario."</p>
<p align='justify'>Gracias por cotizar con nosotros, te enviamos la cotización solicitada, puedes realizar las siguientes operaciones:</p>
<div style='display:flex;'>
<div align='center' style='width: 25%'><button type='button' class='btn btn-info' onclick=\"location.href='http://127.0.0.1/GH%20Medic%20V1/cliente/descargarCotizacion.php?id=".$idCotizacion."&codigo=".$codigoCotizacion."'\">Descargar</button></div>
<div align='center' style='width: 25%'><button type='button' class='btn btn-secondary' onclick=\"location.href='http://127.0.0.1/GH%20Medic%20V1/cliente/cotizacion.php?id=".$idCotizacion."&codigo=".$codigoCotizacion."'\">Ver cotización</button></div>
<div align='center' style='width: 25%'><button type='button' class='btn btn-primary' onclick=\"location.href='http://127.0.0.1/GH%20Medic%20V1/cliente/chat.php?id=".$idCotizacion."&codigo=".$codigoCotizacion."'\">Enviar mensaje</button></div>
<div align='center' style='width: 25%'><button type='button' class='btn btn-success' onclick=\"location.href='http://127.0.0.1/GH%20Medic%20V1/cliente/aceptar.php?id=".$idCotizacion."&codigo=".$codigoCotizacion."'\">Aceptar</button></div>
</div>
<br><br>
<div class='box'>Si lo deseas, puedes contactar al agente de ventas por medio de whatsapp:</div>
<div class='box'>
  <a href='https://api.whatsapp.com/send?phone=+52".$telefono."' target='_blank'><img src='http://erpghmedic.com.mx/img/whatsapp-logo.png'  /></a>
</div>
<hr>
<center><img src='http://erpghmedic.com.mx/img/Logo-transparente.png' width='15%' /></center>";
//<a href='http://erpghmedic.com.mx/index.php?id=".$idUsuario."&codigo=".$codigoBD."' >Timlid - Activar cuenta</a></p>
?>