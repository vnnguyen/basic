<?
use yii\helpers\Html;
$this->title = $model['title'];
?>

<style type="text/css">
img[src^="https://mail.google.com/mail/e/"], img[goomoji] {display:inline!important; margin:0!important;}
.td-post-content img {height:auto!important;}
</style>
<div class="td-main-content-wrap">
	<div class="td-container td-post-template-default">
		<div class="td-crumb-container">
			<div class="entry-crumbs">
				<span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a title="" class="entry-crumb" itemprop="url" href="<?= DIR ?>"><span itemprop="title">IMS</span></a></span>
				<i class="td-icon-right td-bread-sep"></i>
				<span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a title="View all posts in #" class="entry-crumb" itemprop="url" href="#"><span itemprop="title">Blog</span></a></span>
				<i class="td-icon-right td-bread-sep"></i>
				<span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a title="View all posts in #" class="entry-crumb" itemprop="url" href="#"><span itemprop="title">Cat</span></a></span>
				<i class="td-icon-right td-bread-sep td-bred-no-url-last"></i>
				<span class="td-bred-no-url-last" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><meta itemprop="title" content="#"><meta itemprop="url" content="#">...</span>
			</div>
		</div>

		<div class="td-pb-row">
			<div class="td-pb-span8 td-main-content" role="main">
				<div class="td-ss-main-content">
					<div class="clearfix"></div>
								
	<article class="post type-post status-publish format-standard has-post-thumbnail hentry category-featured category-grid-1 category-grid-2 category-grid-3 category-grid-4 category-grid-5 category-grid-6 category-grid-7 category-grid-8 category-tagdiv-health-fitness category-module-1-layout category-module-10-layout category-module-11-layout category-module-12-layout category-module-13-layout category-module-14-layout category-module-15-layout category-module-16-layout category-module-2-layout category-module-3-layout category-module-4-layout category-module-5-layout category-module-6-layout category-module-7-layout category-module-8-layout category-module-9-layout category-no-grid category-template-style-1 category-template-style-2 category-template-style-3 category-template-style-4 category-template-style-5 category-template-style-6 category-template-style-7 category-template-style-8" itemscope="" itemtype="http://schema.org/Article">
		<div class="td-post-header">
			<ul class="td-category">
				<li class="entry-category"><a style="background-color:#a444bd;" href="tagdiv-lifestyle/">Lifestyle</a></li><li class="entry-category"><a style="background-color:#3fbcd5;" href="http://demo.tagdiv.com/newspaper/category/tagdiv-lifestyle/tagdiv-health-fitness/">Health &amp; Fitness</a></li>
			</ul>
			<header class="td-post-title">
				<h1 class="entry-title"><?= $model['title'] ?></h1>
				<div class="td-module-meta-info">
					<div class="td-post-author-name">By <a itemprop="author" href="#"><?= $model['author']['name'] ?></a> - </div>
					<div class="td-post-date"><time itemprop="dateCreated" class="entry-date updated td-module-date" datetime="<?= date('Y-m-d\TH:i:s', strtotime($model['online_from'])) ?>+00:00"><?= date_format(date_timezone_set(date_create($model['online_from']), timezone_open('Asia/Saigon')), 'j/n/Y H:i') ?></time><meta itemprop="interactionCount" content="UserComments:5"></div>
					<div class="td-post-comments"><a href="#comments"><i class="td-icon-comments"></i><?= $model['comment_count'] ?></a></div>
					<div class="td-post-views"><i class="td-icon-views"></i><span class="td-nr-views-149"><?= $model['hits'] ?></span></div>
				</div>
			</header>
		</div>
		<!--div class="td-post-sharing td-post-sharing-top ">
			<div class="td-default-sharing">
				<a class="td-social-sharing-buttons td-social-facebook" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fdemo.tagdiv.com%2Fnewspaper%2F120-er-visits-linked-synthetic-wordpress-nyc-past-week%2F" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="td-icon-facebook"></i><div class="td-social-but-text">Share on Facebook</div></a>
				<a class="td-social-sharing-buttons td-social-twitter" href="https://twitter.com/intent/tweet?text=More+Than+120+ER+Visits+Linked+To+Synthetic+WordPress+In+NYC+Over+Past+Week&amp;url=http%3A%2F%2Fdemo.tagdiv.com%2Fnewspaper%2F120-er-visits-linked-synthetic-wordpress-nyc-past-week%2F&amp;via=Newspaper+6"><i class="td-icon-twitter"></i><div class="td-social-but-text">Tweet on Twitter</div></a>
				<a class="td-social-sharing-buttons td-social-google" href="http://plus.google.com/share?url=http://demo.tagdiv.com/newspaper/120-er-visits-linked-synthetic-wordpress-nyc-past-week/" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="td-icon-googleplus"></i></a>
				<a class="td-social-sharing-buttons td-social-pinterest" href="http://pinterest.com/pin/create/button/?url=http://demo.tagdiv.com/newspaper/120-er-visits-linked-synthetic-wordpress-nyc-past-week/&amp;media=http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/63.jpg" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="td-icon-pinterest"></i></a>
			</div>
		</div-->

		<div class="td-post-content">
			<? if ($model['image'] == 'xxx') { ?>
			<div class="td-post-featured-image"><a href="" data-caption="" class="td-modal-image"><img width="696" height="464" itemprop="image" class="entry-thumb td-animation-stack-type0-1" src="<?= str_replace('http://', 'https://', $model['image']) ?>" alt="" title=""></a></div>
			<? } ?>
			<?= $model['body'] ?>
		</div>
