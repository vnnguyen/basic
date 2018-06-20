
<!-- END: LAYOUT/HEADERS/HEADER-1 -->
<!-- BEGIN: CONTENT/USER/FORGET-PASSWORD-FORM -->
<div class="modal fade c-content-login-form" id="forget-password-form" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content c-square">
			<div class="modal-header c-no-border">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<h3 class="c-font-24 c-font-sbold">Password Recovery</h3>
				<p>
					To recover your password please fill in your email address
				</p>
				<form>
					<div class="form-group">
						<label for="forget-email" class="hide">Email</label>
						<input type="email" class="form-control input-lg c-square" id="forget-email" placeholder="Email">
					</div>
					<div class="form-group">
						<button type="submit" class="btn c-theme-btn btn-md c-btn-uppercase c-btn-bold c-btn-square c-btn-login">Submit</button>
						<a href="javascript:;" class="c-btn-forgot" data-toggle="modal" data-target="#login-form" data-dismiss="modal">Back To Login</a>
					</div>
				</form>
			</div>
			<div class="modal-footer c-no-border">
				<span class="c-text-account">Don't Have An Account Yet ?</span>
				<a href="javascript:;" data-toggle="modal" data-target="#signup-form" data-dismiss="modal" class="btn c-btn-dark-1 btn c-btn-uppercase c-btn-bold c-btn-slim c-btn-border-2x c-btn-square c-btn-signup">Signup!</a>
			</div>
		</div>
	</div>
</div>
<!-- END: CONTENT/USER/FORGET-PASSWORD-FORM -->
<!-- BEGIN: CONTENT/USER/SIGNUP-FORM -->
<div class="modal fade c-content-login-form" id="signup-form" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content c-square">
			<div class="modal-header c-no-border">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<h3 class="c-font-24 c-font-sbold">Create An Account</h3>
				<p>
					Please fill in below form to create an account with us
				</p>
				<form>
					<div class="form-group">
						<label for="signup-email" class="hide">Email</label>
						<input type="email" class="form-control input-lg c-square" id="signup-email" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="signup-username" class="hide">Username</label>
						<input type="email" class="form-control input-lg c-square" id="signup-username" placeholder="Username">
					</div>
					<div class="form-group">
						<label for="signup-fullname" class="hide">Fullname</label>
						<input type="email" class="form-control input-lg c-square" id="signup-fullname" placeholder="Fullname">
					</div>
					<div class="form-group">
						<label for="signup-country" class="hide">Country</label>
						<select class="form-control input-lg c-square" id="signup-country">
							<option value="1">Country</option>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" class="btn c-theme-btn btn-md c-btn-uppercase c-btn-bold c-btn-square c-btn-login">Signup</button>
						<a href="javascript:;" class="c-btn-forgot" data-toggle="modal" data-target="#login-form" data-dismiss="modal">Back To Login</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- END: CONTENT/USER/SIGNUP-FORM -->
<!-- BEGIN: CONTENT/USER/LOGIN-FORM -->
<div class="modal fade c-content-login-form" id="login-form" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content c-square">
			<div class="modal-header c-no-border">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<h3 class="c-font-24 c-font-sbold">Good Afternoon!</h3>
				<p>
					Let's make today a great day!
				</p>
				<form>
					<div class="form-group">
						<label for="login-email" class="hide">Email</label>
						<input type="email" class="form-control input-lg c-square" id="login-email" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="login-password" class="hide">Password</label>
						<input type="password" class="form-control input-lg c-square" id="login-password" placeholder="Password">
					</div>
					<div class="form-group">
						<div class="c-checkbox">
							<input type="checkbox" id="login-rememberme" class="c-check">
							<label for="login-rememberme" class="c-font-thin c-font-17">
							<span></span>
							<span class="check"></span>
							<span class="box"></span>
							Remember Me </label>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn c-theme-btn btn-md c-btn-uppercase c-btn-bold c-btn-square c-btn-login">Login</button>
						<a href="javascript:;" data-toggle="modal" data-target="#forget-password-form" data-dismiss="modal" class="c-btn-forgot">Forgot Your Password ?</a>
					</div>
					<div class="clearfix">
						<div class="c-content-divider c-divider-sm c-icon-bg c-bg-grey c-margin-b-20">
							<span>or signup with</span>
						</div>
						<ul class="c-content-list-adjusted">
							<li>
								<a class="btn btn-block c-btn-square btn-social btn-twitter">
								<i class="fa fa-twitter"></i>
								Twitter </a>
							</li>
							<li>
								<a class="btn btn-block c-btn-square btn-social btn-facebook">
								<i class="fa fa-facebook"></i>
								Facebook </a>
							</li>
							<li>
								<a class="btn btn-block c-btn-square btn-social btn-google">
								<i class="fa fa-google"></i>
								Google </a>
							</li>
						</ul>
					</div>
				</form>
			</div>
			<div class="modal-footer c-no-border">
				<span class="c-text-account">Don't Have An Account Yet ?</span>
				<a href="javascript:;" data-toggle="modal" data-target="#signup-form" data-dismiss="modal" class="btn c-btn-dark-1 btn c-btn-uppercase c-btn-bold c-btn-slim c-btn-border-2x c-btn-square c-btn-signup">Signup!</a>
			</div>
		</div>
	</div>
