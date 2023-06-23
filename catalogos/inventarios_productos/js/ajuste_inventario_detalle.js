$(document).ready(function () {

  var table = $("#tblAjustesDetalle").DataTable();

  if(table.rows().count() === 0){
    $("#btnAjustar").prop("disabled", true);
  }else{
    $("#btnAjustar").prop("disabled", false);
  }

  $("#tblAjustesDetalle").DataTable().destroy();
  cargarAjustesDetalle();

  $("#btnBusqueda").on("click", function () {
    var valor = $("#txtBusqueda").val();
    var tipo_ajuste = $("#inputTipoAjuste").val();

    if (tipo_ajuste == 1) {
      Swal.fire({
        title: "Búsqueda de productos",
        //"<th class='d-none'>SerieProducto</th>" + line 28
        html:
          "<label>Productos que no apliquen serie, lote y caducidad no se pueden duplicar</label>" +
          "<div class='table-responsive'>" +
          "<table class='table' id='tblBusquedaAjustes' class='display' cellspacing='0' width='100%'>" +
          "<thead>" +
          "<tr>" +
          "<th class='d-none'>IdProducto</th>" +
          
          "<th class='d-none'>LoteProducto</th>" +
          "<th class='d-none'>CaducidadProducto</th>" +
          "<th width='100px'>Clave</th>" +
          "<th width='200px'>Nombre</th>" +
          "<th width='150px'>Descripción</th>" +
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
        //"<th class='d-none'>SerieProducto</th>" + 56
        //"<th>Serie</th>" + 63
        html:
          "<div class='table-responsive'>" +
          "<table class='table' id='tblBusquedaAjustes' class='display' cellspacing='0' width='100%'>" +
          "<thead>" +
          "<tr>" +
          "<th class='d-none'>IdExistencia</th>" +
          "<th class='d-none'>IdProducto</th>" +
          
          "<th class='d-none'>LoteProducto</th>" +
          "<th class='d-none'>CaducidadProducto</th>" +
          "<th width='100px'>Clave</th>" +
          "<th width='100px'>Nombre</th>" +
          "<th width='150px'>Descripción</th>" +
          
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
    }

    $("#tblBusquedaAjustes").DataTable().destroy();
    if (tipo_ajuste == 1) {
      cargarBusquedaAjustePositivo(valor);
    } else {
      cargarBusquedaAjusteNegativo(valor);
    }
  });

  $("#btnAjustar").on("click", function () {
        
    var tipo_ajuste = $("#inputTipoAjuste").val();

    if (tipo_ajuste == 1) {
      insertarAjustePositivo();
    } else {
      insertarAjusteNegativo();
    }
  });

});

function buscar(event) {
  if (event.keyCode == 13) {
    var valor = $("#txtBusqueda").val();
    var tipo_ajuste = $("#inputTipoAjuste").val();

    if (tipo_ajuste == 1) {
      Swal.fire({
        title: "Búsqueda de productos",
        //"<th class='d-none'>SerieProducto</th>" +
        html:
          "<label>Productos que no apliquen serie, lote y caducidad no se pueden duplicar</label>" +
          "<div class='table-responsive'>" +
          "<table class='table' id='tblBusquedaAjustes' class='display' cellspacing='0' width='100%'>" +
          "<thead>" +
          "<tr>" +
          "<th class='d-none'>IdProducto</th>" +
          
          "<th class='d-none'>LoteProducto</th>" +
          "<th class='d-none'>CaducidadProducto</th>" +
          "<th width='100px'>Clave</th>" +
          "<th width='200px'>Nombre</th>" +
          "<th width='150px'>Descripción</th>" +
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
        //"<th class='d-none'>SerieProducto</th>" + 141
        //"<th>Serie</th>" + 149
        html:
          "<div class='table-responsive'>" +
          "<table class='table' id='tblBusquedaAjustes' class='display' cellspacing='0' width='100%'>" +
          "<thead>" +
          "<tr>" +
          "<th class='d-none'>IdExistencia</th>" +
          "<th class='d-none'>IdProducto</th>" +
          
          "<th class='d-none'>LoteProducto</th>" +
          "<th class='d-none'>CaducidadProducto</th>" +
          "<th width='100px'>Clave</th>" +
          "<th width='100px'>Nombre</th>" +
          "<th width='150px'>Descripción</th>" +
          
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
    }

    $("#tblBusquedaAjustes").DataTable().destroy();
    if (tipo_ajuste == 1) {
      cargarBusquedaAjustePositivo(valor);
    } else {
      cargarBusquedaAjusteNegativo(valor);
    }
  }
}

function limpiarInputBusqueda() {
  $("#txtBusqueda").val("");
}

function cargarBusquedaAjusteNegativo(valor) {
  var sucursal = $("#inputSucursal").val();

  var table = $("#tblBusquedaAjustes")
    .DataTable({
      language: setFormatDatatablesTblBusquedaAjustes(),
      dom: "<'row'<'col pt-5'l><'col pt-5'f>>rtip",
      buttons: [],
      scrollX: false,
      lengthChange: true,
      info: false,
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_BusquedaAjusteNegativo",
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
        //{ data: "SerieProducto" },
        { data: "LoteProducto" },
        { data: "CaducidadProducto" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Descripcion" },
        //{ data: "Serie" },
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

  $("#tblBusquedaAjustes tbody").on("click", "tr", function () {
    var rowData = table.row(this).data();

    var id_existencia = rowData.IdExistencia;
    var id_producto = rowData.IdProducto;

    if (rowData.Existencia === "") {
      var existencia = 0;
    } else {
      var existencia = rowData.Existencia;
    }

    var clave = rowData.Clave;
    var pos = clave.indexOf(">");
    var clave2 = clave.slice(pos + 1);

    var descripcion = rowData.Descripcion;
    var nombre = rowData.Nombre;
    var pos2 = nombre.indexOf(">");
    var nombre2 = nombre.slice(pos2 + 1);

    if (rowData.Lote) {
      var lote = rowData.Lote;
    } else {
      var lote = "";
    }

    /*if (rowData.Serie) {
      var serie = rowData.Serie;
    } else {
      var serie = "";
    }*/

    if (rowData.Caducidad === "") {
      var caducidad = "0000-00-00";
    } else {
      var caducidad = rowData.Caducidad;
    }

    var existe = 0;
    $("#tblAjustesDetalle select").each(function () {
      if (id_existencia == $(this).attr("id").split("_")[1]) {
        existe = 1;
      }
    });

    var cantidad =
      '<input class="form-control" type="number" min="0" oninput="if((this.value.length) > 11) {this.value = this.value.substring(0, 11);}" id="cant_' +
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
    var inputMotivo =
    '<select id="mot_' + id_existencia + '" class="select" onchange="checarNoVacio(this)"><option data-placeholder="true"></option><option>Error de captura</option><option>Producto dañado</option><option>Resultados de inventario</option><option>Bienes robados</option><option>Otro</option></select>';
    var inputObservacion =
      '<textarea class="form-control not-rezise" id="obs_' + id_existencia + '" style="height:33px"></textarea>';

    var accionEliminar =
      '<img class="mt-2" id="btnEliminar_' +
      id_existencia +
      '" width="15px" height="15px" src="../../../../img/inventarios/delete.svg" data-toggle="tooltip" data-placement="top" title="Eliminar">';

    if (caducidad == "0000-00-00") {
      caducidad = "";
    }

    var table2 = $("#tblAjustesDetalle").DataTable();

    if (existe == 1) {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 2000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "No se puede duplicar productos",
        sound: '../../../../../sounds/sound4'
      });
    } else {
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
            nombre2,
          Descripcion: descripcion,
          //Serie: serie,
          Lote: lote,
          Caducidad: caducidad,
          Existencia: existencia,
          Cantidad: cantidad,
          Motivo: inputMotivo,
          Observaciones: inputObservacion,
          Acciones: accionEliminar,
        })
        .draw();

      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });

      $('.tooltip').tooltip('hide');

      new SlimSelect({
        select: "#mot_" + id_existencia,
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona motivo"
      });

    }

    swal.close();

    setTimeout(function(){
      var table = $("#tblAjustesDetalle").DataTable();

      if(table.rows().count() === 0){
        $("#btnAjustar").prop("disabled", true);
      }else{
        $("#btnAjustar").prop("disabled", false);
      }
    }, 500);

  });
}

function cargarBusquedaAjustePositivo(valor) {

  var table = $("#tblBusquedaAjustes")
    .DataTable({
      language: setFormatDatatablesTblBusquedaAjustes(),
      dom: "<'row'<'col pt-5'l><'col pt-5'f>>rtip",
      buttons: [],
      scrollX: false,
      lengthChange: true,
      info: false,
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_BusquedaAjustePositivo",
          data1: valor,
        },
      },
      pageLength: 15,
      paging: true,
      order: [],
      columns: [
        { data: "IdProducto" },
        //{ data: "SerieProducto" },
        { data: "LoteProducto" },
        { data: "CaducidadProducto" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Descripcion" },
      ],
      columnDefs: [
        { orderable: false, targets: [0, 1, 2, 3], visible: false },
        {className: "text-center", targets: [3]}],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });
    });

  $("#tblBusquedaAjustes tbody").on("click", "tr", function () {
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
    //var serie_producto = rowData.SerieProducto;
    var lote_producto = rowData.LoteProducto;
    var caducidad_producto = rowData.CaducidadProducto;

    var clave = rowData.Clave;
    var pos = clave.indexOf(">");
    var clave2 = clave.slice(pos + 1);

    var descripcion = rowData.Descripcion;
    var nombre = rowData.Nombre;
    var pos2 = nombre.indexOf(">");
    var nombre2 = nombre.slice(pos2 + 1);

    var table2 = $("#tblAjustesDetalle").DataTable();
    var existeClave = 0;
    var tableData = table2.rows().data();
    for (var i = 0; i < tableData.length; i++) {
      if (clave2 == tableData[i].ClaveProducto) {
        existeClave = 1;
      }
    }

    var date = new Date();
    date.setDate(date.getDate() + 1);
    var mes = date.getMonth() + 1;
    var dia = date.getDate();
    switch (dia.toString().length , mes.toString().length) {
      case 1 , 2:
        var fecha =
          date.getFullYear() +
          "-" +
          (date.getMonth() + 1) +
          "-0" +
          date.getDate();
        break;
      case 2 , 1:
        var fecha =
          date.getFullYear() +
          "-0" +
          (date.getMonth() + 1) +
          "-" +
          date.getDate();
        break;
      case 1 , 1:
        var fecha =
          date.getFullYear() +
          "-0" +
          (date.getMonth() + 1) +
          "-0" +
          date.getDate();
        break;
      case 2 , 2:
        var fecha =
          date.getFullYear() +
          "-" +
          (date.getMonth() + 1) +
          "-" +
          date.getDate();
        break;
    }

    var cantidad =
      '<input class="form-control" type="number" min="0" oninput="if((this.value.length) > 11) {this.value = this.value.substring(0, 11);}" id="cant_' +
      id_existencia +
      '" onchange="checarNoVacio(this)">';

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
      '<input type="hidden" id="ser_' + id_existencia + '" value="">';*/
    var inputLote =
      '<input type="hidden" id="lot_' + id_existencia + '" value="">';
    var inputCaducidad =
      '<input type="hidden" id="fech_' +
      id_existencia +
      '" value="0000-00-00">';
    var inputMotivo =
    '<select id="mot_' + id_existencia + '" class="select" onchange="checarNoVacio(this)"><option data-placeholder="true"></option><option>Error de captura</option><option>Producto dañado</option><option>Resultados de inventario</option><option>Bienes robados</option><option>Otro</option></select>';
    var inputObservacion =
      '<textarea class="form-control not-rezise" id="obs_' + id_existencia + '"  style="height:33px"></textarea>';

    var accionEliminar =
      '<img class="mt-2" id="btnEliminar_' +
      id_existencia +
      '" width="15px" height="15px" src="../../../../img/inventarios/delete.svg" data-toggle="tooltip" data-placement="top" title="Eliminar">';

    /*if (serie_producto == "0") {
      serie =
        '<input class="form-control" type="text" id="serie_' +
        id_existencia +
        '_' +
        id_producto +
        '" disabled>';
    } else {
      serie =
        '<input class="form-control" type="text" maxlength="55" id="serie_' +
        id_existencia +
        '_' +
        id_producto +
        '" onchange="validarRepeAjusInv(this)">';
    }*/

    if (lote_producto == "0") {
      lote =
        '<input class="form-control" type="text" id="lote_' +
        id_existencia +
        '_' +
        id_producto +
        '" disabled>';
    } else {
      lote =
        '<input class="form-control" type="text" maxlength="55" id="lote_' +
        id_existencia +
        '_' +
        id_producto +
        '" onchange="validarRepeAjusInv(this)">';
    }

    if (caducidad_producto == "0") {
      caducidad =
        '<input class="form-control" type="date"  id="fecha_' +
        id_existencia +
        '" disabled>';
    } else {
      caducidad =
        '<input class="form-control" type="date" min="' +
        fecha +
        '"  id="fecha_' +
        id_existencia +
        '" onchange="validarCaducidad(this)">';
    }

    /*if (
      serie_producto == "0" &&
      lote_producto == "0" &&
      caducidad_producto == "0"
    ) {
      serie = "";
      lote = "";
      caducidad = "";
    }*/

    if (caducidad == "0000-00-00") {
      caducidad = "";
    }

    if (
      existeClave == 1 &&
      //serie_producto == "0" &&
      lote_producto == "0" &&
      caducidad_producto == "0"
    ) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 5000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "No se puede duplicar productos sin lote y caducidad",
        sound: '../../../../../sounds/sound4'
      });
    } else {
      table2.row
        .add({
          IdExistencia: id_existencia,
          IdProducto: id_producto,
          ClaveProducto: clave2,
          Nombre:
            inputIdProducto +
            inputClave +
            //inputSerie +
            inputLote +
            inputCaducidad +
            nombre2,
          Descripcion: descripcion,
          //Serie: serie,
          Lote: lote,
          Caducidad: caducidad,
          Existencia: "",
          Cantidad: cantidad,
          Motivo: inputMotivo,
          Observaciones: inputObservacion,
          Acciones: accionEliminar,
        })
        .draw();

      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "hover focus click",
        });
      });

      $('.tooltip').tooltip('hide');

      new SlimSelect({
        select: "#mot_" + id_existencia,
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona motivo"
      });

    }

    swal.close();

    setTimeout(function(){
      var table = $("#tblAjustesDetalle").DataTable();

      if(table.rows().count() === 0){
        $("#btnAjustar").prop("disabled", true);
      }else{
        $("#btnAjustar").prop("disabled", false);
      }
    }, 500);

  });
}

