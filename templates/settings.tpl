{extends file='index.tpl'}
{block name="content"}
   
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title">{$lang.settings}</h1>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>{$lang.lang}</label>
                <div class="btn-group" role="group">
                    {foreach $languages as $language}
                        <button type="button"
                            class="btn btn-lang btn-default {if $language == $smarty.cookies.lang}btn-success{/if}"
                            data-lang="{$language}">
                            {$language}
                        </button>
                    {/foreach}
                </div>
            </div>

            <div class="form-group">
                <label>{$lang.style}</label>
                <div class="btn-group" role="group">
                    {foreach $styles as $style}
                        <button type="button"
                            class="btn btn-style btn-default {if $style == $smarty.cookies.style}btn-success{/if}"
                            data-style="{$style}">
                            {$style}
                        </button>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
    
{/block}

{block name="scripts"}
    <script>
    $(document).ready(function() {
        
    });
    </script>
{/block}