$(document).ready(function(){
  
  const cmbRegimenFiscal = new SlimSelect({
    select: "#cmbRegimenFiscal",
    placeholder: "Seleccione un régimen fiscal..."
  });

  const cmbStateCompany = new SlimSelect({
    select: "#cmbStateCompany",
    placeholder: "Seleccione un estado..."
  });

  const cmbUpdateRegimenFiscal = new SlimSelect({
    select: "#cmbUpdateRegimenFiscal",
    placeholder: "Seleccione un régimen fiscal..."
  });

  const cmbUpdateStateCompany = new SlimSelect({
    select: "#cmbUpdateStateCompany",
    placeholder: "Seleccione un estado..."
  });

  loadCombo("regimenFiscal",cmbRegimenFiscal,"","Seleccione un régimen fiscal...");
  loadCombo("stateCompany",cmbStateCompany,"","Seleccione un estado...");

  $(document).on("click","#editCompanyData",function(){
    var id = $(this).data("id");
    $("#txtUpdatePKDatosEmpresa_52").val(id);
    

    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      data: {
        clase: "get_data",
        funcion: "get_companyData",
        data: id
      },
      dataType: "json",
      success: function (respuesta) {
        
        $("#editar_DatosEmpresa_52").modal("toggle");
        $("#txtUpdateDatosEmpresa_52").val(respuesta[0].RazonSocial);

        $("#txtUpdateGiroComercial").val(respuesta[0].giro_comercial);
        $("#txtUpdateDomicilioFiscal").val(respuesta[0].domicilio_fiscal);
        $("#txtUpdateRegimenFiscal").val(respuesta[0].regimen_fiscal);
        $("#txtUpdateIMSS").val(respuesta[0].registro_patronal);
        $("#txtUpdatePasswordCert").val(respuesta[0].password_certificado);

        $("#txtUpdateStreetCompany").val(respuesta[0].calle);
        $("#txtUpdateExteriorCompany").val(respuesta[0].numero_exterior);
        $("#txtUpdateInteriorCompany").val(respuesta[0].numero_interior);
        $("#txtUpdateNeighborhoodCompany").val(respuesta[0].colonia);
        $("#txtUpdateCityCompany").val(respuesta[0].ciudad);
        
        $("#txtUpdateZipCompany").val(respuesta[0].codigo_postal);
        $("#txtUpdatePhoneCompany").val(respuesta[0].telefono);
        $("#uploadFileUpdateCer").val(respuesta[0].certificado_archivo);
        $("#uploadFileUpdateCer").prop('title',respuesta[0].certificado_archivo);
        $("#uploadFileUpdateKey").val(respuesta[0].llave_certificado_archivo);
        $("#uploadFileUpdateKey").prop('title',respuesta[0].llave_certificado_archivo);
        $("#uploadFileUpdateLogo").val(respuesta[0].logo);
        $("#uploadFileUpdateLogo").prop('title',respuesta[0].logo);

        $("#txtUpdateSerie").val(respuesta[0].serie_inicial);
        $("#txtUpdateFolio").val(respuesta[0].folio_inicial);
        
        loadCombo("regimenFiscal",cmbUpdateRegimenFiscal,respuesta[0].regimen_fiscal_id,"Seleccione un régimen fiscal...");
        loadCombo("stateCompany",cmbUpdateStateCompany,respuesta[0].estado_id,"Seleccione un estado...");
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
});


$(document).on("change","#fileCer",function(){
  $("#uploadFileCer").val(this.files[0].name);
  
});

$(document).on("change","#fileKey",function(){
  $("#uploadFileKey").val(this.files[0].name);
});

$(document).on("change","#fileLogo",function(){
  $("#uploadFileLogo").val(this.files[0].name);
  var inputCertFile = document.getElementById('fileLogo');
  var cert = inputCertFile.files[0];
  var filesize = cert.size/1048576;
  
  let img = new Image();
  img.src = window.URL.createObjectURL(cert);

  img.onload = () => {
    if(img.width < 200 && img.height < 200){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El logo debe medir al menos 200px por 200px",
      });
    }
    if(filesize > 1){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El logo debe de pesar menos de 1 MB",
      });
    }
  }
});

$(document).on("change","#fileUpdateCer",function(){
  $("#uploadUpdateFileCer").val(this.files[0].name);
  
});

