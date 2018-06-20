<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


$points = [
    "1" => ['label' => 'DONG VAN', 'data' => '465,65'],
    "2" => ['label' => 'BAO LAC', 'data' => '513,114'],
    "3" => ['label' => 'CAO BANG', 'data' => '577,142'],
    "4" => ['label' => 'HA GIANG', 'data' => '422,132'],
    "5" => ['label' => 'LANG SON', 'data' => '635,234'],
    "6" => ['label' => 'HA NOI', 'data' => '524,331'],
    "7" => ['label' => 'BA BE', 'data' => '508,173'],
    "8" => ['label' => 'BAC HA', 'data' => '345,149'],
    "9" => ['label' => 'SA PA', 'data' => '297,173'],
    "10" => ['label' => 'LAO CAI', 'data' => '310,164'],
    "11" => ['label' => 'MUONG HUM', 'data' => '272,155'],
    "12" => ['label' => 'MU CANG CHAI', 'data' => '327,248'],
    "13" => ['label' => 'NGHIA LO', 'data' => '366,259'],
    "14" => ['label' => 'YEN BAI', 'data' => '405,249'],
    "15" => ['label' => 'THAC BA', 'data' => '427,251'],
    "16" => ['label' => 'HOA BINH', 'data' => '472,362'],
    "17" => ['label' => 'MAI CHAU', 'data' => '437,383'],
    "18" => ['label' => "BAIE D'ALONG", 'data' => '680,332'],
    "19" => ['label' => 'RED RIVER DELTA', 'data' => '572,373'],
    "20" => ['label' => 'NINH BINH', 'data' => '526,418'],
    "21" => ['label' => 'VINH', 'data' => '503,624'],
    "22" => ['label' => 'PHONG NHA', 'data' => '558,760'],
    "23" => ['label' => 'DONG HOI', 'data' => '620,767'],
    "24" => ['label' => 'VINH MOC', 'data' => '676,819'],
    "25" => ['label' => 'DONG HA', 'data' => '689,853'],
    "26" => ['label' => 'KHE SANH', 'data' => '635,870'],
    "27" => ['label' => 'A LUOI', 'data' => '696,910'],
    "28" => ['label' => 'HUE', 'data' => '750,887'],
    "29" => ['label' => 'DA NANG', 'data' => '812,932'],
    "30" => ['label' => 'CU LAO CHAM', 'data' => '854,944'],
    "31" => ['label' => 'HOI AN', 'data' => '831,966'],
    "32" => ['label' => 'MY SON', 'data' => '818,976'],
    "33" => ['label' => 'KHAM DUC', 'data' => '756,1012'],
    "34" => ['label' => 'MY LAI', 'data' => '890,1043'],
    "35" => ['label' => 'KON TUM', 'data' => '774,1140'],
    "36" => ['label' => 'CENTRAL HIGHLANDS', 'data' => '846,1267'],
    "37" => ['label' => 'SIX SENSE', 'data' => '945,1375'],
    "38" => ['label' => 'NHA TRANG', 'data' => '929,1400'],
    "39" => ['label' => 'HON BA', 'data' => '923,1427'],
    "40" => ['label' => 'DA LAT', 'data' => '853,1427'],
    "41" => ['label' => 'NAM NUNG FOREST', 'data' => '772,1400'],
    "42" => ['label' => 'BU PRANG', 'data' => '723,1401'],
    "43" => ['label' => 'BU GIA MAP', 'data' => '687,1410'],
    "44" => ['label' => 'GIA NGHIA', 'data' => '740,1413'],
    "45" => ['label' => 'TAY NINH', 'data' => '555,1504'],
    "46" => ['label' => 'MUI NE', 'data' => '821,1552'],
    "47" => ['label' => 'LONG XUYEN', 'data' => '473,1622'],
    "48" => ['label' => 'CAI BE', 'data' => '546,1624'],
    "49" => ['label' => 'VINH LONG', 'data' => '520,1641'],
    "50" => ['label' => 'SA DEC', 'data' => '540,1661'],
    "51" => ['label' => 'PHUOC LONG', 'data' => '600,1653'],
    "52" => ['label' => 'TRA ON', 'data' => '540,1669'],
    "53" => ['label' => 'CAN THO', 'data' => '515,1669'],
    "54" => ['label' => 'RACH GIA', 'data' => '436,1684'],
    "55" => ['label' => 'PHU QUOC', 'data' => '299,1648'],
    "56" => ['label' => 'CON DAO', 'data' => '618,1820'],
    "57" => ['label' => 'TRA VINH', 'data' => '550,1709'],
    "58" => ['label' => 'HO CHI MINH VILLE (SAIGON)', 'data' => '625,1570'],
    "227" => ['label' => 'CHAU DOC', 'data' => '438,1588'],
    "229" => ['label' => 'BEN TRE', 'data' => '596,1639'],
    "233" => ['label' => 'HA TIEN', 'data' => '406,1649'],
    "235" => ['label' => 'HAI PHONG', 'data' => '624,347'],
    "237" => ['label' => 'SON LA', 'data' => '299,302'],
    "239" => ['label' => 'DIEN BIEN', 'data' => '190,293'],
    "245" => ['label' => 'QUY NHON', 'data' => '936,1218'],
    "247" => ['label' => 'NINH THUAN', 'data' => '899,1474'],
    "253" => ['label' => 'DONG NAI', 'data' => '711,1495'],
    "254" => ['label' => 'BUON MA THUOT', 'data' => '785,1343'],

    "255" => ["label" =>"LAI CHAU", "data" => "229,162"],
    "257" => ["label" =>"MEO VAC", "data" => "476,74"],
    "258" => ["label" =>"KY SON", "data" => "471,354"],
    "259" => ["label" =>"CAT BA", "data" => "666,364"],
    "260" => ["label" =>"MY THO", "data" => "588,1621"],
    "261" => ["label" =>"GO CONG", "data" => "617,1624"],
    "262" => ["label" =>"CU CHI", "data" => "617,1557"],
    "263" => ['label' => 'HOANG SU PHI', 'data' => '378,122'],
    // bo sung 7/6/2018
    "264" => ["label" =>"MOC CHAU", "data" => "416,353"],
    "265" => ["label" =>"SUOI MU", "data" => "446,396"],
    "266" => ["label" =>"SUOI THAU", "data" => "405,128"],
    "267" => ["label" =>"TA LAI", "data" => "720,1524"],
    "268" => ["label" =>"PU LUONG", "data" => "438,361"],
    "270" => ["label" =>"PHAN RANG", "data" => "924,1455"],
    "271" => ["label" =>"VINH HY", "data" => "928,1454"],
    "272" => ["label" =>"VUNG TAU", "data" => "678,1623"],
    "273" => ["label" =>"THU DAU MOT", "data" => "635,1554"],
    "274" => ["label" =>"CAM RANH", "data" => "930,1430"],
    "275" => ["label" =>"THONG NONG", "data" => "537,113"],
    "276" => ["label" =>"SOC TRANG", "data" => "549,1725"],
    "277" => ["label" =>"NAM NGUA", "data" => "541,120"],

];
$jsonPoints = json_encode($points);
$pointsLaos = [
    "59" => ['label' => 'BAN LO MA', 'data' => '525,261'],
    "60" => ['label' => 'PAK NAM NOI', 'data' => '500,277'],
    "61" => ['label' => 'MUANG KHUA', 'data' => '524,293'],
    "62" => ['label' => 'NONG KHIAO', 'data' => '538,400'],
    "63" => ['label' => 'HOUAY SAY', 'data' => '152,459'],
    "64" => ['label' => 'OUDOMXAY', 'data' => '391,483'],
    "65" => ['label' => 'PAK OU', 'data' => '501,507'],
    "66" => ['label' => 'VIENG THONG', 'data' => '737,406'],
    "67" => ['label' => 'PAKBENG', 'data' => '277,558'],
    "68" => ['label' => 'LUANG PRABANG', 'data' => '470,543'],
    "69" => ['label' => 'KOUANG SI', 'data' => '501,559'],
    "70" => ['label' => 'PHONGSAVAN', 'data' => '756,580'],
    "71" => ['label' => 'XIENG KHOUANG', 'data' => '788,593'],
    "72" => ['label' => 'VANG VIENG', 'data' => '469,700'],
    "73" => ['label' => 'VIENTIANE', 'data' => '549,902'],
    "74" => ['label' => 'XEKONG', 'data' => '1375,1247'],
    "75" => ['label' => 'PAKSÉ', 'data' => '1180,1468'],
    "76" => ['label' => 'CHAMPASSAK', 'data' => '1171,1486'],
    "77" => ['label' => 'WAT PHOU', 'data' => '1163,1496'],
    "78" => ['label' => 'ILE DON DAENG', 'data' => '1120,1571'],
    "79" => ['label' => '4000 ILES', 'data' => '1114,1594'],
    "80" => ['label' => 'ILE DON KHONE', 'data' => '1117,1615'],
    "81" => ['label' => 'ILE DON KHONE', 'data' => '1153,1625'],
    "81" => ['label' => 'ILE DON KHONE', 'data' => '1153,1625'],
    "231" => ['label' => 'LUANG NAMTHA', 'data' => '324,347'],
    "241" => ['label' => 'MUANG NGOI', 'data' => '572,388'],
    "243" => ['label' => 'SEKONG', 'data' => '1335,1392'],
    "249" => ['label' => 'SAVANNAKHET', 'data' => '962,1175'],
    "252" => ['label' => 'KHAMMOUANE', 'data' => '1090,995'],
    "253" => ['label' => 'BOLOVENS', 'data' => '1216,1398'],
    "254" => ["label" =>"SAINYABULI", "data" => "332,708"],

    // bo sung 7/6/2018
    "255" => ["label" =>"MUANG LA LODGE", "data" => "479,388"],
    "256" => ["label" =>"PHONG SALY", "data" => "410,191"],
    "257" => ["label" =>"BOUN TAI", "data" => "433,264"],
    "258" => ["label" =>"PAK NAM NOY", "data" => "474,338"],

];
$jsonPointsLaos = json_encode($pointsLaos);
$poitsCambodge = [
    "82" => ['label' => 'PREAH VIHEAR', 'data' => '952,225'],
    "83" => ['label' => 'ANLONG VENG', 'data' => '781,266'],
    "84" => ['label' => 'POIPET', 'data' => '369,521'],
    "85" => ['label' => 'BANTEAY SREI', 'data' => '788,440'],
    "86" => ['label' => 'KOH KER', 'data' => '1029,410'],
    "87" => ['label' => 'PREAH RUMKEL', 'data' => '1292,357'],
    "88" => ['label' => 'RATANAKIRI', 'data' => '1586,456'],
    "89" => ['label' => 'PREK TOAL', 'data' => '574,508'],
    "90" => ['label' => 'ANGKOR', 'data' => '750,477'],
    "91" => ['label' => 'STUNG TRENG', 'data' => '1314,466'],
    "92" => ['label' => 'BATTAMBANG', 'data' => '499,576'],
    "93" => ['label' => 'DAM DACK', 'data' => '814,567'],
    "94" => ['label' => 'KRATIE', 'data' => '1334,570'],
    "95" => ['label' => 'PAILIN', 'data' => '403,686'],
    "96" => ['label' => 'PURSAT', 'data' => '723,654'],
    "97" => ['label' => 'KOMPONG THOM', 'data' => '941,637'],
    "98" => ['label' => 'PHNOMPRICH', 'data' => '1591,648'],
    "99" => ['label' => 'BOPING', 'data' => '890,687'],
    "100" => ['label' => 'MONDULKIRI', 'data' => '1530,670'],
    "101" => ['label' => 'CHNOK TRU', 'data' => '850,726'],
    "102" => ['label' => 'KAMPONG CHNANG', 'data' => '922,761'],
    "103" => ['label' => 'OU DONG', 'data' => '989,900'],
    "104" => ['label' => 'KOMPONG CHAM', 'data' => '1170,908'],
    "105" => ['label' => 'SENMONOROM', 'data' => '1631,796'],
    "106" => ['label' => 'KOH KONG', 'data' => '476,1008'],
    "107" => ['label' => 'TA TAI', 'data' => '503,1003'],
    "108" => ['label' => 'PHNOM PENH', 'data' => '1019,988'],
    "109" => ['label' => 'TAKEO', 'data' => '986,1113'],
    "110" => ['label' => 'ILES ET CÔTES KHMERS', 'data' => '556,1251'],
    "111" => ['label' => 'KAMPOT', 'data' => '754,1284'],
    "112" => ['label' => 'SIHANOUKVILLE', 'data' => '641,1286'],
    "113" => ['label' => 'KEP', 'data' => '855,1318'],
    "114" => ["label" => "KOH RONG SANLOEM", "data" => "560,1297"],
    // bo sung 7/6/2018
    "115" => ["label" =>"KOH RONG SALOEM", "data" => "559,1293"],
];
$jsonPointsCambodge = json_encode($poitsCambodge);
$transports = [
    "ap" => ['label' => 'AIRPLANE', 'color' => '#6b5e90'],
    "tr" => ['label' => 'TRAIN', 'color' => '#f07281'],
    "ca" => ['label' => 'CAR', 'color' => '#f59c84'],
    "mt" => ['label' => 'MOTOBIKE', 'color' => '#355e7d'],
    "bc" => ['label' => 'BICYCLE', 'color' => '#c76c98'],
    "bo" => ['label' => 'BOAT', 'color' => '#622d6b'],
    "wa" => ['label' => 'Walk', 'color' => '#cdcdcd'],
];
$jsonTransports = json_encode($transports);
$pointsMulti = [
    "114" => ['label' => 'DONG VAN', 'data' => '771,77'],
    "115" => ['label' => 'BAO LAC', 'data' => '824,131'],
    "116" => ['label' => 'CAO BANG', 'data' => '896,163'],
    "117" => ['label' => 'HA GIANG', 'data' => '719,157'],
    "118" => ['label' => 'LANG SON', 'data' => '963,268'],
    "119" => ['label' => 'HA NOI', 'data' => '838,383'],
    "120" => ['label' => 'BA BE', 'data' => '819,198'],
    "121" => ['label' => 'BAC HA', 'data' => '632,175'],
    "122" => ['label' => 'SA PA', 'data' => '576,198'],
    "123" => ['label' => 'LAO CAI', 'data' => '593,189'],
    "124" => ['label' => 'MUONG HUM', 'data' => '554,181'],
    "125" => ['label' => 'MU CANG CHAI', 'data' => '613,286'],
    "126" => ['label' => 'NGHIA LO', 'data' => '659,298'],
    "127" => ['label' => 'YEN BAI', 'data' => '702,286'],
    "128" => ['label' => 'THAC BA', 'data' => '726,286'],
    "129" => ['label' => 'HOA BINH', 'data' => '775,415'],
    "130" => ['label' => 'MAI CHAU', 'data' => '734,439'],
    "131" => ['label' => "BAIE D'ALONG", 'data' => '1017,384'],
    "132" => ['label' => 'RED RIVER DELTA', 'data' => '894,428'],
    "133" => ['label' => 'NINH BINH', 'data' => '837,477'],
    "134" => ['label' => 'VINH', 'data' => '814,714'],
    "135" => ['label' => 'PHONG NHA', 'data' => '875,872'],
    "136" => ['label' => 'DONG HOI', 'data' => '948,883'],
    "137" => ['label' => 'VINH MOC', 'data' => '1012,936'],
    "138" => ['label' => 'DONG HA', 'data' => '1025,974'],
    "139" => ['label' => 'KHE SANH', 'data' => '965,994'],
    "140" => ['label' => 'A LUOI', 'data' => '1032,1040'],
    "141" => ['label' => 'HUE', 'data' => '1094,1017'],
    "142" => ['label' => 'DA NANG', 'data' => '1165,1070'],
    "143" => ['label' => 'CU LAO CHAM', 'data' => '1213,1084'],
    "144" => ['label' => 'HOI AN', 'data' => '1186,1104'],
    "145" => ['label' => 'MY SON', 'data' => '1169,1119'],
    "146" => ['label' => 'KHAM DUC', 'data' => '1102,1154'],
    "147" => ['label' => 'MY LAI', 'data' => '1252,1190'],
    "148" => ['label' => 'KON TUM', 'data' => '1121,1303'],
    "149" => ['label' => 'CENTRAL HIGHLANDS', 'data' => '1204,1447'],
    "150" => ['label' => 'SIX SENSE', 'data' => '1316,1567'],
    "151" => ['label' => 'NHA TRANG', 'data' => '1298,1598'],
    "152" => ['label' => 'HON BA', 'data' => '1292,1627'],
    "153" => ['label' => 'DA LAT', 'data' => '1215,1627'],
    "154" => ['label' => 'NAM NUNG FOREST', 'data' => '1123,1597'],
    "155" => ['label' => 'BU PRANG', 'data' => '1066,1601'],
    "156" => ['label' => 'BU GIA MAP', 'data' => '1027,1606'],
    "157" => ['label' => 'GIA NGHIA', 'data' => '1083,1613'],
    "158" => ['label' => 'TAY NINH', 'data' => '876,1720'],
    "159" => ['label' => 'MUI NE', 'data' => '1177,1767'],
    "160" => ['label' => 'LONG XUYEN', 'data' => '780,1849'],
    "161" => ['label' => 'CAI BE', 'data' => '866,1851'],
    "162" => ['label' => 'VINH LONG', 'data' => '834,1871'],
    "163" => ['label' => 'SA DEC', 'data' => '852,1870'],
    "164" => ['label' => 'PHUOC LONG', 'data' => '922,1883'],
    "165" => ['label' => 'TRA ON', 'data' => '855,1900'],
    "166" => ['label' => 'CAN THO', 'data' => '826,1900'],
    "167" => ['label' => 'RACH GIA', 'data' => '742,1920'],
    "168" => ['label' => 'PHU QUOC', 'data' => '583,1874'],
    "169" => ['label' => 'CON DAO', 'data' => '940,2075'],
    "170" => ['label' => 'TRA VINH', 'data' => '864,1942'],
    "171" => ['label' => 'HO CHI MINH VILLE (SAIGON)', 'data' => '953,1788'],
    "172" => ['label' => 'BAN LO MA', 'data' => '375,337'],
    "173" => ['label' => 'PAK NAM NOI', 'data' => '357,350'],
    "174" => ['label' => 'MUANG KHUA', 'data' => '373,361'],
    "175" => ['label' => 'NONG KHIAO', 'data' => '384,440'],
    "176" => ['label' => 'HOUAY SAY', 'data' => '99,482'],
    "177" => ['label' => 'OUDOMXAY', 'data' => '276,499'],
    "178" => ['label' => 'PAK OU', 'data' => '357,518'],
    "179" => ['label' => 'VIENG THONG', 'data' => '532,483'],
    "180" => ['label' => 'PAKBENG', 'data' => '192,554'],
    "181" => ['label' => 'LUANG PRABANG', 'data' => '334,546'],
    "182" => ['label' => 'KOUANG SI', 'data' => '358,556'],
    "183" => ['label' => 'PHONGSAVAN', 'data' => '545,572'],
    "184" => ['label' => 'XIENG KHOUANG', 'data' => '568,581'],
    "185" => ['label' => 'VANG VIENG', 'data' => '334,661'],
    "186" => ['label' => 'VIENTIANE', 'data' => '392,810'],
    "187" => ['label' => 'XEKONG', 'data' => '1000,1062'],
    "188" => ['label' => 'PAKSÉ', 'data' => '857,1225'],
    "189" => ['label' => 'CHAMPASSAK', 'data' => '851,1237'],
    "190" => ['label' => 'WAT PHOU', 'data' => '844,1245'],
    "191" => ['label' => 'ILE DON DAENG', 'data' => '814,1301'],
    "192" => ['label' => '4000 ILES', 'data' => '810,1318'],
    "193" => ['label' => 'ILE DON KHONE', 'data' => '814,1331'],
    "194" => ['label' => 'KHONG ISLAND', 'data' => '839,1340'],
    "195" => ['label' => 'PREAH VIHEAR', 'data' => '678,1307'],
    "196" => ['label' => 'ANLONG VENG', 'data' => '595,1327'],
    "197" => ['label' => 'POIPET', 'data' => '396,1401'],
    "198" => ['label' => 'BANTEAY SREI', 'data' => '599,1410'],
    "199" => ['label' => 'KOH KER', 'data' => '715,1395'],
    "200" => ['label' => 'PREAH RUMKEL', 'data' => '843,1371'],
    "201" => ['label' => 'RATANAKIRI', 'data' => '985,1418'],
    "202" => ['label' => 'PREK TOAL', 'data' => '495,1444'],
    "203" => ['label' => 'ANGKOR', 'data' => '581,1428'],
    "204" => ['label' => 'STUNG TRENG', 'data' => '853,1423'],
    "205" => ['label' => 'BATTAMBANG', 'data' => '460,1475'],
    "206" => ['label' => 'DAM DACK', 'data' => '613,1472'],
    "207" => ['label' => 'KRATIE', 'data' => '864,1472'],
    "208" => ['label' => 'PAILIN', 'data' => '413,1530'],
    "209" => ['label' => 'PURSAT', 'data' => '567,1514'],
    "210" => ['label' => 'KOMPONG THOM', 'data' => '672,1505'],
    "211" => ['label' => 'PHNOMPRICH', 'data' => '987,1512'],
    "212" => ['label' => 'BOPING', 'data' => '648,1529'],
    "213" => ['label' => 'MONDULKIRI', 'data' => '1007,1523'],
    "214" => ['label' => 'CHNOK TRU', 'data' => '629,1549'],
    "215" => ['label' => 'KAMPONG CHNANG', 'data' => '664,1566'],
    "216" => ['label' => 'OU DONG', 'data' => '696,1632'],
    "217" => ['label' => 'KOMPONG CHAM', 'data' => '784,1636'],
    "218" => ['label' => 'SENMONOROM', 'data' => '1007,1582'],
    "219" => ['label' => 'KOH KONG', 'data' => '448,1684'],
    "220" => ['label' => 'TA TAI', 'data' => '461,1681'],
    "221" => ['label' => 'PHNOM PENH', 'data' => '711,1676'],
    "222" => ['label' => 'TAKEO', 'data' => '694,1735'],
    "223" => ['label' => 'ILES ET CÔTES KHMERS', 'data' => '487,1801'],
    "224" => ['label' => 'KAMPOT', 'data' => '582,1817'],
    "225" => ['label' => 'SIHANOUKVILLE', 'data' => '526,1817'],
    "226" => ['label' => 'KEP', 'data' => '630,1832'],
    "228" => ['label' => 'CHAU DOC', 'data' => '739,1804'],
    "230" => ['label' => 'BEN TRE', 'data' => '921,1863'],
    "232" => ['label' => 'LUANG NAMTHA', 'data' => '232,397'],
    "234" => ['label' => 'HA TIEN', 'data' => '706,1872'],
    "236" => ['label' => 'HAI PHONG', 'data' => '959,397'],
    "238" => ['label' => 'SON LA', 'data' => '590,353'],
    "240" => ['label' => 'DIEN BIEN', 'data' => '457,334'],
    "242" => ['label' => 'MUANG NGOI', 'data' => '409,420'],
    "244" => ['label' => 'SEKONG', 'data' => '975,1131'],
    "246" => ['label' => 'QUY NHON', 'data' => '1038,1378'],
    "248" => ['label' => 'NINH THUAN', 'data' => '1270,1673'],
    "250" => ['label' => 'SAVANNAKHET', 'data' => '699,1007'],
    "251" => ['label' => 'KHAMMOUANE', 'data' => '804,847'],
    "254" => ['label' => 'DONG NAI', 'data' => '1052,1702'],
    "255" => ['label' => 'BUON MA THUOT', 'data' => '1128,1527'],
    "256" => ['label' => 'BOLOVENS', 'data' => '885,1167'],
    

    //bổ sung 13/6
    "257" => ['label' => 'HOANG SU PHI', 'data' => '590,150'],
    "258" => ["label" =>"NAM NGUA", "data" => "846,134"],
    "259" => ["label" =>"MOC CHAU", "data" => "679,419"],
    "260" => ["label" =>"SUOI MU", "data" => "745,378"],
    "261" => ["label" =>"PU LUONG", "data" => "739,381"],
    "262" => ["label" =>"PHAN RANG", "data" => "1271,1671"],
    "264" => ["label" =>"VINH HY", "data" => "1295,1658"],
    "265" => ["label" =>"VUNG TAU", "data" => "1021,1840"],
    "266" => ["label" =>"THU DAU MOT", "data" => "962,1765"],
    "267" => ["label" =>"CAM RANH", "data" => "1294,1631"],
    "268" => ["label" =>"SA PA", "data" => "572,182"],
    "269" => ["label" =>"THONG NONG", "data" => "845,143"],
    "270" => ["label" =>"SUOI THAU", "data" => "652,156"],
    "271" => ["label" =>"TA LAI", "data" => "1058,1718"],
    "272" => ["label" =>"SOC TRANG", "data" => "876,1951"],
    "273" => ["label" =>"CAT BA", "data" => "1020,425"],
    //lao
    "274" => ["label" =>"MUANG LA LODGE", "data" => "319,443"],
    "275" => ["label" =>"PHONG SALY", "data" => "344,336"],
    "276" => ["label" =>"BOUN TAI", "data" => "313,341"],
    "277" => ["label" =>"PAK NAM NOY", "data" => "346,406"],
    //cam
    "278" => ["label" =>"KOH RONG SALOEM", "data" => "615,1909"],

];
$jsonPointsMulti = json_encode($pointsMulti);
$pointsMyanmar = [
    "1" => ["label" =>"CHIN", "data" => "238,975"],
    "2" => ["label" =>"MANDALAY", "data" => "631,1102"],
    "3" => ["label" =>"BAGAN", "data" => "456,1200"],
    "4" => ["label" =>"KENGTUNG", "data" => "1243,1192"],
    "5" => ["label" =>"GROTTE PINDAYA", "data" => "712,1229"],
    "6" => ["label" =>"KALAW", "data" => "709,1282"],
    "7" => ["label" =>"HEHO", "data" => "746,1275"],
    "8" => ["label" =>"TAUNGGY", "data" => "770,1293"],
    "9" => ["label" =>"LAC INLE", "data" => "775,1317"],
    "10" => ["label" =>"INDEIN", "data" => "749,1325"],
    "11" => ["label" =>"PINLAUNG", "data" => "744,1389"],
    "12" => ["label" =>"NAYPYIDAW", "data" => "653,1445"],
    "13" => ["label" =>"LOIKAW", "data" => "797,1466"],
    "14" => ["label" =>"NGAPALI", "data" => "379,1639"],
    "15" => ["label" =>"RANGOON", "data" => "567,1894"],
    "16" => ["label" =>"HPA-AN", "data" => "898,1887"],
    "17" => ["label" =>"MAWLAMYINE", "data" => "903,1952"], 
    "18" => ["label" =>"KYAIKHTEEYOE", "data" => "806,1789"],
];
$jsonPointsMyanmar = json_encode($pointsMyanmar);
$pointsMultiNew = [
    "1" => ["label" =>"CHIN", "data" => "112,458"],
    "2" => ["label" =>"BAGAN", "data" => "215,564"],
    "3" => ["label" =>"MANDALAY", "data" => "297,517"],
    "4" => ["label" =>"GROTTE PINDAYA", "data" => "335,577"],
    "5" => ["label" =>"KENGTUNG", "data" => "583,559"],
    "6" => ["label" =>"KALAW", "data" => "332,603"],
    "7" => ["label" =>"HEHO", "data" => "350,600"],
    "8" => ["label" =>"TAUNGGY", "data" => "361,608"],
    "9" => ["label" =>"LAC INLE", "data" => "363,619"],
    "10" => ["label" =>"INDEIN", "data" => "351,623"],
    "11" => ["label" =>"PINLAUNG", "data" => "349,653"],
    "12" => ["label" =>"NAYPYIDAW", "data" => "306,678"],
    "13" => ["label" =>"LOIKAW", "data" => "374,689"],
    "14" => ["label" =>"NGAPALI", "data" => "177,770"],
    "15" => ["label" =>"RANGOON", "data" => "266,890"],
    "16" => ["label" =>"HPA-AN", "data" => "422,887"],
    "17" => ["label" =>"BAN LO MA (YAPA)", "data" => "778,551"],
    "18" => ["label" =>"PAK NAM NOI", "data" => "768,557"],
    "19" => ["label" =>"MUANG KHUA", "data" => "792,555"],
    "20" => ["label" =>"MUANG NGOI", "data" => "788,589"],
    "21" => ["label" =>"NONG KHIAO", "data" => "782,607"],
    "22" => ["label" =>"HOUAY SAY", "data" => "628,630"],
    "23" => ["label" =>"OUDOMXAY", "data" => "724,639"],
    "24" => ["label" =>"MUANG LA", "data" => "750,628"],
    "25" => ["label" =>"VIENG THONG (NAM ET)", "data" => "863,629"],
    "26" => ["label" =>"PAKBENG", "data" => "679,669"],
    "27" => ["label" =>"LUANG PRABANG", "data" => "756,663"],
    "28" => ["label" =>"PAK OU", "data" => "768,649"],
    "29" => ["label" =>"KOUANG SI", "data" => "768,670"],
    "30" => ["label" =>"PHONSAVAN", "data" => "870,678"],
    "31" => ["label" =>"XIENG KHOUANG", "data" => "882,683"],
    "32" => ["label" =>"SAYABOURY", "data" => "715,700"],
    "33" => ["label" =>"KASI", "data" => "746,715"],
    "34" => ["label" =>"VANG VIENG", "data" => "756,727"],
    "35" => ["label" =>"VIENTIANE", "data" => "787,807"],
    "36" => ["label" =>"XEKONG", "data" => "1116,944"],
    "37" => ["label" =>"TA OY", "data" => "1108,960"],
    "38" => ["label" =>"THONGLEK", "data" => "1122,984"],
    "39" => ["label" =>"SALAVAN", "data" => "1107,993"],
    "40" => ["label" =>"BOLOVEN", "data" => "1078,1028"],
    "41" => ["label" =>"ATTAPU", "data" => "1118,1032"],
    "42" => ["label" =>"PAKSE", "data" => "1038,1032"],
    "43" => ["label" =>"CHAMPASSAK", "data" => "1035,1038"],
    "44" => ["label" =>"WAT PHOU", "data" => "1032,1043"],
    "45" => ["label" =>"ILE DON DAENG", "data" => "1015,1072"],
    "46" => ["label" =>"4000 ILES", "data" => "1013,1082"],
    "47" => ["label" =>"ILE DON KHONE", "data" => "1014,1089"],
    "48" => ["label" =>"KHONG ISLAND", "data" => "1028,1093"],
    "49" => ["label" =>"PREAH VIHEAR", "data" => "942,1076"],
    "50" => ["label" =>"ANLONG VENG", "data" => "897,1087"],
    "51" => ["label" =>"POIPET", "data" => "789,1127"],
    "52" => ["label" =>"BANTEAY SREI", "data" => "899,1132"],
    "53" => ["label" =>"ANGKOR", "data" => "889,1142"],
    "54" => ["label" =>"PREK TOAL", "data" => "843,1150"],
    "55" => ["label" =>"BATTAMBANG", "data" => "824,1167"],
    "56" => ["label" =>"PAILIN", "data" => "798,1197"],
    "57" => ["label" =>"PURSAT", "data" => "882,1188"],
    "58" => ["label" =>"DAM DACK", "data" => "906,1165"],
    "59" => ["label" =>"KOH KER", "data" => "962,1124"],
    "60" => ["label" =>"PREAH RUMKEL", "data" => "1031,1111"],
    "61" => ["label" =>"STUNG TRENG", "data" => "1036,1139"],
    "62" => ["label" =>"RATANAKIRI", "data" => "1109,1136"],
    "63" => ["label" =>"KRATIE", "data" => "1043,1166"],
    "64" => ["label" =>"PHNOM PRICH", "data" => "1109,1187"],
    "65" => ["label" =>"MONDULKIRI", "data" => "1120,1193"],
    "66" => ["label" =>"SENMONOROM", "data" => "1120,1225"],
    "67" => ["label" =>"KOMPONG THOM", "data" => "938,1183"],
    "68" => ["label" =>"BOPING", "data" => "925,1197"],
    "69" => ["label" =>"PHAT SANDAY", "data" => "928,1205"],
    "70" => ["label" =>"CHNOK TRU", "data" => "915,1207"],
    "71" => ["label" =>"KAMPONG CHNANG", "data" => "934,1216"],
    "72" => ["label" =>"VEAL VENG", "data" => "830,1228"],
    "73" => ["label" =>"PHNOM SAMBOK", "data" => "823,1235"],
    "74" => ["label" =>"OU DONG", "data" => "952,1252"],
    "75" => ["label" =>"KOMPONG CHAM", "data" => "999,1255"],
    "76" => ["label" =>"PHNOM PENH", "data" => "959,1276"],
    "77" => ["label" =>"TAKEO", "data" => "951,1308"],
    "78" => ["label" =>"TA TAI", "data" => "825,1280"],
    "79" => ["label" =>"KOH KONG CITY", "data" => "818,1281"],
    "80" => ["label" =>"KOH KONG ISLAND", "data" => "816,1300"],
    "81" => ["label" =>"ILES ET CÔTES KHMERS", "data" => "837,1344"],
    "82" => ["label" =>"SIHANNOUKVILLE", "data" => "859,1353"],
    "83" => ["label" =>"KAMPOT", "data" => "889,1352"],
    "84" => ["label" =>"KEP", "data" => "915,1361"],
    "85" => ["label" =>"DONG VAN", "data" => "993,411"],
    "86" => ["label" =>"MEO VAC", "data" => "1000,428"],
    "87" => ["label" =>"BAO LAC", "data" => "1021,441"],
    "88" => ["label" =>"KHUOI KHON", "data" => "1026,449"],
    "89" => ["label" =>"NAM NGUA", "data" => "1059,450"],
    "90" => ["label" =>"CAO BANG", "data" => "1060,458"],
    "91" => ["label" =>"BA BE", "data" => "1018,476"],
    "92" => ["label" =>"HA GIANG", "data" => "965,455"],
    "93" => ["label" =>"BAC HA", "data" => "918,464"],
    "94" => ["label" =>"LAO CAI", "data" => "897,473"],
    "95" => ["label" =>"SAPA", "data" => "888,477"],
    "96" => ["label" =>"MUONG HUM", "data" => "874,468"],
    "97" => ["label" =>"LAI CHAU", "data" => "856,479"],
    "98" => ["label" =>"MUONG LAY", "data" => "832,527"],
    "99" => ["label" =>"DIEN BIEN PHU", "data" => "823,554"],
    "100" => ["label" =>"MU CANG CHAI", "data" => "907,523"],
    "101" => ["label" =>"NGHIA LO", "data" => "932,531"],
    "102" => ["label" =>"YEN BAI", "data" => "955,525"],
    "103" => ["label" =>"THAC BA", "data" => "968,524"],
    "104" => ["label" =>"LANG SON", "data" => "1097,515"],
    "105" => ["label" =>"HA NOI", "data" => "1030,578"],
    "106" => ["label" =>"HOA BINH", "data" => "995,595"],
    "107" => ["label" =>"MAI CHAU", "data" => "973,607"],
    "108" => ["label" =>"NINH BINH", "data" => "1029,628"],
    "109" => ["label" =>"RED RIVER DELTA", "data" => "1059,601"],
    "110" => ["label" =>"BAIE D'ALONG", "data" => "1126,577"],
    "111" => ["label" =>"VIET HAI", "data" => "1128,608"],
    "112" => ["label" =>"VINH", "data" => "1016,756"],
    "113" => ["label" =>"PHONG NHA", "data" => "1049,841"],
    "114" => ["label" =>"DONG HOI", "data" => "1088,848"],
    "115" => ["label" =>"VINH MOC", "data" => "1123,876"],
    "116" => ["label" =>"DONG HA", "data" => "1130,897"],
    "117" => ["label" =>"KHE SANH", "data" => "1098,908"],
    "118" => ["label" =>"A LUOI (A ROANG)", "data" => "1133,932"],
    "119" => ["label" =>"HUE", "data" => "1167,920"],
    "120" => ["label" =>"BHO HONG", "data" => "1182,957"],
    "121" => ["label" =>"DA NANG", "data" => "1205,949"],
    "122" => ["label" =>"CU LAO CHAM", "data" => "1232,958"],
    "123" => ["label" =>"HOI AN", "data" => "1217,967"],
    "124" => ["label" =>"MY SON", "data" => "1208,975"],
    "125" => ["label" =>"KHAM DUC", "data" => "1171,994"],
    "126" => ["label" =>"MY LAI", "data" => "1254,1014"],
    "127" => ["label" =>"KON TUM", "data" => "1182,1075"],
    "128" => ["label" =>"PLEIKU", "data" => "1191,1089"],
    "129" => ["label" =>"QUY NHON", "data" => "1276,1084"],
    "130" => ["label" =>"CENTRAL HIGHLANDS", "data" => "1226,1153"],
    "131" => ["label" =>"BUON ME THUOT", "data" => "1177,1206"],
    "132" => ["label" =>"NAM NUNG", "data" => "1183,1234"],
    "133" => ["label" =>"BU PRANG", "data" => "1152,1236"],
    "134" => ["label" =>"GIA NGHIA", "data" => "1161,1243"],
    "135" => ["label" =>"BU GIA MAP", "data" => "1131,1239"],
    "136" => ["label" =>"SIX SENSES", "data" => "1287,1217"],
    "137" => ["label" =>"FOREST", "data" => "1278,1235"],
    "138" => ["label" =>"HON BA", "data" => "1274,1250"],
    "139" => ["label" =>"CAM RANH", "data" => "1281,1255"],
    "140" => ["label" =>"DA LAT", "data" => "1233,1251"],
    "141" => ["label" =>"AMANOI (VINH HY)", "data" => "1277,1275"],
    "142" => ["label" =>"DJIRING", "data" => "1182,1279"],
    "143" => ["label" =>"CHO LAU", "data" => "1219,1315"],
    "144" => ["label" =>"MUI NE", "data" => "1212,1326"],
    "145" => ["label" =>"TA LAI", "data" => "1144,1295"],
    "146" => ["label" =>"TAY NINH", "data" => "1049,1300"],
    "147" => ["label" =>"HO CHI MINH CITY", "data" => "1091,1337"],
    "148" => ["label" =>"MY THO", "data" => "1060,1372"],
    "149" => ["label" =>"CAI BE", "data" => "1044,1373"],
    "150" => ["label" =>"SA DEC", "data" => "1037,1381"],
    "151" => ["label" =>"VINH LONG", "data" => "1027,1382"],
    "152" => ["label" =>"PHUOC LONG (COCO LAND)", "data" => "1074,1389"],
    "153" => ["label" =>"TRA ON", "data" => "1038,1398"],
    "154" => ["label" =>"CAN THO", "data" => "1023,1399"],
    "155" => ["label" =>"LONG XUYEN", "data" => "997,1370"],
    "156" => ["label" =>"HA TIEN", "data" => "933,1371"],
    "157" => ["label" =>"ILE DE PHU QUOC", "data" => "891,1384"],
    "158" => ["label" =>"RACH GIA", "data" => "976,1408"],
    "159" => ["label" =>"TRA VINH", "data" => "1044,1420"],
    "160" => ["label" =>"ILE DE CON DAO", "data" => "1084,1493"],
    "161" => ["label" =>"CHAU DOC", "data" => "975,1347"],
    "162" => ["label" =>"BEN TRE", "data" => "1073,1378"], 
    "163" => ["label" =>"MAWLAMYINE", "data" => "423,918"], 
    "164" => ["label" =>"KYAIKHTEEYOE", "data" => "372,840"],

    "165" => ["label" =>"LAI CHAU", "data" => "847,475"],
    "166" => ["label" =>"HOAN SU PHI", "data" => "953,448"],
    "167" => ["label" =>"MEO VAC", "data" => "998,421"],
    "168" => ["label" =>"KY SON", "data" => "997,590"],
    "170" => ["label" =>"MY THO", "data" => "1069,1371"],
    "171" => ["label" =>"GO CONG", "data" => "1086,1372"],
    "172" => ["label" =>"CU CHI", "data" => "1081,1326"],
    "173" => ["label" => "KOH RONG SANLOEM", "data" => "840,1355"],
    "174" => ["label" =>"SAINYABULI", "data" => "701,691"],


    //bo sung 76 vn
    "175" => ['label' => 'HOANG SU PHI', 'data' => '940,448'],
    "176" => ["label" =>"MOC CHAU", "data" => "943,593"],
    "177" => ["label" =>"SUOI MU", "data" => "979,593"],
    "180" => ["label" =>"PU LUONG", "data" => "987,602"],
    "182" => ["label" =>"PHAN RANG", "data" => "1264,1276"],
    "183" => ["label" =>"VINH HY", "data" => "1271,1262"],
    "185" => ["label" =>"VUNG TAU", "data" => "1126,1366"],
    "186" => ["label" =>"THU DAU MOT", "data" => "1095,1327"],
    "188" => ["label" =>"CAM RANH", "data" => "1279,1255"],
    "198" => ["label" =>"NAM NGUA", "data" => "1037,453"],

    "191" => ["label" =>"SA PA", "data" => "890,474"],
    "192" => ["label" =>"THONG NONG", "data" => "1035,442"],
    "193" => ["label" =>"SUOI THAU", "data" => "962,454"],
    "194" => ["label" =>"TA LAI", "data" => "1147,1303"],
    "195" => ["label" =>"SOC TRANG", "data" => "1039,1428"],
    "197" => ["label" =>"CAT BA", "data" => "1129,606"],


    //LAO
    "184" => ["label" =>"MUANG LA LODGE", "data" => "752,582"],
    "189" => ["label" =>"PHONG SALY", "data" => "760,527"],
    "190" => ["label" =>"BOUN TAI", "data" => "744,559"],
    "196" => ["label" =>"PAK NAM NOY", "data" => "758,580"],
    //cam
    "199" => ["label" =>"KOH RONG SALOEM", "data" => "839,1355"],


    "200" => ["label" =>"NHA TRANG", "data" => "1280,1214"],

];

