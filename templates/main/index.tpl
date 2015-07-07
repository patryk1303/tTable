{extends file='index.tpl'}
{block name="content"}
<div class="jumbotron">
    <h1>tTable</h1>
    <p class="lead">Rozkład jazdy komunikacji miejskiej.</p>
    <p>
        <a href="#" class="btn btn-default lines-show">
            <i class="fa fa-location-arrow"></i> Linie
        </a>
        <a href="{siteUrl url='/stops'}" class="btn btn-default">
            <i class="fa fa-spoon"></i> Przystanki
        </a>
    </p>
</div>
{/block}