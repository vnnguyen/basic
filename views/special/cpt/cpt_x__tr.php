<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
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

                        <? if ($cpt['cp']) { ?>
                        <?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['cp']['id'])?>
                        <? } else { ?>
                        <span title="<?= $cpt['updatedBy']['name'] ?> @ <?= date('j/n/Y H:i', strtotime($cpt['updated_at'])) ?>"><?= $cpt['dvtour_name'] ?></span>
                        <? } ?>

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
                    <!--td>
                        <?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['cp_id'], ['style'=>'color:#060']) ?>
                        @<?= Html::a($cpt['cp']['venue']['name'], '@web/venues/r/'.$cpt['cp']['venue']['id'], ['style'=>'color:#f60']) ?>
                    </td-->
                    <!--
                    <td>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><?= $cpt['b_status'] ?></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a href="#">Draft</a></li>
                                <li>Planned</li>
                                <li>Ready, pre-booking not needed</li>
                                <li>Sent to provider</li>
                                <li>Waiting list</li>
                                <li>Ready</li>
                                <li>Canceled and none</li>
                                <li>Canceled and replaced</li>
                            </ul>
                        </div>
                    </td>
                    <td><?= $cpt['p_status'] ?></td>
                    -->
                    <td class="text-right"><?= rtrim(rtrim($cpt['qty'], '0'), '.') ?></td>
                    <td class="text-muted"><?= $cpt['unit'] ?></td>
                    <td class="text-right text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= rtrim(rtrim(number_format($cpt['price'], 2), '0'), '.') ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                    <td class="text-right text-danger text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= rtrim(rtrim(number_format($cpt['price'] * $cpt['qty'], 2), '0'), '.') ?> <span class="text-muted"><?= $cpt['unitc'] ?></td>
                    <td class="text-nowrap"><?= $cpt['payer'] ?></td>
                    <td class="text-nowrap"><?= $cpt['due'] == '0000-00-00' ? '' : $cpt['due'] ?></td>
                    <td>
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
                </tr>
