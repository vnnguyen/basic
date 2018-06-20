<?php

Yii::$app->params['page_title'] = 'Noel 2016 pax list - request from Cao Phuong Nhung';
Yii::$app->params['page_breadcrumbs'] = [
    ['Special', 'special'],
    ['Nhung CP', 'special/nhungcp'],
    ['Noel 2016 pax list']
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Yeu cau Cao Phuong Nhung 14/11/2016</h6>
        </div>
        <div class="panel-body">
            <pre>
Dear anh Huân,

Để chuẩn bị cho việc tri ân khách nhân dịp Noel, em muốn nhờ anh xuất giúp một số danh sách như sau:

1. Danh sách khách hàng của Amica, có thêm tên của Bán hàng và CSKH đã giao dịch với khách gần đây nhất
Các trường cần có :
1. M / Mme
2. Họ và tên khách hàng
2. Email khách hàng
3. Code tour/ ID
3. Sale gần đây nhất đã từng làm việc với khách đó
4. CSKH gần nhất từng care khách đó
 
2. Danh sách khách hàng VIP của Amica – Đã đi tour với Amica từ 3 lần trở lên
Các trường cần có :
1. M / Mme
2. Họ và tên khách hàng
3. Email khách hàng
4. Các tour đã đi với Amica
 
3. Danh sách khách hàng Ambassadeurs – Đã giới thiệu cho Amica 2 người bạn trở lên
Các trường cần có :
1. M / Mme
2. Họ và tên khách hàng
3. Email khách hàng
 
4. Danh sách khách lost – Đã contact hỏi tour nhưng không mua tour 
Các trường cần có :
1. M / Mme
2. Họ và tên khách hàng
3. Email khách hàng
 
Anh Huân giúp em xuất các danh sách trên anh nhé. Em cảm ơn anh nhiều. 

Em Nhung
            </pre>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">1. Danh sach khach hang</h6>
        </div>
        <div class="panel-body">
            <a href="?action=download&what=list1">Link download (dữ liệu cũ)</a>
            |
            <a href="?action=download&what=list1a">Link download (dữ liệu mới)</a>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">2. Danh sach khach hang da di tour voi Amica 3 lan tro len</h6>
        </div>
        <div class="panel-body">
            <a href="https://my.amicatravel.com/customers?year=&code=&fname=&lname=&gender=all&age=&country=&address=&email=&bcount=3&rcount=0&output=view">Link xem</a>
            |
            <a href="https://my.amicatravel.com/customers?year=&code=&fname=&lname=&gender=all&age=&country=&address=&email=&bcount=3&rcount=0&output=download">Link download</a>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">3. Danh sach khach hang da gioi thieu 2 HS tro len</h6>
        </div>
        <div class="panel-body">
            <a href="https://my.amicatravel.com/customers?year=&code=&fname=&lname=&gender=all&age=&country=&address=&email=&bcount=0&rcount=2&output=view">Link xem</a>
            |
            <a href="https://my.amicatravel.com/customers?year=&code=&fname=&lname=&gender=all&age=&country=&address=&email=&bcount=0&rcount=2&output=download">Link download</a>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">4. Danh sach khach lost - da hoi ma khong mua tour</h6>
        </div>
        <div class="panel-body">
            <a href="?action=download&what=list4">Link download (cả LOST và đang bán, chưa mua tour)</a>
            |
            <a href="?action=download&what=list4&status=lost-only">Link download (chỉ LOST)</a>
        </div>
    </div>
</div>