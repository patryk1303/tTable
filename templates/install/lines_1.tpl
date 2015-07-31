{extends 'index.tpl'}
{block name="content"}
    
    <h1>Linie</h1>
    <div id="installing">
        Instalowanie
    </div>
    <hr>
    <div>
        <img alt="loading" id="loader" src="{baseUrl}/img/ajax-loader.gif"><br>
        <a href="{siteUrl url='/install/end'}" id="button-end" class="btn btn-info" style="display: none">kolejny krok - koniec</a>
    </div>
    <hr>
    <div id="result">
        
    </div>
    <hr>
    
    
    <div class="row">
       <ol class="breadcrumb">
           <li>strona główna</li>
           <li>ustawienia</li>
           <li>typy dni</li>
           <li>przystanki</li>
           <li class="active">linie, kierunki, oznaczenia, trasy, odjazdy</li>
           <li>koniec</li>
       </ol>
   </div>
{/block}

{block name="scripts"}
<link rel="stylesheet" href="{siteUrl url='/css/install.css'}">
<script>
    var step = [],
        lines = {$lines|json_encode},
        lines_count = lines.length,
        len = lines.length,
        $steps = $('#steps'),
        $result = $('#result'),
        added_lines = 0,
        done_directions = 0,
        done_signs = 0,
        done_routes = 0,
        done_departures = 0;
    
    function storeLine(line) {
        $.ajax({
            url: '{siteUrl url='/install/api/install/line/'}'+ line,
            method: 'POST',
            beforeSend: function(xhr) {
                $steps.prepend('Dodawanie linii ' + line + '<br>');
            },
            success: function(response) { 
                added_lines++;
                $result.prepend('Dodano linię ' + response + '<br>');
                
                storeDirections(response);
            }
        });
    }
    
    function storeDirections(line) {
        $.ajax({
            url: '{siteUrl url='/install/api/install/directions/'}'+ line,
            method: 'POST',
            beforeSend: function(xhr) {
                $steps.prepend('Przetwarzanie kierunków dla linii ' + line + '<br>');
            },
            success: function(response) { 
                done_directions++;
                $result.prepend('Dodano kierunki linii ' + response + '<br>');
                
                storeSigns(response);
            }
        });
    }
    
    function storeSigns(line) {
        $.ajax({
            url: '{siteUrl url='/install/api/install/signs/'}'+ line,
            method: 'POST',
            beforeSend: function(xhr) {
                $steps.prepend('Przetwarzanie oznaczeń dla linii ' + line + '<br>');
            },
            success: function(response) { 
                done_signs++;
                $result.prepend('Dodano oznaczenia linii ' + response + '<br>');
                
                storeRoutes(response);
            }
        });
    }
    
    function storeRoutes(line) {
        $.ajax({
            url: '{siteUrl url='/install/api/install/routes/'}'+ line,
            method: 'POST',
            beforeSend: function(xhr) {
                $steps.prepend('Przetwarzanie tras dla linii ' + line + '<br>');
            },
            success: function(response) { 
                done_routes++;
                $result.prepend('Dodano trasy linii ' + response + '<br>');
                
                storeDepartures(response);
            }
        });
    }
    
    function storeDepartures(line) {
        $.ajax({
            url: '{siteUrl url='/install/api/install/departures/'}'+ line,
            method: 'POST',
            beforeSend: function(xhr) {
                $steps.prepend('Przetwarzanie odjazdów dla linii ' + line + '<br>');
            },
            success: function(response) { 
                done_departures++;
                $result.prepend('Dodano odjazdy linii ' + response + '<br>');
                
                checkAdd();
            }
        });
    }
    
    function checkAdd() {
        var lines = added_lines === lines_count,
            directions = done_directions === lines_count,
            signs = done_signs === lines_count,
            routes = done_routes === lines_count,
            departures = done_departures === lines_count;
    
        if (lines && directions && signs &&
                routes && departures) {
            $('#loader').hide();
            $('#button-end').show();
        }
    
    }
    
    $(document).ready(function() {
        for(i=0;i<len;i++) {
            var current_line = lines[i];
            
            storeLine(current_line);
        }
    });
    
</script>
{/block}