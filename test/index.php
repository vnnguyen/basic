<?php
$soDinh = 5;//so dinh
$start = 1;// diem bat dau
$distanc = [];// luu do dai cac canh
$t = [];//luu nong do vet
$delta = [];//luu cap nhat mui
$w = [];// hanh trinh
$mark = [];// dinh da tham
$uv = []; // dinh chua tham
$n_loop = 0;
$traveler = 25;
$max = 1000000;
$cost_best = 10000000;
$best_router = [];
$q = 1;
$td = [];



function resetMark($soDinh){
    for($i = 1; $i <= $soDinh; $i ++){
        $mark[$i] = false;
    }
    return $mark;
}
function resetUv($soDinh){
    for($i = 1; $i <= $soDinh; $i ++){
        $uv[$i] = 0;
    }
}

for($i = 1; $i <= $soDinh; $i ++){
    for($j = 1; $j <= $soDinh; $j ++){
        $td[$i][$j] = -1;
        if ($i == $j) {
            $distanc[$i][$j] = 0;
        } else {
            $distanc[$i][$j] = $max;
        }
    }
}
$distanc[1][2] = $distanc[2][1] = 5;
$distanc[1][4] = $distanc[4][1] = 9;
$distanc[1][5] = $distanc[5][1] = 1;
$distanc[2][3] = $distanc[3][2] = 2;
$distanc[3][4] = $distanc[4][3] = 7;
$distanc[4][5] = $distanc[5][4] = 2;

for($i = 1; $i <= $soDinh-1; $i ++){
    for($j = $i + 1; $j <= $soDinh; $j ++){
        $t[$i][$j] = 0.5;// nong do
        $delta[$i][$j] = 0;//khoi tao vet
        $t[$j][$i] = $t[$i][$j]; $delta[$j][$i] = $delta[$i][$j];
    }
}

for($k = 1; $k <= $soDinh; $k ++){
    for($i = 1; $i <= $soDinh; $i ++){
        for($j = 1; $j <= $soDinh; $j ++){
            if ($i == $k || $k == $j || $i == $j) {
                continue;
            }
            if ($distanc[$i][$j] > $distanc[$i][$k] + $distanc[$k][$j]) {
                $distanc[$i][$j] = $distanc[$i][$k] + $distanc[$k][$j];
                $td[$i][$j] = $k;
            }
        }
    }
}

do {
    $n_loop ++;
    for ($i = 1; $i <= $traveler ; $i++) { 
        $w[1] = $start;
        $mark = resetMark($soDinh);
        $mark[1] = true;
        $cost = 0;
        for ($j = 2; $j <= $soDinh; $j++) {
           
            // chon diem tiep theo
            $w[$j] = point($j, $soDinh, $t, $distanc, $mark); //rand($j, $soDinh);//

            $cost = $cost + $distanc[ $w[$j-1] ][ $w[$j] ];

            $mark[$w[$j]] = True;
            echo $w[$j] .'-'. $n_loop. ' <br>';
        }
    }
    // $cost = $cost + $distanc[ $w[$soDinh] ][ $w[1] ];
    // if ($cost < $cost_best) {
    //     $cost_best = $cost;
    //     $best_router = $w;
    // }
    // for($i = 1; $i <= $soDinh-1; $i ++){
    //     for($j = $i + 1; $j <= $soDinh; $j ++){
    //         $delta[$i][$j] = $delta[$i][$j] + $q/$cost;
    //         $delta[$j][$i] = $delta[$i][$j];

    //         $t[$i][$j] = 0.8 * $delta[$i][$j] + $q/$cost;
    //         $t[$j][$i] = $t[$i][$j];
    //     }
    // }
} while ($n_loop < 500);
function point($k, $soDinh, $t, $distanc, $mark){
    $sum = $dem = 0; 
    resetUv($soDinh);
    for ($i=1; $i <= $soDinh ; $i++) { 
        if (!$mark[$i]) {
            $dem ++;
            $uv[$dem] = $i;
        }
    } 
    $p = p($k - 1, $uv, $t, $distanc);
    var_dump($p);die;
    
    $r = rand(1, 10) / 10;
    $t = 0;
    $i = 1;
    while ($t < 0.8) {
        $t += $p[$uv[$i]];
        $i ++;
    }
    return $uv[$i];
}
function p($start, $uv, $t, $distanc){
    $p = [0];
    
    $sum = [];
    foreach($uv as $v){
        $t_start_v = $t[$start][$v];
        $start_v = $distanc[$start][$v];
        $sum[] = ($t_start_v  * 1/ $distanc[$start][$v]);
    }
    return $sum;
}

?>