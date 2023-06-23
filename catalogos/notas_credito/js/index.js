
///Redireccionar a detalles con parametro

var tablaD;
$(function () {

  
  $("#cmbRelacion").change(function (e) { 
    e.preventDefault();
    console.log($("#cmbRelacion").value);
  });

  $("#cmbMotivo").change(function (e) { 
    e.preventDefault();
    var idsustituto = $("#cmbRelacion").value;
    if(idsustituto == undefined){
      idsustituto = 0;
    }
    console.log(idsustituto);
  });

/*   $(document).on("click", "#detalle_nota", function () {
    var data = $(this).data("id");
  
    $().redirect("detalle_nota.php", {
      'idNota': data
    });
  });

   function redirectDetalle(idNota){
    $().redirect("detalle_nota.php", {
      'idNota': idNota
    });
  } */
    //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }else{
    crearSelects();
    cargarTablaIndex();
    cargarCMBCliente();
    tablaD.columns.adjust().draw();
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = "../dashboard.php";
  });

  //activa los tooltips en datatable
  $('#tblNotasCredit tbody').on('mouseover', 'tr', function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        html: true
    });
    $('[data-toggle="tooltip"]').on("click", function () {
      $(this).tooltip("dispose");
    });
  });
});

function cargarCMBRelacion(cliente){
  console.log(cliente);
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_Docs",client:cliente},
      success: function (data) {
        //console.log("data de proveedor: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html += '<option disabled selected value="f">Seleccione</option>';
              html +=
              '<option value="' +
              data[i].id_Nota_Facturapi +
              '">' +
              data[i].num_serie_nota + ' ' + data[i].folion_nota + ": $" + dosDecimales(data[i].importe) +
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].id_Nota_Facturapi +
              '">' +
              data[i].num_serie_nota + ' ' + data[i].folion_nota + ": $" + dosDecimales(data[i].importe) +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#cmbRelacion").append(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}
function crearSelects(){
    new SlimSelect({
        select: '#cmbCliente', 
        deselectLabel: '<span class="">✖</span>'
      });

      new SlimSelect({
        select: '#cmbMotivo', 
        deselectLabel: '<span class="">✖</span>'
      });
      
}


function cargarCMBCliente() {
  //here our function should be implemented 
  var html = "";
  //Consulta los proveedores de la empresa
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_Cliente"},
    success: function (data) {
      //console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
            html += '<option selected value="f">Todos</option>';
            html +=
            '<option value="' +
            data[i].PKCliente +
            '">' +
            data[i].NombreComercial +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCliente +
            '">' +
            data[i].NombreComercial +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbCliente").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  
});
}

function cargarTablaIndex() {
    $("#tblNotasCredit").DataTable().destroy();
    let espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
      sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };

    tablaD = $("#tblNotasCredit").dataTable({
      language: espanol,
      info: false,
      /* autoWidth: false, */
      scrollX: true,
      bSort: false,
      pageLength: 10,
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
        buttons: [
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar.php";
            },
          }
        ],
      },
      ajax: "functions/get_Notas_Credito.php",
      columns: [
        { data: "Folio" },
        { data: "UUID" , className: "text-center"},
        { data: "Cliente" },
        { data: "Importe" , className: "text-center"},
        { data: "F_Creacion" },
        { data: "Estado" },
      ],
      //Poner la columna de id oculta
      columnDefs: [
        {
          targets: [],
          visible: false,
          searchable: false,
        },
      ],
    });
    
  }

  function validarImputs2(){
    let _cliente = $('select[name=cmbCliente] option').filter(':selected').val();
    let _fecha_de = $('#txtDateFrom').val();
    let _fecha_to = $('#txtDateTo').val();
    console.log(_cliente);
    if(_fecha_de== ""){
      _fecha_de = "";
    }
    if(_fecha_to== "f"){
      _fecha_to = "f";
    }
    filtro();
    
  }

  function filtro(){
    let _cliente = $('select[name=cmbCliente] option').filter(':selected').val();
    let _fecha_de = $('#txtDateFrom').val();
    let _fecha_to = $('#txtDateTo').val();
    _cliente = (_cliente==undefined)?"f":_cliente;
    if(_fecha_de== ""){
      _fecha_de = "f";
    }
    if(_fecha_to== ""){
      _fecha_to = "f";
    }
    //lleno la tabla con ajax
    tablaD.ajax
      .url(
        "functions/Filtrada_get_Notas_Credito.php?cliente_id=" +
          _cliente +
          "&Ffrom=" +
          _fecha_de +
          "&Fto=" +
          _fecha_to
      )
      .load();
  }

  function cancelarNC(id){

    var idsustituto = $("#cmbRelacion option:selected").val(); 
    console.log("sustituto" + idsustituto);
    if(idsustituto == undefined){
      idsustituto = 0;
    }
    console.log("sustituto: " + idsustituto);
    let motivo = $("#cmbMotivo option:selected").val();
    $.ajax({
      type: "POST",
      url: "functions/controller.php",
      data: { clase:"delete_data",funcion:"cancel_NC",idnc:id, motive:motivo, idsnc:idsustituto},
      dataType: "text",
      success: function (response) {
        console.info(response);
        console.log(response);
        if(response=="1"){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Se canceló la nota de credito!",
        });
        $('#mdlsavealert').modal('hide');
        setTimeout(function(){ window.location.reload()}, 400);
        }else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Error. No se pudo cancelar!",
          });

        }   
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
  }

  function showModal(id,cliente){
    $('#mdlsavealert').modal('show')
    //Cada que se cambia el motivo comprueba la opcion seleccionada
    $("#cmbMotivo").change(function (e) { 
      // e.preventDefault();
       $option = $("#cmbMotivo option:selected").val();
       //Si es 02 03 04 habilita el boton aceptar
       if($option== "02" || $option== "03"|| $option== "04"){
         $("#btnAcepCambios").removeAttr("disabled").focus().val("Ahora si lo puedes editar");
         $("#relacion").html(`<div id="relacion" class= "col-lg">
                              </div>`)
      //Si es 01 muestra select de notas de credito del mismo cliente y empresa y pone el boton disabled
       }else if($option == "01"){
       console.log("cambio");
         $("#relacion").html(
           `<div id="relacion" class= "col-lg">
             <label for="usr">Documento Relacionado:*</label>
             <select name="cmbRelacion" class="form-select" id="cmbRelacion" aria-label="Default select example">
             </select>
             <div class="invalid-feedback" id="invalid-cmbFMPago">Seleccione un documento.</div>
           </div>`
         );
         setTimeout(() => {
           new SlimSelect({
             select: '#cmbRelacion', 
             deselectLabel: '<span class="">✖</span>'
           });
           //Llena el select
           cargarCMBRelacion(cliente);
         }, 100);
        // Pone el boton disabled
         $("#btnAcepCambios").attr('disabled', true);
         }
         /// Si se cambia el documento relacionado y no es f o undefine, habilita el boton
         $("#cmbRelacion").change(function (e) { 
          $option2 = $("#cmbRelacion option:selected").val();
          console.log($option2);
           if($option2 != "f"  && $option2 != undefined){
             $("#btnAcepCambios").removeAttr("disabled").focus().val("Ahora si lo puedes editar");
            }
         });
     });

    
     

    console.log(id);
    //Si se da click en aceptar, pero esta disabled no hace nada
    $("#btnAcepCambios").click(function (e) {
      if($(this).is('[disabled]')){

      }else{
        e.preventDefault();
        cancelarNC(id);
      } 
      
    });
  }




  