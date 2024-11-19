<?php
$date = date("Ymd"); // 获取当前日期
// 读取20230710.MDB文件内容
$contents = file_get_contents("log/$date.MDB");

// 使用正则表达式匹配安卓下载次数
preg_match("/Android: (\d+)/", $contents, $androidMatches);
$androidDownloads = isset($androidMatches[1]) ? $androidMatches[1] : 0;

// 使用正则表达式匹配iOS下载次数
preg_match("/iOS: (\d+)/", $contents, $iosMatches);
$iosDownloads = isset($iosMatches[1]) ? $iosMatches[1] : 0;

// 读取fw_20230710.MDB文件内容
$fwContents = file_get_contents("log/fw_$date.MDB");

// 使用正则表达式匹配安卓访问次数
preg_match("/Android: (\d+)/", $fwContents, $androidVisits);
$androidVisitsCount = isset($androidVisits[1]) ? $androidVisits[1] : 0;

// 使用正则表达式匹配iOS访问次数
preg_match("/iOS: (\d+)/", $fwContents, $iosVisits);
$iosVisitsCount = isset($iosVisits[1]) ? $iosVisits[1] : 0;

// 获取最近七天的日期
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Ymd', strtotime("-$i days"));
    $dates[] = $date;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* 样式表 */
        table {
            width: 100%;
            text-align: center;
            margin: auto;
        }

        th, td {
            padding: 10px;
            width: 50%;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            /* 手机端样式 */
            table {
                width: 100%;
                margin: auto;
            }
        }
        .dates-container {
            text-align: center;
            margin-top: 20px;
        }

        .date-item {
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            background-color: #f2f2f2;
            padding: 8px 15px;
            border-radius: 5px;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .date-item:hover {
            background-color: #dcdcdc;
        }
        .date-item.selected {
      background-color: #818181;
    color: #fff;
}

    </style>
</head>
<body>
    <div style="text-align: center;padding: 20px;font-weight: bold;">开心淘APP下载统计</div>
    <table>
        <tr>
            <th>类型</th>
            <th>次数</th>
        </tr>
        <tr>
    <td>安卓下载次数</td>
    <td id="android-downloads"><?php echo $androidDownloads; ?></td>
</tr>
<tr>
    <td>iOS下载次数</td>
    <td id="ios-downloads"><?php echo $iosDownloads; ?></td>
</tr>
<tr>
    <td>安卓访问次数</td>
    <td id="android-visits"><?php echo $androidVisitsCount; ?></td>
</tr>
<tr>
    <td>iOS访问次数</td>
    <td id="ios-visits"><?php echo $iosVisitsCount; ?></td>
</tr>

    </table>
    <div class="dates-container">
        <?php foreach ($dates as $date): ?>
                <span id="<?php echo $date; ?>" class="date-item" onclick="loadData('<?php echo $date; ?>')"><?php echo $date; ?></span>

        <?php endforeach; ?>
        <span id="total" class="date-item" onclick="calculateStats()">总统计量</span>
        <span id="total" class="date-item" onclick="reloadym()">点击刷新</span>
    </div>
    <script>
  window.addEventListener("DOMContentLoaded", function() {
    var dateItems = document.getElementsByClassName("date-item");

    for (var i = 0; i < dateItems.length; i++) {
        var dateItem = dateItems[i];

        // 添加点击事件监听器以切换选中效果
        dateItem.addEventListener("click", function() {
            // 移除先前选中的日期效果
            for (var j = 0; j < dateItems.length; j++) {
                dateItems[j].classList.remove("selected");
            }

            // 添加选中的日期效果
            this.classList.add("selected");
        });
    }
    
    // 加载当前日期的数据
    loadData(getCurrentDate());
});

function getCurrentDate() {
    var currentDate = new Date().toISOString().split('T')[0];
    return currentDate;
}

function loadData(date) {
    var xhttp = new XMLHttpRequest();

    // 清除先前选中的日期效果
    var dateItems = document.getElementsByClassName("date-item");
    for (var i = 0; i < dateItems.length; i++) {
        dateItems[i].classList.remove("selected");
    }

    // 根据传入的日期参数添加选中的日期效果
    if (date === "total") {
        document.getElementById("total").classList.add("selected");
    } else {
        document.getElementById(date).classList.add("selected");
    }

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            document.getElementById("android-downloads").innerText = data.androidDownloads;
            document.getElementById("ios-downloads").innerText = data.iosDownloads;
            document.getElementById("android-visits").innerText = data.androidVisitsCount;
            document.getElementById("ios-visits").innerText = data.iosVisitsCount;
        } else if (this.readyState == 4 && this.status != 200) {
            alert("暂无数据");
        }
    };
    xhttp.open("GET", "getData.php?date=" + date, true);
    xhttp.send();
}

function calculateStats() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);

            // 设置计算后的总数到相应的元素
            document.getElementById("android-downloads").innerText = data.androidDownloadsTotal;
            document.getElementById("ios-downloads").innerText = data.iosDownloadsTotal;
            document.getElementById("android-visits").innerText = data.androidVisitsTotal;
            document.getElementById("ios-visits").innerText = data.iosVisitsTotal;
            
            // 移除先前选中的日期效果
            var dateItems = document.getElementsByClassName("date-item");
            for (var i = 0; i < dateItems.length; i++) {
                dateItems[i].classList.remove("selected");
            }

            // 添加选中的日期效果
            document.getElementById("total").classList.add("selected");
        } else if (this.readyState == 4 && this.status != 200) {
            alert("暂无数据");
        }
    };
    xhttp.open("GET", "calculateStats.php", true);
    xhttp.send();
}

function reloadym() {

            // 刷新页面
            location.reload();
        }
        
</script>

</body>
</html>
