function crearSelects(){
  new SlimSelect({
    select: '#cmbProveedor', 
    deselectLabel: '<span class="">✖</span>',
    addable:  ()=> {
      $("#nuevo_Proveedor").modal("show");
    }
  });
  new SlimSelect({
    select: '#cmbSucursal', 
    deselectLabel: '<span class="">✖</span>'
  });
  new SlimSelect({
      select: '#cmbCategoriaCuenta', 
      deselectLabel: '<span class="">✖</span>',
      addable:  ()=> {
        $("#nueva_categoria").modal("show");
      }
  });
  cmbTipoPersona = new SlimSelect({
    select: "#cmbTipoPersona",
    deselectLabel: '<span class="">✖</span>',
  });
}  

$(document).on('change','#cmbCategoriaCuenta',(e)=>{
    const category = e.target.value;
    //const subcategory = document.getElementById('cmbSubcategoriaCuenta');
    cargarCMBSubcategorias(category);
    //subcategory.removeAttribute('disabled');
    cmbSubcategoria.enable();
});

$(document).on('change','#cmbSubcategoriaCuenta',(e)=>{
    console.log(e.target.value);
})

function test(){
    var idProveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
    var fecha = $("#txtfecha").val();
    var cmbSucursal = $('select[name=cmbSucursal] option').filter(':selected').val();
    var txtNoDocumento = $("#txtNoDocumento").val();
    var txtSerie = $("#txtSerie").val();
    var txtSubtotal = $("#txtSubtotal").val();
    var txtIva = $("#txtIva").val();
    var txtIEPS = $("#txtIEPS").val();
    var txtImporte = $("#txtImporte").val();
    var txtDescuento = $("#txtDescuento").val();
    var radiodoc = $('input:radio[name=radioDoc]:checked').val()

    console.log(idProveedor," ", fecha," ", cmbSucursal," ", txtNoDocumento," ", txtSerie," ",txtSubtotal," ",txtIva ," ",txtIEPS ," ",txtImporte ," ",txtDescuento ," Radio: ",radiodoc)
    
}
  

  $(document).ready(function(){
    crearSelects();
    cargarCMBProveedor();
    cargarCMBSucursal();
    $("#btnguardarDetalle").click(function(){
      validarData();
    });
    cargarCMBCategorias();
    cmbSubcategoria = new SlimSelect({
      select: '#cmbSubcategoriaCuenta', 
      deselectLabel: '<span class="">✖</span>'
    });

    cmbSubcategoria.disable();
  });

  
