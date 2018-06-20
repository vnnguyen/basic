<?php

use yii\helpers\Html;

Yii::$app->params['body_class'] = 'bg-white sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = 'B2B workspace';
Yii::$app->params['page_breadcrumbs'] = [
    ['B2B']
];

$sql = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND DATE_ADD(day_from, INTERVAL day_count DAY)>:this AND SUBSTRING(op_code,1,1)="G" ORDER BY day_from, id LIMIT 1000';
$todayTours = \common\models\Product::findBySql($sql, [':this'=>date('Y-m-d'), ':next'=>date('Y-m-d')])
    ->with([
        'days'=>function($q) {
            return $q->select(['id', 'name', 'meals', 'rid']);
        },
    ])
    ->asArray()
    ->all();

$sql = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND day_from>:this AND SUBSTRING(op_code,1,1)="G" ORDER BY day_from, id LIMIT 1000';
$upcomingTours = \common\models\Product::findBySql($sql, [':this'=>date('Y-m-d'), ':next'=>date('Y-m-d', strtotime('+7 days'))])
    ->with([
        'tour.operators'=>function($q) {
            return $q->select(['id', 'name'=>'nickname']);
        },
        'days'=>function($q) {
            return $q->select(['id', 'name', 'meals', 'rid']);
        },
    ])
    ->asArray()
    ->all();

$recentlyOpenedCases = \common\models\Kase::find()
    ->select(['id', 'name', 'stype', 'created_dt'=>'created_at', 'owner_id'])
    ->where(['stype'=>['b2b', 'b2b-series', 'b2b-prod']])
    ->with(['owner'=>function($q){
        return $q->select(['id', 'name'=>'nickname']);
    }
    ])
    ->orderBy('created_dt DESC')
    ->limit(10)
    ->asArray()
    ->all();

$recentlyOpenedTours = \common\models\Tour::find()
    // ->select(['id', 'name', 'stype', 'created_dt'=>'created_at', 'owner_id'])
    ->where('SUBSTRING(code,1,1)="G"')
    ->with([
        'operators'=>function($q){
            return $q->select(['id', 'name'=>'nickname']);
        },
        'product'=>function($q){
            return $q->select(['id', 'day_from', 'pax', 'day_count']);
        },
    ])
    ->orderBy('id DESC')
    ->limit(10)
    ->asArray()
    ->all();

