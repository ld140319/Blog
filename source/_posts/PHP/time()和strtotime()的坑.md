# time()和strtotime()的坑

__time()获取到的是格林威治时间对应的Unix时间戳,与时区无关__
__strtotime()获取到的是指定日期对应的格林威治时间对应的Unix时间戳,与时区有关__
__date()在格式化时间戳时会自动加上时区的偏移__

```
date_default_timezone_set('Asia/Shanghai'); //设置为东八区上海时间
var_dump(strtotime("now")); //1532960923
var_dump(date('Y-m-d H:i:s'), time()); //2018-07-30 22:28:43 1532960923

date_default_timezone_set('UTC'); //设置为格林威治时间
var_dump(strtotime("now")); //1532960923
var_dump(date('Y-m-d H:i:s'), time()); //2018-07-30 14:28:43 1532960923

date_default_timezone_set('Asia/Shanghai'); //设置为东八区上海时间
var_dump(date('Z')); //28800 获取相对于UTC的偏移
$a = floor((strtotime('2015-02-02 07:00:00')+date('Z')) / 86400 ); //获取UTC 2015-02-01 23:00:00点对应的时间戳+28800
$b = floor((strtotime('2015-02-02 09:00:00')+date('Z')) / 86400 ); //获取UTC 2015-02-02 01:00:00点对应的时间戳+28800
var_dump($b - $a); // 结果 0

//如果不加8个小时 转换为UTC时间后 一个为前一天 一个为后一天
$a = floor((strtotime('2015-02-02 07:00:00')) / 86400 );
$b = floor((strtotime('2015-02-02 09:00:00')) / 86400 );
var_dump($b - $a); // 结果 1

date_default_timezone_set('UTC'); //设置为东八区上海时间
var_dump(date('Z')); // "0"
$a = floor( (strtotime('2015-02-02 07:00:00')+date('Z')) / 86400 ); //获取UTC 2015-02-02 07:00:00点对应的时间戳
$b = floor( (strtotime('2015-02-02 09:00:00')+date('Z')) / 86400 ); //获取UTC 2015-02-02 09:00:00点对应的时间戳
var_dump($b - $a); // 结果 0
$a = floor( (strtotime('2015-02-02 07:00:00')) / 86400 );
$b = floor( (strtotime('2015-02-02 09:00:00')) / 86400 );
var_dump($b - $a); // 结果 0, 因为仅仅相差两个小时

//https://www.cnblogs.com/caly/p/4277760.html

date_default_timezone_set('UTC'); //设置为格林威治时间
var_dump(strtotime("now")); //1532942390
var_dump(date('Y-m-d H:i:s'),time()); //2018-07-30 09:19:50

var_dump(strtotime("2018-07-30 17:20:00")); //1532971200 获取到的是UTC 2018-07-30 17:20:00对应的时间戳
var_dump(date("Y-m-d H:i:s", strtotime("2018-07-30 17:20:00"))); //2018-07-30 17:20:00

date_default_timezone_set('Asia/Shanghai'); //设置为东八区上海时间
var_dump(strtotime("now")); //1532942390
var_dump(date('Y-m-d H:i:s'),time()); //2018-07-30 17:19:50
var_dump(strtotime("2018-07-30 17:20:00")); //1532942400  获取到的是UTC 2018-07-30 8:20:00对应的时间戳
var_dump(date("Y-m-d H:i:s", strtotime("2018-07-30 17:20:00"))); //2018-07-30 17:20:00

//strtotime($create_time) 得到的同样是格林威治时间对应的时间戳

var_dump(1532942400-1532971200); //-28800
```




