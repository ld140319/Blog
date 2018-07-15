#MySQL日期函数

##时区设置

MySQL服务器中的三种时区设置：

　　1. 系统时区---保存在系统变量system_time_zone

　　2. 服务器时区---保存在全局系统变量global.time_zone

　　3. 每个客户端连接的时区---保存在会话变量session.time_zone
　　
```
    客户端时区的设置会影响一些日期函数返回值的显示，例如：now()、curtime()、curdate()，也影响timestamp列值的显示。

　　默认情况下，客户端和服务器的时区相同，其值为SYSTEM，表示使用系统时区。
　　
　　select @@global.time_zone,@@session.time_zone;
　　
　　show variables like 'system_time_zone';
```

##常用函数

1.  NOW([fsp])：返回服务器的当前日期和时间(fsp指定小数秒的精度，取值0--6)

    <span style="color:red;">格式：‘YYYY-MM-DD HH:MM:SS’或者‘YYYYMMDDHHMMSS’</span>
    
    ```
        now()的显示格式是‘YYYY-MM-DD HH:MM:SS’
        
        now()+0的显示格式是‘YYYYMMDDHHMMSS’
        
        select now(), now()+0, now(6);
        
        now()函数的同义词有：CURRENT_TIMESTAMP 、 CURRENT_TIMESTAMP()、LOCALTIMESTAMP 、 LOCALTIMESTAMP()、LOCALTIME 、 LOCALTIME()
    ```
    
    |now()|now()+0|now(6)|
    |---|---|---|
    |2018-05-22 23:04:23|20180522230423|2018-05-22 23:04:23.128903|
    
2. 　SYSDATE( )：返回服务器的当前日期和时间

    与now的不同点：(一般使用NOW而不用SYSDATE)
    
       <span style="color:red;">①SYSDATE()返回的是函数执行时的时间</span>
    
       ②now()返回的是语句执行时的时间
    
    ```
    select now(),sleep(2),now();
    ```
    |now()|sleep(2)|now(6)|
    |---|---|---|
    |2018-05-22 23:10:01|0|2018-05-22 23:10:01|
    ```
    select sysdate(),sleep(2),sysdate();
    ```
    |sysdate()|sleep(2)|sysdate(6)|
    |---|---|---|
    |2018-05-22 23:11:16|0|2018-05-22 23:11:18|
    
3. CURDATE()：返回当前日期，只包含年月日

   <span style="color:red;">格式： 　‘YYYY-MM-DD’或者‘YYYYMMDD’</span>
   
   ```
   同义词有： CURRENT_DATE 、CURRENT_DATE()
   ```

　  ```
   select curdate(),curdate()+0;
   ```
   
     |curdate()|curdate()+0|
    |---|---|---|
    |2018-05-22|20180522|
    
4. CURTIME([fsp])：返回当前时间，只包含时分秒(fsp指定小数秒的精度，取值0--6)

   <span style="color:red;">格式: ‘HH:MM:SS’或者‘HHMMSS’</span>
   
   ```
   同义词有：CURRENT_TIME 、 CURRENT_TIME() 
   ```
   ```
   select curtime(),curtime(2);
   ```
   
     |curtime()|curtime(2)|
    |---|---|---|
    |23:15:53|23:15:53.47|
    
5. 选取日期时间的各个部分：日期、时间、年、季度、月、日、小时、分钟、秒、微秒（常用）

```
    SELECT now(),date(now()); -- 日期
    
    SELECT now(),time(now()); -- 时间
    
    SELECT now(),year(now()); -- 年
    
    SELECT now(),quarter(now()); -- 季度
    
    SELECT now(),month(now()); -- 月
    
    SELECT now(),week(now()); -- 周
    
    SELECT now(),day(now()); -- 日
    
    SELECT now(),hour(now()); -- 小时
    
    SELECT now(),minute(now()); -- 分钟
    
    SELECT now(),second(now()); -- 秒
    
    SELECT now(),microsecond(now()); -- 微秒
    
     
    
    EXTRACT(unit  FROM  date)：从日期中抽取出某个单独的部分或组合
    
    SELECT now(),extract(YEAR FROM now()); -- 年
    
    SELECT now(),extract(QUARTER FROM now()); -- 季度
    
    SELECT now(),extract(MONTH FROM now()); -- 月
    
    SELECT now(),extract(WEEK FROM now()); -- 周
    
    SELECT now(),extract(DAY FROM now()); -- 日
    
    SELECT now(),extract(HOUR FROM now()); -- 小时
    
    SELECT now(),extract(MINUTE FROM now()); -- 分钟
    
    SELECT now(),extract(SECOND FROM now()); -- 秒
    
    SELECT now(),extract(YEAR_MONTH FROM now()); -- 年月
    
    SELECT now(),extract(HOUR_MINUTE FROM now()); -- 时分
```

6. 日期时间运算函数：分别为给定的日期date加上(add)或减去(sub)一个时间间隔值expr

    格式：

　　DATE_ADD(date, INTERVAL  expr  unit);

