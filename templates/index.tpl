<!doctype html>
<html>
    <head>
        <title>rJazdy</title>
        <link rel="stylesheet" href="{baseUrl}/css/main.css">
        <link rel="stylesheet" href="{baseUrl}/css/bootstrap.css">
        <link rel="stylesheet" href="{baseUrl}/css/bootstrap-theme.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
    </head>
    
    <body>
       
        {include "main/menu.tpl"}
        
        <div class="container">
        {block "content"}
        
        {/block}
        
        <script src="{baseUrl}/js/lib/jquery.js"></script>
        <script src="{baseUrl}/js/lib/bootstrap.js"></script>
        
        {block "scripts"}
            
        {/block}
        
            <footer id="footer">
                &copy; 2015 Patryk Wychowaniec
            </footer>
        
        </div>
        
        
    </body>
</html>
