$(document).ready(function(){
  cmbRegimenFiscal = new SlimSelect({
    select: '#cmbRegimenFiscal',
    placeholder: 'Seleccione un regimen fiscal...',
  });

  cmbEstado = new SlimSelect({
    select: '#cmbEstado',
    placeholder: 'Seleccione un estado...',
  });
  loadCombo("get_regimenFiscal","#cmbRegimenFiscal","");
  loadCombo("get_estados","#cmbEstado","");
  
    $.ajax({
        method: "post",
        url: "php/funciones",
        data: {
        clase: 'get_data',
        funcion: 'get_dataEnterprise'
        },
        datatype: "json",
        success: function(respuesta){
            res = JSON.parse(respuesta);

            ruta = res[0].logo;

            var reader = new FileReader();
            var MAX_WIDTH = 200;
            var MAX_HEIGHT = 100;
            
            var img = document.createElement("img");
            
            img.onload = function (event){
                var width = 400;
                var height = 200;
                console.log(width + "x" + height);
     
                var canvas = document.createElement("canvas");
                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext("2d");
                
                ctx.mozImageSmoothingEnabled = false;
                ctx.webkitImageSmoothingEnabled = false;
                ctx.msImageSmoothingEnabled = false;
                ctx.imageSmoothingEnabled = false;

                ctx.drawImage(img,0,0,width,height);
                
                var dataurl = canvas.toDataURL(ruta);
                document.getElementById("logo_empresa").src = dataurl;
            }
            
            img.src = ruta;
            
            //ruta = "archivos/"+res[0].PKEmpresa+"/fiscales/"+res[0].logo
            //$("#logo_empresa").attr("src",res[0].logo);
            if(res[0].termino_vencimiento_sello_cfdi !== null && res[0].termino_vencimiento_sello_cfdi !== ""){
                sql_expired_date = new Date(res[0].termino_vencimiento_sello_cfdi);
                expired_date = moment(sql_expired_date).format("DD-MM-YYYY");
                $("#expired_date_certificate").html("Certificados expiran en: " + expired_date);
                $("#expired_date_certificate").css("display",'block');
            } else {
                expired_date = "";
                $("#expired_date_certificate").css("display",'none');
            }

            $("#txtNombreEmpresa").val(res[0].nombre_comercial);
            $("#txtRazonSocial").val(res[0].RazonSocial);
            $("#txtRfc").val(res[0].RFC);
            //loadCombo("get_regimenFiscal","#cmbRegimenFiscal",res[0].regimen_fiscal_id);
            cmbRegimenFiscal.setSelected(res[0].regimen_fiscal_id);
            $("#txtTelefono").val(res[0].telefono);
            $("#txtCalle").val(res[0].calle);
            $("#txtNumeroExterior").val(res[0].numero_exterior);
            $("#txtNumeroInterior").val(res[0].numero_interior);
            $("#txtCp").val(res[0].codigo_postal);
            $("#txtColonia").val(res[0].colonia);
            $("#txtCiudad").val(res[0].ciudad);
            //loadCombo("get_estados","#cmbEstado",res[0].estado_id);
            cmbEstado.setSelected(res[0].estado_id);
            $("#txtPais").val("MEX");
            $("#uploadFileUpdateCer").val(res[0].certificado_archivo);
            $("#uploadFileUpdateCer").prop('title',res[0].certificado_archivo);
            $("#uploadFileUpdateKey").val(res[0].llave_certificado_archivo);
            $("#uploadFileUpdateKey").prop('title',res[0].llave_certificado_archivo);
            //var date_expired = expired_date !== null && expired_date !== "" ? "Certificados expiran en: " + expired_date : "";
            validateInpustRequired();
            
        },
        error:function(error){
        console.log(error);
        }
    });
    
    
});

function loadCombo(func,input,value){
  $.ajax({
    method: "post",
    url: "php/funciones",
    data: {
      clase: 'get_data',
      funcion: func
    },
    datatype: "json",
    success: function(respuesta){
      res = JSON.parse(respuesta);
      html = "<option data-placeholder='true'></option>";

      res.forEach(element => {
        if(value === element.id){
          html += "<option value='"+element.id+"' selected>"+element.texto+"</option>";
        } else {
          html += "<option value='"+element.id+"'>"+element.texto+"</option>";
        }
      });

      $(input).html(html);

    },
    error:function(error){
      console.log(error);
    }
  });
}

