<?php
$date = date("Ymd"); // 获取当前日期

if (!file_exists("log/$date.MDB")) {
    // 创建新文件并写入初始值
    $android_num = 0;
    $ios_num = 0;
} else {
    // 更新已存在文件中的值
    $contents = file_get_contents("log/$date.MDB");
    $matches = [];
    preg_match('/iOS: (\d+)/', $contents, $matches);
    $ios_num = intval($matches[1]);
    preg_match('/Android: (\d+)/', $contents, $matches);
    $android_num = intval($matches[1]);
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'Android') !== false) {
    // 安卓客户端
    $android_num++;
} elseif (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false) {
    // 苹果客户端
    $ios_num++;
}

// 更新文件内容
$file_content = "iOS: $ios_num\n";
$file_content .= "Android: $android_num\n";
file_put_contents("log/$date.MDB", $file_content);

?>
