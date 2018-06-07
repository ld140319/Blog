# MySQL常见使用

## 表修改

```
    (1) 修改表名
     rename table 原表名 to 新表名;
     
     alter table 原表名 rename 新表名;
     
     #注意事项:
     
     1. 不能使用RENAME TABLE语句来重命名临时表，但可以使用ALTER TABLE语句重命名临时表。
     2. 如果表的名称更改，那么引用表名的应用程序代码也需要更改。 此外，您必须手动调整引用该表的其他数据库对象，如视图，存储过程，触发器，外键约束等
     3. 在安全性方面，我们授予旧表的任何权限必须手动迁移到新表。
     
    (2) 重置自动增量值
        
        方式一: ALTER TABLE table_name AUTO_INCREMENT = value;
        
    #注意事项:
        
        该值必须大于或等于自动增量列的当前最大值
        
        方式二: TRUNCATE TABLE table_name;
        
    (3) 修改表引擎类型
    
        #看你的mysql现在已提供什么存储引擎
        
            show engines;

         #看你的mysql当前默认的存储引擎
        
            show variables like '%storage_engine%';

        #某个表用了什么引擎(在显示结果里参数engine后面的就表示该表当前用的存储引擎):
        
            show create table 表名;
            
        方式一: ALTER TABLE my_table ENGINE=InnoDB
        方式二: mysqldump 导出，导入。这个比较容易操作，直接把导出来的sql文件给改了，然后再导回去。
        方式三: 创建，插入。这个比第一种速度快， 安全性比第二种高，推荐。
        分2步操作
          
            a.创建表,先创建一个和要操作表一样的表，然后更改存储引擎为目标引擎。    
                
                CREATE TABLE my_tmp_table LIKE my_table;
                ALTER TABLE my_tmp_table ENGINE=InnoDB;

            b.插入。为了安全和速度，最好加上事务，并限制id(主键）范围。

                INSERT INTO my_tmp_table SELECT * FROM my_table;
                
    (4) 修改字符集类型
    
    修改表的默认字符集:

        ALTER TABLE table_name DEFAULT CHARACTER SET character_name;
    
    修改表字段的默认字符集:
    
        ALTER TABLE table_name modify field  field_type CHARACTER SET character_name [other_attribute]
        
        ALTER TABLE table_name CHANGE old_field new_field field_type CHARACTER SET character_name [other_attribute]
    
    修改表的默认字符集和所有列的字符集:
    
        ALTER TABLE table_name CONVERT TO CHARACTER SET character_name
    
```
  

### 列修改

```
    #添加列
    
        alter table 表名 add 新列名 约束条件 before/after 旧列名
    
    #删除列
    
        alter table 表名 dorp 列名
    
    #修改列名(仅仅需要列出需要修改的约束)
    
        alter table 表名 change 原列名 新列名  类型约束
    
    #修改列属性(仅仅需要列出需要修改的约束)
    
        alter table 表名 modify 列名 类型约束

```

## 查询技巧

1. 获取表的总行数
   
```
1. 获取单个表的MySQL行计数

    SELECT 表名 as table_name,COUNT(*) as rows FROM 表名;
    
2. 获取多个表的MySQL行计数

    SELECT 表名1 tablename,count(*) as rows form 表名2
    UNION ALL
    SELECT 表名2 tablename,count(*) as rows form 表名2
eg: 
    select "jobs" tablename,count(*) as rows from jobs
    union all
    select "user" tablename,count(*) as rows from user;
```

2. 根据查询结果来创建临时表

```
    create table 表名 查询条件
    
    eg：
    
    create table user_back select * from user_id>5;
    
    create table user_back select user_id from user_id>5;

    INSERT INTO t2(title,note) SELECT title, 'data migration' FROM t1;
```

3. 查看建表语句

```
    show create table 表名
    
    show create table 数据库名.表名
    
    desc 数据库名;
    
    desc 数据库名.表名; 
    
    show databases like "%关键字%"
    
    show tables like "%关键字%"
```

4. 查询mysql数据库（某个数据库）某张表的所有列名；查询某个数据库所有表名

