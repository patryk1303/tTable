{extends 'index.tpl'}
{block name="content"}
    
    <div class="jumbotron">
        <h1>Instalator <strong>rJazdy</strong></h1>
        <p>Witaj w instalatorze <strong>rJazdy</strong>.</p>
        <p>
            Przed przystąpieniem do instalacji, upewnij się, czy pliki rozkładu jazdy znajdują się<br>
            w katalogu <em>/timetable</em> oraz są zgodne ze standardem.
        </p>
        <p>
            <a href="{siteUrl url='/install/daytypes'}" class="btn btn-info">przejdź dalej</a>
        </p>
    </div>
        
   <div class="row">
       <ol class="breadcrumb">
           <li class="active">strona główna</li>
           <li>typy dni</li>
           <li>przystanki</li>
           <li>linie, kierunki, oznaczenia, trasy, odjazdy</li>
{*           <li>oznaczenia</li>*}
{*           <li>trasy</li>*}
{*           <li>odjazdy</li>*}
           <li>koniec</li>
       </ol>
   </div>
    
{/block}

{block name="scripts"}
<link rel="stylesheet" href="{siteUrl url='/css/install.css'}">
{/block}