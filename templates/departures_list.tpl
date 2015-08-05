<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<title>Lista odjazdów</title>
	<link rel="stylesheet" href="{baseUrl}/css/pdf.css">
</head>
<body>
	{$i=0}
	{foreach $daytypes as $day}
		{if $counts[$i] > 0}
		<div class="row">
			<h2>{$day.name}</h2>
			<table>
				<tr>
					<th>Przystanek</th>
					{$n=1}
					{foreach $numbers[$i] as $number}
						<th>
							{* place for departure signs *}
						</th>
						{$n=$n+1}
					{/foreach}
				</tr>
				{$j=0}
				{foreach $route as $route_row}
				<tr>
					<td>({$route[$j].stopid}) {$route_row.name1} {$route_row.name2}</td>
					{$k=1}
					{foreach $numbers[$i] as $number}
						<td>
							{foreach $deps[$i][$j] as $dep}
								{if $dep.tripnumber == $k}
									{$dep.departure}<br>
									{break}
								{/if}
							{/foreach}
						</td>
						{$k=$k+1}
					{/foreach}
				</tr>
				{$j=$j+1}
				{/foreach}
			</table>
		</div>

		{if $signs|count}
		<div class="row">
			<h3>Oznaczenia</h3>
			{foreach $signs as $sign}
			<div class="sign">
				<div class="sig">{$sign.sign}</div> <div class="desc">{$sign.description}</div><br class="clear">
			</div>
			{/foreach}
		</div>
		{/if}

		<br class="break-page">
		<pagebreak />
		{$i=$i+1}
		{/if}
	{/foreach}
	{*
	<div class="row">
		<h3>Oznaczenia</h3>
		{foreach $signs as $sign}
		<div class="sign">
			<div class="sig">{$sign.sign}</div> <div class="desc">{$sign.description}</div>
		</div>
		{/foreach}
	</div>
	*}
</body>
</html>