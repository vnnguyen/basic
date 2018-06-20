<?
$this->title = 'Cats (beta version)';
?>
<table class="table table-condensed table-bordered">
	<thead></thead>
	<tbody>
		<? foreach ($cats as $li) { ?>
		<tr>
			<td><?= $li->id ?></td>
			<td><?= $li->name ?></td>
		</tr>
		<? } ?>
	</tbody>
</table>
