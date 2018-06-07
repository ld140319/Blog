# MySQL binlog

## 作用
 
```
    记录数据的修改 --- 增删改
```

## 查看是否启用

```
    show variables like "%log_bin%"
```

## binlog日志类型

```
    （1）Row level
    
　　日志中会记录每一行数据被修改的情况，然后在slave端对相同的数据进行修改。
　　优点：能清楚的记录每一行数据修改的细节
　　缺点：数据量太大
　　
　　（2）Statement level（默认）
　　
　　每一条被修改数据的sql都会记录到master的bin-log中，slave在复制的时候sql进程会解析成和原来master端执行过的相同的sql再次执行
　　优点：解决了 Row level下的缺点，不需要记录每一行的数据变化，减少bin-log日志量，节约磁盘IO，提高新能
　　缺点：容易出现主从复制不一致(时间函数等)
　　
　　（3）Mixed（混合模式）
　　
　　结合了Row level和Statement level的优点
　　
　　2.1 Statement 
每一条会修改数据的sql都会记录在binlog中。

优点：不需要记录每一行的变化，减少了binlog日志量，节约了IO，提高性能。

缺点：由于记录的只是执行语句，为了这些语句能在slave上正确运行，因此还必须记录每条语句在执行的时候的一些相关信息，以保证所有语句能在slave得到和在master端执行时候相同 的结果。另外mysql 的复制,像一些特定函数功能，slave可与master上要保持一致会有很多相关问题。

ps：相比row能节约多少性能与日志量，这个取决于应用的SQL情况，正常同一条记录修改或者插入row格式所产生的日志量还小于Statement产生的日志量，但是考虑到如果带条件的update操作，以及整表删除，alter表等操作，ROW格式会产生大量日志，因此在考虑是否使用ROW格式日志时应该跟据应用的实际情况，其所产生的日志量会增加多少，以及带来的IO性能问题。

2.2 Row

5.1.5版本的MySQL才开始支持row level的复制,它不记录sql语句上下文相关信息，仅保存哪条记录被修改。

优点： binlog中可以不记录执行的sql语句的上下文相关的信息，仅需要记录那一条记录被修改成什么了。所以rowlevel的日志内容会非常清楚的记录下每一行数据修改的细节。而且不会出现某些特定情况下的存储过程，或function，以及trigger的调用和触发无法被正确复制的问题.

缺点:所有的执行的语句当记录到日志中的时候，都将以每行记录的修改来记录，这样可能会产生大量的日志内容。

ps:新版本的MySQL中对row level模式也被做了优化，并不是所有的修改都会以row level来记录，像遇到表结构变更的时候就会以statement模式来记录，如果sql语句确实就是update或者delete等修改数据的语句，那么还是会记录所有行的变更。

2.3 Mixed

从5.1.8版本开始，MySQL提供了Mixed格式，实际上就是Statement与Row的结合。

在Mixed模式下，一般的语句修改使用statment格式保存binlog，如一些函数，statement无法完成主从复制的操作，则采用row格式保存binlog，MySQL会根据执行的每一条具体的sql语句来区分对待记录的日志形式，也就是在Statement和Row之间选择一种。

```

## MySQL企业binlog模式的选择

```
互联网公司使用MySQL的功能较少（不用存储过程、触发器、函数），选择默认的Statement level

用到MySQL的特殊功能（存储过程、触发器、函数）则选择Mixed模式

用到MySQL的特殊功能（存储过程、触发器、函数），又希望数据最大化一直则选择Row模式

```

## mysqlbinlog解析工具

```
    将Mysql的binlog日志转换成Mysql语句，默认情况下binlog日志是二进制文件，无法直接查看
```

|参数|描述|
|----|----|
|-d|指定库的binlog|
|-r|相当于重定向到指定文件|
|--start-position  --stop-position|	按照指定位置精确解析binlog日志（精确），如不接--stop-positiion则一直到binlog日志结尾|
|--start-datetime  --stop-datetime|	按照指定时间解析binlog日志（模糊，不准确），如不接--stop-datetime则一直到binlog日志结尾|

## MySQL中设置binlog模式

```
    方式一: set global binlog_format='ROW';
    方式二:
        binlog_format='ROW'
```

## 配置binlog文件

```
    log_bin=ON  
    log_bin_basename=/var/lib/mysql/mysql-bin  
    log_bin_index=/var/lib/mysql/mysql-bin.index  
    

    第一个参数是打开binlog日志
    第二个参数是binlog日志的基本文件名，后面会追加标识来表示每一个文件
    第三个参数指定的是binlog文件的索引文件，这个文件管理了所有的binlog文件的目录
    
    log-bin=/var/lib/mysql/mysql-bin
    
    这一个参数的作用和上面三个的作用是相同的，mysql会根据这个配置自动设置log_bin为on状态，自动设置log_bin_index文件为你指定的文件名后跟.index

    这些配置完毕之后对于5.7以下版本应该是可以了，但是我们这个时候用的如果是5.7及以上版本的话，重启mysql服务会报错。这个时候我们必须还要指定一个参数

    server-id=123454  
```

## binlog日志管理

```
    1. 查看当前正在写入的binlog文件
    
        show master status
        
    2. 获取binlog文件列表
        
        show binary logs;
        
    3. 登录到mysql查看binlog, 只查看第一个binlog文件的内容
    
         show binlog events;
         
    4. 查看指定binlog文件的内容
    
         show binlog events in 'mysql-bin.000002';
        
    5. 重置binlog日志(会将日志文件删除，重置index文件 生产环境不要执行)
    
        reset master
        
        reset slave;    //删除slave的中继日志
        
    6. 关闭当前的二进制日志文件并创建一个新文件，新的二进制日志文件的名字在当前的二进制文件的编号上加1(误操作后，应该立即执行此语句)
    
        flush logs
```

## 数据恢复实例

```
    1. 直接恢复
    
    mysqlbinlog --start-position=219 --stop-position=1008 -d school_activity /usr/local/mysql/data/mysql-bin.000001|mysql -u root -pliuzeming
    
    2. 恢复为SQL语句
    
    mysqlbinlog --start-position=219 --stop-position=977 -d school_activity -r school_activity_log.sql  /usr/local/mysql/data/mysql-bin.000001
    
    mysql -u root -pliuzeming < school_activity_log.sql
    
    注意事项: 数据恢复时， 数据更改的时间仍然会以实际修改时间为准 不会以恢复时间为准， 但是新的binlog文件的position会被更新，执行原来的语句时将产生新的记录
```