　　DATE_SUB(date, INTERVAL  expr  unit);

    interval是间隔类型关键字
    
    expr是一个表达式，对应后面的类型
    
    unit是时间间隔的单位(间隔类型)（20个），如下：
    
    |关键字|含义|
    |---|---|
    |HOUR|小时|
    |MINUTE|分|
    |SECOND|秒|
    |MICROSECOND|毫秒|
    |YEAR|年|
    |MONTH|月|
    |DAY|日|
    |WEEK|周|
    |QUARTER|季|
    |YEAR_MONTH|年和月|
    |DAY_HOUR|日和小时|
    |DAY_MINUTE|日和分钟|
    |DAY_ SECOND|日和秒|
    |HOUR_MINUTE|小时和分|
    |HOUR_SECOND|小时和秒|
    |MINUTE_SECOND|分钟和秒|
    
    ```
        select now(),date_add(now(),interval 1 day);　　#加一天
    ```
    
    | now() | date_add(now(),interval 1 day) |
    |-------|--------------------------------|
    | 2018-05-22 23:32:42 | 2018-05-23 23:32:42|
    
    ```
    不使用函数，也可以写表达式进行日期的加减：

　　date  + INTERVAL  expr  unit

　　date  - INTERVAL  expr  unit
    ```
    
    ```
        SELECT '2008-12-31 23:59:59' + INTERVAL 1 SECOND;
     ```

    | '2008-12-31 23:59:59' + INTERVAL 1 SECOND |
    |-------------------------------------------|
    | 2009-01-01 00:00:00                       |

7. DATE_FORMAT() 函数用于以不同的格式显示日期/时间数据 
   
   语法: DATE_FORMAT(date,format) date 参数是合法的日期。format 规定日期/时间的输出格式。

|格式|含义|
|----|----|
|%a|缩写星期名|
|%b|	缩写月名|
|%c|    月，数值|
|%D|	带有英文前缀的月中的天|
|%d|	月的天，数值(00-31)|
|%e|	月的天，数值(0-31)|
|%f|	微秒|
|%H|	小时 (00-23)|
|%h|    小时 (01-12)|
|%I|	小时 (01-12)|
|%i|	分钟，数值(00-59)|
|%j|	年的天 (001-366)|
|%k|	小时 (0-23)|
|%l|	小时 (1-12)|
|%M|	月名|
|%m|	月，数值(00-12)|
|%p|	AM 或 PM|
|%r|	时间，12-小时（hh:mm:ss AM 或 PM )|
|%S|	秒(00-59)|
|%s|	秒(00-59)|
|%T|	时间, 24-小时 (hh:mm:ss)|
|%U|	周 (00-53) 星期日是一周的第一天|
|%u|	周 (00-53) 星期一是一周的第一天|
|%V|	周 (01-53) 星期日是一周的第一天，与 %X 使用|
|%v|	周 (01-53) 星期一是一周的第一天，与 %x 使用|
|%W|	星期名|
|%w|	周的天 （0=星期日, 6=星期六）|
|%X|	年，其中的星期日是周的第一天，4 位，与 %V 使用|
|%x|	年，其中的星期一是周的第一天，4 位，与 %v 使用|
|%Y|	年，4 位
|%y|    年，2 位

```
SELECT now(), DATE_FORMAT("%Y/%M%D %H:%m:%i", now())
```
| now()               | DATE_FORMAT( now(), "%Y/%m/%d %H:%m:%i") |
|---------------------|------------------------------------------|
| 2018-05-22 23:51:50 | 2018/05/22 23:05:51                      |

9. DATEDIFF(expr1, expr2)：返回两个日期相减（expr1 − expr2 ）相差的天数

```
select datediff('2017-3-24 18:32:59','2016-9-1');
```

| datediff('2017-3-24 18:32:59','2016-9-1') |
|-------------------------------------------|
|                                       204 |

10. TIMEDIFF返回两个TIME或DATETIME值之间的差值。 

    TIMEDIFF(dt1, dt2);
    
TIMEDIFF函数接受两个必须为相同类型的参数，<span style="color:red;">即TIME或DATETIME.</span> TIMEDIFF函数返回表示为时间值的dt1 - dt2的结果。

```
SELECT TIMEDIFF('12:00:00','10:00:00') diff;
```
| diff                                      |
|-------------------------------------------|
| 02:00:00                                  |


```
SELECT TIMEDIFF('2010-01-01 01:00:00', '2010-01-02 01:00:00') diff;
```
| diff                                      |
|-------------------------------------------|
| -24:00:00                                 |

```
如果任一参数为NULL，TIMEDIFF函数将返回NULL。
```

```
SELECT TIMEDIFF('2010-01-01',NULL) diff;
```
| diff                                      |
|-------------------------------------------|
| NULL                                      |


```
如果传递两个不同类型的参数，一个是DATETIME，另一个是TIME，TIMEDIFF函数也返回NULL
```

```
SELECT TIMEDIFF('2010-01-01 10:00:00','10:00:00') diff;
```
| diff                                      |
|-------------------------------------------|
| NULL                                      |

10. 个性化时间

```
　　dayofweek(date)

　　dayofmonth(date)

　　dayofyear(date)

    分别返回日期在一周、一月、一年中是第几天
    
    
    dayname()

　　monthname()
    
    分别返回日期的星期和月份名称

名称是中文or英文的由系统变量lc_time_names控制(默认值是'en_US')

set lc_time_names='zh_CN';
```

