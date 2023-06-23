$(document).ready(function(){
    /*Tabs*/
    $('#administrador-tab').on('click',function(){
        $('.rdbHeader').each(function(){
            this.disabled = true;
        });

        $('.chkRh').each(function(){
            this.disabled = true;
            this.checked = true;
        });

        $('#rdoControlTotalRh').prop('checked', true);
    });

    $('#rh-tab').on('click',function(){
        $('.rdbHeader').each(function(){
            this.disabled = true;
        });

        $('.chkRh').each(function(){
            this.disabled = true;
            this.checked = true;
        });

        $('#rdoControlTotalRh').prop('checked', true);
    });

    $('#personalizado-tab').on('click',function(){
        $('.rdbHeader').each(function(){
            this.disabled = false;
        });
    });
    /* radio buttons */
    $('#rdoControlTotalRh').on('click',function(){
      if(this.checked){
          $('.chkRh').each(function(){
              this.disabled = true;
              this.checked = true;
          });
      }
    });

    $('#rdoNoEliminarRh').on('click',function(){
      if(this.checked){
          $('.chkRh').each(function(){
              this.disabled = true;
              this.checked = true;
          });

          $('.chkRhEliminar').each(function(){
              this.checked = false;
          });

          $('.chkEncabezado').each(function(){
              this.checked = false;
          });
      }
    });

    $('#rdoVerRh').on('click',function(){
      if(this.checked){
          $('.chkRh').each(function(){
              this.disabled = true;
              this.checked = false;
          });

          $('.chkRhVer').each(function(){
              this.checked = true;
          });
      }
    });

    $('#rdoSinPermisosRh').on('click',function(){
      if(this.checked){
          $('.chkRh').each(function(){
              this.disabled = true;
              this.checked = false;
          });
      }
    });

    $('#rdoPersonalizadoRh').on('click',function(){
      if(this.checked){
          $('.chkRh').each(function(){
              this.disabled = false;
          });

          $('.chkRh').each(function(){
              this.checked = true;
          });
      }
    });

    /* Check box*/
    /*$('.chkRh').on('click',function(){
        $('.chkEncabezado').each(function(){
            this.checked = false;
        });
    });*/

    $('#chkTodoEmpleados').on('click',function(){
      if(this.checked){
          $('.chkEmpleados').each(function(){
              this.checked = true;
          });
      }else{
           $('.chkEmpleados').each(function(){
              this.checked = false;
          });
      }
    });

    $('#chkTodoUsuarios').on('click',function(){
      if(this.checked){
          $('.chkUsuarios').each(function(){
              this.checked = true;
          });
      }else{
           $('.chkUsuarios').each(function(){
              this.checked = false;
          });
      }
    });

    $('#chkTodoNomina').on('click',function(){
      if(this.checked){
          $('.chkNomina').each(function(){
              this.checked = true;
          });
      }else{
           $('.chkNomina').each(function(){
              this.checked = false;
          });
      }
    });

    $('#chkTodoTurnos').on('click',function(){
      if(this.checked){
          $('.chkTurnos').each(function(){
              this.checked = true;
          });
      }else{
           $('.chkTurnos').each(function(){
              this.checked = false;
          });
      }
    });

    $('#chkTodoPuestos').on('click',function(){
      if(this.checked){
          $('.chkPuestos').each(function(){
              this.checked = true;
          });
      }else{
           $('.chkPuestos').each(function(){
              this.checked = false;
          });
      }
    });

    $('#chkTodoSucursales').on('click',function(){
      if(this.checked){
          $('.chkSucursales').each(function(){
              this.checked = true;
          });
      }else{
           $('.chkSucursales').each(function(){
              this.checked = false;
          });
      }
    });
  });
