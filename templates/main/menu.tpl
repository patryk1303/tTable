<nav class="navbar navbar-inverse navbar-blue navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=".">rJazdy</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                {foreach $lines as $line}
                    <li><a href="{siteUrl url='/line/'}{$line.line}">{$line.line}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>
</nav>