```

    select TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME from  information_schema.columns;
    
    SELECT  table_name FROM information_schema.tables WHERE table_schema like  '%表名关键字%' AND table_type = 'BASE TABLE'
    
    eg: 
        
                DROP PROCEDURE if exists test;
                delimiter ;;
                create procedure test ()
                begin
                SELECT 
                    CONCAT(GROUP_CONCAT(CONCAT('SELECT \'',
                                        table_name,
                                        '\' table_name,COUNT(*) rows FROM ',
                                        table_name)
                                SEPARATOR ' UNION '),
                            ' ORDER BY table_name')
                INTO @sql 
                FROM
                    (SELECT 
                        table_name
                    FROM
                        information_schema.tables
                    WHERE
                        table_schema like '%lara%'
                            AND table_type = 'BASE TABLE') table_list;
                prepare stmt from  @sql;
                EXECUTE stmt;
                deallocate prepare stmt;
                end
                ;;
                DELIMITER ;
                call test()
        
        eg:
        
                DROP PROCEDURE if exists test;
                delimiter ;;
                                create procedure test ()
                                begin
                                SELECT 
                                    CONCAT(GROUP_CONCAT(CONCAT('SELECT \'',
                                                        table_name,
                                                        '\' table_name,COUNT(*) rows FROM ',
                                                        table_name)
                                                SEPARATOR ' UNION '),
                                            ' ORDER BY table_name')
                                INTO @sql 
                                FROM
                                    (SELECT 
                                        table_name
                                    FROM
                                        information_schema.tables
                                    WHERE
                                        table_schema like '%lara%'
                                            AND table_type = 'BASE TABLE') table_list;
                				set @new_sql=concat('select count(*) into                                                   @total from ( ', @sql, ' ) tmp');						
                                prepare stmt from  @new_sql;
                                EXECUTE stmt;
                								select @total;
                                deallocate prepare stmt;
                                end
                                ;;
                 DELIMITER ;
                call test();
        
        eg:
        
            drop procedure if exists test;
            delimiter //
            create procedure test(
                `one_p` varchar(10)
            )
            begin
             
                declare _one_p_like varchar(20);
                declare _nums int;
                 
                create temporary table if not exists temp_test as 
                select '北京市dkdjfkdjf' as address
                union
                select '北京市12121';
                 
                set _one_p_like = concat(one_p, '%');
                 
                SELECT count(address) into _nums from temp_test where address like _one_p_like;
                 
                set @p=CONCAT('select count(address) into @p_num from temp_test where address like', '\'', _one_p_like, '\'');
                PREPARE p_sql FROM @p;
                EXECUTE  p_sql;
                deallocate prepare p_sql;
                 
                select _nums;
                select @p_num;
                 
                drop temporary table if exists temp_test;
                 
            end; //
             
            delimiter ;
             
            call test('北京');

        eg:
        
            CREATE PROCEDURE `test`.`new_procedure` ()
            BEGIN
            -- 需要定义接收游标数据的变量 
              DECLARE a CHAR(16);
              -- 游标
              DECLARE cur CURSOR FOR SELECT i FROM test.t;
              -- 遍历数据结束标志
              DECLARE done INT DEFAULT FALSE;
              -- 将结束标志绑定到游标
              DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
              -- 打开游标
              OPEN cur;
              
              -- 开始循环
              read_loop: LOOP
                -- 提取游标里的数据，这里只有一个，多个的话也一样；
                FETCH cur INTO a;
                -- 声明结束的时候
                IF done THEN
                  LEAVE read_loop;
                END IF;
                -- 这里做你想做的循环的事件
            
                INSERT INTO test.t VALUES (a);
            
              END LOOP;
              -- 关闭游标
              CLOSE cur;
            END
```

5. 如何在MySQL表中找到重复的值

```
1. 在一列中找到重复的值

    SELECT 
    col, 
    COUNT(col)
    FROM
        table_name
    GROUP BY col
    HAVING COUNT(col) > 1;
    
    eg: 
    
        在contacts表中查找具有重复email的所有行
        
        SELECT 
        email, 
        COUNT(email)
        FROM
            contacts
        GROUP BY email
        HAVING COUNT(email) > 1;
        
2.  在多个列中查找重复值  

        SELECT 
        col1, COUNT(col1),
        col2, COUNT(col2),
            ...
        
        FROM
            table_name
        GROUP BY 
            col1, 
            col2, ...
        HAVING 
               (COUNT(col1) > 1) AND 
               (COUNT(col2) > 1) AND 
               ...
        
        eg:
        
        使用first_name，last_name和email列中的重复值在contacts表中查找行
        
        SELECT 
            first_name, COUNT(first_name),
            last_name,  COUNT(last_name),
            email,      COUNT(email)
        FROM
            contacts
        GROUP BY 
            first_name , 
            last_name , 
            email
        HAVING  COUNT(first_name) > 1
            AND COUNT(last_name) > 1
            AND COUNT(email) > 1;
```

