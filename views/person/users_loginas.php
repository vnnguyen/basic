<?

$this->title = 'Login bằng tài khoản: '.$theUser['name'];
$this->params['breadcrumb'] = [
	['Người dùng', 'users'],
	['Xem', 'users/r/'.$theUser['id']],
	['Login', 'users/loginas/'.$theUser['id']],
];
?>
<div class="col-md-8">
  <form method="post" action="">
		<p>Xác nhận login bằng tài khoản của người này:<br />
		<input type="password" class="form-control" name="pwd" value="" /></p>
		<p><button type="submit" class="btn btn-primary">Login</button></p>
  </form>
</div>