<footer>
	<!--div class="td-post-source-tags">
		<div class="td-post-source-via ">
			<div class="td-post-small-box"><span>VIA</span><a rel="nofollow" href="#">Google</a></div>
			<div class="td-post-small-box"><span>SOURCE</span><a rel="nofollow" href="#">VH Magazine</a></div>
		</div>
		<ul class="td-tags td-post-small-box clearfix">
			<li><span>TAGS</span></li>
			<li><a href="#tag/art/">art</a></li><li><a href="#tag/cool/">cool</a></li>
			<li><a href="#tag/design/">design</a></li>
			<li><a href="#tag/tutorials/">tutorials</a></li>
			<li><a href="#tag/women/">women</a></li><li><a href="#tag/wordpress/">wordpress</a></li>
		</ul>
	</div>
	<div class="td-post-sharing td-post-sharing-bottom td-with-like"><span class="td-post-share-title">SHARE</span>
		<div class="td-default-sharing">
			<a class="td-social-sharing-buttons td-social-facebook" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fdemo.tagdiv.com%2Fnewspaper%2F120-er-visits-linked-synthetic-wordpress-nyc-past-week%2F" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="td-icon-facebook"></i><div class="td-social-but-text">Facebook</div></a>
			<a class="td-social-sharing-buttons td-social-twitter" href="https://twitter.com/intent/tweet?text=More+Than+120+ER+Visits+Linked+To+Synthetic+WordPress+In+NYC+Over+Past+Week&amp;url=http%3A%2F%2Fdemo.tagdiv.com%2Fnewspaper%2F120-er-visits-linked-synthetic-wordpress-nyc-past-week%2F&amp;via=Newspaper+6"><i class="td-icon-twitter"></i><div class="td-social-but-text">Twitter</div></a>
			<a class="td-social-sharing-buttons td-social-google" href="http://plus.google.com/share?url=http://demo.tagdiv.com/newspaper/120-er-visits-linked-synthetic-wordpress-nyc-past-week/" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="td-icon-googleplus"></i></a>
			<a class="td-social-sharing-buttons td-social-pinterest" href="http://pinterest.com/pin/create/button/?url=http://demo.tagdiv.com/newspaper/120-er-visits-linked-synthetic-wordpress-nyc-past-week/&amp;media=http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/63.jpg" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="td-icon-pinterest"></i></a>
		</div>
		<div class="td-classic-sharing">
			<ul>
				<li class="td-classic-facebook"><iframe frameborder="0" src="http://www.facebook.com/plugins/like.php?href=http://demo.tagdiv.com/newspaper/120-er-visits-linked-synthetic-wordpress-nyc-past-week/&amp;layout=button_count&amp;show_faces=false&amp;width=105&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; width:105px; height:21px; background-color:transparent;"></iframe></li>
				<li class="td-classic-twitter"><iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/tweet_button.bd0320cab493e168513c7173184c1d5c.en.html#_=1439483355843&amp;count=horizontal&amp;dnt=false&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=http%3A%2F%2Fdemo.tagdiv.com%2Fnewspaper%2F120-er-visits-linked-synthetic-wordpress-nyc-past-week%2F&amp;size=m&amp;text=More%20Than%20120%20ER%20Visits%20Linked%20To%20Synthetic%20WordPress%20In%20NYC%20Over%20Past%20Week&amp;url=http%3A%2F%2Fdemo.tagdiv.com%2Fnewspaper%2F120-er-visits-linked-synthetic-wordpress-nyc-past-week%2F" class="twitter-share-button twitter-tweet-button twitter-share-button twitter-count-horizontal" title="Twitter Tweet Button" data-twttr-rendered="true" style="position: static; visibility: visible; width: 78px; height: 20px;"></iframe>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
			</ul>
		</div>
	</div-->
	<div style="border-top:1px dotted #ddd; height:1em;"></div>
	<div class="td-block-row td-post-next-prev">
	<div class="td-block-span6 td-post-prev-post">
	<div class="td-post-next-prev-content"><span>Bài cũ hơn</span><a href="#post-26-most-anticipated-hotel-openings-in-2016/">Most Anticipated Hotel Openings in Canary Islands 2016</a></div>
	</div>
	<div class="td-next-prev-separator"></div>
	<div class="td-block-span6 td-post-next-post">
	<div class="td-post-next-prev-content"><span>Bài mới hơn</span><a href="#post-chilled-pea-soup-with-fried-ham-croquettes-recipe/">Chilled pea soup with fried ham croquettes recipe</a></div>
	</div>
	</div>
	<div class="author-box-wrap"><a itemprop="author" href="<?= DIR ?>blog/author/admin/"><img src="<?= $model['author']['image'] ?>" width="96" height="96" alt="<?= $model['author']['name'] ?>" class="avatar avatar-96 wp-user-avatar wp-user-avatar-96 alignnone photo td-animation-stack-type0-1"></a>
	<div class="desc">
		<div class="td-author-name vcard author"><span class="fn"><a itemprop="author" href="<?= DIR ?>blog/author/admin/"><?= $model['author']['name'] ?></a></span></div>
		<!--div class="td-author-url"><a href="http://www.armin-vans.com">http://www.armin-vans.com</a></div-->
		<div class="td-author-description"><?= nl2br($model['author']['profileMember']['intro']) ?></div>
		<div class="td-author-social"><span class="td-social-icon-wrap"><a target="_blank" href="#" title="Facebook"><i class="td-icon-font td-icon-facebook"></i></a></span><span class="td-social-icon-wrap"><a target="_blank" href="http://twitter.com/#" title="Twitter"><i class="td-icon-font td-icon-twitter"></i></a></span><span class="td-social-icon-wrap"><a target="_blank" href="#" title="Vimeo"><i class="td-icon-font td-icon-vimeo"></i></a></span><span class="td-social-icon-wrap"><a target="_blank" href="#" title="VKontakte"><i class="td-icon-font td-icon-vk"></i></a></span><span class="td-social-icon-wrap"><a target="_blank" href="#" title="Youtube"><i class="td-icon-font td-icon-youtube"></i></a></span></div><div class="clearfix"></div></div></div>			<meta itemprop="author" content="Armin Vans"><meta itemprop="datePublished" content="2015-02-22T05:58:41+00:00"><meta itemprop="headline " content="More Than 120 ER Visits Linked To Synthetic WordPress In NYC Over Past Week"><meta itemprop="image" content="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/63.jpg"><meta itemprop="interactionCount" content="UserComments:5">
