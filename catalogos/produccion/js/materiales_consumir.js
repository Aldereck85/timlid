var _permissions = {
    read: 0,
    add: 0,
    edit: 0,
    delete: 0,
    export: 0,
  };

var topButtons = [];
  
  $(document).ready(function () {
    loadCombo('sucursales','#sucursal_input','','','');
    new SlimSelect({
      select: '#sucursal_input'
    });
    validate_Permissions(74);
  });
  
  function setFormatDatatables() {
    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };
    return idioma_espanol;
  }
  
  function validate_Permissions(pkPantalla) {
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
      dataType: "json",
      success: function (data) {
        _permissions.read = data[0].isRead;
        _permissions.add = data[0].isAdd;
        _permissions.edit = data[0].isEdit;
        _permissions.delete = data[0].isDelete;
        _permissions.export = data[0].isExport;
  
        //MATERIALES
        if (pkPantalla == "74") {
          if (_permissions.export == "1") {
            topButtons.push({
              extend: "excelHtml5",
              text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
              className: "btn-custom--white-dark",
              titleAttr: "Excel",
            });
          }
        }
  
        $("#tblListaMateriales").dataTable({
          language: setFormatDatatables(),
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 50,
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
            buttons: topButtons,
          },
          ajax: {
            url: "../../php/funciones.php",
            data: {
              clase: "get_data",
              funcion: "get_listaMaterialesConsumirTable",
              data: 0
            },
          },
          columns: [
            { data: "Id" },
            { data: "Sucursal" },
            { data: "ClaveInterna" },
            { data: "Nombre" },
            { data: "Existencia" },
            { data: "MaterialesConsumir" },
            { data: "Unidad" }
          ],
          columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 6, width: "0px" },
          ],
        });
      },
    });
  }

  function loadCombo(funcion,input,data,value,texto){
  
    $.ajax({
      url: "../../php/funciones.php",
      method:"POST",
      data: {
        clase: "get_data",
        funcion: "get_"+funcion,
        value: value
      },
      datatype: "json",
      success: function(respuesta){
        var res = JSON.parse(respuesta);
        html = "<option data-placeholder='true'></option><option value=0>Todas</option>";
        if(res.length > 0){
          $.each(res,function(i){
            if(res[i].id === parseInt(data)){
              
              html += "<option value='"+res[i].id+"' selected>"+res[i].texto+"</option>";
            } else {
              
              html += "<option value='"+res[i].id+"'>"+res[i].texto+"</option>";
            }
          });
        } else {
          html += "<option>No hay registros.</option>";
        }
        
        $(input).html(html);
      },
      error: function(error){
        console.log(error);
      }
    });
   
  }

  $(document).on("click","#filtro_materiales",function(){
    console.log('que pedo');
    if($("#sucursal_input").val()){
      $("#tblListaMateriales").DataTable().destroy();
      $("#tblListaMateriales").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
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
          buttons: topButtons,
        },
        ajax: {
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_listaMaterialesConsumirTable",
            data: $('#sucursal_input').val()
          },
        },
        columns: [
          { data: "Id" },
          { data: "Sucursal" },
          { data: "ClaveInterna" },
          { data: "Nombre" },
          { data: "Existencia" },
          { data: "MaterialesConsumir" },
          { data: "Unidad" }
        ],
        columnDefs: [
          { orderable: false, targets: 0, visible: false }
        ],
      });
    }else{
      $("#invalid-sucursal").css("display", "block");
    }
  });

  $(document).on("change","#sucursal_input",function(){
      $("#invalid-sucursal").css("display", "none");
  });