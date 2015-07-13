<!-- begin lines modal -->
<div class="modal fade" id="linesModal" tabindex="-1"
    role="dialog" aria-labelledby="linesModalLabel">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal"
                   aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
               <h4 class="modal-title" id="linesModalLabel">
                   {$lang.lines}
               </h4>
           </div>
           <div class="modal-body" id="linesModalBody">
               <ul class="nav nav-pills">
               {foreach $lines as $line}
                    {if isset($line.line)}
                        <li><a href="{siteUrl url='/line/'}{$line.line}">{$line.line}</a></li>
                    {/if}
               {/foreach}
               </ul>
           </div>
       </div>
   </div>
</div>
<!-- end lines modal -->