$(document).ready(function () {

  var table = $("#tblCambiosDetalle").DataTable();

  if(table.rows().count() === 0){
    $("#btnCambiar").prop("disabled", true);
  }else{
    $("#btnCambiar").prop("disabled", false);
  }

  $("#tblCambiosDetalle").DataTable().destroy();
  cargarCambiosDetalle();

  $("#btnBusqueda").on("click", function () {
    var valor = $("#txtBusqueda").val();
    var tipo_cambio = $("#inputTipoCambio").val();

    if (tipo_cambio == 1) {
      Swal.fire({
        title: "Búsqueda de productos",
        html:
          "<div class='table-responsive'>" +
          "<table class='table' id='tblBusquedaCambios' class='display' cellspacing='0' width='100%'>" +
          "<thead>" +
          "<tr>" +
          "<th class='d-none'>IdExistencia</th>" +
          "<th class='d-none'>IdProducto</th>" +
          
          "<th class='d-none'>LoteProducto</th>" +
          "<th class='d-none'>CaducidadProducto</th>" +
          "<th width='100px'>Clave</th>" +
          "<th width='100px'>Nombre</th>" +
          "<th>Lote</th>" +
          "<th width='50px'>Existencia</th>" +
          "</tr>" +
          "</thead>" +
          "</table>" +
          "</div>",
        showCancelButton: false,
        showConfirmButton: false,
        willClose: () => {
          limpiarInputBusqueda();
        },
      });
    } else {
      Swal.fire({
        title: "Búsqueda de productos",
        html:
          "<div class='table-responsive'>" +
          "<table class='table' id='tblBusquedaCambios' class='display' cellspacing='0' width='100%'>" +
          "<thead>" +
          "<tr>" +
          "<th class='d-none'>IdExistencia</th>" +
          "<th class='d-none'>IdProducto</th>" +
          
          "<th class='d-none'>LoteProducto</th>" +
          "<th class='d-none'>CaducidadProducto</th>" +
          "<th width='100px'>Clave</th>" +
          "<th width='100px'>Nombre</th>" +
          "<th>Serie</th>" +
          "<th width='50px'>Existencia</th>" +
          "</tr>" +
          "</thead>" +
          "</table>" +
          "</div>",
        showCancelButton: false,
        showConfirmButton: false,
        willClose: () => {
          limpiarInputBusqueda();
        },
      });
    }

    $("#tblBusquedaCambios").DataTable().destroy();
    if (tipo_cambio == 1) {
      cargarBusquedaCambioLote(valor);
    } else {
      cargarBusquedaCambioSerie(valor);
    }
  });

  $("#btnCambiar").on("click", function () {
    setTimeout(function () {
      var tipo_cambio = $("#inputTipoCambio").val();
      if (tipo_cambio == 1) {
        insertarCambioLote();
      } else {
        insertarCambioSerie();
      }
    }, 200);

  }); 

});

function gif(){
  $("#loader").css("display", "block");
}

function buscar(event) {
  if (event.keyCode == 13) {
    var valor = $("#txtBusqueda").val();
    var tipo_cambio = $("#inputTipoCambio").val();

    if (tipo_cambio == 1) {
      Swal.fire({
        title: "Búsqueda de productos",
        html:
        "<div class='table-responsive'>" +
        "<table class='table' id='tblBusquedaCambios' class='display' cellspacing='0' width='100%'>" +
        "<thead>" +
        "<tr>" +
        "<th class='d-none'>IdExistencia</th>" +
        "<th class='d-none'>IdProducto</th>" +
      
        "<th class='d-none'>LoteProducto</th>" +
        "<th class='d-none'>CaducidadProducto</th>" +
        "<th width='100px'>Clave</th>" +
        "<th width='100px'>Nombre</th>" +
        "<th>Lote</th>" +
        "<th width='50px'>Existencia</th>" +
        "</tr>" +
        "</thead>" +
        "</table>" +
        "</div>",
        showCancelButton: false,
        showConfirmButton: false,
        willClose: () => {
          limpiarInputBusqueda();
        },
      });
    } else {
      Swal.fire({
        title: "Búsqueda de productos",
        html:
        "<div class='table-responsive'>" +
        "<table class='table' id='tblBusquedaCambios' class='display' cellspacing='0' width='100%'>" +
        "<thead>" +
        "<tr>" +
        "<th class='d-none'>IdExistencia</th>" +
        "<th class='d-none'>IdProducto</th>" +
        
        "<th class='d-none'>LoteProducto</th>" +
        "<th class='d-none'>CaducidadProducto</th>" +
        "<th width='100px'>Clave</th>" +
        "<th width='100px'>Nombre</th>" +
        "<th>Serie</th>" +
        "<th width='50px'>Existencia</th>" +
        "</tr>" +
        "</thead>" +
        "</table>" +
        "</div>",
        showCancelButton: false,
        showConfirmButton: false,
        willClose: () => {
          limpiarInputBusqueda();
        },
      });
    }

    $("#tblBusquedaCambios").DataTable().destroy();
    if (tipo_cambio == 1) {
      cargarBusquedaCambioLote(valor);
    } else {
      cargarBusquedaCambioSerie(valor);
    }
  }
}

