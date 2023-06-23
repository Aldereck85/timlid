var isEdit = false;
$(document).ready(function () {
    new SlimSelect({
        select: '#cmbEditCategory', 
        deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
        select: '#cmbEditSubcategory', 
        deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
        select: '#cmbEditSubcategory1', 
        deselectLabel: '<span class="">✖</span>'
    });
  //Inicializar los tooltip
  $('[data-toggle="tooltip"]').tooltip({
    //Para que desaparescan cuando se sale del elemento
    trigger: "hover",
  });

  $("#btnPagos").on("click", function(){
    cargar_moduloPagos();
  });

  //Validar al querer eliminar la cuenta por pagar que no esté parcialmente pagada ni pagada
  $("#btnEliminar").on("click", function(e){
    if(parseInt($("#txtEstatusFactura").val()) === 4 || parseInt($("#txtEstatusFactura").val()) === 5){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡No se puede eliminar ésta cuenta!",
      });
    }else{
      $("#mdldeletealert").modal('show');
    }
  });

  //Petición al eliminar la cuenta por pagar
  $("#btnEliminarCuentaPagar").on("click", function(){
    let id = $("#cuenta_id").val();
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"delete_data",funcion:"delete_cuentaPagar", id},
      success: function (data) {
       window.location.href = '../cuentas_pagar/';
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
  });

  /* Verificar si el valor de un input cambio para activar el boton guardar */
  $(".edit").each(function () {
    var elem = $(this);

    // Save current value of element
    elem.data("oldVal", elem.val());

    // Look for changes in the value
    elem.bind("propertychange change keyup input paste", function (event) {
      // If value has changed...
      if (subtotal != elem.val()) {
        // Updated stored value
        elem.data("oldVal", elem.val());
        //console.log(subtotal)
        //console.log(elem.val())
        if(isEdit){
          $("#btnguardarDetalle").removeAttr("disabled");
          $("#btnguardarDetalle").removeAttr("style");
          $("#spanbutton").tooltip("disable");
        }

        // Do action
      } else {
        $("#btnguardarDetalle").attr("disabled");
      }
    });
  });

  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = "../dashboard.php";
  });
  // Jquery Dependency

  ///
  //// Funcion para editar en el modal
  ///
  function UpdateUserDetails() {
    // Optener Valores del modal
    var inputPrecio = $("#inputPrecio").val();
    var inputDescuento = $("#inputDescuento").val();
    var inputIva = $("#inputIva").val();
    var inputCantidad = $("#inputCantidad").val();
    var inputIeps = $("#inputIeps").val();
    var comentarios = $("#txtComentarios").val();

    // Optener el valor coculto del id modal
    var id = $("#hidden_cuenta_id").val();

    // Mandar los datos en un POST a el archivo UPdate.php a actualizar
    $.ajax({
      type: "POST",
      url: "../cuentas_pagar/functions/Update.php",
      dataType: "json",
      data: { 
        action: "0",
        id: id,
        inputPrecio: inputPrecio,
        inputDescuento: inputDescuento,
        inputIva: inputIva,
        inputCantidad: inputCantidad,
        inputIeps: inputIeps,
        comentarios: comentarios
      },
      success: function (res) {
        if (res.status == "ok") {
          alert("Data: " + data + "\nStatus: " + res.status);
          $("#modaldcp").modal("hide");
          console.log(inputPrecio);

        } else if(res.status == "no"){
          $("#mdlsavealert").hide();
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡No se puede editar ésta cuenta!",
          });
        } else {
          alert("Algo ha fallado, revisa tus entradas");
        }
      }
    });
  }
  ///
  //// FUncion para editar los datos de la cabecera
  ///
  function Updatecabecera() {
    var inputSubtotal = $("#subtotal").val();
    var inmputImporte = $("#txtimporte").val();
    var inputIva = $("#_txtiva").val();
    var inmputIeps = $("#_txtieps").val();
    var comentarios = $("#txtComentarios").val();

    var id = $("#cuenta_id").val();

    $.ajax({
      type: "POST",
      url: "../cuentas_pagar/functions/Update.php",
      dataType: "json",
      data: { 
        action: "1",
        id: id,
        inputSubtotal: inputSubtotal,
        inmputImporte: inmputImporte,
        inputIva: inputIva,
        inmputIeps: inmputIeps,
        comentarios: comentarios
      },
      success: function (res) {
        if (res.status == "ok") {
          console.log("tamo bien");
          $("#mdlsavealert").hide();
          window.history.back();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Los datos de la cuenta por pagar se han actualizado con exito!",
          });

          /* $("#mdlnotifi").modal('show'); */
        } else if(res.status == "no"){
          $("#mdlsavealert").hide();
          window.history.back();
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡No se puede editar ésta cuenta!",
          });
        } else {
          alert("Algo ha fallado, revisa tus entradas");
        }
      }
    });
  }

  function cargar_moduloPagos(){
    let id = $("#cuenta_id").val();
    $.ajax({
      url: "../cuentas_pagar/functions/function_redireccionPagos.php",
      data: {
        id: id
      },
      dataType: "json",
      success: function (data) {
        if (data["estatus"] == "ok") {
          $().redirect("../pagos/agregar_anticipo.php", {
            'idProveedor':data['proveedor_id'],
            'idFactura':data['idFactura']
            });
        } else if (data["estatus"] == "pagada"){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: data["result"],
          });
        }else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salió mal!",
          });
        }
      },
      error: function (jqXHR, exception, data, response) {
        var msg = "";
        if (jqXHR.status === 0) {
          msg = "Not connect.\n Verify Network.";
        } else if (jqXHR.status == 404) {
          msg = "Requested page not found. [404]";
        } else if (jqXHR.status == 500) {
          msg = "Internal Server Error [500].";
        } else if (exception === "parsererror") {
          msg = "Requested JSON parse failed.";
        } else if (exception === "timeout") {
          msg = "Time out error.";
        } else if (exception === "abort") {
          msg = "Ajax request aborted.";
        } else {
          msg = "Uncaught Error.\n" + jqXHR.responseText;
        }
      },
    });
  }

  ///Llamada a la funcion con los clicks
  $("#enviar").click(UpdateUserDetails);
  $("#btnAcepCambios").click(Updatecabecera);

  /* En #edit.val Tengo el valor binario de la tabla funciones_permisos->funcion_editar-> En Mi Pantalla (60) en el rol actual*/
  /* En el If() ocultaremos la columna de editar dependiendo de su rol*/
  /* Ocultamos la columna cargando dos tablas diferentes donde en el else agregamos la columna de editar al target de ocultar */
  if ($("#edit").val() !== "1") {
    $("#subtotal").prop("disabled", true);
    $("#txtimporte").prop("disabled", true);
    $("#_txtiva").prop("disabled", true);
    $("#_txtieps").prop("disabled", true);

    $("#mod").hide();
    var cuenta_id = $("#cuenta_id").val();
    $(document).ready(function () {
      var idioma_espanol = {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
        sLoadingRecords: "Cargando...",
        searchPlaceholder: "Buscar...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "<i class='fas fa-chevron-right'></i>",
          sPrevious: "<i class='fas fa-chevron-left'></i>",
        },
      };
      $("#tbldetalle").dataTable({
        language: idioma_espanol,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        paging: false,
        searching: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "",//btn-table-custom
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [],
        },
        ajax: "functions/get_data.php?cuenta_id=" + cuenta_id,
        columns: [
          { data: "Proveedor" },
          { data: "Producto" },
          { data: "Clave" },
          { data: "Cantidad" },
          { data: "Precio" },
          { data: "Descuento" },
          { data: "IVA" },
          { data: "IEPS_Fijo" },
          { data: "IEPS" },
          { data: "Editar" },
          { data: "Key" },
        ],
        columnDefs: [
          {
            targets: [10, 0, 9],
            visible: false,
            searchable: false,
            className: "text-center",
          },
        ],
      });

      //Cuando el modal aparece
      $("#modaldcp").on("show.bs.modal", function (e) {
        /* alert("Modal Mostrada con Evento de Boostrap"); */
      });
      $("#modaldcp").on("hidden.bs.modal", function (e) {
        /* alert("Modal Oculta con Evento de Boostrap"); */
        $("#tbldetalle").DataTable().ajax.reload();
      });
    });
    /* En este else esta la tabla con la columna de editar oculta. */
  } else {
    var cuenta_id = $("#cuenta_id").val();
    $(document).ready(function () {
      var idioma_espanol = {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
        sLoadingRecords: "Cargando...",
        searchPlaceholder: "Buscar...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "<i class='fas fa-chevron-right'></i>",
          sPrevious: "<i class='fas fa-chevron-left'></i>",
        },
      };
      $("#tbldetalle").dataTable({
        language: idioma_espanol,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        paging: false,
        searching: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "",//btn-table-custom
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [],
        },
        ajax: "functions/get_data.php?cuenta_id=" + cuenta_id,
        columns: [
          { data: "Proveedor" },
          { data: "Producto" },
          { data: "Clave" },
          { data: "Cantidad" },
          { data: "Precio" },
          { data: "Descuento" },
          { data: "IVA" },
          { data: "IEPS_Fijo" },
          { data: "IEPS" },
          { data: "Editar" },
          { data: "Key" },
        ],
        /* Ocultar la columna de Id */
        columnDefs: [
          {
            targets: [10, 0, 9],
            visible: false,
            searchable: false,
            className: "text-center",
          },
        ],
      });

      $("#modaldcp").on("hidden.bs.modal", function (e) {
        /* alert("Modal Oculta con Evento de Boostrap"); */
        $("#tbldetalle").DataTable().ajax.reload();
      });

      //Acceder al valor de la tabla clicado
      var table = $("#tbldetalle").DataTable();
      $("#tbldetalle tbody").on("click", "tr", function () {
        var data = table.row(this).data();
        /* Pasar los datos de la fila a el modal */
        $("#mdnombre").val(data.Proveedor);
        $("#inputClave").val(data.Clave);
        $("#inputCantidad").val(data.Cantidad);
        $("#inputPrecio").val(data.Precio);
        $("#inputDescuento").val(data.Descuento);
        $("#inputIva").val(data.IVA);
        $("#inputIeps").val(data.IEPS);
        $("#hidden_cuenta_id").val(data.Key);
      });
    });
  } //End Else

  /* Optenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
  /* $('#editarcp').on('click',function(){ */
  var cuenta_id = $("#cuenta_id").val();
  $.ajax({
    type: "POST",
    url: "../cuentas_pagar/functions/get_ajax.php",
    dataType: "json",
    data: { cuenta_id: cuenta_id, funcion: "1" },
    success: function (data) {
      if (data.status == "ok") {
        $("#nombre").text(data.result.NombreComercial);
        $("#txtfolio").text(data.result.folio_factura);
        $("#txtserie").text(data.result.num_serie_factura);
        $("#txtfechaF").text(data.result.fecha_factura.split(" ")[0]);
        $("#txtfechaV").text(data.result.fecha_vencimiento.split(" ")[0]);
        $("#txtCategoriaGasto").text(data.result.categoria);
        $('#txtSubcategoriaGasto').text(data.result.subcategoria);
        $("#txtIdCategoriaGasto").val(data.result.cat_id);
        $('#txtIdSubcategoriaGasto').val(data.result.subcat_id);
        $("#subtotal").val(data.result.subtotal);
        $("#txtimporte").val(data.result.importe);
        $("#txtimporte_Cabecera").text("$ "+data.result.importe);
        $("#_txtiva").val(data.result.iva);
        $("#_txtieps").val(data.result.ieps);
        $("#txtComentarios").text(data.result.comentarios);
        $("#txtEstatusFactura").val(parseInt(data.result.estatus_factura));
        
        //validaciones 
        if(parseInt(data.result.estatus_factura) == 5){
          $("#subtotal").addClass('disabled');
          $("#txtimporte").addClass('disabled');
          $("#_txtiva").addClass('disabled');
          $("#_txtieps").addClass('disabled');
          isEdit = false;
        }else{
          isEdit = true;
        }

      } else {
        $(".user-content").slideUp();
        $("#alertInvoice").modal("show");
      }
    },
  });
  function showalert() {}

    $(document).on("click","#btn_save_editCategory",()=>{
        if(
            $("#cmbEditCategory").val() !== null && 
            $("#cmbEditCategory").val() !== "" &&
            $("#cmbEditSubcategory").val() !== null && 
            $("#cmbEditSubcategory").val() !== ""
        ){
            var id = $("#cuenta_id").val();
            var cat = $("#cmbEditCategory option:selected").val();
            var catText = $("#cmbEditCategory option:selected").text();
            var subcat = $("#cmbEditSubcategory option:selected").val();
            var subcatText = $("#cmbEditSubcategory option:selected").text();
            saveEditCategory(id,cat,subcat,catText,subcatText);
           
        } else{
            Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Faltan campos por rellenar",
              });
        }
    });

    $(document).on("click","#btn_save_editSubcategory",()=>{
        var id = $("#cuenta_id").val();
        var cat = $("#cmbEditSubcategory1 option:selected").val();
        var catText = $("#cmbEditSubcategory1 option:selected").text();
        saveEditSubcategory(id,cat,catText);
    });
});

