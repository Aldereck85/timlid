$(document).ready(function() {
    cargardata();
    crearSelects();
    cargartblmovimientos();
    if($("#pagoLibre").val() == '1'){
        $("#btnEditar").css("display", "none");
    }

});

function cargardata() {
    /* Optenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
    /* $('#editarcp').on('click',function(){ */
    var idpagos = $('#idpago').val();
    var Tipohtml;
    $.ajax({
        type: 'POST',
        url: '../pagos/functions/get_to_edit.php',
        dataType: "json",
        data: { idpagos: idpagos, funcion: "1" },
        success: function(data) {
            if (data.status == 'ok') {
                $('#proveedorid').val(data.result.PKProveedor);
                $('#txtfecha').val(data.result.fecha_pago);
                $('#tipopagoid').val(data.result.tipo_pago);
                Tipohtml = data.result.tipo_pago;
                switch (Tipohtml){
                    case 0:
                        Tipohtml = '<option selected value= 0 >Trasferencia </option>';
                        break;
                    case 1:
                        Tipohtml = '<option selected value= 1 >Cheque </option>';
                        break;
                    case 2:
                        Tipohtml = '<option selected value= 2 >Efectivo </option>';
                        break;
                    case 3:
                        Tipohtml = '<option selected value= 3 >Tarjeta </option>';
                        break;
                }
               // document.getElementById('cmbTipoPag').value = (data.result.tipo_pago);
               (data.result.categoria !== null && data.result.categoria) && 
               (data.result.subcategoria !== null && data.result.subcategoria) ?
               $("#cat_cuentas").removeClass('d-none') : null;
                $('#cmbTipoPag').append(Tipohtml);
                //console.log($('#cmbTipoPag').val());
                $('#cuentaid').val(data.result.cuenta_origen_id);
                $('#txtreferencia').val(data.result.Referencia);
                $('#textareaCoemtarios').val(data.result.comentarios);
                $('#txtTotal').val(data.result.total);
                cargarCMBProveedor();
                $("#txtCategoriaCuenta").val(data.result.categoria);
                $("#txtSubcategoriaCuenta").val(data.result.subcategoria)
                $('#btnEditar').html(data.btnEdit);
                $('#btnEliminar').html(data.btnDelete);

            } else {
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            }
        }
    });

}

function crearSelects() {
    new SlimSelect({
        select: '#cmbProveedor',
        deselectLabel: '<span class="">✖</span>'
    })
    new SlimSelect({
        select: '#cmbCuenta',
        deselectLabel: '<span class="">✖</span>'
    })
    new SlimSelect({
        select: '#cmbTipoPag',
        deselectLabel: '<span class="">✖</span>'
    })
    
}

function cargarCMBProveedor() {
    var idprove = $('#proveedorid').val();
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
        type: 'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_proveedorCombo" },
        success: function(data) {
            //console.log("data de proveedor: ", data);
            $.each(data, function(i) {
                // console.log(data[i].PKData);
                // console.log((idprove));
                // console.log(data[i].PKData == parseInt(idprove));
                

                //Crea el html para ser mostrado
                if (data[i].PKData == parseInt(idprove)) {
                    html +=
                        '<option selected value="' +
                        data[i].PKData +
                        '">' +
                        data[i].Data +
                        "</option>";
                } else {
                    html +=
                        '<option value="' +
                        data[i].PKData +
                        '">' +
                        data[i].Data +
                        "</option>";
                }
            });
            //Pone los proveedores en el select
            $("#cmbProveedor").append(html);
            //Aplica el primer filtro con el proveedor primero
            /* var table = $('#tblcuentas').DataTable();
            $('input[type="search"]').val($("#cmbProveedor option:selected").text());
            table
                .search($("#cmbProveedor option:selected").text())
                .draw(); */
            cargarCMBCuentas();
            //cargarProductosEmpresa();
        },
        error: function(error) {
            console.log("Error");
            console.log(error);
        },
    });


}

function cargarCMBCuentas() {
    var idcuenta = $('#cuentaid').val();
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    $.ajax({
        type: 'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_cuenta" },
        success: function(data) {
            //console.log("data de cuenta: ", data);
            $.each(data, function(i) {
                if (data[i].PKCuenta == idcuenta) {
                    html +=
                        '<option selected value="' +
                        data[i].PKCuenta +
                        '">' +
                        data[i].Cuenta +
                        "</option>";
                } else {
                    html +=
                        '<option value="' +
                        data[i].PKCuenta +
                        '">' +
                        data[i].Cuenta +
                        "</option>";
                }
            });

            $("#cmbCuenta").append(html);
        },
        error: function(error) {
            console.log("Error");
            console.log(error);
        },
    });


}

function cargartblmovimientos() {
    var idpagos = $('#idpago').val();
    let espanol = {
        sProcessing: 'Procesando...',
        sZeroRecords: 'No se encontraron resultados',
        sEmptyTable: 'Ningún dato disponible en esta tabla',
        sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
        sLoadingRecords: 'Cargando...',
        searchPlaceholder: 'Buscar...',
        oPaginate: {
            sFirst: 'Primero',
            sLast: 'Último',
            sNext: "<i class='fas fa-chevron-right'></i>",
            sPrevious: "<i class='fas fa-chevron-left'></i>"
        },
    }

    tablaD = $("#tblmovimientos").DataTable({
        "language": espanol,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-table-custom",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [],
        },
        "ajax": "functions/get_to_see?pagoid=" + idpagos,
        "columns": [
            { "data": "Proveedor" },
            { "data": "Folio de Factura" },
            {"data": "Serie de Factura"},
            { "data": "Fecha de Vencimiento" },
            { "data": "Importe" },
            { "data": "Estatus" },
            { "data": "Id" },
        ],
        "columnDefs": [
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            }, 
          ]
    });
    afetertable();
}
function afetertable(){
    $('table input[type=checkbox]').attr('disabled','true');

}
function loadTipo(){
    var idcuenta = $('#cuentaid').val();
}

function cargarCMBSubcategorias(subCat,name)
{
  var html = '<option disabled value="f" selected>Seleccione una subcategoria</option>';
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    cache: false,
    success: function (data) {
      if (data !== "" && data !== null && data.length > 0) {
        
        $.each(data, function (i) {
            
            if(data[i].PKSubcategoria === name){
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
        html += '';
      } else {
        html += '';
      }
      
      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function cargarCMBCategorias(name)
{
  var html = '<option disabled value="f" selected>Seleccione una categoria</option>';
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_categorias"},
    success: function (data) {
      if (data !== "" && data !== null && data.length > 0) {
        $.each(data, function (i) {
          if (data[i].PKCategoria === name) {
            html += '<option value="' +
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
        html += '';
      } else {
        html += '';
      }
      $("#cmbCategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}