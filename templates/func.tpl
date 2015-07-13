{function name=stopname name1="" name2="" req="" write_req=true pull=true}
    {$name1}
    {if $name2|strlen > 0} / {$name2} {/if}
    {if $write_req}
        {if $req} <span class="{if $pull}pull-right {/if}red"><i class="fa fa-hand-o-up"></i></span> {/if}
    {/if}
{/function}

{function name=check_minute hour="" minute=""}
	{$status = ""}
	{$d_time = $hour|cat:":"|cat:$minute|strtotime}
	{if $current_time > $d_time}
		{$status|cat:" passed"}
	{elseif $next_hour_time > $d_time}
		{$status|cat:" next"}
	{/if}
	{$status}
{/function}