$jsonPointsMultiNew = json_encode($pointsMultiNew);

Yii::$app->params['page_title'] = Yii::t('t', 'Map drawing tool');
Yii::$app->params['page_layout'] = '-n -t';
Yii::$app->params['body_class'] = 'sidebar-xs';

?>
<script>
var product_id = <?= $theProduct['id'] ?>;
var time = '<?= date('Ymd-Hi', strtotime('+7 hours')) ?>';
var text_confirm_insert_map = '<?= Yii::t('x', 'This will replace any existing map. Continue?') ?>';
</script>
<style>
#myCanvas{background-color: #fff;}
#locations_chosen{min-width:600px;}
.text-pink img {border:1px solid #e91e63!important;}
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Change location's name</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">New name:</label>
                        <input type="text" class="form-control" id="change-name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                <button id="remove-text" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                <button id="submit-change-text" data-layer="" type="button" class="btn btn-primary">OK</button>
            </div>
        </div>
    </div>
</div>

<div id="alert"></div>
<div class="row mb-10">
    <div class="col-sm-3"><?= Yii::t('x', 'Program') ?>:</div>
    <div class="col-sm-9"><?= Html::a($theProduct['title'], '/products/r/'.$theProduct['id']) ?></div>
</div>
<div class="row mb-10">
    <div class="col-sm-3"><?= Yii::t('x', 'Load map') ?>:</div>
    <div class="col-sm-9">
        <ul class="list-inline">
            <li>
                <a data-width="1508" data-height="2052" class="map vietnam" data-map="/assets/tools/map-drawer/img/maps/vietnam1.jpg" href="javascript:;">Vietnam
                <br><img src="/timthumb.php?h=100&src=/assets/tools/map-drawer/img/maps/vietnam1.jpg"></a>
            </li>
            <li>
                <a data-width="1580" data-height="1872" class="map laos" data-map="/assets/tools/map-drawer/img/maps/laos1.jpg" href="javascript:;">Laos
                <br><img src="/timthumb.php?h=100&src=/assets/tools/map-drawer/img/maps/laos1.jpg"></a>
            </li>
            <li>
                <a data-width="1900" data-height="1872" class="map cambodge" data-map="/assets/tools/map-drawer/img/maps/cambodge.jpg" href="javascript:;">Cambodia
                <br><img src="/timthumb.php?h=100&src=/assets/tools/map-drawer/img/maps/cambodge.jpg"></a>
            </li>
            <li>
                <a data-width="1500" data-height="3049" class="map myanmar" data-map="/assets/tools/map-drawer/img/maps/myanmar.jpg" href="javascript:;">Myanmar
                <br><img src="/timthumb.php?h=100&src=/assets/tools/map-drawer/img/maps/myanmar.jpg"></a>
            </li>
            <li>
                <a data-width="1900" data-height="2200" class="map multipays" data-map="/assets/tools/map-drawer/img/maps/multipays.jpg" href="javascript:;">Indochina
                <br><img src="/timthumb.php?h=100&src=/assets/tools/map-drawer/img/maps/multipays.jpg"></a>
            </li>
            <li>
                <a data-width="1500" data-height="1551" class="map multipays-new" data-map="/assets/tools/map-drawer/img/maps/multipays-new.jpg" href="javascript:;">VLCM
                <br><img src="/timthumb.php?h=100&src=/assets/tools/map-drawer/img/maps/multipays-new.jpg"></a>
            </li>
        </ul>
    </div>
</div>
<div class="row mb-10">
    <div class="col-sm-3">
        <?= Yii::t('x', 'Select points & paths') ?>:
    </div>
    <div class="col-sm-9">
        <select data-placeholder="<?= Yii::t('x', 'Select points & paths') ?>" multiple class="chosen-select" id="locations">
            <optgroup label="TRANSPORTS">
                <?php foreach ($transports as $ktp => $tp): ?> 
                    <option class='<?= $ktp ?>' data-type="transport" value="<?= $ktp ?>"><?= $tp['label'] ?></option>
                <?php endforeach; ?>
            </optgroup>
            <optgroup label="LOCATIONS" class="vietnam-location">
                <?php foreach ($points as $kpo => $po) : ?>
                    <option class="location vietnam-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                <?php endforeach; ?>
                <?php foreach ($pointsLaos as $kpo => $po) : ?>
                    <option disabled="true" class="location laos-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                <?php endforeach; ?>
                <?php foreach ($poitsCambodge as $kpo => $po) : ?>
                    <option disabled="true" class="location cambodge-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                <?php endforeach; ?>
                <?php foreach ($pointsMyanmar as $kpo => $po) : ?>
                    <option disabled="true" class="location myanmar-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                <?php endforeach; ?>  
                <?php foreach ($pointsMultiNew as $kpo => $po) : ?>
                    <option disabled="true" class="location multipays-new-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                <?php endforeach; ?>    
                <?php foreach ($pointsMulti as $kpo => $po) : ?>
                    <option disabled="true" class="location multipays-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                <?php endforeach; ?>
            </optgroup>
        </select>
    </div>
</div>
<div class="row mb-10">
    <div class="col-sm-3"><?= Yii::t('x', 'Other options') ?>:</div>
    <div class="col-sm-9">
        <label><?= Yii::t('x', 'Curve') ?>: <input type="number" style="width:50px" min="-60" max="60" step="10" id="change-line" name="change-line" value="30"></label>
        &nbsp; <label><input type="checkbox" id="change-dashed" name="change-dashed" value="yes"> <?= Yii::t('x', 'Solid line') ?></label>
        &nbsp; <label><input type="checkbox" id="en-map" name="en-map" value="yes" <?= $theProduct['language'] == 'en' ? 'checked="checked"' : '' ?>> <?= Yii::t('x', 'English label') ?></label>
    </div>
</div>
<div class="row mb-10">
    <div class="col-sm-3 text-pink">
        &nbsp;
    </div>
    <div class="col-sm-9">
        <button class="btn btn-primary" type="button" id="submit"><?= Yii::t('x', 'Draw on map') ?></button>
        <button class="btn btn-danger" type="button" id="clear-all"><?= Yii::t('x', 'Clear path') ?></button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <? if (in_array(USER_ID, [1, $theProduct['created_by'], $theProduct['updated_by']])) { ?>
        <button class="btn btn-success" type="button" id="insert"><?= Yii::t('x', 'Insert to program') ?></button>
        <? } ?>
        <button class="btn btn-info" type="button" id="save"><?= Yii::t('x', 'Download as jpeg') ?></button>
    </div>
</div>

<canvas id="myCanvas" width="1900"></canvas>

<?

$this->registerCssFile('/assets/tools/map-drawer/css/chosen.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('/assets/tools/map-drawer/css/fontface.css', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJsFile('/assets/tools/map-drawer/js/chosen.jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/tools/map-drawer/js/chosen.order.jquery.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/tools/map-drawer/js/jcanvas.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/tools/map-drawer/js/FileSaver.js', ['depends'=>'yii\web\JqueryAsset']);

$js = <<<'TXT'
points = [];

// $('canvas').mouseover(function() {
//     return false;
// })

var scale = 1;
var fontSize = 20;
var pointR = 10;
var ex, ey;

$('#insert').on('click', function() {
    if (!confirm(text_confirm_insert_map)) {
        return false;
    }
    var canvas = document.getElementById('myCanvas')
    var dataURL = canvas.toDataURL('image/jpeg');
    var btn = $(this)
    btn.addClass('disabled').html('Saving map...')
    var jqxhr = $.ajax({
        url: '?xh&action=insert&id=' + product_id,
        type: 'post',
        data: {
            data: dataURL,
            file_name: 'carte_devis_' + product_id + '_' + time + '.jpg',
        },
        dataType: 'json'
    }).
    done(function(data) {
        $('#alert').html('<div class="alert alert-success">Map has been saved to your program!</div>')
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
        btn.removeClass('disabled').html('Insert to program')
    })
});

$('#save').on('click', function() {
    var canvas = document.getElementById("myCanvas"), ctx = canvas.getContext("2d");
    canvas.toBlob(function(blob) {
        saveAs(blob, 'carte_devis_' + product_id + '_' + time + '.jpg');
    }, 'image/jpeg');
});

$('a.map').click(function() {
    var width = parseInt($(this).data("width"));
    var height = parseInt($(this).data("height"));
    var canvas = document.getElementById('myCanvas');

    canvas.width = width;
    canvas.height = height;
    $('a.map').removeClass('text-bold text-pink');
    $(this).addClass('text-bold text-pink');
    $('canvas').removeLayers();
    $('#myCanvas').drawImage({
        source: $(this).data('map'),
        layer: true,
        // name: 'country',
        x: 0, y: 0,
        width: width,
        height: height,
        fromCenter: false,
        scale: 1,
    });
    if ($(this).hasClass("vietnam")) {
        scale = 1;
        ex = 1105;
        ey = 1037;
        points = {$jsonPoints};
        $('#locations .location').prop('disabled', true).trigger("chosen:updated");
        $('#locations .vietnam-location').prop('disabled', false).trigger("chosen:updated");
        $('#locations').val('').trigger('chosen:updated');
        drawMapVietnam();
    } else if ($(this).hasClass("laos")) {
        scale = 1.5;
        ex = 100;
        ey = 1256;
        points = {$jsonPointsLaos};
        $('#locations .location').prop('disabled', true).trigger("chosen:updated");
        $('#locations .laos-location').prop('disabled', false).trigger("chosen:updated");
        $('#locations').val('').trigger('chosen:updated');
        drawMapLaos();
    } else if ($(this).hasClass('cambodge')) {
        scale = 1.8;
        ex = 1270;
        ey = 1278;
        points = {$jsonPointsCambodge};
        $('#locations .location').prop('disabled', true).trigger("chosen:updated");
        $('#locations .cambodge-location').prop('disabled', false).trigger("chosen:updated");
        $('#locations').val('').trigger('chosen:updated');
        drawMapCambodge();
    } else if ($(this).hasClass('myanmar')) {
        scale = 1.8;
        ex = 1270;
        ey = 1278;
        points = {$jsonPointsMyanmar};
        $('#locations .location').prop('disabled', true).trigger("chosen:updated");
        $('#locations .myanmar-location').prop('disabled', false).trigger("chosen:updated");
        $('#locations').val('').trigger('chosen:updated');
        drawMapMyanmar();
    }else if ($(this).hasClass('multipays-new')) {
        scale = 0.8;
        ex = 1205;
        ey = 401;
       // fontSize = 15;
        pointR = 6;
        points = {$jsonPointsMultiNew};
        $('#locations .location').prop('disabled', true).trigger("chosen:updated");
        $('#locations .multipays-new-location').prop('disabled', false).trigger("chosen:updated");
        $('#locations').val('').trigger('chosen:updated');
        drawMapMultiNew();  
    } else if ($(this).hasClass('multipays')) {
        scale = 1.2;
        ex = 1534;
        ey = 1326;
        points = {$jsonPointsMulti};
        $('#locations .location').prop('disabled', true).trigger("chosen:updated");
        $('#locations .multipays-location').prop('disabled', false).trigger("chosen:updated");
        $('#locations').val('').trigger('chosen:updated');
        drawMapMulti();
    }

})

function drawMapVietnam() {
    $('canvas').drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text6',
        draggable: true,
        strokeWidth: 2,
        x: 525, y: 315,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        text: 'HA NOI',
        scale: scale,
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        name: 'point6',
        draggable: true,
        x: 514, y: 321,
        width: 26,
        height: 25,
        scale: 1,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-vietnam.png',
        name: 'name-vietnam',
        draggable: true,
        x: 382, y: 524,
        width: 100,
        height: 27,
        fromCenter: false
    });
}
;
function drawMapLaos() {
    $('canvas').drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text73',
        draggable: true,
        strokeWidth: 2,
        x: 549, y: 942,
        fontSize: fontSize,
        scale: scale,
        bringToFront: true,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        text: 'VIENTIANE',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        draggable: true,
        name: 'point73',
        x: 539, y: 892,
        width: 32,
        height: 32,
        fromCenter: false
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-laos.png',
        name: 'name-laos',
        draggable: true,
        x: 586, y: 700,
        width: 100,
        height: 41,
        fromCenter: false
    });
}

