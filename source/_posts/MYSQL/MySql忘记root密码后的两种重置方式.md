# MySql忘记root密码后的两种重置方式

## 指定init-file

(1)停止MySQL服务

    windows: 任务管理器
    
    linux: service mysql stop或者kill `cat /mysql-data-directory/host_name.pid`

(2)编辑mysql-init.txt

    MySQL 5.7.6 及以后: ALTER USER 'root'@'localhost' IDENTIFIED BY 'MyNewPass';
    
    MySQL 5.7.5 及以前: SET PASSWORD FOR 'root'@'localhost' = PASSWORD('MyNewPass');
    
(3)切换到MySql安装目录下的bin目录

    windows: cd "C:\Program Files\MySQL\MySQL Server 5.7\bin"
    
    linux: cd /usr/local/MySqL/bin
    
(4)启动

    windows: mysqld --init-file=C:\\mysql-init.txt
    
    linux: mysqld --init-file=/home/me/mysql-init &
    
    启动时指定配置文件： 
    
    mysqld --defaults-file="C:\\ProgramData\\MySQL\\MySQL Server 5.7\\my.ini" --init-file=C:\\mysql-init.txt
    
    mysqld --defaults-file="/etc/my.cnf" --init-file=/home/me/mysql-init &
    
注意事项:

    如果重置失败，替换mysql-init.txt内容未以下内容后重试
    
```
UPDATE mysql.user
    SET authentication_string = PASSWORD('MyNewPass'), password_expired = 'N'
    WHERE User = 'root' AND Host = 'localhost';
FLUSH PRIVILEGES;
```
(5) 停止服务， 正常重启

## skip-grant-tables跳过账户验证

(1)停止MySQL服务

    windows: 任务管理器
    
    linux: service mysql stop或者kill `cat /mysql-data-directory/host_name.pid`
    
    
(3)切换到MySql安装目录下的bin目录

    windows: cd "C:\Program Files\MySQL\MySQL Server 5.7\bin"
    
    linux: cd /usr/local/MySqL/bin
    
(4)启动

    windows: mysqld --skip-grant-tables --skip-networking
    
    linux: mysqld --skip-grant-tables --skip-networking &
    
    --skip-networking的作用是拒绝所有远程连接，保证安全
    
(5)连接

    mysql

    FLUSH PRIVILEGES;
    
(6)连接成功以后，在命令行中修改密码

     MySQL 5.7.6 及以后: ALTER USER 'root'@'localhost' IDENTIFIED BY 'MyNewPass';
     
     MySQL 5.7.5 及以前: SET PASSWORD FOR 'root'@'localhost' = PASSWORD('MyNewPass');
     
     如果重置失败:
     
     UPDATE mysql.user
     SET authentication_string = PASSWORD('MyNewPass'), password_expired = 'N'
     WHERE User = 'root' AND Host = 'localhost';
     FLUSH PRIVILEGES;

(7) 停止服务， 正常重启
