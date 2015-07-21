<?php

$app->group('/departures', function() use ($app) {
    
    $app->get('/', function() use ($app) {
        $app->redirect('../');
    });

    $app->get('/list/:line/:direction', function($line,$direction) use ($app) {
        require_once dirname(__FILE__).'/../lib/mpdf/mpdf.php';
        $data = get_list_departures($line,$direction);

        // ob_start();

        // $app->response->headers->set('Content-Type', 'application/pdf');
        $app->render("departures_list.tpl", array(
            "daytypes" => $data["daytypes"],
            "numbers" => $data["numbers"],
            "route" => $data["route"],
            "signs" => $data["signs"],
            "deps" => $data["departures"],
            "counts" => $data["counts"]
        ));

        // $html = ob_get_contents();
        // ob_end_clean();

        // $pdf = new mPDF('', 'A4-L', '', '', 5, 5, 15, 15);
        // $pdf->keep_table_proportions = true;
        // $pdf->WriteHTML($html);
        // $pdf->Output('odjazdy.pdf','I');

    });
    
    $app->get('/:line/:direction/:stop', function($line,$direction,$stop) use ($app) {        
        $departures = get_departures($line, $direction, $stop);
        
        $app->render("departures.tpl",array(
            "line" => $line,
            "dir_name" => $departures["direction_name"],
            "dir_no" => $direction,
            "stop_id" => $stop,
            "stop_name" => get_stop_name($stop),
            "signs" => $departures["signs"],
            "route" => $departures["route"],
            "departures" => $departures["departures"],
            "other_lines" => $departures["other_lines"],
            "line_date" => $departures["line_date"],
            "hours" => $departures["hours"]
        ));
    });
    
    $app->get('/:param+', function() use ($app) {
        $app->redirect('../');
    });
    
});
