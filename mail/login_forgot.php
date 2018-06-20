<?
define('APP_URL', 'https://my.amicatravel.com/');
?>
<div style="width:600px">
	<p>Xin hãy click đường link sau đây để lấy lại mật khẩu:</p>
	<p><a href="<?= APP_URL ?>login/reset?token=<?= $token ?>"><?= APP_URL ?>login/reset?token=<?= $token ?></a></p>
	<p>Trân trọng,</p>
	<p>Website admin</p>
</div>