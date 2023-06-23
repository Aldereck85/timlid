<?php

  function crearNegocio($id, $negocio){
    $html =  '
        <div id="drag_'.$id.'" class="position-relative negocio_parent">
          <div class="negocio border rounded-bottom p-2 w-full '.$negocio['prioridad'].'-prioridad">
            <h6 class="text-center mb-0" data-toggle="tooltip" data-placement="top" title="'.$negocio['prioridad'].' prioridad">'.$negocio['nombre'].'</h6>
            <div><span class="text-sm font-weight-bold">Nombre del cliente / lead</span></div>
            <span class="text-sm valor">$ '.number_format($negocio['valor'],2,'.',',').'</span>
            <div class="d-flex justify-content-center mt-1">
              <button type="button" class="btn btn-xs mx-2 rounded-full btn-anterior" data-toggle="tooltip" title="Anterior" onclick="anterior(event)">
                <i class="fas fa-arrow-left fa-xs text-info"></i>
              </button>
              <a href="../../catalogos/contactos/" class="btn btn-xs mx-2 rounded-full" data-toggle="tooltip" title="Contactos">
                <i class="far fa-address-card fa-xs text-success"></i>
              </a>';
    if($negocio['cliente'] ?? false){
      $html .= '<a href="../../catalogos/clientes/catalogos/clientes/editar_cliente?c=11" class="btn btn-xs mx-2 rounded-full" data-toggle="tooltip" title="Cliente">
                  <i class="far fa-folder-open fa-xs text-primary"></i>
                </a>';
    }
    else{
      $html .= '<a href="../../catalogos/contactos/" class="btn btn-xs mx-2 rounded-full" data-toggle="tooltip" title="Lead">
                  <i class="far fa-bookmark fa-xs text-primary"></i>
                </a>';
    }
    $html .= '<button type="button" class="btn btn-xs mx-2 rounded-full btn-siguiente" data-toggle="tooltip" title="Siguiente" onclick="siguiente(event)">
                <i class="fas fa-arrow-right fa-xs text-info"></i>
              </button>
            </div>
          </div>
        </div>';

    return $html;
  }

  function crearEtapa($id, $etapa){
    $html = '
      <div class="card mt-2 mt-lg-0 border etapas" data-id="'.$id.'">
        <div class="card-header">
          '.$etapa['nombre'].'
        </div>
        <div class="card-body space-y-1">';
          for($i= 0; $i< count($etapa['negocios']); $i++){
            $html .= crearNegocio($id.'_'.$i, $etapa['negocios'][$i]);
          }
      $html .= '</div>
        <div class="card-footer">
        Total: $ <span class="total">0.00</span>
        </div>
      </div>';

      return $html;
  }
?>