?>
<div class="col-md-12">
    <div class="row">
        <div class="col-sm-3">
            <div class="panel bg-orange-400">
                <div class="panel-body">
                    <div class="heading-elements">
                        <span class="heading-text badge bg-orange-800">+53,6%</span>
                    </div>
                    <h3 class="no-margin">28</h3>
                    new confirmed tours (DEMO DATA)
                    <div class="text-muted text-size-small">in the last 7 days</div>
                </div>
                <div class="container-fluid">
                    <div id="members-online"><svg width="176.88125610351562" height="50"><g width="176.88125610351562"><rect class="d3-random-bars" width="5.0953448260271985" x="2.183719211154514" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="9.462783248336226" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="16.741847285517938" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="24.020911322699654" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="31.299975359881365" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="38.57903939706308" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="45.85810343424479" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="53.1371674714265" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="60.41623150860821" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="67.69529554578993" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="74.97435958297164" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="82.25342362015336" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="89.53248765733507" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="96.81155169451678" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="104.09061573169849" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="111.3696797688802" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="118.64874380606192" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="125.92780784324363" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="133.20687188042535" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="140.48593591760707" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="147.76499995478878" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="155.0440639919705" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="162.3231280291522" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="169.6021920663339" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect></g></svg></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel bg-blue-400">
                <div class="panel-body">
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><i class="fa fa-car"></i></li>
                        </ul>
                    </div>
                    <h3 class="no-margin">F1705031</h3>
                    Latest confirmed tour
                    <div class="text-muted text-size-small">by A Great Consultant</div>
                </div>
                <div id="today-revenue"><svg width="196.88125610351562" height="50"><g transform="translate(0,0)" width="196.88125610351562"><defs><clipPath id="clip-line-small"><rect class="clip" width="196.88125610351562" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L46.14687601725261,25.76923076923077L72.29375203450522,5L98.44062805175781,15.384615384615383L124.58750406901042,5L150.73438008626303,36.15384615384615L176.88125610351562,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="46.14687601725261" y1="50" x2="46.14687601725261" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="72.29375203450522" y1="50" x2="72.29375203450522" y2="5" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="98.44062805175781" y1="50" x2="98.44062805175781" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="124.58750406901042" y1="50" x2="124.58750406901042" y2="5" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="150.73438008626303" y1="50" x2="150.73438008626303" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="176.88125610351562" y1="50" x2="176.88125610351562" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="46.14687601725261" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="72.29375203450522" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="98.44062805175781" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="124.58750406901042" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="150.73438008626303" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="176.88125610351562" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel bg-violet-400">
                <div class="panel-body">
                    <div class="heading-elements">
                        <span class="heading-text badge bg-violet-800">+53,6%</span>
                    </div>
                    <h3 class="no-margin">3,450</h3>
                    (TESTING)
                    <div class="text-muted text-size-small">489 avg</div>
                </div>
                <div class="container-fluid">
                    <div id="members-online"><svg width="176.88125610351562" height="50"><g width="176.88125610351562"><rect class="d3-random-bars" width="5.0953448260271985" x="2.183719211154514" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="9.462783248336226" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="16.741847285517938" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="24.020911322699654" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="31.299975359881365" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="38.57903939706308" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="45.85810343424479" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="53.1371674714265" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="60.41623150860821" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="67.69529554578993" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="74.97435958297164" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="82.25342362015336" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="89.53248765733507" height="44.73684210526316" y="5.2631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="96.81155169451678" height="34.21052631578947" y="15.789473684210527" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="104.09061573169849" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="111.3696797688802" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="118.64874380606192" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="125.92780784324363" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="133.20687188042535" height="26.31578947368421" y="23.68421052631579" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="140.48593591760707" height="47.368421052631575" y="2.631578947368425" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="147.76499995478878" height="28.947368421052634" y="21.052631578947366" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="155.0440639919705" height="36.84210526315789" y="13.15789473684211" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="162.3231280291522" height="42.10526315789473" y="7.894736842105267" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="5.0953448260271985" x="169.6021920663339" height="31.57894736842105" y="18.42105263157895" style="fill: rgba(255, 255, 255, 0.498039);"></rect></g></svg></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
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
    <div class="row">
        <div class="col-sm-6">
            <p><strong>Tours in operation today</strong></p>
            <table class="table table-narrow table-striped table-bordered mb-20">
                <thead>
                    <tr>
                        <th>Tour code & name, days, pax</th>
                        <th>Today's activities</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($todayTours as $tour) { ?>
                    <tr>
                        <td>
                            <?= Html::a($tour['op_code'], '/products/op/'.$tour['id']) ?>
                            <?= $tour['day_count'] ?>d
                            <?= $tour['pax'] ?>p
                            <?= date('j/n', strtotime($tour['day_from'])) ?>
                        </td>
                        <td><?
                        foreach ($tour['days'] as $i=>$day) {
                            if (date('Y-m-d', strtotime('+'.$i.' days '.$tour['day_from'])) == date('Y-m-d')) {
                                echo $day['name'];
                                break;
                            }
                        }
                        ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>

            <p><strong>Upcoming tours</strong></p>
            <table class="table table-narrow table-striped table-bordered mb-20">
                <thead>
                    <tr>
                        <th>Start</th>
                        <th>Tour</th>
                        <th>Operators</th>
                        <th>First day</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($upcomingTours as $tour) { ?>
                    <tr>
                        <td>
                            <?= date('j/n', strtotime($tour['day_from'])) ?>
                        </td>
                        <td>
                            <?= Html::a($tour['op_code'], '/products/op/'.$tour['id']) ?>
                            <?= $tour['day_count'] ?>d
                            <?= $tour['pax'] ?>p
                        </td>
                        <td><?
                        foreach ($tour['tour']['operators'] as $op) {
                            echo $op['name'];
                            break;
                        }
                        ?></td>
                        <td><?
                        foreach ($tour['days'] as $i=>$day) {
                            if (date('Y-m-d', strtotime('+'.$i.' days '.$tour['day_from'])) == date('Y-m-d', strtotime($tour['day_from']))) {
                                echo $day['name'];
                                break;
                            }
                        }
                        ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
            <p><strong>Recent activities</strong></p>
            <p>(LIST OF ACTIVITIES)</p>
        </div>
        <div class="col-sm-6">
            <p><strong>Recently opened cases</strong></p>
            <table class="table table-narrow table-striped table-bordered mb-20">
                <thead>
                    <tr>
                        <th></th>
                        <th>Case name</th>
                        <th>Category</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recentlyOpenedCases as $case) { ?>
                    <tr>
                        <td><?= date('j/n', strtotime($case['created_dt'])) ?></td>
                        <td><?= Html::a($case['name'], '/b2b/cases/r/'.$case['id']) ?></td>
                        <td><?= $case['stype'] ?></td>
                        <td><?= $case['owner']['name'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>

            <p><strong>Recently confirmed tours</strong></p>
            <table class="table table-narrow table-striped table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tour code & name, days, pax</th>
                        <th>Operator</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recentlyOpenedTours as $tour) { ?>
                    <tr>
                        <td><?= date('j/n', strtotime($tour['created_dt'])) ?></td>
                        <td>
                            <?= Html::a($tour['code'].' - '.$tour['name'], '/b2b/tours/r/'.$tour['id']) ?>
                            <?= $tour['product']['day_count'] ?>d
                            <?= $tour['product']['pax'] ?>p
                            <?= date('j/n/Y', strtotime($tour['product']['day_from'])) ?>
                        </td>
                        <td><?= $tour['operators'][0]['name'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>