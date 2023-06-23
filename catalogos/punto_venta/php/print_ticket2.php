<?php
    include_once "../../../vendor/autoload.php";
    use Dompdf\Dompdf;

    try{
        $dompdf = new Dompdf();
        $dompdf->setPaper(array( 0 , 0 , 226.77 , 226.77 ), 'portrait');

        $GLOBALS['bodyHeight'] = 0;

        ob_start();
        $value = isset($_POST['value']) ? $_POST['value'] : "";
        $value1 = isset($_POST['value1']) ? $_POST['value1'] : "";
        $value2 = isset($_POST['value2']) ? $_POST['value2'] : "";
        $date = isset($_POST['date']) ? $_POST['date'] : "";
        $date1 = isset($_POST['date1']) ? $_POST['date1'] : "";
        $value3 = isset($_POST['value3']) ? $_POST['value3'] : "";
        include "./create_ticket.php";
        $html1 = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->set_paper( array( 0 , 0 , 226.77 , 226.77 ) );
        $dompdf->load_html( $html1 );
        $dompdf->render();
        $page_count = $dompdf->get_canvas( )->get_page_number( );
        unset($dompdf);

        ob_start();
        $value = isset($_POST['value']) ? $_POST['value'] : "";
        $value1 = isset($_POST['value1']) ? $_POST['value1'] : "";
        $value2 = isset($_POST['value2']) ? $_POST['value2'] : "";
        $date = isset($_POST['date']) ? $_POST['date'] : "";
        $date1 = isset($_POST['date1']) ? $_POST['date1'] : "";
        $value3 = isset($_POST['value3']) ? $_POST['value3'] : "";
        include "./create_ticket.php";
        $html2 = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->set_paper( array( 0 , 0 , 226.77 , 226.77 * $page_count + 20 ) );
        $dompdf->load_html( $html2 );
        $dompdf->render();

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=documento.pdf");
        echo $dompdf->output();
    } catch(Exception $e){
        return "Servicio no disponible" . $e->getMessage();
    }
?>