$(document).on('click','#btnAgregar',function(){
  if($('#txtTipoEntrada').val() !== ""){

  }else{
    Swal.fire({
      icon: 'error',
      title: 'Campo obligatorio.',
      text: 'El tipo de entrada es un dato obligatorio.',

    });
    //alert('El tipo de entrada es un dato obligatorio.');
  }
});
