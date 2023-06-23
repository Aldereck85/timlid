<?php
    require_once 'clases.php';
    $ticket_size = 270;

    $get_data = new get_data();
    $get_print = new get_print();

    $data_enterpise = $get_data->getDataEnterprise();
    $client = $get_data->getDataClient($value);
    $data_products = $get_data->getProductsTicket($value,"","");
    $data_cashRegister = $get_data->getDataTicket($value,"","");
    
    $path = $_ENV['RUTA_ARCHIVOS_READ'] . $data_enterpise[0]->PKEmpresa . "/fiscales/";
    $path_logo = $path . $data_enterpise[0]->logo;

    $num_ext = $data_enterpise[0]->numero_interior !== null && $data_enterpise[0]->numero_interior !== "" ? $data_enterpise[0]->numero_interior : "";

    $domi_fiscal = 
    $data_enterpise[0]->calle !== null && $data_enterpise[0]->calle !== "" ?
        $data_enterpise[0]->calle .
        $data_enterpise[0]->numero_exterior .
        $num_ext .
        $data_enterpise[0]->codigo_postal . "<br>" .
        $data_enterpise[0]->colonia .
        $data_enterpise[0]->ciudad . 
        $data_enterpise[0]->Estado . "<br>" .
        $data_enterpise[0]->telefono : "";
    
    $dom_fiscal = $data_enterpise[0]->domicilio_fiscal !== null && $data_enterpise[0]->domicilio_fiscal !== "" ? $data_enterpise[0]->domicilio_fiscal : $domi_fiscal;

    $products = [];
    $descuento = 0;
    $precio_total = 0;

    foreach($data_products as $r)
    {
        array_push($products,[
            "quantity"=>$r->cantidad,
            "name"=>$r->nombre,
            "price"=>$r->subtotal
        ]);
        $descuento += $r->descuento;
        $precio_total += $r->total;
    }

    $data_tax = $get_print->getFormatTicketTax($value,$date,$date1);
    $impuestos_aux = "";
    for ($i=0; $i < count($data_tax); $i++) { 
        
        $impuestos_aux .= $data_tax[$i] . "<br>";
    }

    $impuestos = ($impuestos_aux !== "" && $impuestos_aux !== null) ? $impuestos_aux : "$0.00";
    $descuento_can = ($precio_total * ($descuento / 100));
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Timlid | Ticket</title>
        <style>
            * {
                font-size: 12px;
                font-family: 'DejaVu Sans',serif;
            }
            @page { margin-top: 0px; margin-bottom: 0px; }
            h1 {
                font-size: 18px;
            }

            h2 {
                font-size: 12px;
            }

            h3 {
                font-size: 11px;
            }

            .ticket {
                margin: 2px;
            }

            td,
            th,
            tr,
            table {
                border-collapse: collapse;
                margin: 0 auto;
            }

            td.quantity {
                width: 20%;
                font-size: 9px;
                text-align: left;
            }

            th.quantity {
                
                text-align: left;
            }

            td.price {
                width: 20%;
                text-align: right;
                font-size: 9px;
            }

            td.product {
                width: 60%;
                text-align: justify;
                font-size: 9px;
            }

            th {
                text-align: center;
            }

            .center_item {
                text-align:center;
                align-content: center;
            }

            .ticket {
                width: <?= $ticket_size; ?>px;
                max-width: <?= $ticket_size; ?>px;
            }

            img {
                max-width: 10%;
                width: 10%;
            }

            * {
                margin: 0;
                padding: 0;
            }

            .ticket {
                margin: 0;
                padding: 0;
            }
            hr {
                margin-top: 10px;
                margin-bottom: 10px;
                border: 1px solid;
                border-radius: 5px;
            }

            body {
                text-align: center;
            }

            table {
                width: 95%;
            }
            th,tr {
                width: 30%;
                
            }
            .border-bottom-table{
                
                border-bottom: 1px solid;
            }
        </style>
    </head>
    <body>
        <div class="ticket center_item">
            
            <h2 style="font-weight: normal;"><?= strtoupper($data_enterpise[0]->RazonSocial);?></h2>
            <h3 style="font-weight: normal;"><?= strtoupper($dom_fiscal);?></h3>
            <h3 style="font-weight: normal;">Folio: <?=$data_cashRegister[0]->folio;?></h3>
            <hr>
            <h2>DATOS DEL CLIENTE:</h2>
            <h3><?= strtoupper($client[0]->razon_social);?></h3>
            <h3>RFC: <?= strtoupper($client[0]->rfc);?></h3>
            <h3>C.P.: <?=$client[0]->codigo_postal?></h3>
            
            <hr>

            <table>
                <thead>
                    <tr class="center_item">
                        <th class="quantity">CANT</th>
                        <th class="producto">DESC</th>
                        <th class="precio">IMPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count = count($products);
                        $rowCount = 0;
                        foreach($products as $r){
                            if($rowCount < $count-1){
                    ?>
                        <tr class="border-bottom-table">
                        <?php } else { ?> <tr> <?php }?>
                        
                        <td class="quantity"><?=number_format((double)$r['quantity'],2);?></td>
                        <td class="product"><?=strtoupper($r['name']);?></td>
                        <td class="price">$<?=number_format((double)$r['price'],2);?></td>
                    </tr>
                    
                    <?php $rowCount++;} ?>
                </tbody>
            </table>
            <hr>
            <table width=100%>
                <tr>                       
                    <tr>
                        <td width="75"></td>
                        <td  style="text-align:left;font-size:9px;">Subtotal</td>
                        <td style="text-align:right;font-size:9px;">$<?=number_format($data_cashRegister[0]->subtotal,2)?></td>
                    </tr>
                    
                    <tr>
                        <td ></td>
                        <td style="text-align: left;font-size:9px;">Impuestos</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td  style="text-align: right;font-size:9px;"><?=$impuestos;?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: left;font-size:9px;">Descuento: <?=$descuento;?>%</td>
                        <td style="text-align: right;font-size:9px;">$<?=number_format($descuento_can,2);?></td>
                    </tr>
                    <tr><td style="padding:5px"></td></tr>
                    <tr>
                        <td></td>
                        <td style="text-align: left;font-size:12px; font-weight:bold;">Total</td>
                        <td style="text-align: right;font-size:12px;font-weight:bold;">$<?=number_format($data_cashRegister[0]->total,2);?></td>
                    </tr>
                        
                </tr>
            </table>
            <br><br><br>
            <h2>By Timlid</h2>
        </div>
        <br><br><br><br>
        
    </body>
</html>