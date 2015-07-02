{extends file='index.tpl'}

{block name="content"}
    
{*    {$lines_stops|print_r}*}
    
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Przystanki</h2>
            </div>
            <div class="panel-body">
                {foreach $stops as $stop name=stops}
                    <div class="row stops hover">
                        <div class="col-xs-4">
                            {call stopname name1=$stop.name1 name2=$stop.name2 req=$stop.req pull=false}
                        </div>
                        <div class="col-xs-4">
                            <div class="btn-group" role="group" aria-label="stop-{$stop.id}">
                                <a class="btn btn-info" href="{siteUrl url='/stops/chrono/'}{$stop.id}">
                                    Odjazdy chronologicznie
                                </a>
                                <a class="btn btn-info" href="{siteUrl url='/stops/all/'}{$stop.id}">
                                    Tabliczki przystankowe
                                </a>
                            </div>
                        </div>
                        <div class="pull-right red margin-right-5">
                            {$lines_stops[$smarty.foreach.stops.index][0].lines}
                        </div>
                        {*<a href="{siteUrl url='/stops/chrono/'}{$stop.id}">
                        {call stopname name1=$stop.name1 name2=$stop.name2 req=$stop.req pull=false}
                        <span class="pull-right red margin-right-5">
                            {$lines_stops[$smarty.foreach.stops.index][0].lines}
                        </span>
                        </a>*}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
{/block}

{block name="scripts"}
    
{/block}