$(document).on("click","#btn_save_data",function(){
  
  if ($("#data-enterprise")[0].checkValidity()) {
    var badNombreEmpresa =
      $("#invalid-nombreEmpresa").css("display") === "block" ? false : true;
    var badRazonSocial =
      $("#invalid-razonSocial").css("display") === "block" ? false : true;
    var badRegimenFiscal =
      $("#invalid-regimenFiscal").css("display") === "block" ? false : true;
    var badCodigoPostal =
      $("#invalid-codigoPostal").css("display") === "block" ? false : true;
    
      
    if(
        badNombreEmpresa &&
        badRazonSocial  &&
        badRegimenFiscal  &&
        badCodigoPostal
      )
    {
      
      nombre_empresa = $("#txtNombreEmpresa").val();
      razon_social = $("#txtRazonSocial").val();
      regimen_fiscal = $("#cmbRegimenFiscal").val();
      telefono = $("#txtTelefono").val();
      calle = $("#txtCalle").val();
      numero_exterior = $("#txtNumeroExterior").val();
      numero_interior = $("#txtNumeroInterior").val();
      codigo_postal = $("#txtCp").val();
      colonia = $("#txtColonia").val();
      ciudad = $("#txtCiudad").val();
      estado = $("#cmbEstado").val();

      $.ajax({
        method: "post",
        url: "php/funciones",
        data: {
          clase: 'get_data',
          funcion: 'get_validRazonSocial',
          value: razon_social
        },
        datatype: "json",
        success: function(respuesta){
          
          if(parseInt(respuesta) === 1){
            $("#loader1").css("display","block");
            $("#loader1").addClass("loader");
            data = '{'+
                      '"nombre_empresa" : "' + nombre_empresa + '",' +
                      '"razon_social" : "' + razon_social + '",' +
                      '"regimen_fiscal" : "' + regimen_fiscal + '",' +
                      '"telefono" : "' + telefono + '",' +
                      '"calle" : "' + calle + '",' +
                      '"numero_exterior" : "' + numero_exterior + '",' +
                      '"numero_interior" : "' + numero_interior + '",' +
                      '"codigo_postal" : "' + codigo_postal + '",' +
                      '"colonia" : "' + colonia + '",' +
                      '"ciudad" : "' + ciudad + '",' +
                      '"estado" : "' + estado +'"' +
                    '}';

            
            $.ajax({
              method: "post",
              url: "php/funciones",
              data: {
                clase: 'update_data',
                funcion: 'update_data_enterprise',
                data: data
              },
              datatype: "json",
              success: function(respuesta1){
                
                res = JSON.parse( respuesta1);
                $(".loader").fadeOut("slow");
                $("#loader1").removeClass("loader");
                if(res === true){

                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3100,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Los datos de la empresa se han guardado con éxito"
                  });
                } else {
                  
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3100,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "Hubo un error al momento de guardar la información"
                  });
                }
                
              },
              error:function(error){
                console.log(error);
              }
            });
            
          } else {
            
            $("#invalid-razonSocialFormat").css("display", "block");
            $("#txtRazonSocial").addClass("is-invalidFormat");
            
          }
        },
        error:function(error){
          console.log(error);
        }
      });
    }
  } else {

    if (!$("#txtNombreEmpresa").val()) {
      $("#invalid-nombreEmpresa").css("display", "block");
      $("#txtNombreEmpresa").addClass("is-invalid");
    }

    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razonSocial").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }

    if (!$("#cmbRegimenFiscal").val()) {
      $("#invalid-regimenFiscal").css("display", "block");
      $("#cmbRegimenFiscal").addClass("is-invalid");
    }

    if (!$("#txtCp").val()) {
      $("#invalid-codigoPostal").css("display", "block");
      $("#txtCp").addClass("is-invalid");
    }
  }
    
});

