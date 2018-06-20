<style>
h1.title {text-align:center;}
h2.subtitle {text-align:center;}
.table td, .table th {padding:4px;}
.table-intro {margin-top:1cm; margin-bottom:1cm;}
.table-main {margin-bottom:1cm;}
    .table-main td, .table-main th {border:1px solid #777;}
</style>
<div class="col-md-12">
    <h1 class="title"><?= Yii::t('in-hd', 'GIẤY ĐỀ NGHỊ TẠM ỨNG TIỀN TOUR') ?></h1>
    <h2 class="subtitle">(dành cho hướng dẫn viên trước khi đi tour)</h2>

    <table class="table-intro table table-borderless">
        <tbody>
            <tr>
                <th width="20%">Họ tên người đề nghị</th><td>Tôn Nữ Thiên Tân</td>
                <th width="20%">Bộ phận (hoặc Địa chỉ)</th><td>Bộ phận Dịch vụ Khách hàng</td>
            </tr>
            <!-- <tr><td colspan="4">đề nghị được tạm ứng số tiền theo nội dung dưới đây</td></tr> -->
            <tr>
                <th>Hình thức tạm ứng</th><td>Tiền mặt</td>
                <th>Thời hạn hoàn ứng</th><td>12/12/2017</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-narrow table-main table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Nội dung</th>
                <th>Số tiền</th>
                <th>Chứng từ</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <? foreach (range(1, 10) as $line) { ?>
            <tr>
                <td>1</td>
                <td>This is my content</td>
                <td class="text-right">123,456 VND</td>
                <td>CPT 1223344</td>
                <td></td>
            </tr>
            <? } ?>
            <tr>
                <th class="text-right" colspan="2">Tổng cộng</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td class="text-right" colspan="2">Số tiền bằng chữ</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-borderless table-signatures text-center">
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td class="text-center">Ngày 12/2/2017</td>
            </tr>
            <tr>
                <th width="33%" class="text-center">
                Kế toán
                </th>
                <th width="33%" class="text-center">
                Trưởng bộ phận
                </th>
                <th width="33%" class="text-center">
                Người đề nghị
                </th>
            </tr>
            <tr>
                <td style="height:3cm"></td>
                <td style="height:3cm"></td>
                <td style="height:3cm"></td>
            </tr>
            <tr>
                <td class="text-center">Ngô Duy Long</td>
                <td class="text-center">Phạm Ngân Hà</td>
                <td class="text-center">Tôn Nữ Thiên Tân</td>
            </tr>
        </tbody>
    </table>
</div>