<!-- begin settings modal -->
<div class="modal fade" id="settingsModal" tabindex="-1"
role="dialog" aria-labelledby="settingsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
        aria-label="Close">
          <span aria-hidden="true">
            &times;
          </span>
        </button>
        <h4 class="modal-title" id="settingsModalLabel">
          {$lang.settings}
        </h4>
      </div>
      <div class="modal-body" id="settingsModalBody">
        <div class="form-group">
          <div class="col-xs-3">
            <label>
              {$lang.lang}
            </label>
          </div>
          <div class="col-xs-9">
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
          <br class="clearfix">
        </div>

        <div class="form-group">
          <div class="col-xs-3">
            <label>
             {$lang.style}
            </label>
          </div>
          <div class="col-xs-9">
            <div class="btn-group" role="group">
              {foreach $styles as $style}
                <button type="button"
                class="btn btn-style btn-default {if $style->number == $smarty.cookies.style}btn-success{/if}"
                data-style="{$style->number}" data-container="body" data-image-url="{baseUrl}/img/styles/{$style->img}">
                  {$style->name}
                </button>
              {/foreach}
            </div>
          </div>
          <br class="clearfix">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end settings modal -->