$(document).on('shown.bs.modal','#modal_edit_category',()=>{
    var cat_id = $("#txtIdCategoriaGasto").val();
    var subcat_id = $("#txtIdSubcategoriaGasto").val();
    
    cargarCMBCategorias(cat_id);
    cargarCMBSubcategorias(cat_id,subcat_id,'#cmbEditSubcategory');
    
    
});

$(document).on("change","#cmbEditCategory",()=>{
    cat_id = $("#cmbEditCategory").val();
    cargarCMBSubcategorias(cat_id,"",'#cmbEditSubcategory');
})

$(document).on('shown.bs.modal','#modal_edit_subcategory',()=>{
    var cat_id = $("#txtIdCategoriaGasto").val();
    var subcat_id = $("#txtIdSubcategoriaGasto").val();
    $("#txtEditCategoryText").text($("#txtCategoriaGasto").text());
    
    cargarCMBSubcategorias(cat_id,subcat_id,'#cmbEditSubcategory1');
});

function cargarCMBCategorias(value)
{
  var html = "";
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_categorias"},
    success: function (data) {
      //   console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (parseInt(value) === parseInt( data[i].PKCategoria)) {
          html +=
            '<option value="' +
            data[i].PKCategoria +
            '" selected>' +
            data[i].Nombre+
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCategoria +
            '">' +
            data[i].Nombre+
            "</option>";
        }
      });

      $("#cmbEditCategory").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}
