{extends 'index.tpl'}
{block name="content"}
    
    <div class="jumbotron">
        <h1>Gratulujemy</h1>
        <p>Własnie udało się zainstalować rozkłady jazdy!</p>
        <p>
            Jeżeli instalacja przebiegła pomyślnie, rozkład jazdy powinien juz działać.<br>
            Jeżeli nie, popraw pliki i <a href="{siteUrl url='/install'}">spróbuj ponownie</a>
        </p>
        <p>
            <a href="{baseUrl}" class="btn btn-info">przejdź do strony głównej</a>
        </p>
    </div>
        
   <div class="row">
       <ol class="breadcrumb">
           <li>strona główna</li>
           <li>ustawienia</li>
           <li>typy dni</li>
           <li>przystanki</li>
           <li>linie, kierunki, oznaczenia, trasy, odjazdy</li>
           <li class="active">koniec</li>
       </ol>
   </div>
    
{/block}

{block name="scripts"}
<link rel="stylesheet" href="{siteUrl url='/css/install.css'}">
{/block}