</footer>
	</article> <!-- /.post -->

	<script>var block_td_uid_15_55cae9b088597 = new td_block();
block_td_uid_15_55cae9b088597.id = "td_uid_15_55cae9b088597";
block_td_uid_15_55cae9b088597.atts = '{"limit":3,"ajax_pagination":"next_prev","live_filter":"cur_post_same_categories","td_ajax_filter_type":"td_custom_related","class":"td_block_id_203950561 td_uid_15_55cae9b088597_rand","td_column_number":3,"live_filter_cur_post_id":149,"live_filter_cur_post_author":"1"}';
block_td_uid_15_55cae9b088597.td_column_number = "3";
block_td_uid_15_55cae9b088597.block_type = "td_block_related_posts";
block_td_uid_15_55cae9b088597.post_count = "3";
block_td_uid_15_55cae9b088597.found_posts = "68";
block_td_uid_15_55cae9b088597.header_color = "";
block_td_uid_15_55cae9b088597.ajax_pagination_infinite_stop = "";
block_td_uid_15_55cae9b088597.max_num_pages = "23";
td_blocks.push(block_td_uid_15_55cae9b088597);
</script><div class="td_block_wrap td_block_related_posts td_block_id_203950561 td_uid_15_55cae9b088597_rand td_with_ajax_pagination td-pb-border-top"><h4 class="td-related-title"><a id="td_uid_16_55cae9b088b9a" class="td-related-left td-cur-simple-item" data-td_filter_value="" data-td_block_id="td_uid_15_55cae9b088597" href="#">BÀI LIÊN QUAN</a><a id="td_uid_17_55cae9b088bd3" class="td-related-right" data-td_filter_value="td_related_more_from_author" data-td_block_id="td_uid_15_55cae9b088597" href="#">CÙNG TÁC GIẢ</a></h4><div id="td_uid_15_55cae9b088597" class="td_block_inner">

	<div class="td-related-row">

	<div class="td-related-span4">

		<div class="td_module_related_posts td-animation-stack td_mod_related_posts">
			<div class="td-module-image">
				<div class="td-module-thumb"><a href="#post-five-things-you-may-have-missed-over-the-weekend-from-the-world-of-business/" rel="bookmark" title="Five things you may have missed over the weekend from the world of business"><img width="218" height="150" itemprop="image" class="entry-thumb td-animation-stack-type0-1" src="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/67-218x150.jpg" alt="" title="Five things you may have missed over the weekend from the world of business"></a></div>				<a href="http://demo.tagdiv.com/newspaper/category/tagdiv-lifestyle/tagdiv-business/" class="td-post-category">Business</a>			</div>
			<div class="item-details">
				<h3 itemprop="name" class="entry-title td-module-title"><a itemprop="url" href="#post-five-things-you-may-have-missed-over-the-weekend-from-the-world-of-business/" rel="bookmark" title="Five things you may have missed over the weekend from the world of business">Five things you may have missed over the weekend from the world of business</a></h3>			</div>
		</div>
		
	</div> <!-- ./td-related-span4 -->

	<div class="td-related-span4">

		<div class="td_module_related_posts td-animation-stack td_mod_related_posts">
			<div class="td-module-image">
				<div class="td-module-thumb"><a href="#post-express-recipes-how-to-make-creamy-papaya-raita/" rel="bookmark" title="Express Recipes: How to make Creamy Papaya Raita"><img width="218" height="150" itemprop="image" class="entry-thumb td-animation-stack-type0-1" src="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/49-218x150.jpg" alt="" title="Express Recipes: How to make Creamy Papaya Raita"></a></div>				<a href="http://demo.tagdiv.com/newspaper/category/tagdiv-lifestyle/" class="td-post-category">Lifestyle</a>			</div>
			<div class="item-details">
				<h3 itemprop="name" class="entry-title td-module-title"><a itemprop="url" href="#post-express-recipes-how-to-make-creamy-papaya-raita/" rel="bookmark" title="Express Recipes: How to make Creamy Papaya Raita">Express Recipes: How to make Creamy Papaya Raita</a></h3>			</div>
		</div>
		
	</div> <!-- ./td-related-span4 -->

	<div class="td-related-span4">

		<div class="td_module_related_posts td-animation-stack td_mod_related_posts">
			<div class="td-module-image">
				<div class="td-module-thumb"><a href="#post-springfest-one-fashion-show-at-the-university-of-michigan/" rel="bookmark" title="SpringFest One Fashion Show at the University of Michigan"><img width="218" height="150" itemprop="image" class="entry-thumb td-animation-stack-type0-1" src="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/9-218x150.jpg" alt="" title="SpringFest One Fashion Show at the University of Michigan"></a></div>				<a href="http://demo.tagdiv.com/newspaper/category/tagdiv-fashion/tagdiv-new-look-2015/" class="td-post-category">New look 2015</a>			</div>
			<div class="item-details">
				<h3 itemprop="name" class="entry-title td-module-title"><a itemprop="url" href="#post-springfest-one-fashion-show-at-the-university-of-michigan/" rel="bookmark" title="SpringFest One Fashion Show at the University of Michigan">SpringFest One Fashion Show at the University of Michigan</a></h3>			</div>
		</div>
		
	</div> <!-- ./td-related-span4 --></div><!--./row-fluid--></div><div class="td-next-prev-wrap"><a href="#" class="td-ajax-prev-page ajax-page-disabled" id="prev-page-td_uid_15_55cae9b088597" data-td_block_id="td_uid_15_55cae9b088597"><i class="td-icon-font td-icon-menu-left"></i></a><a href="#" class="td-ajax-next-page" id="next-page-td_uid_15_55cae9b088597" data-td_block_id="td_uid_15_55cae9b088597"><i class="td-icon-font td-icon-menu-right"></i></a></div></div> <!-- ./block -->

	<div class="comments" id="comments">
		<div class="td-comments-title-wrap ">
			<h4 class="block-title"><span><?= $model['comment_count'] ?> COMMENTS</span></h4>
		</div>
		<ol class="comment-list ">
			<? foreach ($model['comments'] as $comment) { ?>
			<li class="comment " id="li-comment-<?= $comment['id'] ?>">
				<article>
					<footer>
						<img src="<?= $comment['createdBy']['image'] ?>" width="50" height="50" alt="<?= $comment['createdBy']['name'] ?>" class="avatar avatar-50 wp-user-avatar wp-user-avatar-50 alignnone photo">
						<cite><a href="/org/members/r/<?= $comment['createdBy']['id'] ?>" rel="external nofollow" class="url"><?= $comment['createdBy']['name'] ?></a></cite>
						<a class="comment-link" href="#li-comment-<?= $comment['id'] ?>">
							<time pubdate="<?= strtotime($comment['created_at']) ?>"><?= date('j/n/Y \l\ú\c H\hi', strtotime($comment['created_at'])) ?></time>
						</a>
					</footer>
					<div class="comment-content">
						<p><?= nl2br($comment['body']) ?></p>
					</div>
				</article>
			</li><!-- #comment-## -->
			<? } ?>
		</ol>
		<div class="comment-pagination"></div>
		<div id="respond" class="comment-respond">
			<h3 id="reply-title" class="comment-reply-title">LEAVE A REPLY <small><a rel="nofollow" id="cancel-comment-reply-link" href="/newspaper/120-er-visits-linked-synthetic-wordpress-nyc-past-week/#respond" style="display:none;">Cancel reply</a></small></h3>
			<p class="must-log-in">You must be <a class="td-login-modal-js" data-effect="mpf-td-login-effect" href="#login-form">logged in </a>to post a comment.</p>
		</div><!-- #respond -->
	</div> <!-- /.content -->
	<div class="clearfix"></div>
