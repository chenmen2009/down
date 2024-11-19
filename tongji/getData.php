<?php
$date = $_GET['date'];
$filename = "log/$date.MDB";
$fwFilename = "log/fw_$date.MDB";

if (!file_exists($filename) || !file_exists($fwFilename)) {
    $data = [
        'androidDownloads' => 0,
        'iosDownloads' => 0,
        'androidVisitsCount' => 0,
        'iosVisitsCount' => 0
    ];
} else {
    $contents = file_get_contents($filename);
    preg_match("/Android: (\d+)/", $contents, $androidMatches);
    $androidDownloads = isset($androidMatches[1]) ? $androidMatches[1] : 0;
    preg_match("/iOS: (\d+)/", $contents, $iosMatches);
    $iosDownloads = isset($iosMatches[1]) ? $iosMatches[1] : 0;

    $fwContents = file_get_contents($fwFilename);
    preg_match("/Android: (\d+)/", $fwContents, $androidVisits);
    $androidVisitsCount = isset($androidVisits[1]) ? $androidVisits[1] : 0;
    preg_match("/iOS: (\d+)/", $fwContents, $iosVisits);
    $iosVisitsCount = isset($iosVisits[1]) ? $iosVisits[1] : 0;

    $data = [
        'androidDownloads' => $androidDownloads,
        'iosDownloads' => $iosDownloads,
        'androidVisitsCount' => $androidVisitsCount,
        'iosVisitsCount' => $iosVisitsCount
    ];
}

echo json_encode($data);
?>
