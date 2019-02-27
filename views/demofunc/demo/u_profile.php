<?
echo 'waiting...';
$js = <<<TXT
	update_pro(1);

	function update_pro(page)
	{
		if(!page) page = 1;
		$.ajax({
				method: 'GET',
				url: '/demo/u_profile',
				data: {page: page},
				dataType: 'json'
			}).done(function(response){
				if(response.page == page) {
					console.log(response);return false;
				}
				page = response.page;

				update_pro(page);
			});
	}
TXT;
$this->registerJs($js);
?>