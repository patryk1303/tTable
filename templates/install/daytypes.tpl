{extends 'index.tpl'}
{block name="content"}
    
    <h1>Typy dni</h1>
    Dodane typy dni:
    <div id="result">
        <img alt="loading" src="{baseUrl}/img/ajax-loader.gif">
    </div>
    <a href="{siteUrl url='/install/lines'}" class="btn btn-info">kolejny krok - linie</a>
    
{/block}

{block name="scripts"}
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