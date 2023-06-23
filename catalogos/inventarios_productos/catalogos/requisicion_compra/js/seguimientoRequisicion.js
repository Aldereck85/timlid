 //arreglo que contiene el detalle de los productos de la requisición
 var arrDataProds = {};
 //arreglo que contiene los impuestos por producto
 var arrTaxesProds = {};
 //arreglo con los impuestos de tipo ieps por producto
 var arrIepsProds = {};

 
 var idRequisicion;
 var estatusRequisicion;
 var totalImpuestos;
 var NumDecimales = 2;


 function LoadData(){
    //se recuperan datos de la cabecera
    $.ajax({
        url: "php/functions.php",
        data: { 
            clase: "get_data", 
            funcion: "get_data_CabeceraSeguimientoRequisicion",
            data: idRequisicion,
        },
        dataType: "json",
        success: function (respuesta) {
            if($.isEmptyObject(respuesta)){
              $("#alert_NotFound").modal("show");
            }

            $("#txtFolio").text(respuesta[0].folio);
            $("#txtFechaEmision").val(respuesta[0].fecha_registro);
            cargarCMBSucursal(respuesta[0].FKSucursal, "cmbDireccionEnvio");        
            $("#txtArea").val(respuesta[0].area);
            $("#txtEmpleado").val(respuesta[0].NombreEmpleado);
            cargarCMBProveedor(respuesta[0].FKProveedor, "cmbProveedor");
            cargarCMBComprador(respuesta[0].comprador,"cmbComprador");
            $("#NotasComprador").val(respuesta[0].notas_comprador);
            
            estatusRequisicion=respuesta[0].estatus;
            var estado="";
            switch(respuesta[0].estatus){
                case 1:
                    estado = '<span class="left-dot turquoise-dot">Pendiente</span>';
                    break;
                case 2:
                    estado = '<span class="left-dot yellow-dot">Parcialmente colocada</span>';
                    break;
                case 3:
                    estado = '<span class="left-dot green-dot">Colocada completa</span>';
                    break;
                case 4:
                    estado = '<span class="left-dot red-dot">Cerrada</span>';
                    break;
                case 0:
                    estado = '<span class="left-dot red-dot">Cancelada</span>';
                    break;
            }

            html = `<h5>Estatus: </h5>`;
            $("#divEstatus").html(html);
            html = `<h5>` + estado + `</h5>`;
            $("#divEstatusSpan").html(html);
        },
        error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: '¡Algo salió mal al recuperar cabecera!',
            sound: '../../../../../sounds/sound4'
        });
        },
    }); 

    //recupera datos de los productos
    $.ajax({
        url: "php/functions.php",
        data: { 
            clase: "get_data", 
            funcion: "get_data_ProductosRequisicion",
            idRequisicion : idRequisicion 
           },
        dataType: "json",
        success: function (respuesta) {
            $.each(respuesta, function (i) {
                var arrAux = {};
                arrAux['id'] = respuesta[i].FKProducto;
                arrAux['restante'] = respuesta[i].restante;
                arrAux['claveSugerida'] = respuesta[i].claveProd;
                arrAux['nombreSugerido'] = respuesta[i].nombreProd;
                arrAux['proveedorSugerido'] = respuesta[i].FKProveedor;
                arrAux['cantidad'] = respuesta[i].restante;
                arrAux['precio'] = respuesta[i].precio;

                arrDataProds[respuesta[i].FKProducto] = Object.assign({}, arrAux);
            });
        },
        error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: '¡Algo salió mal al recuperar datos de productos!',
            sound: '../../../../../sounds/sound4'
        });
        },
    }); 

    //recupera impuestos de los productos
    $.ajax({
      url: "php/functions.php",
      data: { 
          clase: "get_data", 
          funcion: "get_Taxes_ProductosRequisicion",
          idRequisicion : idRequisicion 
         },
      dataType: "json",
      success: function (respuesta) {
        var arrPKIeps = [2, 3];
          $.each(respuesta, function (i) {
              var arrAux = {};
              arrAux['pkImpuesto'] = respuesta[i].pkImpuesto;
              arrAux['FKTipoImpuesto'] = respuesta[i].FKTipoImpuesto;
              arrAux['FKTipoImporte'] = respuesta[i].FKTipoImporte;
              arrAux['nombre'] = respuesta[i].nombre;
              arrAux['tasa'] = respuesta[i].tasa;
              arrAux['FKProducto'] = respuesta[i].FKProducto;
             
              arrTaxesProds[respuesta[i].num] = Object.assign({}, arrAux);

              if(arrPKIeps.includes(arrAux['pkImpuesto'])){
                arrIepsProds[respuesta[i].num] = Object.assign({}, arrAux);
              }
          });
      },
      error: function (error) {
      console.log(error);
      Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: '¡Algo salió mal al recuperar impuestos de productos!',
          sound: '../../../../../sounds/sound4'
      });
      },
  });
 }

 function isComprador(){
    //valida si es comprador
    $.ajax({
      url: "php/functions.php",
      data: { 
          clase: "validate_data", 
          funcion: "validate_isComprador_Requisicion",
      },
      dataType: "json",
      success: function (respuesta) {
          //si el estatus es 1, se puede acceder al seguimiento.
          if(respuesta.seguimiento != 1){
            $("#alert").modal("show");
          }
      },
      error: function (error) {
      console.log(error);
      Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: '¡Algo salió mal al validar datos!',
          sound: '../../../../../sounds/sound4'
      });
      },
    });
 }

 //valida el estatus de la requisicion para que cuando éste sea completo redireccione
 function validaEstatusRequisicion(){
  $.ajax({
   url: "php/functions.php",
   data: { 
       clase: "validate_data", 
       funcion: "validar_estadoRequisicionCompra",
       data: idRequisicion
   },
   dataType: "json",
   success: function (respuesta) {
       //si el estatus es 1, se puede acceder al seguimiento.
       if(respuesta.estatus == 0 || respuesta.estatus == 3 || respuesta.estatus == 4){
         $("#alertSeguimiento").modal("show");
       }
   },
   error: function (error) {
   console.log(error);
   Lobibox.notify("error", {
       size: "mini",
       rounded: true,
       delay: 3000,
       delayIndicator: false,
       position: "center top",
       icon: true,
       img: "../../../../img/timdesk/notificacion_error.svg",
       msg: '¡Algo salió mal al validar datos!',
       sound: '../../../../../sounds/sound4'
   });
   setTimeout(function(){window.history.back()},1500);
   },
 });
}

 //cada que cambia el proveedor se comprueba si ya tiene el producto y si es así se desactivan los campos de clave y producto
 function cambioProveedor() {
   var valor = $("#cmbProveedor").val();
   if (valor!="no") {
     $("#invalid-proveedor").css("display", "none");
     $("#cmbProveedor").removeClass("is-invalid");
   }
 }

 function validaFormulario(){
  if ($("#frmRequisicionCompra")[0].checkValidity()) {
      $("#invalid-producto").css("display","none");
        var badFechaEst =
          $("#invalid-fechaEst").css("display") === "block" ? false : true;
        var badComprador =
          $("#invalid-comprador").css("display") === "block" ? false : true;
        var badSucursal =
          $("#invalid-sucursal").css("display") === "block" ? false : true;
        var badCondicion =
          $("#invalid-condicionPago").css("display") === "block" ? false : true;
        var badMoneda =
          $("#invalid-moneda").css("display") === "block" ? false : true;
        var badProveedor = 
            $("#invalid-proveedor").css("display") === "block" ? false : true;

        if (
          (badFechaEst &&
          badComprador &&
          badSucursal &&
          badCondicion &&
          badMoneda &&
          badProveedor)
        ) {
          return true;
        }
      } else { 
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: '¡Campos incompletos!',
          sound: '../../../../../sounds/sound4'
        });

        if (!$("#txtFechaEstimada").val()) {
          $("#invalid-fechaEst").css("display", "block");
          $("#txtFechaEstimada").addClass("is-invalid");
        }
    
        if (!$("#cmbComprador").val() || $("#cmbComprador").val() < 1) {
          $("#invalid-comprador").css("display", "block");
          $("#cmbComprador").addClass("is-invalid");
        }
    
        if (!$("#cmbDireccionEnvio").val() || $("#cmbDireccionEnvio").val() < 1) {
          $("#invalid-sucursal").css("display", "block");
          $("#cmbDireccionEnvio").addClass("is-invalid");
        }   
        
        if (!$("#cmbCondicionPago").val() || $("#cmbCondicionPago").val() < 1) {
            $("#invalid-condicionPago").css("display", "block");
            $("#cmbCondicionPago").addClass("is-invalid");
        } 

        if (!$("#cmbMoneda").val() || $("#cmbMoneda").val() < 1) {
            $("#invalid-moneda").css("display", "block");
            $("#cmbMoneda").addClass("is-invalid");
        } 

        if (!$("#cmbProveedor").val() || $("#cmbProveedor").val() < 1) {
            $("#invalid-proveedor").css("display", "block");
            $("#cmbProveedor").addClass("is-invalid");
        }

        return false;
      }
 }

 function validaCamposProducto(){
    for (const property in arrDataProds) {
        if(arrDataProds[property]['claveSugerida'] === "no" || arrDataProds[property]['claveSugerida'] === "" || arrDataProds[property]['claveSugerida'] === null){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡Producto sin Clave!',
            sound: '../../../../../sounds/sound4'
          });
          return false;
        }

        if(arrDataProds[property]['nombreSugerido'] === "no" || arrDataProds[property]['nombreSugerido'] === "" || arrDataProds[property]['nombreSugerido'] === null){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡Producto sin Nombre!',
            sound: '../../../../../sounds/sound4'
          });
          return false;
        }

        if(arrDataProds[property]['cantidad'] === "" || arrDataProds[property]['cantidad'] <= 0 || arrDataProds[property]['cantidad'] === null){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡Producto sin Cantidad!',
            sound: '../../../../../sounds/sound4'
          });
          return false;
        }

        if(arrDataProds[property]['precio'] === "" || arrDataProds[property]['precio'] <= 0 || arrDataProds[property]['precio'] === null){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡Producto sin Precio!',
            sound: '../../../../../sounds/sound4'
          });
          return false;
        }
      }
      return true;
 }

 //eliminación de espacios y saltos de linea de referencia
 function eliminaEspaciosSaltos(data){
   aux = data.split("\t").join(" ");
   aux = aux.split("\n").join(" ");
   aux = aux.split(" ");
   data="";
   for (let i = 0; i < aux.length; i++) {
     if(aux[i] !== ""){
      data += aux[i] + " ";
     }
   }
   return data
 }

 //guarda el seguimiento (crea la orden de compra)
 function saveSeguimiento() {
   if(estatusRequisicion !== 0 && estatusRequisicion !== 3 && estatusRequisicion !== 4){
    if(validaFormulario() && validaCamposProducto()){
      if (!$.isEmptyObject(arrDataProds)) {
        // se deshabilita el botón de guardar
        $("#btnAgregar").prop("disabled", true);

        //recupera los datos de la cabecera
        _FechaEstimada = $("#txtFechaEstimada").val();
        _SucursalEntrega = $("#cmbDireccionEnvio").val();
        _Proveedor = $("#cmbProveedor").val();
        _Comprador = $("#cmbComprador").val();
        _CondicionPago = $("#cmbCondicionPago").val();
        _Moneda = $("#cmbMoneda").val();
        _NotasProveedor =  $("#NotasProveedor").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
        _NotasProveedor=eliminaEspaciosSaltos(_NotasProveedor);
            
        //valida tamaño de los campos 
        if(_NotasProveedor.length <= 255){
            $.ajax({
                url: "php/functions.php",
                data: { 
                    clase: "save_data", 
                    funcion: "save_seguimiento",
                    data2: _permissions.add,
                    data3: arrDataProds,
                    data4: _SucursalEntrega,
                    data5: _Proveedor,
                    data6: _Comprador,
                    data7: _NotasProveedor,
                    data8: _CondicionPago,
                    data9: _Moneda,
                    data10: _FechaEstimada,
                    data11: idRequisicion
                },
                dataType: "json",
                success: function (respuesta) {
                    if(respuesta["estatus"] == "ok"){
                      Swal.fire({
                        icon: "success",
                        title: "Registro exitoso",
                        text: "¿Deseas enviarle la orden de compra por correo electrónico al proveedor?",
                        type: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText:
                          'Si <i class="far fa-arrow-alt-circle-right"></i>',
                        cancelButtonText: 'No <i class="far fa-times-circle"></i>',
                        buttonsStyling: false,
                        allowEnterKey: false,
                        customClass: {
                          actions: "d-flex justify-content-around",
                          confirmButton: "btn-custom btn-custom--blue",
                          cancelButton: "btn-custom btn-custom--border-blue",
                        },
                      }).then((result) => {
                        if (result.isConfirmed) {
                          $("#modal_envio").load(
                            "../orden_compras/functions/modal_envio.php?id=" +
                              respuesta['result'].PKProveedor +
                            "&txtId=" +
                              respuesta['result'].idOrden +
                              "&estatus=0&txtNotas=",
                            function () {
                              $("#datos_envio").modal("show");
                            }
                          );
                        } else {
                          window.location.href = "./";
                        }
                      });
                    }else{
                        Lobibox.notify("warning", {
                            size: "mini",
                            rounded: true,
                            delay: 3000,
                            delayIndicator: false,
                            position: "center top",
                            icon: true,
                            img: "../../../../img/timdesk/warning_circle.svg",
                            msg: 'Error al registrar datos',
                            sound: '../../../../../sounds/sound4'
                        });
                        $("#btnAgregar").prop("disabled", false);
                        $("#agregarProducto").prop("disabled", false);
                    }
                },
                error: function (error) {
                console.log(error);
                $("#btnAgregar").prop("disabled", false);
                $("#agregarProducto").prop("disabled", false);
                },
            });
          }else{
              Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/warning_circle.svg",
                  msg: "¡Se ha exidido el numero de caracteres en los campos!",
                  sound: '../../../../../sounds/sound4'
              });
          }   
        }else{
            Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡No hay productos agregados!",
                sound: '../../../../../sounds/sound4'
              });
          } 
    }
   }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede aplicar el seguimiento a ésta requisición!",
      sound: '../../../../../sounds/sound4'
    });
   }
   
 } 
 
 function cargarCMBComprador(data, input) {
    var html = "";
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_comprador" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
        $.each(respuesta, function (i) {
         if(data === respuesta[i].PKComprador){
           selected = 'selected';
         }else{
           selected = '';
         }
          html +=
            `<option value="${respuesta[i].PKComprador}" ${selected}>${respuesta[i].Nombre}</option>`;
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
 
  function cargarCMBSucursal(data, input) {
    var html = "";
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_sucursalCombo" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
        $.each(respuesta, function (i) {
         if(data === respuesta[i].PKData){
           selected = 'selected';
         }else{
           selected = '';
         }
          html +=
            `<option value="${respuesta[i].PKData}" ${selected}>${respuesta[i].Data}</option>`;
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
 
  function cargarCMBProveedor(data, input) {
    var html = "";
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_proveedorCombo" },
      dataType: "json",
      async:false,
      success: function (respuesta) {
        html += '<option selected disabled value="no">Seleccione un proveedor</option>';
        $.each(respuesta, function (i) {
         if(data === respuesta[i].PKData && data !== 0){
           selected = 'selected';
         }else{
           selected = '';
         }
          html +=
            `<option value="${respuesta[i].PKData}" ${selected}>${respuesta[i].Data}</option>`;
        });
  
        $("#" + input + "").html(html);
        $("#cmbProveedor").trigger("change");
      },
      error: function (error) {
        console.log(error);
      },
    });
  }

  function cargarCMBCondicionPago(data, input) {
    var html = "", selected = "";
  
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_condicionPago" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
  
        $.each(respuesta, function (i) {
          if(data === respuesta[i].PKCondicion){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKCondicion+'" '+selected+'>'+respuesta[i].Condicion+'</option>';
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
  
  function cargarCMBMoneda(data, input) {
    var html = "", selected = "";
  
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_Moneda" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
  
        $.each(respuesta, function (i) {
          if(data === respuesta[i].PKTipoMoneda){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKTipoMoneda+'" '+selected+'>'+respuesta[i].TipoMoneda+'</option>';
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
 
 function validEmptyInput(item, invalid = null) {
   const val = item.value;
   const parent = item.parentNode;
   let invalidDiv;
   if (invalid) {
     invalidDiv = document.getElementById(invalid);
   } else {
     for (let i = 0; i < parent.children.length; i++) {
       if (parent.children[i].classList.contains("invalid-feedback")) {
         invalidDiv = parent.children[i];
         break;
       }
     }
   }
   if (!val) {
     item.classList.add("is-invalid");
     invalid ? 'no hace nada' : invalidDiv.style.display = "block" ;
   } else {
     item.classList.remove("is-invalid");
     invalid ? 'no hace nada' : invalidDiv.style.display = "none" ;
   }
 }

 function Slimselects(){

   new SlimSelect({
       select: "#cmbProveedor",
       deselectLabel: '<span class="">✖</span>',
     });
 
     new SlimSelect({
       select: "#cmbComprador",
       deselectLabel: '<span class="">✖</span>',
     });
   
     new SlimSelect({
       select: "#cmbDireccionEnvio",
       deselectLabel: '<span class="">✖</span>',
     });

     new SlimSelect({
        select: "#cmbCondicionPago",
        deselectLabel: '<span class="">✖</span>',
      });

     new SlimSelect({
        select: "#cmbMoneda",
        deselectLabel: '<span class="">✖</span>',
      });
 }

 /* valida que la cantidad y precio del input del producto no sea 0 o vacio, 
 si es así, se actualiza la cantidad del producto en el arreglo de productos */
 function validaCantidad(sender){
   var valor = $(sender).val() ? parseFloat($(sender).val()) : 0;
   var input = $(sender).attr('name');
   var aux = $(sender).attr('id').split("-");
   var tipoInput = aux[0];
   var prod = aux[1];
   var auxDatosProd = Object.assign({}, arrDataProds[prod]);
  
   if(valor <= 0 || valor == "" || valor == null){
    valor = 1;
    if(tipoInput == "precio"){
      sender.value = "1.00";
    }else{
      sender.value = 1;
    }
   }
  
   //comprueba si es input cantidad
   if(input === "inpt_cantidad"){
        //comprueba que sea menor a la cantidad pendiente del producto
        if(valor > arrDataProds[prod]['restante']){
            auxDatosProd['cantidad'] = arrDataProds[prod]['restante'];
            sender.value = arrDataProds[prod]['restante'];
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: 'Se ha excedido la cantidad restante',
              sound: '../../../../../sounds/sound4'
            });
        }else{
            auxDatosProd['cantidad'] = valor;
        }
   }else if(input === "inpt_precio"){
        //valida que la cantidad no sea mayor a 12 enteros y 6 decimales
        aux = valor.toString().split(".");
        var ValorAux;
        flag = false;

        if(aux.length > 0){
          if(aux.length == 1 && aux[0].length > 12){
            flag = true;
            ValorAux = aux[0].substring(0,12) + ".00";
          }else if(aux.length >= 2 && (aux[0].length > 12 || aux[1].length > 6)){
            flag = true;
            ValorAux = aux[0].substring(0,12) + "." + aux[1].substring(0,6);
          }

          if(flag){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: 'El precio solo admite hasta 12 enteros y 6 decimales',
              sound: '../../../../../sounds/sound4'
            });
            valor = ValorAux;
            sender.value = ValorAux;
          }
        }

        auxDatosProd['precio'] = valor;
   }
   
   //actualiza la cantidad/precio en el arreglo de productos
   arrDataProds[prod] = Object.assign({}, auxDatosProd);

   //actualiza el lbl del importe
   var importe = auxDatosProd['cantidad'] * auxDatosProd['precio'];

   $("#importe-"+prod).val(importe);
  
   formatoCantidad();
   CalculaImpuestos();
 }

 function formatoCantidad(){
  NumDecimales = 2;

  //recorre todos los inputs participantes (precio e importe) y le asigna el numero de decimales del que tiene más 
  $("input[name='inpt_precio']").each(function() {
    var aux = parseFloat($(this).val().replace(",","")).toString().split('.');
    if(aux.length >= 2){
      if(aux[1].length > NumDecimales){
        if(aux[1].length > 6){
          aux = parseFloat(parseFloat($(this).val()).toFixed(6).replace(",","")).toString().split('.');
          if(aux.length >= 2){
            aux[1].length > NumDecimales ? NumDecimales = aux[1].length : NumDecimales = NumDecimales;
          }
        }else{
          NumDecimales = aux[1].length;
        }
      }
    }
  });

  //ya teniendo la cantidad máxima de decimales usados en los inputs, todos se ajustan a ésta
  $("input[name='inpt_precio']").each(function() {
    $(this).val(parseFloat($(this).val().replace(",","")).toFixed(NumDecimales).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
  });
 }

 function CambiaClaveNombre(sender){
  var valor = $(sender).val().trim();
  var input = $(sender).attr('name');
  var aux = $(sender).attr('id').split("-");
  var tipoInput = aux[0];
  var prod = aux[1];

  if(tipoInput != "importe"){
    var auxDatosProd = Object.assign({}, arrDataProds[prod]);
 
    //comprueba si es input cantidad
    if(input === "inpt_name"){
        auxDatosProd['nombreSugerido'] = valor;
    }else if(input === "inpt_clave"){
        auxDatosProd['claveSugerida'] = valor;
    }
 
    //actualiza la cantidad/precio en el arreglo de productos
    arrDataProds[prod] = Object.assign({}, auxDatosProd);
  }
 }

 function deleteProducto(sender){
    $(sender).closest('tr').remove();
    var key = $(sender).attr("id").split("-")[1];
    delete arrDataProds[key];
    //se borran los impuestos del producto
    for (const property in arrTaxesProds) {
      if(arrTaxesProds[property]['FKProducto'] == key){
        delete arrTaxesProds[property];
      }
    }
    CalculaImpuestos();
 }

 function dosDecimales(n) {
  n = Math.round((n + Number.EPSILON) * 100) / 100;
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
 }

 //calcula el total de la orden de compra con impuestos
 function CalculaImpuestos(){
  var html = "";
  var totalImpuesto=0;

  //calcula el subtotal
  var subTotal = 0;
  for (const property in arrDataProds) {
    subTotal += arrDataProds[property]['cantidad'] * arrDataProds[property]['precio'];
  }
  $("#LblSubTotal").text(dosDecimales(subTotal));

//calcula los impuestos
  if(!$.isEmptyObject(arrTaxesProds)){
    var ImpuestoProd=0;
    var finalTaxes = {};
    var arrPkIva = [1];

    //calcula total de producto
    for (const property in arrTaxesProds) {
      var auxDatosTaxes = Object.assign({}, arrTaxesProds[property]);

      //recupera la pk del producto del impuesto
      var pkProd = auxDatosTaxes['FKProducto'];
      var tasa;

      var sumIepsProd = 0;
      //si el impuesto es iva y el producto tiene ieps calcula el subtotal + ieps del producto para el cálculo del iva
      if(arrPkIva.includes(auxDatosTaxes['pkImpuesto'])){
        for (const property in arrIepsProds) {
          if(arrIepsProds[property]['FKProducto'] == auxDatosTaxes['FKProducto']){
            var imp = 0;
            if(arrIepsProds[property]['FKTipoImporte'] == 2){
              imp = (arrDataProds[pkProd]['cantidad'] * arrIepsProds[property]['tasa']);
            }else if(arrIepsProds[property]['FKTipoImporte'] == 1){
              imp = (arrDataProds[pkProd]['cantidad'] * arrDataProds[pkProd]['precio']) * (arrIepsProds[property]['tasa'] / 100);
            }

            //lo suma o lo resta, según el tipo de impuesto
            if(arrIepsProds[property]['FKTipoImpuesto'] == 2){
              sumIepsProd = sumIepsProd - imp;
            }else{
              sumIepsProd += imp;
            }
          }
        }
      }
      
      

      if(auxDatosTaxes['FKTipoImporte'] == 2){
        ImpuestoProd = (arrDataProds[pkProd]['cantidad'] * auxDatosTaxes['tasa']) + sumIepsProd;
      }else{
        ImpuestoProd = ((arrDataProds[pkProd]['cantidad'] * arrDataProds[pkProd]['precio']) + sumIepsProd) * (auxDatosTaxes['tasa'] / 100);
      }

      //lo suma o lo resta, según el tipo de impuesto
      if(auxDatosTaxes['FKTipoImpuesto'] == 2){
        totalImpuesto = totalImpuesto - ImpuestoProd;
      }else{
        totalImpuesto += ImpuestoProd;
      }

      if((auxDatosTaxes['tasa'] !== '' || auxDatosTaxes['tasa'] !== null) && auxDatosTaxes['FKTipoImporte'] != 2){
        tasa = auxDatosTaxes['tasa']+'%';
      }else{
        tasa = auxDatosTaxes['tasa'];
      }      

      // al arreglo de impuestos le añado el importe del impuesto
      auxDatosTaxes['TotalImpuestoProd'] = ImpuestoProd;
      auxDatosTaxes['tasa'] = tasa;

      //comprueba si existe el impuesto con la misma tasa, si es así, se le añade el impuesto del producto al ya existente, si no se registra.
      if(!finalTaxes.hasOwnProperty(auxDatosTaxes['pkImpuesto']+"-"+auxDatosTaxes['tasa'])){
        finalTaxes[auxDatosTaxes['pkImpuesto']+"-"+auxDatosTaxes['tasa']] = Object.assign({}, auxDatosTaxes);
      }else{
        var auxArr = Object.assign({}, finalTaxes[auxDatosTaxes['pkImpuesto']+"-"+auxDatosTaxes['tasa']]);
        auxArr['TotalImpuestoProd'] = auxArr['TotalImpuestoProd'] + auxDatosTaxes['TotalImpuestoProd'];
        finalTaxes[auxDatosTaxes['pkImpuesto']+"-"+auxDatosTaxes['tasa']] = Object.assign({}, auxArr);

      }
    }

    //se pintan los impuestos en la tabla
    for (const property in finalTaxes) {
    html +=
    "<tr>" +
    '<td style="text-align: right;">' +
    finalTaxes[property]['nombre'] +
    "</td>" +
    '<td style="text-align: right;">' +
    finalTaxes[property]['tasa'] +
    " </td>" +
    '<td style="text-align: right;">.....</td>' +
    '<td style="text-align: right;">$ ' +
    dosDecimales(finalTaxes[property]['TotalImpuestoProd'])
    //dosDecimales(respuesta[i].totalImpuesto) +
    "</td>" +
    "</tr>";

    }
    
    $("#impuestos").html(html);
  }

  //pinta el total de la orden
  $("#LblTotal").text(dosDecimales(subTotal + totalImpuesto));
 }

 $(document).ready(function () {
   idRequisicion = $("#inpt_idRequisicion").val();
   validaEstatusRequisicion();
   cargarCMBCondicionPago("", "cmbCondicionPago");
   cargarCMBMoneda("", "cmbMoneda");
   Slimselects();
   LoadData();

   $("#tblListadoRequisicionesCompra").DataTable({
       language: setFormatDatatables(),
       destroy: true,
       info: false,
       scrollX: true,
       bSort: false,
       pageLength: 15,
       responsive: true,
       lengthChange: false,
       ajax: {
        url: "php/functions.php",
        data: {
          clase: "get_data",
          funcion: "get_productosRequisicion",
          idRequisicion: idRequisicion
        },
       },
       columns: [
         { data: "Id" },
         { data: "Clave" },
         { data: "Producto" },
         { data: "Cantidad_r", width: "80px" },
         { data: "Cantidad_p", width: "80px" },
         { data: "Cantidad_f", width: "80px" },
         { data: "Cantidad", width: "150px"},
         { data: "Precio", width: "150px"},
         { data: "Unidad medida", width: "150px"},
         { data: "Importe"},
         { data: "acciones", width: "10px" },
       ],
       columnDefs: [{targets: 0, visible: false }],
     });

     $("#tblListadoRequisicionesCompra")
     .DataTable()
     .on("draw", function () {
        resetValidations();
        CalculaImpuestos();
    });

    //cada que cambie el proveedor se vuelve a consultar los datos de los productos para cambiar los inputs del precios, clave y nombre
    $("#cmbProveedor").on("change", function(){
      var proveedor = $("#cmbProveedor").val();

      var tabla = $("#tblListadoRequisicionesCompra").DataTable();

      tabla.ajax
      .url("php/functions.php?clase=get_data&funcion=get_productosRequisicion&idRequisicion=" + idRequisicion + "&idProveedor=" + proveedor)
      .load();

      $("#tblListadoRequisicionesCompra")
      .DataTable()
      .on("draw", function () {
          resetValidations();
          //recorre los inputs y ejecuta el onChange
          var hiddenRows = tabla.rows().nodes();
          $("input", hiddenRows).each(function () {
            if($(this).prop("disabled") == true){
              CambiaClaveNombre($(this));
            }else{
              $(this).trigger("change");
            }
          });
      });
      CalculaImpuestos();
    });

   //valida permisos
   if (_permissions.add !== 1) {
       $("#alert").modal("show");
   }

   //Redireccionamos al Dash cuando se oculta el modal.
   $("#alert").on("hidden.bs.modal", function (e) {
       window.location.href = "../../../dashboard.php";
   });

   $("#alertSeguimiento").on("hidden.bs.modal", function (e) {
    window.location.href = "./";
   });

   $("#alert_NotFound").on("hidden.bs.modal", function (e) {
    window.location.href = "./";
   });
 });