$(document).on("change","#fileUpdateKey",function(){
  $("#uploadUpdateFileKey").val(this.files[0].name);
});

$(document).on("change","#fileUpdateLogo",function(){
  $("#uploadUpdateFileLogo").val(this.files[0].name);
  var inputCertFile = document.getElementById('fileUpdateLogo');
  var cert = inputCertFile.files[0];
  var filesize = cert.size/1048576;
  
  let img = new Image();
  img.src = window.URL.createObjectURL(cert);

  img.onload = () => {
    if(img.width < 200 && img.height < 200){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El logo debe medir al menos 200px por 200px",
      });
    }
    if(filesize > 1){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El logo debe de pesar menos de 1 MB",
      });
    }
  }
});

$(document).on("click","#btnAgregar_DatosEmpresa_52",function(){
  
  var inputCertFile = document.getElementById("fileCer");
  var cert = inputCertFile.files[0];
  var inputKeyFile = document.getElementById("fileKey");
  var key = inputKeyFile.files[0];
  var inputLogoFile = document.getElementById("fileLogo");
  var logo = inputLogoFile.files[0];
  var filesize =logo.size/1048576;

  let img = new Image();
  let width, height;
  img.src = window.URL.createObjectURL(logo);

  var giro_comercial = $("#txtGiroComercial").val();
  var domicilio = $("#txtDomicilioFiscal").val();
  var imss = $("#txtIMSS").val();
  var regimen_fiscal = $("#cmbRegimenFiscal").val();
  var pass = $("#txtPasswordCert").val();
  var street = $("#txtStreetCompany").val();
  var exterior = $("#txtExteriorCompany").val();
  var interior = $("#txtInteriorCompany").val();
  var neighborhood = $("#txtNeighborhoodCompany").val();
  var zip = $("#txtZipCompany").val();
  var city = $("#txtCityCompany").val();
  var state = $("#cmbStateCompany").val();
  var phone = $("#txtPhoneCompany").val();
  var serie = $("#txtSerie").val();
  var folio = $("#txtFolio").val();

  img.onload = () => {
    width = img.width;
    height = img.height;
    if(giro_comercial !== null && giro_comercial !== ""){
      if(regimen_fiscal !== null && regimen_fiscal !== ""){
        if(street !== null && street !== ""){
          if(exterior !== null && exterior !== ""){
            if(neighborhood !== null && neighborhood !== ""){
              if(zip !== null && zip !== ""){
                if(city !== null && city !== ""){
                  if(state !== null && state !== ""){
                    if(inputCertFile.value !== null && inputCertFile.value !== ""){
                      if(inputKeyFile.value !== null && inputKeyFile !== ""){
                        if(pass !== null && pass !== ""){
                          if(inputLogoFile.value !== null && inputLogoFile.value !== ""){
                            if(width <= 200 && height <= 200){
                              if(filesize > 1){
                                if(serie !== null && serie !== ""){
                                  if(folio !== null && folio !== ""){
                                    var data = new FormData();
                                    data.append('giro_comercial',giro_comercial);
                                    data.append('domicilio',domicilio);
                                    data.append('imss',imss);
                                    data.append('regimen_fiscal',regimen_fiscal);
                                    data.append('cert',cert);
                                    data.append('key',key);
                                    data.append('logo',logo);
                                    data.append('password_cert',pass);
                                    data.append('street',street);
                                    data.append('exterior',exterior);
                                    data.append('interior',interior);
                                    data.append('neighborhood',neighborhood);
                                    data.append('zip',zip);
                                    data.append('city',city);
                                    data.append('state',state);
                                    data.append('phone',phone);
                                    data.append('serie',serie);
                                    data.append('folio',folio);
                                    
                                    $.ajax({
                                      url: "php/save_companyData.php",
                                      type: "POST",
                                      data: data,
                                      contentType: false,
                                      cache: false,
                                      processData: false,
                                      success: function (respuesta) {
                                        if(respuesta === "1"){
                                          $("#agregar_DatosEmpresa_52").modal("toggle");
                                          $("#tblDatosEmpresa").DataTable().ajax.reload();
                                          Lobibox.notify("success", {
                                            size: "mini",
                                            rounded: true,
                                            delay: 3000,
                                            delayIndicator: false,
                                            position: "center top", //or 'center bottom'
                                            icon: true,
                                            img: "../../img/timdesk/checkmark.svg",
                                            msg: "¡Registro guardado con éxito!",
                                          });
                                        } else {
                                          Lobibox.notify("warning", {
                                            size: "mini",
                                            rounded: true,
                                            delay: 3000,
                                            delayIndicator: false,
                                            position: "center top",
                                            icon: true,
                                            img: "../../img/timdesk/warning_circle.svg",
                                            msg: "Ocurrió un error al editar",
                                          });
                                        }
                                      },
                                      error: function (error) {
                                        console.log(error);
                                      },
                                    });
                                  } else {
                                    Lobibox.notify("error", {
                                      size: "mini",
                                      rounded: true,
                                      delay: 3000,
                                      delayIndicator: false,
                                      position: "center top",
                                      icon: true,
                                      img: "../../img/timdesk/warning_circle.svg",
                                      msg: "El folio inicial es un dato obligatorio",
                                    });
                                  }
                                } else {
                                  Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: "La serie inicial es un dato obligatorio",
                                  });
                                }
                              } else{
                                Lobibox.notify("error", {
                                  size: "mini",
                                  rounded: true,
                                  delay: 3000,
                                  delayIndicator: false,
                                  position: "center top",
                                  icon: true,
                                  img: "../../img/timdesk/warning_circle.svg",
                                  msg: "El logo debe de pesar menos de 1 MB",
                                });
                              }
                            } else {
                              Lobibox.notify("error", {
                                size: "mini",
                                rounded: true,
                                delay: 3000,
                                delayIndicator: false,
                                position: "center top",
                                icon: true,
                                img: "../../img/timdesk/warning_circle.svg",
                                msg: "El logo debe medir al menos 200px por 200px",
                              });
                            }
                          } else {
                            Lobibox.notify("error", {
                              size: "mini",
                              rounded: true,
                              delay: 3000,
                              delayIndicator: false,
                              position: "center top",
                              icon: true,
                              img: "../../img/timdesk/warning_circle.svg",
                              msg: "El logo es un dato obligatorio",
                            });
                          }
                        } else {
                          Lobibox.notify("error", {
                            size: "mini",
                            rounded: true,
                            delay: 3000,
                            delayIndicator: false,
                            position: "center top",
                            icon: true,
                            img: "../../img/timdesk/warning_circle.svg",
                            msg: "La contraseña del certificado es un dato obligatorio",
                          });
                        }
                      } else {
                        Lobibox.notify("error", {
                          size: "mini",
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: "center top",
                          icon: true,
                          img: "../../img/timdesk/warning_circle.svg",
                          msg: "El archivo .key es un dato obligatorio",
                        });
                      }
                    } else {
                      Lobibox.notify("error", {
                        size: "mini",
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/warning_circle.svg",
                        msg: "El archivo .cert es un dato obligatorio",
                      });
                    }
                  } else {
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: "El estado es un dato obligatorio",
                    });
                  }
                } else {
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "La ciudad es un dato obligatorio",
                  });
                }
              } else {
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "El código postal es un dato obligatorio",
                });
              }
            } else {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "La colonia es un dato obligatorio",
              });
            }
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "El número exterior es un dato obligatorio",
            });
          }
        } else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "La calle es un dato obligatorio",
          });
        }
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "El regimen_fiscal es un dato obligatorio",
        });
      }
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El giro comercial es un dato obligatorio",
      });
    }
  }
     
});



