<?
$this->title = $model['name_en'];
$this->params['icon'] = 'globe';
$this->params['breadcrumb'] = [
	['Destinations', 'destinations'],
	['View', 'destinations/r/'.$model['id']],
];
$this->params['actions'] = [
	['New destination', 'destinations/c', 'plus'],
];
$this->params['nav'] = 'input|destinations';
?>
<div class="col-lg-12">
	
</div>
