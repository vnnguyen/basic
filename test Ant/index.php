<?php
$soDinh = 7;//so dinh
$start = 1;// diem bat dau
$distanc = [];// luu do dai cac canh
$t = [];//luu nong do vet
$delta = [];//luu cap nhat mui
$w = [];// hanh trinh
$mark = [];// dinh da tham
$uv = []; // dinh chua tham
$max = 1000000;
$cost_best = 10000000;
$best_router = [];
$q = 1;
$td = [];
$stop_loop = 2000;
$n_loop = 0;
$traveler = 1 * 25;
$last = 6;
$commback = false;



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
$distanc[3][4] = $distanc[4][3] = 20;
$distanc[4][5] = $distanc[5][4] = 2;
$distanc[2][6] = $distanc[6][2] = 4;
$distanc[2][7] = $distanc[7][2] = 2;
$distanc[3][7] = $distanc[7][3] = 6;

$distanc[4][7] = $distanc[7][4] = 3;
$distanc[6][7] = $distanc[7][6] = 1;

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
function path($start, $end, $td){
    $p = [];
    $mid = $td[$start][$end];
    if($mid !== -1){
        $p[] = $mid;
        
        $left = [];
        $right = [];
        if ($td[$start][$mid] !== -1){
            $left = path($start, $mid, $td);
        }
        
        if ($td[$mid][$end] !== -1){
            $right = path($mid, $end, $td);
        }
        
        if (!empty($left)) {
            $p = array_push($left, $mid);
            $p = $left;
        }
        if (!empty($right)) {
            array_unshift($right, $mid);
            $p = $right;
        }
        return $p;
    } else {
        $p = [];
    }
    return $p;
    

    
}

do {
    $n_loop ++;
    // echo 'loop '. $n_loop .'<br>';
    for ($K = 1; $K <= $traveler ; $K++) { 
        $start = 6;
        $w = [];
        $w[] = $start;
        $cost = 0;
        $visities = [3, 1, 4];
        $not_yet_visited = array_values(array_diff($visities, $w));
        // var_dump($not_yet_visited);die;
        
        
        while(!empty($not_yet_visited) && $start){
            $next = point($start, $not_yet_visited, $t, $distanc);
            
            $cost = $cost + $distanc[ $start ][ $next ];
            $w[] = $next;
            $start = $next;
            $not_yet_visited = array_values(array_diff($not_yet_visited, [$next]));
        }
        if (!$cost > 0) continue;
        if ($commback){
            $cost = $cost + $distanc[ $start ][ $last ];
        }
        
    
        if ($cost < $cost_best) {
            $cost_best = $cost;
            $best_router = $w;
            
            if ($commback){
                array_push($best_router, $last);
            }
            $stop_loop = 2000;
        } else {
            $stop_loop --;
            if ($stop_loop <= 0) break 2;
        }
        $full_router = [$w[0]];
        for ($i=1; $i < count($w); $i++) { 
            $next = $w[$i];
                $p = path($w[$i - 1], $next, $td);
                if(!empty($p)){
                    foreach($p as $v){
                        $full_router[] = $v;
                    }
                } 
                $full_router[] = $next;
        }
        for ($i=1; $i < count($full_router); $i++) { 
            $delta[$full_router[$i-1]][$full_router[$i]] = $delta[$full_router[$i-1]][$full_router[$i]] + $q/$cost;
            // $delta[$full_router[$i]][$full_router[$i-1]] = $delta[$full_router[$i-1]][$full_router[$i]];
        }
        
        // echo  ' [kien '. $K . ']: cost -' .$cost_best . '   router: ' . implode('=>', $best_router) . '<br>';
        for($i = 1; $i <= $soDinh; $i ++){
            for($j = 1; $j <= $soDinh; $j ++){
                    if($i == $j) continue;
                    $t[$i][$j] = 0.8 * $t[$i][$j] + $delta[$i][$j];
                    // $t[$j][$i] = $t[$i][$j];
    
            }
        }
    }
    
    
} while ($n_loop < 500);

$full_router = [$best_router[0]];
for ($i=1; $i < count($best_router); $i++) { 
    $next = $best_router[$i];
        $p = path($best_router[$i - 1], $next, $td);
        if(!empty($p)){
            foreach($p as $v){
                $full_router[] = $v;
            }
        } 
        $full_router[] = $next;
}
var_dump($cost_best);
var_dump($best_router);
var_dump($full_router);die;

