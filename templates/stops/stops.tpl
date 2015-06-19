{extends file='index.tpl'}

{block name="content"}
    
{*    {$lines_stops|print_r}*}
    
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Przystanki</h2>
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills nav-stacked">
                    {foreach $stops as $stop name=stops}
                        <li>
                            <a href="{siteUrl url='/stops/'}{$stop.id}">
                            {call stopname name1=$stop.name1 name2=$stop.name2 req=$stop.req pull=false}
                            <span class="pull-right red margin-right-5">
                                {$lines_stops[$smarty.foreach.stops.index][0].lines}
                            </span>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>
{/block}

{block name="scripts"}
    
{/block}