function limpiarInputBusqueda() {
  $("#txtBusqueda").val("");
}

function cargarBusquedaCambioSerie(valor) {
  var sucursal = $("#inputSucursal").val();

  var table = $("#tblBusquedaCambios")
    .DataTable({
      language: setFormatDatatablesTblBusquedaCambios(),
      dom: "<'row'<'col pt-5'l><'col pt-5'f>>rtip",
      buttons: [],
      scrollX: false,
      lengthChange: true,
      info: false,
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_BusquedaCambioSerie",
          data1: sucursal,
          data2: valor,
        },
      },
      pageLength: 15,
      paging: true,
      order: [],
      columns: [
        { data: "IdExistencia" },
        { data: "IdProducto" },
       
        { data: "LoteProducto" },
        { data: "CaducidadProducto" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Serie" },
        { data: "Existencia" },
      ],
      columnDefs: [
        { orderable: false, targets: [0, 1, 2, 3, 4], visible: false },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });
    });

  $("#tblBusquedaCambios tbody").on("click", "tr", function () {
    var rowData = table.row(this).data();

    var result = "";
    var characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < 5; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    var id_existencia = result;
    var id_producto = rowData.IdProducto;

    if (rowData.Existencia === "") {
      var existencia = 0;
    } else {
      var existencia = rowData.Existencia;
    }

    var clave = rowData.Clave;
    var pos = clave.indexOf(">");
    var clave2 = clave.slice(pos + 1);

    var nombre = rowData.Nombre;
    var pos2 = nombre.indexOf(">");
    var nombre2 = nombre.slice(pos2 + 1);

    if (rowData.Lote) {
      var lote = rowData.Lote;
    } else {
      var lote = "";
    }

    if (rowData.Serie) {
      var serie = rowData.Serie;
    } else {
      var serie = "";
    }

    if (rowData.Caducidad === "") {
      var caducidad = "0000-00-00";
    } else {
      var caducidad = rowData.Caducidad;
    }

    var cantidad =
      '<input class="form-control" type="number" min="0" id="cant_' +
      id_existencia +
      '" onchange="validarCantidad(this)">';

    var inputIdExistencia =
      '<input type="hidden" id="idEx_' +
      id_existencia +
      '" value="' +
      id_existencia +
      '">';
    var inputIdProducto =
      '<input type="hidden" id="idPro_' +
      id_existencia +
      '" value="' +
      id_producto +
      '">';
    var inputClave =
      '<input type="hidden" id="clave_' +
      id_existencia +
      '" value="' +
      clave2 +
      '">';
    /*var inputSerie =
      '<input type="hidden" id="ser_' +
      id_existencia +
      '" value="' +
      serie +
      '">';*/
    var inputLote =
      '<input type="hidden" id="lot_' +
      id_existencia +
      '" value="' +
      lote +
      '">';
    var inputCaducidad =
      '<input type="hidden" id="fech_' +
      id_existencia +
      '" value="' +
      caducidad +
      '">';
    var inputExistencia =
      '<input type="hidden" id="ex_' +
      id_existencia +
      '" value="' +
      existencia +
      '">';
    var inputLoteSerie =
      '<input class="form-control" type="text" id="lotser_' +
      id_existencia +
      '_' +
      id_producto +
      '" onchange="ValidarUnicoLote(this)">';
    var inputObservacion =
      '<textarea class="form-control not-rezise" id="obs_' + id_existencia + '" style="height:33px"></textarea>';

    var accionAgregar =
      '<img id="btnAgregar_' +
      id_existencia +
      '" width="15px" height="15px" src="../../../../img/timdesk/ICONO AGREGAR3.svg" data-toggle="tooltip" data-placement="top" title="Agregar"><br>';
    var accionEliminar =
      '<img class="mt-2" id="btnEliminar_' +
      id_existencia +
      '" width="15px" height="15px" src="../../../../img/inventarios/delete.svg" data-toggle="tooltip" data-placement="top" title="Eliminar">';

    if (caducidad == "0000-00-00") {
      caducidad = "";
    }

    var table2 = $("#tblCambiosDetalle").DataTable();

      table2.row
        .add({
          IdExistencia: id_existencia,
          IdProducto: id_producto,
          ClaveProducto: clave2,
          Nombre:
            inputIdExistencia +
            inputIdProducto +
            inputClave +
            inputLote +
            //inputSerie +
            inputCaducidad +
            inputExistencia +
            '<p>' +
            nombre2 +
            '</p>',
          Serie: serie,
          Lote: lote,
          Caducidad: caducidad,
          Existencia: existencia,
          Cantidad: cantidad,
          LoteSerie: inputLoteSerie,
          Observaciones: inputObservacion,
          Acciones: accionAgregar + accionEliminar,
        })
        .draw();

      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });

      $('.tooltip').tooltip('hide');

    swal.close();

    setTimeout(function(){
      var table = $("#tblCambiosDetalle").DataTable();

      if(table.rows().count() === 0){
        $("#btnCambiar").prop("disabled", true);
      }else{
        $("#btnCambiar").prop("disabled", false);
      }
    }, 500);

  });
}
function cargarBusquedaCambioLote(valor) {
  var sucursal = $("#inputSucursal").val();

  var table = $("#tblBusquedaCambios")
    .DataTable({
      language: setFormatDatatablesTblBusquedaCambios(),
      dom: "<'row'<'col pt-5'l><'col pt-5'f>>rtip",
      buttons: [],
      scrollX: false,
      lengthChange: true,
      info: false,
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_BusquedaCambioLote",
          data1: sucursal,
          data2: valor,
        },
      },
      pageLength: 15,
      paging: true,
      order: [],
      columns: [
        { data: "IdExistencia" },
        { data: "IdProducto" },
       
        { data: "LoteProducto" },
        { data: "CaducidadProducto" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Lote" },
        { data: "Existencia" },
      ],
      columnDefs: [
        { orderable: false, targets: [0, 1, 2, 3, 4], visible: false },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });
    });

  $("#tblBusquedaCambios tbody").on("click", "tr", function () {
    var rowData = table.row(this).data();

    var result = "";
    var characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < 5; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    var id_existencia = result;
    var id_producto = rowData.IdProducto;

    if (rowData.Existencia === "") {
      var existencia = 0;
    } else {
      var existencia = rowData.Existencia;
    }

    var clave = rowData.Clave;
    var pos = clave.indexOf(">");
    var clave2 = clave.slice(pos + 1);

    var nombre = rowData.Nombre;
    var pos2 = nombre.indexOf(">");
    var nombre2 = nombre.slice(pos2 + 1);

    if (rowData.Lote) {
      var lote = rowData.Lote;
    } else {
      var lote = "";
    }

    if (rowData.Serie) {
      var serie = rowData.Serie;
    } else {
      var serie = "";
    }

    if (rowData.Caducidad === "") {
      var caducidad = "0000-00-00";
    } else {
      var caducidad = rowData.Caducidad;
    }

    var cantidad =
      '<input class="form-control" type="number" min="0" id="cant_' +
      id_existencia +
      '" onchange="validarCantidad(this)">';

    var inputIdExistencia =
      '<input type="hidden" id="idEx_' +
      id_existencia +
      '" value="' +
      id_existencia +
      '">';
    var inputIdProducto =
      '<input type="hidden" id="idPro_' +
      id_existencia +
      '" value="' +
      id_producto +
      '">';
    var inputClave =
      '<input type="hidden" id="clave_' +
      id_existencia +
      '" value="' +
      clave2 +
      '">';
    /*var inputSerie =
      '<input type="hidden" id="ser_' +
      id_existencia +
      '" value="' +
      serie +
      '">';*/
    var inputLote =
      '<input type="hidden" id="lot_' +
      id_existencia +
      '" value="' +
      lote +
      '">';
    var inputCaducidad =
      '<input type="hidden" id="fech_' +
      id_existencia +
      '" value="' +
      caducidad +
      '">';
    var inputExistencia =
      '<input type="hidden" id="ex_' +
      id_existencia +
      '" value="' +
      existencia +
      '">';
    var inputLoteSerie =
      '<input class="form-control" type="text" id="lotser_' +
      id_existencia +
      '_' +
      id_producto +
      '" onchange="ValidarUnicoLote(this)">';
    var inputObservacion =
      '<textarea class="form-control not-rezise" id="obs_' + id_existencia + '" style="height:33px"></textarea>';

    var accionAgregar =
      '<img id="btnAgregar_' +
      id_existencia +
      '" width="15px" height="15px" src="../../../../img/timdesk/ICONO AGREGAR3.svg" data-toggle="tooltip" data-placement="top" title="Agregar"><br>';
    var accionEliminar =
      '<img class="mt-2" id="btnEliminar_' +
      id_existencia +
      '" width="15px" height="15px" src="../../../../img/inventarios/delete.svg" data-toggle="tooltip" data-placement="top" title="Eliminar">';

    if (caducidad == "0000-00-00") {
      caducidad = "";
    }

    var table2 = $("#tblCambiosDetalle").DataTable();

      table2.row
        .add({
          IdExistencia: id_existencia,
          IdProducto: id_producto,
          ClaveProducto: clave2,
          Nombre:
            inputIdExistencia +
            inputIdProducto +
            inputClave +
            inputLote +
            //inputSerie +
            inputCaducidad +
            inputExistencia +
            '<p>' +
            nombre2 +
            '</p>',
          Serie: serie,
          Lote: lote,
          Caducidad: caducidad,
          Existencia: existencia,
          Cantidad: cantidad,
          LoteSerie: inputLoteSerie,
          Observaciones: inputObservacion,
          Acciones: accionAgregar + accionEliminar,
        })
        .draw();

      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });

      $('.tooltip').tooltip('hide');

    swal.close();

    setTimeout(function(){
      var table = $("#tblCambiosDetalle").DataTable();

      if(table.rows().count() === 0){
        $("#btnCambiar").prop("disabled", true);
      }else{
        $("#btnCambiar").prop("disabled", false);
      }
    }, 500);

  });
}

