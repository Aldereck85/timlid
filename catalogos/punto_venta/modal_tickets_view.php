<div class="modal fade" id="modal_tickets_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="../../img/punto_venta/listar_tickets_blanco.svg" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Tickets</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <input type="hidden" id="txtLastTicket"> 
        <div class="table-responsive">
          <table class="table" id="tblTicketsView" style="width:100%">
            <thead>
              <tr>
                <th></th>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Estatus</th>
                <th>Factura</th>
                <th></th>
                
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>