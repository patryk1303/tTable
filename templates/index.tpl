{include file='func.tpl'}
<!doctype html>
<html>
    <head>
        <title>rJazdy</title>
{*        <link rel="stylesheet" href="{baseUrl}/css/temp.css">*}
        <link rel="stylesheet" href="{baseUrl}/css/main.css">
{*        <link rel="stylesheet" href="{baseUrl}/css/bootstrap.css">*}
        <link rel="stylesheet" href="{baseUrl}/css/lavish-bootstrap.css">
{*        <link rel="stylesheet" href="{baseUrl}/css/bootstrap-theme.css">*}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
    </head>
    
    <body>
       
        {include "main/menu.tpl"}
        
        <div class="container">
        {block "content"}
        
        {/block}
        
        <script>
            var URL = '{baseUrl}';
        </script>
        <script src="{baseUrl}/js/lib/jquery.js"></script>
        <script src="{baseUrl}/js/lib/bootstrap.js"></script>
        <script src="{baseUrl}/js/main.js"></script>
        
        {block "scripts"}
            
        {/block}
        
            <footer id="footer" class="hidden-print">
                &copy; 2015 Patryk Wychowaniec<br>
                Stylizacje: <a href="https://github.com/ksiazkowicz">Maciej Janiszewski</a>
            </footer>
        
        </div>
    </body>
</html>