</div>
	</div>
	<div class="td-pb-span4 td-main-sidebar" role="complementary">
		<div class="td-ss-main-sidebar">
			<div class="clearfix"></div>
				<div class="td-adspot-title">- Advertisement -</div>
				<div class="td-a-rec td-a-rec-id-sidebar ">
					<div class="td-visible-desktop">
						<a href="https://www.amica-travel.com"><img class="td-retina" style="width: 300px;" src="https://www.amica-travel.com/upload/ideas/tours/formules-originales/immersion-chez-les-lu-ma/2.jpg" alt="" height="300" width="250"></a>
					</div>
					<div class="td-visible-tablet-landscape">
						<a href="https://www.amica-travel.com"><img class="td-retina" style="width: 300px;" src="https://www.amica-travel.com/upload/ideas/tours/formules-originales/immersion-chez-les-lu-ma/2.jpg" alt="" height="300" width="250"></a>
					</div>
					<div class="td-visible-tablet-portrait">
						<a href="https://www.amica-travel.com"><img class="td-retina" style="width: 200px;" src="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/rec200.jpg" alt="" height="200" width="200"></a>
					</div>
					<div class="td-visible-phone">
						<a href="https://www.amica-travel.com"><img class="td-retina" style="width: 300px;" src="https://www.amica-travel.com/upload/ideas/tours/formules-originales/immersion-chez-les-lu-ma/2.jpg" alt="" height="300" width="250"></a>
					</div>
				</div>
				<script>var block_td_uid_18_55cae9b08c0d1 = new td_block();