function cargarCambiosDetalle() {
  var table = $("#tblCambiosDetalle").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: false,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 1, visible: false },
    ],
    columns: [
      { data: "IdExistencia" },
      { data: "IdProducto" },
      { data: "ClaveProducto" },
      { data: "Nombre" },
      
      { data: "Lote" },
      { data: "Caducidad" },
      { data: "Existencia" },
      { data: "Cantidad" },
      { data: "LoteSerie" },
      { data: "Observaciones" },
      { data: "Acciones" },
    ],
  });

  table.columns.adjust().draw();

  $("#tblCambiosDetalle tbody").on("click", "img", function () {
    if($(this).attr("id").startsWith("btnAgregar") === true){
      $('.tooltip').tooltip('hide');
      var rowData = table.row($(this).parents("tr")).data()
      var result = "";
      var characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      var charactersLength = characters.length;
      for (var i = 0; i < 5; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      var id_existencia = result;
      var id_producto = rowData.IdProducto;
  
      if (rowData.Existencia === "") {
        var existencia = 0;
      } else {
        var existencia = rowData.Existencia;
      }
  
      var clave = rowData.ClaveProducto;
  
      var nombre = rowData.Nombre;
      var pos = nombre.indexOf('<p>');
      var pos2 = nombre.indexOf('</p>');
      var nombre2 = nombre.slice(pos + 3, pos2);
      console.log(nombre);
  
      if (rowData.Lote) {
        var lote = rowData.Lote;
      } else {
        var lote = "";
      }
  
      if (rowData.Serie) {
        var serie = rowData.Serie;
      } else {
        var serie = "";
      }
  
      if (rowData.Caducidad === "") {
        var caducidad = "0000-00-00";
      } else {
        var caducidad = rowData.Caducidad;
      }
  
      var cantidad =
        '<input class="form-control" type="number" min="0" id="cant_' +
        id_existencia +
        '" onchange="validarCantidad(this)">';
  
      var inputIdExistencia =
        '<input type="hidden" id="idEx_' +
        id_existencia +
        '" value="' +
        id_existencia +
        '">';
      var inputIdProducto =
        '<input type="hidden" id="idPro_' +
        id_existencia +
        '" value="' +
        id_producto +
        '">';
      var inputClave =
        '<input type="hidden" id="clave_' +
        id_existencia +
        '" value="' +
        clave +
        '">';
      /*var inputSerie =
        '<input type="hidden" id="ser_' +
        id_existencia +
        '" value="' +
        serie +
        '">';*/
      var inputLote =
        '<input type="hidden" id="lot_' +
        id_existencia +
        '" value="' +
        lote +
        '">';
      var inputCaducidad =
        '<input type="hidden" id="fech_' +
        id_existencia +
        '" value="' +
        caducidad +
        '">';
      var inputExistencia =
        '<input type="hidden" id="ex_' +
        id_existencia +
        '" value="' +
        existencia +
        '">';
      var inputLoteSerie =
        '<input class="form-control" type="text" id="lotser_' +
        id_existencia +
        '_' +
        id_producto +
        '" onchange="ValidarUnicoLote(this)">';
      var inputObservacion =
        '<textarea class="form-control not-rezise" id="obs_' + id_existencia + '" style="height:33px"></textarea>';
  
      var accionAgregar =
        '<img id="btnAgregar_' +
        id_existencia +
        '" width="15px" height="15px" src="../../../../img/timdesk/ICONO AGREGAR3.svg" data-toggle="tooltip" data-placement="top" title="Agregar"><br>';
      var accionEliminar =
        '<img class="mt-2" id="btnEliminar_' +
        id_existencia +
        '" width="15px" height="15px" src="../../../../img/inventarios/delete.svg" data-toggle="tooltip" data-placement="top" title="Eliminar">';
  
      if (caducidad == "0000-00-00") {
        caducidad = "";
      }
  
      
  
        table.row
          .add({
            IdExistencia: id_existencia,
            IdProducto: id_producto,
            ClaveProducto: clave,
            Nombre:
              inputIdExistencia +
              inputIdProducto +
              inputClave +
              inputLote +
              //inputSerie +
              inputCaducidad +
              inputExistencia +
              '<p>' +
              nombre2 +
              '</p>',
           
            Lote: lote,
            Caducidad: caducidad,
            Existencia: existencia,
            Cantidad: cantidad,
            LoteSerie: inputLoteSerie,
            Observaciones: inputObservacion,
            Acciones: accionAgregar + accionEliminar,
          })
          .draw();
    }else{
      table.row($(this).parents("tr")).remove().draw();
      $('.tooltip').tooltip('hide');
      setTimeout(function(){
        if(table.rows().count() === 0){
          $("#btnCambiar").prop("disabled", true);
        }else{
          $("#btnCambiar").prop("disabled", false);
        }
      }, 500);
    }
    
  });

}