function drawMapCambodge() {
    $('canvas').drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text108',
        draggable: true,
        strokeWidth: 2,
        x: 1009, y: 958,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'PHNOM PENH',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        draggable: true,
        name: 'point108',
        x: 1009, y: 978,
        width: 22,
        bringToFront: true,
        height: 21,
        scale: 2,
        fromCenter: false
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-cambodge.png',
        name: 'name-laos',
        draggable: true,
        x: 441, y: 816,
        width: 332,
        height: 69,
        fromCenter: false,
        bringToFront: true
    });
}
function drawMapMyanmar() {
    $('canvas').drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text12',
        draggable: true,
        strokeWidth: 2,
        x: 530, y: 1445,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'NAYPYIDAW',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        draggable: true,
        name: 'point12',
        x: 644, y: 1432,
        width: 22,
        bringToFront: true,
        height: 21,
        scale: 2,
        fromCenter: false
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-birmanie.png',
        name: 'name-birmanie',
        draggable: true,
        x: 639, y: 987,
        width: 200,
        height: 45,
        fromCenter: false,
        bringToFront: true
    });
}
function drawMapMulti() {
    $('canvas').drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text221',
        draggable: true,
        strokeWidth: 2,
        x: 718, y: 1706,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'PHNOM PENH',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text119',
        draggable: true,
        strokeWidth: 2,
        x: 838, y: 357,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'HA NOI',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text186',
        draggable: true,
        strokeWidth: 2,
        x: 395, y: 841,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'VIENTIANE',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        draggable: true,
        name: 'point186',
        x: 382, y: 800,
        width: 22,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        draggable: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        name: 'point119',
        x: 828, y: 373,
        width: 22,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        draggable: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        name: 'point221',
        x: 711, y: 1666,
        width: 22,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-cambodge.png',
        name: 'name-cambodge',
        draggable: true,
        x: 441, y: 1602,
        width: 135,
        height: 30,
        fromCenter: false,
        bringToFront: true
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-laos.png',
        name: 'name-laos',
        draggable: true,
        x: 423, y: 672,
        width: 68,
        height: 30,
        fromCenter: false,
        bringToFront: true
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-vietnam.png',
        name: 'name-vietnam',
        draggable: true,
        x: 680, y: 607,
        width: 107,
        height: 31,
        fromCenter: false,
        bringToFront: true
    });
}
function drawMapMultiNew() {
    $('canvas').drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text76',
        draggable: true,
        strokeWidth: 2,
        x: 944, y: 1292,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'PHNOM PENH',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text105',
        draggable: true,
        strokeWidth: 2,
        x: 1028, y: 562,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'HA NOI',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text35',
        draggable: true,
        strokeWidth: 2,
        x: 787, y: 794,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'VIENTIANE',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }
    }).drawText({
        fillStyle: '#000',
        layer: true,
        name: 'text12',
        draggable: true,
        strokeWidth: 2,
        x: 254, y: 683,
        fontSize: fontSize,
        fontFamily: 'DINAlternate-Medium, sans-serif',
        fontStyle: 'normal',
        scale: scale,
        text: 'NAYPYIDAW',
        mouseover: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 0.5
            });
        },
        mouseout: function(layer) {
            $('canvas').setLayer(layer.name, {
                opacity: 1
            });
        }    
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        draggable: true,
        name: 'point76',
        x: 951, y: 1267,
        width: 20,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        draggable: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        name: 'point105',
        x: 1019, y: 568,
        width: 20,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        draggable: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        name: 'point35',
        x: 778, y: 800,
        width: 20,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false
    }).drawImage({
        layer: true,
        draggable: true,
        source: '/assets/tools/map-drawer/img/maps/capitan.png',
        name: 'point12',
        x: 298, y: 670,
        width: 20,
        height: 21,
        scale: scale,
        bringToFront: true,
        fromCenter: false    
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-cambodge.png',
        name: 'name-cambodge',
        draggable: true,
        x: 845, y: 1300,
        width: 75,
        height: 20,
        fromCenter: false,
        bringToFront: true
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-laos.png',
        name: 'name-laos',
        draggable: true,
        x: 803, y: 728,
        width: 48,
        height: 18,
        fromCenter: false,
        bringToFront: true
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-birmanie.png',
        name: 'name-birmanie',
        draggable: true,
        x: 300, y: 463,
        width: 73,
        height: 22,
        fromCenter: false,
        bringToFront: true    
    }).drawImage({
        layer: true,
        source: '/assets/tools/map-drawer/img/maps/name-vietnam.png',
        name: 'name-vietnam',
        draggable: true,
        x: 939, y: 693,
        width: 67,
        height: 20,
        fromCenter: false,
        bringToFront: true
    });
}

