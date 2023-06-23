$(document).ready(function () {
  var html = '<div class="card spaced-title">';
  var html1 = "";
  $("#loader").css("display","block");
  $("#loader").addClass("loader");
//   new SlimSelect({
//     select: "#cmbRol",
//     deselectLabel: '<span class="">✖</span>',
//     placeholder: "Seleccione un rol...",
//   });

  if ($("#txtIdProfile").val() !== "" && $("#txtIdProfile").val() !== null) {
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "getProfile",
        value: $("#txtIdProfile").val(),
      },
      dataType: "json",
      success: function (respuesta) {
        $("#txtPerfil").val(respuesta[0].nombre);
        //loadCombo(respuesta[0].roles_id, "cmbRol");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else if (
    $("#txtUserRole").val() !== "" &&
    $("#txtUserRole").val() !== null
  ) {
    console.log("role: ", $("#txtUserRole").val());
    var role = parseInt($("#txtUserRole").val());
    //loadCombo(role, "cmbRol");
    //$("#cmbRol").attr("readonly", true);
  } else {
    //loadCombo("", "cmbRol");
  }

  if ($("#txtIdProfile").val() !== "" && $("#txtIdProfile").val() !== null) {
    var html =
      '<button class="btnesp first espEliminar float-right" name="btnGuardar"' +
      'id="btnEliminar" style="margin-right:10px;">Eliminar</button>';

    $("#showBtnEliminar").html(html);
  }
  
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "getSections" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        //console.log("respuesta:",respuesta);
        //if (0==0) {
          $("#title-card-tabs").append(
            '<div class="card mb-1">' +
              '<div class="card-header title_tab" id="heading' +
              respuesta[i].id +
              '">' +
              '<h5 class="mb-0">' +
              '<a class="roles-buton-secction" id="opt-header-' +
              respuesta[i].siglas +
              '" type="button" data-toggle="collapse" data-target="#collapse' +
              respuesta[i].id +
              '" aria-expanded="true" aria-controls="collapse' +
              respuesta[i].id +
              '">' +
              '<i id="opt-group-' +
              respuesta[i].siglas +
              '" class="opt-menu-icon"></i>' +
              "<span class='roles-title'>" +
              respuesta[i].seccion +
              "</span>" +
              "</a>" +
              "</h5>" +
              "</div>" +
              '<div id="collapse' +
              respuesta[i].id +
              '" class="collapse collapse-body" aria-labelledby="heading' +
              respuesta[i].id +
              '" data-parent="#title-card-tabs">' +
              '<div class="card-body">' +
              '<div class="radio-permisos-container">' +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoControlTotal' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender"><label for="rdoControlTotal' +
              respuesta[i].siglas +
              '">Control total<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoNoEliminar' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoNoEliminar' +
              respuesta[i].siglas +
              '">Controlar todo excepto eliminar<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoVer' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoVer' +
              respuesta[i].siglas +
              '">Solo lectura<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoSinPermisos' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoSinPermisos' +
              respuesta[i].siglas +
              '">Sin permisos<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoPersonalizado' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoPersonalizado' +
              respuesta[i].siglas +
              '">Personalizado<span></span> <span></span></label>' +
              "</span>" +
              "</div>" +
              "<br>" +
              '<div class="d-flex flex-wrap" id="checks-body-' +
              respuesta[i].id +
              '">'
          );

          $.ajax({
            url: "php/funciones.php",
            data: {
              clase: "get_data",
              funcion: "getScreensVal",
              value: respuesta[i].id,
            },
            dataType: "json",
            success: function (respuesta1) {
              //console.log("valores pantalla:",respuesta1);
              $.each(respuesta1, function (j) {
                var pantalla = eliminarDiacriticos(respuesta1[j].pantalla);
                pantalla = pantalla.replace(/\//g, "");
                pantalla = pantalla.replace(/ /g, "");
                $("#checks-body-" + respuesta[i].id).append(
                  '<div class="col-lg-2">' +
                    '<input type="checkbox" name="chkTodo' +
                    pantalla +
                    '" class="chk' +
                    respuesta[i].siglas +
                    ' chkEncabezado" id="chkTodo' +
                    pantalla +
                    '" disabled onclick="check(this)">' +
                    '<label class="space-left-7" for="chkTodo' +
                    pantalla +
                    '"><b>' +
                    respuesta1[j].pantalla +
                    "</b></label>" +
                    '<form id="form-checks-' +
                    respuesta1[j].id +
                    '" action="" method="post" data-pantalla="' +
                    respuesta1[j].id +
                    '" data-seccion="' +
                    respuesta1[j].seccion_id +
                    '">'
                );

                $.ajax({
                  url: "php/funciones.php",
                  data: {
                    clase: "get_data",
                    funcion: "getFunctionsVal",
                    value: respuesta1[j].id,
                  },
                  dataType: "json",
                  success: function (respuesta2) {
                    //var pantalla = respuesta1[j].pantalla.replace(/ /g,"");
                    var pantalla = eliminarDiacriticos(respuesta1[j].pantalla);
                    pantalla = pantalla.replace(/\//g, "");
                    pantalla = pantalla.replace(/ /g, "");

                    //console.log("pantalla: ",pantalla);
                    $.each(respuesta2, function (l) {
                      var funcion = respuesta2[l].funcion.replace(/ /g, "");

                      $("#form-checks-" + respuesta1[j].id).append(
                        '<input type="checkbox" id="chk' +
                          funcion +
                          pantalla +
                          '" class="space-left-7 chk' +
                          pantalla +
                          " chk" +
                          respuesta[i].siglas +
                          " chk" +
                          respuesta[i].siglas +
                          funcion +
                          '" name="chkVerEmpleado' +
                          respuesta[i].siglas +
                          funcion +
                          " chk" +
                          respuesta[i].siglas +
                          funcion +
                          '" disabled data-idf="' +
                          respuesta2[l].id +
                          '" data-idp="' +
                          respuesta1[j].id +
                          '" data-ids="' +
                          respuesta[i].id +
                          '" data-funcion="' +
                          funcion +
                          '">' +
                          '<label class="space-left-7" for="chk' +
                          funcion +
                          respuesta[i].id +
                          '">' +
                          respuesta2[l].funcion +
                          "</label><br>"
                      );
                      //console.log("idf:",$('#chk'+respuesta2[l].Funcion+pantalla).data('idf'));
                      $.ajax({
                        url: "php/funciones.php",
                        data: {
                          clase: "get_data",
                          funcion: "getFunctionsValues",
                          value: $("#txtIdProfile").val(),
                          id: respuesta1[j].id,
                        },
                        dataType: "json",
                        success: function (respuesta3) {
                          //console.log("respuesta3:",respuesta3);

                          var pantalla = eliminarDiacriticos(
                            respuesta1[j].pantalla
                          );
                          pantalla = pantalla.replace(/\//g, "");
                          pantalla = pantalla.replace(/ /g, "");

                          //var funcion = respuesta2[l].funcion.replace(/ /g,"");
                          //console.log("chk"+funcion + pantalla);
                          if (respuesta3.length > 0) {
                            $.each(respuesta3, function (k) {
                              if (
                                respuesta3[k].pantalla_id === respuesta1[j].id
                              ) {
                                if (respuesta3[k].Ver === 0) {
                                  $("#chkVer" + pantalla).attr(
                                    "checked",
                                    false
                                  );
                                } else {
                                  $("#chkVer" + pantalla).attr("checked", true);
                                }
                                if (respuesta3[k].Agregar === 0) {
                                  $("#chkAgregar" + pantalla).attr(
                                    "checked",
                                    false
                                  );
                                } else {
                                  $("#chkAgregar" + pantalla).attr(
                                    "checked",
                                    true
                                  );
                                }
                                if (respuesta3[k].Editar === 0) {
                                  $("#chkEditar" + pantalla).attr(
                                    "checked",
                                    false
                                  );
                                } else {
                                  $("#chkEditar" + pantalla).attr(
                                    "checked",
                                    true
                                  );
                                }
                                if (respuesta3[k].Eliminar === 0) {
                                  $("#chkEliminar" + pantalla).attr(
                                    "checked",
                                    false
                                  );
                                } else {
                                  $("#chkEliminar" + pantalla).attr(
                                    "checked",
                                    true
                                  );
                                }
                                if (respuesta3[k].Exportar === 0) {
                                  $("#chkExportarexcel" + pantalla).attr(
                                    "checked",
                                    false
                                  );
                                } else {
                                  $("#chkExportarexcel" + pantalla).attr(
                                    "checked",
                                    true
                                  );
                                }

                                if (
                                  respuesta3[k].Ver === 1 &&
                                  respuesta3[k].Agregar === 1 &&
                                  respuesta3[k].Editar === 1 &&
                                  respuesta3[k].Eliminar === 1 &&
                                  respuesta3[k].Exportar === 1
                                ) {
                                  $("#chkTodo" + pantalla).attr(
                                    "checked",
                                    true
                                  );
                                }
                              }
                              ///Habilita el boton para dar Guardar cambios cuando se termino de consultar todos los permisos.
                              if(respuesta.length == i+1 && respuesta1.length == j+1 && respuesta2.length == l+1 && respuesta3.length == k+1){
                                document.querySelector('#btnGuardar').disabled = false;
                                $(".loader").fadeOut("slow");
                                $("#loader").removeClass("loader");
                              }

                            });
                            //console.log("permisos: ",respuesta3[0].ver);
                            /*if(respuesta3[0].permiso === 0){
                              $('#chkTodo'+pantalla).attr('checked',false);
                              $('#chk'+funcion+pantalla).attr('checked',false);
                            }else{
                              $('#chkTodo'+pantalla).attr('checked',false);
                              $('#chk'+funcion+pantalla).attr('checked',true);
                            }*/
                          }
                        },
                        error: function (error) {
                          console.log(error);
                        },
                      });

                      $("#rdoControlTotalGeneral").on("click", function () {
                        var pantalla = eliminarDiacriticos(
                          respuesta1[j].pantalla
                        );
                        pantalla = pantalla.replace(/\//g, "");
                        pantalla = pantalla.replace(/ /g, "");
                        if (this.checked) {
                          //console.log(".chk" + pantalla);
                          $(".chk" + pantalla).each(function () {
                            this.checked = true;
                            this.disabled = true;
                          });
                          $("#chkTodo" + pantalla).each(function () {
                            this.checked = true;
                            this.disabled = true;
                          });
                        }
                      });

                      $("#rdoNoEliminarGeneral").on("click", function () {
                        if (this.checked) {
                          $(".chk" + respuesta[i].siglas).each(function () {
                            this.disabled = true;
                            this.checked = true;
                          });

                          $(".chk" + respuesta[i].siglas + "Eliminar").each(
                            function () {
                              this.checked = false;
                            }
                          );
                        }
                      });

                      $("#rdoVerGeneral").on("click", function () {
                        if (this.checked) {
                          $(".chk" + respuesta[i].siglas).each(function () {
                            this.disabled = true;
                            this.checked = false;
                          });

                          $(".chk" + respuesta[i].siglas + "Ver").each(
                            function () {
                              this.checked = true;
                            }
                          );
                        }
                      });

                      $("#rdoSinPermisosGeneral").on("click", function () {
                        if (this.checked) {
                          $(".chk" + respuesta[i].siglas).each(function () {
                            this.disabled = true;
                            this.checked = false;
                          });
                        }
                      });

                      $("#rdoPersonalizadoGeneral").on("click", function () {
                        if (this.checked) {
                          $(".chk" + respuesta[i].siglas).each(function () {
                            this.disabled = false;
                          });
                        }
                      });
                    });
                  },
                  error: function (error) {
                    console.log(error);
                  },
                });
                $("#checks-body-" + respuesta[i].id).append(
                  "</form>" + "</div>" + "</div>" + "</div>" + "</div>"
                );
                var pantalla = eliminarDiacriticos(respuesta1[j].pantalla);
                pantalla = pantalla.replace(/\//g, "");
                pantalla = pantalla.replace(/ /g, "");
                $("#chkTodo" + pantalla).on("click", function () {
                  if (this.checked) {
                    $(".chk" + pantalla).each(function () {
                      this.checked = true;
                    });
                  } else {
                    $(".chk" + pantalla).each(function () {
                      this.checked = false;
                    });
                  }
                });
              });
            },
            error: function (error) {
              console.log(error);
            },
          });
        //}

        $("#rdoControlTotal" + respuesta[i].siglas).on("click", function () {
          //console.log("control total por pantalla:",respuesta[i].siglas);
          if (this.checked) {
            $(".chk" + respuesta[i].siglas).each(function () {
              this.disabled = true;
              this.checked = true;
            });
          }
        });

        $("#rdoNoEliminar" + respuesta[i].siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].siglas).each(function () {
              this.disabled = true;
              this.checked = true;
            });

            $(".chk" + respuesta[i].siglas + "Eliminar").each(function () {
              this.checked = false;
            });

            $(".chkEncabezado").each(function () {
              this.checked = false;
            });
          }
        });

        $("#rdoVer" + respuesta[i].siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].siglas).each(function () {
              this.disabled = true;
              this.checked = false;
            });

            $(".chk" + respuesta[i].siglas + "Ver").each(function () {
              this.checked = true;
            });
          }
        });

        $("#rdoSinPermisos" + respuesta[i].siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].siglas).each(function () {
              this.disabled = true;
              this.checked = false;
            });
          }
        });

        $("#rdoPersonalizado" + respuesta[i].siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].siglas).each(function () {
              this.disabled = false;
            });
          }
        });
      });
      
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#btnGuardar", function () {
  $("#loader").css("display","block");
  $("#loader").addClass("loader");
  document.querySelector('#btnGuardar').disabled = true;
  var perfil = $("#txtPerfil").val();
  //var rol = $("#cmbRol").val();
  var perfilBad = false,
    rolBad = false;

  if (!perfil) {
    $("#invalid-perfil").css("display", "block");
    $("#txtPerfil").addClass("is-invalid");
    perfilBad = true;
  } else {
    //validarPerfilUnico(perfil);
    $("#invalid-perfil").css("display", "none");
    $("#txtPerfil").removeClass("is-invalid");
    perfilBad = false;
  }