function ValidarUnicoLote(item) {
  
  var lotser = $(item).val();
  var id_detalle = $(item).attr("id").split("_")[1];
  var tipo_cambio = $("#inputTipoCambio").val();
  var sucursal = $("#inputSucursal").val();
  var clave = $("#clave_" + id_detalle).val();

  if(tipo_cambio == 1){
    var tipo = 'lote';
  }else{
    var tipo = 'serie';
  }
  var clave = $("#clave_" + id_detalle).val();
  
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_ValidarUnicoLote",
      data1: clave,
      data2: sucursal,
      data3: tipo,
      data4: lotser
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta de validar el lote o serie: ",
        respuesta[0][0]
      );
      if (
        respuesta[0][0] == 1 && 
        tipo_cambio == 1 &&
        lotser != ''
        ) {
        $("#invalid_lot_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_lot_" +
            id_detalle +
            "'>Este lote ya existe</div>"
        ).insertAfter($(item));
      } else if(
        respuesta[0][0] == 0 && 
        tipo_cambio == 1 &&
        lotser != ''
      ){
        $("#invalid_lot_" + id_detalle).remove();
      }
      else if(
        respuesta[0][0] == 1 && 
        tipo_cambio == 0 &&
        lotser != ''
      ){
        $("#invalid_ser_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_ser_" +
            id_detalle +
            "'>Esta serie ya existe</div>"
        ).insertAfter($(item));
      }
      else if(
        respuesta[0][0] == 0 && 
        tipo_cambio == 0 &&
        lotser != ''
      ){
        $("#invalid_ser_" + id_detalle).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  var campo = $(item).attr("id").split("_")[0];
  var id_detalle = $(item).attr("id").split("_")[1];
  var tipo_cambio = $("#inputTipoCambio").val();
  
  $("table input[type=text]").each(function () {
    
    if (
      $(this).attr("id") != $(item).attr("id") &&
      $(this).val() == $(item).val() &&
      $(this).val() &&
      $(this).attr("id").split("_")[2] == $(item).attr("id").split("_")[2]
    ) {
      if (tipo_cambio == 0) {
        $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_insercion_" +
            campo +
            "_" +
            id_detalle +
            "'>No repitas series</div>"
        ).insertAfter($(item));
        return false;
      } else {
        $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_insercion_" +
            campo +
            "_" +
            id_detalle +
            "'>No repitas lotes</div>"
        ).insertAfter($(item));
        return false;
      }
    } else {
      $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
    }
  });

  if($(item).val()){
    var id = $(item).attr("id").split("_")[1];
    var campo = $(item).attr("id").split("_")[0];
    $("#invalid_entry_" + id + "_" + campo).remove();
    $(item).removeClass("is-invalid");
  }


}

