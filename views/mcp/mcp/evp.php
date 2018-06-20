<?
Yii::$app->params['page_title'] = 'EVP account';
Yii::$app->params['page_layout'] = 'sli';
Yii::$app->params['page_breadcrumbs'] = [
	['EVP system', 'evp'],
	['Main'],
];
?>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Dashboard</h6>
		</div>
		<div class="panel-body">
			This is under construction
		</div>
	</div>
</div>

<? $this->beginBlock('sli'); ?>
<div class="sidebar sidebar-secondary sidebar-default">
	<div class="sidebar-content">
		<!-- Sidebar search -->
		<div class="sidebar-category">
			<div class="category-title">
				<span>Search</span>
				<ul class="icons-list">
					<li><a href="#" data-action="collapse"></a></li>
				</ul>
			</div>

			<div class="category-content">
				<form action="#">
					<div class="has-feedback has-feedback-left">
						<input type="search" class="form-control" placeholder="Search">
						<div class="form-control-feedback">
							<i class="icon-search4 text-size-base text-muted"></i>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- /sidebar search -->


		<!-- Sub navigation -->
		<div class="sidebar-category">
			<div class="category-title">
				<span>Navigation</span>
				<ul class="icons-list">
					<li><a href="#" data-action="collapse"></a></li>
				</ul>
			</div>

			<div class="category-content no-padding">
				<ul class="navigation navigation-alt navigation-accordion">
					<li class="navigation-header">Category title</li>
					<li><a href="#"><i class="icon-googleplus5"></i> Link</a></li>
					<li><a href="#"><i class="icon-googleplus5"></i> Another link</a></li>
					<li><a href="#"><i class="icon-portfolio"></i> Link with label <span class="label bg-success-400">Online</span></a></li>
					<li class="navigation-divider"></li>
					<li>
						<a href="#" class="has-ul"><i class="icon-cog3"></i> Menu levels</a>
						<ul class="hidden-ul">
							<li><a href="#"><i class="icon-IE"></i> Second level</a></li>
							<li>
								<a href="#" class="has-ul"><i class="icon-firefox"></i> Second level with child</a>
								<ul class="hidden-ul">
									<li><a href="#"><i class="icon-android"></i> Third level</a></li>
									<li>
										<a href="#" class="has-ul"><i class="icon-apple2"></i> Third level with child</a>
										<ul class="hidden-ul">
											<li><a href="#"><i class="icon-html5"></i> Fourth level</a></li>
											<li><a href="#"><i class="icon-css3"></i> Fourth level</a></li>
										</ul>
									</li>
									<li><a href="#"><i class="icon-windows"></i> Third level</a></li>
								</ul>
							</li>
							<li><a href="#"><i class="icon-chrome"></i> Second level</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<!-- /sub navigation -->


		<!-- Form sample -->
		<div class="sidebar-category">
			<div class="category-title">
				<span>Form example</span>
				<ul class="icons-list">
					<li><a href="#" data-action="collapse"></a></li>
				</ul>
			</div>

			<form action="#" class="category-content">
				<div class="form-group">
					<label>Your name:</label>
					<input type="text" class="form-control" placeholder="Username">
				</div>

				<div class="form-group">
					<label>Your password:</label>
					<input type="password" class="form-control" placeholder="Password">
				</div>

				<div class="form-group">
					<label>Your message:</label>
					<textarea rows="3" cols="3" class="form-control" placeholder="Default textarea"></textarea>
				</div>

				<div class="row">
					<div class="col-xs-6">
						<button type="reset" class="btn btn-danger btn-block">Reset</button>
					</div>
					<div class="col-xs-6">
						<button type="submit" class="btn btn-info btn-block">Submit</button>
					</div>
				</div>
			</form>
		</div>
		<!-- /form sample -->

	</div>
</div>
<? $this->endBlock(); ?>