function validateCer(file){
  var request;
  var ext = file.split(".");
  ext = ext[ext.length-1].toLowerCase();

  if(file !== ""){
    if(ext === "cer"){
      request = true;
    } else {
      request = false;
    }
  } else {
    request = true;
  }

  return request;
}

function validateKey(file){
  var request;
  var ext = file.split(".");
  ext = ext[ext.length-1].toLowerCase();

  if(ext === "key"){
    request = true;
  } else {
    request = false;
  }

  return request;
}

function validateLogo(file){
  var request;
  var ext = file.split(".");
  ext = ext[ext.length-1].toLowerCase();
  var arrayExtensions = ["jpg" , "jpeg", "png", "bmp", "gif"];

  if(arrayExtensions.lastIndexOf(ext) > -1){
    request = true;
  } else {
    request = false;
  }

  return request;
}

$(document).on("click","#logo-company",function(){
  $("#modal-logo").css("display","block");
  $("#zoom-logo").attr("src",$(this).attr("src"));
  $("#caption-logo_empresa").html($(this).attr("alt"));
});

$(document).on("click",".close-logo_empresa",function(){
  $("#modal-logo").css("display","none");
});



$(document).on("click","#btnEditar_DatosEmpresa_52",function(){
  var inputCertFile = document.getElementById("fileUpdateCer");
  var cert = inputCertFile.files[0];
  var inputKeyFile = document.getElementById("fileUpdateKey");
  var key = inputKeyFile.files[0];
  var inputLogoFile = document.getElementById("fileUpdateLogo");
  var logo = inputLogoFile.files[0];
  var filesize =logo.size/1048576;

  let img = new Image();
  let width, height;
  img.src = window.URL.createObjectURL(logo);

  var giro_comercial = $("#txtUpdateGiroComercial").val();
  var domicilio = $("#txtUpdateDomicilioFiscal").val();
  var imss = $("#txtUpdateIMSS").val();
  var regimen_fiscal = $("#cmbUpdateRegimenFiscal").val();
  var pass = $("#txtUpdatePasswordCert").val();
  var street = $("#txtUpdateStreetCompany").val();
  var exterior = $("#txtUpdateExteriorCompany").val();
  var interior = $("#txtUpdateInteriorCompany").val();
  var neighborhood = $("#txtUpdateNeighborhoodCompany").val();
  var zip = $("#txtUpdateZipCompany").val();
  var city = $("#txtUpdateCityCompany").val();
  var state = $("#cmbUpdateStateCompany").val();
  var phone = $("#txtUpdatePhoneCompany").val();
  var serie = $("#txtUpdateSerie").val();
  var folio = $("#txtUpdateFolio").val();

  img.onload = () => {
    width = img.width;
    height = img.height;
    if(giro_comercial !== null && giro_comercial !== ""){
      if(regimen_fiscal !== null && regimen_fiscal !== ""){
        if(street !== null && street !== ""){
          if(exterior !== null && exterior !== ""){
            if(neighborhood !== null && neighborhood !== ""){
              if(zip !== null && zip !== ""){
                if(city !== null && city !== ""){
                  if(state !== null && state !== ""){
                    if(inputCertFile.value !== null && inputCertFile.value !== ""){
                      if(inputKeyFile.value !== null && inputKeyFile !== ""){
                        if(pass !== null && pass !== ""){
                          if(inputLogoFile.value !== null && inputLogoFile.value !== ""){
                            if(width <= 200 && height <= 200){
                              if(filesize > 1){
                                if(serie !== null && serie !== ""){
                                  if(folio !== null && serie !== ""){
                                    var data = new FormData();
                                    data.append('giro_comercial',giro_comercial);
                                    data.append('domicilio',domicilio);
                                    data.append('imss',imss);
                                    data.append('regimen_fiscal',regimen_fiscal);
                                    data.append('cert',cert);
                                    data.append('key',key);
                                    data.append('logo',logo);
                                    data.append('password_cert',pass);
                                    data.append('street',street);
                                    data.append('exterior',exterior);
                                    data.append('interior',interior);
                                    data.append('neighborhood',neighborhood);
                                    data.append('zip',zip);
                                    data.append('city',city);
                                    data.append('state',state);
                                    data.append('phone',phone);
                                    data.append('serie',serie);
                                    data.append('folio',folio);
                                    
                                    $.ajax({
                                      url: "php/save_companyData.php",
                                      type: "POST",
                                      data: data,
                                      contentType: false,
                                      cache: false,
                                      processData: false,
                                      success: function (respuesta) {
                                        if(respuesta === "1"){
                                          $("#editar_DatosEmpresa_52").modal("toggle");
                                          $("#tblDatosEmpresa").DataTable().ajax.reload();
                                          Lobibox.notify("success", {
                                            size: "mini",
                                            rounded: true,
                                            delay: 3000,
                                            delayIndicator: false,
                                            position: "center top", //or 'center bottom'
                                            icon: true,
                                            img: "../../img/timdesk/checkmark.svg",
                                            msg: "¡Registro guardado con éxito!",
                                          });
                                        } else {
                                          Lobibox.notify("error", {
                                            size: "mini",
                                            rounded: true,
                                            delay: 3000,
                                            delayIndicator: false,
                                            position: "center top",
                                            icon: true,
                                            img: "../../img/timdesk/warning_circle.svg",
                                            msg: "Ocurrió un error al editar" + respuesta ,
                                          });
                                        }
                                      },
                                      error: function (error) {
                                        console.log(error);
                                      },
                                    });
                                  } else {
                                    Lobibox.notify("error", {
                                      size: "mini",
                                      rounded: true,
                                      delay: 3000,
                                      delayIndicator: false,
                                      position: "center top",
                                      icon: true,
                                      img: "../../img/timdesk/warning_circle.svg",
                                      msg: "El folio inicial es un dato obligatorio",
                                    });
                                  }
                                } else {
                                  Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: "La serie inicial es un dato obligatorio",
                                  });
                                }
                              } else{
                                Lobibox.notify("error", {
                                  size: "mini",
                                  rounded: true,
                                  delay: 3000,
                                  delayIndicator: false,
                                  position: "center top",
                                  icon: true,
                                  img: "../../img/timdesk/warning_circle.svg",
                                  msg: "El logo debe de pesar menos de 1 MB",
                                });
                              }
                            } else {
                              Lobibox.notify("error", {
                                size: "mini",
                                rounded: true,
                                delay: 3000,
                                delayIndicator: false,
                                position: "center top",
                                icon: true,
                                img: "../../img/timdesk/warning_circle.svg",
                                msg: "El logo debe medir al menos 200px por 200px",
                              });
                            }
                          } else {
                            Lobibox.notify("error", {
                              size: "mini",
                              rounded: true,
                              delay: 3000,
                              delayIndicator: false,
                              position: "center top",
                              icon: true,
                              img: "../../img/timdesk/warning_circle.svg",
                              msg: "El logo es un dato obligatorio",
                            });
                          }
                        } else {
                          Lobibox.notify("error", {
                            size: "mini",
                            rounded: true,
                            delay: 3000,
                            delayIndicator: false,
                            position: "center top",
                            icon: true,
                            img: "../../img/timdesk/warning_circle.svg",
                            msg: "La contraseña del certificado es un dato obligatorio",
                          });
                        }
                      } else {
                        Lobibox.notify("error", {
                          size: "mini",
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: "center top",
                          icon: true,
                          img: "../../img/timdesk/warning_circle.svg",
                          msg: "El archivo .key es un dato obligatorio",
                        });
                      }
                    } else {
                      Lobibox.notify("error", {
                        size: "mini",
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/warning_circle.svg",
                        msg: "El archivo .cert es un dato obligatorio",
                      });
                    }
                  } else {
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: "El estado es un dato obligatorio",
                    });
                  }
                } else {
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "La ciudad es un dato obligatorio",
                  });
                }
              } else {
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "El código postal es un dato obligatorio",
                });
              }
            } else {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "La colonia es un dato obligatorio",
              });
            }
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "El número exterior es un dato obligatorio",
            });
          }
        } else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "La calle es un dato obligatorio",
          });
        }
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "El regimen_fiscal es un dato obligatorio",
        });
      }
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El giro comercial es un dato obligatorio",
      });
    }
  }
});

