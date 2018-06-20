<?

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return rtrim(rtrim($str, '0'), '.');
    }
}

?>
                <tr>
                    <td class="text-muted text-center"><?= Html::a('Xem', '@web/cpt/r/'.$cpt['dvtour_id'], ['class'=>'text-muted']) ?></td>
                    <? if (!$theTour) { ?>
                    <td><?= Html::a($cpt['tour']['code'], '@web/tours/r/'.$cpt['tour']['id']) ?></td>
                    <td class="text-nowrap"><?= date('d-m-Y D', strtotime($cpt['dvtour_day'])) ?></td>
                    <? } ?>
                    <td>
                        <span title="ĐH đánh dấu đã đặt xong" class="label label-<?= $cpt['status'] == 'k' ? 'success' : 'default'?>">ĐH</span>
                        <? /* if ($cpt['cptTietkiem']) { ?>
                        <span class="badge badge-warning pull-right" title="Tiết kiệm: <? foreach ($cpt['cptTietkiem'] as $tkiem) { echo number_format($tkiem['amount']).' '.$tkiem['currency'] ?>&nbsp;<? } ?>">$</span>
                        <? } */?>
                        <? if ($cpt['comments']) { ?>
                        <span class="badge badge-default popovers pull-right"
                            data-trigger="hover"
                            data-placement="right"
                            data-html="true"
                            data-content="
                        <? foreach ($cpt['comments'] as $li2) { ?>
                        <div style='margin-bottom:5px'><strong><?= $li2['updatedBy']['name'] ?></strong> <em><?= DateTimeHelper::format($li2['updated_at'], 'j/n/Y H:i') ?></em></div>
                        <p><?= nl2br(Html::encode($li2['body'])) ?></p>
                        <? } ?>
                        "><?= count($cpt['comments']) ?></span>
                        <? } ?>

                        <? /* if ($cpt['cp']) { ?>
                        <?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['cp']['id'])?>
                        <? } else { */ ?>
                        <span title="<?= $cpt['updatedBy']['name'] ?> @ <?= \app\helpers\DateTimeHelper::convert($cpt['updated_at'], 'j/n/Y H:i') ?>"><?= $cpt['dvtour_name'] ?></span>
                        <? //} ?>

                        @<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>
                        <? if ($cpt['company']) { ?>
                        $<?= Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['company']['id']) ?>
                        <? } else { ?>
                            <? if ($cpt['oppr'] != '') { ?>
                        $<?= $cpt['oppr'] ?>
                            <? } ?>
                        <? } ?>

                        <? if ($cpt['updated_by'] == USER_ID || 1 == USER_ID) { ?>
                        <?= Html::a('<i class="fa fa-edit"></i>', '@web/tours/services/'.$cpt['tour_id'].'#dvtour-'.$cpt['dvtour_id'], ['class'=>'text-muted', 'rel'=>'external']) ?>
                        <? } ?>
                    </td>

                    <td class="text-center"><?= (real)$cpt['qty'] ?></td>
                    <td class="text-center text-muted"><?= $cpt['unit'] ?></td>
                    <td class="text-right text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                    <?
                    $lineTotal = $cpt['price'] * $cpt['qty'];
                    ?>
                    <td class="text-right text-pink text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($lineTotal, intval($lineTotal) == $lineTotal ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></td>
                    <td>
                        <? if ($cpt['crfund'] == 'yes') { ?><i title="CR Fund / Quỹ QHKH" class="fa fa-quora text-warning"></i><? } ?>
                        <?= $cpt['payer'] ?></td>
                    <td><?= $cpt['due'] == '0000-00-00' ? '' : $cpt['due'] ?></td>
                    <td>
                        <!--
                        <i title="Check 1" class="check check1 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 2" class="check check2 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 3" class="check check3 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 4" class="check check4 text-muted fa fa-circle-o cursor-pointer"></i>
                        -->
                        <?
                        $cpt['approved_by'] = trim($cpt['approved_by'], '[');
                        $cpt['approved_by'] = trim($cpt['approved_by'], ':]');
                        $ids = explode(':][', $cpt['approved_by']);
                        $apprCnt = 0;
                        $apprName = [];
                        foreach ($ids as $id2) {
                            foreach ($approvedBy as $user) {
                                if ($user['id'] == (int)$id2) {
                                    $apprCnt ++;
                                    $apprName[] = $user['name'];
                                }
                            }
                        }
                        if ($apprCnt > 0) {
                        ?><span class="badge badge-info" title="Xác nhận: <?= implode(', ', $apprName) ?>"><?= $apprCnt ?></span><?
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        $cptPaidInFull = false;
                        $cptInBasket = false;
                        foreach ($cpt['mtt'] as $mtt) {
                            if ($mtt['status'] == 'on') {
                                $title = 'Click to check/uncheck:'.chr(10);
                                $title .= number_format($mtt['amount'], intval($mtt['amount']) == $mtt['amount'] ? 0 : 2).' '.$mtt['currency'].chr(10);
                                $title .= $mtt['updatedBy']['name'].' @'.date('j/n/Y', strtotime($mtt['updated_dt']));
                                if ($mtt['check'] != '') {
                                    $title .= chr(10).'Checked: '.$mtt['check'];
                                }
                        ?>
                        <span title="<?= $title ?>" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-mtt_id="<?= $mtt['id'] ?>" class="cursor-pointer ajax-check-mtt badge badge-<?= $mtt['check'] == '' ? 'info' : 'success' ?>">$</span>
                        <?
                                if ($mtt['paid_in_full'] == 'yes') {
                                    $cptPaidInFull = true;
                                }
                            } elseif ($mtt['status'] == 'draft') {
                                if ($mtt['created_by'] == USER_ID) {
                                    $cptInBasket = true;
                                }
                            }
                        }
                        ?>
                        <? if (!$cptPaidInFull) { ?>
                        <a title="Đánh dấu đã TT 100%" href="#" class="label label-default mark-paid" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT</a>
                        <a title="Sửa TT riêng mục này" href="/cpt/r/<?= $cpt['dvtour_id'] ?>?action=new-mtt" class="label label-default" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT+</a>
                            <? if ($cptInBasket) { ?>
                        <a title="Bỏ Đề nghị thanh toán" href="#" class="label label-info add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
                            <? } else { ?>
                        <a title="Đề nghị thanh toán" href="#" class="label label-default add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
                            <? } ?>
                        <? } else { ?>
                        <i title="Đã thanh toán toàn bộ" class="fa fa-lock text-success"></i>
                        <? } ?>
                    </td>
                    <td>
                        <a title="Đánh dấu đã lấy hoá đơn" href="#" class="label label-<?= $cpt['vat_ok'] == 'ok' ? 'success' : 'default' ?> vat-ok" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">HĐ</a>
                    </td>
                </tr>