$('#clear-all').click(function() {
    $('#locations').val('').trigger('chosen:updated');
})

$('#locations').chosen({
    no_results_text: 'Oops, nothing found!',
    width: '90%',
    inherit_select_classes: true
});

function isNumber(obj) {
    return !isNaN(parseFloat(obj))
}

$("#submit").on('click', function() {
    var tour = $('#locations').getSelectionOrder();
    var transports = {$jsonTransports};
    var x1, x2, y1, y2, d, yl, yn, cx1, cy1, label1, label2;
    var angle = -$("#change-line").val() * 0.017453292519943295;
    var e = 0;
    var tp = [];
    for (var i = 0; i < tour.length; i++) {
        var canvas = document.getElementById('myCanvas');
        var ctx = canvas.getContext('2d');
        var cxtCustom = ctx;
        var strokeDash = [8 * scale, 8 * scale];
        var explain = null;
        if ($('#change-dashed').is(":checked")) {
            strokeDash = [];
        }
        if (!isNumber(tour[i]) && (tp.indexOf(tour[i]) < 0)) {
            tp.push(tour[i]);
            e++;
            switch (tour[i]) {
                case 'ap':
                    explain = 'airplane';
                    break;
                case 'tr':
                    explain = 'train';
                    break;
                case 'ca':
                    explain = 'car';
                    break;
                case 'mt':
                    explain = 'motobike';
                    break;
                case 'bc':
                    explain = 'bycicle';
                    break;
                case 'bo':
                    explain = 'boat';
                    break;
                case 'wa':
                    explain = 'walk';
                    break;
            }
            if ($('#en-map').is(":checked")) {
                explain += '_en';
            }
            explain += '.png';
            $('canvas').drawImage({
                layer: true,
                source: '/assets/tools/map-drawer/img/legends/' + explain,
                name: 'explain-' + tour[i],
                groups: ['explain'],
                draggable: true,
                dragGroups: ['explain'],
                x: ex, y: ey + e * 68,
                width: 250,
                height: 68,
                fromCenter: false
            });

        }
        if (isNumber(tour[i])) {
            x1 = parseInt(points[tour[i]]["data"].split(",")[0]);
            label1 = points[tour[i]]["label"];
            y1 = parseInt(points[tour[i]]["data"].split(",")[1]);
            if ($('canvas').getLayer('text' + tour[i]) === undefined) {
                $('canvas').drawText({
                    fillStyle: '#000',
                    label: true,
                    name: 'text' + tour[i],
                    draggable: true,
                    strokeWidth: 2,
                    x: x1, y: y1 - 20 * scale,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    text: label1,
                    scale: scale,
                    dblclick: function(layer) {
                        $('#change-name').val(layer.text);
                        $('#submit-change-text').data('layer', layer.name);
                        $('#myModal').modal('show');
                        // code to run when square is clicked
                    },
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                });
            }
            if ($('canvas').getLayer('point' + tour[i]) === undefined) {
                $('canvas').drawArc({
                    label: true,
                    arc: true,
                    name: 'point' + tour[i],
                    draggable: true,
                    fillStyle: '#e35429',
                    strokeStyle: '#e35429',
                    strokeWidth: 1,
                    x: x1, y: y1,
                    radius: pointR,
                    scale: scale,
                    bringToFront: true,
                    dblclick: function(layer) {
                        // code to run when square is clicked
                        $('canvas').removeLayer(layer.name);
                        return false;
                    },
                    dragstop: function(layer) {
                        // code to run as layer is being dragged
                        points[parseInt(layer.name.match(/\d+/)[0])].data = layer.x + ',' + layer.y;
                    },
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                });
            }
            ;
        }

//                    if (i % 2 == 0 && i != (tour.length - 1)) {
        if (isNumber(tour[i]) && !isNumber(tour[i + 1]) && isNumber(tour[i + 2]) && tour.length > 2 && i <= (tour.length - 2)) {
            if ((tour[0] == tour[tour.length - 1]) && i == (tour.length - 3)) {
                angle = 0;
            }
            x2 = parseInt(points[tour[i + 2]]["data"].split(",")[0]);
            y2 = parseInt(points[tour[i + 2]]["data"].split(",")[1]);
//                        if(newPoints['point'+tour[i+2]]){
//                            x2 = parseInt(newPoints['point'+tour[i+2]].split(",")[0]);
//                            y2 = parseInt(newPoints['point'+tour[i+2]].split(",")[1]);
//                        }
            label2 = points[tour[i + 2]]["label"];

            yl = y2 >= y1 ? y2 : y1;
            yn = y2 >= y1 ? y1 : y2;
            d = Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2)) / 2;
            cx1 = (x1 + x2 - Math.tan(angle) * (y2 - y1)) / 2;
            cy1 = (x2 * x2 + y2 * y2 - x1 * x1 - y1 * y1 + (x1 - x2) * (x2 + x1 - Math.tan(angle) * (y2 - y1))) / (2 * (y2 - y1));
             var posC = mathC(x2, y2, cx1, cy1, pointR);
            var posA = mathC(x1, y1, cx1, cy1, pointR);
            var argsCustom = {
                strokeStyle: transports[tour[i + 1]]['color'],
                strokeWidth: 1,
                layer: true,
                strokeDash: strokeDash,
                strokeJoin: 'round',
                rounded: true,
                endArrow: false,
                arrowRadius: 20,
                arrowAngle: 50,
                x1: posA.x, y1: posA.y,
                cx1: cx1, cy1: cy1,
                x2: posC.x, y2: posC.y,
                x: 0,
                y: 0,
                _toRad: 0.017453292519943295
            };
            var l = 2;
            var paramsCustom = new jCanvasObject(argsCustom);
            // Draw curve and arrow head
            if ($('canvas').getLayerGroup('line' + tour[i] + tour[i + 2]) === undefined) {

                _addArrowCustom(cxtCustom, paramsCustom, paramsCustom, paramsCustom[ 'cx' + (l - 1) ] + paramsCustom.x, paramsCustom[ 'cy' + (l - 1) ] + paramsCustom.y, paramsCustom[ 'x' + l ] + paramsCustom.x, paramsCustom[ 'y' + l ] + paramsCustom.y, 'line' + tour[i] + tour[i + 2]);
                $('canvas').drawQuadratic({
                    layer: true,
                    groups: ['line' + tour[i] + tour[i + 2]],
                    strokeStyle: transports[tour[i + 1]]['color'],
                    strokeWidth: 4 * scale,
                    strokeDash: strokeDash,
                    strokeDashOffset: 0,
                    strokeJoin: 'round',
                    rounded: true,
                    endArrow: false,
                    arrowRadius: 20,
                    arrowAngle: 60,
                    x1: posA.x, y1: posA.y,
                    cx1: cx1, cy1: cy1,
                    x2: posC.x, y2: posC.y,
                    dblclick: function(layer) {
                        // code to run when square is clicked
                        $('canvas').removeLayerGroup(layer.groups[0]);
                        return false;
                    },
                    mouseover: function(layer) {
                        $('canvas').setLayerGroup(layer.groups[0], {
                            opacity: 0.7
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayerGroup(layer.groups[0], {
                            opacity: 1
                        });
                    }
                });
            }
            continue;
        }
    }
});

