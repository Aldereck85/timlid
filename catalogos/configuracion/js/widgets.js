window.addEventListener("load", function () {
  document.addEventListener("click", function (e) {
    if (e.target.id === "guardar-widgets") {
      var wgMiFacturacion = $("#wg-mi-facturacion");
      var wgVentas = $("#wg-ventas");
      var wgVentasEjecutivo = $("#wg-ventas-ejecutivo");
      var wgCuentasPagar = $("#wg-cuentas-pagar");
      var wgCuentasCobrar = $("#wg-cuentas-cobrar");
      var wgVentasAnio = $("#wg-ventas-anio");
      var wgProyectos = $("#wg-proyectos");
      var wgCumpleanios = $("#wg-cumpleanios");
      var wgNotas = $("#wg-notas");
      var wgCalendario = $("#wg-calendario");
      var wgVentasAnioGrafica1 = $("#wg-ventas-anio-grafica-1");
      var wgVentasAnioGrafica2 = $("#wg-ventas-anio-grafica-2");
      var wgVentasMes = $("#wg-ventas-mes");
      var widgets = {
        wgMiFacturacion: wgMiFacturacion.val(),
        wgVentas: wgVentas.val(),
        wgVentasEjecutivo: wgVentasEjecutivo.val(),
        wgCuentasPagar: wgCuentasPagar.val(),
        wgCuentasCobrar: wgCuentasCobrar.val(),
        wgVentasAnio: wgVentasAnio.val(),
        wgProyectos: wgProyectos.val(),
        wgCumpleanios: wgCumpleanios.val(),
        wgNotas: wgNotas.val(),
        wgCalendario: wgCalendario.val(),
        wgVentasAnioGrafica1: wgVentasAnioGrafica1.val(),
        wgVentasAnioGrafica2: wgVentasAnioGrafica2.val(),
        wgVentasMes: wgVentasMes.val(),
      };


      $.ajax({
        type: "POST",
        data: {
          clase: "save_data",
          funcion: "save_widgets",
          widgets,
        },
        dataType: "json",
        url: "php/funciones.php",
        success: function (res) {
          console.log({ res });
          if (res.status !== "success") {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: res.message,
            });
            return;
          }
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: res.message,
          });
        },
        error: function (error) {
          console.log({ error });
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });
        },
      });
    }
  });
});
