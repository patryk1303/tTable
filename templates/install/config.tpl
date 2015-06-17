{extends 'index.tpl'}
{block name="content"}
    
    <h1>Ustawienia</h1>
    <form action="{siteUrl url='/install/daytypes'}" method="POST">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ustawienia połączenia z bazą danych</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr class="form-group">
                        <td>
                            <label for="db_host">Adres serwera</label>
                        </td>
                        <td>
                            <input clas="form-control" id="db_host" name="db_host">
                        </td>
                    </tr>
                    <tr class="form-group">
                        <td>
                            <label for="db_user">Użytkownik bazy danych</label>
                        </td>
                        <td>
                            <input clas="form-control" id="db_user" name="db_user">
                        </td>
                    </tr>
                    <tr class="form-group">
                        <td>
                            <label for="db_pass">Hasło bazy danych</label>
                        </td>
                        <td>
                            <input clas="form-control" id="db_pass" name="db_pass">
                        </td>
                    </tr>
                    <tr class="form-group">
                        <td>
                            <label for="db_name">Nazwa bazy danych</label>
                        </td>
                        <td>
                            <input clas="form-control" id="db_name" name="db_name">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel-footer">
                <button class="btn btn-default" onclick="checkDB()" type="button">
                    Sprawdź połączenie z bazą danych
                </button>
                <img alt="connection status" id="connStat" src="">
            </div>
        </div>
        
        <button class="btn btn-info" id="btnNext" disabled="disabled">kolejny krok - typy dni</button>
    </form>
    
    <div class="row">
       <ol class="breadcrumb">
           <li>strona główna</li>
           <li class="active">ustawienia</li>
           <li>typy dni</li>
           <li>przystanki</li>
           <li>linie, kierunki, oznaczenia, trasy, odjazdy</li>
           <li>koniec</li>
       </ol>
   </div>
{/block}

{block name="scripts"}
<link rel="stylesheet" href="{siteUrl url='/css/install.css'}">
<style>
    #connStat { width: 32px; height: 32px;  }
</style>
<script>
    var $connStat = $('#connStat');
    
    $(document).ready(function() {
        $connStat.hide();
    });
    
    function checkDB() {
        var db_host = $('#db_host').val(),
            db_user = $('#db_user').val(),
            db_pass = $('#db_pass').val(),
            db_base = $('#db_base').val();
    
       $connStat.show();
       $connStat.attr('src', '{baseUrl}/img/ajax-loader_2.gif');
    }
</script>
{/block}