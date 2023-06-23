$(document).ready(function () {
    
});

function descargarF(idF){
    $.ajax({
      url: "functions/DescargarPDF.php",
      data: {idFac:idF},
      xhrFields: {
        responseType: 'blob'
      },
      success: function (response) {
        if(response!="err"){
          var a = document.createElement('a');
          var url = window.URL.createObjectURL(response);
          a.href = url;
          a.download = 'NC-'+idF+'.pdf';
          a.click();
          window.URL.revokeObjectURL(url);
        }else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Error, no se pudo descargar!",
          });
        }
      }
    });
  }


  function Descarga_xml(id) {
    $.ajax({
      url: "functions/function_descarga_xml.php",
      data: {
        id: id,
      },
      dataType: "XML",
  
      success: function (data) {
        if (data != "err") {
          var a = document.createElement("a");
          var xmlDoc = new XMLSerializer().serializeToString(data);
          var blob = new File([xmlDoc], "CP" + id + ".xml", { type: "text/xml" });
          var url = window.URL.createObjectURL(blob);
          a.href = url;
          a.download = "NC-" + id + ".xml";
          a.click();
          window.URL.revokeObjectURL(url);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Error, no se pudo descargar!",
          });
        }
      },
      error: function (jqXHR, exception, data, response) {
        var msg = "";
        if (jqXHR.status === 0) {
          msg = "Not connect.\n Verify Network.";
        } else if (jqXHR.status == 404) {
          msg = "Requested page not found. [404]";
        } else if (jqXHR.status == 500) {
          msg = "Internal Server Error [500].";
        } else if (exception === "parsererror") {
          msg = "Requested JSON parse failed.";
        } else if (exception === "timeout") {
          msg = "Time out error.";
        } else if (exception === "abort") {
          msg = "Ajax request aborted.";
        } else {
          msg = "Uncaught Error.\n" + jqXHR.responseText;
        }
      },
    });
  }
  
  