$(document).on("change","#fileLogo",function(e){
    $("#loader").css("display","block");
    $("#loader").addClass("loader");
    var form_data = new FormData();
    //var file_data = document.getElementById('fileLogo');
    console.log(e.target.files);
    if(e.target.files){
        let imageFile = e.target.files[0];   
        form_data.append('file', imageFile);
        
        $.ajax({
            method: "post",
            url: "php/upload_logo.php",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(respuesta){
            res = JSON.parse(respuesta);
            //console.log(respuesta);
            $(".loader").fadeOut("slow");
            $("#loader").removeClass("loader");
            if(res.response === true){
                var ruta = res.logo

                var reader = new FileReader();
                var MAX_WIDTH = 300;
                var MAX_HEIGHT = 300;
                reader.onload = function(e)
                {
                    var img = document.createElement("img");
                    
                    img.onload = function (event){
                        var width = img.width;
                        var height = img.height;
                        console.log(width + "x" + height);
                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height = height * (MAX_WIDTH / width);
                                width = MAX_WIDTH;
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width = width * (MAX_HEIGHT / height);
                                height = MAX_HEIGHT;
                            }
                        }
                        var canvas = document.createElement("canvas");
                        canvas.width = width;
                        canvas.height = height;
                        var ctx = canvas.getContext("2d");
                        ctx.mozImageSmoothingEnabled = false;
                        ctx.webkitImageSmoothingEnabled = false;
                        ctx.msImageSmoothingEnabled = false;
                        ctx.imageSmoothingEnabled = false;
                        ctx.drawImage(img,0,0,width,height);
                        
                        var dataurl = canvas.toDataURL(ruta);
                        document.getElementById("logo_empresa").src = dataurl;
                    }

                    img.src = ruta;
                }

                reader.readAsDataURL(imageFile);
                // ruta = "archivos/"+res.empresa_id+"/fiscales/"+res.logo
               
                
                
                Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3100,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "El logotipo de la empresa se han guardado con éxito"
                });

            } else {
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3100,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "Hubo un error al momento de guardar la información"
                });
            }
            },
            error:function(error){
            console.log(error);
            }
        });
    }
});

$(document).on("change","#fileCer",()=>{
  var file_data = document.getElementById('fileCer');
  $("#uploadFileUpdateCer").val(file_data.files[0].name);
  $("#uploadFileUpdateCer").prop('title',file_data.files[0].name);
});

$(document).on("change","#fileKey",()=>{
  var file_data = document.getElementById('fileKey');
  $("#uploadFileUpdateKey").val(file_data.files[0].name);
  $("#uploadFileUpdateKey").prop('title',file_data.files[0].name);
});

$(document).on("click","#btn_save_certificate",function(){
  var form_data = new FormData();
  var file_data_cert = document.getElementById('fileCer');
  var file_data_key = document.getElementById('fileKey');
  var pass_cert = document.getElementById('txtPasswordCert');
  
  form_data.append('file_cer', file_data_cert.files[0]);
  form_data.append('file_key', file_data_key.files[0]);
  form_data.append('pass_cert', pass_cert.value);

  if ($("#data-certificate")[0].checkValidity()) {
    var badFileCert =
      $("#invalid-fileCer").css("display") === "block" ? false : true;
    var badFileKey =
      $("#invalid-fileKey").css("display") === "block" ? false : true;
    var badPasswordCert =
      $("#invalid-passwordCert").css("display") === "block" ? false : true;

    if(badFileCert &&
      badFileKey &&
      badPasswordCert)
    {
      $("#loader").css("display","block");
      $("#loader").addClass("loader");
      $.ajax({
        method: "post",
        url: "php/upload_certificates.php",
        data: form_data,
        processData: false,
        contentType: false,
        success: function(respuesta){
        $(".loader").fadeOut("slow");
        $("#loader").removeClass("loader");
        res = JSON.parse(respuesta);
        
        if(res.response === true){
          $("#txtRfc").val(res.rfc);
          $("#expired_date_certificate").css("display",'block');
          $("#expired_date_certificate").html("Certificados expiran en: " + res.fecha_vencimiento);
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "Los certificados de la empresa se han guardado con éxito"
          });
        } else {
          if(res.message !== ""){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3100,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: res.message
            });
          }
        }
      },
        error:function(error){
          console.log(error);
        }
      });
    }
  } else {
    if (!$("#fileCer").val()) {
      $("#invalid-fileCer").css("display", "block");
      $("#fileCer").addClass("is-invalid");
    }
    if (!$("#fileKey").val()) {
      $("#invalid-fileKey").css("display", "block");
      $("#fileKey").addClass("is-invalid");
    }
    if (!$("#txtPasswordCert").val()) {
      $("#invalid-passwordCert").css("display", "block");
      $("#txtPasswordCert").addClass("is-invalid");
    }
  }

})