//   if (!rol) {
//     $("#invalid-rol").css("display", "block");
//     $("#cmbRol").addClass("is-invalid");
//     rolBad = true;
//   } else {

    // $("#invalid-rol").css("display", "none");
    // $("#cmbRol").removeClass("is-invalid");
    //rolBad = false;
  //}
  if (!perfilBad) {
    var noForms = document.forms;
    var permisos = new Array();
    /* var funcionVer = new Array();
    var funcionAgregar = new Array();
    var funcionEditar = new Array();
    var funcionEliminar = new Array();
    var funcionExportarExcel = new Array(); */
    var noElements;
    var screen_sections = new Array();

    var funcionVer;
    var funcionAgregar;
    var funcionEditar;
    var funcionEliminar;
    var funcionExportarExcel;

    

    $.each(noForms, function (i) {
      //console.log("forms:",$("#" + noForms[i].id + "").data("seccion"));

      noElements = document.forms[i].elements;
      //console.log("indice",i);
      $.each(noElements, function (j) {
        //var idp = $("#" + noElements[j].id + "").data("idp");
        if( parseInt($("#" + noElements[j].id).data("idp")) == parseInt($("#" + noForms[i].id + "").data("pantalla"))){
          console.log("Iguales");
          console.log("IDP: " , $("#" + noElements[j].id).data("idp"))
        }else{
          console.log("IDP: " , $("#" + noElements[j].id).data("idp"))
          console.log("PantallaForm: " , $("#" + noForms[i].id + "").data("pantalla"))
          
        }
        //console.log(noElements[j].id);
        //var ids = $("#" + noElements[j].id + "").data("ids");

        if ($("#" + noElements[j].id + "").data("funcion") === "Ver") {
          if ($("#" + noElements[j].id + "").is(":checked")) {
           // funcionVer.push({ funcionVer: 1 });
           funcionVer = 1;
          } else {
           // funcionVer.push({ funcionVer: 0 });
           funcionVer = 0;
          }
        }

        if ($("#" + noElements[j].id + "").data("funcion") === "Agregar") {
          if ($("#" + noElements[j].id + "").is(":checked")) {
            //funcionAgregar.push({ funcionAgregar: 1 });
            funcionAgregar = 1;
          } else {
            //funcionAgregar.push({ funcionAgregar: 0 });
            funcionAgregar = 0;
          }
        }

        if ($("#" + noElements[j].id + "").data("funcion") === "Editar") {
          if ($("#" + noElements[j].id + "").is(":checked")) {
            //funcionEditar.push({ funcionEditar: 1 });
            funcionEditar =1;
          } else {
            //funcionEditar.push({ funcionEditar: 0 });
            funcionEditar= 0;
          }
        }

        if ($("#" + noElements[j].id + "").data("funcion") === "Eliminar") {
          if ($("#" + noElements[j].id + "").is(":checked")) {
            //funcionEliminar.push({ funcionEliminar: 1 });
            funcionEliminar = 1;
          } else {
            //funcionEliminar.push({ funcionEliminar: 0 });
            funcionEliminar= 0;
          }
        }

        if (
          $("#" + noElements[j].id + "").data("funcion") === "Exportarexcel"
        ) {
          if ($("#" + noElements[j].id + "").is(":checked")) {
            //funcionExportarExcel.push({ funcionExportarExcel: 1 });
            funcionExportarExcel = 1;
          } else {
            //funcionExportarExcel.push({ funcionExportarExcel: 0 });
            funcionExportarExcel = 0;
          }
        }
      });
      screen_sections.push({
        pantalla: $("#" + noForms[i].id + "").data("pantalla"),
        seccion: $("#" + noForms[i].id + "").data("seccion"),
        funcionVer: funcionVer,
        funcionAgregar: funcionAgregar,
        funcionEditar: funcionEditar,
        funcionEliminar: funcionEliminar,
        funcionExportarExcel: funcionExportarExcel
      });
    });

    for (let index = 0; index < screen_sections.length; index++) {
      var pantalla = index + 1;
     /*  permisos.push({
        funcion_ver: funcionVer[index]["funcionVer"],
        funcion_agregar: funcionAgregar[index]["funcionAgregar"],
        funcion_editar: funcionEditar[index]["funcionEditar"],
        funcion_eliminar: funcionEliminar[index]["funcionEliminar"],
        funcion_exportarExcel:
          funcionExportarExcel[index]["funcionExportarExcel"],
        pantalla: screen_sections[index]["pantalla"],
        seccion: screen_sections[index]["seccion"],
      }); */
      permisos.push({
        funcion_ver: screen_sections[index]["funcionVer"],
        funcion_agregar: screen_sections[index]["funcionAgregar"],
        funcion_editar: screen_sections[index]["funcionEditar"],
        funcion_eliminar: screen_sections[index]["funcionEliminar"],
        funcion_exportarExcel:
        screen_sections[index]["funcionExportarExcel"],
        pantalla: screen_sections[index]["pantalla"],
        seccion: screen_sections[index]["seccion"],
      });
    }

    var jsonPermisos = JSON.stringify(permisos);

    console.log(jsonPermisos);
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "savePermission",
        value: jsonPermisos,
        perfil: perfil,
        rol: "",
        id_perfil: $("#txtIdProfile").val(),
        user: $("#txtUser").val(),
        name: $("#txtUserName").val(),
        role: $("#txtUserRole").val(),
      },
      method: "post",
      dataType: "json",
      success: function (respuesta) {
        console.log("save permission", respuesta);
        if (respuesta != 0) {
          window.location.href = "../../index.php";
          //alert("");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡El perfil fue guardado!",
          });
          document.querySelector('#btnGuardar').disabled = false;

        }
        $(".loader").fadeOut("slow");
        $("#loader").removeClass("loader");

      },
      error: function (error) {
        console.log(error);
        document.querySelector('#btnGuardar').disabled = false;
      },
    });
  }else{
    document.querySelector('#btnGuardar').disabled = false;
  }
});

$(document).on("click", "#btnEliminar", function () {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "deletePermission",
      value: $("#txtIdProfile").val(),
    },
    method: "post",
    dataType: "json",
    success: function (respuesta) {
      window.location.href = "../../#CargarPerfiles";
      /*if(respuesta){
            alert("Elemento eliminado");
            window.location.href = "../../#CargarPerfiles";
          } else {
            alert("Elemento no eliminado." + respuesta);
          }*/
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function eliminarDiacriticos(texto) {
  return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function loadCombo(data, input) {
  var html = '<option data-placeholder="true"></option>';
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_roles" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta combo:",respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].id) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].id +
            '" ' +
            selected +
            ">" +
            respuesta[i].name +
            "</option>";
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      } else {
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$("#chkTodoEmpleados").click(function (e) { 
  e.preventDefault();
  if(this.is(":checked")){
    console.log("1");
  }else{
    console.log("0");
  }
});

function check(ini){
  console.log("Dentro");
  if(ini.checked==true){
    ini.setAttribute("checked",true);
  }else{
    console.log("0");
    ini.setAttribute("checked",false);
  }
}