function jCanvasObject(args) {
    var params = this,
            propName;
    // Copy the given parameters into new object
    for (propName in args) {
        // Do not merge defaults into parameters
        if (args.hasOwnProperty(propName)) {
            params[ propName ] = args[ propName ];
        }
    }
    return params;
}
// Adds arrow to path using the given properties
function _addArrowCustom(ctx, params, path, x1, y1, x2, y2, group) {
    ctx.webkitLineDash = ctx.mozDash = [];
    angle *= params._toRad;
    path.arrowAngle *= params._toRad;
    path.arrowRadius *= scale;
    var leftX, leftY,
            rightX, rightY,
            offsetX, offsetY,
            angle;
    // If arrow radius is given and path is not closed
    if (path.arrowRadius && !params.closed) {
        var PI = Math.PI,
                round = Math.round,
                abs = Math.abs,
                sin = Math.sin,
                cos = Math.cos,
                atan2 = Math.atan2,
                // Calculate angle
                angle = atan2((y2 - y1), (x2 - x1));
        // Adjust angle correctly
        angle -= PI;
        // Calculate offset to place arrow at edge of path
        offsetX = (params.strokeWidth * cos(angle));
        offsetY = (params.strokeWidth * sin(angle));

        // Calculate coordinates for left half of arrow
        leftX = x2 + (path.arrowRadius * cos(angle + (path.arrowAngle / 2)));

        leftY = y2 + (path.arrowRadius * sin(angle + (path.arrowAngle / 2)));
        // Calculate coordinates for right half of arrow
        rightX = x2 + (path.arrowRadius * cos(angle - (path.arrowAngle / 2)));
        rightY = y2 + (path.arrowRadius * sin(angle - (path.arrowAngle / 2)));

        $('canvas').drawPath({
            layer: true,
            groups: [group],
            draggable: true,
            strokeStyle: params.strokeStyle,
            strokeWidth: 1 * scale,
            bringToFront: true,
            fillStyle: params.strokeStyle,
            p1: {
                type: 'line',
                x1: leftX - offsetX, y1: leftY - offsetY,
                x2: x2 - offsetX, y2: y2 - offsetY,
                x3: rightX - offsetX, y3: rightY - offsetY,
                x4: leftX - offsetX, y4: leftY - offsetY
            },
        });

    }
}