function cargarAjustesDetalle() {
  var table = $("#tblAjustesDetalle").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: false,
    bSort: false,
    paging: false,
    responsive: true,
    lengthChange: false,
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 1, visible: false },
      {className: "text-center", targets: [4]}
    ],
    columns: [
      { data: "IdExistencia" },
      { data: "IdProducto" },
      { data: "ClaveProducto" },
      { data: "Nombre" },
      { data: "Descripcion" },
      //{ data: "Serie" },
      { data: "Lote" },
      { data: "Caducidad" },
      { data: "Existencia" },
      { data: "Cantidad" },
      { data: "Motivo" },
      { data: "Observaciones" },
      { data: "Acciones" },
    ],
  });

  table.columns.adjust().draw();

  $("#tblAjustesDetalle tbody").on("click", "img", function () {
    table.row($(this).parents("tr")).remove().draw();
    $('.tooltip').tooltip('hide');
    setTimeout(function(){
      if(table.rows().count() === 0){
        $("#btnAjustar").prop("disabled", true);
      }else{
        $("#btnAjustar").prop("disabled", false);
      }
    }, 500);
  });

}

function validarRepeAjusInv(item) {
  
  var campo = $(item).attr("id").split("_")[0];
  var id_detalle = $(item).attr("id").split("_")[1];
  
  $("table input[type=text]").each(function () {
    
    if (
      $(this).attr("id").startsWith(campo) &&
      $(this).attr("id") != $(item).attr("id") &&
      $(this).val() == $(item).val() &&
      $(this).val() &&
      $(this).attr("id").split("_")[2] == $(item).attr("id").split("_")[2] &&
      !$(this).prop("disabled")
    ) {
      if (campo == "serie") {
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
      funcion: "get_ValidacionAjusteExistencia",
      data1: sucursal,
      data2: clave,
      //data3: serie,
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

function validarCaducidad(item) {
  var id_detalle = $(item).attr("id").split("_")[1];
  var campo = $(item).attr("id").split("_")[0];
  var caducidad = $(item).val();
  var date = new Date();
  date.setDate(date.getDate());
  var mes = date.getMonth() + 1;
  var dia = date.getDate();
  switch (dia.toString().length , mes.toString().length) {
    case 1 , 2:
      var fecha =
        date.getFullYear() +
        "-0" +
        (date.getMonth() + 1) +
        "-" +
        date.getDate();
      break;
    case 2 , 1:
      var fecha =
        date.getFullYear() +
        "-" +
        (date.getMonth() + 1) +
        "-0" +
        date.getDate();
      break;
    case 1 , 1:
      var fecha =
        date.getFullYear() +
        "-0" +
        (date.getMonth() + 1) +
        "-0" +
        date.getDate();
      break;
    case 2 , 2:
      var fecha =
        date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
      break;
  }

//   if (fecha >= caducidad) {
//     $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
//     $(
//       "<div class='invalid-feedback d-block' id='invalid_insercion_" +
//         campo +
//         "_" +
//         id_detalle +
//         "'>Introduce fechas mayores a hoy</div>"
//     ).insertAfter($("#" + campo + "_" + id_detalle));
//   } else {
//     $("#invalid_insercion_" + campo + "_" + id_detalle).remove();
//   }

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
  console.log($(item).attr("id"));
  var campo = $(item).attr("id").split("_")[0];
    $("#invalid_entry_" + id + "_" + campo).remove();
  $(item).removeClass("is-invalid");

}

function insertarAjusteNegativo() {

  var valor = 1;

  $("table input[type=text],input[type=date],input[type=number],select").each(
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
  var tipo = $("#inputTipoAjuste").val();

  if(valor == 1){
    if (!$("div").hasClass("invalid-feedback")) {

      $("#loader").css("display", "block");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_Ajustes",
          data1: sucursal,
          data2: tipo,
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta de insertar un ajuste: ", respuesta);
        },
        error: function (error) {
          console.log(error);
        },
      });
      
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_IdAjuste",
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta del id del ajuste: ", respuesta);
          $("#inputAjuste").val(respuesta.id);
          $("#inputTipoAjuste").val(respuesta.tipo_ajuste);
          $("#inputFolioAjuste").val(respuesta.folio);
        },
        error: function (error) {
          console.log(error);
        },
      });

      var ajuste = 0;
      ajuste = $("#inputAjuste").val();
      var folio_ajuste = 0;
      folio_ajuste = $("#inputFolioAjuste").val();
      var tipo_ajuste = $("#inputTipoAjuste").val(); 
    
      $("input[type=number]").each(function () {
        var id_detalle = $(this).attr("id").split("_")[1];
        var id_producto = $("#idPro_" + id_detalle).val();
        if (tipo_ajuste == 1) {
          var existencia = 0;
        } else {
          var existencia = $("#ex_" + id_detalle).val();
        }
        var cantidad = $(this).val();
        var clave = $("#clave_" + id_detalle).val();
        if ($("#lot_" + id_detalle).val()) {
          var lote = $("#lot_" + id_detalle).val();
        } else {
          var lote = "";
        }
        console.log(lote);
        /*
        if ($("#ser_" + id_detalle).val()) {
          var serie = $("#ser_" + id_detalle).val();
        } else {
          var serie = "";
        }
        console.log(serie);
        */
        if ($("#fech_" + id_detalle).val()) {
          var caducidad = $("#fech_" + id_detalle).val();
        } else {
          var caducidad = "0000-00-00";
        }
        console.log(caducidad);
        var motivo = $("#mot_" + id_detalle).val();
        var observaciones = $("#obs_" + id_detalle).val();

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "save_data",
              funcion: "save_Ajustar",
              data1: ajuste,
              data2: id_producto,
              data3: existencia,
              data4: cantidad,
              data5: clave,
              data6: lote,
              //data7: serie,
              data8: caducidad,
              data9: motivo,
              data10: observaciones,
            },
            dataType: "json",
            async: false,
            success: function (respuesta) {
              console.log("respuesta de hacer el ajuste negativo: ", respuesta);
            },
            error: function (error) {
              console.log(error);
            },
          });
        
      });

      $("#loader").css("display", "none");
    
      Swal.fire({
        title: "Ajuste exitoso",
        icon: "success",
        html:
          "<label>El ajuste de folio: " +
          folio_ajuste +
          " ha sido realizado exitosamente</label>",
        width: "600px",
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Descargar PDF",
        reverseButtons: true,
        backdrop: false,
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
          cancelButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "../ajuste_inventario/";
        } else if (result.isDismissed) {
          window.location.href =
            "funciones/descargar_Ajuste.php?data=" + ajuste;

          setTimeout(function () {
            window.location.href = "../ajuste_inventario/";
          }, 2000);
        }
      });

    } else {
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

function insertarAjustePositivo() {

  var valor = 1;

  $("table input[type=text],input[type=date],input[type=number],select").each(
    function () {
      if (!$(this).prop("disabled") && !$(this).val()) {
        console.log(this);
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
  var tipo = $("#inputTipoAjuste").val();

  if(valor == 1){
    if (!$("div").hasClass("invalid-feedback")) {
      $("#loader").css("display", "block");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_Ajustes",
          data1: sucursal,
          data2: tipo,
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta de insertar un ajuste: ", respuesta);
        },
        error: function (error) {
          console.log(error);
        },
      });

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_IdAjuste",
        },
        dataType: "json",
        async: false,
        success: function (respuesta) {
          console.log("respuesta del id del ajuste: ", respuesta);
          $("#inputAjuste").val(respuesta.id);
          $("#inputTipoAjuste").val(respuesta.tipo_ajuste);
          $("#inputFolioAjuste").val(respuesta.folio);
        },
        error: function (error) {
          console.log(error);
        },
      }); 

      var ajuste = 0;
      ajuste = $("#inputAjuste").val();
      var folio_ajuste = 0;
      folio_ajuste = $("#inputFolioAjuste").val();
    
      $("input[type=number]").each(function () {
        var id_detalle = $(this).attr("id").split("_")[1];
        var id_producto = $("#idPro_" + id_detalle).val();
        var existencia = 0;
        var cantidad = $(this).val();
        var clave = $("#clave_" + id_detalle).val();
        if ($("#lote_" + id_detalle + '_' + id_producto) && $("#lote_" + id_detalle + '_' + id_producto).val()) {
          var lote = $("#lote_" + id_detalle + '_' + id_producto).val();
        } else if (
          $("#lote_" + id_detalle + '_' + id_producto) &&
          !$("#lote_" + id_detalle + '_' + id_producto).val()
        ) {
          var lote = "";
        } else if (!$("#lote_" + id_detalle + '_' + id_producto) && $("#lot_" + id_detalle).val()) {
          var lote = $("#lot_" + id_detalle).val();
        } else if (
          !$("#lote_" + id_detalle + '_' + id_producto) &&
          !$("#lot_" + id_detalle).val()
        ) {
          var lote = "";
        }
        console.log(lote);
        if ($("#serie_" + id_detalle + '_' + id_producto) && $("#serie_" + id_detalle + '_' + id_producto).val()) {
          var serie = $("#serie_" + id_detalle + '_' + id_producto).val();
        } /*else if (
          $("#serie_" + id_detalle + '_' + id_producto) &&
          !$("#serie_" + id_detalle + '_' + id_producto).val()
        ) {
          var serie = "";
        } else if (
          !$("#serie_" + id_detalle + '_' + id_producto) &&
          $("#ser_" + id_detalle).val()
        ) {
          var serie = $("#ser_" + id_detalle).val();
        } else if (
          !$("#serie_" + id_detalle + '_' + id_producto) &&
          !$("#ser_" + id_detalle).val()
        ) {
          var serie = "";
        }
        console.log(serie);*/
        if ($("#fecha_" + id_detalle) && $("#fecha_" + id_detalle).val()) {
          var caducidad = $("#fecha_" + id_detalle).val();
        } else if (
          $("#fecha_" + id_detalle) &&
          !$("#fecha_" + id_detalle).val()
        ) {
          var caducidad = "0000-00-00";
        } else if (
          !$("#fecha_" + id_detalle) &&
          $("#fech_" + id_detalle).val()
        ) {
          var caducidad = $("#fech_" + id_detalle).val();
        } else if (
          !$("#fecha_" + id_detalle) &&
          !$("#fech_" + id_detalle).val()
        ) {
          var caducidad = "0000-00-00";
        }
        console.log(caducidad);
        var motivo = $("#mot_" + id_detalle).val();
        var observaciones = $("#obs_" + id_detalle).val();

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "save_data",
              funcion: "save_Ajustar",
              data1: ajuste,
              data2: id_producto,
              data3: existencia,
              data4: cantidad,
              data5: clave,
              data6: lote,
              //data7: serie,
              data8: caducidad,
              data9: motivo,
              data10: observaciones,
            },
            dataType: "json",
            async: false,
            success: function (respuesta) {
              console.log("respuesta de hacer el ajuste positivo: ", respuesta);
            },
            error: function (error) {
              console.log(error);
            },
          }); 
      
      });

      $("#loader").css("display", "none");

      Swal.fire({
        title: "Ajuste exitoso",
        icon: "success",
        html:
          "<label>El ajuste de folio: " +
          folio_ajuste +
          " ha sido realizado exitosamente</label>",
        width: "600px",
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Descargar PDF",
        reverseButtons: true,
        backdrop: false,
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
          cancelButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "../ajuste_inventario/";
        } else if (result.isDismissed) {
          window.location.href =
            "funciones/descargar_Ajuste.php?data=" + ajuste;

          setTimeout(function () {
            window.location.href = "../ajuste_inventario/";
          }, 2000);
        }
      });

    } else {
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

function setFormatDatatablesTblBusquedaAjustes() {
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