function validarRepeAjusInv(item) {
  
  var campo = $(item).attr("id").split("_")[0];
  var id_detalle = $(item).attr("id").split("_")[1];
  var tipo_cambio = $("#inputTipoCambio").val();
  
  $("table input[type=text]").each(function () {
    
    if (
      $(this).attr("id") != $(item).attr("id") &&
      $(this).val() == $(item).val() &&
      $(this).val() &&
      $(this).attr("id").split("_")[2] == $(item).attr("id").split("_")[2]
    ) {
      if (tipo_cambio == 0) {
        $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_insercion_" +
            campo +
            "_" +
            id_detalle +
            "'>No repitas series</div>"
        ).insertAfter($(item));
        return false;
      } else {
        $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_insercion_" +
            campo +
            "_" +
            id_detalle +
            "'>No repitas lotes</div>"
        ).insertAfter($(item));
        return false;
      }
    } else {
      $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
    }
  });

  if($(item).val()){
    var id = $(item).attr("id").split("_")[1];
    var campo = $(item).attr("id").split("_")[0];
    $("#invalid_entry_" + id + "_" + campo).remove();
    $(item).removeClass("is-invalid");
  }


}

function validarCantidad(item) {
  var sucursal = $("#inputSucursal").val();

  var id_detalle = $(item).attr("id").split("_")[1];
  var clave = $("#clave_" + id_detalle).val();
  if (!$("#ser_" + id_detalle).val()) {
    var serie = "";
  } else {
    var serie = $("#ser_" + id_detalle).val();
  }
  if (!$("#lot_" + id_detalle).val()) {
    var lote = "";
  } else {
    var lote = $("#lot_" + id_detalle).val();
  }
  if(!$(item).val()){
    var cantidad = 0;
  }else{
    var cantidad = $(item).val();
  }

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_ValidacionExistenciaCambioLoteSerie",
      data1: sucursal,
      data2: clave,
      data3: serie,
      data4: lote,
      data5: cantidad,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta de validar la cantidad con la existencia: ",
        respuesta
      );
      if (respuesta == 1) {
        $("#invalid_cant_" + id_detalle).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_cant_" +
            id_detalle +
            "'>Existencia insuficiente</div>"
        ).insertAfter($("#cant_" + id_detalle));
      } else {
        $("#invalid_cant_" + id_detalle).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  if($(item).val()){
    var id = $(item).attr("id").split("_")[1];
    var campo = $(item).attr("id").split("_")[0];
    $("#invalid_entry_" + id + "_" + campo).remove();
    $(item).removeClass("is-invalid");
  }

}

