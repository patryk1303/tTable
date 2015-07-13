{extends file='index.tpl'}
{block name="content"}
<div class="jumbotron">
    <h1>tTable</h1>
    <p class="lead">{$lang.intro_text}</p>
    <p>
        <a href="#" class="btn btn-default lines-show">
            <i class="fa fa-location-arrow"></i> {$lang.lines}
        </a>
        <a href="{siteUrl url='/stops'}" class="btn btn-default">
            <i class="fa fa-spoon"></i> {$lang.stops}
        </a>
    </p>
</div>
{/block}