</div>
<!-- END: CONTENT/USER/LOGIN-FORM -->
<!-- BEGIN: LAYOUT/SIDEBARS/QUICK-SIDEBAR -->
<nav class="c-layout-quick-sidebar">
<div class="c-header">
	<button type="button" class="c-link c-close">
	<i class="icon-login"></i>
	</button>
</div>
<div class="c-content">
	<div class="c-section">
		<h3>Theme Colors</h3>
		<div class="c-settings">
			<span class="c-color c-default c-active" data-color="default"></span>
			<span class="c-color c-green1" data-color="green1"></span>
			<span class="c-color c-green2" data-color="green2"></span>
			<span class="c-color c-green3" data-color="green3"></span>
			<span class="c-color c-yellow1" data-color="yellow1"></span>
			<span class="c-color c-yellow2" data-color="yellow2"></span>
			<span class="c-color c-yellow3" data-color="yellow3"></span>
			<span class="c-color c-red1" data-color="red1"></span>
			<span class="c-color c-red2" data-color="red2"></span>
			<span class="c-color c-red3" data-color="red3"></span>
			<span class="c-color c-purple1" data-color="purple1"></span>
			<span class="c-color c-purple2" data-color="purple2"></span>
			<span class="c-color c-purple3" data-color="purple3"></span>
			<span class="c-color c-blue1" data-color="blue1"></span>
			<span class="c-color c-blue2" data-color="blue2"></span>
			<span class="c-color c-blue3" data-color="blue3"></span>
			<span class="c-color c-brown1" data-color="brown1"></span>
			<span class="c-color c-brown2" data-color="brown2"></span>
			<span class="c-color c-brown3" data-color="brown3"></span>
			<span class="c-color c-dark1" data-color="dark1"></span>
			<span class="c-color c-dark2" data-color="dark2"></span>
			<span class="c-color c-dark3" data-color="dark3"></span>
		</div>
	</div>
	<div class="c-section">
		<h3>Header Type</h3>
		<div class="c-settings">
			<input type="button" class="c-setting_header-type btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase active" data-value="boxed" value="boxed"/>
			<input type="button" class="c-setting_header-type btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase" data-value="fluid" value="fluid"/>
		</div>
	</div>
	<div class="c-section">
		<h3>Header Mode</h3>
		<div class="c-settings">
			<input type="button" class="c-setting_header-mode btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase active" data-value="fixed" value="fixed"/>
			<input type="button" class="c-setting_header-mode btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase" data-value="static" value="static"/>
		</div>
	</div>
	<div class="c-section">
		<h3>Mega Menu Style</h3>
		<div class="c-settings">
			<input type="button" class="c-setting_megamenu-style btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase active" data-value="dark" value="dark"/>
			<input type="button" class="c-setting_megamenu-style btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase" data-value="light" value="light"/>
		</div>
	</div>
	<div class="c-section">
		<h3>Font Style</h3>
		<div class="c-settings">
			<input type="button" class="c-setting_font-style btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase active" data-value="default" value="default"/>
			<input type="button" class="c-setting_font-style btn btn-sm c-btn-square c-btn-border-1x c-btn-white c-btn-sbold c-btn-uppercase" data-value="light" value="light"/>
		</div>
	</div>
