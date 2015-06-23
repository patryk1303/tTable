{extends file='index.tpl'}

{block name="content"}
    
    <div class="row">
        <div class="alert alert-info small-padding alert-print">
            <h3>
                Odjazdy z przystanku
                <span class="red">{call stopname name1=$stop_name.name1 name2=$stop_name.name2 write_req=false}</span>
                chronologicznie
            </h3>
        </div>
    </div>
    
    {foreach $departures as $departure}
        <div class="row">
            <div class="panel panel-default panel-day-{$departure.daytype_id}">
                <div class="panel-heading panel-heading-collapse">
                    <h4>
                        <a data-toggle="collapse" data-target="#day-{$departure.daytype_id}">{$departure.daytype}</a>
                    </h4>
                </div>
                <div class="panel-collapse collapse" id="day-{$departure.daytype_id}">
                    <div class="panel-body">
                        <table class="table table-condensed table-responsive table-hover">
                            <tr>
                                <th>Linia</th>
                                <th>Kierunek</th>
                                <th>Odjazd</th>
                            </tr>
                            {foreach $departure.departures as $row}
                            <tr class="trip-show" data-daytype-id="{$departure.daytype_id}"
                                data-trip-no="{$row.tripnumber}"
                                data-line="{$row.line}"
                                data-dir-no="{$row.dirnumber}"
                                data-stop-id="{$stop_id}">
                                <td>{$row.line}</td>
                                <td>{$row.directionname}</td>
                                <td>{$row.hour} <sup>{$row.min}</sup></td>
                            </tr>
                            {/foreach}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    {/foreach}
    
    {include file='common/_trip_modal.tpl'}
{/block}

{block name="scripts"}
    <script src="{baseUrl}/js/trip_modal.js"></script>
{/block}