$('#submit-change-text').click(function() {
    $('canvas').getLayer($(this).data('layer')).text = $('#change-name').val().toUpperCase();
    $('#myModal').modal('hide');
});
$('#remove-text').click(function() {
    $('canvas').removeLayer($('#submit-change-text').data('layer'));
    $('#myModal').modal('hide');
});
function mathC(xb, yb, xm, ym, R) {
    var result = [];
    var x, y;
    var a = 1 + ((yb - ym) * (yb - ym)) / ((xb - xm) * (xb - xm));
    var b = 2 * ((yb - ym)*(xb * ym - xm * yb) / ((xb - xm) * (xb - xm)) - xb - yb * (yb - ym) / (xb - xm));
    var c = (xb * ym - xm * yb) * (xb * ym - xm * yb) / ((xb - xm) * (xb - xm)) - 2*yb * (xb*ym - xm*yb) / (xb - xm) + xb * xb + yb * yb - R * R;
    var delta = b*b -4*a*c;
    var x1 = (-b + Math.sqrt(delta)) / (2 * a);
    var x2 = (-b - Math.sqrt(delta)) / (2 * a);
    var y1 = (x1 * (yb - ym) + xb * ym - yb * xm) / (xb - xm);
    var y2 = (x2 * (yb - ym) + xb * ym - yb * xm) / (xb - xm);
    var d1 = (x1 - xm) * (x1 - xm) + (y1 - ym) * (y1 - ym);
    var d2 = (x2 - xm) * (x2 - xm) + (y2 - ym) * (y2 - ym);
    if (d1 < d2) {
        x = x1;
        y = y1;
    } else {
        x = x2;
        y = y2;
    }
    result['x'] = x;
    result['y'] = y;
    return result;
};

$("a.map.vietnam").trigger("click");

TXT;
$js = str_replace([
    '{$jsonTransports}',
    '{$jsonPoints}',
    '{$jsonPointsLaos}',
    '{$jsonPointsCambodge}',
    '{$jsonPointsMulti}',
    '{$jsonPointsMyanmar}',
    '{$jsonPointsMultiNew}',
    ], [
    $jsonTransports,
    $jsonPoints,
    $jsonPointsLaos,
    $jsonPointsCambodge,
    $jsonPointsMulti,
    $jsonPointsMyanmar,
    $jsonPointsMultiNew,
    ], $js);
$this->registerJs($js);