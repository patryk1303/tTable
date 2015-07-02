{extends file='index.tpl'}
{block name="content"}
    {call departures_style_1
    line=$line stop_name=$stop_name stop_id=$stop_id dir_name=$dir_name
    departures=$departures signs=$signs line_date=$line_date other_lines=$other_lines
    route=$route print_button=true}
    {include file='common/_trip_modal.tpl'}
{/block}

{block name="scripts"}
    <script src="{baseUrl}/js/trip_modal.js"></script>
{/block}