function cargarCMBProveedor() {
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_proveedorCombo"},
      success: function (data) {
        
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<option value="' +
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
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    }); 
  
}
function cargarCMBSucursal() {
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_sucursalCombo"},
      success: function (data) {
        console.log("data de proveedor: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<option value="' +
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
        //Pone los proveedores en el select
        $("#cmbSucursal").append(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    }); 
  
}
function validarData(){
    redFlag1 = 0;
    redFlag3 = 0;
    redFlag4 = 0;
    redFlag5 = 0;
    inputID= "txtNoDocumento"; 
    invalidDivID = "invalid-noDocumento";
    textInvalidDiv = "Campo requerido";
    if (($('#txtNoDocumento').val()=="" || $('#txtNoDocumento').val()==undefined )) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text("Folio requerido");
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text("Folio requerido");
      redFlag1 = 1;
    }
    invalidDivID = "invalid-subtotal";
    if (($('#txtSubtotal').val()=="" || $('#txtSubtotal').val()==undefined )) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("Subtotal requerido");
    } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("Subtotal requerido");
        redFlag3 = 1;
    }
    invalidDivID = "invalid-importe";
    if (($('#txtImporte').val()=="" || $('#txtImporte').val()==undefined )) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("Importe requerido");
    } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text(textInvalidDiv);
        redFlag4 = 1;
    }
    if(($('#cmbCategoriaCuenta').val()=="" || $('#cmbCategoriaCuenta').val()==undefined )){
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("La categoria es requerida.");
    } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("La categoria es requerida.");
        redFlag5 = 1;
    }
    if(redFlag1==1&&redFlag3==1&&redFlag4==1&&redFlag5){
        var serie = $("#txtSerie").val();
        var folio = $("#txtNoDocumento").val();
        console.log(serie,folio);
        validarrepit(serie, folio);
    }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Faltan algunos campos requeridos!",
    });
  }
}
//Validar que la serie y folio no se repitan para el proveedor, si se repiten no permite el insert
function validarrepit(serie, folio){
    var idProveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
    $.ajax({
        type:'POST',
        url: "functions/controller.php",
        dataType: "json",
        data: { clase:"get_data",funcion:"validate_seriefolio", _serie:serie,_folio:folio,_idProveedor:idProveedor},
        success: function (data) {
          console.log("data de proveedor: ", data);
          $.each(data, function (i) {
              if (data[i].existe==1){
                inputID= "txtNoDocumento"; 
                invalidDivID = "invalid-noDocumento";
                $("#" + inputID).addClass("is-invalid");
                $("#" + invalidDivID).show();
                $("#" + invalidDivID).text("El folio y serie ya existe para el proveedor");
                inputID= "txtSerie"; 
                invalidDivID = "invalid-serie";
                $("#" + inputID).addClass("is-invalid");
                $("#" + invalidDivID).show();
                $("#" + invalidDivID).text("El folio y serie ya existe para el proveedor");
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Folio y serie repetidos!",
                  });
              }
              else{
                  console.log($("#txtfecha").val()+" 00:00:00");
                  saveDatas();
              }
               console.log(data[i].FOLIO);
               console.log(data[i].existe);
          });
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
      });
}
function saveDatas(){

    var idProveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
    var fecha = $("#txtfecha").val();
    var fechavenci = $("#txtfechavenci").val();
    var cmbSucursal = $('select[name=cmbSucursal] option').filter(':selected').val();
    var txtNoDocumento = $("#txtNoDocumento").val();
    var txtSerie = $("#txtSerie").val();
    if(!$("#txtSerie").val()){
      txtSerie = 'N/A';
    }
    var txtSubtotal = $("#txtSubtotal").val();
    var txtIva = $("#txtIva").val();
    if(txtIva==""||txtIva==null){
        txtIva=0;
    }
    var txtIEPS = $("#txtIEPS").val();
    if(txtIEPS==""||txtIEPS==null){
        txtIEPS=0;
    }
    
    var txtImporte = $("#txtImporte").val();
    var txtDescuento = $("#txtDescuento").val();
    var txtDescuento = $("#txtIEPS").val();
    if(txtDescuento==""||txtDescuento==null){
        txtDescuento=0.0;
    }
    console.log(txtDescuento);
    var radiodoc = $('input:radio[name=radioDoc]:checked').val()
    var cat = $("#cmbCategoriaCuenta").val();
    var subcat = $("#cmbSubcategoriaCuenta").val();
    if(!$("#cmbSubcategoriaCuenta").val()){
      subcat = 1;
    }
    var comentarios = $("#txtComentarios").val();

            //Ajax que manda los parametros para el procedimiento almacenado spc_tablaDetalle_cuentasCobrar
            $.ajax({
                url: "functions/controller.php",
                data: { 
                  clase: "save_datas",
                  funcion: "insert_all",
                  _proveedor: idProveedor,
                  _sucursal: cmbSucursal,
                  _txtNoDocumento: txtNoDocumento,
                  _txtSerie: txtSerie,
                  _txtSubtotal: txtSubtotal,
                  _txtIva:txtIva,
                  _txtIEPS:txtIEPS,
                  _txtImporte:txtImporte,
                  _txtDescuento:txtDescuento,
                  _fecha:fecha,
                  _fechavenci:fechavenci,
                  _radiodoc:radiodoc,
                  _cat: cat,
                  _subcat: subcat,
                  _comentarios: comentarios
                },
                dataType: "json",
                success: function (data,response) {
                  console.log("data de cabecera: "+ data);
                  // Si se recibio un error 
                  if (response[0]!="E") {
                    console.log("Respuesta 0 "+ data);
                    
                    Lobibox.notify("success", {
                      size: "mini",
                      rounded: true,
                      delay: 2000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/checkmark.svg",
                      msg: "¡Registrado con exito!",
                    });
                   setTimeout(function(){ window.location= '../cuentas_pagar';}, 1500);
                  } else {
                    console.log("Error");
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 1498,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/notificacion_error.svg",
                      msg: "¡Algo salio mal!",
                    });
                  }
                },
                error: function(jqXHR, exception,data,response) {
                  var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log("data de cabecera: "+ data);
                    console.log("response de cabecera: "+ response);
                    console.log("excepcion " + exception);
                    console.log(msg);
                },
              });
}

