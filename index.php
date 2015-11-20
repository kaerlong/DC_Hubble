<?php
require "conn.php";

if (getenv("HTTP_X_FORWARDED_FOR"))
{
    $ip = getenv("HTTP_X_FORWARDED_FOR");
}
elseif (getenv("HTTP_CLIENT_IP"))
{
    $ip = getenv("HTTP_CLIENT_IP");
}
elseif (getenv("REMOTE_ADDR"))
{
    $ip = getenv("REMOTE_ADDR");
}
else
{
    $ip = "Unknown";
}

$get_ip = mysql_escape_string($ip);
$get_type = mysql_escape_string("get_show");
$get_info = mysql_escape_string("OK");
$sqlstr = "insert into ip(type,ip,info) values('".$get_type."','".$get_ip."','".$get_info."')";
mysql_query($sqlstr) or die(mysql_error());
?>
<HTML>
    <BODY bgcolor="#F0F8FF">
        <meta charset="utf-8"/>
            <?php
                require_once("getData.php");
                $rand = new RandomTable();
                
                $lastTmpRaw = $rand->getLastTmpData();
                $lastTmpData = $lastTmpRaw[0][4];
                
                $lastDhtRaw = $rand->getLastDhtData();
                $lastDhtData = $lastDhtRaw[0][5];
                
                $lastRenRaw = $rand->getLastRenData();
                $lastRenData = $lastRenRaw[0][3];
                
                $lastSmkRaw = $rand->getLastSmkData();
                $lastSmkData = $lastSmkRaw[0][3];

                $lastAddRaw = $rand->getLastAddData();
                $lastAddData = $lastAddRaw[0][3];
                
                $accessCountRaw = $rand->getAccessIpCount();
                $accessCount = $accessCountRaw[0][0];
                
                $rawdata = $rand->getDhtData();
                $listDhtData;
                $listDhtTime;
                for($i = 0 ;$i<count($rawdata);$i++){
                    $listDhtData[$i]= $rawdata[$i][5];
                    $listDhtTime[$i] = $rawdata[$i][3];
                }
                
                $rawdata = $rand->getRenData();
                $listRenData;
                $listRenTime;
                for($i = 0 ;$i<count($rawdata);$i++){
                    $listRenData[$i]= $rawdata[$i][4];
                    $time= $rawdata[$i][3];
                    $date = new DateTime($time);
                    $listRenTime[$i] = ($date->getTimestamp())*1000;
                }
                
                $rawdata = $rand->getTmpData();
                $listTmpData;
                $listTmpTime;
                for($i = 0 ;$i<count($rawdata);$i++){
                    $listTmpData[$i]= $rawdata[$i][4];
                    $listTmpTime[$i] = $rawdata[$i][3];
                }
            ?>
    <table width = '100%'>
    <tr>
        <td width = '33%'>
                <div id="lastTmp" style="height:300px;width:300px;"></div>
        </td>
            <td width = '33%'>
                <div id="lastDht" style="height:300px;width:300px;"></div>
            </td>
            <td  width = '33%' style="border-left:5px solid; border-left-color:red;">
                <div id="block1" style="height:20px;width:300px;color:red;font-size:25px;"></div>
                <div id="accessCountText" style="height:30px;width:300px;color:green;font-size:12px;font-weight:blod;"><b><?php echo '您是第 ', $accessCount, ' 位访问者；您的IP：', $ip;?></b></div>
                <div id="lastSmkText" style="height:40px;width:300px;color:red;font-size:30px;font-weight:blod;"><b><?php echo '最后烟雾报警时间：' ?></b></div>
                <div id="lastSmkTime" style="height:70px;width:300px;color:red;font-size:35px;font-weight:blod;"><b><?php echo $lastSmkData ?></b></div>
                <div id="block2" style="height:50px;width:300px;color:black;font-size:25px;">-----------------------</div>
                <div id="lastRenText" style="height:40px;width:300px;color:red;font-size:30px;font-weight:blod;"><b><?php echo '最后人员出现时间：' ?></b></div>
                <div id="lastRenTime" style="height:70px;width:300px;color:red;font-size:35px;font-weight:blod;"><b><?php echo $lastRenData ?></b></div>
                <div id="block4" style="height:50px;width:300px;color:black;font-size:25px;">-----------------------</div>
                <div id="lastAddText" style="height:40px;width:300px;color:blue;font-size:30px;font-weight:blod;"><b><?php echo '最后数据接收时间：' ?></b></div>
                <div id="lastAddTime" style="height:70px;width:300px;color:blue;font-size:35px;font-weight:blod;"><b><?php echo $lastAddData ?></b></div>
            </td>
        </tr>
    </table>
    <div id="ren" style="height:300px"></div>
    <div id="ren_" style="height:30px"></div>
    <div id="tmp" style="height:300px"></div>
    <div id="tmp_" style="height:10px"></div>
    <div id="dht" style="height:300px"></div>
    <div id="dht_" style="height:20px"></div>
    <script src="./echarts/build/source/echarts.js"></script>
    <script src="./js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript">
        var renLastDate = '<?php echo $lastRenData ?>';
        var smkLastDate = '<?php echo $lastSmkData ?>';
        var addLastDate = '<?php echo $lastAddData ?>';
        var obj;
        setInterval(function (){
            $.ajax({
                type: "get",
                url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&type=add",
                success: function (data) {
                    if (data != null){
                        obj = JSON.parse(data);
                        document.getElementById('lastAddTime').innerHTML = obj.add;
                        addLastDate = obj.add;
                        if (((new Date()).getTime() - (new Date(addLastDate)).getTime()) > 300000) {
                            alert("警告！！！ \n##服务器长时间未接收数据！\n\n##时间间隔：" + ((new Date()).getTime() - (new Date(addLastDate)).getTime())/1000 + " 秒");
                        }
                    }
                },
                filed:function (data) {
                    alert("get add faile!");
                }
            });
            $.ajax({
                type: "get",
                    url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&type=ren",
                    success: function (data) {
                        if (data != null){
                            obj = JSON.parse(data);
                            if (obj.ren != renLastDate){
                                document.getElementById('lastRenTime').innerHTML = obj.ren;
                                renLastDate = obj.ren;
                                //alert("警告！！！ \n##有人进入检测环境！\n\n##进入时间：" + renLastDate);
                            }
                        }
                    },
                    filed:function (data) {
                        alert("get ren faile!");
                    }
            });
            $.ajax({
                type: "get",
                    url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&type=smk",
                    success: function (data) {
                        if (data != null){
                            obj = JSON.parse(data);
                            if (obj.smk != smkLastDate){
                                document.getElementById('lastSmkTime').innerHTML = obj.smk;
                                smkLastDate = obj.smk;
                                alert("警告！！！ \n##检测环境烟雾超标！\n\n##时间：" + renLastDate);
                            }
                        }
                    },
                    filed:function (data) {
                        alert("get smk faile!");
                    }
            });
        },13000);
        
        require.config({
            paths: {
                echarts: './echarts/build/dist'
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/gauge'
            ],
            function (ec) {
                var lastTmpChart = ec.init(document.getElementById('lastTmp')); 
                var option = {
                    tooltip : {
                        formatter: "{a} <br/>{b} : {c}°C"
                    },
                    series : [
                        {
                            name:'实时温度',
                            type:'gauge',
                            detail : {formatter:'{value}°C'},
                            data:[{value: <?php echo $lastTmpData;?>, name: '温度'}]
                        }
                    ]
                };
                timeTicket = setInterval(function (){
                    $.ajax({
                        type: "get",
                        url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&type=tmp",
                        success: function (data) {
                            if (data != null){
                                obj = JSON.parse(data);
                                option.series[0].data[0].value = parseFloat(obj.tmp).toFixed(1);
                                lastTmpChart.setOption(option, true);
                                if (parseFloat(obj.tmp).toFixed(1) > 35) {
                                    alert("警告！！！ \n##检测环境温度过高！\n\n##温度为：" + obj.tmp + " 摄氏度");
                                }
                                else if (parseFloat(obj.tmp).toFixed(1) < 10) {
                                    alert("警告！！！ \n##检测环境温度过低！\n\n##温度为：" + obj.tmp + " 摄氏度");
                                }
                            }
                        },
                        filed:function (data) {
                            alert("get tmp faile!");
                        }
                    });
                },17000);
                lastTmpChart.setOption(option); 
            }
        );
    </script>
    <script type="text/javascript">
        require.config({
            paths: {
                echarts: './echarts/build/dist'
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/gauge'
            ],
            function (ec) {
                var lastDhtChart = ec.init(document.getElementById('lastDht')); 
                var timeArray = [];
                var option = {
                    tooltip : {
                        formatter: "{a} <br/>{b} : {c}%H"
                    },
                    series : [
                        {
                            name:'实时湿度',
                            type:'gauge',
                            detail : {formatter:'{value} %H'},
                            data:[{value: <?php echo $lastDhtData?>, name: '湿度'}]
                        }
                    ]
                };
                timeTicket = setInterval(function (){
                    $.ajax({
                        type: "get",
                        url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&type=dht",
                        success: function (data) {
                            if (data != null){
                                var obj = JSON.parse(data);
                                option.series[0].data[0].value = parseFloat(obj.dht).toFixed(0);
                                lastDhtChart.setOption(option, true);
                                if (parseFloat(obj.dht).toFixed(0) > 80) {
                                    alert("警告！！！ \n##检测环境湿度过高！\n\n##湿度为：" + parseFloat(obj.dht).toFixed(0) + " %H");
                                }
                                else if (parseFloat(obj.dht).toFixed(0) < 15) {
                                    alert("警告！！！ \n##检测环境湿度过低！\n\n##湿度为：" + parseFloat(obj.dht).toFixed(0) + " %H");
                                }
                            }
                        },
                        filed:function (data) {
                            alert("get dht faile!");
                        }
                    });
                },19000);
                lastDhtChart.setOption(option); 
            }
        );
    </script>
    <script type="text/javascript">
        require.config({
            paths: {
                echarts: './echarts/build/dist'
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/scatter'
            ],
            function (ec) {
                var renChart = ec.init(document.getElementById('ren')); 
                var timeArray = [];
                <?php
                    for($i = 0 ;$i<count($listRenTime);$i= $i+1){
                ?>
                timeArray.push(<?php echo $listRenTime[$i]; ?>);
                <?php } ?>
                var option = {
                    title : {
                        text : '人员分布散列图',
                        subtext : '实时数据'
                    },
                    tooltip : {
                        trigger: 'axis',
                        axisPointer:{
                            show: true,
                            type : 'cross',
                            lineStyle: {
                                type : 'dashed',
                                width : 1
                            }
                        }
                    },
                    dataZoom: {
                        show: true,
                        start : 75,
                        end : 100
                    },
                    legend : {
                        data : ['人员分布']
                    },
                    grid: {
                        y2: 80
                    },
                    xAxis : [
                        {
                            type : 'time',
                            splitNumber:25
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    animation: false,
                    series : [
                        {
                            name:'人员分布',
                            type:'scatter',
                            tooltip : {
                                trigger: 'axis',
                                formatter : function (params) {
                                    var date = new Date(params.value[0]);
                                    return '时间：'
                                           + '（'
                                           + date.getFullYear() + '-'
                                           + (date.getMonth() + 1) + '-'
                                           + date.getDate() + ' '
                                           + date.getHours() + ':'
                                           + date.getMinutes() + ':'
                                           + date.getSeconds()
                                           +  '）<br/>'
                                           + '有人！！';
                                },
                                axisPointer:{
                                    type : 'cross',
                                    lineStyle: {
                                        type : 'dashed',
                                        width : 1
                                    }
                                }
                            },
                            symbolSize: 8,
                            data: (function () {
                                var d = [];
                                var len = 0;
                                while (len++ <= 360) {
                                    d.push([
                                        timeArray[len-1],
                                        1
                                    ]);
                                }
                                return d;
                            })()
                        }
                    ]
                };
                
                renChart.setOption(option);
            }
        );
    </script>
    <script type="text/javascript">
        var tmpLastDate = '<?php echo $listTmpTime[count($listTmpTime) - 1]; ?>';
        require.config({
            paths: {
                echarts: './echarts/build/dist'
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/line'
            ],
            function (ec) {
                var tmpChart = ec.init(document.getElementById('tmp')); 
                var option = {
                    title : {
                        text: '气温变化',
                        subtext: '实时数据'
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['气温']
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            boundaryGap : true,
                            data : (function() {
                                var data = [];
                                <?php
                                    for($i = 0 ;$i<count($listTmpTime);$i= $i+1){
                                ?>
                                data.push("<?php echo $listTmpTime[$i]; ?>");
                                <?php } ?>
                                return data;
                            })()
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value',
                            axisLabel : {
                                formatter: '{value} °C'
                            },
                            splitNumber: 5
                        }
                    ],
                    series : [
                        {
                            name:'气温',
                            type:'line',
                            itemStyle: {
                                normal: {
                                    color: 'green'
                                }
                            },

                            data:(function() {
                                var data = [];
                                <?php
                                    for($i = 0 ;$i<count($listTmpData);$i= $i+1){
                                ?>
                                data.push(<?php echo $listTmpData[$i];?>);
                                <?php } ?>
                                return data;
                            })(),
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            }
                        }
                    ]
                };
                            
                setInterval(function (){
                    $.ajax({
                        type: "get",
                        url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&date=" + tmpLastDate + "&type=tmpAfter",
                        success: function (data) {
                            if (data != null){
                                var obj = JSON.parse(data);
                                for (var one in obj){
                                    tmpLastDate = obj[one].time;
                                    tmpChart.addData([
                                        [
                                            0,
                                            parseFloat(obj[one].value).toFixed(2),
                                            false,
                                            false,
                                            obj[one].time
                                        ]
                                    ]);
                                }
                            }
                        },
                        filed:function (data) {
                            alert("get renAfter faile!");
                        }
                    });
                },23000);
        
                tmpChart.setOption(option); 
            }
        );
    </script>
    <script type="text/javascript">
        var dhtLastDate = '<?php echo $listDhtTime[count($listDhtTime) - 1]; ?>';
        require.config({
            paths: {
                echarts: './echarts/build/dist'
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/line'
            ],
            function (ec) {
                var dhtChart = ec.init(document.getElementById('dht')); 
                var option = {
                    title : {
                        text: '湿度变化',
                        subtext: '实时数据'
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['湿度']
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            boundaryGap : true,
                            data : (function() {
                                var data = [];
                                <?php
                                    for($i = 0 ;$i<count($listDhtTime);$i= $i+1){
                                ?>
                                data.push("<?php echo $listDhtTime[$i]; ?>");
                                <?php } ?>
                                return data;
                            })()
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value',
                            axisLabel : {
                                formatter: '{value} %H'
                            },
                            splitNumber: 5
                        }
                    ],
                    series : [
                        {
                            name:'湿度',
                            type:'line',
                            itemStyle: {
                                normal: {
                                    color: 'blue'
                                }
                            },
                            data:(function() {
                                var data = [];
                                <?php
                                    for($i = 0 ;$i<count($listDhtData);$i= $i+1){
                                ?>
                                data.push(<?php echo $listDhtData[$i];?>);
                                <?php } ?>
                                return data;
                            })(),
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            }
                        }
                    ]
                };
                            
                setInterval(function (){
                    $.ajax({
                        type: "get",
                        url: "http://XXX.XXX.XXX/getnewdata.php?pwd=XXXXXX&date=" + dhtLastDate + "&type=dhtAfter",
                        success: function (data) {
                            if (data != null){
                                var obj = JSON.parse(data);
                                for (var one in obj){
                                    dhtLastDate = obj[one].time;
                                    dhtChart.addData([
                                        [
                                            0,
                                            parseFloat(obj[one].value).toFixed(0),
                                            false,
                                            false,
                                            obj[one].time
                                        ]
                                    ]);
                                }
                            }
                        },
                        filed:function (data) {
                            alert("get renAfter faile!");
                        }
                    });
                },29000);
        
                dhtChart.setOption(option); 
            }
        );
    </script>
    </BODY>
</html>
