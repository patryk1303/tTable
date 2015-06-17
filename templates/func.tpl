{function name=stopname name1="" name2="" req="" write_req=true pull=true}
    {$name1}
    {if $name2|strlen > 0} / {$name2} {/if}
    {if $write_req}
        {if $req} <span class="{if $pull}pull-right {/if}red"><i class="glyphicon glyphicon-hand-up"></i></span> {/if}
    {/if}
{/function}