function cargarCMBSubcategorias(subCat,value,input)
{
  var html = '<option disabled value="f" selected>Seleccione una categoria</option>';
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    success: function (data) {
      $.each(data, function (i) {
        if (parseInt(value) === parseInt(data[i].PKSubcategoria)) {
          html +=
            '<option value="' +
            data[i].PKSubcategoria +
            '" selected>' +
            data[i].Nombre+
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKSubcategoria +
            '">' +
            data[i].Nombre+
            "</option>";
        }
      });

      $(input).html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function saveEditCategory(id,value,value1,valueTxt,valueTxt1){
    $.post(
        "functions/controller.php",
        {
            clase: "update_data",
            funcion: "update_category",
            id:id,
            value: value,
            value1: value1
        },(response)=>{
            console.log(JSON.parse(response));
            var res = JSON.parse(response);
            if(res === true)
            {
                $('#txtCategoriaGasto').text(valueTxt);
                $('#txtIdCategoriaGasto').val(value);
                $('#txtSubcategoriaGasto').text(valueTxt1);
                $('#txtIdSubcategoriaGasto').val(value1);
                $("#modal_edit_category").modal('hide');
            }
        }
    );
}

function saveEditSubcategory(id,value,valueTxt){
    $.post(
        "functions/controller.php",
        {
            clase: "update_data",
            funcion: "update_subcategory",
            id:id,
            value: value
        },(response)=>{
            console.log(JSON.parse(response));
            var res = JSON.parse(response);
            if(res === true)
            {
                $('#txtSubcategoriaGasto').text(valueTxt);
                $('#txtIdSubcategoriaGasto').val(value);
                $("#modal_edit_subcategory").modal('hide');
            }
        }
    );
}