{extends file='index.tpl'}
{block name="content"}
   
    <div class="page-header">
        <h1>{$lang.dirs_and_stops} {$line}</h1>
    </div>
    
    <div class="row">
    {foreach $data as $row name=directions name=directions}
        
        <div class="col-sm-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>{$row.name}</h2>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                    {foreach $row.stops as $stop}
                        <li>
                            <a href="{siteUrl url='/departures/'}{$line}/{$stop.dirnuber}/{$stop.stopid}">
                                {call stopname name1=$stop.name1 name2=$stop.name2 req=$stop.req}
                            </a>
                        </li>
                    {/foreach}
                    </ul>
                </div>
            </div>
        </div>
                    
        {if $smarty.foreach.directions.index+1 % 2 == 0}
            </div>
            <div class="row">
        {/if}

        
    {/foreach}
    </div>
    
{/block}