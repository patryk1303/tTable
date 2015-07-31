{extends file='index.tpl'}
{block name="content"}
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3>Admin panel - powiedomienia - dodaj</h3>
		</div>
		<div class="panel-body">
			<form action="{baseUrl}/admin/announcements/add" method="POST">
				<div class="form-group">
					<label for="type" class="col-sm-2">Typ:</label>
					<div class="col-sm-10">
						<select name="type" id="type">
							<option value="-1"></option>
							<option value="line">linia</option>
							<option value="direction">kierunek</option>
							<option value="stop">przystanek</option>
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
{/block}

{block name="scripts"}
    <script>
    	$('#type').on('change', function() {
    		var $self = $(this),
    			val = $self.val();
    		
    	});
    </script>
{/block}