function desaparecerTooltip(item) {
  $(item).tooltip("hide");
}

function checarNoVacio(item){

  var id = $(item).attr("id").split("_")[1];
  var campo = $(item).attr("id").split("_")[0];
    $("#invalid_entry_" + id + "_" + campo).remove();
  $(item).removeClass("is-invalid");

}

function descargarPDF() {
  var cambio = 0;
  cambio = $("#inputCambio").val();
  window.location.href =
            "funciones/descargar_Cambio.php?data=" + cambio;
}

function insertarCambioSerie() {

  var valor = 1;

  $("table input[type=text],input[type=number]").each(
    function () {
      if (!$(this).val()) {
        valor = 0;
        $(this).addClass("is-invalid");
        var id = $(this).attr("id").split("_")[1];
        var campo = $(this).attr("id").split("_")[0];
        $("#invalid_entry_" + id + "_" + campo).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_entry_" +
            id +
            "_" + 
            campo +
            "'>Campo obligatorio</div>"
        ).insertAfter($(this).next());
      }
    }
  );

  var sucursal = $("#inputSucursal").val();

  if(valor == 1){
    if (!$("div").hasClass("invalid-feedback")) {

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_Cambios",
          data1: sucursal,
          data2: 'serie',
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta de insertar un cambio: ", respuesta);
        },
        error: function (error) {
          console.log(error);
        },
      });
      
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_IdCambio",
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta del id del cambio: ", respuesta);
          $("#inputCambio").val(respuesta.id);
          $("#inputTipoCambio").val(respuesta.tipo_cambio);
          $("#inputFolioCambio").val(respuesta.folio);
        },
        error: function (error) {
          console.log(error);
        },
      });


      var cambio = 0;
      cambio = $("#inputCambio").val();
      var folio_cambio = 0;
      folio_cambio = $("#inputFolioCambio").val();  
    
      $("input[type=number]").each(function () {
        var id_detalle = $(this).attr("id").split("_")[1];
        var id_producto = $("#idPro_" + id_detalle).val();
        var existencia = $("#ex_" + id_detalle).val();
        var cantidad = $(this).val();
        var clave = $("#clave_" + id_detalle).val();
        if ($("#ser_" + id_detalle).val()) {
          var serieantigua = $("#ser_" + id_detalle).val();
        } else {
          var serieantigua = "";
        }
        console.log(serieantigua);
        if ($("#fech_" + id_detalle).val()) {
          var caducidad = $("#fech_" + id_detalle).val();
        } else {
          var caducidad = "0000-00-00";
        }
        console.log(caducidad);
        var serienueva = $("#lotser_" + id_detalle + "_" + id_producto).val();
        var observaciones = $("#obs_" + id_detalle).val();

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "save_data",
              funcion: "save_Cambiar",
              data1: cambio,
              data2: id_producto,
              data3: existencia,
              data4: cantidad,
              data5: clave,
              data6: '',
              data7: serieantigua,
              data8: '',
              data9: serienueva,
              data10: caducidad,
              data11: observaciones,
            },
            dataType: "json",
            async: false,
            success: function (respuesta) {
              console.log("respuesta de hacer el cambio de serie: ", respuesta);
            },
            error: function (error) {
              console.log(error);
            },
          });
        
      });
    
      Swal.fire({
        title: "Cambio exitoso",
        icon: "success",
        html:
          "<label>El cambio de folio: " +
          folio_cambio +
          " ha sido realizado exitosamente</label>" +
          "<br><br>" +
          "<button  class='btn-custom btn-custom--border-blue' id='btn-descargar' onclick='descargarPDF()'>Descargar PDF</button>",
        width: "600px",
        showConfirmButton: true,
        confirmButtonText: "Aceptar",
        backdrop: false,
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "../cambio_lote/";
          
        }
      });

      $("#loader").css("display", "none");

    } else {
      $("#loader").css("display", "none");
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 2000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "!Datos inválidos¡",
        sound: '../../../../../sounds/sound4'
      });
    }
  } else {
    $("#loader").css("display", "none");
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "Completa los siguientes campos",
      sound: '../../../../../sounds/sound4'
    });
  }
}

