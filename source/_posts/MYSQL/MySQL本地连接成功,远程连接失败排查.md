---
title: 'MySQL本地连接成功,远程连接失败排查'
date: 2018-03-29 10:47:04
tags:
-MySql
categories:
-MySql
---
# MySQL本地连接成功,远程连接失败排查

1.查看服务器安全组设置以及防火墙规则

2.查看my.cnf bind_address设置

3.查看账户是否有远程连接的权限

4.GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'youpassword' WITH GRANT OPTION;