function cargarCMBCategorias()
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
        if (i == 0) {
          html +=
            '<option disabled value="f" selected>Seleccione una categoria</option>';
          html +=
            '<option value="' +
            data[i].PKCategoria +
            '">' +
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

      $("#cmbCategoriaCuenta").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}
function cargarCMBSubcategorias(subCat)
{
  var html = '<option disabled value="f" selected>Seleccione una categoria</option>';
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    success: function (data) {
         console.log("data de subcat: ", data);
      $.each(data, function (i) {
        // if (i == 0) {
        //   html +=
        //     '<option disabled value="f" selected>Seleccione una categoria</option>';
        //   html +=
        //     '<option value="' +
        //     data[i].PKSubcategoria +
        //     '">' +
        //     data[i].Nombre+
        //     "</option>";
        // } else {
          html +=
            '<option value="' +
            data[i].PKSubcategoria +
            '">' +
            data[i].Nombre+
            "</option>";
        //}
      });

      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

$(document).on('change','#txtSubtotal',(e)=>{
  var subtotal, iva, ieps, descuento;
  if($("#txtSubtotal").val()){
    subtotal = $("#txtSubtotal").val()
  }else{
    subtotal = 0;
  }
  if($("#txtIva").val()){
    iva = $("#txtIva").val()
  }else{
    iva = 0;
  }
  if($("#txtIEPS").val()){
    ieps = $("#txtIEPS").val()
  }else{
    ieps = 0;
  }
  if($("#txtDescuento").val()){
    descuento = $("#txtDescuento").val()
  }else{
    descuento = 0
  }
  $("#txtImporte").val((parseFloat(subtotal) + parseFloat(iva) + parseFloat(ieps) - parseFloat(descuento)).toFixed(2));
})

$(document).on('change','#txtIva',(e)=>{
  var subtotal, iva, ieps, descuento;
  if($("#txtSubtotal").val()){
    subtotal = $("#txtSubtotal").val()
  }else{
    subtotal = 0;
  }
  if($("#txtIva").val()){
    iva = $("#txtIva").val()
  }else{
    iva = 0;
  }
  if($("#txtIEPS").val()){
    ieps = $("#txtIEPS").val()
  }else{
    ieps = 0;
  }
  if($("#txtDescuento").val()){
    descuento = $("#txtDescuento").val()
  }else{
    descuento = 0
  }
  $("#txtImporte").val((parseFloat(subtotal) + parseFloat(iva) + parseFloat(ieps) - parseFloat(descuento)).toFixed(2));
})

$(document).on('change','#txtIEPS',(e)=>{
  var subtotal, iva, ieps, descuento;
  if($("#txtSubtotal").val()){
    subtotal = $("#txtSubtotal").val()
  }else{
    subtotal = 0;
  }
  if($("#txtIva").val()){
    iva = $("#txtIva").val()
  }else{
    iva = 0;
  }
  if($("#txtIEPS").val()){
    ieps = $("#txtIEPS").val()
  }else{
    ieps = 0;
  }
  if($("#txtDescuento").val()){
    descuento = $("#txtDescuento").val()
  }else{
    descuento = 0
  }
  $("#txtImporte").val((parseFloat(subtotal) + parseFloat(iva) + parseFloat(ieps) - parseFloat(descuento)).toFixed(2));
})

$(document).on('change','#txtDescuento',(e)=>{
  var subtotal, iva, ieps, descuento;
  if($("#txtSubtotal").val()){
    subtotal = $("#txtSubtotal").val()
  }else{
    subtotal = 0;
  }
  if($("#txtIva").val()){
    iva = $("#txtIva").val()
  }else{
    iva = 0;
  }
  if($("#txtIEPS").val()){
    ieps = $("#txtIEPS").val()
  }else{
    ieps = 0;
  }
  if($("#txtDescuento").val()){
    descuento = $("#txtDescuento").val()
  }else{
    descuento = 0
  }
  $("#txtImporte").val((parseFloat(subtotal) + parseFloat(iva) + parseFloat(ieps) - parseFloat(descuento)).toFixed(2));
})

function dosDecimales(item) {
  $(item).val(parseFloat(Number.parseFloat(item.value)
  .toFixed(2)));
}

$(document).on("click", "#btnAgregarProveedor", (e)=> {
  var nombre = $("#nombreProv").val();
  var email = $("#emailProv").val();
  var tipoPersona = $("#cmbTipoPersona").val();
  var isCreditoCheck = $("#creditoProv").is(':checked');
  var diascredito = $("#txtDiasCredito").val();
  var limitepractico = $("#txtLimiteCredito").val();

  if (!nombre) {
    $("#invalid-nombreProvModal").css("display", "block");
    $("#nombreProv").addClass("is-invalid");
  }
  if (!email) {
    $("#invalid-emailProv").css("display", "block");
    $("#emailProv").addClass("is-invalid");
  }
  if (!tipoPersona) {
    $("#invalid-tipoPersonaProv").css("display", "block");
  }
  if (isCreditoCheck) {
    if (!diascredito) {
      $("#invalid-diasProv").css("display", "block");
      $("#txtDiasCredito").addClass("is-invalid");
    }
    if (!limitepractico) {
      $("#invalid-credProv").css("display", "block");
      $("#txtLimiteCredito").addClass("is-invalid");
    }
  }

  var badNombreProv =
    $("#invalid-nombreProvModal").css("display") === "block" ? false : true;
  var badEmailProv =
    $("#invalid-emailProv").css("display") === "block" ? false : true;
  var badTipoPersonaProv =
    $("#invalid-tipoPersonaProv").css("display") === "block" ? false : true;
  var badDiasProv =
    $("#invalid-diasProv").css("display") === "block" ? false : true;
  var badCredProv =
    $("#invalid-credProv").css("display") === "block" ? false : true;

  if (badNombreProv && badEmailProv && badTipoPersonaProv && badDiasProv && badCredProv) {
    $.ajax({
      url: "functions/agregar_Proveedor.php",
      type: "POST",
      data: {
        "nombre": nombre,
        "email": email,
        "tipoPersona": tipoPersona,
        "isCreditoCheck": isCreditoCheck,
        "diascredito": diascredito,
        "limitepractico": limitepractico,
      },
      success: function(data, status, xhr) {
        console.log(data);
        if (data.trim() == "exito") {
          $('#nuevo_Proveedor').modal('hide');
          $("#nombreProv").val("");
          $("#emailProv").val("");
          cmbTipoPersona.set('');
          $("#creditoProv").val("");
          $("#txtDiasCredito").val("");
          $("#txtLimiteCredito").val("");
          $('#agregarProveedor').trigger("reset");
          cargarCMBProveedor("cmbProvedoresGasto");
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: '../../img/timdesk/checkmark.svg',
            msg: '¡Registro agregado!'
          });
        } else {
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top',
            icon: true,
            img: '../../../../img/timdesk/warning_circle.svg',
            img: null,
            msg: 'Ocurrió un error al agregar'
          });
        }
      }
    });
  }
});
// Validar que no se repita el nombre comercial del proveedor y que no este vacio el input
function escribirNombre() {
  var valor = document.getElementById("nombreProv").value;
  console.log("Valor nombre: " + valor);
  $.ajax({
    url: "functions/validar_proveedor.php",
    type: "POST",
    data: {
      "nombre": valor,
    },
    dataType: "json",
    success: function(data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreProvModal").css("display", "block");
        $("#invalid-nombreProvModal").text("El nombre ya esta en el registro.");
        $("#nombreProv").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProvModal").css("display", "block");
          $("#invalid-nombreProvModal").text("El proveedor debe tener un nombre.");
          $("#nombreProv").addClass("is-invalid");
        } else {
          $("#invalid-nombreProvModal").css("display", "none");
          $("#nombreProvModal").removeClass("is-invalid");
        }
      }
    },
    error: function(error) {
      console.log(error);
    }
  });
}

