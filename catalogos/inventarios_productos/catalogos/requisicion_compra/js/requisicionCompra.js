var _permissions = {
    read: 0,
    add: 0,
    edit: 0,
    delete: 0,
    export: 0,
};

function validaPermisos(){
    $.ajax({
        url: "php/functions.php",
        data: {
          clase: "validate_data",
          funcion: "validate_permisos",
        },
        dataType: "json",
        async: false,
        success: function (data) {
            _permissions.read = data.funcion_ver;
            _permissions.add = data.funcion_agregar;
            _permissions.edit = data.funcion_editar;
            _permissions.delete = data.funcion_eliminar;
            _permissions.export = data.funcion_exportar;
        },
        error: function (error) {
          console.log(error);
        },
      });
}

function setFormatDatatables() {
    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };
    return idioma_espanol;
}

function resetValidations() {
    $(".alpha-only").on("input", function () {
      var regexp = /[^a-zA-Z ]/g;
      if ($(this).val().match(regexp)) {
        $(this).val($(this).val().replace(regexp, ""));
      }
    });
  
    /*Permitir solamente letras y numeros(puntos y guiones medios*/
    $(".alphaNumeric-only").on("input", function () {
      var regexp = /[^a-zA-Z0-9 @.-]/g;
      if ($(this).val().match(regexp)) {
        $(this).val($(this).val().replace(regexp, ""));
      }
    });
  
    /*Permitir solamente letras y numeros sin punto*/
    $(".alphaNumericNDot-only").on("input", function () {
      var regexp = /[^a-zA-Z0-9 @]/g;
      if ($(this).val().match(regexp)) {
        $(this).val($(this).val().replace(regexp, ""));
      }
    });
  
    /*Permitir solamente numeros*/
    $(".numeric-only").on("input", function () {
      var regexp = /[^0-9]/g;
      if ($(this).val().match(regexp)) {
        $(this).val($(this).val().replace(regexp, ""));
      }
    });
  
    /*Permitir solamente numeros y ":" reloj*/
    $(".time-only").on("input", function () {
      var regexp = /[^0-9:]/g;
      if ($(this).val().match(regexp)) {
        $(this).val($(this).val().replace(regexp, ""));
      }
    });
  
    /*Permitir numero decimales */
    $(".numericDecimal-only").on("input", function () {
      var regexp = /[^\d.]/g;
      if ($(this).val().match(regexp)) {
        $(this).val($(this).val().replace(regexp, ""));
      }
    });
}

$(document).ready(function(){
    validaPermisos();
});