3. 比较两个表不同的数据

```
        SELECT pk, c1
        FROM
         (
           SELECT t1.pk, t1.c1
           FROM t1
           UNION ALL
           SELECT t2.pk, t2.c1
           FROM t2
        )  t
        GROUP BY pk, c1
        HAVING COUNT(*) = 1
        ORDER BY pk
        
        注: pk、c1指的是区分两个表是否是否相同的数据列，不一定是同名数据列，可以通过as来设置别名解决
        
        eg:
        
            CREATE TABLE t1(
                id int auto_increment primary key,
                title varchar(255) 
            );
            
            CREATE TABLE t2(
                id int auto_increment primary key,
                title varchar(255),
                note varchar(255)
            );
            
            INSERT INTO t1(title)
            VALUES('row 1'),('row 2'),('row 3');
            
            INSERT INTO t2(title,note)
            SELECT title, 'data migration'
            FROM t1;
            
            SELECT id,title
            FROM (
                SELECT id, title FROM t1
                UNION ALL
                SELECT id,title FROM t2
            ) tbl
            GROUP BY id, title
            HAVING count(*) = 1
            ORDER BY id;
            
            INSERT INTO t2(title,note) VALUES('new row 4','new');
            
            SELECT id,title
            FROM (
                SELECT id, title FROM t1
                UNION ALL
                SELECT id,title FROM t2
            ) tbl
            GROUP BY id, title
            HAVING count(*) = 1
            ORDER BY id;
```

7. 删除单表重复行

```
    CREATE TABLE contacts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        first_name VARCHAR(50) DEFAULT NULL,
        last_name VARCHAR(50) DEFAULT NULL, 
        email VARCHAR(255) NOT NULL
    );

    INSERT INTO contacts (first_name,last_name,email) 
    VALUES ('Carine ','Schmitt','carine.schmitt@yiibai.com'),
           ('Jean','King','jean.king@gmail.com'),
           ('Peter','Ferguson','peter.ferguson@google.com'),
           ('Janine ','Labrune','janine.labrune@qq.com'),
           ('Jonas ','Bergulfsen','jonas.bergulfsen@mac.com'),
           ('Janine ','Labrune','janine.labrune@qq.com'),
           ('Susan','Nelson','susan.nelson@qq.com'),
           ('Zbyszek ','Piestrzeniewicz','zbyszek.piestrzeniewicz@att.com'),
           ('Roland','Keitel','roland.keitel@yahoo.com'),
           ('Julie','Murphy','julie.murphy@yahoo.com'),
           ('Kwai','Lee','kwai.lee@google.com'),
           ('Jean','King','jean.king@qq.com'),
           ('Susan','Nelson','susan.nelson@qq.com'),
           ('Roland','Keitel','roland.keitel@yahoo.com');
           
    SELECT 
    email, COUNT(email)
    FROM
        contacts
    GROUP BY email
    HAVING COUNT(email) > 1;
    
    select * from contacts t1
    inner join contacts t2
    where t1.email = t2.email;
    
    DELETE t1 FROM contacts t1
    INNER JOIN contacts t2 
    WHERE t1.id > t2.id AND t1.email = t2.email;
    
    SELECT 
    email, COUNT(email)
    FROM contacts
    GROUP BY email
    HAVING COUNT(email) > 1;
```

8. 选择第n个最高纪录

```
    (1)子查询
    
    SELECT *
    FROM
        (
            SELECT *
            FROM table_name
            ORDER BY column_name DESC
            LIMIT N
        ) AS tbl
    LIMIT 1;
    
     注: 取整表排序后的第一条数据
     
   (2)利用offset
   
    SELECT  *
    FROM table_name
    ORDER BY column_name DESC
    LIMIT n - 1, 1;
    
    注: 查询返回n-1行之后的第一行，以便获得第n个最高记录。

9. 选择第n个最低纪录    
    
     (1)子查询
    
    SELECT *
    FROM
        (
            SELECT *
            FROM table_name
            ORDER BY column_name ASC
            LIMIT N
        ) AS tbl
    LIMIT 1;
    
     注: 取整表排序后的第一条数据
     
   (2)利用offset
   
    SELECT  *
    FROM table_name
    ORDER BY column_name ASC
    LIMIT n - 1, 1;
    
    注: 查询返回n-1行之后的第一行，以便获得第n个最高记录。
```
10. regexp 正则表达式查询

    ```
        SELECT  column_list
        FROM table_name
        WHERE string_column REGEXP pattern;
        
        eg: 
        
            
            SELECT  productname
            FROM products
            WHERE productname REGEXP '^(A|B|C)'
            ORDER BY productname;
            
            如果要使REGEXP运算符以区分大小写的方式比较字符串，可以使用BINARY运算符将字符串转换为二进制字符串。因为MySQL比较二进制字节逐字节而不是逐字符。 这允许字符串比较区分大小写。
            
            SELECT  productname
            FROM products
            WHERE productname REGEXP BINARY '^(A|B|C)'
            ORDER BY productname;
            
    ```

