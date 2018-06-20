<?
use yii\helpers\Html;

$this->title  = 'Danh sách thành viên Amica ('.count($models).')';
$this->params['icon'] = 'font';
$this->params['breadcrumb'] = [
    ['Community', '@web/community'],
    ['Knowledge base', '@web/kb'],
    ['Lists', '@web/kb/lists'],
    ['Thành viên', '@web/kb/lists/members'],
];
?>
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="active"><a href="#t1" data-toggle="tab">Cá nhân</a></li>
                <li><a href="#t2" data-toggle="tab">Nhóm</a></li>
            </ul>
        </div>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="t1">
            <? if (empty($models)) { ?><p>No data found</p><? } else { ?>
            <div class="table-responsive">
                <table class="table table-condensed table-xxs">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Họ tên</th>
                            <th>NS</th>
                            <th>Công việc & Văn phòng</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($models as $li) { ?>
                        <tr>
                            <td>
                                <? if ($li['image'] == '') { ?>
                                <img src="http://www.gravatar.com/avatar/<?= md5($li['email']) ?>?d=wavatar" style="width:42px; float:left;" class="img-circle">
                                <? } else { ?>
                                <img src="<?= DIR ?>timthumb.php?w=100&h=100&zc=1&src=<?= $li['image'] ?>" style="width:42px; float:left;" class="img-circle">
                                <? } ?>
                            </td>
                            <td>
                                <?= Html::a($li['name'], '@web/users/r/'.$li['id']) ?>
                            </td>
                            <td class="text-center"><?= $li['bday'] ?>/<?= $li['bmonth'] ?></td>
                            <td><?= $li['profileMember']['position'] ?>, <?= $li['profileMember']['unit'] ?> (<?= $li['profileMember']['location'] ?>)</td>
                            <td><?= $li['email'] ?></td>
                            <td><?= $li['phone'] ?></td>
                            <td><?
                                foreach ($li['metas'] as $lii) {
                                    if ($lii['k'] == 'facebook') {
                                        echo Html::a('<i class="fa fa-facebook"></i>', $lii['v'], ['target'=>'_blank', 'style'=>'color:#3b5998']);
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <? } ?>
                    </tbody>
                </table>
            </div>
            <? } ?>
        </div>
        <div class="tab-pane" id="t2">
            <div class="alert alert-info">
                Chú ý:
                <br>- Mr Nicolas Vidal không nằm trong nhóm nào (muốn anh ấy đọc, bạn phải gửi thêm cho anh ấy).
                <br>- Mr Mạnh và Ms Nga nhân sự cũng ở trong và nhận được email của các nhóm nghiệp vụ (điều hành, bán hàng, kế toán, dv đầu vào, cskh, marketing).
                <br>- Tất cả các nhóm đều chỉ có địa chỉ email đuôi <strong>@amicatravel.com</strong>, không có địa chỉ nhom@amica-travel.com hoặc nhom@amicatravel.org như email cá nhân.
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Email nhóm</th>
                            <th>Miêu tả</th>
                            <th>Số người</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>group.lanhdao@amicatravel.com</td><td>Lãnh đạo công ty (founders)</td><td>3</td>
                        </tr>
                        <tr>
                            <td>group.quanly@amicatravel.com</td><td>Các thành viên trưởng phó các bộ phận + anh Mạnh</td><td>10</td>
                        </tr>
                        <tr>
                            <td>group.banhang@amicatravel.com</td><td>Bộ phận Bán hàng tại Hà Nội, Hoa Bearez, 3 lãnh đạo</td><td>20</td>
                        </tr>
                        <tr>
                            <td>group.dieuhanh@amicatravel.com</td><td>Bộ phận Điều hành tại Hà Nội</td><td>6</td>
                        </tr>
                        <tr>
                            <td>group.dvdv@amicatravel.com</td><td>Bộ phận Dịch vụ đầu vào</td><td>2</td>
                        </tr>
                        <tr>
                            <td>group.cskh@amicatravel.com</td><td>Bộ phận Chăm sóc khách hàng + Phương Anh</td><td>4</td>
                        </tr>
                        <tr>
                            <td>group.marketing@amicatravel.com</td><td>Bộ phận Marketing (tiếng Anh + Pháp) + designer</td><td>7</td>
                        </tr>
                        <tr>
                            <td>group.ketoan@amicatravel.com</td><td>Bộ phận Kế toán tại Hà Nội</td><td>4</td>
                        </tr>
                        <tr>
                            <td>group.tang3@amicatravel.com</td><td>Mọi nhân viên làm việc ở tầng 3 tại vp Hà Nội (trừ 3 lãnh đạo và Mr Hiển)</td><td>17</td>
                        </tr>
                        <tr>
                            <td>group.tang5@amicatravel.com</td><td>Mọi nhân viên làm việc ở tầng 5 tại vp Hà Nội (trừ 3 lãnh đạo)</td><td>19</td>
                        </tr>
                        <tr>
                            <td>group.tang6@amicatravel.com</td><td>Mọi nhân viên làm việc ở tầng 6 tại vp Hà Nội (trừ 3 lãnh đạo)</td><td>13</td>
                        </tr>
                        <tr>
                            <td>group.huongdan@amicatravel.com</td><td>Các Hướng dẫn viên của công ty tại Hà Nội + anh Hà</td><td>9</td>
                        </tr>
                        <tr>
                            <td>group.hanoi@amicatravel.com</td><td>Các thành viên văn phòng Hà Nội (3 lãnh đạo, nhân viên). Chú ý: không có Hướng dẫn</td><td></td>
                        </tr>
                        <tr>
                            <td>group.saigon@amicatravel.com</td><td>Các thành viên văn phòng Saigon</td><td>2</td>
                        </tr>
                        <tr>
                            <td>group.siemreap@amicatravel.com</td><td>Các thành viên văn phòng Siem Reap, Cambodia</td><td>4</td>
                        </tr>
                        <tr>
                            <td>group.amica@amicatravel.com</td><td>Mọi thành viên Amica ở tất cả các vp. Chú ý: không có Nicolas Vidal + Hướng dẫn</td><td>62</td>
                        </tr>
                    </tbody>
                </table>
                <p>Danh sách này cập nhật ngày 26/01/2015.</p>
            </div>
        </div>
    </div>

    </div>
</div>