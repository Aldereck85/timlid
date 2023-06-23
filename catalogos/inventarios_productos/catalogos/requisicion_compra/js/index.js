//función para mostrar la notificación generada en otra pantalla (registro, actualizacion u eliminacion de RC)
function notoficacion_RC(){
    var notifi = $("#notifi").val();
    var m;
    switch (notifi) {
      case "1":
        m = "¡Requisición registrada con exito!";
        break;
      case "2":
        m = "¡Requisición actualizada con exito!"
        break;
      case "3":
        m = "¡Requisición eliminada con exito!"
        break;
      case "4":
        m = "¡Seguimiento registrado con exito!"
    }

    if (notifi != "0") {
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: m,
        sound: '../../../../../sounds/sound2'
      });
    }

}
$(document).ready(function () {
    var topButtons = [];
    if(_permissions.add==1){
        topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
            className: "btn-custom--white-dark",
            action: function () {
                window.location.href = "agregar_Requisicion.php";
            },
        });
    }

    if(_permissions.export==1){
        topButtons.push({
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          });
    }

    var filtro = "";
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
      var estatus = data[7]; // informacion del estado de la cotizacion

      if (filtro == "") {
        return true;
      }
  
      if (estatus == filtro) {
        return true;
      } else {
        return false;
      }
    });

    tblRequisiciones = $("#tblRequisicionesCompra").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: topButtons,
        },
        ajax: {
          url: "php/functions.php",
          data: {
            clase: "get_data",
            funcion: "get_requisicionesTable",
            data: _permissions.edit,
            data2: _permissions.delete,
          },
        },
        columns: [
          { data: "Id" },
          { data: "folio" },
          { data: "f emision" },
          { data: "f estimada" },
          { data: "comprador" },
          { data: "aplicado por" },
          { data: "area" },
          { data: "estado" },
          { data: "Acciones", width: "5%" },
        ],
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
        ],
      });

    new $.fn.dataTable.Buttons(tblRequisiciones, {
    dom: {
        button: {
        tag: "button",
        className: "btn-table-custom",
        },
        buttonLiner: {
        tag: null,
        },
    },
    buttons: [
        {
        text: '<i class="fas fa-globe"></i> Todas',
        className: "btn-table-custom--blue",
        action: function (e, dt, node, config) {
            filtro = "";
            $("#tblRequisicionesCompra").DataTable().draw();
        },
        },
        {
        text: '<i class="fas fa-clock"></i> Pendiente',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
            filtro = "Pendiente";
            $("#tblRequisicionesCompra").DataTable().draw();
            filtro = "";
        },
        },
        {
        text: '<i class="fas fa-archive"></i> Parcialmente colocada',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
            filtro = "Parcialmente colocada";
            $("#tblRequisicionesCompra").DataTable().draw();
            filtro = "";
        },
        },
        {
        text: '<i class="fas fa-check-circle"></i> Colocada completa',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
            filtro = "Colocada completa";
            $("#tblRequisicionesCompra").DataTable().draw();
            filtro = "";
        },
        },
        {
        text: '<i class="fas fa-times"></i> Cerrada',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
            filtro = "Cerrada";
            $("#tblRequisicionesCompra").DataTable().draw();
            filtro = "";
        },
        },
        {
          text: '<i class="fas fa-trash-alt"></i> Cancelada',
          className: "btn-table-custom--red",
          action: function (e, dt, node, config) {
              filtro = "Cancelada";
              $("#tblRequisicionesCompra").DataTable().draw();
              filtro = "";
          },
          },
    ],
    });

    tblRequisiciones.buttons(1, null).container().appendTo("#btn-filters");

    
    //valida permisos
    if (_permissions.read !== 1) {
        $("#alert").modal("show");
    }

    if (_permissions.add !== 1) {
        $("#agregarPagoBTN").remove();
    }

    if (_permissions.delete !== 1) {
        $("#deletePago").remove();
    }

    if (_permissions.edit !== 1) {
        $("#editarMP").remove();
    }

    notoficacion_RC();

    //Redireccionamos al Dash cuando se oculta el modal.
    $("#alert").on("hidden.bs.modal", function (e) {
        window.location.href = "../../../dashboard.php";
    });
});