document.addEventListener('DOMContentLoaded', () => {

    //Cargar los combos con con el plugin SlimSelect
    const cmbSlimTipoReporte = new SlimSelect ({
        select: "#cmbTipoReporte",
        placeholder: "Seleccionar tipo de reporte",
        deselectLabel: '<span class="">✖</span>',
        onChange: () => {
            document.getElementById('invalid-tipoReporte').classList.remove('d-block');
            document.getElementById('invalid-tipoReporte').classList.add('d-none');

            //Si en el combo del tipo de reporte se elgige general se deshabilita el combo de tipo de movimiento y los controles del intervalo de fechas
            const tipoReporte = document.getElementById('cmbTipoReporte').value;
            if(tipoReporte == 1){
                document.getElementById('inputDateDe').disabled = true;
                document.getElementById('inputDateHasta').disabled = true;
                cmbSlimTipoMovimiento.disable();
            }else{
                document.getElementById('inputDateDe').disabled = false;
                document.getElementById('inputDateHasta').disabled = false;
                cmbSlimTipoMovimiento.enable();
            }
        }
    });

    
    const cmbSlimClaves = new SlimSelect ({
        select: "#cmbClave",
        placeholder: "Seleccionar clave",
        deselectLabel: '<span class="">✖</span>',
        onChange: () => {
            document.getElementById('invalid-claves').classList.remove('d-block');
            document.getElementById('invalid-claves').classList.add('d-none');
            
        }
    });
    let clavesSeleccionadas = cmbSlimClaves.selected();;
    
    const cmbSlimSucursal = new SlimSelect ({
        select: "#cmbSucursal",
        placeholder: "Seleccionar sucursal",
        deselectLabel: '<span class="">✖</span>',
        onChange: () => {
            document.getElementById('invalid-sucursal').classList.remove('d-block');
            document.getElementById('invalid-sucursal').classList.add('d-none');
        }
    });

    const cmbSlimTipoMovimiento = new SlimSelect ({
        select: "#cmbTipoMovimiento",
        placeholder: "Seleccionar tipo de movimiento",
        deselectLabel: '<span class="">✖</span>',
    });

    document.getElementById('inputDateDe').disabled = true;
    document.getElementById('inputDateHasta').disabled = true;
    cmbSlimTipoMovimiento.disable();

    //Obtener las claves de los productos de la empresa y cargarlas en el combo correspondiente
    let htmlCmbClaves = "";    
    fetch("../../php/funciones.php?clase=get_data&funcion=get_cmbClaves")
    .then( respuesta => {
        //console.log(respuesta)
        return respuesta.json()
    })
    .then( datos => {
        datos.forEach(element => {
            htmlCmbClaves +=
                    '<option value="' +
                    element.ClaveInterna +
                    '">' +
                    element.ClaveInterna +
                    "</option>";
        });
        document.getElementById('cmbClave').innerHTML = htmlCmbClaves;
    })
    .catch( error => {
        //console.log(error)
    });

    //Obtener las sucursales de la empresa y cargarlas en el combo correspondiente
    let htmlCmbSucursales = "";    
    fetch("../../php/funciones.php?clase=get_data&funcion=get_cmbSucursalesKardex")
    .then( respuesta => {
        //console.log(respuesta)
        return respuesta.json()
    })
    .then( datos => {
        datos.forEach(element => {
            htmlCmbSucursales +=
                    '<option data-placeholder="true"></option>' +
                    '<option value="' +
                    element.id +
                    '">' +
                    element.sucursal +
                    "</option>";
        });
        document.getElementById('cmbSucursal').innerHTML = htmlCmbSucursales;
    })
    .catch( error => {
        //console.log(error)
    });

    fetch("../../php/funciones.php?clase=edit_data&funcion=update_clavesKardexTemp")
    .then( respuesta => {
        //console.log(respuesta)
        return respuesta
    })
    .then( datos => {
        //console.log(datos)
    })
    .catch( error => {
        //console.log(error)
    });

    //Primero se actualizan todas las claves que existan en la tabla temporal a un estado de activo=0 y luego por cada opción seleccionada inserto o actulizo según sea el caso cada clave en la tabla temporal
    document.getElementById('cmbClave').addEventListener('change', ()=>{
        fetch("../../php/funciones.php?clase=edit_data&funcion=update_clavesKardexTemp")
        .then( respuesta => {
            //console.log(respuesta)
            return respuesta
        })
        .then( datos => {
            //console.log(datos)
        })
        .catch( error => {
            //console.log(error)
        });
        clavesSeleccionadas = cmbSlimClaves.selected();
        //console.log(clavesSeleccionadas)
        clavesSeleccionadas.forEach((clave)=> {
            fetch("../../php/funciones.php?clase=save_data&funcion=save_clavesKardexTemp&clave="+clave)
            .then( respuesta => {
                //console.log(respuesta)
                return respuesta.json()
            })
            .then( datos => {
                //console.log(datos)
            })
            .catch( error => {
                //console.log(error)
            });
        })
    })

    /*
        Al dar clic en el botón del filtro validar que los combos de tipo de reporte, claves y sucursal tengan valor seleccionado.
        También validar que si uno de los controles de fecha tiene valor también el otro lo tenga.
    */
    document.getElementById('btnFinalizar').addEventListener('click', ()=>{
        const reporte = cmbSlimTipoReporte.selected();
        const sucursal = cmbSlimSucursal.selected();
        let fechaDe =  document.getElementById('inputDateDe').value;
        if(!fechaDe){
            fechaDe = '0000-00-00'
        }
        //console.log(fechaDe);
        let fechaHasta =  document.getElementById('inputDateHasta').value;
        if(!fechaHasta){
            fechaHasta = '0000-00-00'
        }
        //console.log(fechaHasta);
        let tipoMovimiento = cmbSlimTipoMovimiento.selected();
        if(tipoMovimiento == ''){
            tipoMovimiento = 0
        }
        if(reporte == ''){
            document.getElementById('invalid-tipoReporte').classList.remove('d-none');
            document.getElementById('invalid-tipoReporte').classList.add('d-block');
        } 
        if(clavesSeleccionadas.length == 0){
            document.getElementById('invalid-claves').classList.remove('d-none');
            document.getElementById('invalid-claves').classList.add('d-block');
        } 
        if(sucursal == ''){
            document.getElementById('invalid-sucursal').classList.remove('d-none');
            document.getElementById('invalid-sucursal').classList.add('d-block');
        }
        if(fechaDe == '0000-00-00' && fechaHasta != '0000-00-00' && reporte == 2){
            document.getElementById('invalid-fechaDe').classList.remove('d-none');
            document.getElementById('invalid-fechaDe').classList.add('d-block');
        }
        if(fechaHasta == '0000-00-00' && fechaDe != '0000-00-00' && reporte == 2){
            document.getElementById('invalid-fechaHasta').classList.remove('d-none');
            document.getElementById('invalid-fechaHasta').classList.add('d-block');
        }
        if(reporte == 1 && clavesSeleccionadas && sucursal){
            $("#tblReporteDetallado").DataTable().destroy();
            document.getElementById('tblReporteGeneral').classList.remove('d-none');
            document.getElementById('tblReporteDetallado').classList.add('d-none');
            cargarReporteKardexGeneral(sucursal);
        }
        if(reporte == 2 && clavesSeleccionadas && sucursal && ((fechaDe == '0000-00-00' && fechaHasta == '0000-00-00') || (fechaDe != '0000-00-00' && fechaHasta != '0000-00-00'))){
            $("#tblReporteGeneral").DataTable().destroy();
            document.getElementById('tblReporteDetallado').classList.remove('d-none');
            document.getElementById('tblReporteGeneral').classList.add('d-none');
            cargarReporteKardexDetallado(sucursal, tipoMovimiento, fechaDe, fechaHasta);
        }
    })

    document.getElementById('inputDateDe').addEventListener('click', ()=>{
        document.getElementById('invalid-fechaDe').classList.remove('d-block');
        document.getElementById('invalid-fechaDe').classList.add('d-none');
    })
    document.getElementById('inputDateHasta').addEventListener('click', ()=>{
        document.getElementById('invalid-fechaHasta').classList.remove('d-block');
        document.getElementById('invalid-fechaHasta').classList.add('d-none');
    })

});

