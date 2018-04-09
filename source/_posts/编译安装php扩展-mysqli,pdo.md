---
title: '编译安装php扩展-mysqli,pdo'
date: 2018-04-08 08:59:52
tags:
-php
categories:
-php
---

<ul>
<li><a href="#mysqli">编译安装mysqli扩展</a></li>
<li><a href="#pdo">编译安装pdo扩展</a></li>
<li><a href="#contact">php与mysql如何联系起来?</a></li>
</ul>

<h2 id="mysqli">编译安装mysqli扩展</h2>

(1)切换到php扩展源码目录

    cd /usr/local/src/php-7.17/ext/
    
(2)进入mysqli目录

    cd mysqli
    
(3)phpize生成configure文件(phpize是一个shell脚本,用于生成PECL扩展的configure文件)

    /usr/local/php/bin/phpize

(4)查看php-config位置

    updatedb && locate php-config
    which php-config
    find / -name "*php-config*"
    
(5)生成Makefile 

    updatedb && locate mysql_config
    
    ./configure --with-php-config=/usr/local/php/bin/php-config --with-mysqli=/usr/local/mysql/bin/mysql_config
    
    /usr/local/mysql/bin/mysql_config指的是用来寻找MySQL配置文件位置的脚本
    
(6)

    make
    
    make test
    
    make install

 (7)查看扩展目录位置
 
    php -i|grep extensions
 
 (8)查看是否生成了扩展相关的so文件
 
         ls /usr/local/php/lib/php/extensions/no-debug-non-zts-20160303/
         
 (9)查看php.ini文件位置
 
        php --ini
        
        php -i|egrep "*.ini"
        
 (10)编辑php.ini
    
        extension_dir=/usr/local/php7/lib/php/extensions/debug-non-zts-20151012/
        
        extension=mysqli.so
        
(11)重启php-fpm

        service php-fpm restart
        
(12)检查是否安装成功
    
         php -m|grep mysqli
         
make时,可能出现的两个错误:

错误一:

/usr/local/src/php-7.1.7/ext/mysqli/mysqli_api.c:36:47: 致命错误：ext/mysqlnd/mysql_float_to_double.h：没有那个文件或目录

原因:

include "ext/mysqlnd/mysql_float_to_double.h"
这是因为当前是在mysqli这个目录下进行的编译，这个目录下是肯定没有ext/mysqlnd/mysql_float_to_double.h这个目录及文件的

解决方案:

(1)方案一

    cd /tmp/php-7.2.4/ext/
   
    cp mysqlnd/mysql_float_to_double.h  mysqli/
    
    phpize
    
    ./configure --prefix=/usr/local/related/mysqli --with-php-config=/usr/local/php/bin/php-config --with-mysqli=/usr/local/mysql/bin/mysql_config
    
    make && make install

(2)方案二

    updatedb && locate mysql_float_to_double.h
    
    vi /usr/local/src/php-7.17/ext/mysqlimysqli_api.c
把第36行的

    include "ext/mysqlnd/mysql_float_to_double.h"
修改为

    include "/usr/local/src/php-7.17/ext/mysqlnd/mysql_float_to_double.h"
    
(3)方案三

    ln -s mysql/include/* usr/include
    
    ln -s php/include/* usr/include

错误二:

make时如果提示:
Cannot find autoconf. Please check your autoconf installation and the
$PHP_AUTOCONF environment variable. Then, rerun this script.
则说明没有安装antoconf,安装即可：

    cd /usr/local/src
    
    wget http://ftp.gnu.org/gnu/autoconf/autoconf-2.62.tar.gz
    
    tar -zvxf autoconf-2.62.tar.gz
    
    cd autoconf-2.62/
    
    ./configure --prefix=/usr/local/related/autoconf M4=/usr/local/related/m4/bin/m4 
    
    --如果不指定M4的地址，那么需要把M4的bin目录加入环境变量/etc/profile
    
    make && make install

    然后将autoconf/bin加入到环境变量再次执行/usr/local/php/bin/phpize就可以生成configure安装文件了

如果没安装m4则checking for GNU M4 that supports accurate traces... configure: error: no acceptable m4 could be found in $PATH.
GNU M4 1.4.5 or later is required; 1.4.11 is recommended
：

    cd /usr/local/src
    
    wget http://ftp.gnu.org/gnu/m4/m4-1.4.9.tar.gz
    
    tar -zvxf m4-1.4.9.tar.gz
    
    cd m4-1.4.9/
    
    ./configure --prefix=/usr/local/related/m4 
    
    make && make install


或者yum安装这两个文件

    yum install m4
    
    yum install autoconf

<h2 id="pdo">编译安装mysqli扩展</h2>

(1)切换到php扩展源码目录

    cd /usr/local/src/php-7.17/ext/
    
(2)进入pdo_mysql目录

   cd pdo_mysql/
    
(3)phpize生成configure文件(phpize是一个shell脚本,用于生成PECL扩展的configure文件)

    /usr/local/php/bin/phpize

(4)查看php-config位置

    updatedb && locate php-config
    which php-config
    find / -name "*php-config*"
    
(5)生成Makefile 

    ./configure --with-php-config=/usr/local/php7/bin/php-config --with-pdo-mysql=/usr/local/mysql/
    
    /usr/local/mysql/指的是MySQL编译时的prefix
(6)

    make
    
    make test
    
    make install

 (7)查看扩展目录位置
 
    php -i|grep extensions
 
 (8)查看是否生成了扩展相关的so文件
 
         ls /usr/local/php/lib/php/extensions/no-debug-non-zts-20160303/
         
 (9)查看php.ini文件位置
 
        php --ini
        
        php -i|egrep "*.ini"
        
 (10)编辑php.ini
    
        extension_dir=/usr/local/php7/lib/php/extensions/debug-non-zts-20151012/
        
        extension=pdo_mysql.so
        
(11)重启php-fpm

        service php-fpm restart
        
(12)检查是否安装成功
    
         php -m|grep pdo_mysql
 
 <h2 id="contact">php与mysql如何联系起来?</h2> 
 
    vim /usr/local/mysql/etc/my.cnf
        
        [client]
            socket=/var/lib/mysql/mysql.sock
        [mysqld]
            socket=/var/lib/mysql/mysql.sock
    
    vim /usr/local/php-7.1.3/lib/php.ini
        
        pdo_mysql.default_socket=/var/lib/mysql/mysql.sock
        mysqli.default_socket=/var/lib/mysql/mysql.sock
        
Mysql有两种连接方式： 
 
（1）TCP/IP 
 
（2）socket 
 
对mysql.sock来说，其作用是程序与mysqlserver处于同一台机器，发起本地连接时可用。
