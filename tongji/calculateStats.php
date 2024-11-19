<?php
$logFolder = "log";
$androidDownloadsTotal = 0;
$iosDownloadsTotal = 0;
$androidVisitsTotal = 0;
$iosVisitsTotal = 0;

$files = scandir($logFolder);
foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }

    $filename = "$logFolder/$file";
    $contents = file_get_contents($filename);

    if (strpos($file, 'fw_') === 0) {
        // 访问次数文件
        preg_match("/Android: (\d+)/", $contents, $androidVisits);
        $androidVisitsCount = isset($androidVisits[1]) ? $androidVisits[1] : 0;
        $androidVisitsTotal += $androidVisitsCount;

        preg_match("/iOS: (\d+)/", $contents, $iosVisits);
        $iosVisitsCount = isset($iosVisits[1]) ? $iosVisits[1] : 0;
        $iosVisitsTotal += $iosVisitsCount;
    } else {
        // 下载数文件
        preg_match("/Android: (\d+)/", $contents, $androidMatches);
        $androidDownloads = isset($androidMatches[1]) ? $androidMatches[1] : 0;
        $androidDownloadsTotal += $androidDownloads;

        preg_match("/iOS: (\d+)/", $contents, $iosMatches);
        $iosDownloads = isset($iosMatches[1]) ? $iosMatches[1] : 0;
        $iosDownloadsTotal += $iosDownloads;
    }
}

$data = [
    'androidDownloadsTotal' => $androidDownloadsTotal,
    'iosDownloadsTotal' => $iosDownloadsTotal,
    'androidVisitsTotal' => $androidVisitsTotal,
    'iosVisitsTotal' => $iosVisitsTotal
];

echo json_encode($data);
?>
