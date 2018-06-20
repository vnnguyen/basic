<?php

return [
    'print_logo'=>'https://my.amicatravel.com/assets/img/logo_161127_mcs_800x311.jpg',
    'print_logo_si'=>'https://my.amicatravel.com/assets/img/logo_si_160922_1248x664.jpg',
    'active_languages'=>['en', 'vi', 'fr'],
    'active_currencies'=>['CNY', 'EUR', 'KHR', 'LAK', 'THB', 'USD', 'VND'],

    'brand_name'=>'AMICA TRAVEL',

    // Page elements
    'page_layout'=>'',
    'page_body_class'=>'',

    'page_title'=>'',
    'page_small_title'=>'',
    'page_meta_title'=>'',
    'page_encode_title'=>true,
    'page_icon'=>'',
    'page_icon_class'=>'',
    'page_header'=>'',

    'page_layout'=>false,
    'body_class'=>false,
    'hide_page_actions'=>false,

    // Dịch vụ đầu vào
    'dvvTypeList'=>[
        'room'=>'Room',
        'sight'=>'Sight-seeing',
        'perf'=>'Performance',
        'learn'=>'Learning, Studying',
        'shop'=>'Shopping',
        'health'=>'Health & Leisure',
        'air'=>'Tickets',
        'transport'=>'Transportation',
        'other'=>'Other',
        'inter'=>'Interpreter',
        'guide'=>'Tour guide',
        'human'=>'Porters & workers',
    ],

    'systemCurrencyList'=>[
        ['code'=>'VND', 'name_en'=>'Vietnamese Dong', 'name_vi'=>'Đồng Việt Nam', 'default'=>true],
        ['code'=>'USD', 'name_en'=>'United States Dollar', 'name_vi'=>'Đô-la Mỹ', 'default'=>false],
        ['code'=>'EUR', 'name_en'=>'Euro', 'name_vi'=>'Đô-la Mỹ', 'default'=>false],
        ['code'=>'KHR', 'name_en'=>'Cambodian Riel', 'name_vi'=>'Riel', 'default'=>false],
        ['code'=>'LAK', 'name_en'=>'Laotian Kip', 'name_vi'=>'Kip', 'default'=>false],
        ['code'=>'AUS', 'name_en'=>'Australian Dollar', 'name_vi'=>'Đô-la Úc', 'default'=>false],
    ],

    'amica/blog/cats'=>[
        ['id'=>01, 'name'=>'(Không phân loại)', 'list'=>''],
        ['id'=>02, 'list'=>'Tin công ty'],
        ['id'=>03, 'name'=>'Tin nhân sự', 'list'=>'--- '],
        ['id'=>04, 'name'=>'Tin công đoàn', 'list'=>''],
        ['id'=>05, 'name'=>'Thông báo', 'list'=>''],
        ['id'=>06, 'name'=>'Sự kiện', 'list'=>''],
        ['id'=>07, 'name'=>'Tin khác', 'list'=>''],
    ],

    'amica/kb/cats'=>[
        ['id'=>1, 'depth'=>1, 'name'=>'Công ty Amica Travel', 'description'=>''],
        ['id'=>2, 'depth'=>2, 'name'=>'Quá trình phát triển', 'description'=>''],
        ['id'=>3, 'depth'=>2, 'name'=>'Quy định, chính sách', 'description'=>''],
        ['id'=>4, 'depth'=>2, 'name'=>'Dành cho thành viên mới', 'description'=>''],
        ['id'=>5, 'depth'=>1, 'name'=>'Chuyên môn - nghiệp vụ', 'description'=>''],
        ['id'=>6, 'depth'=>2, 'name'=>'Dịch vụ đầu vào', 'description'=>''],
        ['id'=>7, 'depth'=>2, 'name'=>'Sản phẩm', 'description'=>''],
        ['id'=>8, 'depth'=>2, 'name'=>'Marketing', 'description'=>''],
        ['id'=>9, 'depth'=>2, 'name'=>'Bán hàng', 'description'=>''],
        ['id'=>10, 'depth'=>2, 'name'=>'Điều hành tour', 'description'=>''],
        ['id'=>11, 'depth'=>2, 'name'=>'Chăm sóc khách hàng', 'description'=>''],
        ['id'=>12, 'depth'=>2, 'name'=>'Kế toán', 'description'=>''],
        ['id'=>13, 'depth'=>2, 'name'=>'Quản lý', 'description'=>''],
        ['id'=>14, 'depth'=>2, 'name'=>'Nhân sự', 'description'=>''],
        ['id'=>15, 'depth'=>2, 'name'=>'IT', 'description'=>''],
        ['id'=>16, 'depth'=>1, 'name'=>'Chủ đề khác', 'description'=>''],
        ['id'=>0, 'depth'=>1, 'name'=>'(Không phân loại)', 'description'=>''],
    ],
];
