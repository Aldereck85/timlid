var id_proveedor;
var cuenta;
$(document).ready(function(){
    cuenta = $("#cuenta").val();
    //console.log(cuenta);
    crearSelects();
    cragarDAta(cuenta);

});
function crearSelects(){
    new SlimSelect({
        select: '#cmbSucursal', 
        deselectLabel: '<span class="">✖</span>'
      })
}
function cragarDAta(cuenta){
    $.ajax({
        type:'POST',
        url:'../cuentas_pagar/functions/get_ajax.php',
        dataType: "json",
        data:{user_id:cuenta,funcion: "2"},
        success:function(data){
            if(data.status == 'ok'){
                $('#txtProveedor').val(data.result.NombreComercial);
                id_proveedor = data.result.proveedor_id;
                $('#txtNoDocumento').val(data.result.folio_factura);
                $('#txtSerie').val(data.result.num_serie_factura);
                $('#txtSubtotal').val(data.result.subtotal);
                $('#txtImporte').val(data.result.importe);
                $('#txtIva').val(data.result.iva);
                $('#txtIEPS').val(data.result.ieps);
                /* $('#txtimporte').val(Intl.NumberFormat("es-MX").format(data.result.importe)); */
                $('#txtfecha').val(data.result.fecha_factura);
                $('#id_sucursal').val(data.result.id_sucursal);
                $('#txtDescuento').val(data.result.descuento);
                cargarCMBsucursal(data.result.id_sucursal);
                var tipoDoc = data.result.tipo_documento;
                console.log(tipoDoc);
                if(tipoDoc==1){
                    $("#factura").prop("checked", true);
                }else if(tipoDoc == 2){
                    $("#remision").prop("checked", true);
                }else if(tipoDoc == 4){
                    $("#anticipo").prop("checked", true);
                }
  
            }else{
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            } 
        }
    });
}
function cargarCMBsucursal(suc){
    var idsucrsal = $('#cuentaid').val();
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    $.ajax({
        type: 'POST',
        url: "functions/controller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_sucursalCombo" },
        success: function(data) {
            console.log("data de cuenta: ", data);
            $.each(data, function(i) {
                if (data[i].PKSucursal == suc) {
                    html +=
                        '<option selected value="' +
                        data[i].PKSucursal +
                        '">' +
                        data[i].Sucursal +
                        "</option>";
                } else {
                    html +=
                        '<option value="' +
                        data[i].PKSucursal +
                        '">' +
                        data[i].Sucursal +
                        "</option>";
                }
            });

            $("#cmbSucursal").append(html);
        },
        error: function(error) {
            console.log("Error");
            console.log(error);
        },
    });
}