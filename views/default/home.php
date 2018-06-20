<?
use yii\helpers\Html;
Yii::$app->params['page_title'] = 'Welcome back, '.Yii::$app->user->identity->nickname;
Yii::$app->params['page_icon'] = 'dashboard';
Yii::$app->params['page_breadcrumbs'] = [];
Yii::$app->params['page_layout'] = '-b -h';
?>
<style>
.task-overdue .task-date {background-color:#fcc; padding:0 4px;}
.task-today {background-color:#ffc; padding:0 4px;}
</style>
<? if (USER_ID == 1) { ?>
<?
$sql = 'select count(*) from at_bookings where status="won" AND status_dt>=:last7';
$count1 = \Yii::$app->db->createCommand($sql, [':last7'=>date('Y-m-d', strtotime('-7 days'))])->queryScalar();

$sql2 = 'SELECT code FROM at_tours WHERE status!="deleted" ORDER BY id DESC LIMIT 1';
$count2 = \Yii::$app->db->createCommand($sql2)->queryScalar();

?>
<div class="col-md-12">
    <div class="col-md-3">
        <div class="panel bg-orange-400">
            <div class="panel-body">
                <div class="heading-elements">
                    <span class="heading-text badge bg-orange-800">+53,6%</span>
                </div>
                <h3 class="no-margin"><?= number_format($count1) ?></h3>
                new confirmed tours
                <div class="text-muted text-size-small">in the last 7 days</div>
            </div>
            <div class="container-fluid">
                <div id="members-online"><svg width="176.88125610351562" height="50"><g width="176.88125610351562"><rect class="d3-random-bars" width="5.0953448260271985" x="2.183719211154514" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="9.462783248336226" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="16.741847285517938" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="24.020911322699654" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="31.299975359881365" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="38.57903939706308" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="45.85810343424479" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="53.1371674714265" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="60.41623150860821" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="67.69529554578993" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="74.97435958297164" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="82.25342362015336" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="89.53248765733507" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="96.81155169451678" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="104.09061573169849" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="111.3696797688802" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="118.64874380606192" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="125.92780784324363" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="133.20687188042535" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="140.48593591760707" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="147.76499995478878" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="155.0440639919705" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="162.3231280291522" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="169.6021920663339" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect></g></svg></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    <!-- Bán nhiều tour nhất trong 30 ngày qua: -->
        <div class="panel bg-blue-400">
            <div class="panel-body">
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><i class="fa fa-car"></i></li>
                    </ul>
                </div>
                <h3 class="no-margin"><?= $count2 ?></h3>
                Latest confirmed tour
                <div class="text-muted text-size-small">by A Great Consultant</div>
            </div>
            <div id="today-revenue"><svg width="196.88125610351562" height="50"><g transform="translate(0,0)" width="196.88125610351562"><defs><clipPath id="clip-line-small"><rect class="clip" width="196.88125610351562" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L46.14687601725261,25.76923076923077L72.29375203450522,5L98.44062805175781,15.384615384615383L124.58750406901042,5L150.73438008626303,36.15384615384615L176.88125610351562,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="46.14687601725261" y1="50" x2="46.14687601725261" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="72.29375203450522" y1="50" x2="72.29375203450522" y2="5" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="98.44062805175781" y1="50" x2="98.44062805175781" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="124.58750406901042" y1="50" x2="124.58750406901042" y2="5" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="150.73438008626303" y1="50" x2="150.73438008626303" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="176.88125610351562" y1="50" x2="176.88125610351562" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="46.14687601725261" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="72.29375203450522" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="98.44062805175781" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="124.58750406901042" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="150.73438008626303" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="176.88125610351562" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
        </div>        
    </div>
    <div class="col-md-3">
        <div class="panel bg-violet-400">
            <div class="panel-body">
                <div class="heading-elements">
                    <span class="heading-text badge bg-violet-800">+53,6%</span>
                </div>
                <h3 class="no-margin">3,450</h3>
                Members online
                <div class="text-muted text-size-small">489 avg</div>
            </div>
            <div class="container-fluid">
                <div id="members-online"><svg width="176.88125610351562" height="50"><g width="176.88125610351562"><rect class="d3-random-bars" width="5.0953448260271985" x="2.183719211154514" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="9.462783248336226" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="16.741847285517938" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="24.020911322699654" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="31.299975359881365" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="38.57903939706308" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="45.85810343424479" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="53.1371674714265" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="60.41623150860821" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="67.69529554578993" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="74.97435958297164" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="82.25342362015336" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="89.53248765733507" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="96.81155169451678" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="104.09061573169849" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="111.3696797688802" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="118.64874380606192" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="125.92780784324363" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="133.20687188042535" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="140.48593591760707" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="147.76499995478878" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="155.0440639919705" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="162.3231280291522" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="169.6021920663339" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect></g></svg></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel bg-pink-400">
            <div class="panel-body">
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog3"></i> <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#"><i class="icon-sync"></i> Update data</a></li>
                                <li><a href="#"><i class="icon-list-unordered"></i> Detailed log</a></li>
                                <li><a href="#"><i class="icon-pie5"></i> Statistics</a></li>
                                <li><a href="#"><i class="icon-cross3"></i> Clear list</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <h3 class="no-margin">49.4%</h3>
                Current server load
                <div class="text-muted text-size-small">34.6% avg</div>
            </div>

            <div id="server-load"><svg width="196.88125610351562" height="50"><g transform="translate(0,0)" width="196.88125610351562"><defs><clipPath id="load-clip-server-load"><rect class="load-clip" width="196.88125610351562" height="50"></rect></clipPath></defs><g clip-path="url(#load-clip-server-load)"><path d="M-7.572356003981371,5L-6.310296669984475,9.444444444444445C-5.04823733598758,13.88888888888889,-2.52411866799379,22.777777777777782,0,24.77777777777778C2.52411866799379,26.77777777777778,5.04823733598758,21.88888888888889,7.572356003981371,22.11111111111111C10.09647467197516,22.333333333333332,12.62059333996895,27.666666666666664,15.14471200796274,30.555555555555554C17.66883067595653,33.44444444444444,20.19294934395032,33.888888888888886,22.71706801194411,30.11111111111111C25.2411866799379,26.333333333333332,27.76530534793169,18.333333333333332,30.28942401592548,17.22222222222222C32.81354268391927,16.111111111111107,35.33766135191306,21.888888888888886,37.86178001990685,26.111111111111107C40.38589868790064,30.33333333333333,42.91001735589443,33,45.43413602388822,32.11111111111111C47.958254691882004,31.222222222222218,50.482373359875794,26.777777777777775,53.006492027869584,21.888888888888886C55.53061069586337,17,58.05472936385717,11.666666666666664,60.57884803185096,11.222222222222221C63.10296669984476,10.777777777777777,65.62708536783855,15.222222222222221,68.15120403583234,17.444444444444443C70.67532270382613,19.666666666666664,73.19944137181992,19.666666666666664,75.7235600398137,16.333333333333336C78.2476787078075,13,80.77179737580128,6.333333333333334,83.29591604379507,5.888888888888889C85.82003471178886,5.444444444444445,88.34415337978265,11.222222222222221,90.86827204777643,13.888888888888888C93.39239071577023,16.555555555555554,95.91650938376401,16.11111111111111,98.4406280517578,18.333333333333336C100.96474671975159,20.555555555555557,103.48886538774538,25.444444444444443,106.01298405573917,28.11111111111111C108.53710272373296,30.77777777777778,111.06122139172675,31.222222222222225,113.58534005972054,27.888888888888893C116.10945872771433,24.555555555555557,118.63357739570813,17.444444444444443,121.1576960637019,16.77777777777778C123.68181473169571,16.111111111111107,126.20593339968948,21.888888888888886,128.7300520676833,20.33333333333333C131.25417073567706,18.777777777777775,133.77828940367087,9.888888888888886,136.30240807166467,8.777777777777775C138.82652673965845,7.666666666666664,141.35064540765222,14.333333333333332,143.87476407564603,14.333333333333332C146.3988827436398,14.333333333333332,148.9230014116336,7.666666666666664,151.44712007962738,7.888888888888886C153.9712387476212,8.111111111111107,156.49535741561496,15.22222222222222,159.01947608360877,20.333333333333332C161.54359475160254,25.444444444444443,164.06771341959634,28.555555555555557,166.59183208759015,30.111111111111114C169.11595075558392,31.66666666666667,171.6400694235777,31.66666666666667,174.1641880915715,31.000000000000004C176.68830675956528,30.333333333333336,179.21242542755908,29,181.73654409555286,27.666666666666664C184.2606627635467,26.333333333333332,186.78478143154047,25,189.30890009953424,25C191.83301876752805,25,194.35713743552182,26.333333333333332,196.8812561035156,28.33333333333333C199.4053747715094,30.33333333333333,201.9294934395032,33,204.45361210749698,29.888888888888886C206.97773077549078,26.777777777777775,209.50184944348456,17.888888888888886,210.76390877748145,13.444444444444443L212.02596811147836,9L212.02596811147836,50L210.76390877748145,49.999999999999986C209.50184944348456,49.99999999999999,206.97773077549078,49.99999999999999,204.453612107497,49.999999999999986C201.9294934395032,49.99999999999999,199.4053747715094,49.99999999999999,196.88125610351562,49.999999999999986C194.35713743552182,49.99999999999999,191.83301876752805,49.99999999999999,189.30890009953424,49.999999999999986C186.78478143154047,49.99999999999999,184.2606627635467,49.99999999999999,181.7365440955529,49.999999999999986C179.21242542755908,49.99999999999999,176.68830675956528,49.99999999999999,174.1641880915715,49.999999999999986C171.6400694235777,49.99999999999999,169.11595075558392,49.99999999999999,166.59183208759012,49.999999999999986C164.06771341959634,49.99999999999999,161.54359475160254,49.99999999999999,159.01947608360877,49.999999999999986C156.49535741561496,49.99999999999999,153.9712387476212,49.99999999999999,151.4471200796274,49.999999999999986C148.9230014116336,49.99999999999999,146.3988827436398,49.99999999999999,143.87476407564603,49.999999999999986C141.35064540765222,49.99999999999999,138.82652673965845,49.99999999999999,136.30240807166464,49.999999999999986C133.77828940367087,49.99999999999999,131.25417073567706,49.99999999999999,128.7300520676833,49.999999999999986C126.20593339968948,49.99999999999999,123.68181473169571,49.99999999999999,121.15769606370193,49.999999999999986C118.63357739570813,49.99999999999999,116.10945872771433,49.99999999999999,113.58534005972054,49.999999999999986C111.06122139172675,49.99999999999999,108.53710272373296,49.99999999999999,106.01298405573917,49.999999999999986C103.48886538774538,49.99999999999999,100.96474671975159,49.99999999999999,98.4406280517578,49.999999999999986C95.91650938376401,49.99999999999999,93.39239071577023,49.99999999999999,90.86827204777644,49.999999999999986C88.34415337978265,49.99999999999999,85.82003471178886,49.99999999999999,83.29591604379507,49.999999999999986C80.77179737580128,49.99999999999999,78.2476787078075,49.99999999999999,75.7235600398137,49.999999999999986C73.19944137181992,49.99999999999999,70.67532270382613,49.99999999999999,68.15120403583234,49.999999999999986C65.62708536783855,49.99999999999999,63.10296669984476,49.99999999999999,60.57884803185096,49.999999999999986C58.05472936385717,49.99999999999999,55.53061069586337,49.99999999999999,53.006492027869584,49.999999999999986C50.482373359875794,49.99999999999999,47.958254691882004,49.99999999999999,45.43413602388822,49.999999999999986C42.91001735589443,49.99999999999999,40.38589868790064,49.99999999999999,37.86178001990685,49.999999999999986C35.33766135191306,49.99999999999999,32.81354268391927,49.99999999999999,30.28942401592548,49.999999999999986C27.76530534793169,49.99999999999999,25.2411866799379,49.99999999999999,22.71706801194411,49.999999999999986C20.19294934395032,49.99999999999999,17.66883067595653,49.99999999999999,15.14471200796274,49.999999999999986C12.62059333996895,49.99999999999999,10.09647467197516,49.99999999999999,7.572356003981371,49.999999999999986C5.04823733598758,49.99999999999999,2.52411866799379,49.99999999999999,0,49.999999999999986C-2.52411866799379,49.99999999999999,-5.04823733598758,49.99999999999999,-6.310296669984475,49.999999999999986L-7.572356003981371,50Z" class="d3-area" style="fill: rgba(255, 255, 255, 0.498039); opacity: 1;" transform="translate(-7.572356224060059,0)"></path></g></g></svg></div>
        </div>
    </div>
</div>
<? } ?>

<div class="col-lg-6 col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('View all', '@web/messages', ['class'=>'pull-right']) ?>
            <strong>LATEST MESSAGES & ACTIVITIES</strong>
        </div>
        <table class="table table-condensed">
            <? if (empty($theNotes)) { ?><tr><td>No data found.</td></tr><? } else { ?>
            <? foreach ($theNotes as $li) { ?>
            <tr><td>
                <?
                if ($li['from']['image'] != '') {
                    $avatar = DIR.'timthumb.php?w=100&h=100&zc=1&src='.$li['from']['image'];
                } else {
                    $avatar = 'https://secure.gravatar.com/avatar/'.md5($li['from']['id']).'.jpg?s=100&d=wavatar';
                }
                ?>
                <?= Html::img($avatar, ['style'=>'width:20px; height:20px;', 'class'=>'img-circle']) ?>
                <? if ($li['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
                <?= Html::a($li['from']['nickname'], '@web/users/r/'.$li['from']['id'], ['style'=>'color:#963']) ?>:
                <? if ($li['priority'] == 'B2') { ?><span class="label label-warning">Important</span><? } ?>
                <? if ($li['priority'] == 'C3') { ?><span class="label label-danger">Urgent</span><? } ?>
                <?= Html::a($li['title'] == '' ? '( No title )' : $li['title'], '@web/notes/r/'.$li['id']) ?>
                <?
                if (!empty($li['to'])) {
                    echo ' <i class="fa fa-caret-right text-muted"></i> ';
                    $toNameList = [];
                    foreach ($li['to'] as $to) {
                        $toNameList[] = '<span class="note-recipient-name">'.$to['nickname'].'</span>';
                    }
                    echo implode(', ', $toNameList);
                }
                ?>
                <?= $li['relatedCase'] && $li['rtype'] == 'case' ? ' # '.Html::a($li['relatedCase']['name'], '@web/cases/r/'.$li['relatedCase']['id'], ['style'=>'color:#060']) : ''?>
                <?= $li['relatedTour'] && $li['rtype'] == 'tour' ? ' # '.Html::a($li['relatedTour']['code'].' - '.$li['relatedTour']['name'], '@web/tours/r/'.$li['relatedTour']['id'], ['style'=>'color:#060']) : '' ?>
                <span class="text-muted"><?= $li['uo'] == $li['co'] ? '' : 'edited ' ?><?= Yii::$app->formatter->asRelativeTime($li['uo']) ?></span>
            </td></tr>
            <? } // foreach notes ?>
            <? } // if not empty ?>
        </table>
    </div>
</div>
<div class="col-lg-6 col-md-4">
    <div class="row">
        <div class="col-lg-6">
            <? if (in_array(Yii::$app->user->id, [1, 118]) && !empty($theTours)) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::a('View all', '@web/tours', ['class'=>'pull-right']) ?>
                    <strong>NEW TOURS</strong>
                </div>
                <table class="table table-condensed">
                    <? foreach ($theTours as $nt) { ?>
                    <tr>
                        <td>
                            (<?= Html::a('View', '@web/tours/r/'.$nt['id']) ?>)
                            <?= Html::a('<strong>'.$nt['code'].'</strong> '.$nt['pax'].'p '.$nt['day_count'].'d '.date('j/n', strtotime($nt['day_from'])), '@web/tours/accept/'.$nt['id']) ?>
                            by <?= Html::a($nt['se_name'], '@web/users/r/'.$nt['se'])?>
                            <span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($nt['uo']) ?></span>
                    </tr>
                    <? } ?>
                </table>
            </div>
            <? } // end if ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::a('View all', '@web/tasks', ['class'=>'pull-right']) ?>
                    <strong>MY TASKS</strong>
                </div>
                <table class="table table-condensed">
                    <? if (empty($theTasks)) { ?><tr><td>No tasks found.</td></tr><? } else { ?>
                    <?
                    $thisYear = date('Y');
                    $today = date('Y-m-d');
                    foreach ($theTasks as $t) {
                    ?>
                    <tr>
                        <td id="div-task-<?=$t['id']?>" class="task <?=$t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$t['status'] == 'off' ? 'task-done' : ''?>">
                        <i id="icon-<?=$t['id']?>" data-task_id="<?=$t['id']?>" class="fa fa-<?=$t['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
                        <?
                        if ($t['fuzzy'] == 'date') {
                            // Echo nuffin'
                        } else {
                            if (substr($t['due_dt'], 0, 4) == $thisYear) {
                                $dueDTDisplay = date('d-m', strtotime($t['due_dt']));
                            } else {
                                $dueDTDisplay = date('d-m-Y', strtotime($t['due_dt']));
                            }
                            if (substr($t['due_dt'], 0, 10) == $today) echo '<span class="task-today">Today</span> ';
                            echo '<span class="task-date">', $dueDTDisplay, '</span>';
                            if ($t['fuzzy'] == 'time') {
                                // Display nuffin
                            } else {
                                echo ' <span class="task-time">'.substr($t['due_dt'], 11, 5).'</span>';
                            }
                        }

                        ?>
                        <? if ($t['is_priority'] == 'yes') { ?><i style="color:#c00;" title="Priority" class="icon-asterisk"></i><? } ?>
                        <?=Yii::$app->user->id == 1 || $t['ub'] ? Html::a($t['description'], '@web/tasks/u/'.$t['id']) : $t['description']?>
                        <?= Html::a('<i class="fa fa-fw fa-link"></i>', DIR.$t['rtype'].'s/r/'.$t['rid'], ['class'=>'text-muted', 'title'=>'Link to '.$t['rtype']]) ?>
                        <? $cnt = 0; foreach ($theTaskUsers as $tu) { if ($tu['task_id'] == $t['id']) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['user_id']?>" class="text-muted <?=$tu['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done'?>" title="AS: <?=$tu['assigned_dt']?>"><?=$tu['user_id'] == Yii::$app->user->id ? 'Tôi' : $tu['user_name']?></span><? } } ?>
                        </td>
                    </tr>
                    <? } // foreach tasks ?>
                    <? } // if empty ?>
                </table>
            </div>
            <? if (!empty($newPayments)) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>RECENT TOUR PAYMENTS</strong>
                    <?= Html::a('View all', '@web/payments', ['class'=>'pull-right']) ?>
                </div>
                <table class="table table-condensed">
                    <? foreach ($newPayments as $payment) { ?>
                    <tr>
                        <td style="white-space:nowrap;">
                            <i class="fa fa-info-circle popovers pull-left text-muted"
                                data-trigger="hover"
                                data-title="<?= $payment['method'] ?>"
                                data-html="true"
                                data-content="<?= nl2br($payment['note']) ?><br>(By <?= $payment['updated'] ?>)"></i>
                            <strong><?= substr($payment['payment_dt'], 8, 2) ?></strong>
                            <small class="text-muted"><?= substr($payment['payment_dt'], 11, 5) ?></small>
                        </td>
                        <td width="75">
                            <?= Html::a($payment['tour_code'], '@web/tours/r/'.$payment['tour_id'], ['style'=>'background:#ffc; color:#148040; padding:0 3px; ']) ?>
                        </td>
                        <td title="Payer">
                            <?= $payment['payer'] ?>
                        </td>
                        <td class="text-right">
                            <?= number_format($payment['amount'], 0) ?>
                            <span class="text-muted"><?= $payment['currency'] ?></span>
                        </td>
                    </tr>
                    <? } ?>
                </table>
            </div>
            <? } //payments ?>

            <? if (!empty($absentPeople)) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>ON LEAVE TODAY</strong>
                    <?= Html::a('Calendar', '@web/calendar', ['class'=>'pull-right']) ?>
                </div>
                <table class="table table-condensed">
                    <? foreach ($absentPeople as $li) { ?>
                    <tr>
                        <td>
                            <?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$li['image'], ['style'=>'height:20px; width:20px;']) ?>
                            <?= Html::a ($li['name'], '@web/users/r/'.$li['id']) ?>
                            <?= $li['e_name'] ?>
                        </td>
                        <td>
                            <strong><?= date('d', strtotime($li['from_dt'])) ?></strong>
                            <small class="text-muted"><?= date('H:i', strtotime($li['from_dt'])) ?></small>
                            -
                            <? if (substr($li['from_dt'], 0, 10) != substr($li['until_dt'], 0, 10)) { ?><strong><?= date('d', strtotime($li['until_dt'])) ?></strong><? } ?>
                            <small class="text-muted"><?= date('H:i', strtotime($li['until_dt'])) ?></small>
                        </td>
                    </tr>
                    <? } ?>
                </table>
            </div>
            <? } //absent ?>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::a('Member list', '@web/kb/lists/members', ['class'=>'pull-right']) ?>
                    <strong>CURRENTLY ONLINE</strong>
                </div>
                <div class="panel-body">
                    <? foreach ($onlineUsers as $li) { ?>
                    <?= Html::a(Html::img(DIR.'timthumb.php?zc=1&w=100&h=100&src='.$li['image'], ['class'=>'img-circle', 'style'=>'width:48px; height:48px; float:left; display:block; margin:0 0 4px 4px;']), '@web/users/r/'.$li['id'], ['title'=>$li['nickname']]) ?>
                    <? } ?>
                </div>
            </div>
            <? if (app\helpers\User::inGroups('any:it,lanhdao,banhang')) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o"></i> <strong>SELLER'S REPORTS</strong>
                </div>
                <div class="panel-body">
                    <p>
                    <?= Html::a('Thống kê HS của tôi', '@web/me/reports', ['rel'=>'external']) ?>
                    &middot;
                    <?= Html::a('Tỉ lệ HS thành công chung', '@web/manager/sales-results', ['rel'=>'external']) ?>
                    &middot;
                    <?= Html::a('Tỉ lệ HS thành công theo nguồn khách', '@web/manager/sales-results-sources', ['rel'=>'external']) ?>
                    &middot;
                    <?= Html::a('Số lượng HS thành công theo tháng', '@web/manager/sales-results-changes', ['rel'=>'external']) ?>
                    &middot;
                    <?= Html::a('Số lượng HS giao người bán hàng các tháng', '@web/manager/sales-results-assignments?seller='.Yii::$app->user->id, ['rel'=>'external']) ?>
                    </p>
                    <select class="form-control" onchange="if ($(this).val() != 0) location.assign('https://my.amicatravel.com/manager/sales-results-seller?source=all&year=2014&seller=' + $(this).val());">
                        <option value="0">- Select to view a seller's report -</option>
                        <? foreach ($sellerList as $seller) { ?>
                        <option value="<?= $seller['id'] ?>"><?= $seller['lname'] ?>, <?= $seller['fname'] ?> (<?= $seller['email'] ?>)</option>
                        <? } ?>
                    </select>
                </div>
            </div>
            <? } ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>RECENTLY VIEWED ITEMS</strong>
                    <?= Html::a('View all', '@web/me/viewed', ['class'=>'pull-right']) ?>
                </div>
                <table class="table table-condensed">
                    <? foreach ($theViewedItems as $it) { ?>
                    <tr>
                        <td><?
                        if ($it['rtype'] == 'case') echo '<i class="text-muted fa fa-briefcase"></i> ';
                        if ($it['rtype'] == 'tour') echo '<i class="text-muted fa fa-truck"></i> ';
                        echo Html::a($it['name'], DIR.$it['rtype'].'s/r/'.$it['rid'], ['rel'=>'external']);
                        ?></td>
                    </tr>
                    <? } ?>
                </table>
            </div>

            <!--
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::a('View all', '@web/me/starred', ['class'=>'pull-right']) ?>
                    <strong>MY STARRED ITEMS</strong>
                </div>
                <table class="table table-condensed">
                    <? if (empty($theStarredItems)) { ?><tr><td>No items found</td></tr><? } ?>
                    <? foreach ($theStarredItems as $it) { ?>
                    <tr>
                        <td>
                            <?
                        if ($it['rtype'] == 'case') echo '<i class="text-muted fa fa-briefcase"></i> ';
                        if ($it['rtype'] == 'tour') echo '<i class="text-muted fa fa-truck"></i> ';
                        echo '<i class="fa fa-star text-warning"></i> ';
                        echo Html::a($it['name'], DIR.$it['rtype'].'s/r/'.$it['rid'], ['rel'=>'external']);
                            ?>
                        </td>
                    </tr>
                    <? } ?>
                </table>
            </div>
            -->
        </div>
    </div>
</div>
<?
$js = <<<TXT
/*
$('i.task-check').on('click', function(){
    var task_id = $(this).data('task_id');
    $.post('/tasks/ajax', {action:'check', task_id:task_id}, function(data){
        if (data.status) {
            if (data.status == 'OK') {
                $('span#assignee-' + task_id + '-' + '{myID}).toggleClass('done');
                $('i#icon-' + task_id).removeClass('icon-check').removeClass('icon-check-empty').addClass(data.icon);
                if (data.icon == 'icon-check') {
                    $('div#div-task-' + task_id).removeClass('task-overdue');
                }
            } else {
                alert(data.message);
            }
        } else {
            alert('Error: data error.');
        }
    }, 'json');
});

*/

TXT;
$this->registerJs(str_replace('{myID}', Yii::$app->user->id, $js));