block_td_uid_18_55cae9b08c0d1.id = "td_uid_18_55cae9b08c0d1";
block_td_uid_18_55cae9b08c0d1.atts = '{"category_id":"","category_ids":"","tag_slug":"","autors_id":"","sort":"popular","installed_post_types":"","limit":"4","offset":"","custom_title":"MUST READ","custom_url":"","header_text_color":"#","header_color":"#","td_ajax_filter_type":"","td_ajax_filter_ids":"","td_filter_default_txt":"All","ajax_pagination":"next_prev","ajax_pagination_infinite_stop":"","class":"td_block_widget td_block_id_719879962 td_uid_18_55cae9b08c0d1_rand"}';
block_td_uid_18_55cae9b08c0d1.td_column_number = "1";
block_td_uid_18_55cae9b08c0d1.block_type = "td_block_1";
block_td_uid_18_55cae9b08c0d1.post_count = "4";
block_td_uid_18_55cae9b08c0d1.found_posts = "160";
block_td_uid_18_55cae9b08c0d1.header_color = "#";
block_td_uid_18_55cae9b08c0d1.ajax_pagination_infinite_stop = "";
block_td_uid_18_55cae9b08c0d1.max_num_pages = "40";
td_blocks.push(block_td_uid_18_55cae9b08c0d1);
				</script>
