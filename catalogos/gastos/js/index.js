$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
    $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
  });
const cmbFiltroCuentaDetalles = document.getElementById('cmbFiltroCuentaDetalles');
const cmbFiltroCategoriasDetalles = document.getElementById('cmbFiltroCategoriasDetalles');
const cmbFiltroSubcategoriasDetalles = document.getElementById('cmbFiltroSubcategoriasDetalles');
const fchFiltroFechaInicioDetalles = document.getElementById('fchFiltroFechaInicioDetalles');
const fchFiltroFechaFinalDetalles = document.getElementById('fchFiltroFechaFinalDetalles');
const btnFiltroGastosDetalles= document.getElementById('btnFiltroGastosDetalles');

cargarCMBCategorias();
getListCategorySubcategory()

btnFiltroGastosDetalles.addEventListener('click',()=>{
    
    var cuenta_filtro = (cmbFiltroCuentaDetalles.value === "" || cmbFiltroCuentaDetalles === 0) ? 0 : cmbFiltroCuentaDetalles.value;
    var categoria_filtro = (cmbFiltroCategoriasDetalles.value === "" || cmbFiltroCategoriasDetalles === 0) ? 0:cmbFiltroCategoriasDetalles.value
    var subcategoria_filtro = (cmbFiltroSubcategoriasDetalles.value === "" || cmbFiltroSubcategoriasDetalles === 0) ? 0 : cmbFiltroSubcategoriasDetalles.value;
    var fechaInicial_filtro = (fchFiltroFechaInicioDetalles.value === "" || fchFiltroFechaInicioDetalles.value === 0) ? 0 :fchFiltroFechaInicioDetalles.value
    var fechaFinal_filtro = (fchFiltroFechaFinalDetalles.value === "" || fchFiltroFechaFinalDetalles.value === 0) ? 0 :fchFiltroFechaFinalDetalles.value
    getListCategorySubcategoryFilter(cuenta_filtro,categoria_filtro,subcategoria_filtro,fechaInicial_filtro,fechaFinal_filtro);
});

function cargarCMBCategorias()
{
    var html = '<option value="0" selected>Seleccione una categoria</option>';
    $.ajax({
        type:'POST',
        url: "php/funciones.php",
        dataType: "json",
        data: { clase:"get_data",funcion:"get_categorias"},
        success: function (data) {
            $.each(data, function (i) {
                html +=
                    '<option value="' +
                    data[i].PKCategoria +
                    '">' +
                    data[i].Nombre+
                    "</option>";
                
            });
            
            $("#cmbFiltroCategoriasDetalles").html(html);
        },
        error: function (error) {
            console.log("Error");
            console.log(error);
        },
    });
}

function cargarCMBSubcategorias(subCat)
{
  var html = '<option value="0" selected>Seleccione una subcategoria</option>';
  $.ajax({
    type:'POST',
    url: "php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    cache: false,
    success: function (data) {
        $.each(data, function (i) {
            html +=
                '<option value="' +
                data[i].PKSubcategoria +
                '">' +
                data[i].Nombre+
                "</option>";
        });
       
      
      $("#cmbFiltroSubcategoriasDetalles").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function getListCategorySubcategory()
{
    var html = "";
    var total_general = 0;

    $.ajax({
        type:'POST',
        url: "php/funciones.php",
        dataType: "json",
        data: { 
            clase:"get_data",
            funcion:"get_listCategorySubcategory",
        },
        cache: false,
        success: function (data) {
            

            $.each(data,(i,x)=>{
                
                html += 
                '<div class="card card-expense">'+
                    '<div class="card-header text-center card-expense-header">'+
                        x.categoria+
                    '</div>'+
                    '<div class="card-body">';
                 if(data[i].hasOwnProperty('subcategorias'))   
                    for (let j = 0; j < data[i].subcategorias.length; j++) {
                        html += '<p class="card-text">'+data[i].subcategorias[j].nombre+': $'+data[i].subcategorias[j].total+'</p>';
                    }
                html += 
                    '</div>'+
                    '<div class="card-footer text-center card-expense-footer">'+
                    'Total: $'+numeral(parseFloat(x.total_neto)).format('0,000,000,000.00')+
                    '</div></div>';
                    total_general += parseFloat(x.total_neto);
                    
            });
            
            $("#cards-reporting-gastos").html(html);
            $(".totalText").text("$"+numeral(parseFloat(total_general)).format('0,000,000,000.00'));
            
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
    });
}

function getListCategorySubcategoryFilter(cuenta,categoria,subcategoria,fecha_inicial,fecha_final)
{
    var html = "";
    var total_general = 0;

    $.ajax({
        type:'POST',
        url: "php/funciones.php",
        dataType: "json",
        data: { 
            clase:"get_data",
            funcion:"get_listCategorySubcategoryFilter",
            cuenta:cuenta,
            categoria:categoria,
            subcategoria:subcategoria,
            fecha_inicial:fecha_inicial,
            fecha_final:fecha_final
        },
        cache: false,
        success: function (data) {
            if(data.length > 0){
                $.each(data,(i,x)=>{
                    
                    html += 
                    '<div class="card card-expense">'+
                        '<div class="card-header text-center card-expense-header">'+
                            x.categoria+
                        '</div>'+
                        '<div class="card-body">';
                        
                    for (let j = 0; j < data[i].subcategorias.length; j++) {
                        html += '<p class="card-text">'+data[i].subcategorias[j].nombre+': $'+data[i].subcategorias[j].total+'</p>';
                    }
                    html += 
                        '</div>'+
                        '<div class="card-footer text-center card-expense-footer">'+
                        'Total: $'+numeral(parseFloat(x.total_neto)).format('0,000,000,000.00')+
                        '</div></div>';
                        total_general += parseFloat(x.total_neto);
                });
            } else {
                html = '<div class="col-12 text-center">No se encontraron coincidencias según los filtros seleccionados.</div>';
            }

            $("#cards-reporting-gastos").html(html);
            $(".totalText").text("$"+numeral(parseFloat(total_general)).format('0,000,000,000.00'));
            $("#ejercicioAnioActual").html("");
            cmbFiltroCuentaDetallesSelect.setSelected('');
            cmbFiltroCategoriasDetallesSelect.setSelected(0);
            cmbFiltroSubcategoriasDetallesSelect.setSelected('');
            fchFiltroFechaInicioDetalles.value = "";
            fchFiltroFechaFinalDetalles.value = "";
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
    });
}