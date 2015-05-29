{extends 'index.tpl'}
{block name="content"}
    
    <h1>Linie</h1>
    Dodane linie i ich kierunki:
    <div id="result">
        <img alt="loading" src="{baseUrl}/img/ajax-loader.gif">
    </div>
    <a href="{siteUrl url='/install/stops'}" class="btn btn-info">kolejny krok - przystanki</a>
    
{/block}

{block name="scripts"}
<script>
    $.ajax({
        url: '{siteUrl url='/install/api/lines'}',
        method: 'GET'
    })
    .done(function(response) {
        var lines = JSON.parse(response),
            html = '';
        html += '<table class="table">';
        html += '<tr>';
        html += '<th>Linia</th>';
        html += '<th>Numer kierunku</th>';
        html += '<th>Nazwa kierunku</th>';
        html += '</tr>';
        for(var i=0,len=lines.length;i<len;++i) {
            for(var j=0,len1=lines[i].directions.length;j<len1;++j) {
                html += '<tr>';
                html += '<td>' + lines[i].line + '</td>';
                html += '<td>' + lines[i].directions[j].dirName + '</td>';
                html += '<td>' + lines[i].directions[j].dirNo + '</td>';
                html += '</tr>';
            }
        }
        html += '</table>';
        
        console.log(html);
        
        $('#result').html(html);
    });
</script>
{/block}