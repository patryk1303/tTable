{extends 'index.tpl'}
{block name="content"}
    
    <h1>Typy dni</h1>
    Dodane typy dni:
    <div id="result">
        <img alt="loading" src="{baseUrl}/img/ajax-loader.gif">
    </div>
    <a href="{siteUrl url='/install/stops'}" class="btn btn-info">kolejny krok - przystanki</a>
    
    <div class="row">
       <ol class="breadcrumb">
           <li>strona główna</li>
           <li>ustawienia</li>
           <li class="active">typy dni</li>
           <li>przystanki</li>
           <li>linie, kierunki, oznaczenia, trasy, odjazdy</li>
           <li>koniec</li>
       </ol>
   </div>
{/block}

{block name="scripts"}
<link rel="stylesheet" href="{siteUrl url='/css/install.css'}">
<script>
    $.ajax({
        url: '{siteUrl url='/install/api/daytypes'}',
        method: 'GET'
    })
    .done(function(response) {
        var daytypes = JSON.parse(response),
            html = '';
        html += '<ul>';
        for(var i=0,len=daytypes.length;i<len;++i) {
            html += '<li>' + daytypes[i].name + '</li>';
        }
        html += '</ul>';
        
        $('#result').html(html);
    });
</script>
{/block}