function cargarReporteKardexGeneral(sucursal){
    $("#tblReporteGeneral").DataTable().destroy();
    $("#tblReporteGeneral").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: false,
        bSort: false,
        pageLength: 50,
        responsive: false,
        lengthChange: true,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
      <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-table-custom",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [
            {
              extend: "excelHtml5",
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
              },
              text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
              className: "btn-table-custom--turquoise",
              titleAttr: "Excel",
            },
          ],
        },
        ajax: {
            url: "../../php/funciones.php",
            data: {
              clase: "get_data",
              funcion: "get_TablaReporteGeneralKardex",
              data: sucursal
            }
          },
          columns: [
            { data: "Id" },
            { data: "Clave" },
            { data: "Descripcion" },
            { data: "Lote" },
            { data: "Serie" },
            { data: "Caducidad" },
            { data: "InventarioInicial" },
            { data: "Entradas" },
            { data: "Salidas" },
            { data: "CantidadSistema" }
          ]
      });
}

function cargarReporteKardexDetallado(sucursal, tipoMovimiento, fechaDe, fechaHasta){
    $("#tblReporteDetallado").DataTable().destroy();
    $("#tblReporteDetallado").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: [0, 14, 15], visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
      <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-table-custom",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [
            {
              extend: "excelHtml5",
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
              },
              text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
              className: "btn-table-custom--turquoise",
              titleAttr: "Excel",
            },
          ],
        },    
        ajax: {
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_TablaReporteDetalladoKardex",
            data1: sucursal,
            data2: tipoMovimiento,
            data3: fechaDe,
            data4: fechaHasta
          },
          dataType: "json",
        },
        columns: [
          { data: "Id" },
          { data: "Clave" },
          { data: "Descripcion" },
          { data: "Lote" },
          { data: "Serie" },
          { data: "Caducidad" },
          { data: "Entradas" },
          { data: "Salidas" },
          { data: "CantidadSistema" },
          { data: "Referencia" },
          { data: "TipoMovimiento" },
          { data: "Usuario" },
          { data: "Observaciones" },
          { data: "Fecha" },
          { data: "Folio" },
          { data: "Motivo" },
        ],
      })
}

function setFormatDatatables() {
    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "",
        sLast: "",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };
    return idioma_espanol;
  }