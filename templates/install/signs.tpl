{extends 'index.tpl'}
{block name="content"}
    
    <h1>Oznaczenia</h1>
    Dodane oznaczenia:
    <div id="result">
        <img alt="loading" src="{baseUrl}/img/ajax-loader.gif">
    </div>
    <a href="{siteUrl url='/install/signs'}" class="btn btn-info">kolejny krok - trasy przejazdów linii</a>
    
    <div class="row">
       <ol class="breadcrumb">
           <li>strona główna</li>
           <li>typy dni</li>
           <li>linie</li>
           <li>przystanki</li>
{*           <li class="active">oznaczenia</li>*}
           <li>trasy</li>
           <li>odjazdy</li>
           <li>koniec</li>
       </ol>
   </div>
{/block}

{block name="scripts"}
<link rel="stylesheet" href="{siteUrl url='/css/install.css'}">
<style>
    #result {
        max-height: 400px;
        overflow-y: auto;
        margin: 10px 0;
        border: 1px solid white;
    }
</style>
<script>
    $.ajax({
        url: '{siteUrl url='/install/api/signs'}',
        method: 'GET'
    })
    .done(function(response) {
        var stops = JSON.parse(response),
            html = '';
        html += '<ul>';
        {*for(var i=0,len=stops.length;i<len;++i) {
            html += '<li> (' + stops[i].id + ') ' + stops[i].name + '</li>';
        }*}
        html += '</ul>';
        
        $('#result').html(html);
    });
</script>
{/block}