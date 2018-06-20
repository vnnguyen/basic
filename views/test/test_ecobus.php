<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$say = [
    'smile'=>'Likes',
    'frown'=>'Dislikes',
    'meh'=>'Comments',
];

// include('_feedbacks_inc.php');

Yii::$app->params['page_title'] = 'Lịch xe Ecobus từ Thứ năm 25/8/2016';
?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tour</th>
                    <th>Ngày khởi hành</th>
                    <th>Ngày kết thúc</th>
                    <th>Tên khách</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Quốc tịch</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <pre>
ĐIỂM ĐÓN / TRẢ CHUYẾN
1
CHUYẾN
2
CHUYẾN
3
CHUYẾN
4
CHUYẾN
5
CHUYẾN
6
ECOPARK 6:00 6:40 9:00 12:00 16:00 17:10
ĐIỂM TRẢ GIỜ TRẢ
S 2 huất Duy Tiến 6:30 7:10 9:30 12:30 16:30 17:40
S 117 Trần Duy Hưng (Tòa nhà Grand Plaza) 6:35 7:15 9:35 12:35 16:35 17:45
S 45 Trần Duy Hưng 6:38 7:18 9:38 12:38 16:38 17:48
S 91 Nguy n Chí Thanh 6:40 7:20 9:40 12:40 16:40 17:50
Trước Tòa nhà Lotte - Đào Tấn 6:45 7:25 9:45 12:45 16:45 17:55
S 70 Nguy n h nh Toàn 6:50 7:30 9:50 12:50 16:50 18:00
S 66 Nguy n Văn Huyên 6:52 7:32 9:52 12:52 16:52 18:02
S 98 Hoàng Qu c Vi t 6:55 7:35 9:55 12:55 16:55 18:05
ĐIỂM ĐÓN GIỜ ĐÓN
S 239 Hoàng Qu c Vi t 6:57 7:37 9:57 12:57 16:57 18:07
S 64 Nguy n Văn Huyên (Công viên Nghĩa Đô) 7:00 7:40 10:00 13:00 17:00 18:10
S 7 Lô 3 Nguy n h nh Toàn 7:03 7:43 10:03 13:03 17:03 18:13
Đ i di n Tòa nhà Lotte - Đào Tấn 7:10 7:50 10:10 13:10 17:10 18:20
S 62 Nguy n Chí Thanh
(Nhà kh ch Cơ Yếu Chính Phủ) 7:15 7:55 10:15 13:15 17:15 18:25
S 40 Trần Duy Hưng 7:20 8:00 10:20 13:20 17:20 18:30
S 198 Trần Duy Hưng 7:22 8:02 10:22 13:22 17:22 18:32
S 107 - C3 huất Duy Tiến 7:30 8:10 10:30 13:30 17:30 18:40
ECOPARK 8:00 8:40 11:00 14:00 18:00 19:10
        </pre>
    </div>
</div>