|元字符|行为|
|------|----|
|^|从字符串的开头匹配|
|$|匹配搜索字符串末尾的位置|
|\||或，常与()一起使用|
|.|匹配任何单个字符|
|[…]|匹配方括号内的任何字符,如[1-9]、[a-zA-Z]|
|[^…]|匹配方括号内未指定的任何字符|
|P1\|P2|匹配p1或p2模式|
|*|匹配前面的字符零次或多次|
|+|匹配前一个字符一次或多次|
|{n}|匹配前几个字符的n个实例|
|{m,n}|从m到n个前一个字符的实例匹配|
|{n，}|匹配前几个字符的至少n个实例|
10. 表复制到新表

```
    CREATE TABLE new_table 
    SELECT col, col2, col3 
    FROM
    existing_table;
    
    首先，MySQL使用CREATE TABLE语句中指定的名称创建一个新表。新表的结构由SELECT语句的结果集定义。 然后，MySQL将来自SELECT语句的数据填充到新表中。

要将部分数据从现有表复制到新表中，请在SELECT语句中使用WHERE子句，如下所示：

    CREATE TABLE new_table 
    SELECT col1, col2, col3 
    FROM existing_table
    WHERE conditions;
    
    CREATE TABLE NOT EXISTS new_table 
    SELECT col1, col2, col3 
    FROM existing_table
    WHERE conditions;
    
    注： 以上方法只是复制表及其数据。它不会复制与表关联的其他数据库对象，如索引，主键约束，外键约束，触发器等。
    
    要从表中复制数据以及表的所有依赖对象，请使用以下语句：

    (1) like 关键字
    
    CREATE TABLE IF NOT EXISTS new_table LIKE existing_table;

    (2)inset table_name select语句
    
    INSERT new_table SELECT * FROM existing_table;
    
    (3)跨库复制
    
        CREATE TABLE destination_db.new_table  LIKE source_db.existing_table;
        
        INSERT destination_db.new_table SELECT * FROM source_db.existing_table;
```

11. 数据库复制

  ```
      mysqldump [OPTIONS] database [tables]
    
      mysqldump [OPTIONS] --databases [OPTIONS] DB1 [DB2 DB3...]
    
      mysqldump [OPTIONS] --all-databases [OPTIONS]
  ```


    ```
    (1)备份单个数据库
    
    mysqldump -uroot -pPassword [database name] > [dump file]

        --opt
    
         如果加上--opt参数则生成的dump文件中稍有不同：
    
         . 建表语句包含drop table if exists tableName
    
         . insert之前包含一个锁表语句lock tables tableName write，insert之后包含unlock tables
         
   (2)跨主机备份

     使用下面的命令可以将host1上的sourceDb复制到host2的targetDb，前提是host2主机上已经创建targetDb数据库：

    mysqldump --host=host1 --opt sourceDb| mysql --host=host2 -C targetDb
        
         -C指示主机间的数据传输使用数据压缩
         
   (3)只备份表结构
   
   mysqldump --no-data --databases mydatabase1 mydatabase2 mydatabase3 > test.dump
   
        --databases指示主机上要备份的数据库
        
    (4)备份所有数据库
    
    mysqldump --all-databases> test.dump
    
    (5)从备份文件恢复数据库
    
    mysql [database name] < [backup file name]
    
    (6)结合Linux的cron命令实现定时备份
    
    比如需要在每天凌晨1:30备份某个主机上的所有数据库并压缩dump文件为gz格式，那么可在/etc/crontab配置文件中加入下面代码行：

        30 1 * * * root mysqldump -u root -pPASSWORD --all-databases | gzip > /mnt/disk2/database_`date '+%m-%d-%Y'`.sql.gz
        
    前面5个参数分别表示分钟、小时、日、月、年，星号表示任意。date '+%m-%d-%Y'得到当前日期的MM-DD-YYYY格式。

注:
    1.gzip在压缩文件时, 默认会变成一个以.gz结尾的文件，不能够指定压缩文件名称，源文件不再存在
    2.在对标准输出到命令行的内容进行压缩时，需要指定一个文件进行保存(重定向)
    
        vim /backup.sh
        
        #!bin/bash
        if [ ! -d /backup] 
        then
        	mkdir -p /backup
        fi
        if [ ! -d /oldbackup] 
        then
        	mkdir -p /oldbackup
        fi
        cd /backup
        echo "You are in backup dir"
        mv backup* /oldbackup
        echo "Old dbs are moved to oldbackup folder"
        Now=`date +"%Y-%m-%d"`
        File=backup-$Now.sql
        mysqldump -u root -pliuzeming --all-databases>$File
        echo "Your database backup successfully completed"
 
        crontab -e30 1 * * * /backup.sh
    ```
