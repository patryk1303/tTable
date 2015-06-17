{extends file='index.tpl'}
{block name="content"}
   
    <div class="page-header">
        <h1 class="center">
            <i class="glyphicon glyphicon-arrow-up"></i>&nbsp;{$line}&nbsp;
            <i class="glyphicon glyphicon-chevron-down"></i>&nbsp;
            {stopname name1=$stop_name.name1 name2=$stop_name.name2 req=$stop_name.req pull=false}&nbsp;
            <br>
            <i class="glyphicon glyphicon-circle-arrow-right"></i>&nbsp;{$dir_name}&nbsp;
        </h1>
    </div>
        
    <div class="row">
        <div class="col-sm-8">
        {* place for departures & signs *}
            <div class="row">
            {* departures panels *}
            {foreach $departures as $departure_day}
                {if $departure_day.count}
                <div class="panel panel-primary panel-day-{$departure_day.daytype_number}">
                    <div class="panel-heading">
                        {$departure_day.daytype}
                    </div> 
                    <div class="panel-body">
                    {foreach $departure_day.departures as $hour}
                        {if $hour.minutes}
                        <div class="hour_block">
                            {$hour.hour}
                            <sup>
                            {foreach $hour.minutes as $minute}
                                <a href="{siteUrl url='/trip/'}{$line}/{$dir_no}/{$stop_id}/{$minute.tripnumber}">
                                    {$minute.min}<small>{$minute.signs}</small>
                                </a>
                            {/foreach}
                            </sup>
                        </div>
                        {/if}
                    {/foreach}
                    </div>
                </div>
                {/if}
            {/foreach}
            </div>
            {* signs *}
            {if $signs|count}
            <div class="row">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Oznaczenia
                    </div>
                    <div class="panel-body">
                        <ul>
                        {foreach $signs as $sign}
                            <li>{$sign.sign} - {$sign.description}</li>
                        {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
            {/if}
            {* other lines *}
            {if $other_lines|count > 1}
            <div class="row hidden-print">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Inne linie odjeżdżające z tego przystanku
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked nav-concerned nav-other-lines">
                        {foreach $other_lines as $other_line}
                            {if $other_line.line != $line or $other_line.dirnumber != $dir_no}
                            <li>
                                <a href="{baseUrl}/departures/{$other_line.line}/{$other_line.dirnumber}/{$stop_id}">
                                    Linia <strong class="red">{$other_line.line}</strong>
                                    w kierunku <strong class="red">{$other_line.name}</strong>
                                </a>
                            </li>
                            {/if}
                        {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
            {/if}
            {* print button *}
            <div class="row">
                <button class="btn btn-primary hidden-print" id="buttonPrint">
                    <i class="glyphicon glyphicon-print"></i>&nbsp;Drukuj
                </button>
            </div>
        </div>
        <div class="col-sm-4">
            {* place for route *}
            <div class="panel panel-info panel-route">
                <div class="panel-heading">Trasa przejazdu</div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked nav-concerned">
                        {foreach $route as $stop}
                        <li {if $stop_id == $stop.stopid} class="active" {/if}>
                            <a href="{siteUrl url='/departures/'}{$line}/{$dir_no}/{$stop.stopid}">
                                {stopname name1=$stop.name1 name2=$stop.name2 req=$stop.req}
                            </a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
{/block}