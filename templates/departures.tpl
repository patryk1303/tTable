{extends file='index.tpl'}
{block name="content"}
    <div class="row departure-header">
        <div class="alert alert-info small-padding alert-print">
            <div class="col-sm-5 separator">
                <h1 class="line">
                    <big>{$line}</big>
                    {if $stop_name.req}
                        <br><small>na żądanie</small>
                    {/if}
                </h1>
            </div>

            <div class="col-sm-7">
                <h2>
                    {call stopname name1=$stop_name.name1 name2=$stop_name.name2 write_req=false}
                </h2>
                <span class="margin-left-30">
                    <i class="glyphicon glyphicon-circle-arrow-right"></i>&nbsp;{$dir_name}
                </span>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-8">
        {* place for departures & signs *}
            <div class="row">
            {* departures panels *}
            {foreach $departures as $departure_day}
                {if $departure_day.count}
                <div class="panel panel-primary panel-day-{$departure_day.daytype_number} panel-small-padding">
                    <div class="panel-heading">
                        <h4>{$departure_day.daytype}</h4>
                    </div> 
                    <div class="panel-body">
                    {foreach $departure_day.departures as $hour}
                        {if $hour.minutes}
                        <div class="hour_block">
                            {$hour.hour}
                            <sup>
                            {foreach $hour.minutes as $minute}
                                <a data-daytype-id="{$departure_day.daytype_number}"
                                   data-trip-no="{$minute.tripnumber}" class="trip-show"
                                   data-line="{$line}"
                                   data-dir-no="{$dir_no}"
                                   data-stop-id="{$stop_id}">
{*                                    href="{siteUrl url='/trip/'}{$line}/{$dir_no}/{$stop_id}/{$departure_day.daytype_number}/{$minute.tripnumber}">*}
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
                <div class="panel panel-warning panel-small-padding">
                    <div class="panel-heading">
                        <h4>Oznaczenia</h4>
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
            <div class="row">
                <div class="col-xs-12 align-right">
                    Rozkład jazdy obowiązuje od {$line_date}
                </div>
            </div>
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
                <div class="col-xs-3">
                    <button class="btn btn-primary hidden-print" id="buttonPrint">
                        <i class="glyphicon glyphicon-print"></i>&nbsp;Drukuj
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            {* place for route *}
            <div class="panel panel-info panel-route panel-small-padding">
                <div class="panel-heading">Trasa przejazdu</div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked nav-concerned">
                        {foreach $route as $stop}
                        <li {if $stop_id == $stop.stopid} class="active" {/if}>
                            <a href="{siteUrl url='/departures/'}{$line}/{$dir_no}/{$stop.stopid}">
                                {call stopname name1=$stop.name1 name2=$stop.name2 req=$stop.req}
                            </a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {include file='common/_trip_modal.tpl'}
{/block}

{block name="scripts"}
    <script src="{baseUrl}/js/trip_modal.js"></script>
{/block}