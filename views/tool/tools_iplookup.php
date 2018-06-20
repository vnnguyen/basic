<?php
use yii\helpers\Html;

$db2 = new fDatabase('mysql', 'amica_my2', 'amica_my', '2w#E4r%T', 'localhost');

$getIp = fRequest::get('ip', 'string', $_SERVER['REMOTE_ADDR'], true);
$getIp = trim($getIp);

$q = $db2->query('SELECT b.*, l.* FROM at_glc_blocks b, at_glc_locs l WHERE b.loc_id=l.id AND INET_ATON(%s)>=b.start_ip_num AND INET_ATON(%s)<=b.end_ip_num LIMIT 1', $getIp, $getIp);
$r = $q->countReturnedRows() > 0 ? $q->fetchRow() : null;

if (isset($r)) {
	// Country
	$q = $db2->query('SELECT name_en FROM at_countries WHERE code=%s LIMIT 1', strtoupper($r['country']));
	$r['country_name'] = $q->countReturnedRows() > 0 ? $q->fetchScalar() : '';
	// Region
	$q = $db2->query('SELECT region_name FROM at_glc_regions WHERE country=%s AND region_code=%s LIMIT 1', $r['country'], $r['region']);
	$r['region_name'] = $q->countReturnedRows() > 0 ? $q->fetchScalar() : '';
}

$this->title = 'IP address lookup';

?>
<div class="col-md-8">
	<form method="get" action="" class="well well-sm form-inline">
		<input type="text" class="form-control" name="ip" value="<?=$getIp?>" />
		<button type="submit" class="btn btn-primary">Look up this IP address</button>
		or <a rel="external" href="http://www.maxmind.com/en/geoip_demo">Look it up on Maxmind.com</a>
		or <a rel="external" href="http://whatismyipaddress.com/ip/<?= $getIp ?>">Look it up on WhatIsMyIpAddress.com</a>
	</form>
	<? if (!isset($r)) { ?>
	<p>No result found for IP this address: <?=$getIp?></p>
	<? } else { ?>
	<p>
		<strong>IP: </strong><?=$getIp?>
		&nbsp;
		<strong>Country:</strong> <?= Html::a($r['country_name'], 'http://www.google.com/webhp?tab=ww#hl=en&q='.$r['country_name'], ['rel'=>'external']) ?>
		&nbsp;
		<strong>Region:</strong> <?=Html::a($r['region_name'] == '' ? '( Not known )' : $r['region_name'], 'http://www.google.com/webhp?tab=ww#hl=en&q='.$r['region_name'].'%2C+'.$r['country_name'], ['rel'=>'external', 'title'=>'Google search'])?>
		&nbsp;
		<strong>City:</strong> <?=Html::a($r['city'] == '' ? '( Not known )' : $r['city'], 'http://www.google.com/webhp?tab=ww#hl=en&q='.$r['city'].'%2C+'.$r['country_name'], ['rel'=>'external', 'title'=>'Google search'])?>
	</p>
	<p><img class="img-rounded" src="http://maps.googleapis.com/maps/api/staticmap?center=<?=$r['lat']?>,<?=$r['lng']?>&zoom=6&size=640x400&sensor=false&markers=color:blue|label:X%|<?=$r['lat']?>,<?=$r['lng']?>" /></p>
		<? } ?>
	</table>
</div>
<div class="col-md-4">
	<h4>Instruction</h4>
	<ul>
		<li>This form is for IPV4 addresses only.</li>
		<li>The free GeoIP database is from Maxmind and updated 2012-09-04. Will be updated regularly.</li>
		<li>Accuracy of country is 99.5%, accurary of cities is 70% for France and 90% for Belgium</li>
		<li>You can always refer to the Maxmind website to look up the most updated data</li>
	</ul>
</div>
