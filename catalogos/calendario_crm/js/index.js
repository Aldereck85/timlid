
$(document).ready(function() {

  initDataTable();
  initSelectPropietario();
  initSelectEstado();
  initSelectMedios();

  $('#btnEnviarModuloClientes').hide();

  var max_chars = 250;

  $('#contador').html(max_chars);

  $('#motivo').keyup(function() {
    var chars = $(this).val().length;
    var diff = max_chars - chars;
    $('#contador').html(diff);   
  });

  $('#btnEliminarContacto').click(function(){

    var accion = 'eliminarContacto';
    var motivo = $('#motivo').val();
    var id = $('#id').val();

    $.ajax({
      method: 'POST', 
      url: 'app/controladores/ContactoController.php',
      data: {
        accion:accion,motivo:motivo,id:id
      },
      dataType: 'json',
      success: function(response){
        if (response) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 5000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: response,
          });
          $("#tblContactos").DataTable().ajax.reload();
          $('#InactivarLead').modal('toggle');
        }
      }
    });

  });

  $('#btnAgregarCliente').click(function(){

    var accion = 'AgregarContactoCliente';
    var campania = $('#campaniaModal').val();
    var email = $('#emailModal').val();
    var id = 30;

    $.ajax({
      method: 'POST', 
      url: 'app/controladores/ContactoController.php',
      data: {
        accion:accion,campania:campania,email:email,id:id
      },
      dataType: 'json',
      success: function(response){
        if (response.error) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 5000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: response.message,
          });
          return;
        }else{
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 5000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: response.message,
          });
          return;
        }
      }
    });
  });
});

