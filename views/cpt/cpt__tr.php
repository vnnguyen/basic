<?php

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
                <tr data-dvtour_id="<?= $cpt['dvtour_id'] ?>">
                    <td class="text-muted text-center"><?= Html::a('Xem', '@web/cpt/r/'.$cpt['dvtour_id'], ['class'=>'text-muted']) ?></td>
                    <?php if (!$theTour) { ?>
                    <td><?= Html::a($cpt['tour']['code'], '@web/tours/r/'.$cpt['tour']['id']) ?></td>
                    <td class="text-nowrap"><?= str_replace('Th ', 'T', Yii::$app->formatter->asDate($cpt['dvtour_day'], 'php:j/n/Y D')) ?></td>
                    <?php } ?>
                    <td>
                        <span title="ĐH đánh dấu đã đặt xong" class="badge badge-<?= $cpt['status'] == 'k' ? 'success' : 'secondary'?>">ĐH</span>
                        <?php /* if ($cpt['cptTietkiem']) { ?>
                        <span class="badge badge-warning pull-right" title="Tiết kiệm: <?php foreach ($cpt['cptTietkiem'] as $tkiem) { echo number_format($tkiem['amount']).' '.$tkiem['currency'] ?>&nbsp;<?php } ?>">$</span>
                        <?php } */?>
                        <?php if ($cpt['comments']) { ?>
                        <span class="badge badge-secondary popovers pull-right"
                            data-trigger="hover"
                            data-placement="right"
                            data-html="true"
                            data-content="
                        <?php foreach ($cpt['comments'] as $li2) { ?>
                        <div style='margin-bottom:5px'><strong><?= $li2['updatedBy']['name'] ?></strong> <em><?= DateTimeHelper::format($li2['updated_at'], 'j/n/Y H:i') ?></em></div>
                        <p><?= nl2br(Html::encode($li2['body'])) ?></p>
                        <?php } ?>
                        "><?= count($cpt['comments']) ?></span>
                        <?php } ?>

                        <?php
                            // var_dump(number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2));die;
                         /* if ($cpt['cp']) { ?>
                        <?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['cp']['id'])?>
                        <?php } else { */ ?>
                        <span title="<?= $cpt['updatedBy']['name'] ?> @ <?= \app\helpers\DateTimeHelper::convert($cpt['updated_at'], 'j/n/Y H:i') ?>"><?= $cpt['dvtour_name'] ?></span>
                        <?php //} ?>

                        @<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>
                        <?php if ($cpt['company']) { ?>
                        $<?= Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['company']['id']) ?>
                        <?php } else { ?>
                            <?php if ($cpt['oppr'] != '') { ?>
                        $<?= $cpt['oppr'] ?>
                            <?php } ?>
                        <?php } ?>

                        <?php if ($cpt['updated_by'] == USER_ID || 1 == USER_ID) { ?>
                        <?= Html::a('<i class="fa fa-edit"></i>', '@web/tours/services/'.$cpt['tour_id'].'#dvtour-'.$cpt['dvtour_id'], ['class'=>'text-muted', 'rel'=>'external']) ?>
                        <?php } ?>
                    </td>

                    <td class="text-center"><?= (real)$cpt['qty'] ?></td>
                    <td class="text-center text-muted"><?= $cpt['unit'] ?></td>
                    <td class="text-right text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                    <?php
                    $lineTotal = $cpt['price'] * $cpt['qty'];
                    ?>
                    <?php if ($viewvat == 'yes') { ?>
                        <?php if (isset($cpt['venue']['id']) && in_array($cpt['venue']['id'], $vatList)) { ?>
                    <td class="text-right text-slate text-nowrap"><?= number_format($lineTotal * 10 / 11, intval($lineTotal) == $lineTotal ? 0 : 2) ?></td>
                    <td class="text-right text-slate small text-nowrap vat"><?= number_format($lineTotal / 11, intval($lineTotal) == $lineTotal ? 0 : 2) ?></td>
                        <?php } else { ?>
                    <td class="text-center text-muted small"></td>
                    <td class="text-center text-muted small vat">NO VAT</td>
                        <?php } ?>
                    <?php } ?>
                    <td class="text-right text-pink text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($lineTotal, intval($lineTotal) == $lineTotal ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></td>
                    <td>
                        <?php if ($cpt['crfund'] == 'yes') { ?><i title="CR Fund / Quỹ QHKH" class="fa fa-quora text-warning"></i><?php } ?>
                        <?= $cpt['payer'] ?></td>
                    <td class="text-muted small m-tk">NO TK</td>
                    <td><?= $cpt['due'] == '0000-00-00' ? '' : $cpt['due'] ?></td>
                    <td>
                        <!--
                        <i title="Check 1" class="check check1 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 2" class="check check2 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 3" class="check check3 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 4" class="check check4 text-muted fa fa-circle-o cursor-pointer"></i>
                        -->
                        <?php
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
                    <td class="text-nowrap">
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
                        <?php if (!$cptPaidInFull) { ?>
                        <a title="Đánh dấu đã TT 100%" href="#" class="badge badge-secondary mark-paid" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT</a>
                        <a title="Sửa TT riêng mục này" href="/cpt/r/<?= $cpt['dvtour_id'] ?>?action=new-mtt" class="badge badge-secondary" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT+</a>
                            <?php if ($cptInBasket) { ?>
                        <a title="Bỏ Đề nghị thanh toán" href="#" class="badge badge-info add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
                            <?php } else { ?>
                        <a title="Đề nghị thanh toán" href="#" class="badge badge-secondary add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
                            <?php } ?>
                        <?php } else { ?>
                        <i title="Đã thanh toán toàn bộ" class="fa fa-lock text-success"></i>
                        <?php } ?>
                    </td>
                    <td>
                        <a title="Đánh dấu đã lấy hoá đơn" href="#" class="badge badge-<?= $cpt['vat_ok'] == 'ok' ? 'success' : 'secondary' ?> vat-ok" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">HĐ</a>
                    </td>

                    <td class="text-center td-check">
                        <label class="m-1 w-100 h-100"><input type="checkbox" class="c-check" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-venue_id="<?= $cpt['venue_id'] ?>"></label>

                    </td>
                </tr>