<div class="td_block_wrap td_block_1 td_block_widget td_block_id_719879962 td_uid_18_55cae9b08c0d1_rand td_with_ajax_pagination td-pb-border-top"><h4 class="block-title"><span>NỔI BẬT</span></h4><div id="td_uid_18_55cae9b08c0d1" class="td_block_inner">
	<div class="td-block-span12">
		<div class="td_module_4 td_module_wrap td-animation-stack" itemscope="" itemtype="http://schema.org/Article">
			<div class="td-module-image">
				<div class="td-module-thumb">
					<a href="" rel="bookmark" title="XXX"><img width="324" height="235" itemprop="image" class="entry-thumb td-animation-stack-type0-1" src="https://www.amica-travel.com/upload/voyage-solidaire/voyage-solidaire-vietnam-vitam%20(13).JPG" alt="" title="3 Four-Star Resorts Bolster Costa Rica’s Luxury Reputation"></a></div>
					<a href="#" class="td-post-category">Travel</a>
				</div>

			<h3 itemprop="name" class="entry-title td-module-title"><a itemprop="url" href="" rel="bookmark" title="">Thăm Hội VITAM ở Nam Định nhân kỉ niệm 3 năm thành lập</a></h3>
			<div class="td-module-meta-info">
				<div class="td-post-author-name"><a itemprop="author" href="http://demo.tagdiv.com/newspaper/author/marius/">John Doe</a> <span>-</span> </div>				<div class="td-post-date"><time itemprop="dateCreated" class="entry-date updated td-module-date" datetime="2015-03-22T05:47:34+00:00">Mar 22, 2015</time><meta itemprop="interactionCount" content="UserComments:0"></div>				<div class="td-module-comments"><a href="#comments">0</a></div>			</div>

			<div class="td-excerpt">
				The model is talking about booking her latest gig, modeling WordPress underwear in the brand latest Perfectly Fit campaign, which was shot by Lachian...			</div>
			
			<meta itemprop="author" content="John Doe"><meta itemprop="datePublished" content="2015-03-22T05:47:34+00:00"><meta itemprop="headline " content="3 Four-Star Resorts Bolster Costa Rica's Luxury Reputation"><meta itemprop="image" content="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/51.jpg"><meta itemprop="interactionCount" content="UserComments:0">
		</div>

		
	</div> <!-- ./td-block-span12 -->

	<? foreach ($latestPosts as $post) { ?>
	<div class="td-block-span12">
		<div class="td_module_6 td_module_wrap td-animation-stack" itemscope="" itemtype="http://schema.org/Article">
			<div class="td-module-thumb"><a href="<?= DIR ?>blog/posts/r/<?= $post['id'] ?>" rel="bookmark" title="<?= $post['title'] ?>"><img width="100" height="70" itemprop="image" class="entry-thumb td-animation-stack-type0-1" src="<?= DIR ?>timthumb.php?src=<?= $post['image'] == '' ? 'assets/img/logo.png' : str_replace('http:', 'https:', $post['image']) ?>&w=100&h=70" alt="" title="<?= $post['title'] ?>"></a></div>
			<div class="item-details">
				<h3 itemprop="name" class="entry-title td-module-title"><a itemprop="url" href="<?= DIR.'blog/posts/r/'.$post['id'] ?>" rel="bookmark" title="<?= $post['title'] ?>"><?= $post['title'] ?></a></h3>
				<div class="td-module-meta-info">
				<div class="td-post-date"><time itemprop="dateCreated" class="entry-date updated td-module-date" datetime="2015-01-22T05:44:07+00:00"><?= date('j/n/Y', strtotime($post['online_from'])) ?></time><meta itemprop="interactionCount" content="UserComments:1"></div>							</div>
			</div>
			<meta itemprop="author" content="<?= $post['author']['name'] ?>"><meta itemprop="datePublished" content="2015-01-22T05:44:07+00:00"><meta itemprop="headline " content="Zeta Architecture: Hexagon is the new circle in 2016"><meta itemprop="image" content="http://demo.tagdiv.com/newspaper/wp-content/uploads/2015/04/114.jpg"><meta itemprop="interactionCount" content="UserComments:1">
		</div>
	</div> <!-- ./td-block-span12 -->
	<? } ?>
</div>
						<div class="td-next-prev-wrap">
							<a href="#" class="td-ajax-prev-page ajax-page-disabled" id="prev-page-td_uid_18_55cae9b08c0d1" data-td_block_id="td_uid_18_55cae9b08c0d1"><i class="td-icon-font td-icon-menu-left"></i></a>
							<a href="#" class="td-ajax-next-page" id="next-page-td_uid_18_55cae9b08c0d1" data-td_block_id="td_uid_18_55cae9b08c0d1"><i class="td-icon-font td-icon-menu-right"></i></a>
						</div>
					</div> <!-- ./block -->
					<div class="clearfix"></div>
				</div>
			</div>
		</div> <!-- /.td-pb-row -->
	</div> <!-- /.td-container -->
</div>