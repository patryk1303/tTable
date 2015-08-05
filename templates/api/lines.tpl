<ul class="nav nav-pills">
{foreach $lines as $line}
     {if isset($line.line)}
         <li><a href="{siteUrl url='/line/'}{$line.line}">{$line.line}</a></li>
     {/if}
{/foreach}
</ul>