function insertarCambioLote() {

  var valor = 1;

  $("table input[type=text],input[type=number]").each(
    function () {
      if (!$(this).prop("disabled") && !$(this).val()) {
        valor = 0;
        $(this).addClass("is-invalid");
        var id = $(this).attr("id").split("_")[1];
        var campo = $(this).attr("id").split("_")[0];
        $("#invalid_entry_" + id + "_" + campo).remove();
        $(
          "<div class='invalid-feedback d-block' id='invalid_entry_" +
            id +
            "_" + 
            campo +
            "'>Campo obligatorio</div>"
        ).insertAfter($(this).next());
      }
    }
  );

  var sucursal = $("#inputSucursal").val();

  if(valor == 1){
    if (!$("div").hasClass("invalid-feedback")) {

      $("#loader").css("display", "block");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_Cambios",
          data1: sucursal,
          data2: 'lote',
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta de insertar un cambio: ", respuesta);
        },
        error: function (error) {
          console.log(error);
        },
      });
      
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_IdCambio",
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta del id del cambio: ", respuesta);
          $("#inputCambio").val(respuesta.id);
          $("#inputTipoCambio").val(respuesta.tipo_cambio);
          $("#inputFolioCambio").val(respuesta.folio);
        },
        error: function (error) {
          console.log(error);
        },
      });

      var cambio = 0;
      cambio = $("#inputCambio").val();
      var folio_cambio = 0;
      folio_cambio = $("#inputFolioCambio").val();

      $("input[type=number]").each(function () {
        var id_detalle = $(this).attr("id").split("_")[1];
        var id_producto = $("#idPro_" + id_detalle).val();
        var existencia = $("#ex_" + id_detalle).val();
        var cantidad = $(this).val();
        var clave = $("#clave_" + id_detalle).val();
        if ($("#lot_" + id_detalle).val()) {
          var loteantiguo = $("#lot_" + id_detalle).val();
        } else {
          var loteantiguo = "";
        }
        console.log(loteantiguo);
        if ($("#fech_" + id_detalle).val()) {
          var caducidad = $("#fech_" + id_detalle).val();
        } else {
          var caducidad = "0000-00-00";
        }
        console.log(caducidad);
        var lotenuevo = $("#lotser_" + id_detalle + "_" + id_producto).val();
        var observaciones = $("#obs_" + id_detalle).val();
          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "save_data",
              funcion: "save_Cambiar",
              data1: cambio,
              data2: id_producto,
              data3: existencia,
              data4: cantidad,
              data5: clave,
              data6: loteantiguo,
              data7: '',
              data8: lotenuevo,
              data9: '',
              data10: caducidad,
              data11: observaciones,
            },
            dataType: "json",
            async: false,
            success: function (respuesta) {
              console.log("respuesta de hacer el cambio de lote: ", respuesta);
            },
            error: function (error) {
              console.log(error);
            },
          });
        
      });

      $("#loader").css("display", "none");
    
      Swal.fire({
        title: "Cambio exitoso",
        icon: "success",
        html:
          "<label>El cambio de folio: " +
          folio_cambio +
          " ha sido realizado exitosamente</label>" +
          "<br><br>" +
          "<button  class='btn-custom btn-custom--border-blue' id='btn-descargar' onclick='descargarPDF()'>Descargar PDF</button>",
        width: "600px",
        showConfirmButton: true,
        confirmButtonText: "Aceptar",
        reverseButtons: false,
        backdrop: false,
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue btn-aceptar"
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "../cambio_lote/";
        }
      });

    } else {
      $("#loader").css("display", "none");
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 2000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "!Datos inválidos¡",
        sound: '../../../../../sounds/sound4'
      });
    }
  } else {
    $("#loader").css("display", "none");
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "Completa los siguientes campos",
      sound: '../../../../../sounds/sound4'
    });
  }
}

function setFormatDatatablesTblBusquedaCambios() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "No hay coincidencias de la búsqueda",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    lengthMenu: `Mostrar: 
                  <select class="mb-n2 mt-1">
                      <option value="20">20</option>
                      <option value="100">100</option>
                      <option value="200">200</option>
                  <select>`,
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    lengthMenu: `Mostrar: 
                  <select class="mb-n2 mt-1">
                      <option value="20">20</option>
                      <option value="100">100</option>
                      <option value="200">200</option>
                  <select>`,
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}