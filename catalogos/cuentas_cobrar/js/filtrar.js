/* function carga_cmbClientes(){
    //carga cmb clientes
  $.ajax({
    type:'POST',
    url:'../cuentas_cobrar/functions/function_cmbClientes.php',
    dataType: "json",
    success:function(data){
      $.each(data, function(i) {
        document.getElementById("chosenClientes").innerHTML += "<option value='"+data[i].PKCliente+"'>"+data[i].NombreComercial+"</option>"; 
      });
    }
  });
} */

function validarImputs(){
    redFlag = true;
    
    textInvalidDiv = "Se requiere almenos un dato";

    inputID= "txtClientes"; 
    invalidDivID = "invalid-txtCliente";
    
    inputID2= "txtDateFrom";
    invalidDivID2 = "invalid-txtDateFrom";

    inputID3= "txtDateTo";
    invalidDivID3 = "invalid-txtDateTo";

    if ((($('#'+inputID).val()=="") || ($('#'+inputID).val()==null)) && (($('#'+inputID2).val()=="") || ($('#'+inputID2).val()==null)) && (($('#'+inputID3).val()=="") || ($('#'+inputID3).val()==null))) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text(textInvalidDiv);
  
        $("#" + inputID2).addClass("is-invalid");
        $("#" + invalidDivID2).show();
        $("#" + invalidDivID2).text(textInvalidDiv);
  
        $("#" + inputID3).addClass("is-invalid");
        $("#" + invalidDivID3).show();
        $("#" + invalidDivID3).text(textInvalidDiv);
      } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text(textInvalidDiv);
  
        $("#" + inputID2).removeClass("is-invalid");
        $("#" + invalidDivID2).hide();
        $("#" + invalidDivID2).text(textInvalidDiv);
  
        $("#" + inputID3).removeClass("is-invalid");
        $("#" + invalidDivID3).hide();
        $("#" + invalidDivID3).text(textInvalidDiv);
        redFlag = false;
      }
    return redFlag; 
}

function filtra_historicoCPC(seleccion,fecha_desde,fecha_hasta){
    if(!validarImputs()){
        if(fecha_desde==""){
            fecha_desde="no"
        }
        if(fecha_hasta==""){
            fecha_hasta="no"
        }
        $.post("functions/get_totales",{
            selection:3,
            fecha_desde : fecha_desde,
            fecha_hasta : fecha_hasta
            },function(response){
                var res = JSON.parse(response);
                $('#txt_total_facturado').html(res.total_facturado);
                $('#txt_total_noFacturado').html(res.total_noFacturado);
            }
        );
        $.ajax({
          type: "POST", 
          url: "functions/function_filtra_historico.php",
          data:{
            seleccion : seleccion,
            fecha_desde : fecha_desde,
            fecha_hasta : fecha_hasta
          }, 
          async: true, 
          success: function(respuesta){
            var json = JSON.parse(respuesta);
            $("#tblHistorico").DataTable({
              destroy: true,
              language: idioma_espanol,
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
                buttons: topButtonsHistorico,
              },
              columns: [
                { data: "Cliente" },
                { data: "Folio factura" },
                { data: "F de expedicion" },
                { data: "F de vencimiento" },
                { data: "Estado" },
                { data: "Monto total" },
                { data: "Monto pagado" },
                { data: "Parcialidades", width: "50px" },
                { data: "Monto notas credito" },
                { data: "Monto insoluto" },
                { data: "Complementos" },
                { data: "Notas credito" },
                { data: "Ver", width: "20px"},
                { data: "Id" },
              ],
              columnDefs: [
                {
                  targets: [13],
                  visible: false,
                  searchable: false,
                },
                {
                  targets: [2, 4, 5, 6, 7, 8, 9, 12],
                  searchable: false,
                },
              ],
              data : json.data
            });

            //se muestra el total
            if(json.isManyClientes==0){
              $("#lblTotal").text(json.total);
              $("#totalCuentas").removeClass("oculto");
            }else{
              $("#lblTotal").text(0);
              $("#totalCuentas").addClass("oculto");
            }
          },
          error: function (request, error) {
                    alertify.success(error);
          }
        });
    }
}

function filtra_cuentasCliente(cliente, periodo, seleccion, fecha_desde, fecha_hasta){
    
    if(!validarImputs()){
        if(fecha_desde==""){
            fecha_desde="no"
        }
        if(fecha_hasta==""){
            fecha_hasta="no"
        }
        tablac.ajax.url("functions/function_filtra_cuentaCliente.php?cliente_id="+cliente+"&periodo="+periodo+"&seleccion="+seleccion+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta).load();
    }
}