$(document).on("change","#txtNombreEmpresa",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-nombreEmpresa").css("display", "none");
    $("#txtNombreEmpresa").removeClass("is-invalid");
  }
  
});

$(document).on("focusout","#txtNombreEmpresa", validateInpustRequired);
$(document).on("focusout","#txtRazonSocial", validateInpustRequired);
$(document).on("focusout","#txtCp", validateInpustRequired);
$(document).on("change","#cmbRegimenFiscal", validateInpustRequired);

$(document).on("change","#txtRazonSocial",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-razonSocial").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalid");
  }

  if($(this).hasClass("is-invalidFormat")){
    $("#invalid-razonSocialFormat").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalidFormat");
  }
});

$(document).on("change","#txtCp",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-codigoPostal").css("display", "none");
    $("#txtCp").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbRegimenFiscal",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-regimenFiscal").css("display", "none");
    $("#cmbRegimenFiscal").removeClass("is-invalid");
  }
  console.log(cmbRegimenFiscal.selected());
});

$(document).on("change","#txtCp",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-codigoPostal").css("display", "none");
    $("#txtCp").removeClass("is-invalid");
  }
});

$(document).on("change","#fileCer",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-fileCert").css("display", "none");
    $("#fileCert").removeClass("is-invalid");
  }
});

$(document).on("change","#fileKey",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-fileKey").css("display", "none");
    $("#fileKey").removeClass("is-invalid");
  }
});

$(document).on("change","#txtPasswordCert",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-passwordCert").css("display", "none");
    $("#txtPasswordCert").removeClass("is-invalid");
  }
});

$(document).on("change","#txtCalle",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-calle").css("display", "none");
    $("#txtCalle").removeClass("is-invalid");
  }
});

$(document).on("change","#txtNumeroExterior",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-noExterior").css("display", "none");
    $("#txtNumeroExterior").removeClass("is-invalid");
  }
});

function validateInpustRequired()
{
    console.log("nombre comercial",$("#txtNombreEmpresa").val());
    console.log("razon social",$("#txtRazonSocial").val());
    console.log("regimen fiscal",$("#cmbRegimenFiscal").val());
    console.log("cp",$("#txtCp").val());
    //no faltan datos
    if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== "" &&
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("");
        $("#fiscal_data_required").css("display",'none');
    // falta el código postal
    } else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== "" &&
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Código postal");
        $("#fiscal_data_required").css("display",'block');
    // falta el régimen fiscal
    } else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Régimen fiscal");
        $("#fiscal_data_required").css("display",'block');
    // falta razón social
    } else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Razón social");
        $("#fiscal_data_required").css("display",'block');
    // falta nombre comercial
    }else if(
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== "" &&
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial");
        $("#fiscal_data_required").css("display",'block');

    }else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Régimen fiscal y Cógigo postal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== "" &&
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== "" 
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial y Código postal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial y Razón social");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Regimen social y Razón Social");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== "" &&
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Razón Social y Código postal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val() !== "" &&
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial y Régimen fiscal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtNombreEmpresa").val() !== null && $("#txtNombreEmpresa").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Razón social, Régimen fiscal y Código postal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtRazonSocial").val() !== null && $("#txtRazonSocial").val()
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial, Régimen fiscal y Código postal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#cmbRegimenFiscal").val() !== null && $("#cmbRegimenFiscal").val()
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial, Razón social y Código postal");
        $("#fiscal_data_required").css("display",'block');
    }else if(
        $("#txtCp").val() !== null && $("#txtCp").val() !== ""
    ){
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial, Razón social y Régimen fiscal");
        $("#fiscal_data_required").css("display",'block');
    } else{
        $("#fiscal_data_required").html("Para poder facturar debe de llenar los siguientes campos: Nombre comercial, Razón social, Régimen fiscal y Código postal");
        $("#fiscal_data_required").css("display",'block');
    }
    
}