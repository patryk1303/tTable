{include file='func.tpl'}
{foreach $trip as $trip_row}
    <div class="{if $trip_row.stopid == $stop_id}current {/if}row">
        <div class="col-xs-1">
            {$trip_row.hour}:{$trip_row.min}
        </div>
        <div class="col-xs-11">
            {stopname name1=$trip_row.name1 name2=$trip_row.name2 req=$trip_row.req pull=false}
        </div>
    </div>
{/foreach}