function point($start, $not_yet_visited, $t, $distanc){
    
    $p = p($start, $not_yet_visited, $t, $distanc);
    
    $r = (float)rand(1, 10) / 10;
    $t = 0;
    $i = 0;

    while (!($t > $r)) {
        $t += $p[$i];
        $i ++;
        if ($i > count($p) - 1) $i = 0;
    }
    return $not_yet_visited[$i];
}
function p($start, $not_yet_visited, $t, $distanc){
    $p = [];
    $sum = [];
    foreach($not_yet_visited as $v){
        if (!isset($t[$start][$v])){var_dump($not_yet_visited);var_dump($start);var_dump($v);die;}
        $sum[] = ($t[$start][$v]  * 1/ $distanc[$start][$v]);
    }
    foreach($sum as $v){
        $p[] = array_sum($sum) == 0? 0: $v/array_sum($sum);
    }
    return $p;
}
    public function actionAnt(){
        $soDinh = 8;//so dinh
        $start = 1;// diem bat dau
        $distanc = [];// luu do dai cac canh
        $t = [];//luu nong do vet
        $delta = [];//luu cap nhat mui
        $w = [];// hanh trinh
        $mark = [];// dinh da tham
        $uv = []; // dinh chua tham
        $max = 1000000;
        $cost_best = 10000000;
        $best_router = [];
        $q = 1;
        $td = [];
        $stop_loop = 2000;
        $n_loop = 0;
        $traveler = 1 * 25;
        $last = 1;
        $commback = true;



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
        $distanc[1][2] = $distanc[2][1] = 2; // HN -> HP 
        $distanc[1][3] = $distanc[3][1] = 1.5; // HN -> VietTri
        $distanc[1][4] = $distanc[4][1] = 2.75;  // HN -> HA LONG
        $distanc[1][5] = $distanc[5][1]= 2; // HN -> Hoa Binh
        $distanc[1][6] = $distanc[6][1]= 2.75; // HN-> LangSon
        $distanc[1][7] = $distanc[7][1]= 6.5; // HN-> SonLa
        $distanc[1][8] = $distanc[8][1]= 3.5; // HN-> Bac Kan

        $distanc[2][3] = $distanc[3][2]= 2.75; // HP -> VietTri
        $distanc[2][4] = $distanc[4][2]= 1.25; // HP -> Halong
        // $distanc[2][5] = $distanc[5][2]= 3.25; // HP -> Hoa binh
        // $distanc[2][6] = $distanc[6][2]= 3.5; // HP -> LangSon
        $distanc[2][7] = $distanc[7][2]= 7.75; // HP -> Sơn La
        $distanc[2][8] = $distanc[8][2]= 3.75; // HP -> bac Kan

        $distanc[3][4] = $distanc[4][3]= 3; // Viet Tri -> Ha Long
        $distanc[3][5] = $distanc[5][3]= 1.75; // Viet Tri => Hoa Binh
        // $distanc[3][6]= $distanc[6][3]= 3; // Viet Tri => Lang Son
        // $distanc[3][7]= $distanc[7][3]= 5.5; // Viet Tri => Son LA
        $distanc[3][8]= $distanc[8][3]= 3; // Viet Tri => Bac Kan

        $distanc[4][5] = $distanc[5][4]= 3.2; // Ha Long => Hoa Binh
        // $distanc[4][6] = $distanc[6][4]= 3.4; // Ha Long => Lang son
        // $distanc[4][7]= $distanc[7][4]= 8.2; // Ha Long => son La
        $distanc[4][8]= $distanc[8][4]= 4.2; // Ha Long => bac Kan 

        $distanc[5][6] = $distanc[6][5]= 3.75; // Hoa Binh => Lang Son
        // $distanc[5][7] = $distanc[7][5]= 5; // Hoa Binh => Son La
        $distanc[5][8]= $distanc[8][5]= 4; //Hoa binh => bac kan

        $distanc[6][7] = $distanc[7][6]= 8.5; // Lang Son => Son La
        $distanc[6][8] = $distanc[8][6]= 4; // LangSon => Bac Kan

        $distanc[7][8] = $distanc[8][7]= 8.5; // Sơn La => Bac Kan

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
        function path($start, $end, $td){
            $p = [];
            $mid = $td[$start][$end];
            if($mid !== -1){
                $p[] = $mid;
                
                $left = [];
                $right = [];
                if ($td[$start][$mid] !== -1){
                    $left = path($start, $mid, $td);
                }
                
                if ($td[$mid][$end] !== -1){
                    $right = path($mid, $end, $td);
                }
                
                if (!empty($left)) {
                    $p = array_push($left, $mid);
                    $p = $left;
                }
                if (!empty($right)) {
                    array_unshift($right, $mid);
                    $p = $right;
                }
                return $p;
            } else {
                $p = [];
            }
            return $p;
            

            
        }
        function point($start, $not_yet_visited, $t, $distanc){
            
            $p = p($start, $not_yet_visited, $t, $distanc);
            
            $r = (float)rand(1, 10) / 10;
            $t = 0;
            $i = 0;

            while (!($t > $r)) {
                $t += $p[$i];
                $i ++;
                if ($i > count($p) - 1) $i = 0;
            }
            return $not_yet_visited[$i];
        }
        function p($start, $not_yet_visited, $t, $distanc){
            $p = [];
            $sum = [];
            foreach($not_yet_visited as $v){
                if (!isset($t[$start][$v])){var_dump($not_yet_visited);var_dump($start);var_dump($v);die;}
                $sum[] = ($t[$start][$v]  * 1/ $distanc[$start][$v]);
            }
            foreach($sum as $v){
                $p[] = array_sum($sum) == 0? 0: $v/array_sum($sum);
            }
            return $p;
        }
        do {
            $n_loop ++;
            // echo 'loop '. $n_loop .'<br>';
            for ($K = 1; $K <= $traveler ; $K++) { 
                $start = 1;
                $w = [];
                $w[] = $start;
                $cost = 0;
                $visities = [2,3,4,5,6,7,8];
                $not_yet_visited = array_values(array_diff($visities, $w));
                // var_dump($not_yet_visited);die;
                
                
                while(!empty($not_yet_visited) && $start){
                    $next = point($start, $not_yet_visited, $t, $distanc);
                    
                    $cost = $cost + $distanc[ $start ][ $next ];
                    $w[] = $next;
                    $start = $next;
                    $not_yet_visited = array_values(array_diff($not_yet_visited, [$next]));
                }
                if (!$cost > 0) continue;
                if ($commback){
                    $cost = $cost + $distanc[ $start ][ $last ];
                }
                
            
                if ($cost < $cost_best) {
                    $cost_best = $cost;
                    $best_router = $w;
                    
                    if ($commback){
                        array_push($best_router, $last);
                    }
                    $stop_loop = 2000;
                } else {
                    $stop_loop --;
                    if ($stop_loop <= 0) break 2;
                }
                $full_router = [$w[0]];
                for ($i=1; $i < count($w); $i++) { 
                    $next = $w[$i];
                        $p = path($w[$i - 1], $next, $td);
                        if(!empty($p)){
                            foreach($p as $v){
                                $full_router[] = $v;
                            }
                        } 
                        $full_router[] = $next;
                }
                for ($i=1; $i < count($full_router); $i++) { 
                    $delta[$full_router[$i-1]][$full_router[$i]] = $delta[$full_router[$i-1]][$full_router[$i]] + $q/$cost;
                    // $delta[$full_router[$i]][$full_router[$i-1]] = $delta[$full_router[$i-1]][$full_router[$i]];
                }
                
                // echo  ' [kien '. $K . ']: cost -' .$cost_best . '   router: ' . implode('=>', $best_router) . '<br>';
                for($i = 1; $i <= $soDinh; $i ++){
                    for($j = 1; $j <= $soDinh; $j ++){
                            if($i == $j) continue;
                            $t[$i][$j] = 0.8 * $t[$i][$j] + $delta[$i][$j];
                            // $t[$j][$i] = $t[$i][$j];
            
                    }
                }
            }
            
            
        } while ($n_loop < 500);

        $full_router = [$best_router[0]];
        for ($i=1; $i < count($best_router); $i++) { 
            $next = $best_router[$i];
                $p = path($best_router[$i - 1], $next, $td);
                if(!empty($p)){
                    foreach($p as $v){
                        $full_router[] = $v;
                    }
                } 
                $full_router[] = $next;
        }
        var_dump($cost_best);
        var_dump($best_router);
        var_dump($full_router);
        die;

        
        
    }

?>