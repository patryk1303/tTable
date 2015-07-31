{extends file='index.tpl'}
{block name="content"}
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3>Admin panel</h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-stacked nav-pills">
				<li>
					<a href="{baseUrl}/admin/announcements">
						Powiadomienia
					</a>
				</li>
			</ul>	
		</div>
	</div>
{/block}

{block name="scripts"}
    
{/block}