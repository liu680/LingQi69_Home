<?php

function randomString($length = 10) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = '';
    for ($i = 0; $i < $length; $i++) { 
        $randomString .= $characters[rand(0, strlen($characters) - 1)]; 
    } 

    $randomString = "GA".$randomString;

    return $randomString; 
}

if (count($argv) < 2) {
    echo "Please input arg\n";
    return;
}

$path = "redeem_code";
$str = file_get_contents($path);
$json = json_decode($str, true);

//统计
if ($argv[1] == '-s') {
    $total = count($json);
    $sold = 0;
    $redeemed = 0;
    foreach ($json as $code => $item) {
        if (strlen($item['udid']) > 0) {
            $redeemed++;
        }

        if (boolval($item['sale']) || strlen($item['udid']) > 0) {
            $sold++;
        }
    }
    
    echo "兑换码总数: $total, 已启用: $sold, 已兑换: $redeemed\n";
    return;
}
//输出
else if ($argv[1] == '-o') {
    $oCount = intval($argv[2]);

    if ($oCount <= 0) {
        return;
    }

    $c = 0;
    foreach ($json as $code => $item) {
        if (!boolval($item['sale']) && !$item['udid']) {
            echo $code."\n";
            $json[$code]['sale'] = true;

            $c++;
            if ($c == $oCount) {
                break;
            }
        }
    }

    $text = json_encode($json);
    file_put_contents($path, $text);
    return;
}

$count = intval($argv[1]);

if (count($json) == 0) {
    $json = array();
}

for ($i = 0; $i < $count; $i++) {
    
    do {
        $code = randomString(16);
        $exist = key_exists($code, $json);
    } while($exist);

    $json[$code] = array(
        'udid' => '',
        'sale' => false,
        'redeem_time' => '',
        'gen_time' => date('Y-m-d H:i:s'),
    );
}

$text = json_encode($json);
file_put_contents($path, $text);


