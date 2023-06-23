function setFormatDatatables(){
  var idioma_espanol = {
    "searchPlaceholder": "Buscar...",
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "",//Mostrar _MENU_ registros
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Agregue productos a la tabla",
    "sInfo":           "",//Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros
    "sInfoEmpty":      "",//Mostrando registros del 0 al 0 de un total de 0 registros
    "sInfoFiltered":   "",//(filtrado de un total de _MAX_ registros)
    "sInfoPostFix":    "",
    "sSearch":         "",//Buscar:
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    /*"oPaginate": {
        "sFirst":    "",//Primero
        "sLast":     "",//Último
        "sNext":     "<img src='../../../../img/icons/pagination.svg' width='15px'>",
        "sPrevious": "<img src='../../../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
    },*/
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
  }
  return idioma_espanol;
}

$(document).ready(function(){
  console.log("id tipo entrada: ",$('#txtIdTipoEntrada').val());
  var contador = 1;
  var x = 0;
  var table = $("#tblEditarEntradasProductos").DataTable({
    //"lengthChange": false,
    //"pageLength": 15,
    //"paging": true,
    //"info": false,
    //"pagingType": "full_numbers",
    "ajax": {url:"../../php/funciones.php",
           data: {clase:"get_data",funcion:"get_productsEntriesTable",id:$('#txtIdEntrada').val()},
           dataType: "json"
         },
    "columns":[
      {"data":"No"},
      {"data":"Codigo"},
      {"data":"Producto"},
      {"data":"Lote"},
      {"data":"Noserie"},
      {"data":"Caducidad"},
      {"data":"Cantidad"}
      //{"data":"Precio_unitario"},
      //{"data":"Precio_total"}
    ],
    "language": setFormatDatatables(),
    columnDefs: [
      { orderable: false, targets: [0,1,2,3,4,5,6] }
    ],
    responsive: true,
  });

  function splitDataTable(data){
    aux = data.split('>');
    console.log("split aux:",aux);
    aux1 = aux[1].split('<');
    console.log("split aux1:",aux1);
    return aux1[0];
  }

  $('#btnAgregarProducto').on('click',function(){
    if($('#cmbProducto').val() !== "" && ($('#txtLote').val() !== "" || $('#txtNoSerie').val() !== "") && $('#txtCantidad').val() !== "") {
      var countRows = table.rows().count();
      var ban = 0;
      var date;
      var dateFormat;
      var rows = table.rows().data();
      var prod = $("#cmbProducto option:selected").text().split(".- ");
      if($('#txtCaducidad').val() !== ""){
        date = new Date($('#txtCaducidad').val());
        console.log($('#txtCaducidad').val());
        console.log(date);
        var day = numeral((date.getDate()+1)).format('00');
        var month = numeral((date.getMonth()+1)).format('00');
        var year = date.getFullYear();
        //dateFormat = date.getDate()+"/"+date.getMonth()+"/"+date.getFullYear();
        dateFormat = day+"/"+month+"/"+year;
      }else{
        dateFormat = "";
      }

      var quantity = numeral($('#txtCantidad').val()).format('0,0');
      //var pu = numeral($('#txtPrecioUnitario').val()).format('0,0.00');
      //var pt = numeral($('#txtPrecioTotal').val()).format('0,0.00');


      for (var i = 0; i < countRows; i++) {

        codigo = splitDataTable(rows[i].Codigo);
        lote = splitDataTable(rows[i].Lote);
        noSerie = splitDataTable(rows[i].Noserie);

        console.log("si hay productos con el mismo codigo, codigo: ",codigo);
        console.log("si hay productos con el mismo codigo, combo: ",$('#cmbProducto').val());
        if(codigo === $('#cmbProducto').val()){
          if($('#txtLote').val() !== "" && lote === $('#txtLote').val()){
            cantidadAux = splitDataTable(rows[i].Cantidad);

            cantidad = numeral(parseInt(cantidadAux) + parseInt($('#txtCantidad').val())).format('0,0');
            //precioTotal = numeral(cantidad * pu).format('0,0.00');
            ban = 1;
            table.cell({row:i,column:6}).data('<label class="textTable">'+cantidad+'</label>');
            //table.cell({row:i,column:8}).data(precioTotal);
          }else if($('#txtNoSerie').val() !== "" && noSerie === $('#txtNoSerie').val()){
            cantidadAux = splitDataTable(rows[i].Cantidad);

            cantidad = numeral(parseInt(cantidadAux) + parseInt($('#txtCantidad').val())).format('0,0');
            //precioTotal = numeral(cantidad * pu).format('0,0.00');
            ban = 1;
            table.cell({row:i,column:6}).data('<label class="textTable">'+cantidad+'</label>');
            //table.cell({row:i,column:8}).data(precioTotal);
          }
        }
      }
      if(ban === 0){
        table.row.add({
          "No":'<label class="textTable">'+contador+'</label>',
          "Codigo":'<label class="textTable">'+$('#cmbProducto').val()+'</label>',
          "Producto":'<label class="textTable">'+prod[1]+'</label>',
          "Lote":'<label class="textTable">'+$('#txtLote').val()+'</label>',
          "Noserie":'<label class="textTable">'+$('#txtNoSerie').val()+'</label>',
          "Caducidad":'<label class="textTable">'+dateFormat+'</label>',
          "Cantidad":'<label class="textTable">'+quantity+'</label>'
          //"Precio_unitario":pu,
          //"Precio_total":pt
        }).draw();
      }

      contador++;
      $('#agregar_producto').modal('toggle');


      if($('#chkLote').is(':checked')){
        $('#chkLote').prop('checked',false);
        $('#chkNoSerie').prop('disabled',false);
        $('#txtLote').css('display','none');
      }
      if($('#chkNoSerie').is(':checked')){
        $('#chkNoSerie').prop('checked',false);
        $('#chkLote').prop('disabled',false);
        $('#txtNoSerie').css('display','none');
      }
      //$('#chkLote').prop('checked',false);
      //$('#chkNoSerie').prop('checked',false);
      $('#txtLote').val("");
      $('#txtNoSerie').val("");
      $('#txtCaducidad').val("");
      $('#txtCantidad').val("");
      $('#txtPrecioTotal').val("");
    }else if($('#cmbProducto').val() === ""){
      Swal.fire({
        title:'Datos obligatorios no ingresados',
        html: 'El producto es un dato obligatorio.',
        icon: 'error',
        showConfirmButton: true,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#txtLote').val() === ""){
      Swal.fire({
        title: 'Datos obligatorios no ingresados',
        html: 'El lote es un dato obligatorio.',
        icon: 'error',
        showConfirmButton: true,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#txtCantidad').val() === ""){
      Swal.fire({
        title: 'Datos obligatorios no ingresados',
        html: 'La cantidad es un dato obligatorio.',
        icon: 'error',
        showConfirmButton: true,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }
  });
});

//Carga combo tipo de entradas
function loadTypeEntries(data,input,input1){
  var value ='';
  //var id = '';
  var selected;

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_typeEntry",data:data},
  	dataType:"json",
    success:function(id){
      $.ajax({
        url:"../../php/funciones.php",
      	data:{clase:"get_data", funcion:"get_typeEntries"},
      	dataType:"json",
        success:function(respuesta){
          console.log("data:",data);
          console.log("recuperacion id tipo entrada: ",id);
          var tipoEntrada = id[0].FKTipoEntrada;
          var almacen = id[0].FKAlmacen;

          var proveedor = id[0].FKProveedor;
          var notas = id[0].Notas;
          var documento = id[0].Documento;

          var cliente = id[0].FKCliente;
          var ticket = id[0].Ticket;

          var ordenFabricacion = id[0].FKOrdenFabricacion;

          var salida = id[0].FKSalidaInventario;

          console.log("respuesta tipo de entradas combo:",respuesta);

          $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
          $('.data-container .repayment-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
          $('.data-container .both-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
          $('.data-container .manufacturingInput-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
          $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
          $('.button-container').css({'display':'none','opacity': '0','visibility': 'hidden'});
          $('.data-container .adjustment-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

          console.log("recuperacion id tipo entrada: ",tipoEntrada);
          console.log("recuperacion almacen: ",almacen);
          console.log("recuperacion proveedor: ",proveedor);
          console.log("recuperacion notas: ",notas);
          console.log("recuperacion documento: ",documento);
          $.each(respuesta,function(i){

            if(tipoEntrada === respuesta[i].PKTipoEntrada){
              value = respuesta[i].TipoEntrada;
              id = respuesta[i].PKTipoEntrada;
            }
            //html += '<option value="'+respuesta[i].PKTipoEntrada+'" '+selected+'>'+respuesta[i].TipoEntrada+'</option>';

          });
          //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
          //console.log("Array estado civil",civilState);

          $('#'+input+'').val(value);
          $('#'+input1+'').val(id);

          console.log("id tipo entrada:",$('#txtIdTipoEntrada').val());
          console.log("id tipo entrada:",$('#txtTipoEntrada').val());

          /*var table = $("#tblEditarEntradasProductos").DataTable({

          });*/
          var entrada = "";
          console.log($('#txtIdTipoEntrada').val());
          switch($('#txtIdTipoEntrada').val()){
            case "1":
              entrada += "C"
            break;
            case "2":
              entrada += "D"
            break;
            case "3":
              entrada += "F"
            break;
            case "4":
              entrada += "A"
            break;
            case "5":
              entrada += "T";
            break;
          }
          entrada += zfill($('#txtIdEntrada').val(),5);
          console.log(entrada);

          $('.lblPageTitle').append(" "+entrada);

          if($('#txtIdTipoEntrada').val() === "1"){

            loadWarehouses(almacen,"cmbAlmacen");
            loadPurchaseOrder("","cmbOrdenCompra");
            loadProvider(proveedor,"cmbProveedor");

            $('#txaNotaEntrada').val(notas);
            $('#txtReferencia').val(documento);
            $('.button-container').css({'display':'flex','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            $('.data-container .purchases-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            //$('.data-container .both-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
          }else if($('#txtIdTipoEntrada').val() === "2"){

            $.ajax({
              url:"../../php/funciones.php",
              data:{clase:"get_data", funcion:"get_idEntries"},
              dataType:"json",
              success:function(respuesta){
                console.log("respuesta desde tipo de entrada devolucion: ",respuesta);
                loadClients(cliente,"cmbCliente");

                loadOutputsClient(documento,'cmbDocumento',cliente);

                loadWarehouses(almacen,"cmbAlmacen2");
                loadQualityTicket(ticket,"cmbTicketCalidad");
              },
              error:function(error){
                console.log(error);
              }
            });
            $('.button-container').css({'display':'flex','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            $('.data-container .repayment-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            //$('.data-container .both-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
          }else if($('#txtIdTipoEntrada').val() === "3"){
            loadManufacturingInput(ordenFabricacion,'cmbOrdenFabricacion');
            loadWarehouses(almacen,"cmbAlmacen3");
            $('.button-container').css({'display':'flex','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            $('.data-container .manufacturingInput-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});

          }else if($('#txtIdTipoEntrada').val() === "5"){
            loadOutputs(salida,'cmbSalida');
            loadWarehouses(almacen,'cmbAlmacen5');
            $('.button-container').css({'display':'flex','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            $('.data-container .transfer-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
            //$('.data-container .both-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
          }
        },
        error:function(error){
          console.log(error);
        }

      });

    },
    error:function(error){
      console.log(error);
    }
  });


}

function loadOutputs(data,input){
  var html ='<option value="" selected>Seleccione una salida...</option>';
  var selected;
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_outputsSelect"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta salidas combo:",respuesta);

      $.each(respuesta,function(i){
        console.log("folio tipo de entradas combo: "+respuesta[i].Folio);
        if(data === respuesta[i].PKSalidaInventario){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKSalidaInventario+'" '+selected+'>'+respuesta[i].PKSalidaInventario+'</option>';
      });
      //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function loadWarehouses(data,input){
  var html ='<option value="" selected>Seleccione un almacén...</option>';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_warehouses"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta almacenes combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKAlmacen){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKAlmacen+'" '+selected+'>'+respuesta[i].Almacen+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function loadPurchaseOrder(data,input){
  var html ='<option value="" selected>Seleccione una orden de compra...</option>';
  /*
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_manufacturingInput"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta orden de fabricacion combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKAlmacen){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKAlmacen+'" '+selected+'>'+respuesta[i].Almacen+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay órdenes de fabricación que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
  */
  $('#'+input+'').html(html);
}

function loadProvider(data,input){
  var html ='<option value="" selected>Seleccione un proveedor...</option>';

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_provider"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta orden de fabricacion combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKProveedor){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKProveedor+'" '+selected+'>'+respuesta[i].NombreComercial+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay órdenes de fabricación que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadOutputsClient(data,input,id){
  var html ='<option value="" selected>Seleccione una salida...</option>';
  var selected;
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_outputsClient", data:id},
  	dataType:"json",
    success:function(respuesta){
      console.log("data desde salidas cliente: ",data);
      console.log("respuesta salidas combo:",respuesta);

      $.each(respuesta,function(i){
        console.log("folio tipo de entradas combo: "+respuesta[i].Folio);
        if(parseInt(data) === respuesta[i].PKSalidaInventario){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKSalidaInventario+'" '+selected+'>'+respuesta[i].PKSalidaInventario+'</option>';
      });
      //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function loadClients(data,input){
  var html ='<option value="" selected>Seleccione un cliente...</option>';
  var selected;

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_clientsSelect"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta tipo de entradas combo:",respuesta);

      $.each(respuesta,function(i){
        console.log("folio tipo de entradas combo: "+respuesta[i].Folio);
        if(data === respuesta[i].PKCliente){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKCliente+'" '+selected+'>'+respuesta[i].NombreComercial+'</option>';
      });
      //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
  $('#'+input+'').html(html);
}

function loadQualityTicket(data,input){
  var html ='<option value="" selected>Seleccione un ticket de calidad...</option>';
  /*
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_manufacturingInput"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta orden de fabricacion combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKAlmacen){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKAlmacen+'" '+selected+'>'+respuesta[i].Almacen+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay órdenes de fabricación que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
  */
  $('#'+input+'').html(html);
}

function loadManufacturingInput(data,input){
  var html ='<option value="" selected>Seleccione una orden de fabricacion...</option>';

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_manufacturingInput"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta orden de fabricacion combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKOrdenFabricacion){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKOrdenFabricacion+'" '+selected+'>'+respuesta[i].PKOrdenFabricacion+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay órdenes de fabricación que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadProductos(data,input){
  var html ='<option value="" selected>Seleccione un código...</option>';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_products"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta productos combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKProducto){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKProducto+'" '+selected+'>'+respuesta[i].PKProducto+'.- '+respuesta[i].Producto+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function loadProductsByOther(data,input,id,type){
  var html ='<option value="" selected>Seleccione un código...</option>';
  var cantidad = "";
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_productsOther",id:id,tipo:type},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta productos combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKProducto){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKProducto+'" '+selected+'>'+respuesta[i].PKProducto+'.- '+respuesta[i].Producto+'</option>';
          cantidad = respuesta[i].Cantidad;
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function zfill(number, width) {
    var numberOutput = Math.abs(number); /* Valor absoluto del número */
    var length = number.toString().length; /* Largo del número */
    var zero = "0"; /* String de cero */

    if (width <= length) {
        if (number < 0) {
             return ("-" + numberOutput.toString());
        } else {
             return numberOutput.toString();
        }
    } else {
        if (number < 0) {
            return ("-" + (zero.repeat(width - length)) + numberOutput.toString());
        } else {
            return ((zero.repeat(width - length)) + numberOutput.toString());
        }
    }
}

//carga de elementos en general
$(document).ready(function(){

  new SlimSelect({
    select: '#cmbSalida'
  });
  new SlimSelect({
    select: '#cmbAlmacen5'
  });
  new SlimSelect({
    select: '#cmbAlmacen'
  });
  new SlimSelect({
    select: '#cmbOrdenCompra'
  });
  new SlimSelect({
    select: '#cmbProveedor'
  });
  new SlimSelect({
    select: '#cmbCliente',
    deselectLabel: '<span class="">✖</span>',
  });
  new SlimSelect({
    select: '#cmbDocumento',
    deselectLabel: '<span class="">✖</span>',
  });
  new SlimSelect({
    select: '#cmbAlmacen2'
  });
  new SlimSelect({
    select: "#cmbTicketCalidad",
    deselectLabel: '<span class="">✖</span>'
  });
  new SlimSelect({
    select: '#cmbOrdenFabricacion',
    deselectLabel: '<span class="">✖</span>'
  });
  new SlimSelect({
    select: '#cmbAlmacen3',
    deselectLabel: '<span class="">✖</span>'
  });
  new SlimSelect({
    select: '#cmbProducto',
    deselectLabel: '<span class="">✖</span>'
  });

  loadTypeEntries($('#txtIdEntrada').val(),'txtTipoEntrada','txtIdTipoEntrada');

  var input = document.getElementById('fileXml'),
      button = document.getElementById('fileXml_alt');

  button.addEventListener('click',function() {
    input.click();
  }, false);

  $('#fileXml').on('change',function(){
  //document.getElementById('fileXml').onchange = function () {
    var file = $('#fileXml')[0].files[0];
    console.log(file.name);

    $('#output').html("<label for='fileXml'>XML:</label> "+file.name);

    if($('#fileXml').val() !== ""){
      var file = $('#fileXml').prop('files')[0];
      console.log("archivo seleccionado: ",file.name);
      var cadena = new FormData();
      cadena.append("file",file);
      $.ajax({
        url:"../../php/guardar_archivos.php",
        data:cadena,
        //enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        type: "post",
        dataType: "json",
        success:function(response){
          idProveedorForm = response.Id[0].PKProveedor;
          console.log(response);
          console.log("respuesta cargar folio: ",response.Folio[0]);
          console.log("respuesta cargar rfc: ",response.Rfc[0]);
          console.log("respuesta cargar id proveedor: ",idProveedorForm);


          $('#txtReferencia').val(response.Folio[0]);
          $('#cmbProveedor').val(idProveedorForm);
          loadProvider(idProveedorForm,'cmbProveedor');
          console.log($('#cmbProveedor').val(idProveedorForm));

        }
      });

    }else{
      console.log("seleccione un xml");
    }

  });

});

$(document).on('change','#chkLote',function(){
  if ( $(this).is(':checked') ) {
    console.log("seleccionado");
    $('#txtLote').css("display","block");
    $('#txtLote').attr("required",true);
    $('#txtNoSerie').css("display","none");
    $('#txtNoSerie').attr("required",false);
    $('#chkNoSerie').attr("disabled",true);
    $(this).attr('checked',true);
  }else{
    console.log("no seleccionado");
    $('#txtLote').css("display","none");
    $('#txtLote').attr("required",false);
    $('#chkNoSerie').attr("disabled",false);
    $(this).attr('checked',false);
  }
});

$(document).on('click','#btnModalAgregarProducto',function(){
  switch($('#txtIdTipoEntrada').val()){
    case "1":
      $('#agregar_producto').modal('toggle');
      loadProductos('','cmbProducto');
    break;
    case "2":
      $('#agregar_producto').modal('toggle');
      loadProductsByOther("","cmbProducto",$('#cmbDocumento').val(),2);
    break;
    case "3":
      $('#agregar_producto').modal('toggle');
      loadProductsByOther("","cmbProducto",$('#cmbOrdenFabricacion').val(),3);
    break;
    case "4":
      $('#agregar_producto').modal('toggle');
    break;
    case "5":
      $('#agregar_producto').modal('toggle');
    break;
  }
});

$(document).on('click','#btnEditarEntrada',function(){
  console.log($('#txtIdTipoEntrada').val());
  var tipoEntrada = $('#txtIdTipoEntrada').val();
  var idEntrada = $('#txtIdEntrada').val();
  console.log("tipo entrada: ",tipoEntrada);
  console.log("idEntrada:",idEntrada);
  /* Eliminar archivo temporal */
  if($('#fileXml').val()!== ""){
    var file = $('#fileXml')[0].files[0];
    console.log("file desde editar entrada: ",file.name);
    $.ajax({
      url:"../../php/funciones.php",
    	data:{clase:"delete_file", funcion:"delete_xml",data:file.name},
    	dataType:"json",
      success:function(respuesta){
        console.log("respuesta desde agregar entrada: ",respuesta);
      },
      error:function(error){
        console.log(error);
      }
    });
  }
  /* Termina eliminar archivo temporal */

  /* Enviar productos para guardar */
  var table = $('#tblEditarEntradasProductos').DataTable();

  var productos = {"Codigo":[],"Lote":[],"Noserie":[],"Caducidad":[],"Cantidad":[]};

  if(tipoEntrada === '1'){
    var arrayGeneral = {"idEntrada":[],"proveedor":[],"referencia":[],"almacen":[],"productos":[],"fecha":[],"usuario":[],"notas":[],"tipoEntrada":[]};

    if($('#cmbProveedor').val() !== "" && $('#txtReferencia').val() !== "" && $('#cmbAlmacen').val() !== "" && table.data().count()) {
      var provider = $('#cmbProveedor').val();
      var reference = $('#txtReferencia').val();
      var warehouse = $('#cmbAlmacen').val();

      var countRows = table.rows().count();
      var rows = table.rows().data();
      var arrayCode = [];
      var arrayLot = [];
      var arrayNoSerie = [];
      var arrayExpiration = [];
      var arrayQuantities = [];
      var arrayUnitPrice = [];
      var arrayTotalPrice = [];

      for (var i = 0; i < countRows; i++) {
        aux = rows[i].Codigo.split('>');
        aux1 = aux[1].split('<');
        codigo = aux1[0];
        arrayCode.push(codigo);


        aux = rows[i].Lote.split('>');
        aux1 = aux[1].split('<');
        lote = aux1[0];

        if(lote !== ""){
          arrayLot.push(lote);
        }else{
            arrayLot.push("null");
        }

        aux = rows[i].Noserie.split('>');
        aux1 = aux[1].split('<');
        noSerie = aux1[0];

        if(noSerie){
          arrayNoSerie.push(noSerie);
        }else{
          arrayNoSerie.push("null");
        }

        aux = rows[i].Caducidad.split('>');
        aux1 = aux[1].split('<');
        caducidad = aux1[0];
        arrayExpiration.push(caducidad);

        aux = rows[i].Cantidad.split('>');
        aux1 = aux[1].split('<');
        cantidad = aux1[0];
        arrayQuantities.push(cantidad);
      }
      d = new Date();
      date = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

      arrayGeneral["proveedor"].push(provider);
      arrayGeneral["referencia"].push(reference);
      arrayGeneral["almacen"].push(warehouse);
      arrayGeneral["fecha"].push(date);
      arrayGeneral["usuario"].push($('#usuario').val());
      arrayGeneral["notas"].push($('#txaNotaEntrada').val());
      arrayGeneral["tipoEntrada"].push(tipoEntrada);
      arrayGeneral["idEntrada"].push(idEntrada);

      console.log("usuario:",$('#usuario').val());

      console.log("array general antes:",arrayGeneral);

      productos["Codigo"].push(arrayCode);
      productos["Lote"].push(arrayLot);
      productos["Noserie"].push(arrayNoSerie);
      productos["Caducidad"].push(arrayExpiration);
      productos["Cantidad"].push(arrayQuantities);

      console.log("productos: ",productos);

      arrayGeneral["productos"].push(productos);

      console.log('array general antes: ',arrayGeneral);

      $.ajax({
        url:"../../php/funciones.php",
      	data:{clase:"edit_data", funcion:"edit_entry",data:arrayGeneral},
      	dataType:"json",
        success:function(respuesta){
          window.location.href = "index.php";
        },
        error:function(error){
          Swal.fire({
            title: '<h3 style="arialRoundedEsp;">Error en base de datos<h3>',
            html: '<h5 style="arialRoundedEsp;">Ocurrió un error al ingresar los datos a la base de datos.<br>'+error+'<h5>',
            icon: 'error',
            showConfirmButton: true,
            focusConfirm: false,
            showCloseButton: false,
            showCancelButton: false,
            confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
            buttonsStyling: false,
            allowEnterKey: false
          });
          console.log(error);
        }
      });
    }else if($('#cmbProveedor').val() === ""){
        Swal.fire({
          title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
          html: '<h5 style="arialRoundedEsp;">El campo proveedor es obligatorio.<h5>',
          icon: 'error',
          showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#txtReferencia').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo documento es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#cmbAlmacen').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo almacén es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else{
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">La tabla está vacía<h3>',
        html: '<h5 style="arialRoundedEsp;">Se debe de agregar productos a la tabla.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }
  }else if(tipoEntrada === '2') {
    console.log("tipo entrada: ",idEntrada);

    if($('#cmbCliente').val() !== "" && $('#cmbDocumento').val() !== "" && $('#cmbAlmacen2').val() !== "" && table.data().count()) {
      var arrayGeneral = {"idEntrada":[],"cliente":[],"documento":[],"almacen":[],"ticket":[],"notas":[],"usuario":[],"tipoEntrada":[],"productos":[],"fecha":[]};
      var cliente = $('#cmbCliente').val();
      var doc = $('#cmbDocumento').val();
      var almacen = $('#cmbAlmacen2').val();
      var ticket = $('#cmbTicketCalidad').val();
      var notas = $('#txaNotaDevolucionVenta').val();
      //var tipoEntrada = $('#cmbTipoEntrada').val();
      var usuario = $('#usuario').val();

      var countRows = table.rows().count();
      var rows = table.rows().data();
      var arrayCode = [];
      var arrayLot = [];
      var arrayNoSerie = [];
      var arrayExpiration = [];
      var arrayQuantities = [];
      var arrayUnitPrice = [];
      var arrayTotalPrice = [];

      for (var i = 0; i < countRows; i++) {
        aux = rows[i].Codigo.split('>');
        aux1 = aux[1].split('<');
        codigo = aux1[0];
        arrayCode.push(codigo);

        aux = rows[i].Lote.split('>');
        aux1 = aux[1].split('<');
        lote = aux1[0];

        if(lote !== ""){
          arrayLot.push(lote);
        }else{
            arrayLot.push("null");
        }

        aux = rows[i].Noserie.split('>');
        aux1 = aux[1].split('<');
        noSerie = aux1[0];

        if(noSerie){
          arrayNoSerie.push(noSerie);
        }else{
          arrayNoSerie.push("null");
        }

        aux = rows[i].Caducidad.split('>');
        aux1 = aux[1].split('<');
        caducidad = aux1[0];
        arrayExpiration.push(caducidad);

        aux = rows[i].Cantidad.split('>');
        aux1 = aux[1].split('<');
        cantidad = aux1[0];
        arrayQuantities.push(cantidad);
      }
      d = new Date();
      date = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

      arrayGeneral["cliente"].push(cliente);
      arrayGeneral["documento"].push(doc);
      arrayGeneral["almacen"].push(almacen);
      arrayGeneral["ticket"].push(ticket);
      arrayGeneral["notas"].push(notas);
      arrayGeneral["usuario"].push(usuario);
      arrayGeneral["tipoEntrada"].push(tipoEntrada);
      arrayGeneral["fecha"].push(date);
      arrayGeneral["idEntrada"].push(idEntrada);

      productos["Codigo"].push(arrayCode);
      productos["Lote"].push(arrayLot);
      productos["Noserie"].push(arrayNoSerie);
      productos["Caducidad"].push(arrayExpiration);
      productos["Cantidad"].push(arrayQuantities);

      arrayGeneral["productos"].push(productos);

      console.log("array general completo:",arrayGeneral);

      $.ajax({
        url:"../../php/funciones.php",
      	data:{clase:"edit_data", funcion:"edit_entry",data:arrayGeneral},
      	dataType:"json",
        success:function(respuesta){
          window.location.href = "index.php";
        },
        error:function(error){
          Swal.fire({
            title: '<h3 style="arialRoundedEsp;">Error en base de datos<h3>',
            html: '<h5 style="arialRoundedEsp;">Ocurrió un error al ingresar los datos a la base de datos.<br>'+error+'<h5>',
            icon: 'error',
            showConfirmButton: true,
            focusConfirm: false,
            showCloseButton: false,
            showCancelButton: false,
            confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
            buttonsStyling: false,
            allowEnterKey: false
          });
          console.log(error);
        }
      });
    }else if($('#cmbCliente').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo cliente es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#cmbDocumento').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo documento es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#cmbAlmacen2').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo almacén es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else{
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">La tabla está vacía<h3>',
        html: '<h5 style="arialRoundedEsp;">Se debe de agregar productos a la tabla.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }
  }else if(tipoEntrada === '3') {
    var arrayGeneral = {"ordenFabricacion":[],"almacen":[],"usuario":[],"tipoEntrada":[],"fecha":[],"productos":[]};
    if($('#cmbOrdenFabricacion').val() !== "" && $('#cmbAlmacen3').val() !== "" && table.data().count()){
      var ordenFabricacion = $('#cmbOrdenFabricacion').val();
      var almacen = $('#cmbAlmacen3').val();
      var usuario = $('#usuario').val();
      var tipoEntrada = $('#cmbTipoEntrada').val();

      var countRows = table.rows().count();
      var rows = table.rows().data();
      var arrayCode = [];
      var arrayLot = [];
      var arrayNoSerie = [];
      var arrayExpiration = [];
      var arrayQuantities = [];
      var arrayUnitPrice = [];
      var arrayTotalPrice = [];

      for (var i = 0; i < countRows; i++) {
        aux = rows[i].Codigo.split('>');
        aux1 = aux[1].split('<');
        codigo = aux1[0];
        arrayCode.push(codigo);

        aux = rows[i].Lote.split('>');
        aux1 = aux[1].split('<');
        lote = aux1[0];

        if(lote !== ""){
          arrayLot.push(lote);
        }else{
            arrayLot.push("null");
        }

        aux = rows[i].Noserie.split('>');
        aux1 = aux[1].split('<');
        noSerie = aux1[0];

        if(noSerie){
          arrayNoSerie.push(noSerie);
        }else{
          arrayNoSerie.push("null");
        }

        aux = rows[i].Caducidad.split('>');
        aux1 = aux[1].split('<');
        caducidad = aux1[0];
        arrayExpiration.push(caducidad);

        aux = rows[i].Cantidad.split('>');
        aux1 = aux[1].split('<');
        cantidad = aux1[0];
        arrayQuantities.push(cantidad);
      }
      d = new Date();
      date = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

      arrayGeneral["ordenFabricacion"].push(ordenFabricacion);
      arrayGeneral["almacen"].push(almacen);
      arrayGeneral["usuario"].push(usuario);
      arrayGeneral["tipoEntrada"].push(tipoEntrada);
      arrayGeneral["fecha"].push(date);

      productos["Codigo"].push(arrayCode);
      productos["Lote"].push(arrayLot);
      productos["Noserie"].push(arrayNoSerie);
      productos["Caducidad"].push(arrayExpiration);
      productos["Cantidad"].push(arrayQuantities);

      arrayGeneral["productos"].push(productos);

      $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"edit_data", funcion:"edit_entry",data:arrayGeneral},
        dataType:"json",
        success:function(respuesta){
          window.location.href = "index.php";
        },
        error:function(error){
          Swal.fire({
            title: '<h3 style="arialRoundedEsp;">Error en base de datos<h3>',
            html: '<h5 style="arialRoundedEsp;">Ocurrió un error al ingresar los datos a la base de datos.<br>'+error+'<h5>',
            icon: 'error',
            showConfirmButton: true,
            focusConfirm: false,
            showCloseButton: false,
            showCancelButton: false,
            confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
            buttonsStyling: false,
            allowEnterKey: false
          });
          console.log(error);
        }
      });
    }else if($('#cmbOrdenFabricacion').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo orden de fabicación es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#cmbAlmacen3').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo almacén es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else{
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">La tabla está vacía<h3>',
        html: '<h5 style="arialRoundedEsp;">Se debe de agregar productos a la tabla.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }
  }else if($('#cmbTipoEntrada').val() === '5'){
    var arrayGeneral = {"salida":[],"almacen":[],"usuario":[],"tipoEntrada":[],"fecha":[],"productos":[]};
    if($('#cmbSalida').val() !== "" && $('#cmbAlmacen5').val() !== "" && table.data().count()){
      var salida = $('#cmbSalida').val();
      var almacen = $('#cmbAlmacen5').val();
      var usuario = $('#usuario').val();
      var tipoEntrada = $('#cmbTipoEntrada').val();

      var countRows = table.rows().count();
      var rows = table.rows().data();
      var arrayCode = [];
      var arrayLot = [];
      var arrayNoSerie = [];
      var arrayExpiration = [];
      var arrayQuantities = [];
      var arrayUnitPrice = [];
      var arrayTotalPrice = [];

      for (var i = 0; i < countRows; i++) {
        aux = rows[i].Codigo.split('>');
        aux1 = aux[1].split('<');
        codigo = aux1[0];
        arrayCode.push(codigo);

        aux = rows[i].Lote.split('>');
        aux1 = aux[1].split('<');
        lote = aux1[0];

        if(lote !== ""){
          arrayLot.push(lote);
        }else{
            arrayLot.push("null");
        }

        aux = rows[i].Noserie.split('>');
        aux1 = aux[1].split('<');
        noSerie = aux1[0];

        if(noSerie){
          arrayNoSerie.push(noSerie);
        }else{
          arrayNoSerie.push("null");
        }

        aux = rows[i].Caducidad.split('>');
        aux1 = aux[1].split('<');
        caducidad = aux1[0];
        arrayExpiration.push(caducidad);

        aux = rows[i].Cantidad.split('>');
        aux1 = aux[1].split('<');
        cantidad = aux1[0];
        arrayQuantities.push(cantidad);
      }
      d = new Date();
      date = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

      arrayGeneral["salida"].push(salida);
      arrayGeneral["almacen"].push(almacen);
      arrayGeneral["usuario"].push(usuario);
      arrayGeneral["tipoEntrada"].push(tipoEntrada);
      arrayGeneral["fecha"].push(date);

      productos["Codigo"].push(arrayCode);
      productos["Lote"].push(arrayLot);
      productos["Noserie"].push(arrayNoSerie);
      productos["Caducidad"].push(arrayExpiration);
      productos["Cantidad"].push(arrayQuantities);

      arrayGeneral["productos"].push(productos);

      $.ajax({
        url:"../../php/funciones.php",
      	data:{clase:"edit_data", funcion:"edit_entry",data:arrayGeneral},
      	dataType:"json",
        success:function(respuesta){
          window.location.href = "index.php";
        },
        error:function(error){
          Swal.fire({
            title: '<h3 style="arialRoundedEsp;">Error en base de datos<h3>',
            html: '<h5 style="arialRoundedEsp;">Ocurrió un error al ingresar los datos a la base de datos.<br>'+error+'<h5>',
            icon: 'error',
            showConfirmButton: true,
            focusConfirm: false,
            showCloseButton: false,
            showCancelButton: false,
            confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
            buttonsStyling: false,
            allowEnterKey: false
          });
          console.log(error);
        }
      });
    }else if($('#cmbSalida').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo salida es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else if($('#cmbAlmacen5').val() === ""){
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">Campo obligatorio vacío<h3>',
        html: '<h5 style="arialRoundedEsp;">El campo almacén es obligatorio.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }else{
      Swal.fire({
        title: '<h3 style="arialRoundedEsp;">La tabla está vacía<h3>',
        html: '<h5 style="arialRoundedEsp;">Se debe de agregar productos a la tabla.<h5>',
        icon: 'error',
        showConfirmButton: true,
        focusConfirm: false,
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
        buttonsStyling: false,
        allowEnterKey: false
      });
    }

  }
  /* Termina enviar productos para guardar */

});
