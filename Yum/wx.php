<?php

error_reporting(E_ERROR); 
ini_set("display_errors","Off");

require_once('RNCryptor.class.php');

$data = file_get_contents("php://input");
$password = md5('https://wx.lingqi69.top/Source.json'); //A. 此处必须修改为你的源地址链接

$cryptor = new Decryptor();
$strParams = $cryptor->decrypt($data, $password);
$params = json_decode($strParams, true);

$udid = $params['udid'];                //用户所提交的udid
$pwd = $params['pwd'];                  //用户所提交的密码
$timestamp = $params['timestamp'];

//debug
// $udid = $argv[1];
// $pwd = $argv[2];
// $timestamp = time();

$status = false;
if ($udid && $pwd && $timestamp) {
    //Todo
    //验证逻辑: 根据udid和密码判断是否已经授权
    $code = $pwd;
    $path = "./codes/redeem_code";
    $str = file_get_contents($path);
    $codesJson = json_decode($str, true);

    if ($pwd == "restore") {
        foreach ($codesJson as $key => $item) {

            if ($item['udid'] == $udid) {

                $expire = strtotime("6 month", strtotime($item["redeem_time"]));
                if ($expire > time()) {
                    $status = true;
                }

                break;
            }
        }
    }
    else if (array_key_exists($code, $codesJson)) {
        $u = $codesJson[$code]['udid'];
        if ($u) {
            if ($udid == $u) {
                $status = true;
            }
        }
        else {
            $codesJson[$code]['udid'] = $udid;
            $codesJson[$code]['redeem_time'] = date('Y-m-d H:i:s');

            $strJson = json_encode($codesJson);
            file_put_contents($path, $strJson);

            $status = true;
        }
    }


}

// if ($status) {
//     echo 'ok';
// }
// else {
//     echo 'no';
// }

// exit;

//输出在GBox加密后的上锁app键值对的内容 xxx_unlock_kvp.json
if ($status) {
    $path = './wx.json';   //B. 读取存放kvp的文件
    $text = file_get_contents($path);

    $json = array(
        'success' => true,
        'data' => json_decode($text, true),
    );

    echo json_encode($json);
}
//验证失败输出
else {
    $json = array(
        'success' => false,
        'message' => '如已有解锁码请联系QQ：3616420196如无解锁码请点击左上角获取解锁码'
    );

    echo json_encode($json);
}