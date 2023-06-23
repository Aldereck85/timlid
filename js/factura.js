jQuery(document).ready(function(){
    var id = $('#txtFolio').val();
    $('#lstProductos').load('actualizar_Lista.php?id='+id);
});
