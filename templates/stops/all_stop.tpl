{extends file='index.tpl'}
{block name="content"}
    {$name = departures_style_|cat:$style}
    {foreach $departures as $departure}
   		{call name=$name
        line=$departure.line stop_name=$stop_name stop_id=$stop_id dir_name=$departure.direction_name hours=$departure.hours
        departures=$departure.departures signs=$departure.signs line_date=$departure.line_date other_lines=""
        dir_no=$departure.dir_no route=$departure.route print_button=false show_other_lines=false}
        <hr class="page-break">
    {/foreach}
    {include file='common/_trip_modal.tpl'}
{/block}

{block name="scripts"}
    <script src="{baseUrl}/js/trip_modal.js"></script>
{/block}