# 临时查看执行的SQL

-- 打开sql执行记录功能(打开所有命令执行记录功能general_log, 所有语句: 成功和未成功的.  ) 
    
    set global general_log=on;

-- 输出到表  
    
    set global log_output='TABLE';                           
   
-- 打开慢查询sql记录slow_log     

    set global log_slow_queries=ON;    

-- 慢查询时间限制(秒)

    set global long_query_time=2;                            

-- 未使用索引的语句  

    set global log_queries_not_using_indexes=ON;  
  
-- 查询sql执行记录(-- 所有语句：  成功和未成功的) 

    select * from MySQL.slow_log order by 1; 
    
    select * from mysql.general_log order by 1;      
  
show full PROCESSLIST;  
  
-- 关闭sql执行记录  

    set global general_log=off;
    
    set global log_slow_queries=OFF;
    
设置日志输出方式为文件（如果设置log_output=table的话，则日志结果会记录到名为gengera_log的表中，这表的默认引擎都是CSV）：

root@(none) 09:41:11>set global log_output=file;
Query OK, 0 rows affected (0.00 sec)

设置general log的日志文件路径：

root@(none) 09:45:06>set global general_log_file='/tmp/general.log';
Query OK, 0 rows affected (0.00 sec)

　开启general log：

root@(none) 09:45:22>set global general_log=on;
Query OK, 0 rows affected (0.02 sec)

过一段时间后，关闭general log：

root@(none) 09:45:31>set global general_log=off;

# MySql Show用法

    1. show tables或show tables from database_name; -- 显示当前数据库中所有表的名称。 
    2. show databases; -- 显示mysql中所有数据库的名称。 
    3. show columns from table_name from database_name; 或show columns from database_name.table_name; -- 显示表中列名称。 
    4. show grants for user_name; -- 显示一个用户的权限，显示结果类似于grant 命令。 
    5. show index from table_name; -- 显示表的索引。 
    6. show status; -- 显示一些系统特定资源的信息，例如，正在运行的线程数量。 
    7. show variables; -- 显示系统变量的名称和值。 
    8. show processlist; -- 显示系统中正在运行的所有进程，也就是当前正在执行的查询。大多数用户可以查看他们自己的进程，但是如果他们拥有process权限，就可以查看所有人的进程，包括密码。 
       show full processlist;
    9. show table status; -- 显示当前使用或者指定的database中的每个表的信息。信息包括表类型和表的最新更新时间。 
    10. show privileges; -- 显示服务器所支持的不同权限。 
    11. show create database database_name; -- 显示create database 语句是否能够创建指定的数据库。 
    12. show create table table_name; -- 显示create database 语句是否能够创建指定的数据库。 
    13. show engines; -- 显示安装以后可用的存储引擎和默认引擎。 
    14. show innodb status; -- 显示innoDB存储引擎的状态。 
    15. show logs; -- 显示BDB存储引擎的日志。 
    16. show warnings; -- 显示最后一个执行的语句所产生的错误、警告和通知。 
    17. show errors; -- 只显示最后一个执行语句所产生的错误。 
    18. show [storage] engines; --显示安装后的可用存储引擎和默认引擎。

# 遇到的坑

1. lavavel  DB 不支持 in参数绑定
2. mysql group_concat 最多129个字符串相连接
3. timestamp 传null 会取当前时间 不会设置为null
4. like != 等查询时，null需要结合or进行特殊处理
5. 如果使用ORDER BY子句按升序对结果集进行排序，则MySQL认为NULL值低于其他值，因此，它会首先显示NULL值。
6. 在列上使用唯一约束或UNIQUE索引时，可以在该列中插入多个NULL值。这是非常好的，因为在这种情况下，MySQL认为NULL值是不同的。