function initSelectMedios() {
  new SlimSelect({
    select: "#campaniaModal",
    placeholder: 'Seleccione un medio de campaña',
    searchPlaceholder: 'Buscar medios',
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectPropietario() {
  new SlimSelect({
    select: "#propietarioModal",
    placeholder: 'Seleccione un vendedor',
    searchPlaceholder: 'Buscar vendedor',
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectEstado() {
  new SlimSelect({
    select: "#estadoModal",
    placeholder: 'Seleccione un estado federativo',
    searchPlaceholder: 'Buscar estado federativo',
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}


function initDataTable(){
  let idioma_espanol = {
    sProcessing: 'Procesando...',
    sZeroRecords: 'No se encontraron resultados',
    sEmptyTable: 'Ningún dato disponible en esta tabla',
    sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: 'Cargando...',
    searchPlaceholder: 'Buscar...',
    oPaginate: {
      sFirst: 'Primero',
      sLast: 'Último',
      sNext: '<img src="../../img/icons/pagination.svg" width="20px"/>',
      sPrevious: '<img src="../../img/icons/pagination.svg" width="20px" style="transform: scaleX(-1)"/>'
    },
    searchBuilder: {
      add: 'Filtros',
      condition: 'Condición',
      conditions: {
        string: {
          contains: 'Contiene',
          empty: 'Vacio',
          endsWith: 'Finaliza con',
          equals: 'Igual',
          not: 'Diferente',
          notEmpty: 'No vacío',
          startsWith: 'Comienza con',
        },
        date: {
          after: 'Después de',
          before: 'Antes de',
          between: 'Entre',
          empty: 'Vacio',
          equals: 'Igual',
          not: 'Diferente',
          notBetween: 'No está entre',
          notEmpty: 'No vacío'
        },
        number: {
          between: 'Between',
          empty: 'Vacio',
          equals: 'Igual',
          gt: 'Mayor que',
          gte: 'Mayor o igual que',
          lt: 'Menor que',
          lte: 'Menor o igual que',
          not: 'Diferente',
          notBetween: 'No está entre',
          notEmpty: 'No vacío',
        },
        array: {
          contains: 'Contiene',
          empty: 'Vacio',
          equals: 'Igual',
          not: 'Diferente',
          notEmpty: 'No vacío',
          without: 'Sin'
        }
      },
      clearAll: 'Limpiar',
      deleteTitle: 'Eliminar',
      data: 'Columna',
      leftTitle: 'Izquierda',
      logicAnd: '+',
      logicOr: 'o',
      rightTitle: 'Derecha',
      title: {
        0: 'Filtros',
        _: 'Filtros (%d)'
      },
      value: 'Opción',
      valueJoiner: 'et'
    }
  }


  let table = $("#tblContactos")
  .DataTable({
    language: idioma_espanol,
    dom: "QBlfrtip",
    buttons: [{
      extend: 'excelHtml5',
      text: '<i class="fas fa-file-excel"></i>',
      className: "btn btn-info datatables-btn float-left mx-2",
      titleAttr: 'Excel',
    },
    {
      extend: 'collection',
      text: 'Listas',
      className: "btn btn-success datatables-btn float-left mx-2",
      autoClose: true,
      buttons: [
      {
        text: 'Abril',
        action: function ( e, dt, node, config ) {
          stored = {
            criteria:[
            {
              condition: 'between',
              data: 'Último contacto',
              value: ['2021-04-01', '2021-04-30']
            },
            {
              condition: '=',
              data: 'Estado Lead',
              value: ['Nuevo']
            }
            ],
            logic: 'AND',
          };

          $('#tblContactos').DataTable().searchBuilder.rebuild(stored);
          let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista: <input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15" value="Abril"></span><button id="dtsb-save-2" class="dtsb-button" type="button">Editar Lista</button></div>'
          $(table.searchBuilder.container()).append(div);
          reset_icons();
          reset_listeners();

        }
      },
      {
        text: 'Nuevo',
        action: function ( e, dt, node, config ) {
          stored = {
            criteria:[
            {
              condition: '=',
              data: 'Estado Lead',
              value: ['Nuevo']
            }
            ],
            logic: 'AND',
          };

          $('#tblContactos').DataTable().searchBuilder.rebuild(stored);
          let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista: <input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15" value="Nuevo"></span><button id="dtsb-save-1" class="dtsb-button" type="button">Editar Lista</button></div>'
          $(table.searchBuilder.container()).append(div);
          reset_icons();
          reset_listeners();
        }
      }
      ]
    }
    ],
    colReorder: true,
    searchBuilder: {
      columns: [2,3,4,5,6,7],
    },
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      type: 'POST',
      url : "app/controladores/ContactoController.php",
      data : {accion:"verContactos"},
      dataSrc:"",

    },
    //data: data,
    paging: true,
    pageLength: 10,

    columns: [
    {
      'data': 'id'
    },
    {
      'data': 'contacto',
      "render": function ( data, type, row, meta ) {
        var url = 'editar_contacto.php?id='+row.id+'';
        return '<a href="'+url+'">'+row.nombre+' '+row.apellido+'</a>';
      }
    },
    {
      'data': 'empresa'
    },
    {
      'data': 'email'
    },
    {
      'data': 'medio_contacto_campania'
    },
    {
      'data': 'propietario'
    },
    {
      'data': 'estatus',
      "render": function ( data, type, row, meta ){
        if(data == '2'){
          return '<h6><span class="badge badge-danger">Inactivo</span></h6>'
        }
        return `<h6><span class="badge badge-success">Activo</span></h6>`;
      }
    },
    {
      "render": function ( data, type, row, meta ) {
        return `<button class="btn btn-sm" id="' + row.id +'" onclick="obtenerCliente(this)" ><i class="far fa-thumbs-up text-success"></i></button>
        <button class="btn btn-sm" data-toggle="modal" data-target="#InactivarLead" id="' + row.id +'" onclick="eliminarProspecto(this)"  ><i class="far fa-thumbs-down text-warning"></i></button>`;
      }
    },
    ],
  });

  reset_listeners();
}

function add_searchbuilder(){
  $(".dtsb-add").on( "click", function() {
    reset_icons();

    if($(".dtsb-list-edit").length == 0){
      let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista:</span><input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15">';
      div += '<button id="dtsb-save-1" class="dtsb-button" type="button" style="margin-left: 10px">Crear Lista</button></div>'
      $($('#tblContactos').DataTable().searchBuilder.container()).append(div);
    }
    reset_listeners();
  });
}

function left_right_searchbuilder(edit = false){
  $(".dtsb-left, .dtsb-right, .dtsb-clearAll, .dtsb-clearGroup").on( "click", function() {
    reset_listeners();
    reset_icons();
  });
}

function delete_searchbuilder(edit = false){
  $(".dtsb-delete").on( "click", function() {
    reset_listeners();
    if($('.dtsb-criteria').length == 0){
      $( ".dtsb-list-edit" ).remove();
    }
  });
}

function reset_icons(){
  $(".dtsb-clearGroup").html('<i class="fas fa-times"></i>');
  $(".dtsb-delete").html('<i class="fas fa-times"></i>');
  $(".dtsb-right").html('<i class="fas fa-angle-right"></i>');
  $(".dtsb-left").html('<i class="fas fa-angle-left"></i>');
}

function reset_listeners(){
  delete_searchbuilder();
  left_right_searchbuilder();
  add_searchbuilder();
}

function obtenerCliente (obj) {

  var rowID = $(obj).attr('id');
  var id = $(obj).closest('tr').find('td:first').html();
  var accion = 'VerificarContacto';

  $.ajax({
    method: 'POST', 
    url: 'app/controladores/CrearClienteController.php',
    data: {
      accion:accion,id:id
    },
    dataType: 'json',
    success: function(response){
      if (response.data) {

        $('#ActivarLead').modal('show');

        $('#nombreModal').val(response.data.nombre);
        $('#apellidoModal').val(response.data.apellido);
        $('#puestoModal').val(response.data.puesto);
        $('#celularModal').val(response.data.celular);

        $('#empresaModal').val(response.data.empresa);
        $('#campaniaModal').val(response.data.medio_contacto_campania_id);
        $('#propietarioModal').val(response.data.empleado_id);
        $('#telefonoModal').val(response.data.telefono);
        $('#emailModal').val(response.data.email);
        $('#estadoModal').val(response.data.estado_id);

      }
    }
  });

}


function agregarCliente(obj) {



  var accion = 'CrearContactoCliente';

  var nombre = $('#nombreModal').val();
  var apellido = $('#apellidoModal').val();
  var puesto = $('#puestoModal').val();
  var celular = $('#celularModal').val();

  var empresa = $('#empresaModal').val();
  var medio_contacto_id = $('#campaniaModal').val();
  var vendedor = $('#propietarioModal').val();
  var telefono = $('#telefonoModal').val();
  var email = $('#emailModal').val();

  var monto_credito = $('#montoModal').val();
  var dias_credito = $('#diasModal').val();

  var razon_social = $('#razon_socialModal').val();
  var rfc = $('#rfcModal').val();
  var municipio = $('#municipioModal').val();
  var colonia = $('#coloniaModal').val();
  var calle = $('#calleModal').val();
  var numero_exterior = $('#numero_exteriorModal').val();
  var numero_interior = $('#numero_interiorModal').val();
  var codigo_postal = $('#codigo_postalModal').val();
  var pais = $('#paisModal').val();
  var estado = $('#estadoModal').val();

  $.ajax({
    method: 'POST', 
    url: 'app/controladores/CrearClienteController.php',
    data: {
      accion:accion,
      nombre:nombre,
      apellido:apellido,
      puesto:puesto,
      celular:celular,
      empresa:empresa,
      medio_contacto_id:medio_contacto_id,
      vendedor:vendedor,
      telefono:telefono,
      email:email,
      monto_credito:monto_credito,
      dias_credito:dias_credito,
      razon_social:razon_social,
      rfc,rfc,
      municipio:municipio,
      colonia:colonia,
      calle:calle,
      numero_exterior:numero_exterior,
      numero_interior:numero_interior,
      codigo_postal:codigo_postal,
      pais:pais,
      estado:estado
    },
    dataType: 'json',
    success: function(response){
      if (response.error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 5000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: response.message,
        });
        return;
      }else{
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 5000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: response.message,
        });
        return;
      }
    }
  });


}

function eliminarProspecto(obj){
  var rowID = $(obj).attr('id');
  var id = $(obj).closest('tr').find('td:first').html();
  $('#id').val(id);
  $('#InactivarLead').modal('show');
}