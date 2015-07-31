{extends file='index.tpl'}
{block name="content"}
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3>Admin panel - powiedomienia</h3>
		</div>
		<div class="panel-body">
			{*if $announcements|count > 0*}
			<table class="table table-bordered table-condensed table-responsive table-hover">
				<tr>
					<th>ID</th>
					<th>Typ</th>
					<th>Dane</th>
					<th>Data od</th>
					<th>Data do</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Linia</td>
					<td>
						linia = 4<br>
						treść = Od 1.09 zawiesza się kursy do Niekłonic
					</td>
					<td>1.08.2015</td>
					<td>2.09.2015</td>
				</tr>
			</table>
			{*else}
			Brak dodanych powiadomień
			{/if*}
		</div>
		<div class="panel-footer text-right">
			<a href="{baseUrl}/admin/announcements/add" class="btn btn-info">
				Dodaj powiedomienie
			</a>
		</div>
	</div>
{/block}

{block name="scripts"}
    
{/block}