</div>
</nav>
<!-- END: LAYOUT/SIDEBARS/QUICK-SIDEBAR -->
<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
	<!-- BEGIN: PAGE CONTENT -->
	<!-- BEGIN: LAYOUT/SLIDERS/REVO-SLIDER-4 -->
	<section class="c-layout-revo-slider c-layout-revo-slider-4">
	<div class="tp-banner-container c-theme" style="height: 620px">
		<div class="tp-banner">
			<ul>
				<!--BEGIN: SLIDE #1 -->
				<li data-transition="fade" data-slotamount="1" data-masterspeed="1000">
					<img alt="" src="assets/base/img/content/backgrounds/bg-29.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
					<div class="caption customin customout" data-x="center" data-y="center" data-hoffset="" data-voffset="-50" data-speed="500" data-start="1000" data-customin="x:0;y:0;z:0;rotationX:0.5;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-easing="Back.easeOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="600">
						<h3 class="c-main-title-circle c-font-48 c-font-bold c-font-center c-font-uppercase c-font-white c-block">
						TAKE THE WEB BY<br>
						STORM WITH JANGO </h3>
					</div>
					<div class="caption lft" data-x="center" data-y="center" data-voffset="110" data-speed="900" data-start="2000" data-easing="easeOutExpo">
						<a href="#" class="c-action-btn btn btn-lg c-btn-square c-theme-btn c-btn-bold c-btn-uppercase">Learn More</a>
					</div>
				</li>
				<!--END -->
				<!--BEGIN: SLIDE #2 -->
				<li data-transition="fade" data-slotamount="1" data-masterspeed="1000">
					<img alt="" src="assets/base/img/content/backgrounds/bg-51.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
					<div class="caption customin customout" data-x="center" data-y="center" data-hoffset="" data-voffset="-50" data-speed="500" data-start="1000" data-customin="x:0;y:0;z:0;rotationX:0.5;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-easing="Back.easeOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="600">
						<h3 class="c-main-title-circle c-font-48 c-font-bold c-font-center c-font-uppercase c-font-white c-block">
						JANGO IS OPTIMIZED<br>
						TO EVERY DEVELOPMENT </h3>
					</div>
					<div class="caption lft" data-x="center" data-y="center" data-voffset="110" data-speed="900" data-start="2000" data-easing="easeOutExpo">
						<a href="#" class="c-action-btn btn btn-lg c-btn-square c-theme-btn c-btn-bold c-btn-uppercase">Learn More</a>
					</div>
				</li>
				<!--END -->
				<!--BEGIN: SLIDE #3 -->
				<li data-transition="fade" data-slotamount="1" data-masterspeed="700" data-delay="6000" data-thumb="">
					<!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
					<img src="assets/base/img/layout/sliders/revo-slider/base/blank.png" alt="">
					<div class="caption fulllscreenvideo tp-videolayer" data-x="0" data-y="0" data-speed="600" data-start="1000" data-easing="Power4.easeOut" data-endspeed="500" data-endeasing="Power4.easeOut" data-autoplay="true" data-autoplayonlyfirsttime="false" data-nextslideatend="true" data-videowidth="100%" data-videoheight="100%" data-videopreload="meta" data-videomp4="assets/base/media/video/video-2.mp4" data-videowebm="" data-videocontrols="none" data-forcecover="1" data-forcerewind="on" data-aspectratio="16:9" data-volume="mute" data-videoposter="assets/base/img/layout/sliders/revo-slider/base/blank.png">
					</div>
					<div class="caption customin customout" data-x="center" data-y="center" data-hoffset="" data-voffset="-30" data-speed="500" data-start="1000" data-customin="x:0;y:0;z:0;rotationX:0.5;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-easing="Back.easeOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="600">
						<h3 class="c-main-title-square c-font-55 c-font-bold c-font-center c-font-uppercase c-font-white c-block">
						Let us show you<br>
						Unlimited possibilities </h3>
					</div>
					<div class="caption lft" data-x="center" data-y="center" data-voffset="130" data-speed="900" data-start="2000" data-easing="easeOutExpo">
						<a href="#" class="c-action-btn btn btn-lg c-btn-square c-btn-border-2x c-btn-white c-btn-bold c-btn-uppercase">Purchase</a>
					</div>
					<div class="tp-caption arrowicon customin rs-parallaxlevel-0 visible-xs" data-x="center" data-y="bottom" data-hoffset="0" data-voffset="-60" data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="500" data-start="2000" data-easing="Power3.easeInOut" data-elementdelay="0.1" data-endelementdelay="0.1" data-linktoslide="next" style="z-index: 13;">
						<div class="rs-slideloop" data-easing="Power3.easeInOut" data-speed="0.5" data-xs="-5" data-xe="5" data-ys="0" data-ye="0">
							<span class="c-video-hint c-font-15 c-font-sbold c-font-center c-font-dark">
							Tap to play video <i class="icon-control-play"></i>
							</span>
						</div>
					</div>
				</li>
				<!--END -->
			</ul>
		</div>
	</div>
	</section>
	<!-- END: LAYOUT/SLIDERS/REVO-SLIDER-4 -->
	<!-- BEGIN: CONTENT/FEATURES/FEATURES-1 -->
	<div class="c-content-box c-size-md c-bg-white">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="c-content-feature-1">
						<div class="c-content-line-icon c-theme c-icon-screen-chart">
						</div>
						<h3 class="c-font-uppercase c-font-bold">Fully responsive</h3>
						<p class="c-font-thin">
							Beautiful cinematic designs optimized for all screen sizes and types. Compatible with Retina high pixel density displays.
						</p>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="c-content-feature-1">
						<div class="c-content-line-icon c-theme c-icon-support">
						</div>
						<h3 class="c-font-uppercase c-font-bold">Visual & Pragmatic</h3>
						<p class="c-font-thin">
							Featuring trending modern web standards.<br/>Clean and easy framework design for worry and hassle free customizations.
						</p>
					</div>
				</div>
				<div class="col-sm-4 c-card">
					<div class="c-content-feature-1">
						<div class="c-content-line-icon c-theme c-icon-bulb">
						</div>
						<h3 class="c-font-uppercase c-font-bold">Dedicated Support</h3>
						<p class="c-font-thin">
							Quick response with regular updates.<br/>Each update will include great new features and enhancements for free.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: CONTENT/FEATURES/FEATURES-1 -->
	<!-- BEGIN: CONTENT/PORTFOLIO/LATEST-WORKS-1 -->
	<div class="c-content-box c-size-md c-bg-grey-1">
		<div class="container">
			<div class="c-content-title-1">
				<h3 class="c-center c-font-uppercase c-font-bold">Latest Portfolio</h3>
				<div class="c-line-center c-theme-bg">
				</div>
				<p class="c-center c-font-uppercase">
					Showcasing your latest designs, sketches, photographs or videos.
				</p>
			</div>
			<div class="cbp-panel">
				<!-- SEE: components.js:ContentCubeLatestPortfolio -->
				<div class="c-content-latest-works cbp cbp-l-grid-masonry-projects">
					<div class="cbp-item graphic">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/08-long.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project1.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock/08.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="Dashboard<br>by Paul Flavius Nechita">zoom</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cbp-item web-design logos">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/07.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project2.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock/07.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="World clock widget<br>by Paul Flavius Nechita">zoom</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cbp-item graphic logos">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/09.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project3.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a href="http://vimeo.com/14912890" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="To-Do dashboard<br>by Tiberiu Neamu">view video</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cbp-item identity web-design">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/014.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project4.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock/014.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="WhereTO app<br>by Tiberiu Neamu">zoom</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cbp-item web-design graphic">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/34.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project5.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock/34.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="Events and more<br>by Tiberiu Neamu">zoom</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cbp-item identity web-design">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/53.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project6.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock/53.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="Ski * buddy<br>by Tiberiu Neamu">zoom</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cbp-item graphic logos">
						<div class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								<img src="assets/base/img/content/stock/39.jpg" alt="">
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="c-masonry-border">
								</div>
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<a href="ajax/projects/project7.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">explore</a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock/39.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="Seemple* music for ipad<br>by Tiberiu Neamu">zoom</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: CONTENT/PORTFOLIO/LATEST-WORKS-1 -->
	<!-- BEGIN: CONTENT/MISC/SERVICES-3 -->
	<div class="c-content-box c-size-md c-bg-white">
		<div class="container">
			<div class="c-content-feature-2-grid">
				<div class="c-content-title-1">
					<h3 class="c-font-uppercase c-center c-font-bold">Services We Do</h3>
					<div class="c-line-center">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-6">
						<div class="c-content-feature-2 c-option-2 c-theme-bg-parent-hover">
							<div class="c-icon-wrapper c-theme-bg-on-parent-hover">
								<div class="c-content-line-icon c-theme c-icon-screen-chart">
								</div>
							</div>
							<h3 class="c-font-uppercase c-title">Web Design</h3>
							<p>
								Lorem ipsum sit dolor eamet dolore adipiscing
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="c-content-feature-2 c-option-2 c-theme-bg-parent-hover">
							<div class="c-icon-wrapper c-theme-bg-on-parent-hover">
								<div class="c-content-line-icon c-theme c-icon-support">
								</div>
							</div>
							<h3 class="c-font-uppercase c-title">Mobile Apps</h3>
							<p>
								Lorem ipsum sit dolor eamet dolore adipiscing
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="c-content-feature-2 c-option-2 c-theme-bg-parent-hover">
							<div class="c-icon-wrapper c-theme-bg-on-parent-hover">
								<div class="c-content-line-icon c-theme c-icon-comment">
								</div>
							</div>
							<h3 class="c-font-uppercase c-title">Consulting</h3>
							<p>
								Lorem ipsum sit dolor eamet dolore adipiscing
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="c-content-feature-2 c-option-2 c-theme-bg-parent-hover">
							<div class="c-icon-wrapper c-theme-bg-on-parent-hover">
								<div class="c-content-line-icon c-theme c-icon-bulb">
								</div>
							</div>
							<h3 class="c-font-uppercase c-title">Campaigns</h3>
							<p>
								Lorem ipsum sit dolor eamet dolore adipiscing
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="c-content-feature-2 c-option-2 c-theme-bg-parent-hover">
							<div class="c-icon-wrapper c-theme-bg-on-parent-hover">
								<div class="c-content-line-icon c-theme c-icon-sticker">
								</div>
							</div>
							<h3 class="c-font-uppercase c-title">UX Design</h3>
							<p>
								Lorem ipsum sit dolor eamet dolore adipiscing
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="c-content-feature-2 c-option-2 c-theme-bg-parent-hover">
							<div class="c-icon-wrapper c-theme-bg-on-parent-hover">
								<div class="c-content-line-icon c-theme c-icon-globe">
								</div>
							</div>
							<h3 class="c-font-uppercase c-title">Hosting</h3>
							<p>
								Lorem ipsum sit dolor eamet dolore adipiscing
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: CONTENT/MISC/SERVICES-3 -->
	<!-- BEGIN: CONTENT/STATS/COUNTER-3 -->
	<div class="c-content-box c-size-md c-bg-parallax" style="background-image: url(assets/base/img/content/backgrounds/bg-29.jpg)">
		<div class="container">
			<div class="c-content-counter-1">
				<div class="c-content-title-1">
					<h3 class="c-center c-font-uppercase c-font-white c-font-bold">We never stop improving</h3>
					<div class="c-line-center c-bg-white">
					</div>
				</div>
				<div class="row c-margin-t-60">
					<div class="col-md-4">
						<div class="c-counter c-font-white c-bordered c-border-red c-font-white" data-counter="counterup">
							130
						</div>
						<h4 class="c-title c-first c-font-white c-font-uppercase c-font-bold">Current Pages</h4>
						<p class="c-content c-font-white c-opacity-08">
							..and growing. We will never stop improving and updating JANGO. Expect more.
						</p>
					</div>
					<div class="col-md-4">
						<div class="c-counter c-font-white c-bordered c-border-blue c-font-white" data-counter="counterup">
							35,500
						</div>
						<h4 class="c-title c-font-white c-font-uppercase c-font-bold">Satisfied Customers</h4>
						<p class="c-content c-font-white c-opacity-08">
							Our Professional and dedicated team are on stand by to server your every concern.
						</p>
					</div>
					<div class="col-md-4">
						<div class="c-counter c-font-white c-bordered c-border-green c-font-white" data-counter="counterup">
							101,865
						</div>
						<h4 class="c-title c-font-white c-font-uppercase c-font-bold">Total Downloads</h4>
						<p class="c-content c-font-white c-opacity-08">
							Join the community of over 101,865 users.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: CONTENT/STATS/COUNTER-3 -->
	<!-- BEGIN: CONTENT/BARS/BAR-6 -->
	<div class="c-content-box c-size-md">
		<div class="container">
			<div class="c-content-bar-1 c-opt-1 c-bordered c-theme-border c-shadow">
				<h3 class="c-font-uppercase c-font-bold">JANGO is optimized to every development</h3>
				<p class="c-font-uppercase">
					 JANGO is build to completely support your web projects by<br>
					 providing <strong>Ultra Flexibility</strong>, <strong>Increased productivity</strong> and <strong>Top quality</strong>
				</p>
				<button type="button" class="btn btn-md c-btn-square c-btn-border-2x c-btn-dark c-btn-uppercase c-btn-bold c-margin-r-40">Explore</button>
				<button type="button" class="btn btn-md c-btn-square c-theme-btn c-btn-uppercase c-btn-bold">Purchase</button>
			</div>
		</div>
	</div>
	<!-- END: CONTENT/BARS/BAR-6 -->
	<!-- BEGIN: CONTENT/BLOG/RECENT-POSTS -->
	<div class="c-content-box c-size-md c-bg-grey-1">
		<div class="container">
			<!-- Begin: Testimonals 1 component -->
			<div class="c-content-blog-post-card-1-slider" data-slider="owl" data-items="3" data-auto-play="8000">
				<!-- Begin: Title 1 component -->
				<div class="c-content-title-1">
					<h3 class="c-center c-font-uppercase c-font-bold">Recent Blog Posts</h3>
					<div class="c-line-center c-theme-bg">
					</div>
				</div>
				<!-- End-->
				<!-- Begin: Owlcarousel -->
				<div class="owl-carousel owl-theme c-theme">
					<div class="item">
						<div class="c-content-blog-post-card-1 c-option-2">
							<div class="c-media c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock2/06.jpg" data-lightbox="fancybox" data-fancybox-group="gallery">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img class="c-overlay-object img-responsive" src="assets/base/img/content/stock2/06.jpg" alt="">
							</div>
							<div class="c-body">
								<div class="c-title c-font-uppercase c-font-bold">
									<a href="#">Web & Mobile Development</a>
								</div>
								<div class="c-author">
									 By <a href="#"><span class="c-font-uppercase">Nick Strong</span></a>
									on <span class="c-font-uppercase">20 May 2015, 10:30AM</span>
								</div>
								<div class="c-panel">
									<ul class="c-tags c-theme-ul-bg">
										<li>
											ux
										</li>
										<li>
											web
										</li>
										<li>
											events
										</li>
									</ul>
									<div class="c-comments">
										<a href="#"><i class="icon-speech"></i> 30 comments</a>
									</div>
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-blog-post-card-1 c-option-2">
							<div class="c-media c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock2/01.jpg" data-lightbox="fancybox" data-fancybox-group="gallery">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img class="c-overlay-object img-responsive" src="assets/base/img/content/stock2/01.jpg" alt="">
							</div>
							<div class="c-body">
								<div class="c-title c-font-uppercase c-font-bold">
									<a href="#">Modern Design Trends</a>
								</div>
								<div class="c-author">
									 By <a href="#"><span class="c-font-uppercase">Penny Baker</span></a>
									on <span class="c-font-uppercase">25 May 2015, 10:30AM</span>
								</div>
								<div class="c-panel">
									<ul class="c-tags c-theme-ul-bg">
										<li>
											design
										</li>
										<li>
											art
										</li>
										<li>
											trends
										</li>
									</ul>
									<div class="c-comments">
										<a href="#"><i class="icon-speech"></i> 18 comments</a>
									</div>
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-blog-post-card-1 c-option-2">
							<div class="c-media c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock2/03.jpg" data-lightbox="fancybox" data-fancybox-group="gallery">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img class="c-overlay-object img-responsive" src="assets/base/img/content/stock2/03.jpg" alt="">
							</div>
							<div class="c-body">
								<div class="c-title c-font-uppercase c-font-bold">
									<a href="#">Beatifully crafted Code</a>
								</div>
								<div class="c-author">
									 By <a href="#"><span class="c-font-uppercase">Jim Raynor</span></a>
									on <span class="c-font-uppercase">26 May 2015, 10:30AM</span>
								</div>
								<div class="c-panel">
									<ul class="c-tags c-theme-ul-bg">
										<li>
											HTML
										</li>
										<li>
											CSS
										</li>
										<li>
											web
										</li>
									</ul>
									<div class="c-comments">
										<a href="#"><i class="icon-speech"></i> 34 comments</a>
									</div>
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-blog-post-card-1 c-option-2">
							<div class="c-media c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock2/04.jpg" data-lightbox="fancybox" data-fancybox-group="gallery">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img class="c-overlay-object img-responsive" src="assets/base/img/content/stock2/04.jpg" alt="">
							</div>
							<div class="c-body">
								<div class="c-title c-font-uppercase c-font-bold">
									<a href="#">Optimized for all Devices</a>
								</div>
								<div class="c-author">
									 By <a href="#"><span class="c-font-uppercase">Sara Conner</span></a>
									on <span class="c-font-uppercase">29 May 2015, 10:30AM</span>
								</div>
								<div class="c-panel">
									<ul class="c-tags c-theme-ul-bg">
										<li>
											Mobile
										</li>
										<li>
											web
										</li>
										<li>
											ux
										</li>
									</ul>
									<div class="c-comments">
										<a href="#"><i class="icon-speech"></i> 25 comments</a>
									</div>
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-blog-post-card-1 c-option-2">
							<div class="c-media c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/stock2/05.jpg" data-lightbox="fancybox" data-fancybox-group="gallery">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img class="c-overlay-object img-responsive" src="assets/base/img/content/stock2/05.jpg" alt="">
							</div>
							<div class="c-body">
								<div class="c-title c-font-uppercase c-font-bold">
									<a href="#">Compatible to all browsers</a>
								</div>
								<div class="c-author">
									 By <a href="#"><span class="c-font-uppercase">Mary Jane</span></a>
									on <span class="c-font-uppercase">30 May 2015, 10:30AM</span>
								</div>
								<div class="c-panel">
									<ul class="c-tags c-theme-ul-bg">
										<li>
											chrome
										</li>
										<li>
											firefox
										</li>
										<li>
											ie
										</li>
									</ul>
									<div class="c-comments">
										<a href="#"><i class="icon-speech"></i> 28 comments</a>
									</div>
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
				</div>
				<!-- End-->
			</div>
			<!-- End-->
		</div>
	</div>
	<!-- END: CONTENT/BLOG/RECENT-POSTS -->
	<!-- BEGIN: CONTENT/MISC/LATEST-ITEMS-3 -->
	<div class="c-content-box c-size-md c-bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="c-content-media-1 c-bordered" style="min-height: 380px;">
						<div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">
							Our Mission
						</div>
						<a href="#" class="c-title c-font-uppercase c-theme-on-hover c-font-bold">Take the web by storm with JANGO</a>
						<p>
							Lorem ipsum dolor sit amet, coectetuer adipiscing elit sed diam nonummy et nibh euismod aliquam erat volutpat
						</p>
						<div class="c-author">
							<div class="c-portrait" style="background-image: url(assets/base/img/content/team/team16.jpg)">
							</div>
							<div class="c-name c-font-uppercase">
								Jack Nilson
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="c-content-media-2-slider" data-slider="owl" data-single-item="true" data-auto-play="4000">
						<div class="c-content-label c-font-uppercase c-font-bold">
							Latest Projects
						</div>
						<div class="owl-carousel owl-theme c-theme owl-single">
							<div class="item">
								<div class="c-content-media-2 c-bg-img-center" style="background-image: url(assets/base/img/content/stock3/36.jpg); min-height: 380px;">
									<div class="c-panel">
										<div class="c-fav">
											<i class="icon-heart c-font-thin"></i>
											<p class="c-font-thin">
												16
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="c-content-media-2 c-bg-img-center" style="background-image: url(assets/base/img/content/stock3/43.jpg); min-height: 380px;">
									<div class="c-panel">
										<div class="c-fav">
											<i class="icon-heart c-font-thin"></i>
											<p class="c-font-thin">
												24
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="c-content-media-2 c-bg-img-center" style="background-image: url(assets/base/img/content/stock3/50.jpg); min-height: 380px;">
									<div class="c-panel">
										<div class="c-fav">
											<i class="icon-heart c-font-thin"></i>
											<p class="c-font-thin">
												19
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: CONTENT/MISC/LATEST-ITEMS-3 -->
	<!-- BEGIN: CONTENT/TESTIMONIALS/TESTIMONIALS-3 -->
	<div class="c-content-box c-size-lg c-bg-parallax" style="background-image: url(assets/base/img/content/backgrounds/bg-3.jpg)">
		<div class="container">
			<!-- Begin: testimonials 1 component -->
			<div class="c-content-testimonials-1" data-slider="owl" data-single-item="true" data-auto-play="5000">
				<!-- Begin: Title 1 component -->
				<div class="c-content-title-1">
					<h3 class="c-center c-font-white c-font-uppercase c-font-bold">Let's See What Our Customers Say</h3>
					<div class="c-line-center c-theme-bg">
					</div>
				</div>
				<!-- End-->
				<!-- Begin: Owlcarousel -->
				<div class="owl-carousel owl-theme c-theme">
					<div class="item">
						<div class="c-testimonial">
							<p>
								 “A system change is always stressful and JANGO did a great job of staying positive, helpful, and patient with us.”
							</p>
							<h3>
							<span class="c-name c-theme">John Snow</span>, CEO, Mockingbird </h3>
						</div>
					</div>
					<div class="item">
						<div class="c-testimonial">
							<p>
								 “It was the smoothest implementation process I have ever been through with JANGO’s process and schedule.”
							</p>
							<h3>
							<span class="c-name c-theme">Arya Stark</span>, CFO, Valar Dohaeris </h3>
						</div>
					</div>
					<div class="item">
						<div class="c-testimonial">
							<p>
								 “After co-founding the company in 2006 the group launched JANGO, the first digital marketplace which focused on rich multimedia web content”
							</p>
							<h3>
							<span class="c-name c-theme">Arya Stark</span>, CFO, Valar Dohaeris </h3>
						</div>
					</div>
					<div class="item">
						<div class="c-testimonial">
							<p>
								 “JANGO is an international, privately held company that specializes in the start-up, promotion and operation of multiple online marketplaces”
							</p>
							<h3>
							<span class="c-name c-theme">Arya Stark</span>, CFO, Valar Dohaeris </h3>
						</div>
					</div>
				</div>
				<!-- End-->
			</div>
			<!-- End-->
		</div>
	</div>
	<!-- END: CONTENT/TESTIMONIALS/TESTIMONIALS-3 -->
	<!-- BEGIN: CONTENT/SLIDERS/TEAM-1 -->
	<div class="c-content-box c-size-md c-bg-white">
		<div class="container">
			<!-- Begin: Testimonals 1 component -->
			<div class="c-content-person-1-slider" data-slider="owl" data-items="3" data-auto-play="8000">
				<!-- Begin: Title 1 component -->
				<div class="c-content-title-1">
					<h3 class="c-center c-font-uppercase c-font-bold">Meet The Team</h3>
					<div class="c-line-center c-theme-bg">
					</div>
				</div>
				<!-- End-->
				<!-- Begin: Owlcarousel -->
				<div class="owl-carousel owl-theme c-theme">
					<div class="item">
						<div class="c-content-person-1">
							<div class="c-caption c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/team/person7.jpg" data-lightbox="fancybox" data-fancybox-group="gallery-1">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img class="c-overlay-object img-responsive" src="assets/base/img/content/team/person7.jpg" alt="">
							</div>
							<div class="c-body">
								<div class="c-head">
									<div class="c-name c-font-uppercase c-font-bold">
										John Doe
									</div>
									<ul class="c-socials c-theme-ul">
										<li>
											<a href="#"><i class="icon-social-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-dribbble"></i></a>
										</li>
									</ul>
								</div>
								<div class="c-position">
									 CEO, Mockingbird
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-person-1">
							<div class="c-caption c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/team/person6.jpg" data-lightbox="fancybox" data-fancybox-group="gallery-1">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img src="assets/base/img/content/team/person6.jpg" class="img-responsive c-overlay-object" alt="">
							</div>
							<div class="c-body">
								<div class="c-head">
									<div class="c-name c-font-uppercase c-font-bold">
										Jim Raynor
									</div>
									<ul class="c-socials c-theme-ul">
										<li>
											<a href="#"><i class="icon-social-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-dribbble"></i></a>
										</li>
									</ul>
								</div>
								<div class="c-position">
									 Caption, Hyperion
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-person-1">
							<div class="c-caption c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/team/person5.jpg" data-lightbox="fancybox" data-fancybox-group="gallery-1">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img src="assets/base/img/content/team/person5.jpg" class="img-responsive c-overlay-object" alt="">
							</div>
							<div class="c-body">
								<div class="c-head">
									<div class="c-name c-font-uppercase c-font-bold">
										Drake Hiro
									</div>
									<ul class="c-socials c-theme-ul">
										<li>
											<a href="#"><i class="icon-social-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-dribbble"></i></a>
										</li>
									</ul>
								</div>
								<div class="c-position">
									 Entrepreneur
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-person-1">
							<div class="c-caption c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/team/person4.jpg" data-lightbox="fancybox" data-fancybox-group="gallery-1">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img src="assets/base/img/content/team/person4.jpg" class="img-responsive c-overlay-object" alt="">
							</div>
							<div class="c-body">
								<div class="c-head">
									<div class="c-name c-font-uppercase c-font-bold">
										Maxell Heart
									</div>
									<ul class="c-socials c-theme-ul">
										<li>
											<a href="#"><i class="icon-social-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-dribbble"></i></a>
										</li>
									</ul>
								</div>
								<div class="c-position">
									 CTO, Octoberite
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="c-content-person-1">
							<div class="c-caption c-content-overlay">
								<div class="c-overlay-wrapper">
									<div class="c-overlay-content">
										<a href="#"><i class="icon-link"></i></a>
										<a <?= DIR ?>assets/jango_1.2.0/base/img/content/team/person1.jpg" data-lightbox="fancybox" data-fancybox-group="gallery-1">
										<i class="icon-magnifier"></i>
										</a>
									</div>
								</div>
								<img src="assets/base/img/content/team/person1.jpg" class="img-responsive c-overlay-object" alt="">
							</div>
							<div class="c-body">
								<div class="c-head">
									<div class="c-name c-font-uppercase c-font-bold">
										Phil Garner
									</div>
									<ul class="c-socials c-theme-ul">
										<li>
											<a href="#"><i class="icon-social-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon-social-dribbble"></i></a>
										</li>
									</ul>
								</div>
								<div class="c-position">
									 CEO, Philly
								</div>
								<p>
									 Lorem ipsum dolor sit amet, dolor adipisicing elit. Nulla nemo ad sapiente officia amet.
								</p>
							</div>
						</div>
					</div>
				</div>
				<!-- End-->
			</div>
			<!-- End-->
		</div>
	</div>
	<!-- END: CONTENT/SLIDERS/TEAM-1 -->
	<!-- BEGIN: CONTENT/SLIDERS/CLIENT-LOGOS-2 -->
	<div class="c-content-box c-size-md c-bg-white">
		<div class="container">
			<!-- Begin: Testimonals 1 component -->
			<div class="c-content-client-logos-slider-1 c-bordered" data-slider="owl" data-items="6" data-desktop-items="4" data-desktop-small-items="3" data-tablet-items="3" data-mobile-small-items="2" data-auto-play="5000">
				<!-- Begin: Title 1 component -->
				<div class="c-content-title-1">
					<h3 class="c-center c-font-uppercase c-font-bold">Happy Customers</h3>
					<div class="c-line-center c-theme-bg">
					</div>
				</div>
				<!-- End-->
				<!-- Begin: Owlcarousel -->
				<div class="owl-carousel owl-theme c-theme owl-bordered1">
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client1.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client2.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client3.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client4.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client5.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client6.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client5.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client6.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client5.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client6.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client5.jpg" alt=""/></a>
					</div>
					<div class="item">
						<a href="#"><img src="assets/base/img/content/client-logos/client6.jpg" alt=""/></a>
					</div>
				</div>
				<!-- End-->
			</div>
			<!-- End-->
		</div>
	</div>
	<!-- END: CONTENT/SLIDERS/CLIENT-LOGOS-2 -->
	<!-- END: PAGE CONTENT -->
</div>
<!-- END: PAGE CONTAINER -->
