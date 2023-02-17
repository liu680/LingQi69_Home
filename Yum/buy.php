<?php
//$url = [
//"https://lingqi69.xyz",
//"https://lingqi69.vip",
//"https://lingqi69.xyz",
//"https://lingqi69.vip",
//"https://lingqi69.xyz",
//"https://lingqi69.vip",
//"https://lingqi69.xyz",
//"https://lingqi69.vip",
//];
//shuffle($url);
//header ('Location: '.$url[0]);



$arr = array(
'https://store.lingqi69.xyz/buy/4',

'https://store.lingqi69.xyz/buy/4',

);

$key = array_rand($arr, 1);

//输出随机内容

// echo $arr[$key];

header('Location: ' . $arr[$key]);

exit;
?>