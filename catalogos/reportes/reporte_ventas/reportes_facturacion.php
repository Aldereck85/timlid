<div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="nav-main-tab">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div id="tabla">
                    <!-- Filtros -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="cmbCliente">Cliente:</label>
                                <select name="cmbCliente" id="cmbCliente" class="form-select" required></select>
                                <div class="invalid-feedback" id="invalid-cmbCliente">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="cmbVendedor">Vendedor:</label>
                                <select name="cmbVendedor" id="cmbVendedor" class="form-select" required></select>
                                <div class="invalid-feedback" id="invalid-cmbVendedor">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="cmbEstado">Estado:</label>
                                <select name="cmbEstado" id="cmbEstado" class="form-select" required></select>
                                <div class="invalid-feedback" id="invalid-cmbEstado">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="cmbProductos">Productos:</label>
                                <select name="cmbProductos" id="cmbProductos" class="form-select" required></select>
                                <div class="invalid-feedback" id="invalid-cmbProductos">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="cmbMarcas">Marcas:</label>
                                <select name="cmbMarcas" id="cmbMarcas" class="form-select" required></select>
                                <div class="invalid-feedback" id="invalid-cmbMarcas">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6 " style="margin-left:20px;">
                                <label for="txtDateFrom">De:</label>
                                <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom">
                                <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="txtDateTo">Hasta:</label>
                                <input class="form-control" type="date" name="txtDateTo" id="txtDateTo">
                                <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                            </div>
                            <div id="container-buttons" class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <button data-toggle="tooltip" data-placement="top" title="Aplicar Filtro" disabled="true" class="btn-custom btn-custom--blue" id="btnFiltertable" style="margin-top: 10px!important">Filtrar</button>
                            </div>
                        </div>
                    </div>
                    <BR></BR>
                    <table class="table" id="tblreport" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Folio</th>
                                <th>Estado</th>
                                <th>Cliente</th>
                                <th>Asesor</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>