$(document).on("click","#btn_aceptar_eliminar_DatosEmpresa_52",function(){
  var id = $("#txtPKDatosEmpresa_52D").val();
  $.ajax({
    url: "php/funciones.php",
      type: "POST",
      data: {
        clase: "delete_data",
        funcion: "delete_companyData",
        data: id
      },
      dataType: "json",
      success: function (respuesta) {
      if(respuesta == 1){
        $("#eliminar_DatosEmpresa_52").modal("toggle");
        $("#tblDatosEmpresa").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../img/chat/notificacion_error.svg",
          msg: "¡Registro eliminado!",
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Ocurrió un error al eliminar",
        });
      }
      },
      error: function (error) {
        console.log(error);
      },
  });
});

function loadCombo(funcion,input,data,texto){
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_"+funcion
    },
    datatype: "json",
    success: function(respuesta){
      var res = JSON.parse(respuesta);

      html = [{value:null,text:texto}];
      $.each(res,function(i){
        
        if(res[i].id === parseInt(data)){
          html.push({value:+res[i].id,text:res[i].texto,selected:'true'});
        } else {
          html.push({value:+res[i].id,text:res[i].texto});
        }
        
      });
      
      input.setData(html);
      html = null;
      
    },
    error: function(error){
      console.log(error);
    }
  });
}