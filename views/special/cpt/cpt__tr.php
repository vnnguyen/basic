<?

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return number_format($str, intval($str) == $str ? 0 : 2);
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
                        <span title="<?= $cpt['updatedBy']['name'] ?> @ <?= date('j/n/Y H:i', strtotime($cpt['updated_at'])) ?>"><?= $cpt['dvtour_name'] ?></span>
                        <? //} ?>

                        @<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>
                        <? if ($cpt['company']) { ?>
                        $<?= Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['company']['id']) ?>
                        <? } else { ?>
                            <? if ($cpt['oppr'] != '') { ?>
                        $<?= $cpt['oppr'] ?>
                            <? } ?>
                        <? } ?>
                        <? if ($cpt['venue_id'] != 0) { ?>
                        <?= Html::a('<i class="fa fa-external-link"></i>', '/venues/r/'.$cpt['venue_id'], ['class'=>'text-muted', 'target'=>'_blank', 'title'=>'Xem '.$cpt['venue']['name']]) ?>
                        <? } ?>
                    </td>

                    <td class="text-center"><?= (real)$cpt['qty'] ?></td>
                    <td class="text-center text-muted"><?= $cpt['unit'] ?></td>
                    <td class="text-right text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                    <?
                    $lineTotal = $cpt['price'] * $cpt['qty'];
                    ?>
                    <td class="text-right text-pink text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($lineTotal, intval($lineTotal) == $lineTotal ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></td>
                    <td><?= $cpt['payer'] ?></td>
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
                        ?><span title="<?= number_format($mtt['amount'], intval($mtt['amount']) == $mtt['amount'] ? 0 : 2) ?> <?= $cpt['unitc'] ?><?= $cpt['unitc'] == $mtt['currency'] ? '' : ' ='.$mtt['currency'] ?>" class="label label-<?= $mtt['check'] == '' ? 'info' : 'success' ?>"><?
                                if ($mtt['paid_in_full'] == 'yes') {
                                    $cptPaidInFull = true;
                                    echo 'TT 100%';
                                } else {
                                    echo 'TT';
                                }
                        ?></span> <?
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
                        <? } ?>
                    </td>
                    <td>
                        <?= Html::a($cpt['updatedBy']['name'], '?updatedby='.$cpt['updated_by'], ['title'=>Yii::$app->formatter->asRelativetime($cpt['updated_at'])]) ?>
                    </td>
                </tr>
