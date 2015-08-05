{extends file='index.tpl'}
{block name="content"}
	{$name = departures_style_|cat:$style}
    {call name=$name
    line=$line stop_name=$stop_name stop_id=$stop_id dir_name=$dir_name dir_no=$dir_no
    departures=$departures signs=$signs line_date=$line_date other_lines=$other_lines hours=$hours
    route=$route print_button=true}
    {include file='common/_trip_modal.tpl'}
{/block}

{block name="scripts"}
    <script src="{baseUrl}/js/trip_modal.js"></script>
{/block}