function validarCorreo(item) {
  const val = item.value;
  const invalidDiv = item.nextElementSibling;

  const reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  const regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(val) && regOficial.test(val)) {
    invalidDiv.style.display = "none";
    invalidDiv.innerText = "El usuario debe tener un correo.";
    item.classList.remove("is-invalid");
  } else if (reg.test(val)) {
    invalidDiv.style.display = "none";
    invalidDiv.innerText = "El usuario debe tener un correo.";
    item.classList.remove("is-invalid");
  } else {
    invalidDiv.style.display = "block";
    invalidDiv.innerText = "El correo debe ser valido.";
    item.classList.add("is-invalid");
  }
}

function validTipoPersona() {
  $("#invalid-tipoPersonaProv").css("display", "none");
}
  
function activarDesactivarCred(item) {
  var dias = document.getElementById("txtDiasCredito");
  var cred = document.getElementById("txtLimiteCredito");
  dias.classList.remove("is-invalid");
  cred.classList.remove("is-invalid");
  document.getElementById("invalid-diasProv").style.display = "none";
  document.getElementById("invalid-credProv").style.display = "none";
  if (item.checked) {
    dias.disabled = false;
    cred.disabled = false;
    return;
  }
  document.getElementById("txtDiasCredito").disabled = true;
  document.getElementById("txtLimiteCredito").disabled = true;
  return;
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
  
//Guardar Categoria
function guardarCategoria(tipoCmb) {
  var nombreCat = $("#txtCategoria").val();

  if (!nombreCat) {
    $("#invalid-categoria").css("display", "block");
    $("#txtCategoria").addClass("is-invalid");
  }

  var badNombreCat =
    $("#invalid-categoria").css("display") === "block" ? false : true;

  if (badNombreCat) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_Categoria.php",
      data: {
        nombreCat: nombreCat,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#nueva_categoria").modal("hide");
          cargarCMBCategorias();
          Lobibox.notify('success', {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: '¡Categoria agregada exitosamente!',
          });
          $("#txtCategoria").val('');
        } else {
          Lobibox.notify('error', {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: '¡Algo salio mal!',
          });
          $("#txtCategoria").val('');
        }
      },
    });
  }
}
//Limpiar mensajes de validaciones y valores de inputs al cerrar modal de agregar proveedor
$(document).on('hidden.bs.modal', '#nuevo_Proveedor', function (e) {
  $("#nombreProv").val("");
  $("#emailProv").val("");
  cmbTipoPersona.set('');
  $("#creditoProv").val("");
  $("#txtDiasCredito").val("");
  $("#txtLimiteCredito").val("");
  $("#invalid-nombreProvModal").css("display", "none");
  $("#nombreProv").removeClass("is-invalid");
  $("#invalid-emailProv").css("display", "none");
  $("#emailProv").removeClass("is-invalid");
  $("#invalid-tipoPersonaProv").css("display", "none");
  $("#invalid-diasProv").css("display", "none");
  $("#txtDiasCredito").removeClass("is-invalid");
  $("#invalid-credProv").css("display", "none");
  $("#txtLimiteCredito").removeClass("is-invalid");
});
//Limpiar mensaje de validacion y el valor del input al cerrar modal de agregar categoria
$(document).on('hidden.bs.modal', '#nueva_categoria', function (e) {
  $("#txtCategoria").val("");
  $("#invalid-categoria").css("display", "none");
  $("#txtCategoria").removeClass("is-invalid");
});