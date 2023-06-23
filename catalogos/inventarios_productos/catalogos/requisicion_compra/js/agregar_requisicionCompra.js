
/* arreglo que contiene los ids de los productos que se han añadido a la requisición
 el orden del arreglo es {id:id, cantidad, nombre, clave}
 */
var arrProdID = {};

  function cambioProveedor() {
    var valor = $("#cmbProveedor").val();
    if (valor!="no") {
      $("#checkAllProd").css("display", "block");
      $("#chkcmbTodoProducto").prop("checked",false);
      $("#invalid-proveedor").css("display", "none");
      $("#cmbProveedor").removeClass("is-invalid");
      $("#txtCantidad").val("");
      $("#txtCantidadHis").val(1);
      $("#txtCantidad").prop("min", 1);
      html = ``;
      $("#datosNew").html(html);
      loadCombo("", "cmbProducto", " un producto", valor, "producto");

      //cuando se marque el check de todos los productos
      $("#chkcmbTodoProducto").on("change", function () {
        $("#txtCantidad").val("");
        $("#txtCantidadHis").val(1);
        $("#txtCantidad").prop("min", 1);
        if (this.checked) {
          Swal.fire(
            "El proveedor seleccionado no provee los productos listados",
            "Para agregarlo a la lista de los productos que provee, favor completar los campós.",
            "info"
          );
          html = `<div class="row">
                  <div class="col-lg-6" id="">
                    <div class="form-group">
                      <label for="usr">Nombre del producto del proveedor:*</label>
                      <input type="text" class="form-control alphaNumeric-only" maxlength="255" name="txtNombreProducto" id="txtNombreProducto" required onkeyup="validEmptyInput(this)">
                      <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un nombre.</div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="usr">Clave del producto del proveedor:*</label>
                      <input type="text" class="form-control alphaNumeric-only" maxlength="255" name="txtClaveProducto" id="txtClaveProducto" required onkeyup="validEmptyInput(this)">
                      <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave.</div>
                    </div>
                  </div>
                </div>
                <br>`;
          $("#datosNew").html(html);
          resetValidations();
          loadCombo("", "cmbProducto", "un producto", valor, "todoProducto");
        } else {
          loadCombo("", "cmbProducto", "un producto", valor, "producto");
          html = ``;
          $("#datosNew").html(html);
        }
      });
        
      $("#cmbProducto").on("change", function () {
        var prod = $("#cmbProducto").val();
        valor = $("#cmbProveedor").val();

        if(valor != "no"){
            $.ajax({
                url: "../../php/funciones.php",
                data: {
                  clase: "get_data",
                  funcion: "get_precioProveedor",
                  value: valor,
                  value1: prod,
                },
                dataType: "json",
                success: function (respuesta) {
                  //Activar el comoboe ingresar los datos respectivos
                  if ($("#chkcmbTodoProducto").is(":checked")) {
        
                    /* recupera el texto de la opción selecccionada, lo separa y precarga el texto en el nombre y clave 
                    cuando es nuevo producto para el proveedor */
                    var prodText = $("#cmbProducto option:selected").html();
                    var aux = prodText.split("-");
                    var auxlenght = aux.length;
                    var prodName = aux[auxlenght-1].trim();
                    var prodClave = "";
        
                    for(i=0; i<auxlenght-1; i++){
                      prodClave += aux[i]+"-"; 
                    }
        
                    prodClave=prodClave.substring(0,prodClave.length-1);
        
                    $("#txtCantidad").val("");
                    $("#txtCantidadHis").val(1);
                    $("#txtCantidad").prop("min", 1);
                    $("#txtNombreProducto").val(prodName);
                    $("#txtClaveProducto").val(prodClave);
                  } else {
                    $("#txtCantidad").val(respuesta[0].CantidadMinima);
                    $("#invalid-cantidad").css("display", "none");
                    $("#txtCantidad").removeClass("is-invalid");
                    $("#txtCantidad").prop("min", respuesta[0].CantidadMinima);
                    $("#txtCantidadHis").val(respuesta[0].CantidadMinima);
                  }
                },
                error: function (error) {
                  console.log(error);
                },
            });
        }
      });
    }else{
        $("#checkAllProd").css("display", "none");
        cmbTodosLosProductos();
        $("#chkcmbTodoProducto").prop("checked",false);
        $("#txtCantidad").val("");
        $("#txtCantidadHis").val(1);
        $("#txtCantidad").prop("min", 1);
        html = ``;
        $("#datosNew").html(html);
    }
  }

  /* valida el formulario, la variable "isSaveAll" determina si se valida desde la agregación de un producto a la tabla 
  si se valida el guardar la requisición */
  function validaFormulario(isSaveAll=0){
    //comprueba si se valida desde guardar requisición o agregar producto
   if(isSaveAll===1){
    $("#cmbProducto").prop("required", false);
    $("#txtCantidad").prop("required", false);
    if ($("#chkcmbTodoProducto").is(":checked")) {
      $("#txtNombreProducto").prop("required", false);
      $("#txtClaveProducto").prop("required", false);
    }
   }else{
    $("#cmbProducto").prop("required", true);
    $("#txtCantidad").prop("required", true);
    if ($("#chkcmbTodoProducto").is(":checked")) {
      $("#txtNombreProducto").prop("required", true);
      $("#txtClaveProducto").prop("required", true);
    }
   }
   if ($("#frmRequisicionCompra")[0].checkValidity()) {
       $("#invalid-producto").css("display","none");
         var badEmision =
           $("#invalid-emision").css("display") === "block" ? false : true;
         var badFechaEst =
           $("#invalid-fechaEst").css("display") === "block" ? false : true;
         var badComprador =
           $("#invalid-comprador").css("display") === "block" ? false : true;
         var badSucursal =
           $("#invalid-sucursal").css("display") === "block" ? false : true;
         var badArea =
           $("#invalid-cmbArea").css("display") === "block" ? false : true;
         var badEmpleado =
           $("#invalid-empleado").css("display") === "block" ? false : true;
         var badProducto = true;
         var badCantidad = true;
         var badNombreProd = true;
         var badClaveProd = true;
       if(isSaveAll===0){
           badProducto =
           $("#invalid-producto").css("display") === "block" ? false : true;
           badCantidad =
           $("#invalid-cantidad").css("display") === "block" ? false : true;
           badNombreProd =
           $("#invalid-nombreProd").css("display") === "block" ? false : true;
           badClaveProd =
           $("#invalid-claveProd").css("display") === "block" ? false : true;
       }
         if (
           (badEmision &&
           badFechaEst &&
           badComprador &&
           badSucursal &&
           badProducto &&
           badCantidad &&
           badNombreProd &&
           badClaveProd &&
           badArea &&
           badEmpleado)
         ) {
           return true;
         }
       } else { 
         if (!$("#txtFechaEmision").val()) {
           $("#invalid-emision").css("display", "block");
           $("#txtFechaEmision").addClass("is-invalid");
         }
     
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
     
         if ((!$("#cmbProducto").val() || $("#cmbProducto").val() < 1) && isSaveAll === 0) {
           $("#invalid-producto").css("display", "block");
           $("#cmbProducto").addClass("is-invalid");
         }
     
         if ((!$("#txtCantidad").val() || $("#txtCantidad").val() < 1) && isSaveAll === 0) {
           $("#invalid-cantidad").css("display", "block");
           $("#invalid-cantidad").text("El producto debe tener una cantidad.");
           $("#txtCantidad").addClass("is-invalid");
         }
     
         if (!$("#chkcmbTodoProducto").is(":checked")) {
           if (
             parseInt($("#txtCantidad").val()) < parseInt($("#txtCantidadHis").val())
           ) {
             $("#invalid-cantidad").css("display", "block");
             $("#invalid-cantidad").text(
               "La cantidad no puede ser menor a la cantidad minima: " +
                 $("#txtCantidadHis").val()
             );
             $("#txtCantidad").addClass("is-invalid");
           }
         }
     
         if ($("#chkcmbTodoProducto").is(":checked")) {
           if (!$("#txtNombreProducto").val()  && isSaveAll === 0) {
             $("#invalid-nombreProd").css("display", "block");
             $("#txtNombreProducto").addClass("is-invalid");
           }
     
           if (!$("#txtClaveProducto").val() && isSaveAll === 0) {
             $("#invalid-claveProd").css("display", "block");
             $("#txtClaveProducto").addClass("is-invalid");
           }
     
         }
   
         if (!$("#cmbArea").val() || $("#cmbArea").val() < 1) {
           $("#invalid-cmbArea").css("display", "block");
           $("#cmbArea").addClass("is-invalid");
         }
   
         if (!$("#cmbEmpleado").val() || $("#cmbEmpleado").val() < 1) {
           $("#invalid-empleado").css("display", "block");
           $("#cmbEmpleado").addClass("is-invalid");
         }

         return false;
       }
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

  //agrega un producto al arreglo de productos
  function agregarProd() {
    if (validaFormulario()) {
        //Obtener valores de los campos
        var idproducto = $("#cmbProducto").val();
        var cantidad = parseInt($("#txtCantidad").val());
        var nombre = "no";
        var clave = "no";
        if ($("#chkcmbTodoProducto").is(":checked")) {
         nombre = $("#txtNombreProducto").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
         clave = $("#txtClaveProducto").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
        
        //eliminación de espacios y saltos de linea de referencia
        nombre=eliminaEspaciosSaltos(nombre);
        clave=eliminaEspaciosSaltos(clave);
        }
        var arrPropiedadesProd = {};
        arrPropiedadesProd["id"] = idproducto;
        arrPropiedadesProd["cantidad"] = cantidad;
        arrPropiedadesProd["nombre"] = nombre;
        arrPropiedadesProd["clave"] = clave;

        arrProdID[idproducto] = Object.assign({}, arrPropiedadesProd);;
        AgregaProductos();
      }
  }

  //carga las productos del arreglo de productos en la pantalla principal
  function AgregaProductos(){
    if (!$.isEmptyObject(arrProdID)) {
        var provee = $("#cmbProveedor").val();
        //lleno la tabla con ajax
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
                funcion: "get_ProductosTable",
                prods: arrProdID,
                provee: provee
              },
              success:
                  limpiaCampos()
            },
            columns: [
              { data: "Id" },
              { data: "Clave_Producto" },
              { data: "Cantidad" },
              { data: "Unidad medida" },
              { data: "Acciones", width : "5px"},
            ],
            columnDefs: [{ targets: 0, visible: false }],
          });

          $("#cmbProveedor option:not(:selected)").remove();

          //cuando se llene la tabla se reasignan las validaciones de los inputs
            $("#tblListadoRequisicionesCompra")
            .DataTable()
            .on("draw", function () {
                resetValidations();
            });
      }else{
          tbl = $("#tblListadoRequisicionesCompra").DataTable();
          tbl.clear().draw();
      }
      isEmptyArrProdId();
  }

  function eliminaProducto(id){
    var key = id;
    delete arrProdID[key];
    AgregaProductos();
  }

  //función para validar si se encuentra algún producto añadido, al momento de existir uno se desactiva el cmbproveedor
  function isEmptyArrProdId(){
      if(!$.isEmptyObject(arrProdID)){
        $("#divProvee").addClass("disabled");
      }else{
        $("#divProvee").removeClass("disabled");
        cargarCMBProveedor("", "cmbProveedor");
        $("#checkAllProd").css("display", "none");
        cmbTodosLosProductos();
        $("#chkcmbTodoProducto").prop("checked",false);
        $("#txtCantidad").val("");
        $("#txtCantidadHis").val(1);
        $("#txtCantidad").prop("min", 1);
        html = ``;
        $("#datosNew").html(html);
      }
  }

  function enviaMail(id){
    $.ajax({
        type: 'POST',
        async:false,
        url: 'functions/enviar_RequisicionPedido.php',
        data: {
          idRequisicion: id
        },
        dataType: "json",
        success: function(data) {
          if (data['result'] == "exito") {
            location.href = "../requisicion_compra/";
          }else{
            Lobibox.notify("warning", {
              size: 'mini',
              rounded: true,
              delay: 1500,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../../../img/timdesk/warning_circle.svg',
              msg: "¡Ocurrió un error al enviar por correo la notifcacion al vendedor!"
            });
            setTimeout(function(){window.history.back()},1500);          
          }
        }
      });
  }

  //guarda la requisición
  function saveRequisicion() {
    if(validaFormulario(1)){
        if (!$.isEmptyObject(arrProdID)) {
                // se deshabilita el botón de guardar
                $("#btnAgregar").prop("disabled", true);
                $("#agregarProducto").prop("disabled", true);


                //recupera los datos de la cabecera
                _FechaEstimada = $("#txtFechaEstimada").val();
                _SucursalEntrega = $("#cmbDireccionEnvio").val();
                _Area = $("#cmbArea").val();
                _Empleado = $("#cmbEmpleado").val();
                _Proveedor = $("#cmbProveedor").val() != "no" ? $("#cmbProveedor").val() : 0;
                _Comprador = $("#cmbComprador").val();
                _NotasComprador =  $("#NotasComprador").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
                _NotasInternas =  $("#NotasInternas").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
                _NotasComprador=eliminaEspaciosSaltos(_NotasComprador);
                _NotasInternas=eliminaEspaciosSaltos(_NotasInternas);
              
                //valida tamaño de los campos 
                if(_NotasComprador.length <= 255 && _NotasInternas.length <= 255){
                    $.ajax({
                        url: "php/functions.php",
                        data: { 
                            clase: "save_data", 
                            funcion: "save_requisicion",
                            data2: _permissions.add,
                            data3: arrProdID,
                            data4: _FechaEstimada,
                            data5: _SucursalEntrega,
                            data6: _Area,
                            data7: _Empleado,
                            data8: _Proveedor,
                            data9: _Comprador,
                            data12: _NotasComprador,
                            data13: _NotasInternas
                        },
                        dataType: "json",
                        success: function (respuesta) {
                            if(respuesta["estatus"] == "ok"){
                                enviaMail(respuesta["result"]);                                
                            }else{
                                Lobibox.notify("warning", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../../../img/timdesk/warning_circle.svg",
                                    msg: respuesta['result'],
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
  
          html +=
            `<option value="${respuesta[i].PKComprador}">${respuesta[i].Nombre}</option>`;
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
  
          html +=
            `<option value="${respuesta[i].PKData}">${respuesta[i].Data}</option>`;
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
      success: function (respuesta) {
        html += '<option selected value="no">Seleccione un proveedor</option>';
        $.each(respuesta, function (i) {
  
          html +=
            `<option value="${respuesta[i].PKData}">${respuesta[i].Data}</option>`;
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
  
  function cargarCMBEmpleado(data, input) {
    var html = "";
    $.ajax({
      url: "php/functions.php",
      data: { clase: "get_data", funcion: "get_cmb_Empleado" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
        $.each(respuesta, function (i) {
  
          html +=
            `<option value="${respuesta[i].PKEmpleado}">${respuesta[i].NombreCompleto}</option>`;
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
  
  function cargarCMBArea(data, input) {
    var html = "";
    $.ajax({
      url: "php/functions.php",
      data: { clase: "get_data", funcion: "get_cmb_Area" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
        $.each(respuesta, function (i) {
  
          html +=
            `<option value="${respuesta[i].id}">${respuesta[i].nombre}</option>`;
        });
  
        $("#" + input + "").html(html);
  
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
  
  function loadCombo(data, input, name, value, fun) {
    html = '<option data-placeholder="true"></option>';
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_" + fun + "Combo", value: value },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta !== "" && respuesta !== null && respuesta.length > 0) {
          $.each(respuesta, function (i) {
            html +=
              '<option value="' +
              respuesta[i].PKData +
              '">' +
              respuesta[i].Data +
              "</option>";
          });
        } else {
          html += '<option value="vacio">No hay datos que mostrar</option>';
        }
  
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
      invalidDiv.style.display = "block";
    } else {
      item.classList.remove("is-invalid");
      invalidDiv.style.display = "none";
    }
  }

  function Slimselects(){

    new SlimSelect({
        select: "#cmbProveedor",
        deselectLabel: '<span class="">✖</span>',
      });
  
      new SlimSelect({
          select: "#cmbArea",
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
    
      cmbProd = new SlimSelect({
        select: "#cmbProducto",
        deselectLabel: '<span class="">✖</span>',
      });

      new SlimSelect({
        select: "#cmbEmpleado",
        deselectLabel: '<span class="">✖</span>',
      });
  }

  function cmbTodosLosProductos(){
    var html = "";
    $.ajax({
      url: "php/functions.php",
      data: { clase: "get_data", funcion: "get_productos" },
      dataType: "json",
      success: function (respuesta) {
        html+= '<option data-placeholder="true"></option>';
        if (respuesta !== "" && respuesta !== null && respuesta.length > 0) {
            $.each(respuesta, function (i) {
              html +=
                '<option value="' +
                respuesta[i].PKData +
                '" >' +
                respuesta[i].Data +
                "</option>";
            });
          } else {
            html += '<option value="vacio">No hay datos que mostrar</option>';
          }
          $("#cmbProducto").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }

  /* valida que la cantidad del input del producto no sea 0 o vacio, 
  si es así, se actualiza la cantidad del producto en el arreglo de productos */
  function validaCantidad(sender, ifFromTable=1){
    var valor = parseInt($(sender).val());
    // comprueba si se está validando la cantidad del txt que está fuera del arreglo, si es así solo valida que sea número y retorna
    if(ifFromTable === 0){
        if($(sender).val() == 0 || $(sender).val() == "" || $(sender).val() < 0){
            sender.value = 1;
            valor = 1;
        }
        return;
    }
    //recupera el id del producto y la cantidad ingresada
    var id = $(sender).attr("id").split("-")[1];

//actualiza la cantidad en el arreglo de productos
    var auxDatosProd = Object.assign({}, arrProdID[id]);

    //cambiamos la cantidad
    auxDatosProd['cantidad'] = valor;

    //reasignamos los datos al producto
    arrProdID[id] = Object.assign({}, auxDatosProd);
  }

  function limpiaCampos(){
    cmbProd.set(0);
    $("#txtCantidad").val("");               
    if ($("#chkcmbTodoProducto").is(":checked")) {
        $("#txtNombreProducto").val("");
        $("#txtClaveProducto").val("");
    }  
  }

  $(document).ready(function () {
    resetValidations();
    Slimselects();
    cargarCMBProveedor("", "cmbProveedor");
    cargarCMBComprador("","cmbComprador");
    cargarCMBSucursal("", "cmbDireccionEnvio");
    cargarCMBEmpleado("","cmbEmpleado");
    cargarCMBArea("","cmbArea");
    cmbTodosLosProductos();
    $("#tblListadoRequisicionesCompra").DataTable({
        language: setFormatDatatables(),
        destroy: true,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        columns: [
          { data: "Id" },
          { data: "Clave_Producto" },
          { data: "Cantidad" },
          { data: "Unidad medida" },
          { data: "Acciones", width : "5px"},
        ],
        columnDefs: [{targets: 0, visible: false }],
      });

    //valida permisos
    if (_permissions.add !== 1) {
        $("#alert").modal("show");
    }

    //Redireccionamos al Dash cuando se oculta el modal.
    $("#alert").on("hidden.bs.modal", function (e) {
        window.location.href = "../../../dashboard.php";
    });
  });