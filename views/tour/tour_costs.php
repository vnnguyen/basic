<?
use yii\helpers\Html;

include('_tours_inc.php');

Yii::$app->params['page_title'] = '!TESTING! Tour costs: '.$theTour['op_code'];

Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_breadcrumbs'] = [['Tour', 'tours']];
Yii::$app->params['page_breadcrumbs'][] = [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)];
Yii::$app->params['page_breadcrumbs'][] = [$theTour['op_code'], 'tours/r/'.$theTourOld['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Costs', 'tours/costs/'.$theTour['id']];

$getFilter = Yii::$app->request->get('filter', '');
$getCurrency = Yii::$app->request->get('currency', '');
$getFromDay = Yii::$app->request->get('from_day', '');
$getUntilDay = Yii::$app->request->get('until_day', '');
$this->registerCss('
    #total-cost {font:bold 20px/30px Arial; padding:5px; border:3px solid #996; background:#ffc; color:#c00;}
span.s-status, span.s-vat, span.dvtour-tra, span.dvtour-ktt, span.s-duyet {background:#ccc; cursor:pointer; padding:1px; font:10px Arial; color:#fff;}
span.xacnhan {background:#090;}
span.pct0 {background:#ccc;}
span.pct50 {background:#fc9;}
span.pct100 {background:#090;}
');
$viewUsdOnly = in_array(USER_ID, [3404, 5805, 14029, 14030, 15007]);

$tgx =[];
$xRates['VND'] = 1;

$theTour['op'] = [1];

// Array for filters
$filterArray = [
'v'=>[],
'vc'=>[],
'bc'=>[],
'p'=>[],
'n'=>[],
];
foreach ($theCptx as $cpt) {
    if (!isset($filterArray['p'][md5($cpt['payer'])]))
        $filterArray['p'][md5($cpt['payer'])] = $cpt['payer'];
    if ($cpt['venue_id'] != 0) {
        if (!isset($filterArray['v'][md5($cpt['venue_id'])]))
            $filterArray['v'][md5($cpt['venue_id'])] = $cpt['venue']['name'];
    } elseif ($cpt['via_company_id'] != 0) {
        if (!isset($filterArray['vc'][md5($cpt['via_company_id'])])) $filterArray['vc'][md5($cpt['via_company_id'])] = $cpt['viaCompany']['name'];
    } elseif ($cpt['by_company_id'] != 0) {
        if (!isset($filterArray['bc'][md5($cpt['by_company_id'])])) $filterArray['bc'][md5($cpt['by_company_id'])] = $cpt['company']['name'];
    } else {
        if (!isset($filterArray['n'][md5($cpt['oppr'])])) $filterArray['n'][md5($cpt['oppr'])] = $cpt['oppr'];
    }
}

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="clearfix">
                <form class="form-inline" style="float:left">
                    Filter this tour:
                    <select name="filter" class="form-control" style="width:auto;">
                        <option value="">- chọn xem theo tên nhà cung cấp -</option>
                        <optgroup label="Tên do điều hành nhập vào">
                            <?
                            asort($filterArray['n']);
                            foreach ($filterArray['n'] as $k=>$v) { ?>
                            <option value="hn-<?=$k?>" <?=$getFilter == 'hn-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                            <? } ?>
                        </optgroup>
                        <optgroup label="Tên do IMS tự động link">
                            <?
                            asort($filterArray['v']);
                            foreach ($filterArray['v'] as $k=>$v) { ?>
                            <option value="hi-<?=$k?>" <?=$getFilter == 'hi-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                            <? } ?>
                        </optgroup>
                        <optgroup label="Công ty cung cấp dịch vụ">
                            <? foreach ($filterArray['vc'] as $k=>$v) { ?>
                            <option value="hv-<?=$k?>" <?=$getFilter == 'hv-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                            <? } ?>
                            <? foreach ($filterArray['bc'] as $k=>$v) { ?>
                            <option value="hb-<?=$k?>" <?=$getFilter == 'hb-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                            <? } ?>
                        </optgroup>
                        <optgroup label="Ai trả tiền">
                            <? foreach ($filterArray['p'] as $k=>$v) { ?>
                            <option value="hp-<?=$k?>" <?=$getFilter == 'hp-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                            <? } ?>
                        </optgroup>
                    </select>
                    <select name="currency" class="form-control" style="width:auto">
                        <option value="all">Currency</option>
                        <? foreach (['USD', 'EUR', 'VND'] as $cu) { ?>
                        <option value="<?= $cu ?>" <?= $cu == $getCurrency ? 'selected="selected"' : '' ?>><?= $cu ?></option>
                        <? } ?>
                    </select>
                    <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                </form>
                <form class="form-inline" style="float:left; margin-left:2em;" action="/tours/costs">
                    View another tour:
                    <?= Html::textInput('code', $theTour['op_code'], ['class'=>'form-control']) ?>
                    <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                </form>
            </div>
        </div>
        <?
$allDays = []; // Tat ca cac ngay co dich vu, hoac nam trong chuong trinh tour

foreach ($theCptx as $cpt) {
    $allDays[$cpt['dvtour_day']] = '';
}

$cnt = 0;
$dayIdList = explode(',', $theTour['day_ids']);
foreach ($dayIdList as $di) {
    foreach ($theTour['days'] as $ng) {
        if ($ng['id'] == $di) {
            $cnt ++;
            $ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
            $allDays[$ngay] = $ng;
        }
    }
}

ksort($allDays);
$allOffices = array(
    'hn'=>'Hà Nội',
    'sg'=>'Saigon',
    'sr'=>'Siem Reap',
    'vt'=>'Vientiane',
    'nt'=>'Nha Trang',
    );

$total = 0;
$totalUSD = 0;
$sub = 0;
$subUSD = 0;

?>
        <!--
        <div class="hide alert alert-info">
            <strong>Các thay đổi trong cách trình bày bảng này (trong giai đoạn chuyển tiếp)</strong>
            <br />8/2 : Đổi màu các dịch vụ đã có link đến nhà cung cấp / địa điểm (được điều hành chọn khi nhập dich vụ vào)
            <br />9/2 : Tên guide (do Ms Chinh nhập) sẽ được thêm thành một dòng trong bảng vào mỗi ngày có guide. Chưa có tác dụng tính chi phí.
            <br />10/2 : Cột "Ai đặt dịch vụ" đổi thành "Admin/Resv?" ghi tên văn phòng sẽ điều hành dịch vụ (Amica hiện có 4 vp) và liệu có phải book trước không. Nếu hướng dẫn là người đặt thì cột này là Không. Vẫn nhập vào như cũ.
            <br />7/8 : Có thể thêm số âm cho dịch vụ (trường hợp trả lại sản phẩm, lấy lại tiền).
        </div>
        -->
        <div class="pull-right" id="total-cost"></div>
        <div class="table-responsive">
            <table id="okle" class="table table-xxs">
                <thead>
                    <tr>
                        <th>Cost & supplier</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <? if (!$viewUsdOnly) { ?>
                        <th>in VND</th>
                        <? } ?>
                        <th>Admin</th>
                        <th>Who pays</th>
                        <th>Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="tr-form-cpt" style="display:none;">
                        <td colspan="8" style="border-left:1px solid #36f;">
                            <form id="form-cpt" class="form-horizontal" method="post" action="" style="max-width:800px; padding:10px; background-color:#f6f6f6;">
                                <fieldset>
                                    <legend>Add cpt</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="">Day in tour</label>
                                        <div class="controls">
                                            <select class="form-control" name="dvtour_day"><?
                                                $cnt = 0;
                                                foreach ($dayIdList as $di) {
                                                    foreach ($theTour['days'] as $ng) {
                                                        if ($ng['id'] == $di) {
                                                            $cnt ++;
                                                            $ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($cnt - 1).'days')); ?>
                                                            <option value="<?= $ngay ?>">Day <?= $cnt ?>: <?= $ng['name'] ?></option><?
                                                        }
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">Name of cost<br><input class="blank form-control" type="text" name="dvtour_name" value=""></div>
                                        <div class="col-md-5">Supplier<br><input class="blank form-control" type="text" name="oppr" value=""></div>
                                        <div class="col-md-2">Admin<br>
                                            <select class="form-control" name="adminby">
                                                <option value="hn">Ha Noi</option>
                                                <option value="sg">Saigon</option>
                                                <option value="hu">Hue</option>
                                                <option value="vt">Vientiane</option>
                                                <option value="sr">Siem Reap</option>
                                                <option value="nt">Nha Trang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">Venue/location <br/>
                                            <select class="form-control select2" name="venue_id">
                                                <option value="0">- Hotel, sightseeing... -</option>
                                                <? foreach ($allVenues as $vn) {?>
                                                <option value="<?=$vn['id']?>"><?=$vn['name']?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">Customer Relations Fund / Quỹ QHKH<br>
                                            <input name="start" value="" type="hidden">
                                            <select class="form-control" name="crfund">
                                                <option value="">No</option>
                                                <option value="yes">Yes (CR Fund)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Nhà cung cấp</label>
                                            <select class="form-control select2" name="by_company_id">
                                                <option value="0">- Tàu, xe, dịch vụ gốc... -</option>
                                                <? foreach ($allCompanies as $c) { ?>
                                                <option value="<?=$c['id']?>"><?=$c['name']?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Đại lý</label>
                                            <select class="form-control select2" name="via_company_id">
                                                <option value="0">- Vé tàu, vé máy bay, package... -</option>
                                                <? foreach ($allCompanies as $c) { ?>
                                                <option value="<?=$c['id']?>"><?=$c['name']?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-md-2">Qty<br><input class="blank form-control text-right" type="text" name="qty" value=""></div>
                                        <div class="col-md-3">Unit<br><input class="blank form-control" type="text" name="unit" value=""></div>
                                        <div class="col-md-2">+/-<br>
                                            <select class="form-control" name="plusminus">
                                                <option value="plus">+</option>
                                                <option value="minus">-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">Price<br><input class="blank form-control text-right" type="text" name="price" value=""></div>
                                        <div class="col-md-2">&nbsp;<br>
                                            <select class="form-control" name="unitc">
                                                <option>VND</option>
                                                <option>USD</option>
                                                <option>EUR</option>
                                                <option>LAK</option>
                                                <option>KHR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">Reservation?<br>
                                            <select class="form-control" name="prebooking">
                                                <option value="yes">Yes / Có</option>
                                                <option value="no">No / Không</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            Who pays<br>
                                            <select class="form-control" name="payer">
                                                <option>Amica Hà Nội</option>
                                                <option>Amica Saigon</option>
                                                <option>Hướng dẫn MB 1</option>
                                                <option>Hướng dẫn MB 2</option>
                                                <option>Hướng dẫn MB 3</option>
                                                <option>Hướng dẫn MB 4</option>
                                                <option>Hướng dẫn MN 1</option>
                                                <option>Hướng dẫn MN 2</option>
                                                <option>Đức Minh</option>
                                                <option>An Hoà</option>
                                                <option>Anh Tấn</option>
                                                <option>Anh Thơ</option>
                                                <option>Anh Vinh</option>
                                                <option>Bunthol</option>
                                                <option>Dak Viet</option>
                                                <option>Thonglish (Laos)</option>
                                                <option>Medsanh (Laos)</option>
                                                <option>Feuang (Laos)</option>
                                                <option>Indo-Siam</option>
                                                <option>VEI Travel</option>
                                                <option>Chita</option>
                                                <option>Nanco</option>
                                                <option>Farid</option>
                                                <option>Jason</option>
                                                <option>Khác</option>
                                                <option>iTravelLaos (old)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            Booking status<br>
                                            <select class="form-control" name="status">
                                                <option value="n">Not OK</option>
                                                <option value="k">OK</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            Payment due<br>
                                            <input type="text" tabindex="-1" class="blank form-control datepicker" name="due" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            Note (optional)
                                            <textarea class="form-control" rows="5" name="comment"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-10">
                                        <a id="form-cpt-submit" class="btn btn-primary" href="#">Save</a>
                                        <a id="form-cpt-copy" class="btn btn-info" href="#">Save as copy</a>
                                        <a id="form-cpt-close" href="#">Cancel</a>
                                    </div>
                                </fieldset>
                                <!-- p><a id="a-submit" class="btn btn-primary" href="#">Ghi các thay đổi</a> hoặc <a href="#" onclick="$('#div-form').addClass('hide').hide(0); $('td.fw-b').removeClass('fw-b'); return false;">Thôi, quay lại</a></p -->
                            </form>
                        </td>
                    </tr>
                    <?

                    foreach ($allDays as $k=>$v) { ?>
                        <tr id="day<?= $k ?>" class="info">
                            <td colspan="<?= $viewUsdOnly ? 6 : 7 ?>">
                                <?= Yii::$app->formatter->asDate($k, 'php:j/n/Y l') ?>
                                <? if ($v == '') { ?>
                                Ngày này không nằm trong chương trình tour chính thức
                                <? } else { ?>
                                <? echo Html::a($v['name'].' ('.$v['meals'].')', '#tours/ngaytour/'.SEG3, ['class'=>"fw-b", 'title'=>str_replace('"', '`', $v['body'])]) ?>
                                <? } ?>
                            </td>
                            <td><?= in_array(USER_ID, [1, 34718]) ? '<a class="dvt-c" href="#cpt-c" day="'.$k.'">+New</a>' : '' ?></td>
                        </tr>
                        <? foreach ($tgx as $tg) {
                            if ($getFilter == '' && $tg['day'] == $k) { ?>
                            <tr>
                                <td><span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status xacnhan">OK</span> <?= Html::a('Tour guide', 'users/lichguide/'.$tg['user_id'].'?month='.substr($tg['day'], 0, 7))?></td>
                                <td><?=Html::a($tg['fname'].' '.$tg['lname'].' - '.$tg['uabout'], 'users/r/'.$tg['user_id'], ['class'=>'td-n', 'style'=>'color:#939'])?></td>
                                <td>1</td>
                                <td>người</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <?}
                        } ?>
                        <?
                        foreach ($theCptx as $cpt) {
                            $hashedOppr = 'hn-'.md5($cpt['oppr']);
                            $hashedVenueId = 'hi-'.md5($cpt['venue_id']);
                            $hashedByCId = 'hb-'.md5($cpt['by_company_id']);
                            $hashedViaCId = 'hv-'.md5($cpt['via_company_id']);
                            $hashedPayer = 'hp-'.md5($cpt['payer']);
                            if ($cpt['dvtour_day'] == $k) {
                                if (
                                    $getFilter == ''
                                    || ($getFilter != '' && $getFilter == $hashedOppr)
                                    || ($getFilter != '' && $getFilter == $hashedVenueId)
                                    || ($getFilter != '' && $getFilter == $hashedByCId)
                                    || ($getFilter != '' && $getFilter == $hashedViaCId)
                                    || ($getFilter != '' && $getFilter == $hashedPayer)
                                    )
                                { ?>
                                    <tr id="dvtour-<?=$cpt['dvtour_id']?>">
                                        <td class="show-on-hover text-nowrap">
                                            <?
                                            $hasComments = !empty($cpt['comments']);
                                            ?>
                                            <i title="Booking confirmed" class="fa fa-circle cursor-pointer dvtour-ok <?=$cpt['status'] == 'k' ? 'text-success' : 'text-muted' ?>" rel="<?= $cpt['dvtour_id'] ?>"></i>
                                            <? if ($cpt['start'] != '00:00:00') { ?><span style="background:#069; padding:0 2px; color:#ffc;"><?=substr($cpt['start'], 0, 5)?></span><? } ?>
                                            <?
                                            if ($cpt['cp_id'] != 0) {
                                                echo '<i class="fa fa-bolt text-warning"></i> ';
                                                echo $cpt['cp']['name'];
                                                if ($cpt['cp']['venue_id'] != 0) {
                                                    echo ', ', Html::a($cpt['cp']['venue']['name'], '/venues/r/'.$cpt['cp']['venue_id']);
                                                }
                                            } else {
                                                ?>
                                                <a title="Sửa cpt #<?=$cpt['dvtour_id']?>" rel="<?=$cpt['dvtour_id']?>" class="cpt-u" href="#cdvtour-update"><?=$cpt['dvtour_name']?></a>
                                                <?
                                                if ($cpt['venue_id'] != 0) {
                                                    echo Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue_id'], ['style'=>'text-decoration:none; color:#600']);
                                                } elseif ($cpt['via_company_id'] != 0) { 
                                                    echo Html::a($cpt['viaCompany']['name'], '@web/companies/r/'.$cpt['via_company_id'], ['title'=>$hashedViaCId, 'style'=>'text-decoration:none; color:#060']);
                                                } elseif ($cpt['by_company_id'] != 0) {
                                                    echo Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['by_company_id'], ['style'=>'text-decoration:none; color:#c60']);
                                                } else {
                                                    echo Html::a($cpt['oppr'], DIR.URI.'?filter=hn-'.md5($cpt['oppr']));
                                                }
                                            }?>
                                            <a title="View cost and add comments" class="<?= $hasComments ? 'text-danger' : ' text-muted shown-on-hover'?>" href="/cpt/r/<?= $cpt['dvtour_id'] ?>"><i class="fa <?= $hasComments ? 'fa-comment-o' : 'fa-ellipsis-h'?>"></i></a>
                                        </td>
                                        <td class="text-right"><?= (float)$cpt['qty'] ?></td>
                                        <td class="text-nowrap">
                                            <?  if ($cpt['cp_id'] != 0) {
                                                echo $cpt['cp']['unit'];
                                            } else {
                                                echo $cpt['unit'];    
                                            }?>
                                        </td>
                                        <td class="text-right text-nowrap">
                                            <? if ($cpt['plusminus'] == 'minus') echo '-'; ?><?
                                            $str = (float)$cpt['price'];
                                            $strPart = explode('.', $str);
                                            if (!isset($strPart[1])) {
                                                $strPart[1] = '';
                                            } else {
                                                $strPart[1] = '.'.$strPart[1];
                                            }
                                            echo number_format($strPart[0], 0).$strPart[1];
                                            ?>
                                            <span class="text-muted"><?= $cpt['unitc'] ?></span>
                                        </td>
                                        <?
                                        $xRates[$cpt['unitc']] = 1;// HUAN
                                        $sub = $cpt['qty']*$cpt['price']*$xRates[$cpt['unitc']]*(1+$cpt['vat']/100);
                                        if ($cpt['unitc'] == 'USD') {
                                            if ($cpt['plusminus'] == 'plus') {
                                                $subUSD = $cpt['qty']*$cpt['price']*(1+$cpt['vat']/100);
                                            } else {
                                                $subUSD = $cpt['qty']*$cpt['price']*(1+$cpt['vat']/100);
                                            }
                                        }
                                        if ($cpt['latest']==0) {
                                            if ($cpt['plusminus'] == 'plus') {
                                                $total += $sub; $totalUSD += $subUSD;
                                            } else {
                                                $total -= $sub; $totalUSD -= $subUSD;
                                            }
                                        } ?>
                                        <? if (!$viewUsdOnly) { ?>
                                                <td class="text-right <? if($cpt['approved_by'] !=0) {?>approved<? } ?>" title="<?=$cpt['unitc'] != 'VND' ? 'Tỉ giá: '.$xRates[$cpt['unitc']] : ''?>">
                                                    <?
                                                    $approveColors = array('#fff', '#ccc', '#666', '#960', '#660', '#090');
                                                    if ($cpt['approved_by'] != '') {
                                                        $cpt['approved_by'] = trim($cpt['approved_by'], '[');
                                                        $cpt['approved_by'] = trim($cpt['approved_by'], ']');
                                                        $approvers = explode('][', $cpt['approved_by']);
                                                        $approverNames = '';
                                                        foreach ($approvers as $ap) {
                                                            if (isset($_users[trim($ap, ':')]['name'])) {
                                                                $approverNames .= $_users[trim($ap, ':')]['name'].', ';
                                                            } else {
                                                                $approverNames .= 'user-'.trim($ap, ':').', ';
                                                            }
                                                        }
                                                    } else {
                                                        $approvers = array();
                                                        $approverNames = '(chưa)';
                                                    }
                                                    $theColor = isset($approveColors[count($approvers)]) ? $approveColors[count($approvers)] : 0;
                                                    echo $cpt['plusminus'] == 'minus' ? '-' : '';
                                                    $subStr = number_format($sub, 2);
                                                    if (substr($subStr, -3) == '.00') {
                                                        $subStr = number_format($sub);
                                                    }
                                                    echo Html::a($subStr, 's-approve/'.$cpt['dvtour_id'], ['title'=>'Approve: '.$approverNames, 'class'=>"approve", 'rel'=>$cpt['dvtour_id']]);
                                                    echo '&nbsp;<span style="color:#fff; font:bold 11px Courier New; padding:1px; background:'.$theColor.'">'.count($approvers).'</span>';
                                                    ?>
                                                </td>
                                        <? } ?>
                                        <td>
                                            <?
                                            echo $allOffices[$cpt['adminby']]?? '';
                                                // echo $cpt['prebooking'] == 'yes' ? ' / Yes' : '';
                                            ?>
                                        </td>
                                        <td><?= Html::a($cpt['payer'], DIR.URI.'?filter=hp-'.md5($cpt['payer']))?></td>
                                        <td>
                                            <?
                                            $traC7 = 0;
                                            if (substr($cpt['c3'], 0, 2) == 'on') {
                                                $traC7 = 100;
                                            }
                                            ?>
                                            <span title="Kế toán đánh dấu đã thanh toán (một phần hoặc toàn bộ)" class="dvtour-tra pct<?= $traC7 ?>">TRẢ</span>
                                            <?= (USER_ID == $theTour['op'] || in_array(USER_ID, $tourOperatorIds)) && USER_ID == $cpt['ub'] && $traC7 == 0 ? '<a title="Xoá dịch vụ" href="#" class="td-n danger dvt-d" rel="'.$cpt['dvtour_id'].'">del</a>' : '<a title="Xoá dịch vụ" href="#" class="td-n danger dvt-d" rel="'.$cpt['dvtour_id'].'">del</a>'?>
                                        </td>
                                    </tr>
                                <? } // if getFilter
                            } // if $cpt[]
                        } // foreach $sx
                    } // foreach $allDays
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
var tour_id = $tour_id;
var action = 'create';
var dvtour_id = 0;
var currentday = $day_from;
    // Action create
    $('tr.info a.dvt-c').on('click', function(){
        $(this).parent().parent().after($('tr#tr-form-cpt'));
        $('tr#tr-form-cpt').show(0).find('legend').html('Thêm dịch vụ');
        action = 'create';
        dvtour_id = 0;
        var day = $(this).attr('day');
        $('#form-cpt').find('.blank').val('');
        $('#form-cpt').find(':input:first').val(day);
        $('#form-cpt').find('[type=text]:first').focus();
        $('#form-cpt-copy').hide(0);
        return false;
    });
    
    // Action copy
    $('a#form-cpt-copy').on('click', function(){
        action = 'copy';
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
            if (data[0] == 'NOK') {
                alert(data[1]);
            } else if (data[0] == 'OK-COPY') {
                $('tr#tr-form-cpt').before('<tr><td colspan="10">Please wait...</td></tr>');
                var prev = $('tr#tr-form-cpt').prev('tr');
                dvtour_id = data[2];
                prev.attr('id', 'dvtour-'+dvtour_id).load('/tours/load_tr?id='+dvtour_id);
                $('[name=dvtour_name], [name=oppr], [name=qty], [name=unit], [name=unitc], [name=price], [name=due], [name=mm]').val('');
                $('tr#tr-form-cpt').hide(0);
                // Change day
                if (currentday != data[3]) {
                    $('tr#dvtour-'+dvtour_id).insertAfter($('tr#day' + data[3]));
                }
            }
        }, 'json');
        return false;
    });
    $('a#form-cpt-submit').on('click', function(){
        //$('#form-cpt-submit #form-cpt-copy').addClass('disabled');
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
            if (data[0] == 'NOK') {
                alert(data[1]);
            } else if (data[0] == 'OK-CREATE') {
                $('tr#tr-form-cpt').before('<tr><td colspan="10">Please wait...</td></tr>');
                var prev = $('tr#tr-form-cpt').prev('tr');
                dvtour_id = data[2];
                prev.attr('id', 'dvtour-'+dvtour_id).load('/tours/load_tr?id='+dvtour_id);
            } else if (data[0] == 'OK-UPDATE') {
                $('tr#dvtour-'+dvtour_id).load('/tours/load_tr?id='+dvtour_id);
                $('[name=dvtour_name], [name=oppr], [name=qty], [name=unit], [name=unitc], [name=price], [name=due], [name=mm]').val('');
                $('tr#tr-form-cpt').hide(0);
                        // Change day
                if (currentday != data[3]) {
                    $('tr#dvtour-'+dvtour_id).insertAfter($('tr#day' + data[3]));
                }
            }
        }, 'json');
            //$('#form-cpt-submit #form-cpt-copy').removeClass('disabled');
        return false;
    });
    
    // Action delete
    $(document).on('click', 'a.dvt-d', function(){
        if (!confirm('Bạn thực sự muốn xoá dịch vụ này?')) {
            return false;
        }
        action = 'delete';
        dvtour_id = $(this).attr('rel');
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata, total:$total}, function(data){
        if (data[0] == 'NOK') {
        alert(data[1]);
        } else {
        $('tr#dvtour-' + dvtour_id).remove();
            $('#total-cost').html(data[2]);
        }
        }, 'json');
        return false;
    });

    $('a.approve').on('click', function(){
        rel = $(this).attr('rel');
        var td = $(this).parent();
        $.post(location.href, {dvtour_id:rel}, function(data){
        if (data != 'nok') {
        td.empty().html(data);
        } else {
        alert('Khong thanh cong...');
        }
        }, 'html');
        return false;
    });
  
    // DIEU HANH CHECK DAT DICH VU
    $('.dvtour-ok').on('click', function(){
        action = 'ok';
        dvtour_id = $(this).attr('rel');
        var span = $(this);
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
        if (data[0] == 'NOK') {
            alert(data[1]);
        } else {
            span.removeClass('xacnhan').addClass(data[1]);
        }
        }, 'json');
        return false;
    });

    // KE TOAN CHECK THANH TOAN
    
    $('span.dvtour-tra').on('click', function(){
        console.log(1);
        action = 'tra';
        dvtour_id = $(this).attr('rel');
        var span = $(this);
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
        if (data[0] == 'NOK') {
        alert(data[1]);
        } else {
        span.removeClass('pct0 pct50 pct100').addClass(data[1]);
        }
        }, 'json');
        return false;
    });

    // KE TOAN DANH DAU VAT
    /*
    $('span.s-vat').on('click', function(){
        var sid = $(this).attr('rel');
        var span = $(this);
        $.post(location.href, {action:'va',sid:sid}, function(data){
            if (data == 'ok') {
                if (span.hasClass('pct50')) {
                      span.removeClass('pct50').addClass('pct100');
                  } else if (span.hasClass('pct100')) {
                      span.removeClass('pct100');
                  } else {
                      span.addClass('pct50');
                  }
              } else {
                alert('Khong thanh cong...');
            }
        }, 'text');
    });
    */
    // KE TOAN TRUONG XN
    $('span.dvtour-ktt').on('click', function(){
        action = 'ktt';
        dvtour_id = $(this).attr('rel');
        var span = $(this);
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
            if (data[0] == 'NOK') {
                alert(data[1]);
            } else {
            span.removeClass('xacnhan').addClass(data[1]);
            }
        }, 'json');
        return false;
    });

    // BOSS DUYET
    $('span.s-duyet').on('click', function(){
        var sid = $(this).attr('rel');
        var span = $(this);
        $.post(location.href, {action:'du',sid:sid}, function(data){
            if (data == 'ok') {
                span.toggleClass('xacnhan');
            } else {
                alert('Khong thanh cong...');
            }
        }, 'text');
    });

    // Action update
    $('#okle').on('click', 'tr a.cpt-u', function(){
        $(this).parent().parent().after($('tr#tr-form-cpt'));
        $('tr#tr-form-cpt').show(0).find('legend').html('Sửa dịch vụ');
        $('#form-cpt-copy').show(0);
        action = 'update-prepare';
        dvtour_id = $(this).attr('rel');
        var formdata = $('#form-cpt').serializeArray();
        $.post('/tours/ajax', {
            action:action,
            tour_id:tour_id,
            dvtour_id:dvtour_id,
            formdata:formdata
        }, function(data){
            if (data[0] == 'NOK') {
                //alert(data[1]);
            } else {
                $('#form-cpt').find('[type=text]:first').focus();
                $('[name=dvtour_day]').val(data['dvtour_day']);
                $('[name=dvtour_name]').val(data['dvtour_name']);
                $('[name=oppr]').val(data['oppr']);
                $('[name=venue_id]').val(data['venue_id']).select2();
                $('[name=adminby]').val(data['adminby']);
                $('[name=start]').val(data['start']);
                $('[name=crfund]').val(data['crfund']);
                $('[name=via_company_id]').val(data['via_company_id']).select2();
                $('[name=by_company_id]').val(data['by_company_id']).select2();
                $('[name=qty]').val(data['qty']);
                $('[name=unit]').val(data['unit']);
                $('[name=price]').val(data['price']);
                $('[name=unitc]').val(data['unitc']);
                $('[name=vat]').val(data['vat']);
                $('[name=prebooking]').val(data['prebooking']);
                $('[name=payer]').val(data['payer']);
                $('[name=status]').val(data['status']);
                $('[name=due]').val(data['due']);
                $('[name=plusminus]').val(data['plusminus']);
                $('[name=mm]').val('');
                action = 'update';
                currentday = data['dvtour_day'];
            }
        }, 'json');

        return false;
    });

    // Close form
    $('a#form-cpt-close').on('click', function(){
        $('tr#tr-form-cpt').hide(0);
        return false;
    });

    $('.shown-on-hover').hide(0);
    $( "table#okle" ).on( "mouseover", ".show-on-hover", function() {
        $(this).find('.hidden-on-hover').hide(0);
        $(this).find('.shown-on-hover').show(0);
    }).on( "mouseout", ".show-on-hover", function() {
        $(this).find('.shown-on-hover').hide(0);
        $(this).find('.hidden-on-hover').show(0);
    });

    $('[name="filter"]').select2();
TXT;

$js = str_replace(['$tour_id'], [$theTourOld['id']], $js);
$js = str_replace(['$day_from'], [$theTour['day_from']], $js);
$js = str_replace(['$total'], [$total], $js);

$this->registerJs($js);