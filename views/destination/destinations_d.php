<?
$q = $db->query('SELECT * FROM at_destinations WHERE id=%i LIMIT 1', seg3);
$theDest = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error('Destination not found.');

if (fRequest::isPost() && fRequest::get('action', 'string') == 'delete') {
	$q = $db->query('DELETE FROM at_destinations WHERE id=%i LIMIT 1', $theDest['id']);
	redirect('destinations');
	exit;
}

$pageSb = 'input__sb.php';
$pageT = 'Delete destination: '.$theDest['name_en'];
$pageBR = array(
	anchor('#', 'Data input'),
	anchor('destinations', 'Destinations'),
	anchor('destinations/r/'.$theDest['id'], $theDest['name_en']),
	anchor(uris, 'Delete'),
);

include('__hd.php');?>
<div class="span12">
	<div class="alert alert-warning">
		<strong>WARNING: You are about to delete an item</strong>
		<br />This action cannot be undone. Are you sure you want to delete it?
	</div>
	<form class="form-inline" method="post" action="">
		<input type="hidden" name="action" value="delete" />
		<button type="submit" class="btn btn-danger">Yes, delete it now</button> or <?=anchor('destinations', 'Cancel')?>
	</form>	
</div>
<? include('__ft.php');

